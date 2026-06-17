<?php

namespace App\Services;

use App\Models\EmailOtp;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Handles OTP generation, delivery, verification, and security controls.
 */
class EmailOtpService
{
    public function __construct(
        protected NotificationService $notifications,
        protected AuditLogService $auditLog,
    ) {}
    /**
     * Create a new OTP, invalidate prior records for the email, and send it via mail.
     *
     * @return array{success: bool, otp?: EmailOtp, plain_code?: string, message?: string, cooldown?: int}
     */
    public function generateAndSend(string $email, string $purpose, string $ipAddress): array
    {
        $email = $this->sanitizeEmail($email);
        $rateKey = $this->sendRateLimitKey($email, $ipAddress);

        if (RateLimiter::tooManyAttempts($rateKey, (int) config('otp.send_rate_limit', 5))) {
            $seconds = RateLimiter::availableIn($rateKey);

            Log::warning('OTP send rate limit exceeded', [
                'email' => $this->maskEmail($email),
                'ip' => $ipAddress,
                'retry_after' => $seconds,
            ]);

            return [
                'success' => false,
                'message' => 'Too many OTP requests. Please try again in '.$seconds.' seconds.',
            ];
        }

        $latest = $this->findActiveOtp($email, $purpose);
        if ($latest && $latest->resendCooldownRemaining() > 0) {
            return [
                'success' => false,
                'message' => 'Please wait before requesting another code.',
                'cooldown' => $latest->resendCooldownRemaining(),
            ];
        }

        $plainCode = $this->generatePlainCode();
        $sessionToken = Str::random(64);
        $now = Carbon::now();

        $otp = DB::transaction(function () use ($email, $purpose, $plainCode, $sessionToken, $ipAddress, $now) {
            $this->invalidatePreviousOtps($email, $purpose);

            return EmailOtp::create([
                'email' => $email,
                'otp_code' => Hash::make($plainCode),
                'purpose' => $purpose,
                'session_token' => $sessionToken,
                'failed_attempts' => 0,
                'locked_until' => null,
                'last_sent_at' => $now,
                'is_verified' => false,
                'ip_address' => $ipAddress,
                'expires_at' => $now->copy()->addMinutes((int) config('otp.expiry_minutes', 5)),
            ]);
        });

        try {
            $this->notifications->sendOtp($email, $plainCode, $purpose);
        } catch (\Throwable $e) {
            report($e);
            $otp->delete();

            Log::error('OTP email delivery failed', [
                'email' => $this->maskEmail($email),
                'purpose' => $purpose,
            ]);

            return [
                'success' => false,
                'message' => 'We could not send the verification email. Please try again shortly.',
            ];
        }

        RateLimiter::hit($rateKey, (int) config('otp.send_rate_decay_seconds', 60));

        $this->auditLog->logOtpGenerated($email, $purpose, $otp->id, $ipAddress);

        Log::info('OTP generated and sent', [
            'email' => $this->maskEmail($email),
            'purpose' => $purpose,
            'otp_id' => $otp->id,
            'expires_at' => $otp->expires_at->toIso8601String(),
        ]);

        return [
            'success' => true,
            'otp' => $otp,
            'plain_code' => $plainCode,
        ];
    }

    /**
     * Validate a submitted OTP against the active database record.
     *
     * @return array{success: bool, message?: string, otp?: EmailOtp, locked?: bool, attempts_remaining?: int}
     */
    public function verify(string $email, string $code, string $sessionToken, string $purpose, string $ipAddress): array
    {
        $email = $this->sanitizeEmail($email);
        $code = $this->sanitizeOtpCode($code);
        $rateKey = $this->verifyRateLimitKey($email, $ipAddress);

        if (RateLimiter::tooManyAttempts($rateKey, (int) config('otp.verify_rate_limit', 10))) {
            $seconds = RateLimiter::availableIn($rateKey);

            return [
                'success' => false,
                'message' => 'Too many verification attempts. Please try again in '.$seconds.' seconds.',
            ];
        }

        RateLimiter::hit($rateKey, (int) config('otp.verify_rate_decay_seconds', 60));

        $otp = EmailOtp::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('session_token', $sessionToken)
            ->where('is_verified', false)
            ->latest('id')
            ->first();

        if ($otp === null) {
            Log::warning('OTP verification failed: record not found', [
                'email' => $this->maskEmail($email),
                'purpose' => $purpose,
            ]);

            return [
                'success' => false,
                'message' => 'Invalid or expired verification session. Please sign in again.',
            ];
        }

        if ($otp->isLocked()) {
            $seconds = $otp->locked_until->diffInSeconds(Carbon::now());

            return [
                'success' => false,
                'message' => 'Too many incorrect attempts. Try again in '.$seconds.' seconds.',
                'locked' => true,
            ];
        }

        if ($otp->isExpired()) {
            Log::info('OTP verification failed: expired', [
                'email' => $this->maskEmail($email),
                'otp_id' => $otp->id,
            ]);

            return [
                'success' => false,
                'message' => 'This verification code has expired. Request a new one.',
            ];
        }

        if (! Hash::check($code, $otp->otp_code)) {
            return $this->handleFailedAttempt($otp, $ipAddress);
        }

        DB::transaction(function () use ($otp, $email, $purpose) {
            $otp->update(['is_verified' => true]);
            $this->invalidatePreviousOtps($email, $purpose, exceptId: $otp->id);
        });

        $this->auditLog->logOtpVerified($email, $purpose, $otp->id, $ipAddress);

        Log::info('OTP verified successfully', [
            'email' => $this->maskEmail($email),
            'purpose' => $purpose,
            'otp_id' => $otp->id,
        ]);

        return [
            'success' => true,
            'otp' => $otp->fresh(),
        ];
    }

    /**
     * Resend the OTP for an existing pending session.
     *
     * @return array{success: bool, message?: string, otp?: EmailOtp, cooldown?: int}
     */
    public function resend(string $email, string $sessionToken, string $purpose, string $ipAddress): array
    {
        $email = $this->sanitizeEmail($email);

        $existing = EmailOtp::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('session_token', $sessionToken)
            ->where('is_verified', false)
            ->latest('id')
            ->first();

        if ($existing === null) {
            return [
                'success' => false,
                'message' => 'Invalid or expired verification session. Please sign in again.',
            ];
        }

        if ($existing->isLocked()) {
            $seconds = $existing->locked_until->diffInSeconds(Carbon::now());

            return [
                'success' => false,
                'message' => 'Verification is temporarily locked. Try again in '.$seconds.' seconds.',
                'locked' => true,
            ];
        }

        $cooldown = $existing->resendCooldownRemaining();
        if ($cooldown > 0) {
            return [
                'success' => false,
                'message' => 'Please wait '.$cooldown.' seconds before requesting a new code.',
                'cooldown' => $cooldown,
            ];
        }

        return $this->generateAndSend($email, $purpose, $ipAddress);
    }

    /**
     * Mark all unverified OTP rows for an email/purpose as verified so they cannot be reused.
     */
    public function invalidatePreviousOtps(string $email, string $purpose, ?int $exceptId = null): void
    {
        $query = EmailOtp::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('is_verified', false);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        $query->update(['is_verified' => true]);
    }

    /**
     * Find the latest active (unverified, unexpired) OTP for an email and purpose.
     */
    public function findActiveOtp(string $email, string $purpose): ?EmailOtp
    {
        return EmailOtp::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest('id')
            ->first();
    }

    /**
     * Generate a cryptographically secure numeric OTP code.
     */
    protected function generatePlainCode(): string
    {
        $length = (int) config('otp.length', 6);
        $max = (10 ** $length) - 1;
        $number = random_int(0, $max);

        return str_pad((string) $number, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Increment failed attempts and apply a temporary lock when the limit is reached.
     *
     * @return array{success: false, message: string, locked?: bool, attempts_remaining?: int}
     */
    protected function handleFailedAttempt(EmailOtp $otp, ?string $ipAddress = null): array
    {
        $maxAttempts = (int) config('otp.max_attempts', 5);
        $failedAttempts = $otp->failed_attempts + 1;
        $updates = ['failed_attempts' => $failedAttempts];

        if ($failedAttempts >= $maxAttempts) {
            $updates['locked_until'] = Carbon::now()->addMinutes((int) config('otp.lockout_minutes', 15));
        }

        $otp->update($updates);

        $this->auditLog->logOtpFailed($otp->email, $otp->purpose, $otp->id, $failedAttempts, $ipAddress);

        Log::warning('OTP verification failed: incorrect code', [
            'email' => $this->maskEmail($otp->email),
            'otp_id' => $otp->id,
            'failed_attempts' => $failedAttempts,
            'locked' => isset($updates['locked_until']),
        ]);

        if (isset($updates['locked_until'])) {
            return [
                'success' => false,
                'message' => 'Too many incorrect attempts. Verification is locked for '.config('otp.lockout_minutes', 15).' minutes.',
                'locked' => true,
            ];
        }

        $remaining = $maxAttempts - $failedAttempts;

        return [
            'success' => false,
            'message' => 'Incorrect verification code. '.$remaining.' attempt'.($remaining === 1 ? '' : 's').' remaining.',
            'attempts_remaining' => $remaining,
        ];
    }

    protected function sanitizeEmail(string $email): string
    {
        return Str::lower(trim($email));
    }

    protected function sanitizeOtpCode(string $code): string
    {
        return preg_replace('/\D/', '', trim($code)) ?? '';
    }

    protected function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return '***';
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = substr($local, 0, min(2, strlen($local)));

        return $visible.'***@'.$domain;
    }

    protected function sendRateLimitKey(string $email, string $ipAddress): string
    {
        return 'otp-send|'.hash('sha256', $email.'|'.$ipAddress);
    }

    protected function verifyRateLimitKey(string $email, string $ipAddress): string
    {
        return 'otp-verify|'.hash('sha256', $email.'|'.$ipAddress);
    }
}

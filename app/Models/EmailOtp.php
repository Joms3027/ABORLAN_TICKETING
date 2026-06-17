<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Represents a single email OTP verification record.
 *
 * The plaintext OTP is never stored; only a bcrypt hash is persisted in otp_code.
 */
class EmailOtp extends Model
{
    protected $table = 'otp_verifications';

    public const PURPOSE_LOGIN = 'login';

    public const PURPOSE_REGISTER = 'register';

    protected $fillable = [
        'email',
        'otp_code',
        'purpose',
        'session_token',
        'failed_attempts',
        'locked_until',
        'last_sent_at',
        'is_verified',
        'ip_address',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'failed_attempts' => 'integer',
            'locked_until' => 'datetime',
            'last_sent_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Determine whether the OTP has passed its expiration timestamp.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Determine whether verification is temporarily locked after too many failures.
     */
    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    /**
     * Seconds remaining until the resend cooldown elapses.
     */
    public function resendCooldownRemaining(): int
    {
        if ($this->last_sent_at === null) {
            return 0;
        }

        $elapsed = $this->last_sent_at->diffInSeconds(Carbon::now());
        $cooldown = (int) config('otp.resend_cooldown_seconds', 60);

        return max(0, $cooldown - $elapsed);
    }
}

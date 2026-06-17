<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\User;
use App\Services\EmailOtpService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Handles OTP verification UI and API actions after login or registration.
 */
class OtpController extends Controller
{
    public function __construct(
        protected EmailOtpService $otpService,
        protected NotificationService $notifications,
    ) {}

    /**
     * Display the OTP verification form for the pending auth session.
     */
    public function showVerify(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get('otp_pending');

        if (! is_array($pending) || empty($pending['email']) || empty($pending['session_token'])) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your verification session has expired. Please sign in again.']);
        }

        $otp = $this->otpService->findActiveOtp($pending['email'], $pending['purpose']);

        return view('auth.verify-otp', [
            'email' => $pending['email'],
            'maskedEmail' => $this->maskEmail($pending['email']),
            'purpose' => $pending['purpose'],
            'expiresAt' => $otp?->expires_at,
            'resendCooldown' => $otp?->resendCooldownRemaining() ?? 0,
            'isLocked' => $otp?->isLocked() ?? false,
            'lockedUntil' => $otp?->locked_until,
        ]);
    }

    /**
     * Validate the submitted OTP and complete authentication on success.
     */
    public function verify(Request $request): RedirectResponse|JsonResponse
    {
        $pending = $request->session()->get('otp_pending');

        if (! is_array($pending) || empty($pending['email']) || empty($pending['session_token'])) {
            return $this->respond($request, false, 'Your verification session has expired. Please sign in again.', 422);
        }

        $data = $request->validate([
            'otp_code' => ['required', 'string', 'regex:/^\d{6}$/'],
        ]);

        $result = $this->otpService->verify(
            $pending['email'],
            $data['otp_code'],
            $pending['session_token'],
            $pending['purpose'],
            $request->ip() ?? '0.0.0.0',
        );

        if (! $result['success']) {
            return $this->respond($request, false, $result['message'], 422, $result);
        }

        $user = User::query()->where('email', $pending['email'])->first();

        if ($user === null) {
            $request->session()->forget('otp_pending');

            return $this->respond($request, false, 'Account not found. Please register again.', 404);
        }

        if ($pending['purpose'] === EmailOtp::PURPOSE_REGISTER) {
            $user->forceFill(['email_verified_at' => now()])->save();

            try {
                $this->notifications->sendAccountCreated($user);
                $this->notifications->notifyAdminsNewAccount($user);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        Auth::login($user, (bool) ($pending['remember'] ?? false));
        $request->session()->regenerate();
        $request->session()->forget('otp_pending');

        $redirect = $user->is_admin
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(
                $pending['purpose'] === EmailOtp::PURPOSE_REGISTER
                    ? route('atup.overview')
                    : route('bookings.index')
            );

        $message = $pending['purpose'] === EmailOtp::PURPOSE_REGISTER
            ? 'Welcome, '.$user->name.'! Your email is verified and your account is ready.'
            : 'Signed in successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => $redirect->getTargetUrl(),
            ]);
        }

        return $redirect->with('status', $message);
    }

    /**
     * Resend a new OTP to the user's email with cooldown enforcement.
     */
    public function resend(Request $request): RedirectResponse|JsonResponse
    {
        $pending = $request->session()->get('otp_pending');

        if (! is_array($pending) || empty($pending['email']) || empty($pending['session_token'])) {
            return $this->respond($request, false, 'Your verification session has expired. Please sign in again.', 422);
        }

        $result = $this->otpService->resend(
            $pending['email'],
            $pending['session_token'],
            $pending['purpose'],
            $request->ip() ?? '0.0.0.0',
        );

        if (! $result['success']) {
            return $this->respond($request, false, $result['message'], 429, $result);
        }

        if (isset($result['otp'])) {
            $request->session()->put('otp_pending.session_token', $result['otp']->session_token);
        }

        return $this->respond(
            $request,
            true,
            'A new verification code has been sent to your email.',
            200,
            ['cooldown' => config('otp.resend_cooldown_seconds', 60)],
        );
    }

    /**
     * Cancel the pending OTP flow and return to the login page.
     */
    public function cancel(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('otp_pending');

        if (is_array($pending) && ! empty($pending['email']) && ! empty($pending['purpose'])) {
            $this->otpService->invalidatePreviousOtps($pending['email'], $pending['purpose']);
        }

        $request->session()->forget('otp_pending');

        return redirect()->route('login')
            ->with('status', 'Verification cancelled. You can sign in again when ready.');
    }

    /**
     * API: send OTP to a registered email (standalone endpoint).
     */
    public function apiSend(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:160'],
            'purpose' => ['required', 'string', 'in:login,register'],
        ]);

        $user = User::query()->where('email', strtolower(trim($data['email'])))->first();

        if ($user === null) {
            return response()->json([
                'success' => false,
                'message' => 'No account found for this email address.',
            ], 404);
        }

        $result = $this->otpService->generateAndSend(
            $data['email'],
            $data['purpose'],
            $request->ip() ?? '0.0.0.0',
        );

        if (! $result['success']) {
            return response()->json($result, 429);
        }

        $request->session()->put('otp_pending', [
            'email' => strtolower(trim($data['email'])),
            'purpose' => $data['purpose'],
            'user_id' => $user->id,
            'remember' => false,
            'session_token' => $result['otp']->session_token,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent.',
            'expires_at' => $result['otp']->expires_at->toIso8601String(),
            'resend_cooldown' => config('otp.resend_cooldown_seconds', 60),
        ]);
    }

    /**
     * API: verify OTP code (requires otp_pending session or matching session_token).
     */
    public function apiVerify(Request $request): JsonResponse|RedirectResponse
    {
        return $this->verify($request);
    }

    /**
     * API: resend OTP code.
     */
    public function apiResend(Request $request): JsonResponse|RedirectResponse
    {
        return $this->resend($request);
    }

    /**
     * Build a web redirect or JSON response depending on the request type.
     *
     * @param  array<string, mixed>  $extra
     */
    protected function respond(Request $request, bool $success, string $message, int $status = 200, array $extra = []): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json(array_merge([
                'success' => $success,
                'message' => $message,
            ], $extra), $status);
        }

        if ($success) {
            return back()->with('status', $message);
        }

        return back()->withErrors(['otp_code' => $message])->withInput();
    }

    /**
     * Mask an email address for display on the verification page.
     */
    protected function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return '***';
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = substr($local, 0, min(2, strlen($local)));

        return $visible.'***@'.$domain;
    }
}

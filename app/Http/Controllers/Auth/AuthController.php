<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\EmailOtpService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected EmailOtpService $otpService,
        protected NotificationService $notifications,
        protected AuditLogService $auditLog,
    ) {}

    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user and initiate email OTP verification before granting access.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'string', 'email', 'max:160', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:32'],
            'password' => ['required', 'confirmed', Password::min(10)->mixedCase()->numbers()->uncompromised()],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $user->forceFill(['is_admin' => false])->save();

        $result = $this->otpService->generateAndSend(
            $user->email,
            EmailOtp::PURPOSE_REGISTER,
            $request->ip() ?? '0.0.0.0',
        );

        if (! $result['success']) {
            $user->delete();

            return back()
                ->withErrors(['email' => $result['message']])
                ->withInput();
        }

        $request->session()->put('otp_pending', [
            'email' => strtolower(trim($user->email)),
            'purpose' => EmailOtp::PURPOSE_REGISTER,
            'user_id' => $user->id,
            'remember' => false,
            'session_token' => $result['otp']->session_token,
        ]);

        return redirect()->route('otp.verify.show')
            ->with('status', 'We sent a verification code to your email. Enter it below to complete registration.');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Validate credentials, then require email OTP verification before signing in.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($credentials['email']).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $this->auditLog->logSuspiciousLogin($credentials['email'], $request->ip() ?? '0.0.0.0', $seconds);

            try {
                $this->notifications->notifyAdminsSuspiciousLogin(
                    $credentials['email'],
                    $request->ip() ?? '0.0.0.0',
                    $seconds,
                );
            } catch (\Throwable $e) {
                report($e);
            }

            return back()
                ->withErrors(['email' => 'Too many login attempts. Please try again in '.$seconds.' seconds.'])
                ->onlyInput('email');
        }

        $user = User::query()->where('email', strtolower(trim($credentials['email'])))->first();

        if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withErrors(['email' => 'The email or password you entered is incorrect.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);

        $result = $this->otpService->generateAndSend(
            $user->email,
            EmailOtp::PURPOSE_LOGIN,
            $request->ip() ?? '0.0.0.0',
        );

        if (! $result['success']) {
            return back()
                ->withErrors(['email' => $result['message']])
                ->onlyInput('email');
        }

        $request->session()->put('otp_pending', [
            'email' => strtolower(trim($user->email)),
            'purpose' => EmailOtp::PURPOSE_LOGIN,
            'user_id' => $user->id,
            'remember' => $request->boolean('remember'),
            'session_token' => $result['otp']->session_token,
        ]);

        return redirect()->route('otp.verify.show')
            ->with('status', 'We sent a verification code to your email. Enter it to complete sign-in.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have been signed out.');
    }
}

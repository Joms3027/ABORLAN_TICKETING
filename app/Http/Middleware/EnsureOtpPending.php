<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures OTP verification routes are only accessible when a pending session exists.
 */
class EnsureOtpPending
{
    /**
     * Redirect guests without a pending OTP session back to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pending = $request->session()->get('otp_pending');

        if (! is_array($pending) || empty($pending['email']) || empty($pending['session_token'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending verification session.',
                ], 403);
            }

            return redirect()->route('login')
                ->withErrors(['email' => 'Please sign in to receive a verification code.']);
        }

        return $next($request);
    }
}

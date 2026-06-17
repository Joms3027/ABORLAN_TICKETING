@extends('emails.layout')

@section('title', 'Verification code')
@section('heading', 'Your Verification Code')

@section('content')
  <p style="margin:0 0 16px;">
    Your One-Time Password (OTP) is: <strong style="font-size:24px;letter-spacing:0.2em;color:#701a75;font-family:monospace;">{{ $otpCode }}</strong>.
    This code will expire in {{ config('otp.expiry_minutes', 5) }} minutes.
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fdf4ff;border:1px solid #f5d0f3;border-radius:8px;">
    <tr>
      <td align="center" style="padding:20px 18px;">
        <p style="margin:0 0 8px;font-size:13px;color:#6b4a6e;text-transform:uppercase;letter-spacing:0.08em;">One-time password</p>
        <p style="margin:0;font-size:32px;font-weight:700;letter-spacing:0.35em;color:#701a75;font-family:monospace;">{{ $otpCode }}</p>
      </td>
    </tr>
  </table>

  <p style="margin:0 0 12px;">
    @if ($purpose === 'register')
      Enter this code on the verification page to complete your account registration.
    @else
      Enter this code on the verification page to complete your sign-in.
    @endif
  </p>

  <p style="margin:0;font-size:14px;color:#6b4a6e;">
    Do not share this code with anyone. If you did not request this code, you can safely ignore this email.
  </p>
@endsection

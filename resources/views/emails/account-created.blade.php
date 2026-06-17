@extends('emails.layout')

@section('title', 'Welcome')
@section('heading', 'Welcome! Your Account Has Been Successfully Created')

@section('content')
  <p style="margin:0 0 16px;">Hello {{ $user->name }},</p>

  <p style="margin:0 0 16px;">
    Your account with <strong>{{ config('app.name') }}</strong> has been successfully created. You can now apply for Atup-atup Falls hiking permits and manage your bookings online.
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fffbeb;border:1px solid #f5d0f3;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 8px;font-size:13px;color:#6b4a6e;text-transform:uppercase;letter-spacing:0.06em;">Account details</p>
        <p style="margin:0;"><strong>Full name:</strong> {{ $user->name }}</p>
        <p style="margin:8px 0 0;"><strong>Email address:</strong> {{ $user->email }}</p>
        <p style="margin:8px 0 0;"><strong>Account created:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p style="margin:8px 0 0;"><strong>System:</strong> {{ config('app.name') }}</p>
      </td>
    </tr>
  </table>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;">
    <tr>
      <td style="padding:14px 18px;">
        <p style="margin:0;font-size:14px;color:#7f1d1d;">
          <strong>Security reminder:</strong> Never share your password or verification codes with anyone. {{ config('app.name') }} staff will never ask for your credentials.
        </p>
      </td>
    </tr>
  </table>

  <p style="margin:0 0 20px;">Sign in anytime to submit a booking request or track your permit applications.</p>

  <p style="margin:0;">
    <a href="{{ route('login') }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      Sign in to your account
    </a>
  </p>
@endsection

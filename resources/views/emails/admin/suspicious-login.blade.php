@extends('emails.layout')

@section('title', 'Security alert')
@section('heading', 'Security Alert: Suspicious Login Attempts')

@section('content')
  <p style="margin:0 0 16px;">Multiple failed login attempts were detected on {{ config('app.name') }}.</p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0;"><strong>Target email:</strong> {{ $targetEmail }}</p>
        <p style="margin:8px 0 0;"><strong>IP address:</strong> {{ $ipAddress }}</p>
        <p style="margin:8px 0 0;"><strong>Lockout duration:</strong> {{ $retryAfterSeconds }} seconds</p>
        <p style="margin:8px 0 0;"><strong>Detected:</strong> {{ now()->format('F j, Y \a\t g:i A') }}</p>
      </td>
    </tr>
  </table>

  <p style="margin:0;">Review audit logs in the admin panel if this activity appears unauthorized.</p>
@endsection

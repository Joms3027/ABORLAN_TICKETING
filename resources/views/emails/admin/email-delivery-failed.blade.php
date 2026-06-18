@extends('emails.layout')

@section('title', 'Email delivery failed')
@section('heading', 'Email Delivery Failed')

@section('content')
  <p style="margin:0 0 16px;">An outbound email could not be delivered to the recipient.</p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0;"><strong>Template:</strong> {{ $notification->template_key }}</p>
        <p style="margin:8px 0 0;"><strong>Recipient:</strong> {{ $notification->recipient_email }}</p>
        <p style="margin:8px 0 0;"><strong>Subject:</strong> {{ $notification->subject }}</p>
        @if ($notification->booking_id)
          <p style="margin:8px 0 0;"><strong>Booking ID:</strong> {{ $notification->booking_id }}</p>
        @endif
        <p style="margin:8px 0 0;"><strong>Attempts:</strong> {{ $notification->attempts }}</p>
        @if ($errorMessage)
          <p style="margin:8px 0 0;"><strong>Error:</strong> {{ $errorMessage }}</p>
        @endif
      </td>
    </tr>
  </table>

  <p style="margin:0;">Check the email delivery log in the admin dashboard and verify SMTP settings if failures persist.</p>
@endsection

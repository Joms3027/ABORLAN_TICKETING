@extends('emails.layout')

@section('title', 'Booking rejected')
@section('heading', 'Booking Request Rejected')

@section('content')
  <p style="margin:0 0 16px;">Hello {{ $booking->user->name }},</p>

  <p style="margin:0 0 16px;">
    We regret to inform you that your booking request has been rejected.
    @if ($booking->admin_notes)
      <strong>Reason:</strong> {{ $booking->admin_notes }}.
    @endif
    You may submit a new request after addressing the stated concern.
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 8px;font-size:13px;color:#6b4a6e;text-transform:uppercase;letter-spacing:0.06em;">Booking summary</p>
        <p style="margin:0;"><strong>Reference number:</strong> {{ $booking->reference_code }}</p>
        <p style="margin:8px 0 0;"><strong>Hike date:</strong> {{ $booking->hike_date->format('F j, Y') }}</p>
        <p style="margin:8px 0 0;"><strong>Party size:</strong> {{ $booking->party_size }} hiker(s)</p>
        <p style="margin:8px 0 0;"><strong>Rejection date:</strong> {{ ($booking->decided_at ?? now())->format('F j, Y \a\t g:i A') }}</p>
        @if ($booking->admin_notes)
          <p style="margin:8px 0 0;"><strong>Reason for rejection:</strong> {{ $booking->admin_notes }}</p>
        @endif
      </td>
    </tr>
  </table>

  <p style="margin:0 0 20px;">To resubmit, sign in to your account and create a new booking request for an available date.</p>

  <p style="margin:0;">
    <a href="{{ route('bookings.create') }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      Submit a new request
    </a>
  </p>
@endsection

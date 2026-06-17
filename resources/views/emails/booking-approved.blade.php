@extends('emails.layout')

@section('title', 'Booking approved')
@section('heading', 'Booking Request Approved')

@section('content')
  <p style="margin:0 0 16px;">Hello {{ $booking->user->name }},</p>

  <p style="margin:0 0 16px;">
    Congratulations! Your booking request has been approved. Please present your booking reference number upon arrival.
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 8px;font-size:13px;color:#6b4a6e;text-transform:uppercase;letter-spacing:0.06em;">Approval details</p>
        <p style="margin:0;"><strong>Reference number:</strong> {{ $booking->reference_code }}</p>
        <p style="margin:8px 0 0;"><strong>Approved schedule:</strong> {{ $booking->hike_date->format('F j, Y') }}@if($booking->trekking_days) — {{ $booking->trekking_days }}@endif</p>
        <p style="margin:8px 0 0;"><strong>Venue:</strong> Atup-atup Falls, Aborlan</p>
        <p style="margin:8px 0 0;"><strong>Party size:</strong> {{ $booking->party_size }} hiker(s)</p>
        @if ($booking->trekking_route)
          <p style="margin:8px 0 0;"><strong>Route:</strong> {{ $booking->trekking_route }}</p>
        @endif
        @if ($booking->tourGuide)
          <p style="margin:8px 0 0;"><strong>Assigned tour guide:</strong> {{ $booking->tourGuide->name }}</p>
        @endif
        <p style="margin:8px 0 0;"><strong>Approval date:</strong> {{ ($booking->decided_at ?? now())->format('F j, Y \a\t g:i A') }}</p>
      </td>
    </tr>
  </table>

  @if ($booking->admin_notes)
    <p style="margin:0 0 16px;"><strong>Additional instructions:</strong> {{ $booking->admin_notes }}</p>
  @else
    <p style="margin:0 0 16px;"><strong>Additional instructions:</strong> Bring a valid ID, follow all Nag-Atup rules and regulations, and download your entry permit before your visit.</p>
  @endif

  <p style="margin:0 0 12px;">
    <a href="{{ route('bookings.show', $booking) }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;margin-right:8px;">
      View booking
    </a>
    <a href="{{ route('bookings.permit', $booking) }}" style="display:inline-block;background:#701a75;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      Download permit
    </a>
  </p>
@endsection

@extends('emails.layout')

@section('title', 'Booking received')
@section('heading', 'Booking Request Received')

@section('content')
  <p style="margin:0 0 16px;">Hello {{ $booking->user->name }},</p>

  <p style="margin:0 0 16px;">
    Thank you for submitting your hiking permit request. We have received your booking and it is now <strong>Pending Review</strong> by the municipal office.
  </p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fffbeb;border:1px solid #f5d0f3;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0 0 8px;font-size:13px;color:#6b4a6e;text-transform:uppercase;letter-spacing:0.06em;">Booking details</p>
        <p style="margin:0;"><strong>Reference number:</strong> {{ $booking->reference_code }}</p>
        <p style="margin:8px 0 0;"><strong>Hike date:</strong> {{ $booking->hike_date->format('F j, Y') }}</p>
        <p style="margin:8px 0 0;"><strong>Party size:</strong> {{ $booking->party_size }} hiker(s)</p>
        <p style="margin:8px 0 0;"><strong>Purpose:</strong> {{ $booking->purpose_of_visit }}</p>
        @if ($booking->trekking_route)
          <p style="margin:8px 0 0;"><strong>Route:</strong> {{ $booking->trekking_route }}</p>
        @endif
        @if ($booking->trekking_days)
          <p style="margin:8px 0 0;"><strong>Schedule:</strong> {{ $booking->trekking_days }}</p>
        @endif
        <p style="margin:8px 0 0;"><strong>Date submitted:</strong> {{ $booking->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p style="margin:8px 0 0;"><strong>Status:</strong> Pending Review</p>
      </td>
    </tr>
  </table>

  <p style="margin:0 0 20px;">You will receive another email once your request has been reviewed. You can track your booking status from your dashboard.</p>

  <p style="margin:0;">
    <a href="{{ $booking->viewUrl() }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      View booking
    </a>
  </p>
@endsection

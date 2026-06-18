@extends('emails.layout')

@section('title', 'Booking approved')
@section('heading', 'Booking Request Approved')

@section('content')
  <p style="margin:0 0 16px;">Dear {{ $booking->user->name }},</p>

  <p style="margin:0 0 16px;">Your booking request has been approved.</p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0;"><strong>Booking Reference:</strong> {{ $booking->reference_code }}</p>
        <p style="margin:8px 0 0;"><strong>Date:</strong> {{ $booking->hike_date->format('F j, Y') }}</p>
        @if ($booking->trekking_days)
          <p style="margin:8px 0 0;"><strong>Time:</strong> Day visit ({{ $booking->trekking_days }})</p>
        @else
          <p style="margin:8px 0 0;"><strong>Time:</strong> Day visit</p>
        @endif
        <p style="margin:8px 0 0;"><strong>Venue/Resource:</strong> Atup-atup Falls, Aborlan</p>
        @if ($booking->tourGuide)
          <p style="margin:8px 0 0;"><strong>Assigned tour guide:</strong> {{ $booking->tourGuide->name }}</p>
        @endif
      </td>
    </tr>
  </table>

  <p style="margin:0 0 16px;">Please present your booking reference upon arrival.</p>

  <p style="margin:0 0 12px;">
    <a href="{{ $booking->viewUrl() }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;margin-right:8px;">
      View booking
    </a>
    <a href="{{ $booking->permitDownloadUrl() }}" style="display:inline-block;background:#701a75;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      Download permit
    </a>
  </p>

  <p style="margin:16px 0 0;">Thank you.</p>
@endsection

@extends('emails.layout')

@section('title', 'Booking rejected')
@section('heading', 'Booking Request Rejected')

@section('content')
  <p style="margin:0 0 16px;">Dear {{ $booking->user->name }},</p>

  <p style="margin:0 0 16px;">We regret to inform you that your booking request has been rejected.</p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0;"><strong>Booking Reference:</strong> {{ $booking->reference_code }}</p>
        <p style="margin:8px 0 0;"><strong>Date:</strong> {{ $booking->hike_date->format('F j, Y') }}</p>
        <p style="margin:8px 0 0;"><strong>Reason for Rejection:</strong></p>
        <p style="margin:8px 0 0;">{{ $booking->admin_notes ?: 'No reason was provided. Please contact the municipality for details.' }}</p>
      </td>
    </tr>
  </table>

  <p style="margin:0 0 16px;">You may submit a new booking request after addressing the issue.</p>

  <p style="margin:0 0 12px;">
    <a href="{{ route('bookings.create') }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      Submit a new request
    </a>
  </p>

  <p style="margin:16px 0 0;">Thank you.</p>
@endsection

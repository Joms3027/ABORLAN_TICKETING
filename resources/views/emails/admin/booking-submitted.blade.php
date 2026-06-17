@extends('emails.layout')

@section('title', 'New booking')
@section('heading', 'New Booking Request Submitted')

@section('content')
  <p style="margin:0 0 16px;">A new hiking permit booking request requires your review.</p>

  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;background:#fffbeb;border:1px solid #f5d0f3;border-radius:8px;">
    <tr>
      <td style="padding:16px 18px;">
        <p style="margin:0;"><strong>Reference:</strong> {{ $booking->reference_code }}</p>
        <p style="margin:8px 0 0;"><strong>Visitor:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})</p>
        <p style="margin:8px 0 0;"><strong>Hike date:</strong> {{ $booking->hike_date->format('F j, Y') }}</p>
        <p style="margin:8px 0 0;"><strong>Party size:</strong> {{ $booking->party_size }}</p>
        <p style="margin:8px 0 0;"><strong>Submitted:</strong> {{ $booking->created_at->format('F j, Y \a\t g:i A') }}</p>
        <p style="margin:8px 0 0;"><strong>Status:</strong> Pending Review</p>
      </td>
    </tr>
  </table>

  <p style="margin:0;">
    <a href="{{ route('admin.bookings.show', $booking) }}" style="display:inline-block;background:#c026d3;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:600;">
      Review booking
    </a>
  </p>
@endsection

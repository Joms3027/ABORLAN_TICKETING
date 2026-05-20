@extends('layouts.admin')

@section('title', 'Booking '.$booking->reference_code)

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <a href="{{ route('admin.bookings.index') }}">Bookings</a>
  <span aria-hidden="true">/</span>
  <span>{{ $booking->reference_code }}</span>
@endsection

@section('content')
  <div class="page-header">
    <h1>{{ $booking->reference_code }}</h1>
    <p>Review details and update the status of this permit application.</p>
  </div>

  <div class="grid-2">
    <div class="panel">
      <div class="panel-head">
        <h2>Details</h2>
        <span class="pill pill-{{ $booking->status }}">{{ $booking->statusLabel() }}</span>
      </div>

      <div class="detail-grid two-col">
        <div class="detail-item">
          <div class="detail-label">Hiker</div>
          <div class="detail-value">{{ $booking->user?->name }}</div>
          <div class="detail-value muted">
            <a href="{{ route('admin.users.show', $booking->user) }}">{{ $booking->user?->email }}</a>
          </div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Mobile</div>
          <div class="detail-value">{{ $booking->contact_phone }}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Hike date</div>
          <div class="detail-value">{{ $booking->hike_date->format('l, F j, Y') }}</div>
          @isset($dayQuota)
            <p class="detail-value muted" style="margin-top: 0.5rem; line-height: 1.5;">
              <strong>{{ $dayBooked }}</strong> of <strong>{{ $dayQuota }}</strong> hikers booked.
              <strong>{{ $dayRemaining }}</strong> slot{{ $dayRemaining === 1 ? '' : 's' }} available.
              @if ($dayMaxBookings !== null)
                <br /><strong>{{ $dayBookingsCount }}</strong> of <strong>{{ $dayMaxBookings }}</strong> groups used.
                @if (($dayBookingsRemain ?? 0) < 1)
                  <span style="color: var(--danger); font-weight: 600;">No new groups allowed.</span>
                @else
                  <strong>{{ $dayBookingsRemain }}</strong> group slot{{ $dayBookingsRemain === 1 ? '' : 's' }} left.
                @endif
              @else
                <br />No separate limit on booking groups.
              @endif
            </p>
            <a href="{{ route('admin.quotas.index', ['preset_date' => $booking->hike_date->toDateString()]) }}" class="btn btn-secondary btn-sm" style="margin-top: 0.35rem;">Change limit for this date</a>
          @endisset
        </div>
        <div class="detail-item">
          <div class="detail-label">Party size</div>
          <div class="detail-value">{{ $booking->party_size }} hiker(s)</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Emergency contact</div>
          <div class="detail-value">{{ $booking->emergency_contact ?: '—' }}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Submitted</div>
          <div class="detail-value">{{ $booking->created_at->format('M j, Y g:i A') }}</div>
        </div>
        @if ($booking->status === 'approved')
          <div class="detail-item">
            <div class="detail-label">Tour guide</div>
            @if ($booking->tourGuide)
              <div class="detail-value">{{ $booking->tourGuide->name }}</div>
              <div class="detail-value muted">Age {{ $booking->tourGuide->age }} · assigned automatically on approval</div>
            @else
              <div class="detail-value muted" style="color: var(--danger);">No guide available for this hike date</div>
              <a href="{{ route('admin.tour-guides.index') }}" class="btn btn-secondary btn-sm" style="margin-top: 0.35rem;">Tour guide monitor</a>
            @endif
          </div>
        @endif
      </div>

      @if ($booking->notes)
        <div class="detail-item" style="margin-top: 1.1rem;">
          <div class="detail-label">Hiker notes</div>
          <p class="detail-value muted" style="white-space: pre-line; margin-top: 0.25rem;">{{ $booking->notes }}</p>
        </div>
      @endif
    </div>

    <div class="panel">
      <div class="panel-head"><h2>Update status</h2></div>
      <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
        @csrf
        @method('PATCH')

        <div class="field">
          <label for="status">Status</label>
          <select id="status" name="status" class="select">
            @foreach (['pending'=>'Pending review','approved'=>'Approve','rejected'=>'Reject','cancelled'=>'Cancelled','completed'=>'Mark completed'] as $val => $label)
              <option value="{{ $val }}" {{ old('status', $booking->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="field" style="margin-top: 0.85rem;">
          <label for="admin_notes">Notes for the hiker (optional)</label>
          <textarea id="admin_notes" name="admin_notes" class="textarea" placeholder="Visible to the hiker when saved.">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Back to list</a>
        </div>
      </form>
    </div>
  </div>

  @include('bookings.partials.permit-details')
@endsection

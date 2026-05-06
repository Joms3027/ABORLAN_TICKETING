@extends('layouts.portal')

@section('title', 'Booking '.$booking->reference_code)

@section('content')
  <div class="layout-shell">
    @include('admin.partials.side-nav')

    <div>
      <div class="page-header">
        <h1>Booking {{ $booking->reference_code }}</h1>
        <p>Review the details and update the status of this hiking permit application.</p>
      </div>

      <div class="grid-2">
        <div class="panel">
          <div class="panel-head">
            <h2>Details</h2>
            <span class="pill pill-{{ $booking->status }}">{{ $booking->statusLabel() }}</span>
          </div>

          <div class="form-grid two-col">
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Hiker</div>
              <div style="font-weight:600;">{{ $booking->user?->name }}</div>
              <div class="hint" style="font-size:0.85rem;">
                <a href="{{ route('admin.users.show', $booking->user) }}">{{ $booking->user?->email }}</a>
              </div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Mobile</div>
              <div>{{ $booking->contact_phone }}</div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Hike date</div>
              <div>{{ $booking->hike_date->format('l, F j, Y') }}</div>
              @isset($dayQuota)
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: var(--text-muted); line-height: 1.45;">
                  <strong>{{ $dayBooked }}</strong> of <strong>{{ $dayQuota }}</strong> daily slots used on this date (capacity you set under Daily quotas). <strong>{{ $dayRemaining }}</strong> slot{{ $dayRemaining === 1 ? '' : 's' }} still open for new bookings—pending and approved hikers both count toward the limit.
                </p>
                <a href="{{ route('admin.quotas.index', ['preset_date' => $booking->hike_date->toDateString()]) }}" class="btn btn-secondary btn-sm" style="margin-top:0.35rem;">Change booking limit for this date</a>
              @endisset
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Party size</div>
              <div>{{ $booking->party_size }} hiker(s)</div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Emergency contact</div>
              <div>{{ $booking->emergency_contact ?: '—' }}</div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Submitted</div>
              <div>{{ $booking->created_at->format('M j, Y g:i A') }}</div>
            </div>
          </div>

          @if ($booking->notes)
            <div style="margin-top:1.1rem;">
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Hiker notes</div>
              <p style="margin-top:0.25rem; white-space: pre-line;">{{ $booking->notes }}</p>
            </div>
          @endif
        </div>

        <div class="panel">
          <div class="panel-head"><h2>Update status</h2></div>
          <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
            @csrf
            @method('PATCH')

            <div class="field">
              <label for="status">Set status</label>
              <select id="status" name="status" class="select">
                @foreach (['pending'=>'Pending review','approved'=>'Approve','rejected'=>'Reject','cancelled'=>'Cancelled','completed'=>'Mark as completed'] as $val => $label)
                  <option value="{{ $val }}" {{ old('status', $booking->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
            </div>

            <div class="field" style="margin-top:0.85rem;">
              <label for="admin_notes">Notes for the hiker (optional)</label>
              <textarea id="admin_notes" name="admin_notes" class="textarea" placeholder="This message will be visible to the hiker.">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save</button>
              <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Back to list</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

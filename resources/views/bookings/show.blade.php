@extends('layouts.portal')

@section('title', 'Booking '.$booking->reference_code)

@section('content')
  <div class="page-header">
    <h1>Booking {{ $booking->reference_code }}</h1>
    <p>Status, schedule, and visitor details for your hiking permit.</p>
  </div>

  <div class="panel">
    <div class="panel-head panel-head--stack">
      <h2>Hike on {{ $booking->hike_date->format('l, F j, Y') }}</h2>
      <span class="pill pill-{{ $booking->status }}">{{ $booking->statusLabel() }}</span>
    </div>

    <div class="form-grid two-col">
      <div>
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Reference</div>
        <div style="font-weight:700; color:var(--navy); font-size:1.05rem;">{{ $booking->reference_code }}</div>
      </div>
      <div>
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Party size</div>
        <div style="font-weight:700; color:var(--navy); font-size:1.05rem;">{{ $booking->party_size }} hiker(s)</div>
      </div>
      <div>
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Mobile</div>
        <div>{{ $booking->contact_phone }}</div>
      </div>
      <div>
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Emergency contact</div>
        <div>{{ $booking->emergency_contact ?: '—' }}</div>
      </div>
      <div>
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Submitted</div>
        <div>{{ $booking->created_at->format('M j, Y g:i A') }}</div>
      </div>
      <div>
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Last decision</div>
        <div>{{ $booking->decided_at ? $booking->decided_at->format('M j, Y g:i A') : 'Awaiting review' }}</div>
      </div>
    </div>

    @if ($booking->notes)
      <div style="margin-top:1.1rem;">
        <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Your notes</div>
        <p style="margin-top:0.25rem; white-space: pre-line;">{{ $booking->notes }}</p>
      </div>
    @endif

    @if ($booking->admin_notes)
      <div class="alert" style="background:#fef9c3; border-color:#fde047; color:#713f12; margin-top:1.1rem;">
        <strong>Message from the LGU:</strong>
        <div style="white-space: pre-line; margin-top: 0.25rem;">{{ $booking->admin_notes }}</div>
      </div>
    @endif

    @if ($booking->status === 'approved' && $booking->tourGuide)
      <div class="alert alert-success" style="margin-top:1.1rem;">
        <strong>Your tour guide:</strong>
        {{ $booking->tourGuide->name }} (age {{ $booking->tourGuide->age }})
        <div style="margin-top: 0.25rem; font-size: 0.9rem;">Assigned for your group on hike day. Please coordinate with your guide at the jump-off point.</div>
      </div>
    @endif

    @include('bookings.partials.permit-details')
    @include('bookings.partials.health-declaration-summary')

    <div class="form-actions">
      <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back to my bookings</a>

      @if ($booking->isCancellable())
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking? This cannot be undone.');" style="margin:0;">
          @csrf
          <button type="submit" class="btn btn-danger">Cancel booking</button>
        </form>
      @endif
    </div>
  </div>

  @if ($booking->feedback)
    <div class="panel">
      <div class="panel-head"><h2>Your feedback</h2></div>
      <p style="color: var(--text-muted); margin-bottom: 1rem;">Thank you for sharing your experience with the LGU.</p>
      @include('bookings.partials.feedback-display')
    </div>
  @elseif ($booking->canReceiveFeedback())
    <div class="panel" id="feedback">
      <div class="panel-head"><h2>Share your feedback</h2></div>
      <p style="color: var(--text-muted); margin-bottom: 1.1rem;">
        How was your visit to Atup-atup Falls? Rate hospitality, your tour guide, and the place, and leave an optional comment.
      </p>
      @include('bookings.partials.feedback-form')
    </div>
  @elseif ($opens = $booking->feedbackOpensOn())
    <div class="panel" id="feedback">
      <div class="panel-head"><h2>Feedback</h2></div>
      <p style="color: var(--text-muted);">
        You can share feedback after your hike on <strong>{{ $opens->format('l, F j, Y') }}</strong>, or once the LGU marks your visit as completed.
      </p>
    </div>
  @endif

  <div class="panel">
    <div class="panel-head"><h2>What to bring on hike day</h2></div>
    <ul style="padding-left: 1.1rem; display:grid; gap:0.4rem; color: var(--text);">
      <li>Valid ID (any government-issued ID).</li>
      <li>Printed or screenshot of this booking with reference <strong>{{ $booking->reference_code }}</strong> and your completed Visitors Entry Permit details.</li>
      <li>Your online health declaration is on file with this booking. Bring the <a href="{{ route('docs.view', ['f' => 'ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf']) }}">waiver of risk</a> if the LGU requires a signed copy on hike day.</li>
      <li>Drinking water, light snacks, and sturdy shoes for trekking.</li>
      <li>A small bag for trash — please pack out everything you bring in.</li>
      <li>Arrive at <strong>Sitio Manaile, Brgy. Dumanguena, Narra, Palawan</strong> by 7:00 AM unless instructed otherwise.</li>
    </ul>
  </div>
@endsection

@php
  $hasPermit = $booking->visitor_address || $booking->members || $booking->purpose_of_visit;
@endphp

@if (! $hasPermit)
  <p class="muted" style="margin:0;">No Visitors Entry Permit details on file (booking may predate the online application form).</p>
@else
  <div class="form-grid two-col">
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Address</div>
      <div>{{ $booking->visitor_address ?: '—' }}</div>
    </div>
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Purpose of visit</div>
      <div>{{ $booking->purpose_of_visit ?: '—' }}</div>
    </div>
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Days of trekking</div>
      <div>{{ $booking->trekking_days ?: $booking->hike_date->format('M j, Y') }}</div>
    </div>
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Route</div>
      <div>{{ $booking->trekking_route ?: '—' }}</div>
    </div>
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Contact no.</div>
      <div>{{ $booking->contact_phone ?: '—' }}</div>
    </div>
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Emergency contact</div>
      <div>{{ $booking->emergency_contact ?: '—' }}</div>
    </div>
    <div>
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">No. of members</div>
      <div>{{ $booking->party_size }}</div>
    </div>
  </div>

  @if ($booking->members)
    <div style="margin-top:1.1rem; overflow-x:auto;">
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); margin-bottom:0.4rem;">Visitor roster</div>
      <table style="width:100%; border-collapse:collapse; font-size:0.92rem;">
        <thead>
          <tr style="border-bottom:2px solid var(--border); text-align:left;">
            <th style="padding:0.45rem 0.5rem;">#</th>
            <th style="padding:0.45rem 0.5rem;">Name</th>
            <th style="padding:0.45rem 0.5rem;">Sex</th>
            <th style="padding:0.45rem 0.5rem;">Address</th>
            <th style="padding:0.45rem 0.5rem;">Emergency contact</th>
            <th style="padding:0.45rem 0.5rem;">Body ID / marks</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($booking->members as $i => $member)
            <tr style="border-bottom:1px solid var(--border);">
              <td style="padding:0.45rem 0.5rem;">{{ $i + 1 }}</td>
              <td style="padding:0.45rem 0.5rem;">{{ $member['name'] ?? '—' }}</td>
              <td style="padding:0.45rem 0.5rem;">{{ $member['sex'] ?? '—' }}</td>
              <td style="padding:0.45rem 0.5rem;">{{ $member['address'] ?? '—' }}</td>
              <td style="padding:0.45rem 0.5rem;">{{ $member['emergency_contact'] ?? '—' }}</td>
              <td style="padding:0.45rem 0.5rem;">{{ $member['body_marks'] ?? '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  @if ($booking->notes)
    <div style="margin-top:1rem;">
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Notes for LGU</div>
      <p style="margin-top:0.25rem; white-space:pre-line;">{{ $booking->notes }}</p>
    </div>
  @endif
@endif

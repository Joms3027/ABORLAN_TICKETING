@extends('layouts.portal')

@section('title', 'Book a hiking permit')

@section('content')
  @php
    $permitPdf = 'NAG-ATUP Visitors Entry Permit.pdf';
    $oldMembers = old('members', []);
    $defaultParty = (int) old('party_size', 1);
    if (count($oldMembers) < $defaultParty) {
      for ($i = count($oldMembers); $i < $defaultParty; $i++) {
        $oldMembers[] = [
          'name' => $i === 0 ? auth()->user()->name : '',
          'sex' => '',
          'address' => old('visitor_address', ''),
          'emergency_contact' => old('emergency_contact', ''),
          'body_marks' => '',
        ];
      }
    }
  @endphp

  <div class="page-header">
    <h1>Book a hiking permit</h1>
    <p>Complete the official <strong>Nag-Atup Visitors Entry Permit</strong> details below, then submit your application. Permit No. and fees are assigned by the LGU after review.</p>
  </div>

  <div class="alert" style="margin-bottom:1.25rem; background: linear-gradient(135deg, rgba(255, 234, 0, 0.22), rgba(192,38,211,0.06)); border: 1px solid rgba(192,38,211,0.28); border-left: 4px solid var(--gold-light); color: var(--text);">
    <strong>Official form:</strong>
    Your answers mirror the
    <a href="{{ route('docs.view', ['f' => $permitPdf]) }}" target="_blank" rel="noopener noreferrer">NAG-ATUP Visitors Entry Permit (PDF)</a>.
    Open it to compare field labels. You may also need the
    <a href="{{ route('docs.view', ['f' => 'HEALTH DECLARATION FORM.pdf']) }}" target="_blank" rel="noopener noreferrer">health declaration</a>
    and
    <a href="{{ route('docs.view', ['f' => 'ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf']) }}" target="_blank" rel="noopener noreferrer">waiver of risk</a>
    before hike day.
  </div>

  <div class="alert" style="margin-bottom:1.25rem; background: var(--surface); border: 1px solid var(--border); color: var(--text);">
    <strong>How limits work:</strong>
    Administrators set <strong>persons per day</strong> for the site and optionally a <strong>maximum number of bookings</strong> per day. New permits are only accepted when both still have room. You enter <strong>how many people</strong> are visiting; that total must fit in the remaining daily capacity (or choose another date).
  </div>

  <div class="grid-2">
    <div>
      <div class="panel">
        <div class="panel-head"><h2>Visitors Entry Permit application</h2></div>

        <form method="POST" action="{{ route('bookings.store') }}" novalidate id="booking-form">
          @csrf

          <h3 style="font-size:1rem; color:var(--navy); margin-bottom:0.75rem;">Schedule &amp; group size</h3>
          <div class="form-grid two-col">
            <div class="field">
              <label for="hike_date">Hike date</label>
              <input id="hike_date"
                     name="hike_date"
                     type="date"
                     class="input"
                     min="{{ $minDate }}"
                     max="{{ $maxDate }}"
                     value="{{ old('hike_date') }}"
                     required />
              <div class="hint">Earliest available: tomorrow. Up to 3 months ahead. (Permit valid for the approved date only.)</div>
              @error('hike_date')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="party_size">No. of members (party size)</label>
              <input id="party_size"
                     name="party_size"
                     type="number"
                     class="input"
                     min="1" max="20"
                     value="{{ old('party_size', 1) }}"
                     required />
              <div class="hint">Total head count in your group, including yourself (max 20 per application).</div>
              @error('party_size')<div class="error">{{ $message }}</div>@enderror
            </div>
          </div>

          <h3 style="font-size:1rem; color:var(--navy); margin:1.25rem 0 0.75rem;">Permit details (page 1)</h3>
          <div class="form-grid two-col">
            <div class="field" style="grid-column: 1 / -1;">
              <label for="visitor_address">Address</label>
              <input id="visitor_address"
                     name="visitor_address"
                     type="text"
                     class="input"
                     placeholder="Home or mailing address of the group leader"
                     value="{{ old('visitor_address') }}"
                     required />
              @error('visitor_address')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="purpose_of_visit">Purpose of visit</label>
              <input id="purpose_of_visit"
                     name="purpose_of_visit"
                     type="text"
                     class="input"
                     placeholder="e.g. Trekking, sightseeing"
                     value="{{ old('purpose_of_visit') }}"
                     required />
              @error('purpose_of_visit')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="trekking_days">Days of trekking</label>
              <input id="trekking_days"
                     name="trekking_days"
                     type="text"
                     class="input"
                     placeholder="Defaults to your hike date if left blank"
                     value="{{ old('trekking_days') }}" />
              <div class="hint">Leave blank to use your selected hike date.</div>
              @error('trekking_days')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field" style="grid-column: 1 / -1;">
              <label for="trekking_route">Mountain to climb / specific route</label>
              <input id="trekking_route"
                     name="trekking_route"
                     type="text"
                     class="input"
                     placeholder="e.g. Nag-Atup (Atup-atup) Waterfalls trail"
                     value="{{ old('trekking_route', 'Nag-Atup (Atup-atup) Waterfalls') }}"
                     required />
              @error('trekking_route')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="contact_phone">Contact no.</label>
              <input id="contact_phone"
                     name="contact_phone"
                     type="tel"
                     class="input"
                     value="{{ old('contact_phone', auth()->user()->phone) }}"
                     required />
              @error('contact_phone')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="emergency_contact">Contact person in case of emergency</label>
              <input id="emergency_contact"
                     name="emergency_contact"
                     type="text"
                     class="input"
                     placeholder="Name &amp; number of someone we can reach"
                     value="{{ old('emergency_contact') }}"
                     required />
              @error('emergency_contact')<div class="error">{{ $message }}</div>@enderror
            </div>
          </div>

          <h3 style="font-size:1rem; color:var(--navy); margin:1.35rem 0 0.5rem;">Visitor roster (page 2)</h3>
          <p class="hint" style="margin-bottom:0.85rem;">One row per person in your group — name, sex, address, emergency contact, and body identification / birth marks.</p>
          @error('members')<div class="error" style="margin-bottom:0.75rem;">{{ $message }}</div>@enderror

          <div id="members-container" style="display:grid; gap:1rem;">
            @foreach ($oldMembers as $index => $member)
              @include('bookings.partials.member-fields', ['index' => $index, 'member' => $member])
            @endforeach
          </div>

          <div class="field" style="margin-top:1.25rem;">
            <label class="checkbox-label" style="display:flex; gap:0.55rem; align-items:flex-start; cursor:pointer;">
              <input type="checkbox"
                     name="permit_rules_ack"
                     value="1"
                     {{ old('permit_rules_ack') ? 'checked' : '' }}
                     required
                     style="margin-top:0.2rem;" />
              <span>I/We have read, understand the rules and regulations of Nag-Atup (Atup-atup) Waterfalls and agree to abide by them, as stated on the Visitors Entry Permit.</span>
            </label>
            @error('permit_rules_ack')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field" style="margin-top: 1rem;">
            <label for="notes">Additional notes for the LGU (optional)</label>
            <textarea id="notes" name="notes" class="textarea" placeholder="Allergies, accessibility needs, guide preferences, etc.">{{ old('notes') }}</textarea>
            @error('notes')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit permit application</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>

    <div>
      <div class="panel">
        <div class="panel-head">
          <h2>Slot availability</h2>
          <span class="muted">Next 30 days</span>
        </div>
        <div class="avail-list" style="max-height: 520px; overflow-y: auto;">
          @foreach ($availability as $row)
            @php
              $cls = ! $row['accepts_new_bookings'] ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
              if ($row['custom']) $cls .= ' custom';
            @endphp
            <button type="button"
                    class="avail-row {{ trim($cls) }}"
                    data-date="{{ $row['date'] }}"
                    style="cursor:pointer; text-align:left; border:1px solid var(--border); width:100%; font: inherit;">
              <div>
                <strong>{{ $row['label'] }}</strong>
                @if ($row['note'])<div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $row['note'] }}</div>@endif
              </div>
              <div style="text-align:right;">
                @if (! $row['accepts_new_bookings'])
                  <strong style="color: var(--danger);">Fully booked</strong>
                @else
                  <strong>{{ $row['remaining'] }}</strong> / {{ $row['quota'] }} persons
                  @if ($row['max_bookings'] !== null)
                    <div class="hint" style="font-size:0.72rem;">{{ $row['bookings_remaining'] ?? 0 }} / {{ $row['max_bookings'] }} bookings</div>
                  @endif
                @endif
              </div>
            </button>
          @endforeach
        </div>
        <p class="hint" style="margin-top: 0.6rem; color: var(--text-muted);">Tap a row to copy the date into the form.</p>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  (function () {
    var memberTemplate = @json(view('bookings.partials.member-fields', ['index' => '__INDEX__', 'member' => []])->render());

    function syncMemberRows() {
      var container = document.getElementById('members-container');
      var partyInput = document.getElementById('party_size');
      if (!container || !partyInput) return;

      var target = Math.min(20, Math.max(1, parseInt(partyInput.value, 10) || 1));
      var existing = container.querySelectorAll('[data-member-row]');

      while (existing.length < target) {
        var html = memberTemplate.replace(/__INDEX__/g, String(existing.length));
        var wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        container.appendChild(wrap.firstElementChild);
        existing = container.querySelectorAll('[data-member-row]');
      }

      while (existing.length > target) {
        container.removeChild(existing[existing.length - 1]);
        existing = container.querySelectorAll('[data-member-row]');
      }

      existing.forEach(function (row, i) {
        row.querySelectorAll('[name]').forEach(function (el) {
          el.name = el.name.replace(/members\[\d+\]/, 'members[' + i + ']');
        });
        var title = row.querySelector('[data-member-title]');
        if (title) title.textContent = 'Visitor ' + (i + 1);
      });
    }

    document.getElementById('party_size')?.addEventListener('change', syncMemberRows);
    document.getElementById('party_size')?.addEventListener('input', syncMemberRows);

    document.getElementById('visitor_address')?.addEventListener('change', function () {
      var addr = this.value;
      document.querySelectorAll('[data-member-address]').forEach(function (el) {
        if (!el.value) el.value = addr;
      });
    });

    document.querySelectorAll('[data-date]').forEach(function (row) {
      row.addEventListener('click', function () {
        var d = row.getAttribute('data-date');
        var input = document.getElementById('hike_date');
        if (input) {
          input.value = d;
          input.focus();
          input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    });
  })();
</script>
@endpush

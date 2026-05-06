@extends('layouts.portal')

@section('title', 'Book a hiking permit')

@section('content')
  <div class="page-header">
    <h1>Book a hiking permit</h1>
    <p>Pick the day you want to hike Atup-atup Falls. Slots shown reflect the daily limits set by LGU administrators for visitor safety.</p>
  </div>

  <div class="alert" style="margin-bottom:1.25rem; background: linear-gradient(135deg, rgba(255, 234, 0, 0.22), rgba(192,38,211,0.06)); border: 1px solid rgba(192,38,211,0.28); border-left: 4px solid var(--gold-light); color: var(--text);">
    <strong>How limits work:</strong>
    Administrators set a <strong>default number of hikers per day</strong> and can raise or lower—or close—the trail for single dates under <strong>Daily quotas</strong>. Parties over the remaining slots cannot complete a booking until the date or group size changes.
  </div>

  <div class="grid-2">
    <div>
      <div class="panel">
        <div class="panel-head"><h2>Permit application</h2></div>

        <form method="POST" action="{{ route('bookings.store') }}" novalidate>
          @csrf
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
              <div class="hint">Earliest available: tomorrow. Up to 3 months ahead.</div>
              @error('hike_date')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="party_size">Number of hikers</label>
              <input id="party_size"
                     name="party_size"
                     type="number"
                     class="input"
                     min="1" max="20"
                     value="{{ old('party_size', 1) }}"
                     required />
              <div class="hint">Includes yourself. Maximum 20 per booking.</div>
              @error('party_size')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="contact_phone">Mobile number</label>
              <input id="contact_phone"
                     name="contact_phone"
                     type="tel"
                     class="input"
                     value="{{ old('contact_phone', auth()->user()->phone) }}"
                     required />
              @error('contact_phone')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
              <label for="emergency_contact">Emergency contact</label>
              <input id="emergency_contact"
                     name="emergency_contact"
                     type="text"
                     class="input"
                     placeholder="Name & number of someone we can reach"
                     value="{{ old('emergency_contact') }}" />
              @error('emergency_contact')<div class="error">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="field" style="margin-top: 1rem;">
            <label for="notes">Notes for the LGU (optional)</label>
            <textarea id="notes" name="notes" class="textarea" placeholder="Allergies, accessibility needs, guide preferences, etc.">{{ old('notes') }}</textarea>
            @error('notes')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit booking</button>
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
              $cls = $row['remaining'] === 0 ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
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
                @if ($row['remaining'] === 0)
                  <strong style="color: var(--danger);">Fully booked</strong>
                @else
                  <strong>{{ $row['remaining'] }}</strong> / {{ $row['quota'] }}
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
</script>
@endpush

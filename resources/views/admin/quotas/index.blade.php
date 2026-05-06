@extends('layouts.portal')

@section('title', 'Daily quotas')

@section('content')
  <div class="layout-shell">
    @include('admin.partials.side-nav')

    <div>
      <div class="page-header">
        <h1>Daily booking quotas</h1>
        <p>Adjust how many hikers may book per day. This controls what visitors see on the booking form and whether new bookings are accepted. Use the default for normal days, or set per-day overrides for closures, holidays, or special events.</p>
      </div>

      @if (! empty($quotaPresetDate))
        <div class="alert alert-success" style="margin-bottom: 1rem; background: rgba(192,38,211,0.08); border-color: var(--border); color: var(--navy);">
          Pre-filled hike date from a booking record. Adjust <strong>hikers allowed</strong> below and save an override if you need more or fewer slots on that day.
        </div>
      @endif

      <div class="grid-2">
        <div class="panel">
          <div class="panel-head"><h2>Default daily quota</h2></div>
          <p style="color: var(--text-muted); margin-bottom:1rem;">Applies to every day that does not have a specific override.</p>
          <form method="POST" action="{{ route('admin.quotas.default') }}" style="display:flex; gap:0.6rem; flex-wrap:wrap; align-items:flex-end;">
            @csrf
            <div class="field" style="flex:1; min-width:160px;">
              <label for="default_quota">Hikers allowed per day</label>
              <input id="default_quota" type="number" min="0" max="500" name="default_quota" class="input" value="{{ old('default_quota', $defaultQuota) }}" required />
            </div>
            <button type="submit" class="btn btn-primary">Save default</button>
          </form>
        </div>

        <div class="panel">
          <div class="panel-head"><h2>Override a specific date</h2></div>
          <form method="POST" action="{{ route('admin.quotas.upsert') }}">
            @csrf
            <div class="form-grid two-col">
              <div class="field">
                <label for="quota_date">Date</label>
                <input id="quota_date" type="date" name="quota_date" class="input" min="{{ now()->toDateString() }}" value="{{ old('quota_date', $quotaPresetDate ?? '') }}" required />
              </div>
              <div class="field">
                <label for="slots">Hikers allowed</label>
                <input id="slots" type="number" min="0" max="500" name="slots" class="input" value="{{ old('slots') }}" required />
                <div class="hint">Set 0 to fully close the trail for that day.</div>
              </div>
            </div>
            <div class="field" style="margin-top:0.85rem;">
              <label for="note">Note (optional)</label>
              <input id="note" type="text" name="note" class="input" value="{{ old('note') }}" placeholder="e.g. Local holiday, Trail maintenance" />
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save override</button>
            </div>
          </form>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head">
          <h2>Next 30 days at a glance</h2>
          <span class="muted">Bold border indicates a custom override</span>
        </div>
        <div class="table-wrap">
          <table class="data">
            <thead>
              <tr>
                <th>Date</th>
                <th>Quota</th>
                <th>Booked</th>
                <th>Remaining</th>
                <th>Note</th>
                <th>Source</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($availability as $row)
                <tr>
                  <td><strong>{{ $row['label'] }}</strong></td>
                  <td>{{ $row['quota'] }}</td>
                  <td>{{ $row['booked'] }}</td>
                  <td>
                    @if ($row['remaining'] === 0)
                      <span style="color: var(--danger); font-weight:700;">Full</span>
                    @else
                      {{ $row['remaining'] }}
                    @endif
                  </td>
                  <td>{{ $row['note'] ?: '—' }}</td>
                  <td>{{ $row['custom'] ? 'Custom override' : 'Default' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head"><h2>Active overrides</h2></div>
        @if ($customDates->isEmpty())
          <p style="color: var(--text-muted);">No upcoming custom overrides. The default daily quota applies to all dates.</p>
        @else
          <div class="table-wrap">
            <table class="data">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Slots</th>
                  <th>Note</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($customDates as $row)
                  <tr>
                    <td><strong>{{ \Illuminate\Support\Carbon::parse($row->quota_date)->format('D, M j, Y') }}</strong></td>
                    <td>{{ $row->slots }}</td>
                    <td>{{ $row->note ?: '—' }}</td>
                    <td>
                      <form method="POST" action="{{ route('admin.quotas.destroy', $row) }}" onsubmit="return confirm('Remove this override and use the default quota instead?');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

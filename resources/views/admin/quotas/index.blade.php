@extends('layouts.admin')

@section('title', 'Daily quotas')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <span>Daily quotas</span>
@endsection

@section('content')
  <div class="page-header">
    <h1>Daily quotas</h1>
    <p>
      Set <strong>persons per day</strong> (total visitors) and <strong>bookings per day</strong> (separate permit applications).
      Both limits apply. Use defaults for normal days, or per-date overrides for holidays and events.
    </p>
  </div>

  @if (! empty($quotaPresetDate))
    <div class="alert alert-info">
      Pre-filled hike date from a booking. Adjust limits below and save an override if needed.
    </div>
  @endif

  <div class="grid-2">
    <div class="panel">
      <div class="panel-head"><h2>Default limits</h2></div>
      <p class="detail-value muted" style="margin-bottom: 1rem;">Used for any day without a specific override.</p>
      <form method="POST" action="{{ route('admin.quotas.default') }}" class="form-grid two-col" style="align-items: flex-end;">
        @csrf
        <div class="field">
          <label for="default_quota">Persons per day</label>
          <input id="default_quota" type="number" min="0" max="500" name="default_quota" class="input" value="{{ old('default_quota', $defaultQuota) }}" required />
          <div class="hint">Max total people; sum of party sizes cannot exceed this.</div>
        </div>
        <div class="field">
          <label for="default_max_bookings">Bookings per day</label>
          <input id="default_max_bookings" type="number" min="0" max="500" name="default_max_bookings" class="input" value="{{ old('default_max_bookings', $defaultMaxBookings) }}" required />
          <div class="hint">Max permit applications per day. Use <strong>0</strong> for no separate cap.</div>
        </div>
        <div class="field" style="grid-column: 1 / -1;">
          <button type="submit" class="btn btn-primary">Save defaults</button>
        </div>
      </form>
    </div>

    <div class="panel">
      <div class="panel-head"><h2>Override a date</h2></div>
      <form method="POST" action="{{ route('admin.quotas.upsert') }}">
        @csrf
        <div class="form-grid two-col">
          <div class="field">
            <label for="quota_date">Date</label>
            <input id="quota_date" type="date" name="quota_date" class="input" min="{{ now()->toDateString() }}" value="{{ old('quota_date', $quotaPresetDate ?? '') }}" required />
          </div>
          <div class="field">
            <label for="slots">Persons for this date</label>
            <input id="slots" type="number" min="0" max="500" name="slots" class="input" value="{{ old('slots') }}" required />
            <div class="hint">Set 0 to close the trail to new visitors.</div>
          </div>
          <div class="field">
            <label for="max_bookings">Bookings for this date</label>
            <input id="max_bookings" type="number" min="0" max="500" name="max_bookings" class="input" value="{{ old('max_bookings') }}" placeholder="Use default if blank" />
            <div class="hint">Leave blank to use default. Set <strong>0</strong> to block new applications.</div>
          </div>
        </div>
        <div class="field" style="margin-top: 0.85rem;">
          <label for="note">Note (optional)</label>
          <input id="note" type="text" name="note" class="input" value="{{ old('note') }}" placeholder="e.g. Holiday, maintenance" />
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save override</button>
        </div>
      </form>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <h2>Next 30 days</h2>
      <span class="muted">Left border = custom override</span>
    </div>
    <div class="table-wrap table-cards">
      <table class="data">
        <thead>
          <tr>
            <th>Date</th>
            <th>Cap</th>
            <th>Booked</th>
            <th>Open</th>
            <th>Groups</th>
            <th>Note</th>
            <th>Source</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($availability as $row)
            <tr @if($row['custom']) style="border-left: 3px solid var(--admin-accent);" @endif>
              <td data-label="Date"><strong>{{ $row['label'] }}</strong></td>
              <td data-label="Cap">{{ $row['quota'] }}</td>
              <td data-label="Booked">{{ $row['booked'] }}</td>
              <td data-label="Open">
                @if (! $row['accepts_new_bookings'] && $row['remaining'] < 1)
                  <span style="color: var(--danger); font-weight: 600;">Full</span>
                @elseif (! $row['accepts_new_bookings'])
                  <span style="color: var(--danger); font-weight: 600;">Groups full</span>
                @else
                  {{ $row['remaining'] }}
                @endif
              </td>
              <td data-label="Groups">
                @if ($row['max_bookings'] === null)
                  <span class="muted">No cap</span>
                @else
                  {{ $row['bookings_booked'] }} / {{ $row['max_bookings'] }}
                @endif
              </td>
              <td data-label="Note">{{ $row['note'] ?: '—' }}</td>
              <td data-label="Source">{{ $row['custom'] ? 'Override' : 'Default' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head"><h2>Active overrides</h2></div>
    @if ($customDates->isEmpty())
      <p class="empty-state">No upcoming custom overrides.</p>
    @else
      <div class="table-wrap table-cards">
        <table class="data">
          <thead>
            <tr>
              <th>Date</th>
              <th>Persons / day</th>
              <th>Bookings / day</th>
              <th>Note</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($customDates as $row)
              <tr>
                <td data-label="Date"><strong>{{ \Illuminate\Support\Carbon::parse($row->quota_date)->format('D, M j, Y') }}</strong></td>
                <td data-label="Persons / day">{{ $row->slots }}</td>
                <td data-label="Bookings / day">
                  @if ($row->max_bookings === null)
                    {{ $defaultMaxBookings > 0 ? $defaultMaxBookings.' (default)' : 'No cap' }}
                  @else
                    {{ $row->max_bookings }}
                  @endif
                </td>
                <td data-label="Note">{{ $row->note ?: '—' }}</td>
                <td class="actions-cell" data-label="">
                  <form method="POST" action="{{ route('admin.quotas.destroy', $row) }}" onsubmit="return confirm('Remove this override and use defaults?');" style="margin:0;">
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
@endsection

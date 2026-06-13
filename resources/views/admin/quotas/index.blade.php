@extends('layouts.admin')

@section('title', 'Daily quotas')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <span>Daily quotas</span>
@endsection

@push('head')
  <style>
    .quota-how-it-works {
      display: flex;
      gap: 0.85rem;
      padding: 1rem 1.1rem;
      border-radius: var(--radius-sm);
      background: linear-gradient(135deg, #fdf4ff 0%, #faf5ff 100%);
      border: 1px solid var(--border);
      font-size: 0.875rem;
      color: var(--text-muted);
      line-height: 1.55;
      margin-bottom: 1.5rem;
    }
    .quota-how-it-works svg {
      flex-shrink: 0;
      width: 1.25rem;
      height: 1.25rem;
      color: var(--admin-accent);
      margin-top: 0.1rem;
    }
    .quota-how-it-works strong { color: var(--navy); }

    .quota-stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 0.85rem;
      margin-bottom: 1.5rem;
    }
    .quota-stat {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1rem 1.1rem;
      box-shadow: var(--shadow-sm);
    }
    .quota-stat .label {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--text-muted);
    }
    .quota-stat .value {
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--navy);
      line-height: 1.2;
      margin-top: 0.2rem;
    }
    .quota-stat .hint {
      font-size: 0.75rem;
      color: var(--text-muted);
      margin-top: 0.25rem;
    }
    .quota-stat.is-open .value { color: var(--success); }
    .quota-stat.is-tight .value { color: var(--warn); }
    .quota-stat.is-full .value { color: var(--danger); }
    .quota-stat.is-featured {
      border-color: var(--admin-accent);
      background: linear-gradient(135deg, #fff 0%, #fdf4ff 100%);
    }

    .quota-form-panel .panel-sub {
      font-size: 0.875rem;
      color: var(--text-muted);
      margin: -0.35rem 0 1rem;
      line-height: 1.5;
    }
    .quota-form-panel.is-editing {
      border-color: var(--admin-accent);
      box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.12);
      transition: box-shadow 0.25s var(--ease), border-color 0.25s var(--ease);
    }

    .quota-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 0.65rem 1rem;
      font-size: 0.75rem;
      color: var(--text-muted);
    }
    .quota-legend span {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }
    .quota-legend i {
      width: 0.65rem;
      height: 0.65rem;
      border-radius: 999px;
      display: inline-block;
    }
    .quota-legend .open i { background: var(--success); }
    .quota-legend .tight i { background: var(--warn); }
    .quota-legend .full i { background: var(--danger); }
    .quota-legend .custom i { background: var(--admin-accent); }

    .quota-toolbar {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
    }
    .quota-toolbar .input {
      flex: 1;
      min-width: 180px;
      max-width: 280px;
    }

    .avail-row {
      cursor: default;
      position: relative;
    }
    .avail-row.custom {
      border-left: 3px solid var(--admin-accent);
      padding-left: calc(0.65rem - 3px);
    }
    .avail-row .avail-actions {
      grid-column: 1 / -1;
      display: flex;
      justify-content: flex-end;
      margin-top: 0.15rem;
    }
    .avail-status-pill {
      display: inline-flex;
      align-items: center;
      margin-left: 0.35rem;
      padding: 0.12rem 0.5rem;
      border-radius: 999px;
      font-size: 0.62rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .avail-status-pill.open { background: #dcfce7; color: #14532d; }
    .avail-status-pill.tight { background: #fef3c7; color: #78350f; }
    .avail-status-pill.full { background: #fee2e2; color: #7f1d1d; }
    .avail-status-pill.closed { background: #f1f5f9; color: #334155; }

    .quota-filter-empty {
      text-align: center;
      padding: 2rem 1rem;
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    .quota-overrides-compact .data td.actions-cell {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      justify-content: flex-end;
    }
  </style>
@endpush

@section('content')
  <div class="page-header">
    <h1>Daily quotas</h1>
    <p>
      Control how many visitors and permit applications Atup-atup Falls accepts each day.
      Set defaults for regular days, then add per-date overrides for holidays, events, or closures.
    </p>
  </div>

  @if (! empty($quotaPresetDate))
    <div class="alert alert-info" role="status">
      Editing limits for <strong>{{ \Illuminate\Support\Carbon::parse($quotaPresetDate)->format('l, F j, Y') }}</strong>.
      Adjust the override form below and save.
    </div>
  @endif

  <div class="quota-how-it-works">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
    </svg>
    <div>
      <strong>Two limits work together.</strong>
      <strong>Persons per day</strong> caps total visitors (sum of all party sizes).
      <strong>Bookings per day</strong> caps how many separate permit applications are allowed.
      A day is closed to new bookings when either limit is reached. Set bookings to <strong>0</strong> to block new applications while keeping a person cap, or set persons to <strong>0</strong> to fully close the trail.
    </div>
  </div>

  <div class="quota-stat-grid">
    <div class="quota-stat is-featured">
      <div class="label">Default persons / day</div>
      <div class="value">{{ $defaultQuota }}</div>
      <div class="hint">
        @if ($defaultMaxBookings > 0)
          Max {{ $defaultMaxBookings }} groups / day
        @else
          No separate group cap
        @endif
      </div>
    </div>
    <div class="quota-stat is-open">
      <div class="label">Open (30 days)</div>
      <div class="value">{{ $stats['open_days'] }}</div>
      <div class="hint">Accepting new bookings</div>
    </div>
    <div class="quota-stat is-tight">
      <div class="label">Nearly full</div>
      <div class="value">{{ $stats['tight_days'] }}</div>
      <div class="hint">Less than 20% capacity left</div>
    </div>
    <div class="quota-stat is-full">
      <div class="label">Full / closed</div>
      <div class="value">{{ $stats['full_days'] }}</div>
      <div class="hint">No new bookings accepted</div>
    </div>
    <div class="quota-stat">
      <div class="label">Active overrides</div>
      <div class="value">{{ $stats['overrides_total'] }}</div>
      <div class="hint">Upcoming custom dates</div>
    </div>
  </div>

  <div class="grid-2">
    <div class="panel quota-form-panel">
      <div class="panel-head">
        <h2>Default limits</h2>
      </div>
      <p class="panel-sub">Applied to any day without a specific override.</p>
      <form method="POST" action="{{ route('admin.quotas.default') }}" class="form-grid two-col" style="align-items: flex-end;">
        @csrf
        <div class="field">
          <label for="default_quota">Persons per day</label>
          <input id="default_quota" type="number" min="0" max="500" name="default_quota" class="input @error('default_quota') is-invalid @enderror" value="{{ old('default_quota', $defaultQuota) }}" required />
          @error('default_quota')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
          <div class="hint">Max total people across all groups.</div>
        </div>
        <div class="field">
          <label for="default_max_bookings">Bookings per day</label>
          <input id="default_max_bookings" type="number" min="0" max="500" name="default_max_bookings" class="input @error('default_max_bookings') is-invalid @enderror" value="{{ old('default_max_bookings', $defaultMaxBookings) }}" required />
          @error('default_max_bookings')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
          <div class="hint"><strong>0</strong> = no separate cap on applications.</div>
        </div>
        <div class="field" style="grid-column: 1 / -1;">
          <button type="submit" class="btn btn-primary">Save defaults</button>
        </div>
      </form>
    </div>

    <div class="panel quota-form-panel" id="quota-override-panel">
      <div class="panel-head">
        <h2 id="quota-override-title">Override a date</h2>
      </div>
      <p class="panel-sub" id="quota-override-hint">Set custom limits for a specific day (holidays, events, closures).</p>
      <form method="POST" action="{{ route('admin.quotas.upsert') }}" id="quota-override-form">
        @csrf
        <div class="form-grid two-col">
          <div class="field">
            <label for="quota_date">Date</label>
            <input id="quota_date" type="date" name="quota_date" class="input @error('quota_date') is-invalid @enderror" min="{{ now()->toDateString() }}" value="{{ old('quota_date', $quotaPresetDate ?? '') }}" required />
            @error('quota_date')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
          </div>
          <div class="field">
            <label for="slots">Persons for this date</label>
            <input id="slots" type="number" min="0" max="500" name="slots" class="input @error('slots') is-invalid @enderror" value="{{ old('slots', $presetSlots) }}" required />
            @error('slots')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
            <div class="hint"><strong>0</strong> closes the trail to new visitors.</div>
          </div>
          <div class="field">
            <label for="max_bookings">Bookings for this date</label>
            <input id="max_bookings" type="number" min="0" max="500" name="max_bookings" class="input @error('max_bookings') is-invalid @enderror" value="{{ old('max_bookings', $presetMaxBookings) }}" placeholder="Use default if blank" />
            @error('max_bookings')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
            <div class="hint">Leave blank for default. <strong>0</strong> blocks new applications.</div>
          </div>
        </div>
        <div class="field" style="margin-top: 0.85rem;">
          <label for="note">Note (optional)</label>
          <input id="note" type="text" name="note" class="input @error('note') is-invalid @enderror" value="{{ old('note', $presetNote) }}" placeholder="e.g. Holiday, maintenance" maxlength="160" />
          @error('note')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
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
      <div class="quota-legend" aria-label="Capacity legend">
        <span class="open"><i></i> Open</span>
        <span class="tight"><i></i> Nearly full</span>
        <span class="full"><i></i> Full</span>
        <span class="custom"><i></i> Custom override</span>
      </div>
    </div>

    <div class="quota-toolbar">
      <nav class="status-tabs" aria-label="Filter availability">
        <a href="#" class="is-active" data-quota-filter="all">All <span class="muted">({{ count($availability) }})</span></a>
        <a href="#" data-quota-filter="open">Open <span class="muted">({{ $stats['open_days'] }})</span></a>
        <a href="#" data-quota-filter="tight">Nearly full <span class="muted">({{ $stats['tight_days'] }})</span></a>
        <a href="#" data-quota-filter="full">Full <span class="muted">({{ $stats['full_days'] }})</span></a>
        <a href="#" data-quota-filter="custom">Custom</a>
      </nav>
      <input type="search" id="quota-search" class="input" placeholder="Search date or note…" aria-label="Search dates" />
    </div>

    <div class="avail-list" id="quota-avail-list">
      @foreach ($availability as $row)
        @php
          $pct = $row['quota'] > 0 ? min(100, round(($row['booked'] / $row['quota']) * 100)) : 100;
          if (! $row['accepts_new_bookings']) {
            $status = $row['quota'] < 1 ? 'closed' : 'full';
            $statusLabel = $row['quota'] < 1 ? 'Closed' : ($row['remaining'] < 1 ? 'Full' : 'Groups full');
            $cls = 'full';
          } elseif ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2))) {
            $status = 'tight';
            $statusLabel = 'Nearly full';
            $cls = 'tight';
          } else {
            $status = 'open';
            $statusLabel = 'Open';
            $cls = '';
          }
          if ($row['custom']) {
            $cls .= ' custom';
          }
          $customRow = $row['custom']
            ? $customDates->first(fn ($c) => \Illuminate\Support\Carbon::parse($c->quota_date)->toDateString() === $row['date'])
            : null;
          $maxBkAttr = $customRow && $customRow->max_bookings !== null
            ? (string) $customRow->max_bookings
            : '';
        @endphp
        <div
          class="avail-row {{ trim($cls) }}"
          data-quota-row
          data-status="{{ $status }}"
          data-custom="{{ $row['custom'] ? '1' : '0' }}"
          data-search="{{ strtolower($row['label'].' '.($row['note'] ?? '')) }}"
          title="Double-click to edit this date"
        >
          <div>
            <strong>{{ $row['label'] }}</strong>
            <span class="avail-status-pill {{ $status }}">{{ $statusLabel }}</span>
            @if ($row['custom'])<span class="avail-badge">Override</span>@endif
            @if ($row['note'])<span class="sub" style="display:block; margin-top:0.15rem;">{{ $row['note'] }}</span>@endif
          </div>
          <div class="avail-meta">
            <strong>{{ $row['booked'] }}</strong> / {{ $row['quota'] }} hikers
            @if ($row['max_bookings'] !== null)
              <br />{{ $row['bookings_booked'] }} / {{ $row['max_bookings'] }} groups
            @else
              <br /><span class="muted">No group cap</span>
            @endif
            <br />
            @if ($row['accepts_new_bookings'])
              <strong>{{ $row['remaining'] }}</strong> open
            @else
              <span style="color: var(--danger); font-weight: 600;">No slots</span>
            @endif
          </div>
          <div class="avail-progress" role="presentation" aria-hidden="true"><span style="width: {{ $pct }}%;"></span></div>
          <div class="avail-actions">
            <button
              type="button"
              class="btn btn-secondary btn-sm"
              data-quota-edit
              data-date="{{ $row['date'] }}"
              data-label="{{ $row['label'] }}"
              data-slots="{{ $row['quota'] }}"
              data-max-bookings="{{ $maxBkAttr }}"
              data-note="{{ $row['note'] ?? '' }}"
            >Edit limits</button>
          </div>
        </div>
      @endforeach
    </div>
    <p class="quota-filter-empty" id="quota-filter-empty" hidden>No dates match this filter. Try another tab or clear your search.</p>
  </div>

  <div class="panel">
    <div class="panel-head">
      <h2>Active overrides</h2>
      <span class="muted">{{ $customDates->count() }} upcoming</span>
    </div>
    @if ($customDates->isEmpty())
      <p class="empty-state">No custom overrides yet. Use the form above to set limits for a specific date.</p>
    @else
      <div class="table-wrap table-cards quota-overrides-compact">
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
              @php
                $dateKey = \Illuminate\Support\Carbon::parse($row->quota_date)->toDateString();
                $dateLabel = \Illuminate\Support\Carbon::parse($row->quota_date)->format('D, M j, Y');
                $maxBk = $row->max_bookings;
              @endphp
              <tr>
                <td data-label="Date"><strong>{{ $dateLabel }}</strong></td>
                <td data-label="Persons / day">{{ $row->slots }}</td>
                <td data-label="Bookings / day">
                  @if ($maxBk === null)
                    {{ $defaultMaxBookings > 0 ? $defaultMaxBookings.' (default)' : 'No cap' }}
                  @else
                    {{ $maxBk }}
                  @endif
                </td>
                <td data-label="Note">{{ $row->note ?: '—' }}</td>
                <td class="actions-cell" data-label="">
                  <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    data-quota-edit
                    data-date="{{ $dateKey }}"
                    data-label="{{ $dateLabel }}"
                    data-slots="{{ $row->slots }}"
                    data-max-bookings="{{ $maxBk !== null ? $maxBk : '' }}"
                    data-note="{{ $row->note ?? '' }}"
                  >Edit</button>
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

@push('scripts')
  <script src="{{ asset('js/admin-quotas.js') }}" defer></script>
@endpush

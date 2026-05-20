@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
  <div class="page-header">
    <h1>Dashboard</h1>
    <p>Overview of bookings, hikers, and slot capacity for Atup-atup Falls.</p>
  </div>

  @if ($stats['pending'] > 0)
    <div class="quick-actions">
      <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="btn btn-primary">
        Review {{ $stats['pending'] }} pending {{ \Illuminate\Support\Str::plural('booking', $stats['pending']) }}
      </a>
      <a href="{{ route('admin.quotas.index') }}" class="btn btn-secondary">Manage quotas</a>
    </div>
  @else
    <div class="quick-actions">
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">All bookings</a>
      <a href="{{ route('admin.quotas.index') }}" class="btn btn-secondary">Manage quotas</a>
    </div>
  @endif

  <div class="stat-grid">
    <div class="stat-card featured">
      <div class="label">Pending review</div>
      <div class="value">{{ $stats['pending'] }}</div>
      <div class="hint">Awaiting your decision</div>
    </div>
    <div class="stat-card">
      <div class="label">Approved</div>
      <div class="value">{{ $stats['approved'] }}</div>
    </div>
    <div class="stat-card">
      <div class="label">Hikers today</div>
      <div class="value">{{ $stats['today_active'] }}</div>
      <div class="hint">Active reservations</div>
    </div>
    <div class="stat-card">
      <div class="label">Users</div>
      <div class="value">{{ $stats['total_users'] }}</div>
    </div>
    <div class="stat-card">
      <div class="label">Total bookings</div>
      <div class="value">{{ $stats['total_bookings'] }}</div>
    </div>
    <div class="stat-card">
      <div class="label">Default quota</div>
      <div class="value">{{ $stats['default_quota'] }}</div>
      <div class="hint">
        hikers/day
        @if ($stats['default_max_bookings'] > 0)
          · max {{ $stats['default_max_bookings'] }} groups
        @else
          · no group limit
        @endif
        · <a href="{{ route('admin.quotas.index') }}">Adjust</a>
      </div>
    </div>
  </div>

  <div class="grid-2">
    <div class="panel">
      <div class="panel-head">
        <h2>Recent bookings</h2>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.bookings.index') }}">View all</a>
      </div>
      @if ($recentBookings->isEmpty())
        <p class="empty-state">No bookings have been submitted yet.</p>
      @else
        <div class="table-wrap">
          <table class="data">
            <thead>
              <tr>
                <th>Reference</th>
                <th>User</th>
                <th>Date</th>
                <th>Party</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($recentBookings as $b)
                <tr>
                  <td><a href="{{ route('admin.bookings.show', $b) }}"><strong>{{ $b->reference_code }}</strong></a></td>
                  <td>
                    {{ $b->user?->name ?? '—' }}
                    @if ($b->user?->email)
                      <div class="sub">{{ $b->user->email }}</div>
                    @endif
                  </td>
                  <td>{{ $b->hike_date->format('M j, Y') }}</td>
                  <td>{{ $b->party_size }}</td>
                  <td><span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <div class="panel">
      <div class="panel-head">
        <h2>Next 7 days</h2>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.quotas.index') }}">Manage quotas</a>
      </div>
      <div class="avail-list">
        @foreach ($upcoming as $row)
          @php
            $pct = $row['quota'] > 0 ? min(100, round(($row['booked'] / $row['quota']) * 100)) : 0;
            $cls = ! $row['accepts_new_bookings'] ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
            if ($row['custom']) $cls .= ' custom';
          @endphp
          <div class="avail-row {{ trim($cls) }}">
            <div>
              <strong>{{ $row['label'] }}</strong>
              @if ($row['custom'])<span class="avail-badge">Custom</span>@endif
              @if ($row['note'])<span class="sub" style="display:block; margin-top:0.15rem;">{{ $row['note'] }}</span>@endif
            </div>
            <div class="avail-meta">
              <strong>{{ $row['booked'] }}</strong> / {{ $row['quota'] }} hikers
              @if ($row['max_bookings'] !== null)
                <br />{{ $row['bookings_booked'] }} / {{ $row['max_bookings'] }} groups
              @endif
              <br />{{ $row['remaining'] }} open
            </div>
            <div class="avail-progress" role="presentation"><span style="width: {{ $pct }}%;"></span></div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection

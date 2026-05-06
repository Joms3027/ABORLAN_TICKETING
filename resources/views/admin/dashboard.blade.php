@extends('layouts.portal')

@section('title', 'Admin dashboard')

@section('content')
  <div class="layout-shell">
    @include('admin.partials.side-nav')

    <div>
      <div class="page-header">
        <h1>Admin dashboard</h1>
        <p>Monitor bookings, hikers and slot capacity for Atup-atup Falls.</p>
      </div>

      <div class="stat-grid">
        <div class="stat-card">
          <div class="label">Pending review</div>
          <div class="value">{{ $stats['pending'] }}</div>
          <div class="hint">Awaiting admin decision</div>
        </div>
        <div class="stat-card">
          <div class="label">Approved (active)</div>
          <div class="value">{{ $stats['approved'] }}</div>
        </div>
        <div class="stat-card">
          <div class="label">Hikers booked today</div>
          <div class="value">{{ $stats['today_active'] }}</div>
          <div class="hint">Active reservations for today</div>
        </div>
        <div class="stat-card">
          <div class="label">Registered users</div>
          <div class="value">{{ $stats['total_users'] }}</div>
        </div>
        <div class="stat-card">
          <div class="label">Total bookings</div>
          <div class="value">{{ $stats['total_bookings'] }}</div>
        </div>
        <div class="stat-card">
          <div class="label">Default daily quota</div>
          <div class="value">{{ $stats['default_quota'] }}</div>
          <div class="hint"><a href="{{ route('admin.quotas.index') }}">Adjust →</a></div>
        </div>
      </div>

      <div class="grid-2">
        <div class="panel">
          <div class="panel-head">
            <h2>Recent bookings</h2>
            <a class="btn btn-secondary btn-sm" href="{{ route('admin.bookings.index') }}">View all</a>
          </div>
          @if ($recentBookings->isEmpty())
            <p style="color: var(--text-muted);">No bookings have been submitted yet.</p>
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
                      <td>{{ $b->user?->name ?? '—' }}<div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $b->user?->email }}</div></td>
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
            <a class="btn btn-secondary btn-sm" href="{{ route('admin.quotas.index') }}">Adjust quotas</a>
          </div>
          <div class="avail-list">
            @foreach ($upcoming as $row)
              @php
                $cls = $row['remaining'] === 0 ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
                if ($row['custom']) $cls .= ' custom';
              @endphp
              <div class="avail-row {{ trim($cls) }}">
                <div>
                  <strong>{{ $row['label'] }}</strong>
                  @if ($row['note'])<div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $row['note'] }}</div>@endif
                </div>
                <div style="text-align:right;">
                  {{ $row['booked'] }} / {{ $row['quota'] }} booked
                  <div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $row['remaining'] }} open</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

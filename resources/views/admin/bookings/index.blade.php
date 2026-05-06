@extends('layouts.portal')

@section('title', 'Manage bookings')

@section('content')
  <div class="layout-shell">
    @include('admin.partials.side-nav')

    <div>
      <div class="page-header">
        <h1>Bookings</h1>
        <p>Approve, reject, or review hiking permit applications submitted by visitors.</p>
      </div>

      <div class="panel">
        <form method="GET" action="{{ route('admin.bookings.index') }}" style="display:flex; flex-wrap:wrap; gap:0.6rem; margin-bottom:1rem;">
          <input type="search" name="q" class="input" placeholder="Search by reference, name, or email" value="{{ $q }}" style="flex: 1; min-width: 220px;" />
          <select name="status" class="select" style="max-width: 200px;">
            <option value="">All statuses</option>
            @foreach (['pending','approved','rejected','cancelled','completed'] as $s)
              <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary">Filter</button>
          <a class="btn btn-secondary" href="{{ route('admin.bookings.index') }}">Reset</a>
        </form>

        @if ($bookings->isEmpty())
          <p style="color: var(--text-muted);">No bookings match the current filters.</p>
        @else
          <div class="table-wrap">
            <table class="data">
              <thead>
                <tr>
                  <th>Reference</th>
                  <th>User</th>
                  <th>Hike date</th>
                  <th>Party</th>
                  <th>Status</th>
                  <th>Submitted</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($bookings as $b)
                  <tr>
                    <td><strong>{{ $b->reference_code }}</strong></td>
                    <td>
                      <div>{{ $b->user?->name ?? '—' }}</div>
                      <div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $b->user?->email }}</div>
                    </td>
                    <td>{{ $b->hike_date->format('M j, Y') }}</td>
                    <td>{{ $b->party_size }}</td>
                    <td><span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                    <td>{{ $b->created_at->format('M j, Y') }}</td>
                    <td><a class="btn btn-secondary btn-sm" href="{{ route('admin.bookings.show', $b) }}">Review</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div>{{ $bookings->links() }}</div>
        @endif
      </div>
    </div>
  </div>
@endsection

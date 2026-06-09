@extends('layouts.admin')

@section('title', 'Bookings')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <span>Bookings</span>
@endsection

@section('content')
  <div class="page-header">
    <h1>Bookings</h1>
    <p>Approve, reject, or review hiking permit applications.</p>
  </div>

  <div class="panel">
    <nav class="status-tabs" aria-label="Filter by status">
      <a href="{{ route('admin.bookings.index', request()->only('q')) }}" class="{{ ! $status ? 'is-active' : '' }}">All</a>
      @foreach (['pending','approved','rejected','cancelled','completed'] as $s)
        <a href="{{ route('admin.bookings.index', array_filter(['status' => $s, 'q' => $q])) }}" class="{{ $status === $s ? 'is-active' : '' }}">{{ ucfirst($s) }}</a>
      @endforeach
    </nav>

    <form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-bar">
      @if ($status)<input type="hidden" name="status" value="{{ $status }}" />@endif
      <input type="search" name="q" class="input" placeholder="Search reference, name, or email" value="{{ $q }}" />
      <button type="submit" class="btn btn-primary">Search</button>
      <a class="btn btn-secondary" href="{{ route('admin.bookings.index') }}">Reset</a>
    </form>

    @if ($bookings->isEmpty())
      <p class="empty-state">No bookings match the current filters.</p>
    @else
      <div class="table-wrap table-cards">
        <table class="data">
          <thead>
            <tr>
              <th>Reference</th>
              <th>User</th>
              <th>Hike date</th>
              <th>Party</th>
              <th>Status</th>
              <th>Tour guide</th>
              <th>Submitted</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($bookings as $b)
              <tr>
                <td data-label="Reference"><strong>{{ $b->reference_code }}</strong></td>
                <td data-label="User">
                  {{ $b->user?->name ?? '—' }}
                  @if ($b->user?->email)<div class="sub">{{ $b->user->email }}</div>@endif
                </td>
                <td data-label="Hike date">{{ $b->hike_date->format('M j, Y') }}</td>
                <td data-label="Party">{{ $b->party_size }}</td>
                <td data-label="Status"><span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                <td data-label="Tour guide">
                  @if ($b->status === 'approved' && $b->tourGuide)
                    {{ $b->tourGuide->name }}
                  @elseif ($b->status === 'approved')
                    <span class="muted" style="color: var(--danger); font-size: 0.8rem;">Unassigned</span>
                  @else
                    —
                  @endif
                </td>
                <td data-label="Submitted">{{ $b->created_at->format('M j, Y') }}</td>
                <td class="actions-cell" data-label="">
                  @if ($b->status === 'pending')
                    <form method="POST" action="{{ route('admin.bookings.update', $b) }}">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="approved" />
                      <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bookings.update', $b) }}" onsubmit="return confirm('Reject this permit application?');">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="rejected" />
                      <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                  @endif
                  <a class="btn btn-secondary btn-sm" href="{{ route('admin.bookings.show', $b) }}">Review</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div>{{ $bookings->links() }}</div>
    @endif
  </div>
@endsection

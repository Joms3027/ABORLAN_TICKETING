@extends('layouts.admin')

@section('title', $user->name)

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <a href="{{ route('admin.users.index') }}">Users</a>
  <span aria-hidden="true">/</span>
  <span>{{ $user->name }}</span>
@endsection

@section('content')
  <div class="page-header">
    <h1>{{ $user->name }}</h1>
    <p>{{ $user->email }} · joined {{ $user->created_at->format('F j, Y') }}</p>
  </div>

  <div class="grid-2">
    <div class="panel">
      <div class="panel-head"><h2>Profile</h2></div>
      <div class="detail-grid two-col">
        <div class="detail-item">
          <div class="detail-label">Mobile</div>
          <div class="detail-value">{{ $user->phone ?: '—' }}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Role</div>
          <div class="detail-value">
            @if ($user->is_admin)
              <span class="pill pill-completed">Administrator</span>
            @else
              <span class="pill">Visitor</span>
            @endif
          </div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Total bookings</div>
          <div class="detail-value">{{ $user->bookings->count() }}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Email verified</div>
          <div class="detail-value">{{ $user->email_verified_at ? $user->email_verified_at->format('M j, Y') : 'Not verified' }}</div>
        </div>
      </div>

      @if (auth()->id() !== $user->id)
        <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}" style="margin-top: 1rem;" onsubmit="return confirm('{{ $user->is_admin ? 'Remove admin role from this user?' : 'Promote this user to administrator?' }}');">
          @csrf
          <button type="submit" class="btn {{ $user->is_admin ? 'btn-warn' : 'btn-success' }}">
            {{ $user->is_admin ? 'Revoke admin role' : 'Promote to administrator' }}
          </button>
        </form>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin-top: 0.75rem;" onsubmit="return confirm('Delete {{ $user->name }}? Their {{ $user->bookings->count() }} booking(s) will also be removed.');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete user</button>
        </form>
      @endif
    </div>

    <div class="panel">
      <div class="panel-head"><h2>Booking summary</h2></div>
      @if ($user->bookings->isEmpty())
        <p class="empty-state">No bookings yet.</p>
      @else
        <div class="table-wrap table-cards">
          <table class="data">
            <thead>
              <tr>
                <th>Reference</th>
                <th>Hike date</th>
                <th>Party</th>
                <th>Status</th>
                <th>Permit form</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($user->bookings as $b)
                @php
                  $hasPermit = $b->visitor_address || $b->members || $b->purpose_of_visit;
                @endphp
                <tr>
                  <td data-label="Reference"><strong>{{ $b->reference_code }}</strong></td>
                  <td data-label="Hike date">{{ $b->hike_date->format('M j, Y') }}</td>
                  <td data-label="Party">{{ $b->party_size }}</td>
                  <td data-label="Status"><span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                  <td data-label="Permit form">
                    @if ($hasPermit)
                      <span class="pill pill-completed">On file</span>
                    @else
                      <span class="muted">—</span>
                    @endif
                  </td>
                  <td class="actions-cell" data-label="">
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.bookings.show', $b) }}">Open</a>
                    @if ($hasPermit)
                      <a class="btn btn-secondary btn-sm" href="#permit-{{ $b->id }}">Permit</a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  @if ($user->bookings->isNotEmpty())
    <div id="permit-applications" style="margin-top: 1.5rem;">
      <div class="page-header" style="margin-bottom: 1rem;">
        <h2 style="font-size: 1.35rem; color: var(--navy);">Visitors Entry Permit applications</h2>
        <p class="muted">Details submitted by this tourist when booking (same fields as the online permit form).</p>
      </div>

      @foreach ($user->bookings as $b)
        <div class="panel" id="permit-{{ $b->id }}" style="margin-bottom: 1.25rem;">
          <div class="panel-head">
            <div>
              <h2>{{ $b->reference_code }}</h2>
              <p class="muted" style="margin-top: 0.2rem; font-size: 0.88rem;">
                Hike {{ $b->hike_date->format('l, F j, Y') }} · submitted {{ $b->created_at->format('M j, Y g:i A') }}
              </p>
            </div>
            <span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span>
          </div>

          @include('bookings.partials.permit-application-body', ['booking' => $b])

          <div class="form-actions" style="margin-top: 1rem; padding-top: 0; border-top: none;">
            <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-primary btn-sm">Manage booking</a>
            <a href="{{ route('docs.view', ['f' => 'NAG-ATUP Visitors Entry Permit.pdf']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm">PDF template</a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
@endsection

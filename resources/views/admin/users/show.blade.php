@extends('layouts.portal')

@section('title', $user->name)

@section('content')
  <div class="layout-shell">
    @include('admin.partials.side-nav')

    <div>
      <div class="page-header">
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->email }} · joined {{ $user->created_at->format('F j, Y') }}</p>
      </div>

      <div class="grid-2">
        <div class="panel">
          <div class="panel-head"><h2>Profile</h2></div>
          <div class="form-grid two-col">
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Mobile</div>
              <div>{{ $user->phone ?: '—' }}</div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Role</div>
              <div>
                @if ($user->is_admin)
                  <span class="pill pill-completed">Administrator</span>
                @else
                  <span class="pill">Visitor</span>
                @endif
              </div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Total bookings</div>
              <div>{{ $user->bookings->count() }}</div>
            </div>
            <div>
              <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Email verified</div>
              <div>{{ $user->email_verified_at ? $user->email_verified_at->format('M j, Y') : 'Not verified' }}</div>
            </div>
          </div>

          @if (auth()->id() !== $user->id)
            <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}" style="margin-top: 1rem;" onsubmit="return confirm('{{ $user->is_admin ? 'Remove admin role from this user?' : 'Promote this user to administrator?' }}');">
              @csrf
              <button type="submit" class="btn {{ $user->is_admin ? 'btn-warn' : 'btn-success' }}">
                {{ $user->is_admin ? 'Revoke admin role' : 'Promote to administrator' }}
              </button>
            </form>
          @endif
        </div>

        <div class="panel">
          <div class="panel-head"><h2>Bookings ({{ $user->bookings->count() }})</h2></div>
          @if ($user->bookings->isEmpty())
            <p style="color: var(--text-muted);">This user has not booked any hiking permits yet.</p>
          @else
            <div class="table-wrap">
              <table class="data">
                <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Party</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($user->bookings as $b)
                    <tr>
                      <td><strong>{{ $b->reference_code }}</strong></td>
                      <td>{{ $b->hike_date->format('M j, Y') }}</td>
                      <td>{{ $b->party_size }}</td>
                      <td><span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span></td>
                      <td><a class="btn btn-secondary btn-sm" href="{{ route('admin.bookings.show', $b) }}">Open</a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.portal')

@section('title', 'Manage users')

@section('content')
  <div class="layout-shell">
    @include('admin.partials.side-nav')

    <div>
      <div class="page-header">
        <h1>Users</h1>
        <p>Registered visitors and administrators of the Atup-atup Falls booking portal.</p>
      </div>

      <div class="panel">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex; gap:0.6rem; margin-bottom:1rem;">
          <input type="search" name="q" class="input" placeholder="Search by name, email, or phone" value="{{ $q }}" style="flex: 1;" />
          <button type="submit" class="btn btn-primary">Search</button>
          @if ($q)<a class="btn btn-secondary" href="{{ route('admin.users.index') }}">Reset</a>@endif
        </form>

        @if ($users->isEmpty())
          <p style="color: var(--text-muted);">No users match the current search.</p>
        @else
          <div class="table-wrap">
            <table class="data">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Bookings</th>
                  <th>Role</th>
                  <th>Joined</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?: '—' }}</td>
                    <td>{{ $user->bookings_count }}</td>
                    <td>
                      @if ($user->is_admin)
                        <span class="pill pill-completed">Administrator</span>
                      @else
                        <span class="pill">Visitor</span>
                      @endif
                    </td>
                    <td>{{ $user->created_at->format('M j, Y') }}</td>
                    <td>
                      <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">View</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div>{{ $users->links() }}</div>
        @endif
      </div>
    </div>
  </div>
@endsection

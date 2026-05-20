@extends('layouts.admin')

@section('title', 'Users')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <span>Users</span>
@endsection

@section('content')
  <div class="page-header">
    <h1>Users</h1>
    <p>Registered visitors and administrators of the booking portal.</p>
  </div>

  <div class="panel">
    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
      <input type="search" name="q" class="input" placeholder="Search name, email, or phone" value="{{ $q }}" />
      <button type="submit" class="btn btn-primary">Search</button>
      @if ($q)<a class="btn btn-secondary" href="{{ route('admin.users.index') }}">Reset</a>@endif
    </form>

    @if ($users->isEmpty())
      <p class="empty-state">No users match the current search.</p>
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
                <td class="actions-cell">
                  <a href="{{ route('admin.users.show', $user) }}{{ $user->bookings_count > 0 ? '#permit-applications' : '' }}" class="btn btn-secondary btn-sm">
                    {{ $user->bookings_count > 0 ? 'Permits' : 'View' }}
                  </a>
                  @if (auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete {{ $user->name }}? Their {{ $user->bookings_count }} booking(s) will also be removed.');" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div>{{ $users->links() }}</div>
    @endif
  </div>
@endsection

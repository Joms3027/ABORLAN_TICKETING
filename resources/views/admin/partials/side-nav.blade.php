<aside class="side-nav">
  <h3>Admin tools</h3>
  <ul>
    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a></li>
    <li><a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'is-active' : '' }}">Bookings</a></li>
    <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">Users</a></li>
    <li><a href="{{ route('admin.quotas.index') }}" class="{{ request()->routeIs('admin.quotas.*') ? 'is-active' : '' }}">Daily quotas</a></li>
    <li><a href="{{ route('admin.homePage.index') }}" class="{{ request()->routeIs('admin.homePage.*') ? 'is-active' : '' }}">Home page</a></li>
  </ul>
</aside>

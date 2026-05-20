<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Atup-atup Falls Booking') · {{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy: #2a0a32;
      --navy-soft: #701a75;
      --teal: #c026d3;
      --teal-hover: #a21caf;
      --teal-muted: rgba(192, 38, 211, 0.14);
      --gold: #ca8a04;
      --gold-light: #ffea00;
      --magenta-bright: #e879f9;
      --text: #1a0a1f;
      --text-muted: #6b4a6e;
      --border: #f5d0f3;
      --surface: #ffffff;
      --bg: #fce7f3;
      --bg-subtle: #fffbeb;
      --radius: 12px;
      --radius-sm: 8px;
      --shadow-sm: 0 1px 2px rgba(88, 28, 135, 0.08);
      --shadow: 0 4px 24px rgba(190, 24, 93, 0.1);
      --shadow-lg: 0 20px 50px rgba(126, 34, 206, 0.14);
      --font: "Source Sans 3", system-ui, -apple-system, "Segoe UI", sans-serif;
      --ease: cubic-bezier(0.4, 0, 0.2, 1);
      --success: #16a34a;
      --warn: #d97706;
      --danger: #dc2626;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font);
      color: var(--text);
      background: linear-gradient(165deg, var(--bg-subtle) 0%, #fff4e6 35%, #fdf4ff 70%, #fef9c3 100%);
      line-height: 1.6;
      font-size: 1rem;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      display: flex; flex-direction: column;
    }
    a { color: var(--teal-hover); }
    a:hover { color: var(--navy); }
    .container { width: min(94%, 1180px); margin-inline: auto; }

    /* Top bar */
    .top-bar {
      background: linear-gradient(115deg, #4c0519 0%, #701a75 38%, #1e1b4b 100%);
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.8125rem;
      font-weight: 500;
      box-shadow: inset 0 -2px 0 var(--gold-light);
    }
    .top-bar .container {
      display: flex; flex-wrap: wrap;
      justify-content: space-between; align-items: center;
      gap: 0.5rem 1.5rem; padding: 0.45rem 0;
    }
    .top-bar strong { color: var(--gold-light); font-weight: 700; }

    /* Header */
    header.site-header {
      position: sticky; top: 0; z-index: 50;
      background: #fff;
      box-shadow: 0 4px 20px rgba(88, 28, 135, 0.06);
    }
    header.site-header::after {
      content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 4px;
      background: linear-gradient(90deg, var(--gold-light), var(--teal), var(--magenta-bright), var(--gold-light));
    }
    .nav {
      display: flex; flex-wrap: wrap; align-items: center;
      gap: 0.75rem 1.25rem; padding: 0.85rem 0;
    }
    .brand {
      display: flex; align-items: center; gap: 0.875rem;
      text-decoration: none; color: inherit;
    }
    .brand-logo { height: 64px; width: auto; max-width: 240px; object-fit: contain; flex-shrink: 0; }
    .brand-text { display: flex; flex-direction: column; line-height: 1.2; }
    .brand-text .name { font-weight: 700; font-size: 1.05rem; color: var(--navy); }
    .brand-text .tag { font-size: 0.72rem; font-weight: 500; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; }

    .nav-links { display: flex; flex-wrap: wrap; gap: 0.25rem; list-style: none; margin-left: auto; align-items: center; }
    .nav-links a {
      text-decoration: none;
      color: var(--navy);
      font-weight: 600;
      font-size: 0.875rem;
      padding: 0.45rem 0.85rem;
      border-radius: 999px;
      transition: background 0.2s var(--ease), color 0.2s var(--ease);
    }
    .nav-links a:hover { background: rgba(255, 234, 0, 0.45); color: var(--navy); }
    .nav-links a.is-active { background: rgba(192, 38, 211, 0.18); box-shadow: inset 0 0 0 2px var(--teal); }
    .nav-links .accent a {
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      color: #fff;
      box-shadow: 0 2px 14px rgba(192, 38, 211, 0.35);
    }
    .nav-links .accent a:hover {
      background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
      color: #fff;
    }
    .nav-user { display: inline-flex; gap: 0.5rem; align-items: center; }
    .nav-user-name { font-size: 0.8125rem; color: var(--text-muted); }
    .logout-form { margin: 0; }
    .logout-btn {
      background: transparent; border: 1px solid var(--border);
      color: var(--navy); padding: 0.4rem 0.8rem;
      border-radius: 999px; font-weight: 600;
      cursor: pointer; font-size: 0.8125rem; font-family: inherit;
    }
    .logout-btn:hover { background: var(--gold-light); color: var(--navy); }

    main { flex: 1; padding: clamp(1.5rem, 4vw, 3rem) 0; }

    .page-header { margin-bottom: 1.75rem; }
    .page-header h1 {
      font-size: clamp(1.5rem, 3vw, 2rem);
      font-weight: 700;
      color: var(--navy);
      letter-spacing: -0.02em;
      margin-bottom: 0.4rem;
    }
    .page-header p { color: var(--text-muted); font-size: 1rem; max-width: 60ch; }

    .panel {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: clamp(1.25rem, 2.5vw, 1.75rem);
      box-shadow: var(--shadow-sm);
    }
    .panel + .panel { margin-top: 1.25rem; }

    .panel-head {
      display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
      gap: 0.5rem 1rem; margin-bottom: 1rem;
    }
    .panel-head h2 {
      font-size: 1.125rem;
      color: var(--navy);
      font-weight: 700;
      letter-spacing: -0.01em;
    }
    .panel-head .muted { color: var(--text-muted); font-size: 0.875rem; }

    /* Buttons */
    .btn {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 0.4rem; padding: 0.6rem 1.1rem; border-radius: var(--radius-sm);
      font-family: inherit; font-weight: 600; font-size: 0.9375rem;
      text-decoration: none; cursor: pointer;
      border: none; transition: background 0.2s var(--ease), transform 0.15s var(--ease), box-shadow 0.2s var(--ease);
      white-space: nowrap;
    }
    .btn-primary { background: var(--teal); color: #fff; box-shadow: 0 2px 8px rgba(192, 38, 211, 0.25); }
    .btn-primary:hover { background: var(--teal-hover); color: #fff; transform: translateY(-1px); }
    .btn-secondary { background: transparent; color: var(--navy); border: 1px solid var(--border); }
    .btn-secondary:hover { border-color: var(--teal); color: var(--teal); background: var(--teal-muted); }
    .btn-success { background: var(--success); color: #fff; }
    .btn-success:hover { background: #15803d; color: #fff; }
    .btn-warn { background: #f59e0b; color: #1a0a1f; }
    .btn-warn:hover { background: #d97706; color: #fff; }
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-danger:hover { background: #b91c1c; color: #fff; }
    .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8125rem; }
    .btn-block { width: 100%; }

    /* Forms */
    .form-grid { display: grid; gap: 1rem; }
    .form-grid.two-col { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    .form-grid.three-col { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
    .field label {
      display: block;
      font-weight: 600;
      color: var(--navy);
      font-size: 0.8125rem;
      margin-bottom: 0.35rem;
      letter-spacing: 0.01em;
    }
    .field .hint { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .input, .select, .textarea {
      width: 100%;
      padding: 0.6rem 0.85rem;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 0.9375rem;
      color: var(--text);
      background: #fff;
      transition: border-color 0.15s var(--ease), box-shadow 0.15s var(--ease);
    }
    .input:focus, .select:focus, .textarea:focus {
      outline: none;
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.18);
    }
    .textarea { min-height: 100px; resize: vertical; }
    .field .error { color: var(--danger); font-size: 0.8125rem; margin-top: 0.3rem; }
    .form-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }

    /* Alerts */
    .alert {
      border-radius: var(--radius-sm);
      padding: 0.85rem 1rem;
      margin-bottom: 1.25rem;
      font-size: 0.9375rem;
      border: 1px solid transparent;
    }
    .alert-success { background: #dcfce7; color: #14532d; border-color: #86efac; }
    .alert-error { background: #fee2e2; color: #7f1d1d; border-color: #fca5a5; }

    /* Tables */
    .table-wrap { overflow-x: auto; border-radius: var(--radius-sm); border: 1px solid var(--border); }
    table.data {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.9rem;
      background: #fff;
    }
    table.data th, table.data td {
      padding: 0.7rem 0.85rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
    }
    table.data th {
      background: linear-gradient(180deg, #fff, #fdf4ff);
      font-weight: 700;
      color: var(--navy);
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }
    table.data tr:last-child td { border-bottom: none; }
    table.data tr:hover { background: rgba(255, 234, 0, 0.08); }

    /* Stat cards */
    .stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    .stat-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.1rem 1.25rem;
      box-shadow: var(--shadow-sm);
    }
    .stat-card .label {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      margin-bottom: 0.35rem;
    }
    .stat-card .value {
      font-size: 1.85rem;
      font-weight: 700;
      color: var(--navy);
      letter-spacing: -0.01em;
      line-height: 1.1;
    }
    .stat-card .hint { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem; }

    /* Status pills */
    .pill {
      display: inline-flex;
      align-items: center;
      padding: 0.2rem 0.65rem;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      background: var(--bg);
      color: var(--navy);
      border: 1px solid var(--border);
    }
    .pill-pending { background: #fef3c7; color: #78350f; border-color: #fcd34d; }
    .pill-approved { background: #dcfce7; color: #14532d; border-color: #86efac; }
    .pill-rejected { background: #fee2e2; color: #7f1d1d; border-color: #fca5a5; }
    .pill-cancelled { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
    .pill-completed { background: #ddd6fe; color: #4c1d95; border-color: #c4b5fd; }

    /* Footer */
    footer.portal {
      background: linear-gradient(180deg, #3b0764 0%, var(--navy) 28%, #1a0520 100%);
      color: rgba(255, 255, 255, 0.75);
      padding: 1.6rem 0;
      font-size: 0.8125rem;
      text-align: center;
      position: relative;
      margin-top: auto;
    }
    footer.portal::before {
      content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, var(--gold-light), var(--teal), var(--magenta-bright), var(--gold-light));
    }

    /* Auth pages */
    .auth-wrap {
      min-height: calc(100vh - 220px);
      display: grid;
      place-items: center;
      padding: 2rem 0;
    }
    .auth-card {
      width: min(96%, 460px);
      background: #fff;
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      padding: 2rem 2rem 1.75rem;
      border: 1px solid var(--border);
    }
    .auth-card h1 {
      font-size: 1.6rem;
      color: var(--navy);
      letter-spacing: -0.02em;
      margin-bottom: 0.35rem;
    }
    .auth-card p.sub { color: var(--text-muted); margin-bottom: 1.4rem; font-size: 0.95rem; }
    .auth-card .form-grid + .form-actions { margin-top: 1.25rem; }

    /* Two-column dashboard layout */
    .grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
    @media (min-width: 900px) { .grid-2 { grid-template-columns: 1.4fr 1fr; } }

    /* Availability mini-grid */
    .avail-list { display: grid; gap: 0.5rem; }
    .avail-row {
      display: grid;
      grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
      gap: 0.5rem;
      align-items: center;
      padding: 0.55rem 0.8rem;
      border-radius: var(--radius-sm);
      background: #fdf4ff;
      border: 1px solid var(--border);
      font-size: 0.875rem;
    }
    .avail-row strong { color: var(--navy); }
    .avail-row.full { background: #fee2e2; border-color: #fca5a5; }
    .avail-row.tight { background: #fef3c7; border-color: #fcd34d; }
    .avail-row.custom::before { content: "★ "; color: var(--teal); }

    /* Hero card on overview page */
    .place-hero {
      position: relative; overflow: hidden; border-radius: var(--radius);
      color: #fff; padding: clamp(2rem, 5vw, 3rem); margin-bottom: 1.75rem;
      box-shadow: var(--shadow-lg);
    }
    .place-hero::before {
      content: ""; position: absolute; inset: 0; z-index: 0;
      background:
        linear-gradient(125deg, rgba(42, 10, 50, 0.85) 0%, rgba(112, 26, 117, 0.7) 50%, rgba(15, 92, 85, 0.78) 100%),
        var(--hero-img) center/cover no-repeat;
    }
    .place-hero > * { position: relative; z-index: 1; }
    .place-hero .eyebrow {
      font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.12em; color: var(--gold-light);
    }
    .place-hero h1 {
      font-size: clamp(1.7rem, 3.5vw, 2.4rem); font-weight: 700;
      letter-spacing: -0.02em; margin: 0.5rem 0 0.75rem; max-width: 22ch;
    }
    .place-hero p { color: rgba(255, 255, 255, 0.9); max-width: 60ch; margin-bottom: 1.25rem; }
    .place-hero .cta-row { display: flex; gap: 0.65rem; flex-wrap: wrap; }
    .place-hero .btn-light { background: #fff; color: var(--navy); }
    .place-hero .btn-light:hover { background: var(--gold-light); color: var(--navy); }
    .place-hero .btn-ghost { background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.4); }
    .place-hero .btn-ghost:hover { background: rgba(255,255,255,0.22); color: #fff; }

    /* Highlight gallery */
    .highlight-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1rem;
    }
    .highlight {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: transform 0.25s var(--ease), box-shadow 0.25s var(--ease);
    }
    .highlight:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
    .highlight img { width: 100%; height: 180px; object-fit: cover; display: block; }
    .highlight .body { padding: 0.95rem 1.05rem 1.1rem; }
    .highlight h3 { font-size: 1rem; color: var(--navy); margin-bottom: 0.3rem; }
    .highlight p { color: var(--text-muted); font-size: 0.875rem; line-height: 1.45; }

    /* Pagination */
    .pagination {
      list-style: none;
      display: flex; flex-wrap: wrap; gap: 0.25rem;
      margin-top: 1.25rem;
    }
    .pagination a, .pagination span {
      padding: 0.4rem 0.75rem;
      border-radius: var(--radius-sm);
      font-size: 0.875rem;
      color: var(--navy);
      background: #fff;
      border: 1px solid var(--border);
      text-decoration: none;
    }
    .pagination .active span,
    .pagination a:hover { background: var(--teal-muted); border-color: var(--teal); color: var(--teal-hover); }
    .pagination .disabled span { opacity: 0.5; }

    /* Sidebar layout for admin */
    .layout-shell { display: grid; grid-template-columns: 1fr; gap: 1.25rem; align-items: start; }
    @media (min-width: 880px) { .layout-shell { grid-template-columns: 240px 1fr; } }
    .side-nav {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1rem;
      box-shadow: var(--shadow-sm);
      position: sticky; top: 110px;
    }
    .side-nav h3 {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      margin-bottom: 0.7rem;
      padding: 0 0.5rem;
    }
    .side-nav ul { list-style: none; display: flex; flex-direction: column; gap: 0.2rem; }
    .side-nav a {
      display: flex; align-items: center; gap: 0.55rem;
      padding: 0.55rem 0.75rem;
      border-radius: var(--radius-sm);
      font-weight: 600; font-size: 0.9rem;
      color: var(--navy);
      text-decoration: none;
    }
    .side-nav a:hover { background: var(--teal-muted); color: var(--teal-hover); }
    .side-nav a.is-active { background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%); color: #fff; box-shadow: 0 2px 12px rgba(192, 38, 211, 0.3); }
    .side-nav a.is-active:hover { color: #fff; }
  </style>
  @stack('head')
</head>
<body>
  @php
    $authUser = auth()->user();
    $isAdmin = $authUser?->is_admin;
  @endphp

  <div class="top-bar">
    <div class="container">
      <span><strong>Atup-atup Falls</strong> · Hiking permit booking · Barangay Culandanum, Aborlan</span>
      <span>Entry point: Sitio Manaile, Brgy. Dumanguena, Narra, Palawan</span>
    </div>
  </div>

  <header class="site-header">
    <div class="container nav">
      <a class="brand" href="{{ url('/') }}">
        <img class="brand-logo"
             src="{{ asset('images/Logo.png') }}"
             alt="Seal of the Municipality of Aborlan"
             decoding="async" />
        <span class="brand-text">
          <span class="name">{{ config('app.name') }}</span>
          <span class="tag">Atup-atup Falls Permit System</span>
        </span>
      </a>

      <ul class="nav-links">
        <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'is-active' : '' }}">Home</a></li>
        <li><a href="{{ route('atup.overview') }}" class="{{ request()->routeIs('atup.*') ? 'is-active' : '' }}">Atup-atup Falls</a></li>
        @auth
          @if ($isAdmin)
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'is-active' : '' }}">Admin</a></li>
          @else
            <li><a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'is-active' : '' }}">My bookings</a></li>
          @endif
          <li class="nav-user">
            <span class="nav-user-name">Hi, {{ $authUser->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
              @csrf
              <button type="submit" class="logout-btn">Sign out</button>
            </form>
          </li>
        @else
          <li><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'is-active' : '' }}">Sign in</a></li>
          <li class="accent"><a href="{{ route('register') }}">Create account</a></li>
        @endauth
      </ul>
    </div>
  </header>

  @if (session('status'))
    <div class="container" style="padding-top: 1rem;">
      <div class="alert alert-success">{{ session('status') }}</div>
    </div>
  @endif

  @if ($errors->any() && ! isset($suppressGlobalErrors))
    <div class="container" style="padding-top: 1rem;">
      <div class="alert alert-error">
        <strong>Please fix the following:</strong>
        <ul style="margin: 0.4rem 0 0 1.1rem;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <main>
    <div class="container">
      @yield('content')
    </div>
  </main>

  <footer class="portal">
    <div class="container">
      &copy; {{ date('Y') }} Municipality of Aborlan · Atup-atup Falls Permit Booking System
    </div>
  </footer>

  @stack('scripts')
</body>
</html>

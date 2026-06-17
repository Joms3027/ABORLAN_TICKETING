<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Atup-atup Falls Booking') · {{ config('app.name') }}</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />
  @auth
    <link rel="prefetch" href="{{ route('bookings.create') }}" as="document" />
    <link rel="prefetch" href="{{ route('bookings.index') }}" as="document" />
  @endauth
  <link rel="prefetch" href="{{ route('atup.overview') }}" as="document" />
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
      --sidebar-bg: linear-gradient(180deg, #2a0a32 0%, #1a0520 55%, #0f172a 100%);
      --sidebar-width: 260px;
      --topbar-h: 64px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body.portal-body {
      font-family: var(--font);
      color: var(--text);
      background: linear-gradient(165deg, var(--bg-subtle) 0%, #fff4e6 35%, #fdf4ff 70%, #fef9c3 100%);
      line-height: 1.6;
      font-size: 1rem;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      overflow-x: clip;
    }
    a { color: var(--teal-hover); }
    a:hover { color: var(--navy); }
    .container { width: min(94%, 1180px); margin-inline: auto; }

    /* App shell */
    .portal-app {
      display: grid;
      grid-template-columns: 1fr;
      min-height: 100vh;
    }
    @media (min-width: 960px) {
      .portal-app { grid-template-columns: var(--sidebar-width) 1fr; }
    }

    /* Sidebar */
    .portal-sidebar {
      background: var(--sidebar-bg);
      color: rgba(255, 255, 255, 0.88);
      display: flex;
      flex-direction: column;
      position: fixed;
      inset: 0 auto 0 0;
      width: min(88vw, var(--sidebar-width));
      z-index: 100;
      transform: translateX(-100%);
      transition: transform 0.25s var(--ease);
      box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
    }
    .portal-app.sidebar-open .portal-sidebar { transform: translateX(0); }
    @media (min-width: 960px) {
      .portal-sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        transform: none;
        box-shadow: none;
      }
    }
    .portal-sidebar-brand {
      padding: 1.25rem 1.15rem 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .portal-sidebar-brand a {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: #fff;
      text-decoration: none;
    }
    .portal-sidebar-brand a:hover { color: var(--gold-light); }
    .portal-sidebar-brand img {
      height: 44px;
      width: auto;
      filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
    }
    .portal-sidebar-brand .title {
      font-weight: 700;
      font-size: 0.95rem;
      line-height: 1.25;
    }
    .portal-sidebar-brand .tag {
      display: block;
      font-size: 0.68rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255, 255, 255, 0.55);
      margin-top: 0.15rem;
    }
    .portal-sidebar-nav { flex: 1; overflow-y: auto; padding: 0.85rem 0.75rem; }
    .portal-sidebar-label {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: rgba(255, 255, 255, 0.45);
      padding: 0 0.65rem;
      margin-bottom: 0.5rem;
    }
    .portal-nav { list-style: none; display: flex; flex-direction: column; gap: 0.15rem; }
    .portal-nav a {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      padding: 0.6rem 0.75rem;
      border-radius: var(--radius-sm);
      font-weight: 600;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.82);
      text-decoration: none;
      transition: background 0.15s var(--ease), color 0.15s var(--ease);
    }
    .portal-nav a:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
    }
    .portal-nav a.is-active {
      background: linear-gradient(135deg, var(--teal) 0%, #9333ea 100%);
      color: #fff;
      box-shadow: 0 2px 12px rgba(192, 38, 211, 0.35);
    }
    .portal-nav a.is-active:hover { color: #fff; }
    .portal-nav a.accent-cta {
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      color: #fff;
      box-shadow: 0 2px 12px rgba(192, 38, 211, 0.3);
      margin-top: 0.35rem;
    }
    .portal-nav a.accent-cta:hover {
      background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
      color: #fff;
    }
    .nav-icon { width: 1.15rem; height: 1.15rem; flex-shrink: 0; opacity: 0.9; }
    .portal-sidebar-foot {
      padding: 0.85rem 0.75rem 1.1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .portal-sidebar-user {
      font-size: 0.8125rem;
      color: rgba(255, 255, 255, 0.65);
      padding: 0 0.75rem 0.65rem;
      line-height: 1.35;
    }
    .portal-sidebar-user strong { color: #fff; font-weight: 600; }
    .portal-sidebar-foot a,
    .portal-sidebar-foot button {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      width: 100%;
      padding: 0.55rem 0.75rem;
      border-radius: var(--radius-sm);
      font-size: 0.85rem;
      font-weight: 600;
      font-family: inherit;
      color: rgba(255, 255, 255, 0.75);
      background: transparent;
      border: none;
      cursor: pointer;
      text-decoration: none;
    }
    .portal-sidebar-foot a:hover,
    .portal-sidebar-foot button:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
    }
    .sidebar-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(26, 5, 32, 0.5);
      z-index: 90;
      backdrop-filter: blur(2px);
    }
    .portal-app.sidebar-open .sidebar-backdrop { display: block; }
    @media (min-width: 960px) { .sidebar-backdrop { display: none !important; } }

    /* Main */
    .portal-main {
      display: flex;
      flex-direction: column;
      min-width: 0;
      min-height: 100vh;
    }
    .portal-topbar {
      position: sticky;
      top: 0;
      z-index: 40;
      display: flex;
      align-items: center;
      gap: 0.75rem 1rem;
      flex-wrap: wrap;
      min-height: var(--topbar-h);
      padding: 0.75rem clamp(1rem, 3vw, 1.75rem);
      background: #fff;
      border-bottom: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
    }
    .portal-topbar::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--gold-light), var(--teal), var(--magenta-bright), var(--gold-light));
    }
    .menu-toggle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.5rem;
      height: 2.5rem;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: #fff;
      cursor: pointer;
      color: var(--navy);
    }
    .menu-toggle:hover { background: var(--teal-muted); border-color: var(--teal); }
    @media (min-width: 960px) { .menu-toggle { display: none; } }
    .portal-topbar-title { flex: 1; min-width: 0; }
    .portal-topbar-title .eyebrow {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
    }
    .portal-topbar-title h1 {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--navy);
      letter-spacing: -0.02em;
      line-height: 1.2;
    }
    .portal-topbar-actions {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      flex-wrap: wrap;
    }
    .portal-user-chip {
      font-size: 0.8125rem;
      color: var(--text-muted);
      padding: 0.35rem 0.65rem;
      background: var(--bg);
      border-radius: 999px;
      border: 1px solid var(--border);
    }
    .portal-user-chip strong { color: var(--navy); }

    .portal-content { flex: 1; padding: clamp(1.25rem, 3vw, 2rem) clamp(1rem, 3vw, 1.75rem) 2.5rem; }

    .page-header { margin-bottom: 1.75rem; }
    .page-header h1 {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
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
    .panel-head--stack .pill { flex-shrink: 0; }
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
      padding: 1.6rem clamp(1rem, 3vw, 1.75rem) calc(1.6rem + env(safe-area-inset-bottom, 0px));
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
      min-height: calc(100vh - 14rem);
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
      position: sticky; top: 1rem;
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

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    /* Responsive tables → stacked cards on small screens */
    @media (max-width: 720px) {
      .table-wrap.table-cards {
        overflow: visible;
        border: none;
        background: transparent;
      }
      .table-wrap.table-cards table.data { background: transparent; }
      .table-wrap.table-cards table.data thead { display: none; }
      .table-wrap.table-cards table.data tbody tr {
        display: block;
        margin-bottom: 0.75rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: #fff;
        box-shadow: var(--shadow-sm);
      }
      .table-wrap.table-cards table.data tbody tr:hover { background: #fff; }
      .table-wrap.table-cards table.data td {
        display: grid;
        grid-template-columns: minmax(0, 38%) 1fr;
        gap: 0.25rem 0.75rem;
        padding: 0.6rem 0.85rem;
        border-bottom: 1px solid var(--border);
        text-align: left;
      }
      .table-wrap.table-cards table.data td:last-child { border-bottom: none; }
      .table-wrap.table-cards table.data td::before {
        content: attr(data-label);
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
      }
      .table-wrap.table-cards table.data td.actions-cell {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        align-items: center;
      }
      .table-wrap.table-cards table.data td.actions-cell::before { display: none; }
    }

    @media (max-width: 640px) {
      .container { width: min(96%, 1180px); }
      .panel-head {
        flex-direction: column;
        align-items: stretch;
      }
      .panel-head .btn { width: 100%; justify-content: center; }
      .panel-head h2 { font-size: 1.05rem; }
      .form-actions { flex-direction: column; }
      .form-actions .btn,
      .form-actions form .btn { width: 100%; }
      .form-actions form { width: 100%; margin: 0; }
      .input, .select, .textarea { font-size: 16px; }
      .btn { min-height: 44px; }
      .auth-card { padding: 1.5rem 1.25rem; }
      .place-hero .cta-row .btn { flex: 1 1 100%; justify-content: center; }
      .feedback-stars label { font-size: 2rem; padding: 0.15rem; }
      .portal-topbar-actions { width: 100%; }
      .portal-topbar-actions .btn { flex: 1; justify-content: center; }
      .portal-user-chip { width: 100%; text-align: center; }
    }

    @media (max-width: 480px) {
      .stat-card .value { font-size: 1.5rem; }
    }
  </style>
  @stack('head')
</head>
<body class="portal-body">
  @php
    $authUser = auth()->user();
    $isAdmin = $authUser?->is_admin;
    $pageTitle = trim($__env->yieldContent('title')) ?: config('app.name');
  @endphp

  <div class="portal-app" id="portalApp">
    <div class="sidebar-backdrop" id="portalSidebarBackdrop" aria-hidden="true"></div>

    <aside class="portal-sidebar" id="portalSidebar" aria-label="Site navigation">
      <div class="portal-sidebar-brand">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/Logo.png') }}" alt="Seal of the Municipality of Aborlan" decoding="async" />
          <span>
            <span class="title">{{ config('app.name') }}</span>
            <span class="tag">Atup-atup Falls Permit System</span>
          </span>
        </a>
      </div>

      <nav class="portal-sidebar-nav">
        <div class="portal-sidebar-label">Menu</div>
        <ul class="portal-nav">
          <li>
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'is-active' : '' }}">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              Home
            </a>
          </li>
          <li>
            <a href="{{ route('atup.overview') }}" class="{{ request()->routeIs('atup.*') ? 'is-active' : '' }}">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
              Atup-atup Falls
            </a>
          </li>
          @auth
            @if ($isAdmin)
              <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'is-active' : '' }}">
                  <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                  Admin
                </a>
              </li>
            @else
              <li>
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'is-active' : '' }}">
                  <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  My bookings
                </a>
              </li>
            @endif
          @else
            <li>
              <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'is-active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                Sign in
              </a>
            </li>
            <li>
              <a href="{{ route('register') }}" class="accent-cta {{ request()->routeIs('register') ? 'is-active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Create account
              </a>
            </li>
          @endauth
        </ul>
      </nav>

      <div class="portal-sidebar-foot">
        @auth
          <div class="portal-sidebar-user">Signed in as <strong>{{ $authUser->name }}</strong></div>
          <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
              Sign out
            </button>
          </form>
        @endauth
      </div>
    </aside>

    <div class="portal-main">
      <header class="portal-topbar">
        <button type="button" class="menu-toggle" id="portalMenuToggle" aria-label="Open menu" aria-expanded="false" aria-controls="portalSidebar">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="portal-topbar-title">
          <div class="eyebrow">Booking portal</div>
          <h1>{{ $pageTitle }}</h1>
        </div>
        <div class="portal-topbar-actions">
          @auth
            <span class="portal-user-chip">Hi, <strong>{{ $authUser->name }}</strong></span>
          @else
            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Sign in</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Create account</a>
          @endauth
        </div>
      </header>

      <main class="portal-content">
        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any() && ! isset($suppressGlobalErrors))
          <div class="alert alert-error">
            <strong>Please fix the following:</strong>
            <ul style="margin: 0.4rem 0 0 1.1rem;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @yield('content')
      </main>

      <footer class="portal">
        &copy; {{ date('Y') }} Municipality of Aborlan · Atup-atup Falls Permit Booking System
      </footer>
    </div>
  </div>

  <script>
    (function () {
      var app = document.getElementById('portalApp');
      var toggle = document.getElementById('portalMenuToggle');
      var backdrop = document.getElementById('portalSidebarBackdrop');
      function setOpen(open) {
        app.classList.toggle('sidebar-open', open);
        if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      }
      if (toggle) toggle.addEventListener('click', function () { setOpen(!app.classList.contains('sidebar-open')); });
      if (backdrop) backdrop.addEventListener('click', function () { setOpen(false); });
      document.querySelectorAll('.portal-nav a, .portal-sidebar-foot a, .portal-sidebar-foot button').forEach(function (link) {
        link.addEventListener('click', function () {
          if (window.matchMedia('(max-width: 959px)').matches) setOpen(false);
        });
      });
    })();
  </script>
  @auth
    @include('partials.logout-confirm-modal')
  @endauth
  <script src="{{ asset('js/logout-confirm.js') }}" defer></script>
  @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin') · {{ config('app.name') }}</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />
  <link rel="prefetch" href="{{ route('admin.bookings.index') }}" as="document" />
  <link rel="prefetch" href="{{ route('admin.dashboard') }}" as="document" />
  <style>
    :root {
      --navy: #2a0a32;
      --navy-soft: #701a75;
      --admin-accent: #c026d3;
      --admin-accent-hover: #a21caf;
      --admin-accent-muted: rgba(192, 38, 211, 0.12);
      --gold: #ca8a04;
      --gold-light: #ffea00;
      --text: #1a0a1f;
      --text-muted: #6b4a6e;
      --border: #e9d5ef;
      --surface: #ffffff;
      --bg: #f4f0f7;
      --sidebar-bg: linear-gradient(180deg, #2a0a32 0%, #1a0520 55%, #0f172a 100%);
      --sidebar-width: 260px;
      --topbar-h: 64px;
      --radius: 12px;
      --radius-sm: 8px;
      --shadow-sm: 0 1px 2px rgba(42, 10, 50, 0.06);
      --shadow: 0 4px 20px rgba(42, 10, 50, 0.08);
      --font: "Source Sans 3", system-ui, -apple-system, "Segoe UI", sans-serif;
      --ease: cubic-bezier(0.4, 0, 0.2, 1);
      --success: #16a34a;
      --warn: #d97706;
      --danger: #dc2626;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { overflow-x: clip; -webkit-text-size-adjust: 100%; }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
      }
    }
    body.admin-body {
      font-family: var(--font);
      color: var(--text);
      background: var(--bg);
      line-height: 1.6;
      font-size: 1rem;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      min-height: 100dvh;
      overflow-x: clip;
    }
    body.admin-body.sidebar-locked { overflow: hidden; touch-action: none; }
    a { color: var(--admin-accent-hover); text-decoration: none; }
    a:hover { color: var(--navy); }

    .admin-app {
      display: grid;
      grid-template-columns: 1fr;
      min-height: 100vh;
    }
    @media (min-width: 960px) {
      .admin-app { grid-template-columns: var(--sidebar-width) 1fr; }
    }

    /* Sidebar */
    .admin-sidebar {
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
    .admin-app.sidebar-open .admin-sidebar { transform: translateX(0); }
    @media (min-width: 960px) {
      .admin-sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
        transform: none;
        box-shadow: none;
      }
    }
    .admin-sidebar-brand {
      padding: 1.25rem 1.15rem 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .admin-sidebar-brand a {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: #fff;
      text-decoration: none;
    }
    .admin-sidebar-brand a:hover { color: var(--gold-light); }
    .admin-sidebar-brand img {
      height: 44px;
      width: auto;
      filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
    }
    .admin-sidebar-brand .title {
      font-weight: 700;
      font-size: 0.95rem;
      line-height: 1.25;
    }
    .admin-sidebar-brand .tag {
      display: block;
      font-size: 0.68rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255, 255, 255, 0.55);
      margin-top: 0.15rem;
    }
    .admin-sidebar-nav { flex: 1; overflow-y: auto; padding: 0.85rem 0.75rem; }
    .admin-sidebar-label {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: rgba(255, 255, 255, 0.45);
      padding: 0 0.65rem;
      margin-bottom: 0.5rem;
    }
    .admin-nav { list-style: none; display: flex; flex-direction: column; gap: 0.15rem; }
    .admin-nav a {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      min-height: 44px;
      padding: 0.6rem 0.75rem;
      border-radius: var(--radius-sm);
      font-weight: 600;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.82);
      text-decoration: none;
      transition: background 0.15s var(--ease), color 0.15s var(--ease);
    }
    .admin-nav a:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
    }
    .admin-nav a.is-active {
      background: linear-gradient(135deg, var(--admin-accent) 0%, #9333ea 100%);
      color: #fff;
      box-shadow: 0 2px 12px rgba(192, 38, 211, 0.35);
    }
    .admin-nav a.is-active:hover { color: #fff; }
    .nav-icon { width: 1.15rem; height: 1.15rem; flex-shrink: 0; opacity: 0.9; }
    .nav-badge {
      margin-left: auto;
      background: var(--gold-light);
      color: var(--navy);
      font-size: 0.68rem;
      font-weight: 700;
      padding: 0.1rem 0.45rem;
      border-radius: 999px;
      min-width: 1.25rem;
      text-align: center;
    }
    .admin-sidebar-foot {
      padding: 0.85rem 0.75rem 1.1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .admin-sidebar-foot a,
    .admin-sidebar-foot button {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      width: 100%;
      min-height: 44px;
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
    .admin-sidebar-foot a:hover,
    .admin-sidebar-foot button:hover {
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
    .admin-app.sidebar-open .sidebar-backdrop { display: block; }
    @media (min-width: 960px) { .sidebar-backdrop { display: none !important; } }

    /* Main */
    .admin-main {
      display: flex;
      flex-direction: column;
      min-width: 0;
      min-height: 100vh;
    }
    .admin-topbar {
      position: sticky;
      top: 0;
      z-index: 40;
      display: flex;
      align-items: center;
      gap: 0.75rem 1rem;
      flex-wrap: wrap;
      min-height: var(--topbar-h);
      padding: 0.75rem clamp(1rem, 3vw, 1.75rem);
      padding-top: max(0.75rem, env(safe-area-inset-top, 0px));
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
    }
    .admin-topbar::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--gold-light), var(--admin-accent), #e879f9, var(--gold-light));
    }
    .menu-toggle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.75rem;
      height: 2.75rem;
      min-width: 44px;
      min-height: 44px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: #fff;
      cursor: pointer;
      color: var(--navy);
    }
    .menu-toggle:hover { background: var(--admin-accent-muted); border-color: var(--admin-accent); }
    @media (min-width: 960px) { .menu-toggle { display: none; } }
    .admin-topbar-title {
      flex: 1;
      min-width: 0;
    }
    .admin-topbar-title .eyebrow {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
    }
    .admin-topbar-title h1 {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--navy);
      letter-spacing: -0.02em;
      line-height: 1.2;
    }
    .admin-topbar-actions {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      flex-wrap: wrap;
    }
    .admin-user-chip {
      font-size: 0.8125rem;
      color: var(--text-muted);
      padding: 0.35rem 0.65rem;
      background: var(--bg);
      border-radius: 999px;
      border: 1px solid var(--border);
    }
    .admin-user-chip strong { color: var(--navy); }

    .admin-content {
      flex: 1;
      padding: clamp(1.25rem, 3vw, 2rem) clamp(1rem, 3vw, 1.75rem) 2.5rem;
    }

    /* Shared components */
    .page-header { margin-bottom: 1.25rem; }
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
    .page-header p { color: var(--text-muted); font-size: 0.975rem; max-width: 65ch; }

    .quick-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }

    .panel {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: clamp(1.15rem, 2.5vw, 1.6rem);
      box-shadow: var(--shadow-sm);
    }
    .panel + .panel { margin-top: 1.15rem; }
    .panel-head {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem 1rem;
      margin-bottom: 1rem;
    }
    .panel-head h2 {
      font-size: 1.05rem;
      color: var(--navy);
      font-weight: 700;
    }
    .panel-head .muted { color: var(--text-muted); font-size: 0.8125rem; }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      padding: 0.55rem 1rem;
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: background 0.2s var(--ease), transform 0.12s var(--ease), box-shadow 0.2s var(--ease);
      white-space: nowrap;
    }
    .btn-primary {
      background: var(--admin-accent);
      color: #fff;
      box-shadow: 0 2px 8px rgba(192, 38, 211, 0.25);
    }
    .btn-primary:hover { background: var(--admin-accent-hover); color: #fff; transform: translateY(-1px); }
    .btn-secondary {
      background: #fff;
      color: var(--navy);
      border: 1px solid var(--border);
    }
    .btn-secondary:hover {
      border-color: var(--admin-accent);
      color: var(--admin-accent-hover);
      background: var(--admin-accent-muted);
    }
    .btn-success { background: var(--success); color: #fff; }
    .btn-success:hover { background: #15803d; color: #fff; }
    .btn-warn { background: #f59e0b; color: #1a0a1f; }
    .btn-warn:hover { background: var(--warn); color: #fff; }
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-danger:hover { background: #b91c1c; color: #fff; }
    .btn-sm { padding: 0.38rem 0.75rem; font-size: 0.8125rem; }
    .btn-ghost {
      background: transparent;
      color: var(--text-muted);
      border: 1px solid transparent;
    }
    .btn-ghost:hover { background: var(--bg); color: var(--navy); border-color: var(--border); }

    .form-grid { display: grid; gap: 1rem; }
    .form-grid.two-col { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    .field label {
      display: block;
      font-weight: 600;
      color: var(--navy);
      font-size: 0.8125rem;
      margin-bottom: 0.35rem;
    }
    .field .hint { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .input, .select, .textarea {
      width: 100%;
      padding: 0.58rem 0.85rem;
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
      border-color: var(--admin-accent);
      box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.15);
    }
    .textarea { min-height: 100px; resize: vertical; }
    .form-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }

    .alert {
      border-radius: var(--radius-sm);
      padding: 0.85rem 1rem;
      margin-bottom: 1.15rem;
      font-size: 0.9rem;
      border: 1px solid transparent;
    }
    .alert-success { background: #dcfce7; color: #14532d; border-color: #86efac; }
    .alert-error { background: #fee2e2; color: #7f1d1d; border-color: #fca5a5; }
    .alert-info { background: #ede9fe; color: #4c1d95; border-color: #c4b5fd; }

    .table-wrap {
      overflow-x: auto;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
    }
    table.data {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.875rem;
      background: #fff;
    }
    table.data th, table.data td {
      padding: 0.72rem 0.9rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }
    table.data th {
      background: #faf5ff;
      font-weight: 700;
      color: var(--navy);
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      white-space: nowrap;
    }
    table.data tr:last-child td { border-bottom: none; }
    table.data tbody tr { transition: background 0.12s var(--ease); }
    table.data tbody tr:hover { background: rgba(192, 38, 211, 0.04); }
    table.data .sub { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.1rem; }
    table.data td.actions-cell {
      text-align: right;
      vertical-align: middle;
      white-space: nowrap;
    }
    table.data td.actions-cell form {
      display: inline-flex;
      margin: 0;
      vertical-align: middle;
    }
    table.data td.actions-cell form + form,
    table.data td.actions-cell form + a,
    table.data td.actions-cell a + form {
      margin-left: 0.4rem;
    }
    table.data td.actions-cell > a {
      vertical-align: middle;
    }

    .stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 0.85rem;
      margin-bottom: 1.5rem;
    }
    .stat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1rem 1.1rem;
      box-shadow: var(--shadow-sm);
      transition: box-shadow 0.2s var(--ease), transform 0.2s var(--ease);
    }
    .stat-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
    .stat-card.featured {
      border-color: var(--admin-accent);
      background: linear-gradient(135deg, #fff 0%, #fdf4ff 100%);
      box-shadow: 0 4px 16px rgba(192, 38, 211, 0.12);
    }
    .stat-card.featured .value { color: var(--admin-accent-hover); }
    .stat-card .label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      color: var(--text-muted);
      margin-bottom: 0.3rem;
    }
    .stat-card .value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--navy);
      line-height: 1.1;
      letter-spacing: -0.02em;
    }
    .stat-card .hint { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .stat-card .hint a { font-weight: 600; }

    .pill {
      display: inline-flex;
      align-items: center;
      padding: 0.18rem 0.6rem;
      border-radius: 999px;
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      background: var(--bg);
      color: var(--navy);
      border: 1px solid var(--border);
    }
    .pill-pending { background: #fef3c7; color: #78350f; border-color: #fcd34d; }
    .pill-approved { background: #dcfce7; color: #14532d; border-color: #86efac; }
    .pill-rejected { background: #fee2e2; color: #7f1d1d; border-color: #fca5a5; }
    .pill-cancelled { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
    .pill-completed { background: #ddd6fe; color: #4c1d95; border-color: #c4b5fd; }

    .grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.15rem; }
    @media (min-width: 900px) { .grid-2 { grid-template-columns: 1.35fr 1fr; } }

    .filter-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      align-items: center;
      margin-bottom: 1.15rem;
      padding-bottom: 1.15rem;
      border-bottom: 1px solid var(--border);
    }
    .filter-bar .input { flex: 1; min-width: 200px; }
    .filter-bar .select { width: auto; min-width: 150px; }

    .status-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      margin-bottom: 1rem;
    }
    .status-tabs a {
      padding: 0.35rem 0.75rem;
      border-radius: 999px;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--text-muted);
      border: 1px solid var(--border);
      background: #fff;
      text-decoration: none;
      transition: all 0.15s var(--ease);
    }
    .status-tabs a:hover { border-color: var(--admin-accent); color: var(--admin-accent-hover); }
    .status-tabs a.is-active {
      background: var(--admin-accent);
      border-color: var(--admin-accent);
      color: #fff;
    }

    .empty-state {
      text-align: center;
      padding: 2.5rem 1.5rem;
      color: var(--text-muted);
      font-size: 0.95rem;
    }
    .empty-state::before {
      content: "📋";
      display: block;
      font-size: 2rem;
      margin-bottom: 0.5rem;
      opacity: 0.6;
    }

    .detail-grid { display: grid; gap: 1rem; }
    .detail-grid.two-col { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
    .detail-label {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--text-muted);
      margin-bottom: 0.2rem;
    }
    .detail-value { font-weight: 600; color: var(--navy); }
    .detail-value.muted { font-weight: 400; color: var(--text-muted); font-size: 0.9rem; }

    .avail-list { display: grid; gap: 0.45rem; }
    .avail-row {
      display: grid;
      grid-template-columns: 1fr auto;
      grid-template-rows: auto auto;
      gap: 0.35rem 0.75rem;
      padding: 0.65rem 0.85rem;
      border-radius: var(--radius-sm);
      background: #faf5ff;
      border: 1px solid var(--border);
      font-size: 0.85rem;
    }
    .avail-row strong { color: var(--navy); }
    .avail-meta { text-align: right; color: var(--text-muted); font-size: 0.8rem; line-height: 1.45; }
    .avail-meta strong { color: var(--navy); }
    .avail-row.full { background: #fef2f2; border-color: #fecaca; }
    .avail-row.tight { background: #fffbeb; border-color: #fde68a; }
    .avail-badge {
      display: inline-block;
      margin-left: 0.35rem;
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      padding: 0.1rem 0.4rem;
      border-radius: 4px;
      background: var(--admin-accent-muted);
      color: var(--admin-accent-hover);
    }
    .avail-progress {
      grid-column: 1 / -1;
      height: 4px;
      background: rgba(42, 10, 50, 0.08);
      border-radius: 999px;
      overflow: hidden;
    }
    .avail-progress span {
      display: block;
      height: 100%;
      background: linear-gradient(90deg, var(--admin-accent), #9333ea);
      border-radius: 999px;
      transition: width 0.3s var(--ease);
    }
    .avail-row.full .avail-progress span { background: var(--danger); }
    .avail-row.tight .avail-progress span { background: var(--warn); }

    .pagination {
      list-style: none;
      display: flex;
      flex-wrap: wrap;
      gap: 0.25rem;
      margin-top: 1.15rem;
    }
    .pagination a, .pagination span {
      padding: 0.38rem 0.7rem;
      border-radius: var(--radius-sm);
      font-size: 0.85rem;
      color: var(--navy);
      background: #fff;
      border: 1px solid var(--border);
      text-decoration: none;
    }
    .pagination .active span,
    .pagination a:hover { background: var(--admin-accent-muted); border-color: var(--admin-accent); }
    .pagination .disabled span { opacity: 0.45; }

    .breadcrumb {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      font-size: 0.8125rem;
      color: var(--text-muted);
      margin-bottom: 0.5rem;
    }
    .breadcrumb a { color: var(--text-muted); }
    .breadcrumb a:hover { color: var(--admin-accent-hover); }
    .breadcrumb span[aria-hidden] { opacity: 0.5; }

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
        font-size: 0.68rem;
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
      .filter-bar { flex-direction: column; align-items: stretch; }
      .filter-bar .input,
      .filter-bar .select { width: 100%; min-width: 0; }
      .filter-bar .btn { width: 100%; justify-content: center; min-height: 44px; }
      .status-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 0.35rem;
        margin-bottom: 1rem;
      }
      .status-tabs a { flex-shrink: 0; }
      .panel-head { flex-direction: column; align-items: stretch; }
      .panel-head .btn { width: 100%; justify-content: center; }
      .form-actions { flex-direction: column; }
      .form-actions .btn,
      .form-actions form { width: 100%; }
      .form-actions form { margin: 0; }
      .input, .select, .textarea { font-size: 16px; }
      .btn { min-height: 44px; }
      .admin-topbar-actions { width: 100%; }
      .admin-topbar-actions .btn { flex: 1; justify-content: center; }
      .admin-user-chip { width: 100%; text-align: center; }
      .quick-actions .btn { flex: 1 1 100%; justify-content: center; }
      .breadcrumb { font-size: 0.78rem; }
    }

    @media (max-width: 480px) {
      .admin-topbar-title h1 { font-size: 1.05rem; }
    }
  </style>
  @stack('head')
</head>
<body class="admin-body">
  @php
    $authUser = auth()->user();
    $pageTitle = trim($__env->yieldContent('title')) ?: 'Admin';
  @endphp

  <div class="admin-app" id="adminApp">
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Admin navigation">
      <div class="admin-sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
          <img src="{{ asset('images/Logo.png') }}" alt="" decoding="async" />
          <span>
            <span class="title">{{ config('app.name') }}</span>
            <span class="tag">Administration</span>
          </span>
        </a>
      </div>

      @include('admin.partials.side-nav')

      <div class="admin-sidebar-foot">
        <a href="{{ url('/') }}">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          View public site
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
          @csrf
          <button type="submit">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            Sign out
          </button>
        </form>
      </div>
    </aside>

    <div class="admin-main">
      <header class="admin-topbar">
        <button type="button" class="menu-toggle" id="menuToggle" aria-label="Open menu" aria-expanded="false" aria-controls="adminSidebar">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="admin-topbar-title">
          <div class="eyebrow">Admin</div>
          <h1>{{ $pageTitle }}</h1>
        </div>
        <div class="admin-topbar-actions">
          <span class="admin-user-chip">Signed in as <strong>{{ $authUser->name }}</strong></span>
          <a href="{{ url('/') }}" class="btn btn-secondary btn-sm">Public site</a>
        </div>
      </header>

      <main class="admin-content">
        @hasSection('breadcrumb')
          <nav class="breadcrumb" aria-label="Breadcrumb">@yield('breadcrumb')</nav>
        @endif

        @if (session('status'))
          <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        @if (session('warning'))
          <div class="alert alert-error" role="alert">{{ session('warning') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert alert-error" role="alert">
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
    </div>
  </div>

  <script>
    (function () {
      var app = document.getElementById('adminApp');
      var toggle = document.getElementById('menuToggle');
      var backdrop = document.getElementById('sidebarBackdrop');
      var body = document.body;
      function setOpen(open) {
        app.classList.toggle('sidebar-open', open);
        if (toggle) {
          toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
          toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        }
        if (window.matchMedia('(max-width: 959px)').matches) {
          body.classList.toggle('sidebar-locked', open);
        } else {
          body.classList.remove('sidebar-locked');
        }
      }
      if (toggle) toggle.addEventListener('click', function () { setOpen(!app.classList.contains('sidebar-open')); });
      if (backdrop) backdrop.addEventListener('click', function () { setOpen(false); });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && app.classList.contains('sidebar-open')) setOpen(false);
      });
      document.querySelectorAll('.admin-nav a').forEach(function (link) {
        link.addEventListener('click', function () {
          if (window.matchMedia('(max-width: 959px)').matches) setOpen(false);
        });
      });
      window.addEventListener('resize', function () {
        if (window.matchMedia('(min-width: 960px)').matches) {
          setOpen(false);
          body.classList.remove('sidebar-locked');
        }
      });
    })();
  </script>
  @include('partials.logout-confirm-modal')
  <script src="{{ asset('js/logout-confirm.js') }}" defer></script>
  @stack('scripts')
</body>
</html>

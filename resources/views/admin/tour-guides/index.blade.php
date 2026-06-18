@extends('layouts.admin')

@section('title', 'Tour guides')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <span aria-hidden="true">/</span>
  <span>Tour guides</span>
@endsection

@push('head')
  <style>
    .tg-page-nav {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 1.25rem;
      align-items: center;
    }
    .tg-page-nav a {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.45rem 0.9rem;
      border-radius: 999px;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--text-muted);
      background: var(--surface);
      border: 1px solid var(--border);
      text-decoration: none;
      transition: border-color 0.15s var(--ease), color 0.15s var(--ease), background 0.15s var(--ease);
    }
    .tg-page-nav a:hover {
      border-color: var(--admin-accent);
      color: var(--admin-accent-hover);
      background: var(--admin-accent-muted);
    }
    .tg-page-nav a .count {
      font-size: 0.68rem;
      font-weight: 700;
      padding: 0.1rem 0.45rem;
      border-radius: 999px;
      background: var(--bg);
      color: var(--navy);
    }
    .tg-page-nav a.is-warn .count { background: #fef3c7; color: #78350f; }
    .tg-page-nav .tg-nav-spacer { flex: 1; min-width: 0.5rem; }

    .tg-how-it-works {
      display: flex;
      gap: 0.85rem;
      padding: 1rem 1.1rem;
      border-radius: var(--radius-sm);
      background: linear-gradient(135deg, #fdf4ff 0%, #faf5ff 100%);
      border: 1px solid var(--border);
      font-size: 0.875rem;
      color: var(--text-muted);
      line-height: 1.55;
    }
    .tg-how-it-works svg {
      flex-shrink: 0;
      width: 1.25rem;
      height: 1.25rem;
      color: var(--admin-accent);
      margin-top: 0.1rem;
    }
    .tg-how-it-works strong { color: var(--navy); }

    .tg-stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 0.85rem;
      margin-bottom: 1.5rem;
    }
    .tg-stat {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1rem 1.15rem;
      box-shadow: var(--shadow-sm);
      display: flex;
      align-items: flex-start;
      gap: 0.85rem;
    }
    .tg-stat.is-alert {
      border-color: #fca5a5;
      background: linear-gradient(135deg, #fff 0%, #fef2f2 100%);
    }
    .tg-stat-icon {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      background: var(--admin-accent-muted);
      color: var(--admin-accent-hover);
    }
    .tg-stat.is-alert .tg-stat-icon { background: #fee2e2; color: var(--danger); }
    .tg-stat .label {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      color: var(--text-muted);
      margin-bottom: 0.2rem;
    }
    .tg-stat .value {
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--navy);
      line-height: 1.1;
    }
    .tg-stat .hint { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem; }

    .tg-add-panel .panel-head h2 { display: flex; align-items: center; gap: 0.5rem; }

    .tg-top-row {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.15rem;
      margin-bottom: 1.5rem;
      align-items: start;
    }
    @media (min-width: 900px) {
      .tg-top-row { grid-template-columns: 1.15fr 1fr; }
    }

    .tg-page-section {
      position: relative;
      margin-bottom: 1.5rem;
      scroll-margin-top: calc(var(--topbar-h) + 1rem);
    }
    .tg-page-section:last-child { margin-bottom: 0; }

    #add-guide,
    #roster,
    #monitor {
      scroll-margin-top: calc(var(--topbar-h) + 1rem);
    }

    .tg-tab-panel[hidden] { display: none !important; }

    .tg-section-toolbar {
      display: flex;
      flex-wrap: wrap;
      gap: 0.65rem;
      align-items: center;
      margin-bottom: 1.15rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
    }
    .tg-section-toolbar .tg-search-wrap {
      flex: 1;
      min-width: 180px;
      position: relative;
    }
    .tg-section-toolbar .tg-search-wrap svg {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      width: 1rem;
      height: 1rem;
      color: var(--text-muted);
      pointer-events: none;
    }
    .tg-section-toolbar .tg-search-wrap .input {
      padding-left: 2.25rem;
      font-size: 0.875rem;
    }
    .tg-filter-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
    }
    .tg-filter-chip {
      padding: 0.35rem 0.75rem;
      border-radius: 999px;
      font-size: 0.78rem;
      font-weight: 600;
      font-family: inherit;
      color: var(--text-muted);
      background: var(--bg);
      border: 1px solid var(--border);
      cursor: pointer;
      transition: all 0.15s var(--ease);
    }
    .tg-filter-chip:hover { border-color: var(--admin-accent); color: var(--admin-accent-hover); }
    .tg-filter-chip.is-active {
      background: var(--admin-accent-muted);
      border-color: var(--admin-accent);
      color: var(--admin-accent-hover);
    }
    .tg-section-toolbar .tg-toolbar-meta {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-left: auto;
    }

    .tg-guide-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 0.85rem;
    }
    .tg-guide-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: box-shadow 0.2s var(--ease), border-color 0.2s var(--ease), transform 0.2s var(--ease);
    }
    .tg-guide-card:hover { box-shadow: var(--shadow); border-color: #e9d5ef; }
    .tg-guide-card.is-busy-today { border-top: 3px solid var(--admin-accent); }
    .tg-guide-card.is-editing { border-color: var(--admin-accent); box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.12); }
    .tg-guide-card.is-hidden { display: none; }
    .tg-guide-card-body { padding: 1rem 1.1rem; }
    .tg-guide-top {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
    }
    .tg-avatar {
      width: 2.75rem;
      height: 2.75rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.95rem;
      color: #fff;
      background: linear-gradient(135deg, var(--admin-accent) 0%, #9333ea 100%);
      box-shadow: 0 2px 8px rgba(192, 38, 211, 0.25);
      flex-shrink: 0;
    }
    .tg-guide-meta { flex: 1; min-width: 0; }
    .tg-guide-meta h3 {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--navy);
      line-height: 1.3;
      word-break: break-word;
    }
    .tg-guide-meta .sub { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.1rem; }
    .tg-status-dot {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      margin-top: 0.45rem;
    }
    .tg-status-dot::before {
      content: "";
      width: 0.45rem;
      height: 0.45rem;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .tg-status-dot.is-available { color: #15803d; }
    .tg-status-dot.is-available::before { background: #22c55e; box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.25); }
    .tg-status-dot.is-busy { color: var(--admin-accent-hover); }
    .tg-status-dot.is-busy::before { background: var(--admin-accent); box-shadow: 0 0 0 2px rgba(192, 38, 211, 0.25); }
    .tg-guide-stats {
      display: flex;
      gap: 0.5rem;
      margin-top: 0.75rem;
      padding-top: 0.75rem;
      border-top: 1px solid var(--border);
    }
    .tg-guide-stat {
      flex: 1;
      text-align: center;
      padding: 0.45rem 0.35rem;
      border-radius: var(--radius-sm);
      background: var(--bg);
    }
    .tg-guide-stat .num { font-size: 1.1rem; font-weight: 700; color: var(--navy); line-height: 1; }
    .tg-guide-stat .lbl { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-top: 0.15rem; }
    .tg-guide-footer {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      padding: 0.65rem 1.1rem;
      background: #fafafa;
      border-top: 1px solid var(--border);
      align-items: center;
    }
    .tg-guide-footer .tg-link-assign { margin-left: auto; }
    .tg-guide-edit {
      padding: 0 1.1rem 1rem;
      border-top: 1px dashed var(--border);
      background: #fdf4ff;
    }
    .tg-guide-edit[hidden] { display: none !important; }
    .tg-guide-form {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 0.65rem;
      align-items: end;
      padding-top: 0.85rem;
    }
    .tg-guide-form-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      grid-column: 1 / -1;
      margin-top: 0.25rem;
      align-items: center;
    }
    .tg-guide-form-actions .tg-delete-form { margin-left: auto; }

    .tg-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      margin-bottom: 1rem;
      padding: 0.25rem;
      background: var(--bg);
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      width: fit-content;
      max-width: 100%;
    }
    .tg-tab {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.45rem 0.9rem;
      border-radius: 6px;
      font-size: 0.8125rem;
      font-weight: 600;
      font-family: inherit;
      color: var(--text-muted);
      background: transparent;
      border: none;
      cursor: pointer;
      transition: background 0.15s var(--ease), color 0.15s var(--ease);
    }
    .tg-tab:hover { color: var(--navy); }
    .tg-tab.is-active {
      background: var(--surface);
      color: var(--admin-accent-hover);
      box-shadow: var(--shadow-sm);
    }
    .tg-tab .tg-tab-count {
      font-size: 0.65rem;
      font-weight: 700;
      padding: 0.08rem 0.4rem;
      border-radius: 999px;
      background: var(--bg);
      color: var(--navy);
    }
    .tg-tab.is-active .tg-tab-count { background: var(--admin-accent-muted); color: var(--admin-accent-hover); }
    .tg-tab-panel { display: none; }
    .tg-tab-panel.is-active { display: block; }

    .tg-monitor-empty-hero {
      text-align: center;
      padding: 2.5rem 1.5rem;
      border-radius: var(--radius-sm);
      background: linear-gradient(135deg, #faf5ff 0%, #f4f0f7 100%);
      border: 1px dashed var(--border);
    }
    .tg-monitor-empty-hero svg {
      width: 2.75rem;
      height: 2.75rem;
      color: var(--admin-accent);
      margin-bottom: 0.75rem;
      opacity: 0.85;
    }
    .tg-monitor-empty-hero h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 0.35rem;
    }
    .tg-monitor-empty-hero p {
      font-size: 0.875rem;
      color: var(--text-muted);
      max-width: 28rem;
      margin: 0 auto;
      line-height: 1.55;
    }
    .tg-monitor-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .tg-guide-monitor {
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      overflow: hidden;
      background: #fff;
      transition: border-color 0.15s var(--ease), box-shadow 0.15s var(--ease);
    }
    .tg-guide-monitor:hover { border-color: #e9d5ef; }
    .tg-guide-monitor.is-empty { opacity: 0.85; }
    .tg-guide-monitor.is-hidden { display: none; }
    .tg-guide-monitor[open] { box-shadow: var(--shadow-sm); border-color: #e9d5ef; }
    .tg-guide-monitor summary {
      list-style: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.8rem 1rem;
      background: #fafafa;
      font-weight: 600;
      color: var(--navy);
      user-select: none;
      transition: background 0.15s var(--ease);
    }
    .tg-guide-monitor summary:hover { background: #f5f0fa; }
    .tg-guide-monitor[open] summary { background: #faf5ff; border-bottom: 1px solid var(--border); }
    .tg-guide-monitor summary::-webkit-details-marker { display: none; }
    .tg-guide-monitor .tg-monitor-chevron {
      margin-left: auto;
      width: 1.25rem;
      height: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      transition: transform 0.2s var(--ease);
      flex-shrink: 0;
    }
    .tg-guide-monitor[open] .tg-monitor-chevron { transform: rotate(180deg); }
    .tg-guide-monitor .tg-monitor-name {
      flex: 1;
      min-width: 0;
      font-size: 0.9rem;
    }
    .tg-guide-monitor .tg-monitor-name .muted {
      font-weight: 400;
      font-size: 0.78rem;
    }
    .tg-guide-monitor .tg-group-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 1.75rem;
      padding: 0.2rem 0.55rem;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 700;
      background: var(--bg);
      color: var(--text-muted);
      border: 1px solid var(--border);
      flex-shrink: 0;
    }
    .tg-guide-monitor .tg-group-badge.has-groups {
      background: #dcfce7;
      color: #14532d;
      border-color: #86efac;
    }
    .tg-guide-monitor .tg-monitor-body { padding: 0.5rem 1rem 0.85rem; }
    .tg-guide-monitor .tg-avatar { width: 2rem; height: 2rem; font-size: 0.75rem; }
    .tg-empty-inline {
      padding: 1rem;
      text-align: center;
      color: var(--text-muted);
      font-size: 0.85rem;
      background: var(--bg);
      border-radius: var(--radius-sm);
    }
    .tg-booking-row {
      display: grid;
      grid-template-columns: auto minmax(0, 1fr) auto;
      gap: 0.65rem 0.85rem;
      align-items: center;
      padding: 0.65rem 0;
      border-bottom: 1px solid var(--border);
    }
    .tg-booking-row:last-child { border-bottom: none; }
    .tg-booking-date-badge {
      width: 2.75rem;
      text-align: center;
      padding: 0.35rem 0.25rem;
      border-radius: var(--radius-sm);
      background: var(--admin-accent-muted);
      flex-shrink: 0;
    }
    .tg-booking-date-badge .day {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--admin-accent-hover);
      line-height: 1;
    }
    .tg-booking-date-badge .mon {
      font-size: 0.6rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--text-muted);
      margin-top: 0.1rem;
    }
    .tg-booking-ref { font-weight: 700; color: var(--navy); font-size: 0.875rem; }
    .tg-booking-meta { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.1rem; }
    .tg-booking-side { text-align: right; }
    .tg-roster-no-match {
      display: none;
      text-align: center;
      padding: 2rem 1rem;
      color: var(--text-muted);
      font-size: 0.9rem;
    }
    .tg-roster-no-match.is-visible { display: block; }

    @media (max-width: 640px) {
      .tg-guide-form { grid-template-columns: 1fr; }
      .tg-guide-footer .tg-link-assign { margin-left: 0; width: 100%; }
      .tg-section-toolbar .tg-toolbar-meta { width: 100%; margin-left: 0; }
      .tg-booking-row { grid-template-columns: auto 1fr; }
      .tg-booking-side { grid-column: 2; text-align: left; }
    }
  </style>
@endpush

@section('content')
  @php
    $initials = fn (string $name) => collect(explode(' ', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->join('');
  @endphp

  <div class="page-header">
    <h1>Tour guides</h1>
    <p>Register guides, track availability, and see which visitor groups are assigned after approval.</p>
  </div>

  <nav class="tg-page-nav" aria-label="On this page">
    <a href="#add-guide">Add guide</a>
    <a href="#roster">Roster <span class="count">{{ $guides->count() }}</span></a>
    <a href="#monitor">Assignments <span class="count">{{ $upcomingCount }}</span></a>
    @if ($unassignedApproved->isNotEmpty())
      <a href="#unassigned" class="is-warn">Needs guide <span class="count">{{ $unassignedApproved->count() }}</span></a>
    @endif
    <span class="tg-nav-spacer" aria-hidden="true"></span>
    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="btn btn-secondary btn-sm">Pending bookings</a>
  </nav>

  <div class="tg-stat-grid" aria-label="Summary statistics">
    <div class="tg-stat">
      <div class="tg-stat-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div>
        <div class="label">Registered guides</div>
        <div class="value">{{ $guides->count() }}</div>
        <div class="hint">{{ $availableTodayCount }} free for hikes today</div>
      </div>
    </div>
    <div class="tg-stat">
      <div class="tg-stat-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
      </div>
      <div>
        <div class="label">Upcoming assignments</div>
        <div class="value">{{ $upcomingCount }}</div>
        <div class="hint">Approved groups from today onward</div>
      </div>
    </div>
    <div class="tg-stat {{ $unassignedApproved->isNotEmpty() ? 'is-alert' : '' }}">
      <div class="tg-stat-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      </div>
      <div>
        <div class="label">Needs a guide</div>
        <div class="value">{{ $unassignedApproved->count() }}</div>
        <div class="hint">
          @if ($unassignedApproved->isNotEmpty())
            <a href="#unassigned">View unassigned groups</a>
          @else
            All upcoming groups are covered
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="tg-top-row">
    <div class="panel tg-add-panel" id="add-guide">
      <div class="panel-head">
        <h2>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
          Add tour guide
        </h2>
      </div>
      <form method="POST" action="{{ route('admin.tour-guides.store') }}">
        @csrf
        <div class="field">
          <label for="add_name">Full name</label>
          <input type="text" id="add_name" name="name" class="input" value="{{ old('name') }}" required maxlength="120" placeholder="e.g. Juan Dela Cruz" autocomplete="name" />
        </div>
        <div class="field" style="margin-top: 0.85rem;">
          <label for="add_age">Age</label>
          <input type="number" id="add_age" name="age" class="input" value="{{ old('age') }}" required min="18" max="80" placeholder="18–80" />
          <div class="hint">Guides must be at least 18 years old.</div>
        </div>
        <div class="form-actions" style="margin-top: 1rem;">
          <button type="submit" class="btn btn-primary">Add to roster</button>
        </div>
      </form>
    </div>

    <div class="panel tg-how-panel" style="display: flex; flex-direction: column; justify-content: center;">
      <div class="tg-how-it-works">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        <div>
          <strong>How assignment works</strong><br />
          When you <strong>approve</strong> a booking, the system assigns the first available guide for that hike date. Each guide leads <strong>one approved group per day</strong>. If none are free, the booking stays approved but unassigned until capacity opens.
        </div>
      </div>
    </div>
  </div>

  <div class="panel tg-page-section" id="roster">
    <div class="panel-head">
      <div>
        <h2>Guide roster</h2>
        <span class="muted">{{ $guides->count() }} {{ \Illuminate\Support\Str::plural('guide', $guides->count()) }} registered</span>
      </div>
    </div>

    @if ($guides->isEmpty())
      <div class="empty-state">
        No tour guides yet.<br />
        <span style="font-size: 0.9rem; color: var(--text-muted);">Add your first guide above, then approve bookings to assign them automatically.</span>
      </div>
    @else
      <div class="tg-section-toolbar" role="search">
        <div class="tg-search-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="search" id="roster-search" class="input" placeholder="Search guides by name…" autocomplete="off" aria-label="Search guides" />
        </div>
        <div class="tg-filter-chips" role="group" aria-label="Filter roster">
          <button type="button" class="tg-filter-chip is-active" data-roster-filter="all">All</button>
          <button type="button" class="tg-filter-chip" data-roster-filter="available">Available today</button>
          <button type="button" class="tg-filter-chip" data-roster-filter="busy">On duty today</button>
        </div>
        <span class="tg-toolbar-meta" id="roster-count-label">{{ $guides->count() }} shown</span>
      </div>

      <div class="tg-guide-grid" id="roster-grid">
        @foreach ($guides as $guide)
          @php
            $guideBookings = $upcomingAssignments->get($guide->id, collect());
            $busyToday = $busyTodayIds->contains($guide->id);
          @endphp
          <article
            class="tg-guide-card {{ $busyToday ? 'is-busy-today' : '' }}"
            data-guide-name="{{ strtolower($guide->name) }}"
            data-guide-status="{{ $busyToday ? 'busy' : 'available' }}"
            data-guide-id="{{ $guide->id }}"
          >
            <div class="tg-guide-card-body">
              <div class="tg-guide-top">
                <div class="tg-avatar" aria-hidden="true">{{ $initials($guide->name) ?: '?' }}</div>
                <div class="tg-guide-meta">
                  <h3>{{ $guide->name }}</h3>
                  <p class="sub">Age {{ $guide->age }}</p>
                  <span class="tg-status-dot {{ $busyToday ? 'is-busy' : 'is-available' }}">
                    {{ $busyToday ? 'On duty today' : 'Available today' }}
                  </span>
                </div>
              </div>
              <div class="tg-guide-stats">
                <div class="tg-guide-stat">
                  <div class="num">{{ $guideBookings->count() }}</div>
                  <div class="lbl">Upcoming</div>
                </div>
                <div class="tg-guide-stat">
                  <div class="num">{{ $busyToday ? '1' : '0' }}</div>
                  <div class="lbl">Today</div>
                </div>
              </div>
            </div>

            <div class="tg-guide-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-tg-edit-toggle aria-expanded="false">Edit</button>
              @if ($guideBookings->isNotEmpty())
                <a href="#monitor-guide-{{ $guide->id }}" class="btn btn-ghost btn-sm tg-link-assign">View assignments</a>
              @endif
            </div>

            <div class="tg-guide-edit" hidden>
              <form method="POST" action="{{ route('admin.tour-guides.update', $guide) }}" class="tg-guide-form" id="edit-form-{{ $guide->id }}">
                @csrf
                @method('PATCH')
                <div class="field" style="margin:0;">
                  <label for="name-{{ $guide->id }}">Full name</label>
                  <input type="text" id="name-{{ $guide->id }}" name="name" class="input" value="{{ $guide->name }}" required maxlength="120" />
                </div>
                <div class="field" style="margin:0;">
                  <label for="age-{{ $guide->id }}">Age</label>
                  <input type="number" id="age-{{ $guide->id }}" name="age" class="input" value="{{ $guide->age }}" required min="18" max="80" style="max-width: 5.5rem;" />
                </div>
                <div class="tg-guide-form-actions">
                  <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                  <button type="button" class="btn btn-ghost btn-sm" data-tg-edit-cancel>Cancel</button>
                </div>
              </form>
              <form method="POST" action="{{ route('admin.tour-guides.destroy', $guide) }}" class="tg-guide-form-actions" style="padding-top: 0; margin-top: -0.25rem;" onsubmit="return confirm('Remove {{ $guide->name }} from the roster?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--danger); margin-left: auto;" @if ($guideBookings->isNotEmpty()) disabled title="Reassign or complete upcoming hikes before deleting" @endif>Delete guide</button>
              </form>
            </div>
          </article>
        @endforeach
      </div>
      <p class="tg-roster-no-match" id="roster-no-match">No guides match your search or filter.</p>
    @endif
  </div>

  <div class="panel tg-page-section" id="monitor">
    <div class="panel-head">
      <div>
        <h2>Assignment monitor</h2>
        <span class="muted">Approved hikes from today onward</span>
      </div>
    </div>

    @if ($guides->isEmpty() && $unassignedApproved->isEmpty())
      <div class="tg-monitor-empty-hero">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        <h3>No guides to monitor yet</h3>
        <p>Add guides to the roster first. Once bookings are approved, assignments will appear here automatically.</p>
      </div>
    @else

      <div class="tg-tabs" role="tablist" aria-label="Assignment views">
        <button type="button" class="tg-tab is-active" role="tab" aria-selected="true" aria-controls="panel-by-guide" id="tab-by-guide" data-tg-tab="by-guide">
          By guide
          <span class="tg-tab-count">{{ $guides->count() }}</span>
        </button>
        <button type="button" class="tg-tab" role="tab" aria-selected="false" aria-controls="panel-all" id="tab-all" data-tg-tab="all">
          All upcoming
          <span class="tg-tab-count">{{ $upcomingCount }}</span>
        </button>
        @if ($unassignedApproved->isNotEmpty())
          <button type="button" class="tg-tab" role="tab" aria-selected="false" aria-controls="panel-unassigned" id="tab-unassigned" data-tg-tab="unassigned">
            Needs guide
            <span class="tg-tab-count">{{ $unassignedApproved->count() }}</span>
          </button>
        @endif
      </div>

      <div id="panel-by-guide" class="tg-tab-panel is-active" role="tabpanel" aria-labelledby="tab-by-guide">
        @if ($guides->isEmpty())
          <p class="tg-empty-inline">No guides in the roster.</p>
        @elseif ($upcomingCount === 0 && $unassignedApproved->isEmpty())
          <div class="tg-monitor-empty-hero">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <h3>No upcoming assignments</h3>
            <p>All {{ $guides->count() }} guides are free. Assignments appear here when you approve bookings for future hike dates.</p>
          </div>
          <div class="tg-section-toolbar" style="border-bottom: none; padding-bottom: 0; margin-top: 1rem;">
            <span class="tg-toolbar-meta" style="margin-left: 0;">Roster overview</span>
            <button type="button" class="btn btn-secondary btn-sm" id="monitor-toggle-empty" style="margin-left: auto;">Show all guides</button>
          </div>
          <div class="tg-monitor-list" id="monitor-list" hidden>
            @foreach ($guides as $guide)
              @php $guideBookings = $upcomingAssignments->get($guide->id, collect()); @endphp
              @include('admin.tour-guides.partials.monitor-row', ['guide' => $guide, 'guideBookings' => $guideBookings, 'initials' => $initials])
            @endforeach
          </div>
        @else
          <div class="tg-section-toolbar">
            <div class="tg-search-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
              <input type="search" id="monitor-search" class="input" placeholder="Search guides…" autocomplete="off" aria-label="Search guides in monitor" />
            </div>
            <div class="tg-filter-chips" role="group" aria-label="Filter monitor">
              <button type="button" class="tg-filter-chip is-active" data-monitor-filter="assigned">With assignments</button>
              <button type="button" class="tg-filter-chip" data-monitor-filter="all">All guides</button>
            </div>
            <button type="button" class="btn btn-ghost btn-sm" id="monitor-expand-all" style="margin-left: auto;">Expand all</button>
          </div>
          <div class="tg-monitor-list" id="monitor-list">
            @foreach ($guides as $guide)
              @php $guideBookings = $upcomingAssignments->get($guide->id, collect()); @endphp
              @include('admin.tour-guides.partials.monitor-row', ['guide' => $guide, 'guideBookings' => $guideBookings, 'initials' => $initials])
            @endforeach
          </div>
        @endif
      </div>

      <div id="panel-all" class="tg-tab-panel" role="tabpanel" aria-labelledby="tab-all" hidden>
        @php $allUpcoming = $upcomingAssignments->flatten()->sortBy(fn ($b) => $b->hike_date->timestamp); @endphp
        @if ($allUpcoming->isEmpty())
          <p class="tg-empty-inline">No upcoming assigned groups.</p>
        @else
          <div class="table-wrap table-cards">
            <table class="data">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Reference</th>
                  <th>Visitor / group</th>
                  <th>Guide</th>
                  <th>Party</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($allUpcoming as $booking)
                  <tr>
                    <td data-label="Date"><strong>{{ $booking->hike_date->format('M j, Y') }}</strong></td>
                    <td data-label="Reference">{{ $booking->reference_code }}</td>
                    <td data-label="Visitor / group">
                      {{ $booking->user?->name ?? '—' }}
                      @if ($booking->user?->email)<div class="sub">{{ $booking->user->email }}</div>@endif
                    </td>
                    <td data-label="Guide">{{ $booking->tourGuide?->name ?? '—' }}</td>
                    <td data-label="Party">{{ $booking->party_size }}</td>
                    <td class="actions-cell" data-label="Actions"><a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">Open</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>

      @if ($unassignedApproved->isNotEmpty())
        <div id="panel-unassigned" class="tg-tab-panel" role="tabpanel" aria-labelledby="tab-unassigned" hidden>
          <div id="unassigned" tabindex="-1"></div>
          <div class="alert alert-error" role="alert" style="margin-bottom: 1rem;">
            <strong>{{ $unassignedApproved->count() }} approved {{ \Illuminate\Support\Str::plural('group', $unassignedApproved->count()) }} still need a guide.</strong>
            Add guides to the roster, or open a booking and save as <strong>Approved</strong> again once a guide is free.
          </div>
          <div class="table-wrap table-cards">
            <table class="data">
              <thead>
                <tr>
                  <th>Hike date</th>
                  <th>Reference</th>
                  <th>Visitor / group</th>
                  <th>Party</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($unassignedApproved as $booking)
                  <tr>
                    <td data-label="Hike date"><strong>{{ $booking->hike_date->format('D, M j, Y') }}</strong></td>
                    <td data-label="Reference">{{ $booking->reference_code }}</td>
                    <td data-label="Visitor / group">
                      {{ $booking->user?->name ?? '—' }}
                      @if ($booking->user?->email)<div class="sub">{{ $booking->user->email }}</div>@endif
                    </td>
                    <td data-label="Party">{{ $booking->party_size }} hiker(s)</td>
                    <td class="actions-cell" data-label="Actions"><a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-primary btn-sm">Open booking</a></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
    @endif
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      var tabs = document.querySelectorAll('[data-tg-tab]');
      var panels = {
        'by-guide': document.getElementById('panel-by-guide'),
        'all': document.getElementById('panel-all'),
        'unassigned': document.getElementById('panel-unassigned')
      };

      function activate(name) {
        tabs.forEach(function (tab) {
          var on = tab.getAttribute('data-tg-tab') === name;
          tab.classList.toggle('is-active', on);
          tab.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        Object.keys(panels).forEach(function (key) {
          var panel = panels[key];
          if (!panel) return;
          var on = key === name;
          panel.classList.toggle('is-active', on);
          panel.hidden = !on;
        });
      }

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          activate(tab.getAttribute('data-tg-tab'));
        });
      });

      function syncFromHash() {
        var hash = location.hash;
        if (hash === '#unassigned' && panels.unassigned) {
          activate('unassigned');
          var anchor = document.getElementById('unassigned');
          if (anchor) anchor.focus({ preventScroll: true });
          return;
        }
        if (hash.indexOf('#monitor-guide-') === 0) {
          activate('by-guide');
          var block = document.querySelector(hash);
          if (block && block.tagName === 'DETAILS') {
            block.open = true;
            block.classList.remove('is-hidden');
            block.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          }
        }
      }

      window.addEventListener('hashchange', syncFromHash);
      syncFromHash();

      /* Roster: search + filter */
      var rosterSearch = document.getElementById('roster-search');
      var rosterGrid = document.getElementById('roster-grid');
      var rosterNoMatch = document.getElementById('roster-no-match');
      var rosterCountLabel = document.getElementById('roster-count-label');
      var rosterFilter = 'all';

      function applyRosterFilters() {
        if (!rosterGrid) return;
        var query = (rosterSearch && rosterSearch.value || '').trim().toLowerCase();
        var cards = rosterGrid.querySelectorAll('.tg-guide-card');
        var shown = 0;
        cards.forEach(function (card) {
          var name = card.getAttribute('data-guide-name') || '';
          var status = card.getAttribute('data-guide-status') || '';
          var matchQuery = !query || name.indexOf(query) !== -1;
          var matchFilter = rosterFilter === 'all'
            || (rosterFilter === 'available' && status === 'available')
            || (rosterFilter === 'busy' && status === 'busy');
          var visible = matchQuery && matchFilter;
          card.classList.toggle('is-hidden', !visible);
          if (visible) shown++;
        });
        if (rosterCountLabel) rosterCountLabel.textContent = shown + ' shown';
        if (rosterNoMatch) rosterNoMatch.classList.toggle('is-visible', shown === 0);
      }

      if (rosterSearch) rosterSearch.addEventListener('input', applyRosterFilters);
      document.querySelectorAll('[data-roster-filter]').forEach(function (chip) {
        chip.addEventListener('click', function () {
          document.querySelectorAll('[data-roster-filter]').forEach(function (c) { c.classList.remove('is-active'); });
          chip.classList.add('is-active');
          rosterFilter = chip.getAttribute('data-roster-filter');
          applyRosterFilters();
        });
      });

      /* Roster: edit toggle */
      document.querySelectorAll('[data-tg-edit-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var card = btn.closest('.tg-guide-card');
          if (!card) return;
          var panel = card.querySelector('.tg-guide-edit');
          var open = panel && panel.hidden;
          document.querySelectorAll('.tg-guide-card.is-editing').forEach(function (other) {
            if (other === card) return;
            other.classList.remove('is-editing');
            var p = other.querySelector('.tg-guide-edit');
            var t = other.querySelector('[data-tg-edit-toggle]');
            if (p) p.hidden = true;
            if (t) { t.setAttribute('aria-expanded', 'false'); t.textContent = 'Edit'; }
          });
          if (!panel) return;
          panel.hidden = !open;
          card.classList.toggle('is-editing', open);
          btn.setAttribute('aria-expanded', open ? 'true' : 'false');
          btn.textContent = open ? 'Close' : 'Edit';
          if (open) {
            var input = panel.querySelector('input[name="name"]');
            if (input) input.focus();
          }
        });
      });

      document.querySelectorAll('[data-tg-edit-cancel]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var card = btn.closest('.tg-guide-card');
          if (!card) return;
          var panel = card.querySelector('.tg-guide-edit');
          var toggle = card.querySelector('[data-tg-edit-toggle]');
          if (panel) panel.hidden = true;
          card.classList.remove('is-editing');
          if (toggle) { toggle.setAttribute('aria-expanded', 'false'); toggle.textContent = 'Edit'; }
        });
      });

      /* Monitor: search + filter */
      var monitorSearch = document.getElementById('monitor-search');
      var monitorList = document.getElementById('monitor-list');
      var monitorFilter = 'assigned';

      function applyMonitorFilters() {
        if (!monitorList) return;
        var query = (monitorSearch && monitorSearch.value || '').trim().toLowerCase();
        monitorList.querySelectorAll('.tg-guide-monitor').forEach(function (row) {
          var name = row.getAttribute('data-monitor-name') || '';
          var hasGroups = row.getAttribute('data-monitor-has-groups') === '1';
          var matchQuery = !query || name.indexOf(query) !== -1;
          var matchFilter = monitorFilter === 'all' || hasGroups;
          row.classList.toggle('is-hidden', !(matchQuery && matchFilter));
        });
      }

      if (monitorSearch) monitorSearch.addEventListener('input', applyMonitorFilters);
      document.querySelectorAll('[data-monitor-filter]').forEach(function (chip) {
        chip.addEventListener('click', function () {
          document.querySelectorAll('[data-monitor-filter]').forEach(function (c) { c.classList.remove('is-active'); });
          chip.classList.add('is-active');
          monitorFilter = chip.getAttribute('data-monitor-filter');
          applyMonitorFilters();
        });
      });

      if (monitorList && monitorFilter === 'assigned') applyMonitorFilters();

      var expandBtn = document.getElementById('monitor-expand-all');
      if (expandBtn && monitorList) {
        expandBtn.addEventListener('click', function () {
          var rows = monitorList.querySelectorAll('.tg-guide-monitor:not(.is-hidden)');
          var anyClosed = false;
          rows.forEach(function (row) { if (!row.open) anyClosed = true; });
          rows.forEach(function (row) { row.open = anyClosed; });
          expandBtn.textContent = anyClosed ? 'Collapse all' : 'Expand all';
        });
      }

      var toggleEmptyBtn = document.getElementById('monitor-toggle-empty');
      if (toggleEmptyBtn && monitorList) {
        toggleEmptyBtn.addEventListener('click', function () {
          var hidden = monitorList.hidden;
          monitorList.hidden = !hidden;
          toggleEmptyBtn.textContent = hidden ? 'Hide guide list' : 'Show all guides';
        });
      }
    })();
  </script>
@endpush

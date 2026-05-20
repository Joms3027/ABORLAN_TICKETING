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

    .tg-guide-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1rem;
    }
    .tg-guide-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.15rem 1.2rem;
      box-shadow: var(--shadow-sm);
      transition: box-shadow 0.2s var(--ease), border-color 0.2s var(--ease);
    }
    .tg-guide-card:hover { box-shadow: var(--shadow); border-color: #e9d5ef; }
    .tg-guide-card.is-busy-today { border-left: 3px solid var(--admin-accent); }
    .tg-guide-top {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      margin-bottom: 1rem;
    }
    .tg-avatar {
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.1rem;
      color: #fff;
      background: linear-gradient(135deg, var(--admin-accent) 0%, #9333ea 100%);
      box-shadow: 0 2px 10px rgba(192, 38, 211, 0.3);
      flex-shrink: 0;
    }
    .tg-guide-meta h3 { font-size: 1rem; font-weight: 700; color: var(--navy); line-height: 1.25; }
    .tg-guide-meta .sub { font-size: 0.8125rem; color: var(--text-muted); margin-top: 0.15rem; }
    .tg-guide-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      margin-left: auto;
      align-self: flex-start;
    }
    .tg-guide-form {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 0.65rem;
      align-items: end;
    }
    .tg-guide-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      margin-top: 0.85rem;
      padding-top: 0.85rem;
      border-top: 1px solid var(--border);
      align-items: center;
    }
    .tg-guide-actions .tg-link-assign { margin-left: auto; }

    .tg-tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      margin-bottom: 1.15rem;
      padding: 0.25rem;
      background: var(--bg);
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      width: fit-content;
      max-width: 100%;
    }
    .tg-tab {
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
    .tg-tab-panel { display: none; }
    .tg-tab-panel.is-active { display: block; }

    .tg-guide-monitor {
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      overflow: hidden;
      margin-bottom: 0.75rem;
      background: #fff;
    }
    .tg-guide-monitor:last-child { margin-bottom: 0; }
    .tg-guide-monitor summary {
      list-style: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.85rem 1rem;
      background: #faf5ff;
      font-weight: 600;
      color: var(--navy);
    }
    .tg-guide-monitor summary::-webkit-details-marker { display: none; }
    .tg-guide-monitor summary::after {
      content: "";
      margin-left: auto;
      width: 0.5rem;
      height: 0.5rem;
      border-right: 2px solid var(--text-muted);
      border-bottom: 2px solid var(--text-muted);
      transform: rotate(45deg);
      transition: transform 0.2s var(--ease);
    }
    .tg-guide-monitor[open] summary::after {
      transform: rotate(-135deg);
      margin-top: 0.25rem;
    }
    .tg-guide-monitor .tg-monitor-body {
      padding: 0 1rem 1rem;
      border-top: 1px solid var(--border);
    }
    .tg-guide-monitor .tg-avatar { width: 2.25rem; height: 2.25rem; font-size: 0.85rem; }
    .tg-empty-inline {
      padding: 1.25rem 1rem;
      text-align: center;
      color: var(--text-muted);
      font-size: 0.9rem;
    }
    .tg-booking-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr) auto;
      gap: 0.5rem 1rem;
      align-items: center;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--border);
    }
    .tg-booking-row:last-child { border-bottom: none; }
    .tg-booking-ref { font-weight: 700; color: var(--navy); font-size: 0.9rem; }
    .tg-booking-meta { font-size: 0.8125rem; color: var(--text-muted); margin-top: 0.15rem; }
    .tg-booking-side { text-align: right; }
    .tg-booking-date { font-size: 0.8125rem; font-weight: 600; color: var(--navy); }

    @media (max-width: 640px) {
      .tg-guide-form { grid-template-columns: 1fr; }
      .tg-guide-badges { margin-left: 0; width: 100%; }
      .tg-guide-top { flex-wrap: wrap; }
      .tg-guide-actions .tg-link-assign { margin-left: 0; width: 100%; }
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
      <h2>Guide roster</h2>
      <span class="muted">{{ $guides->count() }} {{ \Illuminate\Support\Str::plural('guide', $guides->count()) }}</span>
    </div>

    @if ($guides->isEmpty())
      <div class="empty-state">
        No tour guides yet.<br />
        <span style="font-size: 0.9rem; color: var(--text-muted);">Add your first guide above, then approve bookings to assign them automatically.</span>
      </div>
    @else
      <div class="tg-guide-grid">
        @foreach ($guides as $guide)
          @php
            $guideBookings = $upcomingAssignments->get($guide->id, collect());
            $busyToday = $busyTodayIds->contains($guide->id);
          @endphp
          <article class="tg-guide-card {{ $busyToday ? 'is-busy-today' : '' }}">
            <div class="tg-guide-top">
              <div class="tg-avatar" aria-hidden="true">{{ $initials($guide->name) ?: '?' }}</div>
              <div class="tg-guide-meta">
                <h3>{{ $guide->name }}</h3>
                <p class="sub">Age {{ $guide->age }}</p>
              </div>
              <div class="tg-guide-badges">
                @if ($busyToday)
                  <span class="pill pill-approved">On duty today</span>
                @else
                  <span class="pill">Available today</span>
                @endif
                @if ($guideBookings->isNotEmpty())
                  <span class="pill pill-completed">{{ $guideBookings->count() }} upcoming</span>
                @endif
              </div>
            </div>

            <form method="POST" action="{{ route('admin.tour-guides.update', $guide) }}" class="tg-guide-form">
              @csrf
              @method('PATCH')
              <div class="field" style="margin:0;">
                <label class="hint" for="name-{{ $guide->id }}">Name</label>
                <input type="text" id="name-{{ $guide->id }}" name="name" class="input" value="{{ $guide->name }}" required maxlength="120" />
              </div>
              <div class="field" style="margin:0;">
                <label class="hint" for="age-{{ $guide->id }}">Age</label>
                <input type="number" id="age-{{ $guide->id }}" name="age" class="input" value="{{ $guide->age }}" required min="18" max="80" style="max-width: 5.5rem;" />
              </div>
              <div class="tg-guide-actions" style="grid-column: 1 / -1; border-top: none; padding-top: 0; margin-top: 0.25rem;">
                <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                @if ($guideBookings->isNotEmpty())
                  <a href="#monitor-guide-{{ $guide->id }}" class="btn btn-secondary btn-sm tg-link-assign">View assignments</a>
                @endif
              </div>
            </form>

            <form method="POST" action="{{ route('admin.tour-guides.destroy', $guide) }}" class="tg-guide-actions" style="border-top: none; padding-top: 0.35rem;" onsubmit="return confirm('Remove {{ $guide->name }} from the roster?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-ghost btn-sm" @if ($guideBookings->isNotEmpty()) disabled title="Reassign or complete upcoming hikes before deleting" @endif>Delete guide</button>
            </form>
          </article>
        @endforeach
      </div>
    @endif
  </div>

  <div class="panel tg-page-section" id="monitor">
    <div class="panel-head">
      <h2>Assignment monitor</h2>
      <span class="muted">Approved hikes from today onward</span>
    </div>

    @if ($guides->isEmpty() && $unassignedApproved->isEmpty())
      <p class="tg-empty-inline">Add guides to the roster to start tracking assignments.</p>
    @else
      <div class="tg-tabs" role="tablist" aria-label="Assignment views">
        <button type="button" class="tg-tab is-active" role="tab" aria-selected="true" aria-controls="panel-by-guide" id="tab-by-guide" data-tg-tab="by-guide">By guide</button>
        <button type="button" class="tg-tab" role="tab" aria-selected="false" aria-controls="panel-all" id="tab-all" data-tg-tab="all">All upcoming</button>
        @if ($unassignedApproved->isNotEmpty())
          <button type="button" class="tg-tab" role="tab" aria-selected="false" aria-controls="panel-unassigned" id="tab-unassigned" data-tg-tab="unassigned">
            Needs guide ({{ $unassignedApproved->count() }})
          </button>
        @endif
      </div>

      <div id="panel-by-guide" class="tg-tab-panel is-active" role="tabpanel" aria-labelledby="tab-by-guide">
        @if ($guides->isEmpty())
          <p class="tg-empty-inline">No guides in the roster.</p>
        @else
          @foreach ($guides as $guide)
            @php $guideBookings = $upcomingAssignments->get($guide->id, collect()); @endphp
            <details class="tg-guide-monitor" id="monitor-guide-{{ $guide->id }}" {{ $guideBookings->isNotEmpty() ? 'open' : '' }}>
              <summary>
                <span class="tg-avatar" aria-hidden="true">{{ $initials($guide->name) ?: '?' }}</span>
                <span>{{ $guide->name }} <span class="muted" style="font-weight:400;font-size:0.85rem;">· age {{ $guide->age }}</span></span>
                <span class="pill {{ $guideBookings->isEmpty() ? '' : 'pill-approved' }}">
                  {{ $guideBookings->count() }} {{ \Illuminate\Support\Str::plural('group', $guideBookings->count()) }}
                </span>
              </summary>
              <div class="tg-monitor-body">
                @if ($guideBookings->isEmpty())
                  <p class="tg-empty-inline">No upcoming assignments.</p>
                @else
                  @foreach ($guideBookings as $booking)
                    <div class="tg-booking-row">
                      <div>
                        <div class="tg-booking-ref">{{ $booking->reference_code }}</div>
                        <div class="tg-booking-meta">
                          {{ $booking->user?->name ?? '—' }}
                          @if ($booking->party_size > 1) · {{ $booking->party_size }} hikers @endif
                        </div>
                      </div>
                      <div class="tg-booking-side">
                        <div class="tg-booking-date">{{ $booking->hike_date->format('D, M j') }}</div>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm" style="margin-top:0.35rem;">Open</a>
                      </div>
                    </div>
                  @endforeach
                @endif
              </div>
            </details>
          @endforeach
        @endif
      </div>

      <div id="panel-all" class="tg-tab-panel" role="tabpanel" aria-labelledby="tab-all" hidden>
        @php $allUpcoming = $upcomingAssignments->flatten()->sortBy(fn ($b) => $b->hike_date->timestamp); @endphp
        @if ($allUpcoming->isEmpty())
          <p class="tg-empty-inline">No upcoming assigned groups.</p>
        @else
          <div class="table-wrap">
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
                    <td><strong>{{ $booking->hike_date->format('M j, Y') }}</strong></td>
                    <td>{{ $booking->reference_code }}</td>
                    <td>
                      {{ $booking->user?->name ?? '—' }}
                      @if ($booking->user?->email)<div class="sub">{{ $booking->user->email }}</div>@endif
                    </td>
                    <td>{{ $booking->tourGuide?->name ?? '—' }}</td>
                    <td>{{ $booking->party_size }}</td>
                    <td><a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">Open</a></td>
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
          <div class="table-wrap">
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
                    <td><strong>{{ $booking->hike_date->format('D, M j, Y') }}</strong></td>
                    <td>{{ $booking->reference_code }}</td>
                    <td>
                      {{ $booking->user?->name ?? '—' }}
                      @if ($booking->user?->email)<div class="sub">{{ $booking->user->email }}</div>@endif
                    </td>
                    <td>{{ $booking->party_size }} hiker(s)</td>
                    <td><a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-primary btn-sm">Open booking</a></td>
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
            block.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          }
        }
      }

      window.addEventListener('hashchange', syncFromHash);
      syncFromHash();
    })();
  </script>
@endpush

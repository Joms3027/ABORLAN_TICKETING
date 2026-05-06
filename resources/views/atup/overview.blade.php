@extends('layouts.portal')

@section('title', 'Atup-atup Falls — overview')

@php
  $today = now()->toDateString();
  $daysWithSlots = collect($availability)->filter(fn ($r) => $r['remaining'] > 0)->count();
  $nextOpen = collect($availability)->first(fn ($r) => $r['remaining'] > 0);
@endphp

@push('head')
  <style>
    .atup-overview { --atup-sticky-top: 5.5rem; }

    .atup-hero-meta {
      display: flex; flex-wrap: wrap; gap: 0.5rem 0.75rem;
      margin: 1rem 0 1.35rem;
    }
    .atup-pill {
      display: inline-flex; align-items: center; gap: 0.4rem;
      padding: 0.35rem 0.75rem;
      border-radius: 999px;
      font-size: 0.78rem; font-weight: 600;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.35);
      color: rgba(255, 255, 255, 0.95);
    }
    .atup-pill svg { flex-shrink: 0; opacity: 0.9; }

    .atup-jump {
      display: flex; flex-wrap: wrap; gap: 0.5rem 1rem;
      margin-top: 0.25rem;
      padding-top: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.22);
    }
    .atup-jump a {
      font-size: 0.8125rem; font-weight: 600;
      color: var(--gold-light);
      text-decoration: none;
      border-bottom: 1px solid rgba(250, 204, 21, 0.45);
      transition: color 0.15s var(--ease), border-color 0.15s var(--ease);
    }
    .atup-jump a:hover { color: #fff; border-bottom-color: rgba(255, 255, 255, 0.6); }
    .atup-jump a:focus-visible {
      outline: 2px solid var(--gold-light); outline-offset: 3px; border-radius: 2px;
    }

    .atup-summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 0.75rem;
      margin-bottom: 1.75rem;
    }
    .atup-summary-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 0.9rem 1rem;
      box-shadow: var(--shadow-sm);
    }
    .atup-summary-card .k {
      font-size: 0.7rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.06em;
      color: var(--text-muted);
      margin-bottom: 0.25rem;
    }
    .atup-summary-card .v {
      font-size: 1.35rem; font-weight: 700;
      color: var(--navy); line-height: 1.15;
    }
    .atup-summary-card .sub {
      font-size: 0.78rem; color: var(--text-muted);
      margin-top: 0.25rem; line-height: 1.35;
    }

    .atup-layout {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.25rem;
      align-items: start;
    }
    @media (min-width: 900px) {
      .atup-layout { grid-template-columns: 1.4fr 1fr; }
    }

    .atup-section-anchor {
      scroll-margin-top: var(--atup-sticky-top);
    }

    .atup-panel-lead {
      color: var(--text-muted);
      font-size: 0.9rem;
      line-height: 1.5;
      margin: -0.35rem 0 1rem;
    }

    .atup-highlight-scroller {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
      padding-bottom: 0.35rem;
      margin: 0 -0.25rem;
      padding-left: 0.25rem;
      padding-right: 0.25rem;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
    }
    .atup-highlight-scroller .highlight {
      flex: 0 0 min(280px, 85vw);
      scroll-snap-align: start;
    }
    @media (min-width: 700px) {
      .atup-highlight-scroller {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        overflow: visible;
        padding: 0; margin: 0;
        scroll-snap-type: none;
      }
      .atup-highlight-scroller .highlight { flex: none; }
    }

    .atup-steps { list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem; }
    .atup-steps li {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 0.85rem 1rem;
      align-items: start;
    }
    .atup-steps .num {
      width: 2rem; height: 2rem;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.875rem; font-weight: 700;
      background: linear-gradient(135deg, var(--teal-muted) 0%, rgba(250, 204, 21, 0.35) 100%);
      color: var(--navy);
      border: 2px solid var(--border);
      flex-shrink: 0;
    }
    .atup-steps .text { color: var(--text); font-size: 0.9375rem; line-height: 1.55; }
    .atup-steps .text strong { color: var(--navy); }

    .atup-sidebar { display: flex; flex-direction: column; gap: 1.25rem; }
    @media (min-width: 900px) {
      .atup-sidebar > .panel:first-of-type {
        position: sticky;
        top: var(--atup-sticky-top);
      }
    }

    .atup-avail-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr);
      gap: 0.45rem;
      padding: 0.65rem 0.85rem;
      border-radius: var(--radius-sm);
      background: #fdf4ff;
      border: 1px solid var(--border);
      font-size: 0.875rem;
    }
    .atup-avail-row.full { background: #fee2e2; border-color: #fca5a5; }
    .atup-avail-row.tight { background: #fef3c7; border-color: #fcd34d; }
    .atup-avail-row.today {
      box-shadow: 0 0 0 2px var(--teal);
      border-color: rgba(192, 38, 211, 0.45);
    }
    .atup-avail-row.custom .row-top strong::before {
      content: "★ ";
      color: var(--teal);
    }
    .atup-avail-row .row-top {
      display: flex; flex-wrap: wrap; align-items: baseline;
      justify-content: space-between; gap: 0.35rem 1rem;
    }
    .atup-avail-row strong { color: var(--navy); }
    .atup-avail-row .badge-today {
      font-size: 0.65rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: 0.05em;
      padding: 0.15rem 0.45rem;
      border-radius: 999px;
      background: var(--teal);
      color: #fff;
    }
    .atup-avail-row .meter-wrap {
      height: 6px;
      border-radius: 999px;
      background: rgba(42, 10, 50, 0.08);
      overflow: hidden;
      margin-top: 0.15rem;
    }
    .atup-avail-row .meter {
      height: 100%;
      border-radius: 999px;
      background: linear-gradient(90deg, var(--success), #4ade80);
      transition: width 0.4s var(--ease);
    }
    .atup-avail-row.full .meter {
      background: linear-gradient(90deg, var(--danger), #f87171);
    }
    .atup-avail-row.tight .meter {
      background: linear-gradient(90deg, var(--warn), #fbbf24);
    }

    .atup-avail-foot {
      margin-top: 0.75rem;
      padding-top: 0.85rem;
      border-top: 1px dashed var(--border);
      font-size: 0.8125rem;
      color: var(--text-muted);
      line-height: 1.45;
    }

    .atup-reminders {
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 0.65rem;
    }
    .atup-reminders li {
      position: relative;
      padding: 0.65rem 0.85rem 0.65rem 2.35rem;
      background: linear-gradient(135deg, #fffbeb 0%, #fdf4ff 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-size: 0.875rem;
      color: var(--text-muted);
      line-height: 1.45;
    }
    .atup-reminders li::before {
      content: "";
      position: absolute;
      left: 0.85rem; top: 0.85rem;
      width: 0.5rem; height: 0.5rem;
      border-radius: 50%;
      background: var(--teal);
      box-shadow: 0 0 0 3px var(--teal-muted);
    }
    .atup-reminders strong { color: var(--navy); }

    @media (prefers-reduced-motion: reduce) {
      .atup-avail-row .meter { transition: none; }
      .highlight { transition: none !important; }
    }
  </style>
@endpush

@section('content')
  <div class="atup-overview">
    <section
      class="place-hero atup-section-anchor"
      id="top"
      style="--hero-img: url('{{ asset('images/IMG_20260319_112116_746.jpg') }}');"
      aria-labelledby="atup-hero-title"
    >
      <p class="eyebrow">Emerging tourist destination</p>
      <h1 id="atup-hero-title">Atup-atup Falls — Aborlan, Palawan</h1>
      <p>
        Atup-atup Falls is a hidden cascade tucked deep in <strong>Barangay Culandanum, Aborlan</strong>.
        Because of the area's geography, the official entry point for hikers is at
        <strong>Sitio Manaile, Barangay Dumanguena, Municipality of Narra, Palawan</strong>.
        Use this portal to reserve your hiking permit on the day you want to visit.
      </p>
      <div class="atup-hero-meta" aria-label="Key locations">
        <span class="atup-pill" title="Falls location">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/>
          </svg>
          Falls · Brgy. Culandanum, Aborlan
        </span>
        <span class="atup-pill" title="Where to meet your guides">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="12" cy="12" r="3"/><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
          </svg>
          Meet guides · Sitio Manaile, Narra
        </span>
      </div>
      <div class="cta-row">
        @auth
          @if (auth()->user()->is_admin)
            <a class="btn btn-light" href="{{ route('admin.dashboard') }}">Open admin dashboard</a>
          @else
            <a class="btn btn-light" href="{{ route('bookings.create') }}">Book a hiking permit</a>
            <a class="btn btn-ghost" href="{{ route('bookings.index') }}">View my bookings</a>
          @endif
        @else
          <a class="btn btn-light" href="{{ route('register') }}">Register to book</a>
          <a class="btn btn-ghost" href="{{ route('login') }}">Sign in</a>
        @endauth
      </div>
      <nav class="atup-jump" aria-label="On this page">
        <a href="#gallery">Photo gallery</a>
        <a href="#slots">Slot availability</a>
        <a href="#plan">How booking works</a>
      </nav>
    </section>

    <div class="atup-summary" aria-label="Booking snapshot for the next week">
      <div class="atup-summary-card">
        <div class="k">Next 7 days</div>
        <div class="v">{{ $daysWithSlots }}</div>
        <div class="sub">
          {{ $daysWithSlots === 1 ? 'day still has' : 'days still have' }} open capacity
        </div>
      </div>
      <div class="atup-summary-card">
        <div class="k">Next day with slots</div>
        <div class="v" style="font-size: 1.05rem;">
          @if ($nextOpen)
            {{ \Illuminate\Support\Carbon::parse($nextOpen['date'])->format('M j') }}
          @else
            —
          @endif
        </div>
        <div class="sub">
          @if ($nextOpen)
            {{ $nextOpen['remaining'] }} {{ $nextOpen['remaining'] === 1 ? 'slot' : 'slots' }} left · quota {{ $nextOpen['quota'] }}
          @else
            All listed dates are fully booked. Try another week later.
          @endif
        </div>
      </div>
    </div>

    <div class="atup-layout">
      <div>
        <div class="panel atup-section-anchor" id="gallery">
          <div class="panel-head">
            <h2>What you'll see along the trail</h2>
          </div>
          <p class="atup-panel-lead">
            Scroll on phones to browse scenes from the approach, pools, and viewpoints—each group hikes with a local guide from the approved entry point.
          </p>
          <div class="highlight-grid atup-highlight-scroller">
            @foreach ($highlights as $h)
              <article class="highlight">
                <img src="{{ asset('images/'.$h['image']) }}" alt="{{ $h['title'] }}" loading="lazy" width="400" height="180" />
                <div class="body">
                  <h3>{{ $h['title'] }}</h3>
                  <p>{{ $h['caption'] }}</p>
                </div>
              </article>
            @endforeach
          </div>
        </div>

        <div class="panel atup-section-anchor" id="plan">
          <div class="panel-head">
            <h2>Plan your visit</h2>
          </div>
          <p class="atup-panel-lead">Five quick steps from account creation to the trailhead briefing.</p>
          <ol class="atup-steps">
            <li>
              <span class="num" aria-hidden="true">1</span>
              <span class="text">
                <strong>Register an account</strong> on this portal so the LGU can verify visitors and reach you for confirmations.
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">2</span>
              <span class="text">
                <strong>Pick a date</strong> from the booking calendar. Daily slots are limited to protect the falls and ensure safe guided treks.
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">3</span>
              <span class="text">
                <strong>Submit your permit application</strong> with party size, mobile number, and emergency contact.
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">4</span>
              <span class="text">
                <strong>Wait for approval.</strong> An LGU administrator will review and confirm. You will see the status update inside
                @auth
                  <a href="{{ route('bookings.index') }}">My bookings</a>.
                @else
                  <a href="{{ route('login') }}">My bookings</a> after you sign in.
                @endauth
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">5</span>
              <span class="text">
                <strong>Arrive at Sitio Manaile, Brgy. Dumanguena, Narra</strong> on your booked date with a valid ID and your booking reference. Local guides will brief you before the trek.
              </span>
            </li>
          </ol>
        </div>
      </div>

      <div class="atup-sidebar">
        <div class="panel atup-section-anchor" id="slots">
          <div class="panel-head">
            <h2>Slots in the next 7 days</h2>
            <span class="muted">Live quota</span>
          </div>
          <p class="atup-panel-lead">
            Bars show share of capacity still open. Gold rows are filling up; red means fully booked.
          </p>
          <div class="avail-list" role="list">
            @foreach ($availability as $row)
              @php
                $isToday = $row['date'] === $today;
                $cls = $row['remaining'] === 0 ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
                if ($row['custom']) {
                  $cls .= ' custom';
                }
                if ($isToday) {
                  $cls .= ' today';
                }
                $pct = $row['quota'] > 0 ? (int) round(100 * $row['remaining'] / $row['quota']) : 0;
              @endphp
              <div class="atup-avail-row {{ trim($cls) }}" role="listitem">
                <div class="row-top">
                  <div>
                    <strong>{{ $row['label'] }}</strong>
                    @if ($isToday)<span class="badge-today">Today</span>@endif
                  </div>
                  <div style="text-align:right;">
                    @if ($row['remaining'] === 0)
                      <strong style="color: var(--danger);">Fully booked</strong>
                    @else
                      <strong>{{ $row['remaining'] }}</strong> of <strong>{{ $row['quota'] }}</strong> slots open
                    @endif
                  </div>
                </div>
                <div class="meter-wrap" aria-hidden="true">
                  <div class="meter" style="width: {{ $pct }}%;"></div>
                </div>
                @if ($row['note'])
                  <div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $row['note'] }}</div>
                @endif
              </div>
            @endforeach
          </div>
          <p class="atup-avail-foot">
            Need the full calendar and form? Continue to booking after you register or sign in—all counts update when you refresh.
          </p>
          @auth
            @unless (auth()->user()->is_admin)
              <div style="margin-top: 1rem;">
                <a class="btn btn-primary btn-block" href="{{ route('bookings.create') }}">Book a hiking permit</a>
              </div>
            @endunless
          @else
            <div style="margin-top: 1rem;">
              <a class="btn btn-primary btn-block" href="{{ route('register') }}">Register to book a slot</a>
            </div>
          @endauth
        </div>

        <div class="panel">
          <div class="panel-head">
            <h2>Reminders</h2>
          </div>
          <ul class="atup-reminders">
            <li>Permits are <strong>per day, per group</strong>. One active booking per visitor at a time.</li>
            <li>Cancellations are accepted up to the day before your booked hike.</li>
            <li>Bring valid ID, water, snacks, sturdy shoes, and a small bag for trash.</li>
            <li>Hiking is at your own risk. Follow guide instructions throughout the trek.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection

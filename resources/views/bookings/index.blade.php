@extends('layouts.portal')

@section('title', 'My bookings')

@php
  $today = today();
@endphp

@push('head')
  <style>
    .bookings-page .page-header {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem 1.5rem;
    }
    .bookings-page .page-header-text { flex: 1 1 280px; min-width: 0; }
    .bookings-page .page-header-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      align-items: center;
    }
    .bookings-page .page-header-actions .btn { min-width: 10rem; }

    .next-hike-banner {
      display: grid;
      grid-template-columns: auto 1fr auto;
      gap: 1rem 1.25rem;
      align-items: center;
      padding: 1.15rem 1.35rem;
      margin-bottom: 1.5rem;
      border-radius: var(--radius);
      border: 1px solid #86efac;
      background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 55%, #fff 100%);
      box-shadow: var(--shadow-sm);
    }
    .next-hike-banner .date-badge {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-width: 4.25rem;
      padding: 0.55rem 0.65rem;
      border-radius: var(--radius-sm);
      background: #fff;
      border: 1px solid #86efac;
      text-align: center;
      line-height: 1.1;
    }
    .next-hike-banner .date-badge .month {
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--success);
    }
    .next-hike-banner .date-badge .day {
      font-size: 1.65rem;
      font-weight: 700;
      color: var(--navy);
      letter-spacing: -0.02em;
    }
    .next-hike-banner .date-badge .weekday {
      font-size: 0.68rem;
      font-weight: 600;
      color: var(--text-muted);
      margin-top: 0.15rem;
    }
    .next-hike-banner h2 {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 0.25rem;
    }
    .next-hike-banner p {
      color: var(--text-muted);
      font-size: 0.9rem;
      max-width: 52ch;
    }
    .next-hike-banner .countdown {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      margin-top: 0.45rem;
      padding: 0.25rem 0.65rem;
      border-radius: 999px;
      background: rgba(22, 163, 74, 0.12);
      color: #14532d;
      font-size: 0.78rem;
      font-weight: 700;
    }

    .booking-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      margin-bottom: 1.15rem;
    }
    .booking-filters button {
      appearance: none;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--navy);
      font-family: inherit;
      font-size: 0.8125rem;
      font-weight: 600;
      padding: 0.45rem 0.85rem;
      border-radius: 999px;
      cursor: pointer;
      transition: background 0.15s var(--ease), border-color 0.15s var(--ease), color 0.15s var(--ease);
    }
    .booking-filters button:hover {
      border-color: var(--teal);
      background: var(--teal-muted);
      color: var(--teal-hover);
    }
    .booking-filters button.is-active {
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      border-color: transparent;
      color: #fff;
      box-shadow: 0 2px 10px rgba(192, 38, 211, 0.28);
    }
    .booking-filters button .count {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 1.35rem;
      padding: 0.05rem 0.35rem;
      margin-left: 0.35rem;
      border-radius: 999px;
      font-size: 0.68rem;
      background: rgba(42, 10, 50, 0.08);
    }
    .booking-filters button.is-active .count {
      background: rgba(255, 255, 255, 0.22);
    }

    .booking-list {
      display: grid;
      gap: 0.85rem;
    }
    .booking-card {
      display: grid;
      grid-template-columns: auto 1fr auto;
      gap: 1rem 1.25rem;
      align-items: center;
      padding: 1rem 1.15rem;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: #fff;
      transition: border-color 0.15s var(--ease), box-shadow 0.15s var(--ease), transform 0.15s var(--ease);
    }
    .booking-card:hover {
      border-color: rgba(192, 38, 211, 0.35);
      box-shadow: var(--shadow-sm);
      transform: translateY(-1px);
    }
    .booking-card.is-hidden { display: none; }
    .booking-card .card-date {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-width: 3.75rem;
      padding: 0.45rem 0.55rem;
      border-radius: var(--radius-sm);
      background: #fdf4ff;
      border: 1px solid var(--border);
      text-align: center;
      line-height: 1.1;
    }
    .booking-card .card-date .month {
      font-size: 0.62rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--teal-hover);
    }
    .booking-card .card-date .day {
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--navy);
    }
    .booking-card .card-date.is-past { background: #f8fafc; opacity: 0.85; }
    .booking-card .card-body { min-width: 0; }
    .booking-card .card-top {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.45rem 0.65rem;
      margin-bottom: 0.35rem;
    }
    .booking-card .ref {
      font-weight: 700;
      color: var(--navy);
      font-size: 0.95rem;
      letter-spacing: 0.01em;
    }
    .booking-card .meta {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem 1rem;
      color: var(--text-muted);
      font-size: 0.85rem;
    }
    .booking-card .meta span {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }
    .booking-card .meta svg { flex-shrink: 0; opacity: 0.7; }
    .booking-card .card-note {
      margin-top: 0.45rem;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--teal-hover);
    }
    .booking-card .card-note.is-muted { color: var(--text-muted); font-weight: 500; }
    .booking-card .card-actions {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
      align-items: stretch;
    }
    .booking-card .card-actions .btn { justify-content: center; min-width: 6.5rem; }

    .bookings-empty {
      text-align: center;
      padding: clamp(2rem, 5vw, 3.5rem) 1.5rem;
    }
    .bookings-empty .icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 4.5rem;
      height: 4.5rem;
      border-radius: 50%;
      background: var(--teal-muted);
      color: var(--teal-hover);
      margin-bottom: 1rem;
    }
    .bookings-empty h3 {
      font-size: 1.2rem;
      color: var(--navy);
      margin-bottom: 0.45rem;
    }
    .bookings-empty p {
      color: var(--text-muted);
      max-width: 38ch;
      margin: 0 auto 1.25rem;
      font-size: 0.95rem;
    }
    .bookings-empty .tips {
      display: grid;
      gap: 0.55rem;
      max-width: 28rem;
      margin: 1.5rem auto 0;
      text-align: left;
    }
    .bookings-empty .tip {
      display: flex;
      gap: 0.65rem;
      align-items: flex-start;
      padding: 0.65rem 0.85rem;
      border-radius: var(--radius-sm);
      background: #fdf4ff;
      border: 1px solid var(--border);
      font-size: 0.85rem;
      color: var(--text);
    }
    .bookings-empty .tip-num {
      flex-shrink: 0;
      width: 1.5rem;
      height: 1.5rem;
      display: grid;
      place-items: center;
      border-radius: 50%;
      background: var(--teal);
      color: #fff;
      font-size: 0.72rem;
      font-weight: 700;
    }

    .bookings-no-results {
      display: none;
      text-align: center;
      padding: 2rem 1rem;
      color: var(--text-muted);
      font-size: 0.95rem;
    }
    .bookings-no-results.is-visible { display: block; }

    @media (max-width: 720px) {
      .next-hike-banner {
        grid-template-columns: auto 1fr;
      }
      .next-hike-banner .banner-action {
        grid-column: 1 / -1;
      }
      .next-hike-banner .banner-action .btn { width: 100%; }
      .booking-card {
        grid-template-columns: auto 1fr;
      }
      .booking-card .card-actions {
        grid-column: 1 / -1;
        flex-direction: row;
        flex-wrap: wrap;
      }
      .booking-card .card-actions .btn { flex: 1 1 calc(50% - 0.2rem); min-width: 0; }
    }
  </style>
@endpush

@section('content')
  <div class="bookings-page">
    <div class="page-header">
      <div class="page-header-text">
        <h1>My hiking permits</h1>
        <p>Track your Atup-atup Falls bookings, check approval status, and share feedback after your visit.</p>
      </div>
      <div class="page-header-actions">
        <a class="btn btn-secondary" href="{{ route('atup.overview') }}">View availability</a>
        <a class="btn btn-primary" href="{{ route('bookings.create') }}">Book a new date</a>
      </div>
    </div>

    @if ($stats['total'] > 0)
      <div class="stat-grid">
        <div class="stat-card">
          <div class="label">Total bookings</div>
          <div class="value">{{ $stats['total'] }}</div>
          <div class="hint">All permit applications</div>
        </div>
        <div class="stat-card">
          <div class="label">Pending review</div>
          <div class="value">{{ $stats['pending'] }}</div>
          <div class="hint">Awaiting LGU decision</div>
        </div>
        <div class="stat-card">
          <div class="label">Upcoming hikes</div>
          <div class="value">{{ $stats['approved_upcoming'] }}</div>
          <div class="hint">Approved and scheduled</div>
        </div>
        <div class="stat-card">
          <div class="label">Feedback ready</div>
          <div class="value">{{ $stats['feedback_available'] }}</div>
          <div class="hint">Share your visit experience</div>
        </div>
      </div>
    @endif

    @if ($nextHike)
      @php
        $daysUntil = $today->diffInDays($nextHike->hike_date, false);
      @endphp
      <div class="next-hike-banner" role="status">
        <div class="date-badge" aria-hidden="true">
          <span class="month">{{ $nextHike->hike_date->format('M') }}</span>
          <span class="day">{{ $nextHike->hike_date->format('j') }}</span>
          <span class="weekday">{{ $nextHike->hike_date->format('D') }}</span>
        </div>
        <div>
          <h2>Your next hike is coming up</h2>
          <p>
            <strong>{{ $nextHike->reference_code }}</strong> ·
            {{ $nextHike->party_size }} hiker{{ $nextHike->party_size === 1 ? '' : 's' }} ·
            {{ $nextHike->hike_date->format('l, F j, Y') }}
          </p>
          <span class="countdown">
            @if ($daysUntil === 0)
              Today — arrive by 7:00 AM unless instructed otherwise
            @elseif ($daysUntil === 1)
              Tomorrow
            @else
              {{ $daysUntil }} days away
            @endif
          </span>
        </div>
        <div class="banner-action">
          <a class="btn btn-success" href="{{ route('bookings.show', $nextHike) }}">View details</a>
        </div>
      </div>
    @endif

    <div class="panel">
      <div class="panel-head">
        <h2>All bookings</h2>
        @if ($bookings->isNotEmpty())
          <span class="muted">{{ $stats['total'] }} permit{{ $stats['total'] === 1 ? '' : 's' }}</span>
        @endif
      </div>

      @if ($bookings->isEmpty())
        <div class="bookings-empty">
          <div class="icon" aria-hidden="true">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
              <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>
            </svg>
          </div>
          <h3>No hiking permits yet</h3>
          <p>Reserve a date for Atup-atup Falls. You'll receive a reference code once your application is submitted.</p>
          <a class="btn btn-primary" href="{{ route('bookings.create') }}">Book your first hike</a>
          <div class="tips">
            <div class="tip">
              <span class="tip-num">1</span>
              <span>Pick an open date and fill in your group's Visitors Entry Permit details.</span>
            </div>
            <div class="tip">
              <span class="tip-num">2</span>
              <span>Wait for LGU review — you'll see status updates here.</span>
            </div>
            <div class="tip">
              <span class="tip-num">3</span>
              <span>On hike day, bring valid ID and your booking reference to the jump-off point.</span>
            </div>
          </div>
        </div>
      @else
        <div class="booking-filters" role="tablist" aria-label="Filter bookings">
          <button type="button" class="is-active" data-filter="all" role="tab" aria-selected="true">
            All<span class="count">{{ $stats['total'] }}</span>
          </button>
          @if ($stats['pending'] > 0)
            <button type="button" data-filter="pending" role="tab" aria-selected="false">
              Pending<span class="count">{{ $stats['pending'] }}</span>
            </button>
          @endif
          @if ($stats['approved_upcoming'] > 0)
            <button type="button" data-filter="upcoming" role="tab" aria-selected="false">
              Upcoming<span class="count">{{ $stats['approved_upcoming'] }}</span>
            </button>
          @endif
          @if ($stats['past'] > 0)
            <button type="button" data-filter="past" role="tab" aria-selected="false">
              Past<span class="count">{{ $stats['past'] }}</span>
            </button>
          @endif
          @if ($stats['feedback_available'] > 0)
            <button type="button" data-filter="feedback" role="tab" aria-selected="false">
              Feedback<span class="count">{{ $stats['feedback_available'] }}</span>
            </button>
          @endif
        </div>

        <div class="booking-list" id="bookingList">
          @foreach ($bookings as $b)
            @php
              $isPast = in_array($b->status, ['completed', 'rejected', 'cancelled'], true)
                || ($b->status === 'approved' && $b->hike_date?->lt($today));
              $isUpcoming = $b->status === 'approved' && $b->hike_date?->isFuture();
              $filterTags = ['all'];
              if ($b->status === 'pending') {
                $filterTags[] = 'pending';
              }
              if ($isUpcoming) {
                $filterTags[] = 'upcoming';
              }
              if ($isPast) {
                $filterTags[] = 'past';
              }
              if ($b->canReceiveFeedback()) {
                $filterTags[] = 'feedback';
              }
            @endphp
            <article
              class="booking-card"
              data-filters="{{ implode(' ', $filterTags) }}"
            >
              <div class="card-date {{ $isPast ? 'is-past' : '' }}" aria-hidden="true">
                <span class="month">{{ $b->hike_date->format('M') }}</span>
                <span class="day">{{ $b->hike_date->format('j') }}</span>
              </div>

              <div class="card-body">
                <div class="card-top">
                  <span class="ref">{{ $b->reference_code }}</span>
                  <span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span>
                </div>
                <div class="meta">
                  <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    {{ $b->hike_date->format('D, M j, Y') }}
                  </span>
                  <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ $b->party_size }} hiker{{ $b->party_size === 1 ? '' : 's' }}
                  </span>
                  <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    Submitted {{ $b->created_at->format('M j, Y') }}
                  </span>
                </div>
                @if ($b->canReceiveFeedback())
                  <div class="card-note">Feedback available — share your visit experience</div>
                @elseif ($opens = $b->feedbackOpensOn())
                  <div class="card-note is-muted">Feedback opens {{ $opens->format('M j, Y') }}</div>
                @elseif ($b->feedback)
                  <div class="card-note is-muted">Feedback submitted</div>
                @endif
              </div>

              <div class="card-actions">
                <a class="btn btn-secondary btn-sm" href="{{ route('bookings.show', $b) }}">View details</a>
                @if ($b->canReceiveFeedback())
                  <a class="btn btn-primary btn-sm" href="{{ route('bookings.show', $b) }}#feedback">Leave feedback</a>
                @endif
              </div>
            </article>
          @endforeach
        </div>

        @if ($bookings->hasPages())
          <div class="bookings-pagination" style="margin-top:1.25rem;display:flex;justify-content:center;">
            {{ $bookings->links() }}
          </div>
        @endif

        <p class="bookings-no-results" id="bookingsNoResults" hidden>
          No bookings match this filter. <button type="button" class="btn btn-secondary btn-sm" id="clearBookingFilter" style="margin-top:0.75rem;">Show all</button>
        </p>
      @endif
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      var filters = document.querySelector('.booking-filters');
      var list = document.getElementById('bookingList');
      var noResults = document.getElementById('bookingsNoResults');
      var clearBtn = document.getElementById('clearBookingFilter');
      if (!filters || !list) return;

      function applyFilter(name) {
        var cards = list.querySelectorAll('.booking-card');
        var visible = 0;

        cards.forEach(function (card) {
          var tags = (card.getAttribute('data-filters') || '').split(/\s+/);
          var show = name === 'all' || tags.indexOf(name) !== -1;
          card.classList.toggle('is-hidden', !show);
          if (show) visible++;
        });

        filters.querySelectorAll('button').forEach(function (btn) {
          var active = btn.getAttribute('data-filter') === name;
          btn.classList.toggle('is-active', active);
          btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        if (noResults) {
          noResults.hidden = visible > 0;
          noResults.classList.toggle('is-visible', visible === 0);
        }
      }

      filters.addEventListener('click', function (e) {
        var btn = e.target.closest('button[data-filter]');
        if (!btn) return;
        applyFilter(btn.getAttribute('data-filter'));
      });

      if (clearBtn) {
        clearBtn.addEventListener('click', function () {
          applyFilter('all');
        });
      }
    })();
  </script>
@endpush

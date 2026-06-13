<details
  class="tg-guide-monitor {{ $guideBookings->isEmpty() ? 'is-empty' : '' }}"
  id="monitor-guide-{{ $guide->id }}"
  data-monitor-name="{{ strtolower($guide->name) }}"
  data-monitor-has-groups="{{ $guideBookings->isNotEmpty() ? '1' : '0' }}"
  {{ $guideBookings->isNotEmpty() ? 'open' : '' }}
>
  <summary>
    <span class="tg-avatar" aria-hidden="true">{{ $initials($guide->name) ?: '?' }}</span>
    <span class="tg-monitor-name">
      {{ $guide->name }}
      <span class="muted">· age {{ $guide->age }}</span>
    </span>
    <span class="tg-group-badge {{ $guideBookings->isNotEmpty() ? 'has-groups' : '' }}">
      {{ $guideBookings->count() }}
    </span>
    <span class="tg-monitor-chevron" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg>
    </span>
  </summary>
  <div class="tg-monitor-body">
    @if ($guideBookings->isEmpty())
      <p class="tg-empty-inline">No upcoming assignments for this guide.</p>
    @else
      @foreach ($guideBookings as $booking)
        <div class="tg-booking-row">
          <div class="tg-booking-date-badge" aria-label="{{ $booking->hike_date->format('F j, Y') }}">
            <div class="day">{{ $booking->hike_date->format('j') }}</div>
            <div class="mon">{{ $booking->hike_date->format('M') }}</div>
          </div>
          <div>
            <div class="tg-booking-ref">{{ $booking->reference_code }}</div>
            <div class="tg-booking-meta">
              {{ $booking->user?->name ?? '—' }}
              @if ($booking->party_size > 1)
                · {{ $booking->party_size }} hikers
              @else
                · 1 hiker
              @endif
            </div>
          </div>
          <div class="tg-booking-side">
            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">Open</a>
          </div>
        </div>
      @endforeach
    @endif
  </div>
</details>

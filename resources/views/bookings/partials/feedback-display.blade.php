@php
  $fb = $booking->feedback;
@endphp

<style>
  .feedback-summary-stars { color: var(--gold, #ca8a04); letter-spacing: 0.05em; }
  .feedback-summary-row {
    display: grid;
    grid-template-columns: minmax(8rem, 10rem) 1fr;
    gap: 0.5rem 1rem;
    align-items: center;
    margin-bottom: 0.65rem;
  }
  .feedback-summary-label { font-weight: 600; color: var(--navy); font-size: 0.9rem; }
</style>

<div class="feedback-summary">
  <div class="feedback-summary-row">
    <div class="feedback-summary-label">Hospitality</div>
    <div class="feedback-summary-stars" aria-label="{{ $fb->rating_hospitality }} out of 5">
      @for ($i = 1; $i <= 5; $i++){{ $i <= $fb->rating_hospitality ? '★' : '☆' }}@endfor
    </div>
  </div>
  <div class="feedback-summary-row">
    <div class="feedback-summary-label">Tour guide</div>
    <div class="feedback-summary-stars" aria-label="{{ $fb->rating_tour_guide ? $fb->rating_tour_guide.' out of 5' : 'Not rated' }}">
      @if ($fb->rating_tour_guide)
        @for ($i = 1; $i <= 5; $i++){{ $i <= $fb->rating_tour_guide ? '★' : '☆' }}@endfor
      @else
        <span style="color: var(--text-muted); font-size: 0.9rem;">Not rated</span>
      @endif
    </div>
  </div>
  <div class="feedback-summary-row">
    <div class="feedback-summary-label">Place</div>
    <div class="feedback-summary-stars" aria-label="{{ $fb->rating_place }} out of 5">
      @for ($i = 1; $i <= 5; $i++){{ $i <= $fb->rating_place ? '★' : '☆' }}@endfor
    </div>
  </div>
  @if ($fb->comment)
    <div style="margin-top: 1rem;">
      <div class="hint" style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Your comment</div>
      <p style="margin-top:0.25rem; white-space: pre-line;">{{ $fb->comment }}</p>
    </div>
  @endif
  <p class="hint" style="margin-top: 0.85rem;">Submitted {{ $fb->created_at->format('M j, Y g:i A') }}</p>
</div>

@php
  $hasGuide = (bool) $booking->tour_guide_id;
@endphp

<style>
  .feedback-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.15rem;
    margin-top: 0.35rem;
  }
  .feedback-stars input { position: absolute; opacity: 0; width: 0; height: 0; }
  .feedback-stars label {
    cursor: pointer;
    font-size: 1.65rem;
    line-height: 1;
    color: #e9d5ff;
    transition: color 0.15s var(--ease, ease);
  }
  .feedback-stars label:hover,
  .feedback-stars label:hover ~ label,
  .feedback-stars input:checked ~ label {
    color: var(--gold, #ca8a04);
  }
  .feedback-rating-block { margin-bottom: 1.1rem; }
  .feedback-rating-block .rating-label {
    font-weight: 600;
    color: var(--navy);
    font-size: 0.95rem;
  }
</style>

<form method="POST" action="{{ route('bookings.feedback.store', $booking) }}">
  @csrf

  <div class="feedback-rating-block">
    <div class="rating-label">Hospitality</div>
    <p class="hint">How welcome and helpful did the LGU and staff make you feel?</p>
    <div class="feedback-stars" role="group" aria-label="Hospitality rating">
      @for ($i = 5; $i >= 1; $i--)
        <input type="radio" id="rating_hospitality_{{ $i }}" name="rating_hospitality" value="{{ $i }}" {{ (int) old('rating_hospitality') === $i ? 'checked' : '' }} required />
        <label for="rating_hospitality_{{ $i }}" title="{{ $i }} star{{ $i === 1 ? '' : 's' }}">★</label>
      @endfor
    </div>
    @error('rating_hospitality')
      <div class="error">{{ $message }}</div>
    @enderror
  </div>

  <div class="feedback-rating-block">
    <div class="rating-label">
      Tour guide
      @if ($hasGuide && $booking->tourGuide)
        — {{ $booking->tourGuide->name }}
      @endif
    </div>
    <p class="hint">How was your assigned guide during the trek?</p>
    <div class="feedback-stars" role="group" aria-label="Tour guide rating">
      @for ($i = 5; $i >= 1; $i--)
        <input
          type="radio"
          id="rating_tour_guide_{{ $i }}"
          name="rating_tour_guide"
          value="{{ $i }}"
          {{ (int) old('rating_tour_guide') === $i ? 'checked' : '' }}
          @if ($hasGuide) required @endif
        />
        <label for="rating_tour_guide_{{ $i }}" title="{{ $i }} star{{ $i === 1 ? '' : 's' }}">★</label>
      @endfor
    </div>
    @unless ($hasGuide)
      <p class="hint" style="margin-top: 0.35rem;">No tour guide was assigned to this booking — you may skip this rating.</p>
    @endunless
    @error('rating_tour_guide')
      <div class="error">{{ $message }}</div>
    @enderror
  </div>

  <div class="feedback-rating-block">
    <div class="rating-label">Place (Atup-atup Falls)</div>
    <p class="hint">How would you rate the destination and overall experience?</p>
    <div class="feedback-stars" role="group" aria-label="Place rating">
      @for ($i = 5; $i >= 1; $i--)
        <input type="radio" id="rating_place_{{ $i }}" name="rating_place" value="{{ $i }}" {{ (int) old('rating_place') === $i ? 'checked' : '' }} required />
        <label for="rating_place_{{ $i }}" title="{{ $i }} star{{ $i === 1 ? '' : 's' }}">★</label>
      @endfor
    </div>
    @error('rating_place')
      <div class="error">{{ $message }}</div>
    @enderror
  </div>

  <div class="field" style="margin-top: 0.5rem;">
    <label for="feedback_comment">Comments (optional)</label>
    <textarea id="feedback_comment" name="comment" class="textarea" rows="4" placeholder="Tell us what went well or what we can improve.">{{ old('comment') }}</textarea>
    @error('comment')
      <div class="error">{{ $message }}</div>
    @enderror
  </div>

  @error('feedback')
    <div class="alert" style="margin-bottom: 1rem; background:#fef2f2; border-color:#fecaca; color:#991b1b;">{{ $message }}</div>
  @enderror

  <div class="form-actions" style="margin-top: 1rem;">
    <button type="submit" class="btn btn-primary">Submit feedback</button>
  </div>
</form>

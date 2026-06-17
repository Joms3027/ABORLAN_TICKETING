@extends('layouts.auth')

@section('title', 'Verify your email')

@push('head')
  <style>
    .otp-inputs {
      display: flex;
      gap: 0.5rem;
      justify-content: center;
      margin: 0.5rem 0 0;
    }

    .otp-digit {
      width: 2.75rem;
      height: 3.25rem;
      text-align: center;
      font-size: 1.35rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      padding: 0;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      color: var(--navy);
      transition: border-color 0.15s var(--ease), box-shadow 0.15s var(--ease);
    }

    .otp-digit:focus {
      outline: none;
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.18);
    }

    .otp-digit.is-invalid {
      border-color: #fca5a5;
      background: #fffafb;
    }

    .otp-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem 1.25rem;
      justify-content: space-between;
      align-items: center;
      margin-top: 1rem;
      font-size: 0.875rem;
      color: var(--text-muted);
    }

    .otp-timer {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-weight: 600;
      color: var(--navy);
    }

    .otp-timer svg { width: 1rem; height: 1rem; }

    .otp-actions-secondary {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      justify-content: space-between;
      align-items: center;
      margin-top: 1rem;
      font-size: 0.875rem;
    }

    .btn-link {
      background: none;
      border: none;
      padding: 0;
      font-family: inherit;
      font-size: inherit;
      font-weight: 700;
      color: var(--teal-hover);
      cursor: pointer;
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    .btn-link:disabled {
      color: var(--text-muted);
      cursor: not-allowed;
      text-decoration: none;
    }

    .btn-link:hover:not(:disabled) { color: var(--navy); }

    .alert-warning {
      background: #fef9c3;
      color: #713f12;
      border-color: #fde047;
    }

    @media (max-width: 400px) {
      .otp-digit { width: 2.35rem; height: 3rem; font-size: 1.2rem; }
      .otp-inputs { gap: 0.35rem; }
    }
  </style>
@endpush

@section('content')
  <div class="auth-card">
    <div class="auth-card-head">
      <div class="auth-badge">Email verification</div>
      <h1>Enter your verification code</h1>
      <p class="sub">
        @if ($purpose === 'register')
          We sent a 6-digit code to <strong>{{ $maskedEmail }}</strong> to verify your new account.
        @else
          We sent a 6-digit code to <strong>{{ $maskedEmail }}</strong> to complete your sign-in.
        @endif
      </p>
    </div>

    @if ($isLocked && $lockedUntil)
      <div class="alert alert-warning" role="alert">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Too many incorrect attempts. Please wait until the lockout expires before trying again.
      </div>
    @endif

    @error('otp_code')
      <div class="alert alert-error" role="alert">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $message }}
      </div>
    @enderror

    <form method="POST" action="{{ route('otp.verify') }}" id="otp-form" novalidate data-otp-form @if($isLocked) data-locked="true" @endif>
      @csrf
      <div class="field">
        <label for="otp-digit-0">Verification code</label>
        <div class="otp-inputs" role="group" aria-label="6-digit verification code">
          @for ($i = 0; $i < 6; $i++)
            <input
              type="text"
              inputmode="numeric"
              pattern="[0-9]*"
              maxlength="1"
              class="otp-digit @error('otp_code') is-invalid @enderror"
              id="otp-digit-{{ $i }}"
              data-otp-digit
              autocomplete="one-time-code"
              @if($i === 0) autofocus @endif
              @if($isLocked) disabled @endif
              aria-label="Digit {{ $i + 1 }}"
            />
          @endfor
        </div>
        <input type="hidden" name="otp_code" id="otp_code" value="{{ old('otp_code') }}" />
        <p class="hint">Enter the 6-digit code from your email. It expires in {{ config('otp.expiry_minutes', 5) }} minutes.</p>
      </div>

      <div class="otp-meta">
        @if ($expiresAt)
          <span class="otp-timer" data-expires-at="{{ $expiresAt->toIso8601String() }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Expires in <span data-expiry-countdown>--:--</span>
          </span>
        @endif
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block" @if($isLocked) disabled @endif>
          <span data-btn-label>
            Verify and continue
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </span>
          <span data-btn-loading hidden>Verifying…</span>
        </button>
      </div>
    </form>

    <div class="otp-actions-secondary">
      <form method="POST" action="{{ route('otp.resend') }}" id="resend-form" data-resend-form>
        @csrf
        <button
          type="submit"
          class="btn-link"
          data-resend-btn
          data-cooldown="{{ $resendCooldown }}"
          @if($isLocked || $resendCooldown > 0) disabled @endif
        >
          Resend code
          <span data-resend-countdown @if($resendCooldown <= 0) hidden @endif>({{ $resendCooldown }}s)</span>
        </button>
      </form>

      <form method="POST" action="{{ route('otp.cancel') }}">
        @csrf
        <button type="submit" class="btn-link" style="color: var(--text-muted); font-weight: 600;">Cancel and go back</button>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/otp-verify.js') }}" defer></script>
@endpush

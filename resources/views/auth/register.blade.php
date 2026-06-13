@extends('layouts.auth')

@section('title', 'Create your account')

@section('content')
  <div class="auth-card auth-card-wide">
    <div class="auth-card-head">
      <div class="auth-badge">Visitor registration</div>
      <h1>Create your account</h1>
      <p class="sub">Register once to book hiking permits for Atup-atup Falls. The form takes about a minute.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" novalidate data-auth-form>
      @csrf
      <div class="form-grid">
        <div class="field">
          <label for="name">Full name</label>
          <div class="input-group">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input
              id="name"
              class="input @error('name') is-invalid @enderror"
              type="text"
              name="name"
              value="{{ old('name') }}"
              autocomplete="name"
              placeholder="Juan Dela Cruz"
              required
              autofocus
              @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
            />
          </div>
          @error('name')
            <div class="error" id="name-error" role="alert">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="field">
          <label for="email">Email address</label>
          <div class="input-group">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            <input
              id="email"
              class="input @error('email') is-invalid @enderror"
              type="email"
              name="email"
              value="{{ old('email') }}"
              autocomplete="email"
              inputmode="email"
              placeholder="you@example.com"
              required
              @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
            />
          </div>
          @error('email')
            <div class="error" id="email-error" role="alert">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="field">
          <label for="phone">Mobile number</label>
          <div class="input-group">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
            </span>
            <input
              id="phone"
              class="input @error('phone') is-invalid @enderror"
              type="tel"
              name="phone"
              value="{{ old('phone') }}"
              autocomplete="tel"
              inputmode="tel"
              placeholder="+63 912 345 6789"
              required
              @error('phone') aria-invalid="true" aria-describedby="phone-error phone-hint" @else aria-describedby="phone-hint" @enderror
            />
          </div>
          <div class="hint" id="phone-hint">Used for booking confirmations and emergency coordination.</div>
          @error('phone')
            <div class="error" id="phone-error" role="alert">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="form-grid two-col">
          <div class="field">
            <label for="password">Password</label>
            <div class="input-group">
              <span class="input-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              </span>
              <input
                id="password"
                class="input has-toggle @error('password') is-invalid @enderror"
                type="password"
                name="password"
                autocomplete="new-password"
                placeholder="Min. 8 characters"
                required
                minlength="8"
                @error('password') aria-invalid="true" aria-describedby="password-error password-hint" @else aria-describedby="password-hint" @enderror
              />
              <button type="button" class="input-toggle" data-toggle-password aria-controls="password" aria-label="Show password" aria-pressed="false">
                <svg data-icon-show viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-icon-hide hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              </button>
            </div>
            <div class="hint" id="password-hint">At least 8 characters with letters and numbers recommended.</div>
            @error('password')
              <div class="error" id="password-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <div class="input-group">
              <span class="input-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              </span>
              <input
                id="password_confirmation"
                class="input has-toggle"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                placeholder="Re-enter password"
                required
                minlength="8"
              />
              <button type="button" class="input-toggle" data-toggle-password aria-controls="password_confirmation" aria-label="Show password" aria-pressed="false">
                <svg data-icon-show viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg data-icon-hide hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block">
          <span data-btn-label>
            Create account
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
          </span>
          <span data-btn-loading hidden>Creating account…</span>
        </button>
      </div>
    </form>

    <p class="auth-switch">
      Already have an account? <a href="{{ route('login') }}">Sign in instead</a>
    </p>
  </div>
@endsection

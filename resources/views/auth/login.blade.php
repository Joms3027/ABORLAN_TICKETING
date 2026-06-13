@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
  <div class="auth-card">
    <div class="auth-card-head">
      <div class="auth-badge">Welcome back</div>
      <h1>Sign in to your account</h1>
      <p class="sub">Access your hiking permit dashboard and manage bookings for Atup-atup Falls.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" novalidate data-auth-form>
      @csrf
      <div class="form-grid">
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
              autofocus
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
              autocomplete="current-password"
              placeholder="Enter your password"
              required
              @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
            />
            <button type="button" class="input-toggle" data-toggle-password aria-controls="password" aria-label="Show password" aria-pressed="false">
              <svg data-icon-show viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg data-icon-hide hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          @error('password')
            <div class="error" id="password-error" role="alert">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="field">
          <label class="check-row">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
            <span class="check-box" aria-hidden="true"></span>
            <span>Keep me signed in on this device</span>
          </label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-block">
          <span data-btn-label>
            Sign in
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </span>
          <span data-btn-loading hidden>Signing in…</span>
        </button>
      </div>
    </form>

    <p class="auth-switch">
      New visitor? <a href="{{ route('register') }}">Create a free account</a>
    </p>
  </div>
@endsection

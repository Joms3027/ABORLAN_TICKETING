@extends('layouts.portal')

@section('title', 'Sign in')

@section('content')
  <div class="auth-wrap">
    <div class="auth-card">
      <h1>Sign in</h1>
      <p class="sub">Access your hiking permit dashboard for Atup-atup Falls.</p>

      <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        <div class="form-grid">
          <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" class="input" type="password" name="password" required />
            @error('password')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label style="display:flex; gap:0.5rem; align-items:center; font-weight: 500; color: var(--text);">
              <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} />
              Keep me signed in on this device
            </label>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary btn-block">Sign in</button>
        </div>
      </form>

      <p style="margin-top: 1.1rem; font-size: 0.9rem; color: var(--text-muted);">
        New visitor? <a href="{{ route('register') }}">Create an account</a>.
      </p>
    </div>
  </div>
@endsection

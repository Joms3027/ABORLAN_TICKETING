@extends('layouts.portal')

@section('title', 'Create your account')

@section('content')
  <div class="auth-wrap">
    <div class="auth-card">
      <h1>Create your visitor account</h1>
      <p class="sub">Register to book a hiking permit for Atup-atup Falls. The form takes about a minute.</p>

      <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        <div class="form-grid">
          <div class="field">
            <label for="name">Full name</label>
            <input id="name" class="input" type="text" name="name" value="{{ old('name') }}" required autofocus />
            @error('name')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label for="email">Email address</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required />
            @error('email')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label for="phone">Mobile number</label>
            <input id="phone" class="input" type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. +63 912 345 6789" required />
            <div class="hint">Used for booking confirmations and emergency coordination.</div>
            @error('phone')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" class="input" type="password" name="password" required />
            <div class="hint">At least 8 characters.</div>
            @error('password')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" required />
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary btn-block">Create account</button>
        </div>
      </form>

      <p style="margin-top: 1.1rem; font-size: 0.9rem; color: var(--text-muted);">
        Already have an account? <a href="{{ route('login') }}">Sign in instead</a>.
      </p>
    </div>
  </div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Account') · {{ config('app.name') }}</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy: #2a0a32;
      --navy-soft: #701a75;
      --teal: #c026d3;
      --teal-hover: #a21caf;
      --teal-muted: rgba(192, 38, 211, 0.14);
      --gold: #ca8a04;
      --gold-light: #ffea00;
      --magenta-bright: #e879f9;
      --text: #1a0a1f;
      --text-muted: #6b4a6e;
      --border: #f5d0f3;
      --surface: #ffffff;
      --bg: #fce7f3;
      --bg-subtle: #fffbeb;
      --radius: 14px;
      --radius-sm: 8px;
      --shadow-sm: 0 1px 2px rgba(88, 28, 135, 0.08);
      --shadow: 0 4px 24px rgba(190, 24, 93, 0.1);
      --shadow-lg: 0 20px 50px rgba(126, 34, 206, 0.14);
      --font: "Source Sans 3", system-ui, -apple-system, "Segoe UI", sans-serif;
      --ease: cubic-bezier(0.4, 0, 0.2, 1);
      --success: #16a34a;
      --danger: #dc2626;
      --auth-aside-img: url('{{ asset('images/IMG_20260319_112116_746.jpg') }}');
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    @media (prefers-reduced-motion: reduce) {
      html { scroll-behavior: auto; }
      *, *::before, *::after { transition: none !important; animation: none !important; }
    }

    body.auth-body {
      font-family: var(--font);
      color: var(--text);
      background: linear-gradient(165deg, var(--bg-subtle) 0%, #fff4e6 35%, #fdf4ff 70%, #fef9c3 100%);
      line-height: 1.6;
      font-size: 1rem;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      min-height: 100dvh;
    }

    a { color: var(--teal-hover); text-decoration: none; font-weight: 600; }
    a:hover { color: var(--navy); text-decoration: underline; text-underline-offset: 2px; }

    .auth-page {
      display: grid;
      grid-template-columns: 1fr;
      min-height: 100vh;
      min-height: 100dvh;
    }

    @media (min-width: 960px) {
      .auth-page { grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr); }
    }

    .auth-aside {
      display: none;
      position: relative;
      overflow: hidden;
      color: #fff;
      background: var(--navy);
    }

    @media (min-width: 960px) {
      .auth-aside { display: flex; flex-direction: column; }
    }

    .auth-aside-bg {
      position: absolute;
      inset: 0;
      background:
        linear-gradient(155deg, rgba(42, 10, 50, 0.92) 0%, rgba(112, 26, 117, 0.78) 45%, rgba(15, 92, 85, 0.72) 100%),
        var(--auth-aside-img) center/cover no-repeat;
    }

    .auth-aside-bg::after {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 20% 80%, rgba(255, 234, 0, 0.12), transparent 45%);
    }

    .auth-aside-inner {
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 100%;
      padding: clamp(1.75rem, 4vw, 2.75rem);
    }

    .auth-brand {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      color: #fff;
      text-decoration: none;
    }

    .auth-brand:hover { color: var(--gold-light); text-decoration: none; }

    .auth-brand img {
      height: 52px;
      width: auto;
      filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.35));
    }

    .auth-brand .title {
      font-weight: 700;
      font-size: 1.05rem;
      line-height: 1.25;
      display: block;
    }

    .auth-brand .tag {
      display: block;
      font-size: 0.68rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: rgba(255, 255, 255, 0.6);
      margin-top: 0.15rem;
    }

    .auth-aside-copy { max-width: 34ch; }

    .auth-aside-copy .eyebrow {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--gold-light);
      margin-bottom: 0.65rem;
    }

    .auth-aside-copy h2 {
      font-size: clamp(1.65rem, 3vw, 2.15rem);
      font-weight: 700;
      letter-spacing: -0.02em;
      line-height: 1.15;
      margin-bottom: 0.85rem;
    }

    .auth-aside-copy p {
      color: rgba(255, 255, 255, 0.88);
      font-size: 1rem;
      margin-bottom: 1.5rem;
    }

    .auth-benefits {
      list-style: none;
      display: grid;
      gap: 0.75rem;
    }

    .auth-benefits li {
      display: flex;
      align-items: flex-start;
      gap: 0.65rem;
      font-size: 0.9375rem;
      color: rgba(255, 255, 255, 0.9);
    }

    .auth-benefits svg {
      flex-shrink: 0;
      width: 1.15rem;
      height: 1.15rem;
      margin-top: 0.15rem;
      color: var(--gold-light);
    }

    .auth-main {
      display: flex;
      flex-direction: column;
      min-width: 0;
      padding: clamp(1rem, 3vw, 2rem);
    }

    .auth-main-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: clamp(1rem, 3vw, 2rem);
    }

    .auth-back {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--text-muted);
      text-decoration: none;
      padding: 0.45rem 0.65rem;
      border-radius: var(--radius-sm);
      transition: background 0.15s var(--ease), color 0.15s var(--ease);
    }

    .auth-back:hover {
      background: var(--teal-muted);
      color: var(--teal-hover);
      text-decoration: none;
    }

    .auth-back svg { width: 1rem; height: 1rem; }

    .auth-mobile-brand {
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    @media (min-width: 960px) {
      .auth-mobile-brand { display: none; }
    }

    .auth-mobile-brand img { height: 36px; width: auto; }

    .auth-mobile-brand span {
      font-weight: 700;
      font-size: 0.9rem;
      color: var(--navy);
      line-height: 1.2;
    }

    .auth-main-center {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
    }

    .auth-main-panel {
      width: min(100%, 540px);
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

    .auth-card {
      width: min(100%, 480px);
      background: #fff;
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      padding: clamp(1.5rem, 4vw, 2.25rem);
      border: 1px solid var(--border);
    }

    .auth-card.auth-card-wide { width: min(100%, 540px); }

    .auth-card-head { margin-bottom: 1.5rem; }

    .auth-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.3rem 0.7rem;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--teal-hover);
      background: var(--teal-muted);
      border: 1px solid rgba(192, 38, 211, 0.2);
      margin-bottom: 0.85rem;
    }

    .auth-card h1 {
      font-size: clamp(1.45rem, 3.5vw, 1.75rem);
      color: var(--navy);
      letter-spacing: -0.02em;
      line-height: 1.2;
      margin-bottom: 0.45rem;
    }

    .auth-card .sub {
      color: var(--text-muted);
      font-size: 0.9375rem;
      max-width: 42ch;
    }

    .auth-switch {
      margin-top: 1.35rem;
      padding-top: 1.25rem;
      border-top: 1px solid var(--border);
      font-size: 0.9rem;
      color: var(--text-muted);
      text-align: center;
    }

    .auth-switch a { font-weight: 700; }

    .form-grid { display: grid; gap: 1.1rem; }

    .form-grid.two-col {
      grid-template-columns: 1fr;
      gap: 1.1rem;
    }

    @media (min-width: 520px) {
      .form-grid.two-col { grid-template-columns: 1fr 1fr; }
    }

    .field label {
      display: block;
      font-weight: 600;
      color: var(--navy);
      font-size: 0.8125rem;
      margin-bottom: 0.4rem;
      letter-spacing: 0.01em;
    }

    .field .hint {
      font-size: 0.75rem;
      color: var(--text-muted);
      margin-top: 0.3rem;
      line-height: 1.4;
    }

    .field .error {
      display: flex;
      align-items: flex-start;
      gap: 0.35rem;
      color: var(--danger);
      font-size: 0.8125rem;
      margin-top: 0.35rem;
      font-weight: 500;
    }

    .field .error svg {
      flex-shrink: 0;
      width: 0.95rem;
      height: 0.95rem;
      margin-top: 0.1rem;
    }

    .input-group {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 0.85rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      pointer-events: none;
    }

    .input-icon svg { width: 1.05rem; height: 1.05rem; }

    .input, .select, .textarea {
      width: 100%;
      padding: 0.72rem 0.85rem 0.72rem 2.65rem;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 0.9375rem;
      color: var(--text);
      background: #fff;
      transition: border-color 0.15s var(--ease), box-shadow 0.15s var(--ease);
    }

    .input-group .input-toggle {
      position: absolute;
      right: 0.35rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.25rem;
      height: 2.25rem;
      border: none;
      border-radius: var(--radius-sm);
      background: transparent;
      color: var(--text-muted);
      cursor: pointer;
      transition: background 0.15s var(--ease), color 0.15s var(--ease);
    }

    .input-group .input-toggle:hover {
      background: var(--teal-muted);
      color: var(--teal-hover);
    }

    .input-group .input-toggle svg { width: 1.1rem; height: 1.1rem; }

    .input-group .input.has-toggle { padding-right: 2.75rem; }

    .input:focus, .select:focus, .textarea:focus {
      outline: none;
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(192, 38, 211, 0.18);
    }

    .input.is-invalid {
      border-color: #fca5a5;
      background: #fffafb;
    }

    .input.is-invalid:focus {
      border-color: var(--danger);
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }

    .check-row {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      cursor: pointer;
      user-select: none;
      font-size: 0.9rem;
      color: var(--text);
      font-weight: 500;
    }

    .check-row input {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
    }

    .check-box {
      flex-shrink: 0;
      width: 1.15rem;
      height: 1.15rem;
      border: 2px solid var(--border);
      border-radius: 4px;
      background: #fff;
      display: grid;
      place-items: center;
      transition: background 0.15s var(--ease), border-color 0.15s var(--ease);
    }

    .check-row input:focus-visible + .check-box {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    .check-row input:checked + .check-box {
      background: var(--teal);
      border-color: var(--teal);
    }

    .check-row input:checked + .check-box::after {
      content: "";
      width: 0.35rem;
      height: 0.6rem;
      border: solid #fff;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg) translateY(-1px);
    }

    .form-actions { margin-top: 1.35rem; }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      padding: 0.75rem 1.25rem;
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-weight: 700;
      font-size: 0.9375rem;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: background 0.2s var(--ease), transform 0.15s var(--ease), box-shadow 0.2s var(--ease);
      white-space: nowrap;
    }

    .btn svg { width: 1.05rem; height: 1.05rem; }

    .btn-primary {
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      color: #fff;
      box-shadow: 0 4px 14px rgba(192, 38, 211, 0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(192, 38, 211, 0.35);
    }

    .btn-primary:active { transform: translateY(0); }

    .btn-block { width: 100%; }

    .alert {
      border-radius: var(--radius-sm);
      padding: 0.85rem 1rem;
      margin-bottom: 1.25rem;
      font-size: 0.9375rem;
      border: 1px solid transparent;
      display: flex;
      align-items: flex-start;
      gap: 0.55rem;
    }

    .alert svg { flex-shrink: 0; width: 1.1rem; height: 1.1rem; margin-top: 0.1rem; }

    .alert-success { background: #dcfce7; color: #14532d; border-color: #86efac; }
    .alert-error { background: #fee2e2; color: #7f1d1d; border-color: #fca5a5; }

    .auth-footer {
      margin-top: auto;
      padding-top: 1.5rem;
      text-align: center;
      font-size: 0.78rem;
      color: var(--text-muted);
    }

    @media (max-width: 640px) {
      .input { font-size: 16px; }
      .btn { min-height: 48px; }
    }
  </style>
  @stack('head')
</head>
<body class="auth-body">
  <div class="auth-page">
    <aside class="auth-aside" aria-hidden="true">
      <div class="auth-aside-bg"></div>
      <div class="auth-aside-inner">
        <a href="{{ url('/') }}" class="auth-brand">
          <img src="{{ asset('images/Logo.png') }}" alt="" decoding="async" />
          <span>
            <span class="title">{{ config('app.name') }}</span>
            <span class="tag">Atup-atup Falls Permit System</span>
          </span>
        </a>

        <div class="auth-aside-copy">
          <div class="eyebrow">Municipality of Aborlan</div>
          <h2>Book your visit to Atup-atup Falls</h2>
          <p>Secure your hiking permit online — quick registration, real-time availability, and digital confirmations.</p>
          <ul class="auth-benefits">
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Reserve your preferred visit date in minutes
            </li>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Track booking status from your dashboard
            </li>
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Receive updates via email and mobile
            </li>
          </ul>
        </div>

        <p style="font-size: 0.78rem; color: rgba(255,255,255,0.5);">&copy; {{ date('Y') }} Municipality of Aborlan</p>
      </div>
    </aside>

    <main class="auth-main">
      <div class="auth-main-top">
        <a href="{{ url('/') }}" class="auth-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          Back to home
        </a>
        <div class="auth-mobile-brand">
          <img src="{{ asset('images/Logo.png') }}" alt="" decoding="async" />
          <span>{{ config('app.name') }}</span>
        </div>
      </div>

      <div class="auth-main-center">
        <div class="auth-main-panel">
          @if (session('status'))
            <div class="alert alert-success" role="status">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              {{ session('status') }}
            </div>
          @endif

          @yield('content')
        </div>
      </div>

      <footer class="auth-footer">
        &copy; {{ date('Y') }} Municipality of Aborlan · Atup-atup Falls Permit Booking System
      </footer>
    </main>
  </div>

  <script src="{{ asset('js/auth-forms.js') }}" defer></script>
  @stack('scripts')
</body>
</html>

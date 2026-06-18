<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $pageTitle }} · {{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --navy: #2a0a32;
      --teal: #c026d3;
      --teal-hover: #a21caf;
      --border: #f5d0f3;
      --muted: #6b4a6e;
      --radius-sm: 8px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { overflow-x: clip; -webkit-text-size-adjust: 100%; }
    body {
      font-family: "Source Sans 3", system-ui, sans-serif;
      color: var(--navy);
      background: #fdf4ff;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      overflow-x: clip;
    }
    .bar {
      background: #fff;
      border-bottom: 1px solid var(--border);
      padding: 0.75rem 1rem;
      padding-top: max(0.75rem, env(safe-area-inset-top, 0px));
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      justify-content: space-between;
      gap: 0.75rem;
      flex-shrink: 0;
    }
    .bar a {
      color: var(--teal-hover);
      font-weight: 600;
      text-decoration: none;
      min-height: 44px;
      display: inline-flex;
      align-items: center;
    }
    .bar a:hover { color: var(--navy); text-decoration: underline; }
    .bar h1 {
      font-size: clamp(0.9rem, 2.5vw, 1rem);
      font-weight: 700;
      max-width: min(100%, 520px);
      line-height: 1.35;
      flex: 1 1 100%;
    }
    .bar-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      align-items: center;
      width: 100%;
    }
    .bar-actions a {
      flex: 1 1 auto;
      justify-content: center;
      padding: 0.55rem 0.85rem;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      background: #fff;
      font-size: 0.875rem;
    }
    .bar-actions a.primary {
      background: var(--teal);
      color: #fff;
      border-color: var(--teal);
    }
    .bar-actions a.primary:hover {
      background: var(--teal-hover);
      color: #fff;
      text-decoration: none;
    }
    .frame-wrap {
      flex: 1;
      padding: 0.5rem;
      padding-bottom: max(0.5rem, env(safe-area-inset-bottom, 0px));
      min-height: 0;
      display: flex;
      flex-direction: column;
    }
    .frame-inner {
      flex: 1;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      min-height: 320px;
      background: #4b5563;
      border: 1px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(42, 10, 50, 0.08);
    }
    iframe,
    embed {
      width: 100%;
      height: 100%;
      min-height: calc(100dvh - 140px);
      border: 0;
      display: block;
    }
    .fallback {
      padding: 1.5rem;
      text-align: center;
      color: #fff;
      font-size: 0.95rem;
    }
    .fallback a { color: #f0abfc; font-weight: 600; }
    @media (min-width: 640px) {
      .bar { padding: 0.75rem 1.25rem; align-items: center; }
      .bar h1 { flex: 1 1 auto; font-size: 1rem; }
      .bar-actions { width: auto; flex-wrap: nowrap; gap: 0.75rem 1rem; }
      .bar-actions a {
        flex: none;
        border: none;
        background: transparent;
        padding: 0;
        min-height: auto;
      }
      .bar-actions a.primary {
        background: transparent;
        color: var(--teal-hover);
        border: none;
      }
      .frame-wrap { padding: 0.75rem; }
      iframe, embed { min-height: calc(100vh - 88px); }
    }
  </style>
</head>
<body>
  <div class="bar">
    <h1>{{ $pageTitle }}</h1>
    <div class="bar-actions">
      <a href="{{ url('/') }}">← Back to portal</a>
      <a class="primary" href="{{ $documentUrl }}" target="_blank" rel="noopener noreferrer">Open PDF in new tab</a>
    </div>
  </div>
  <div class="frame-wrap">
    <div class="frame-inner">
      <iframe
        title="PDF: {{ $pageTitle }}"
        src="{{ $documentUrl }}#view=FitH"
      ></iframe>
      <noscript>
        <div class="fallback">
          <p>Enable JavaScript for the embedded viewer, or <a href="{{ $documentUrl }}">open the PDF</a>.</p>
        </div>
      </noscript>
    </div>
  </div>
</body>
</html>

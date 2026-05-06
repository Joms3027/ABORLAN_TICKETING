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
      --navy: #0c1929;
      --teal: #0f5c55;
      --border: #e2e8f0;
      --muted: #64748b;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "Source Sans 3", system-ui, sans-serif;
      color: var(--navy);
      background: #f8fafc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .bar {
      background: #fff;
      border-bottom: 1px solid var(--border);
      padding: 0.75rem 1.25rem;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      flex-shrink: 0;
    }
    .bar a {
      color: var(--teal);
      font-weight: 600;
      text-decoration: none;
    }
    .bar a:hover { text-decoration: underline; }
    .bar h1 {
      font-size: 1rem;
      font-weight: 700;
      max-width: min(100%, 520px);
      line-height: 1.35;
    }
    .bar-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem 1rem;
      align-items: center;
      font-size: 0.875rem;
    }
    .bar-actions a.secondary {
      color: var(--muted);
      font-weight: 600;
    }
    .frame-wrap {
      flex: 1;
      padding: 0.75rem;
      min-height: 0;
      display: flex;
      flex-direction: column;
    }
    .frame-inner {
      flex: 1;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      min-height: 480px;
      background: #4b5563;
      border: 1px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(12, 25, 41, 0.06);
    }
    iframe,
    embed {
      width: 100%;
      height: 100%;
      min-height: calc(100vh - 88px);
      border: 0;
      display: block;
    }
    .fallback {
      padding: 1.5rem;
      text-align: center;
      color: #fff;
      font-size: 0.95rem;
    }
    .fallback a { color: #a7f3d0; font-weight: 600; }
  </style>
</head>
<body>
  <div class="bar">
    <h1>{{ $pageTitle }}</h1>
    <div class="bar-actions">
      <a href="{{ url('/') }}">← Back to portal</a>
      <a class="secondary" href="{{ $documentUrl }}" target="_blank" rel="noopener noreferrer">Open PDF in new tab</a>
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $siteName }} · Opening Ceremony</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any" />
  <link rel="preload" href="{{ $standbyMusicUrl }}" as="audio" type="audio/mpeg" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&family=Source+Sans+3:wght@400;600;700&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --magenta:   #c026d3;
      --magenta-bright: #e879f9;
      --purple:    #701a75;
      --navy:      #2a0a32;
      --indigo:    #1e1b4b;
      --gold:      #ffea00;
      --gold-dim:  #ca8a04;
      --red:       #ff3b3b;
      --cyan:      #f0abfc;
      --bg:        #1a0520;
      --panel:     #2a0a32;
      --border:    #701a75;
    }

    body {
      background: linear-gradient(165deg, #4c0519 0%, #2a0a32 40%, #1e1b4b 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-family: 'Orbitron', monospace;
      color: #fce7f3;
      overflow-x: hidden;
      overflow-y: auto;
      padding: 24px 0;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(192,38,211,.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(192,38,211,.1) 1px, transparent 1px);
      background-size: 40px 40px;
      animation: gridShift 20s linear infinite;
      pointer-events: none;
    }
    @keyframes gridShift { to { background-position: 0 40px; } }

    .particles { position: fixed; inset: 0; pointer-events: none; overflow: hidden; }
    .particle {
      position: absolute;
      width: 2px; height: 2px;
      background: var(--magenta-bright);
      border-radius: 50%;
      animation: float linear infinite;
      opacity: 0;
    }
    @keyframes float {
      0%   { transform: translateY(100vh) translateX(0); opacity: 0; }
      10%  { opacity: .6; }
      90%  { opacity: .6; }
      100% { transform: translateY(-10vh) translateX(40px); opacity: 0; }
    }

    .panel {
      position: relative;
      z-index: 2;
      background: rgba(42, 10, 50, 0.92);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 48px 56px;
      width: min(1200px, 95vw);
      box-shadow:
        0 0 60px rgba(192,38,211,.12),
        0 0 0 1px rgba(232,121,249,.08) inset,
        inset 0 -3px 0 var(--gold);
      display: flex;
      flex-direction: row;
      align-items: stretch;
      justify-content: space-between;
      gap: 0;
      flex-wrap: wrap;
    }

    .panel-left {
      flex: 1;
      min-width: 260px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      padding-right: 40px;
      border-right: 1px solid rgba(192,38,211,.25);
    }

    .panel-right {
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      padding-left: 40px;
    }

    @media (max-width: 760px) {
      .panel { flex-direction: column; gap: 28px; padding: 32px 24px; }
      .panel-left {
        align-items: center;
        text-align: center;
        padding-right: 0;
        padding-bottom: 28px;
        border-right: none;
        border-bottom: 1px solid rgba(192,38,211,.25);
      }
      .panel-right { padding-left: 0; }
      .header { text-align: center; }
      .header-logo-img { margin-left: auto; margin-right: auto; }
    }

    .panel::before, .panel::after,
    .corner-bl, .corner-br {
      content: '';
      position: absolute;
      width: 18px; height: 18px;
      border-color: var(--magenta-bright);
      border-style: solid;
      opacity: .55;
    }
    .panel::before { top: 10px; left: 10px;  border-width: 2px 0 0 2px; }
    .panel::after  { top: 10px; right: 10px; border-width: 2px 2px 0 0; }
    .corner-bl     { bottom: 10px; left: 10px;  border-width: 0 0 2px 2px; }
    .corner-br     { bottom: 10px; right: 10px; border-width: 0 2px 2px 0; }

    .header { text-align: left; }
    .header-logo-img {
      width: 96px;
      height: auto;
      margin-bottom: 18px;
      display: block;
      filter: drop-shadow(0 0 12px rgba(255,234,0,.35));
    }
    .header-logo {
      font-size: 10px;
      letter-spacing: 5px;
      color: var(--magenta-bright);
      opacity: .8;
      text-transform: uppercase;
      margin-bottom: 8px;
    }
    .header h1 {
      font-size: clamp(17px, 3.8vw, 24px);
      font-weight: 900;
      letter-spacing: 2px;
      color: var(--gold);
      text-shadow: 0 0 20px rgba(192,38,211,.6);
      line-height: 1.35;
    }
    .header-sub {
      font-family: 'Source Sans 3', sans-serif;
      font-size: 15px;
      color: rgba(255,255,255,.85);
      letter-spacing: 1px;
      margin-top: 10px;
      line-height: 1.5;
    }
    .header-ceremony {
      margin-top: 22px;
      padding-top: 18px;
      border-top: 1px solid rgba(192,38,211,.3);
    }
    .header-ceremony-label {
      font-family: 'Share Tech Mono', monospace;
      font-size: 11px;
      letter-spacing: 4px;
      color: rgba(255,255,255,.75);
      margin-bottom: 6px;
    }
    .header-ceremony-name {
      font-size: clamp(14px, 3vw, 17px);
      font-weight: 700;
      letter-spacing: 2px;
      color: var(--gold);
      text-shadow: 0 0 12px rgba(192,38,211,.5);
    }
    .header-ceremony-title {
      font-family: 'Share Tech Mono', monospace;
      font-size: 12px;
      letter-spacing: 2px;
      color: var(--magenta-bright);
      margin-top: 4px;
    }

    .status-bar {
      width: 100%;
      max-width: 280px;
      background: rgba(0,0,0,.4);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 10px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Share Tech Mono', monospace;
      font-size: 12px;
      letter-spacing: 2px;
      transition: all .5s ease;
    }
    .status-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      flex-shrink: 0;
      background: var(--red);
      box-shadow: 0 0 8px var(--red);
      animation: blink 1.4s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{ opacity:1 } 50%{ opacity:.2 } }
    .status-text { color: #d8a8e0; }
    .status-text span { color: var(--red); transition: color .4s; }

    .status-bar.standby .status-dot { background: var(--gold-dim); box-shadow: 0 0 8px var(--gold-dim); }
    .status-bar.standby .status-text span { color: var(--gold); }
    .status-bar.scanning .status-dot { background: var(--magenta-bright); box-shadow: 0 0 8px var(--magenta-bright); animation: none; }
    .status-bar.scanning .status-text span { color: var(--magenta-bright); }

    .scanner-wrap {
      position: relative;
      width: 220px; height: 220px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .scanner-wrap.locked-overlay::after {
      content: 'AWAITING OPENING';
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-family: 'Share Tech Mono', monospace;
      font-size: 10px;
      letter-spacing: 2px;
      color: var(--gold);
      background: rgba(26,5,32,.72);
      border-radius: 50%;
      z-index: 5;
      pointer-events: none;
      padding: 20px;
    }

    .ring {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 2px solid transparent;
      transition: border-color .4s;
    }
    .ring-1 { border-color: rgba(192,38,211,.25); animation: spin 8s linear infinite; }
    .ring-2 { inset: 10px; border-color: rgba(192,38,211,.12); animation: spin 14s linear infinite reverse; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .scanning .ring-1 {
      border-color: rgba(232,121,249,.5);
      animation: ringPulse 2s ease-in-out infinite, spin 12s linear infinite;
    }
    .scanning .ring-2 {
      border-color: rgba(232,121,249,.3);
      animation: ringPulse 2s ease-in-out .5s infinite, spin 10s linear infinite reverse;
    }
    @keyframes ringPulse {
      0%, 100% { opacity: 0.8; filter: drop-shadow(0 0 4px rgba(232,121,249,.4)); }
      50%      { opacity: 1;   filter: drop-shadow(0 0 12px rgba(232,121,249,.7)); }
    }

    .scanner-btn {
      position: relative;
      z-index: 2;
      width: 160px; height: 160px;
      border-radius: 50%;
      background: radial-gradient(circle at 40% 35%, #3b0d45 0%, #1a0520 70%);
      border: 2px solid var(--border);
      cursor: not-allowed;
      display: flex;
      align-items: center;
      justify-content: center;
      outline: none;
      transition: border-color .4s, box-shadow .4s, opacity .4s;
      box-shadow: 0 0 30px rgba(0,0,0,.8), 0 0 0 1px rgba(192,38,211,.1) inset;
      user-select: none;
      -webkit-tap-highlight-color: transparent;
      touch-action: none;
      overflow: visible;
    }
    .scanner-btn.ready-hold {
      cursor: grab;
    }
    .scanner-btn:hover.ready-hold {
      border-color: var(--magenta);
      box-shadow: 0 0 30px rgba(192,38,211,.3), 0 0 0 1px rgba(232,121,249,.15) inset;
    }
    .scanner-btn.ready-hold:active { transform: scale(.97); }
    .scanner-btn.awaiting { cursor: not-allowed; opacity: .45; }
    .scanner-btn.holding,
    body.holding .scanner-btn.holding { cursor: grabbing; }
    body.holding { cursor: grabbing; user-select: none; }

    .scanner-palm {
      position: relative;
      z-index: 3;
      width: 58px;
      height: 58px;
      fill: rgba(240, 171, 252, 0.9);
      filter: drop-shadow(0 0 10px rgba(192, 38, 211, 0.45));
      pointer-events: none;
      transition: opacity 0.25s ease, transform 0.25s ease;
    }
    .scanner-btn.ready-hold .scanner-palm {
      animation: palmPulse 2s ease-in-out infinite;
    }
    .scanner-btn.holding .scanner-palm,
    .scanner-btn.scanning-state .scanner-palm {
      opacity: 0;
      transform: scale(0.85);
    }
    @keyframes palmPulse {
      0%, 100% { transform: scale(1); opacity: 0.85; }
      50% { transform: scale(1.06); opacity: 1; }
    }

    .scan-line {
      position: absolute;
      left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--magenta), var(--magenta-bright), transparent);
      top: 50%;
      border-radius: 2px;
      opacity: 0;
      pointer-events: none;
      box-shadow: 0 0 10px var(--magenta), 0 0 20px var(--magenta-bright);
    }
    .scanning-state .scan-line {
      opacity: 1;
      z-index: 1;
      animation: scanMove 1.2s ease-in-out infinite alternate;
    }
    @keyframes scanMove {
      0%   { top: 15%; }
      100% { top: 85%; }
    }

    .scan-pulse {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 2px solid rgba(232,121,249,.4);
      pointer-events: none;
      opacity: 0;
    }
    .scanning-state .scan-pulse { animation: scanPulse 1.5s ease-out infinite; }
    .scan-pulse:nth-child(2) { animation-delay: .25s; }
    .scan-pulse:nth-child(3) { animation-delay: .5s; }
    .scan-pulse:nth-child(4) { animation-delay: .75s; }
    @keyframes scanPulse {
      0%   { transform: scale(0.6); opacity: 0.8; }
      100% { transform: scale(1.15); opacity: 0; }
    }

    .scanning-state .scanner-btn {
      animation: scanGlow 1.8s ease-in-out infinite;
      border-color: rgba(232,121,249,.6);
    }
    @keyframes scanGlow {
      0%, 100% { box-shadow: 0 0 30px rgba(0,0,0,.8), 0 0 25px rgba(192,38,211,.3); }
      50%      { box-shadow: 0 0 30px rgba(0,0,0,.8), 0 0 45px rgba(192,38,211,.5), 0 0 60px rgba(232,121,249,.2); }
    }

    .scanner-btn.flash { animation: flashMagenta .5s ease-out; }
    @keyframes flashMagenta {
      0%   { box-shadow: 0 0 0px rgba(232,121,249,0); }
      40%  { box-shadow: 0 0 60px rgba(232,121,249,.7); }
      100% { box-shadow: 0 0 30px rgba(192,38,211,.25); }
    }

    .progress-wrap {
      width: 100%;
      max-width: 280px;
      height: 4px;
      background: rgba(255,255,255,.05);
      border-radius: 4px;
      overflow: hidden;
      opacity: 0;
      transition: opacity .3s;
    }
    .progress-wrap.visible { opacity: 1; }
    .progress-bar {
      height: 100%;
      width: 0%;
      border-radius: 4px;
      background: linear-gradient(90deg, var(--magenta), var(--magenta-bright), var(--gold));
      box-shadow: 0 0 8px var(--magenta);
      transition: width .1s linear;
    }

    .scan-hint {
      font-family: 'Share Tech Mono', monospace;
      font-size: 10px;
      letter-spacing: 2px;
      color: rgba(240,171,252,.55);
      text-align: center;
      max-width: 280px;
      min-height: 14px;
    }

    .footer {
      font-family: 'Share Tech Mono', monospace;
      font-size: 10px;
      letter-spacing: 2px;
      color: rgba(192,38,211,.45);
      text-align: center;
      width: 100%;
      flex-basis: 100%;
      margin-top: 8px;
    }

    .panel.hidden { display: none; }

    .open-screen {
      position: relative;
      z-index: 10;
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 24px;
      padding: 24px;
      animation: fadeIn .5s ease-out forwards;
      max-width: 900px;
      text-align: center;
    }
    .open-screen.visible { display: flex; }
    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }
    .open-screen-welcome {
      font-family: 'Share Tech Mono', monospace;
      font-size: clamp(16px, 4vw, 22px);
      letter-spacing: 3px;
      color: white;
    }
    .open-screen-title {
      font-size: clamp(18px, 4.5vw, 30px);
      font-weight: 900;
      letter-spacing: 4px;
      color: var(--gold);
      text-shadow: 0 0 30px rgba(192,38,211,.6), 0 0 60px rgba(255,234,0,.25);
      line-height: 1.4;
    }
    .open-screen-sub {
      font-family: 'Source Sans 3', sans-serif;
      font-size: 15px;
      color: rgba(255,255,255,.8);
      letter-spacing: 1px;
      max-width: 560px;
    }
    .open-screen-enter {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: linear-gradient(135deg, rgba(192,38,211,.25), rgba(112,26,117,.2));
      border: 1px solid rgba(255,234,0,.55);
      color: var(--gold);
      font-family: 'Orbitron', monospace;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 4px;
      padding: 14px 28px;
      border-radius: 8px;
      text-decoration: none;
      cursor: pointer;
      transition: all .3s;
      box-shadow: 0 0 20px rgba(192,38,211,.25);
    }
    .open-screen-enter:hover {
      background: linear-gradient(135deg, rgba(192,38,211,.4), rgba(112,26,117,.3));
      border-color: var(--gold);
      box-shadow: 0 0 30px rgba(255,234,0,.3);
      transform: translateY(-2px);
    }
    .open-screen-reset {
      margin-top: 8px;
      background: transparent;
      border: 1px solid rgba(255,59,59,.3);
      color: rgba(255,59,59,.7);
      font-family: 'Orbitron', monospace;
      font-size: 10px;
      letter-spacing: 3px;
      padding: 10px 24px;
      border-radius: 6px;
      cursor: pointer;
      transition: all .3s;
    }
    .open-screen-reset:hover {
      background: rgba(255,59,59,.08);
      border-color: var(--red);
      color: var(--red);
    }

    .open-screen.visible .open-screen-welcome { animation: welcomeIn .6s cubic-bezier(.34,1.56,.64,1) .1s both; }
    .open-screen.visible .open-screen-title { animation: titleIn .7s cubic-bezier(.34,1.56,.64,1) .25s both, titleGlow 2.5s ease-in-out .9s infinite; }
    .open-screen.visible .open-screen-enter { animation: btnIn .6s cubic-bezier(.34,1.56,.64,1) .5s both; }
    @keyframes welcomeIn {
      from { opacity: 0; transform: translateY(-20px) scale(0.9); }
      to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes titleIn {
      from { opacity: 0; transform: scale(0.5); }
      to   { opacity: 1; transform: scale(1); }
    }
    @keyframes titleGlow {
      0%, 100% { text-shadow: 0 0 30px rgba(192,38,211,.6), 0 0 60px rgba(255,234,0,.25); }
      50%      { text-shadow: 0 0 50px rgba(232,121,249,.7), 0 0 90px rgba(255,234,0,.4); }
    }
    @keyframes btnIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    #fireworksCanvas {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 5;
      opacity: 0;
      transition: opacity .3s;
    }
    #fireworksCanvas.active { opacity: 1; }

    .audio-gate {
      position: fixed;
      inset: 0;
      z-index: 30;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(26, 5, 32, 0.82);
      backdrop-filter: blur(4px);
      cursor: pointer;
      transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    .audio-gate.hidden {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }
    .audio-gate-inner {
      text-align: center;
      padding: 32px 40px;
      border: 1px solid rgba(255, 234, 0, 0.5);
      border-radius: 16px;
      background: rgba(42, 10, 50, 0.95);
      box-shadow: 0 0 40px rgba(192, 38, 211, 0.3);
      animation: gatePulse 2.2s ease-in-out infinite;
      max-width: 90vw;
    }
    @keyframes gatePulse {
      0%, 100% { box-shadow: 0 0 40px rgba(192, 38, 211, 0.3); transform: scale(1); }
      50% { box-shadow: 0 0 60px rgba(255, 234, 0, 0.25); transform: scale(1.02); }
    }
    .audio-gate-icon {
      display: block;
      font-size: 42px;
      margin-bottom: 16px;
      color: var(--gold);
    }
    .audio-gate-title {
      font-size: clamp(14px, 3.5vw, 18px);
      font-weight: 700;
      letter-spacing: 3px;
      color: var(--gold);
      margin-bottom: 10px;
    }
    .audio-gate-sub {
      font-family: 'Share Tech Mono', monospace;
      font-size: 11px;
      letter-spacing: 2px;
      color: rgba(255, 255, 255, 0.7);
    }

    .audio-unlock {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 20;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      background: rgba(42, 10, 50, 0.95);
      border: 1px solid rgba(255, 234, 0, 0.45);
      border-radius: 999px;
      font-family: 'Share Tech Mono', monospace;
      font-size: 11px;
      letter-spacing: 2px;
      color: var(--gold);
      cursor: pointer;
      box-shadow: 0 0 24px rgba(192, 38, 211, 0.25);
      transition: opacity 0.35s ease, transform 0.35s ease;
    }
    .audio-unlock:hover {
      border-color: var(--gold);
      box-shadow: 0 0 30px rgba(255, 234, 0, 0.2);
    }
    .audio-unlock.hidden {
      opacity: 0;
      pointer-events: none;
      transform: translateX(-50%) translateY(12px);
    }
    .audio-unlock-icon { font-size: 16px; line-height: 1; }

    .audio-toggle {
      position: fixed;
      top: 16px;
      right: 16px;
      z-index: 20;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 1px solid rgba(192, 38, 211, 0.45);
      background: rgba(42, 10, 50, 0.9);
      color: var(--magenta-bright);
      font-size: 18px;
      line-height: 1;
      cursor: pointer;
      display: none;
      align-items: center;
      justify-content: center;
      transition: border-color 0.25s, background 0.25s;
    }
    .audio-toggle.visible { display: flex; }
    .audio-toggle:hover { border-color: var(--gold); }
    .audio-toggle.muted { color: rgba(255, 255, 255, 0.35); }

    @media (prefers-reduced-motion: reduce) {
      .audio-unlock,
      .audio-gate { display: none !important; }
      .audio-gate-inner { animation: none; }
    }
  </style>
</head>
<body>

<div class="particles" id="particles"></div>
<canvas id="fireworksCanvas"></canvas>

<div class="audio-gate" id="audioGate" role="button" tabindex="0" aria-label="Tap to start ceremony music">
  <div class="audio-gate-inner">
    <span class="audio-gate-icon" aria-hidden="true">&#9835;</span>
    <div class="audio-gate-title">CEREMONY MUSIC</div>
    <div class="audio-gate-sub">TAP ANYWHERE TO START STANDBY MUSIC</div>
  </div>
</div>

<button class="audio-unlock hidden" id="audioUnlock" type="button" aria-label="Enable ceremony audio">
  <span class="audio-unlock-icon" aria-hidden="true">&#9835;</span>
  TAP TO ENABLE CEREMONY AUDIO
</button>
<button class="audio-toggle" id="audioToggle" type="button" aria-label="Mute ceremony audio" title="Toggle sound">&#128266;</button>

<div class="panel" id="mainPanel">
  <div class="corner-bl"></div>
  <div class="corner-br"></div>

  <div class="panel-left">
    <div class="header">
      <img src="{{ $logoUrl }}" alt="Municipality of Aborlan" class="header-logo-img" />
      <div class="header-logo">Municipality of Aborlan · Palawan</div>
      <h1>GRAND OPENING OF THE ATUP-ATUP FALLS BOOKING PORTAL</h1>
      <div class="header-sub">{{ $siteName }} — Official visitor permit and LGU information channel</div>
      <div class="header-ceremony">
        <div class="header-ceremony-label">OPENING CEREMONY LED BY</div>
        <div class="header-ceremony-name">HON. MAYOR &amp; LGU OF ABORLAN</div>
        <div class="header-ceremony-title">LOCAL GOVERNMENT UNIT</div>
      </div>
    </div>
  </div>

  <div class="panel-right">
    <div class="status-bar standby" id="statusBar">
      <div class="status-dot"></div>
      <div class="status-text">SYSTEM STATUS: <span id="statusLabel">STANDBY</span></div>
    </div>

    <div class="scanner-wrap locked-overlay" id="scannerWrap">
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>
      <button class="scanner-btn awaiting" id="scannerBtn" type="button" disabled title="Press and hold your palm — available at opening time">
        <svg class="scanner-palm" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M13 3c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm-2 3c-1.1 0-2 .9-2 2v5H7c-1.1 0-2 .9-2 2v1c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-4c0-1.1-.9-2-2-2h-1V8c0-1.1-.9-2-2-2zm0 2h2v5h2v4c0 1.1-.9 2-2 2H9c-1.1 0-2-.9-2-2v-1h2V8z"/>
        </svg>
        <div class="scan-pulse"></div>
        <div class="scan-pulse"></div>
        <div class="scan-pulse"></div>
        <div class="scan-pulse"></div>
        <div class="scan-line"></div>
      </button>
    </div>

    <div class="progress-wrap" id="progressWrap">
      <div class="progress-bar" id="progressBar"></div>
    </div>

    <div class="scan-hint" id="scanHint">Press and hold the palm on the scanner when unlocked — do not release until the scan finishes.</div>
  </div>

  <div class="footer">BIOMETRIC AUTH &nbsp;|&nbsp; AES-256 ENCRYPTED &nbsp;|&nbsp; LGU SECURE GATEWAY</div>
</div>

<div class="open-screen" id="openScreen">
  <div class="open-screen-welcome">WELCOME TO ABORLAN</div>
  <div class="open-screen-title">THE ATUP-ATUP FALLS BOOKING PORTAL IS NOW OFFICIALLY OPEN</div>
  <div class="open-screen-sub">Plan your visit, apply for permits, and explore Nag Atup with the Municipality of Aborlan.</div>
  <a class="open-screen-enter" href="{{ $homeUrl }}" id="enterBtn">ENTER PORTAL &#8594;</a>
  <button class="open-screen-reset" id="openScreenReset" type="button">RESET CEREMONY</button>
</div>

<script src="{{ asset('js/opening-ceremony-audio.js') }}"></script>
<script>
  OpeningCeremonyAudio.configure({
    musicUrl: @json($standbyMusicUrl),
  });

  const OPENS_AT = new Date(@json($opensAtIso));
  const COMPLETE_URL = @json(route('opening.complete'));
  const CSRF_TOKEN = @json(csrf_token());

  /* ── Particles ─────────────────────────────────────────────── */
  const pContainer = document.getElementById('particles');
  for (let i = 0; i < 28; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `
      left: ${Math.random() * 100}%;
      animation-duration: ${6 + Math.random() * 12}s;
      animation-delay: ${Math.random() * 10}s;
      width: ${1 + Math.random() * 2}px;
      height: ${1 + Math.random() * 2}px;
    `;
    pContainer.appendChild(p);
  }

  const panel       = document.getElementById('mainPanel');
  const openScreen  = document.getElementById('openScreen');
  const scannerBtn  = document.getElementById('scannerBtn');
  const scannerWrap = document.getElementById('scannerWrap');
  const statusBar   = document.getElementById('statusBar');
  const statusLabel = document.getElementById('statusLabel');
  const progressWrap= document.getElementById('progressWrap');
  const progressBar = document.getElementById('progressBar');
  const scanHint    = document.getElementById('scanHint');

  let state = 'standby';
  let openingUnlocked = false;
  let progress = 0;
  let scanInterval = null;
  let activePointerId = null;
  let completingScan = false;

  const audioGate = document.getElementById('audioGate');
  const audioUnlockBtn = document.getElementById('audioUnlock');
  const audioToggleBtn = document.getElementById('audioToggle');
  const audio = window.OpeningCeremonyAudio;
  const audioReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let audioReady = false;

  function hideAudioGate() {
    audioGate.classList.add('hidden');
    audioUnlockBtn.classList.add('hidden');
  }

  async function enableCeremonyAudio() {
    if (audioReducedMotion) return;
    if (audioReady) {
      if (!audio.isMuted() && !audio.isBackgroundPlaying()) {
        audio.startBackground();
      }
      return;
    }
    try {
      await audio.unlock();
      audioReady = true;
      audio.startBackground();
      hideAudioGate();
      audioToggleBtn.classList.add('visible');
    } catch (_) {}
  }

  function bindAudioUnlock(el) {
    el.addEventListener('click', enableCeremonyAudio);
    el.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        enableCeremonyAudio();
      }
    });
  }

  bindAudioUnlock(audioGate);
  bindAudioUnlock(audioUnlockBtn);

  ['pointerdown', 'touchstart', 'keydown'].forEach((evt) => {
    document.addEventListener(evt, () => enableCeremonyAudio(), { once: true, passive: evt !== 'keydown' });
  });

  enableCeremonyAudio();

  audioToggleBtn.addEventListener('click', () => {
    if (!audioReady) return;
    const nextMuted = !audio.isMuted();
    audio.setMuted(nextMuted);
    audioToggleBtn.classList.toggle('muted', nextMuted);
    audioToggleBtn.textContent = nextMuted ? '\u{1F507}' : '\u{1F50A}';
    audioToggleBtn.setAttribute('aria-label', nextMuted ? 'Unmute ceremony audio' : 'Mute ceremony audio');
    if (!nextMuted) audio.startBackground();
  });

  setInterval(() => {
    if (!audioReady || audio.isMuted()) return;
    if (!audio.isBackgroundPlaying()) audio.startBackground();
  }, 4000);

  function checkOpeningTime() {
    if (openingUnlocked || OPENS_AT - Date.now() > 0) {
      return;
    }

    openingUnlocked = true;
    enableScanner();
  }

  function enableScanner() {
    state = 'locked';
    scannerBtn.disabled = false;
    scannerBtn.classList.remove('awaiting');
    scannerBtn.classList.add('ready-hold');
    scannerWrap.classList.remove('locked-overlay');
    statusBar.classList.remove('standby');
    statusLabel.textContent = 'LOCKED';
    scanHint.textContent = 'Press and hold the palm on the scanner — do not release until complete.';
    scannerBtn.title = 'Press and hold — releasing resets the scan';
  }

  checkOpeningTime();
  setInterval(checkOpeningTime, 1000);

  function onPointerDown(e) {
    if (state !== 'locked') return;
    if (e.pointerType === 'mouse' && e.button !== 0) return;

    e.preventDefault();
    activePointerId = e.pointerId;

    try {
      scannerBtn.setPointerCapture(e.pointerId);
    } catch (_) {}

    startScan();
  }

  function onPointerEnd(e) {
    if (completingScan || state !== 'scanning') return;
    if (activePointerId !== null && e.pointerId !== activePointerId) return;

    activePointerId = null;

    try {
      scannerBtn.releasePointerCapture(e.pointerId);
    } catch (_) {}

    cancelScan(true);
  }

  scannerBtn.addEventListener('pointerdown', onPointerDown);
  scannerBtn.addEventListener('pointerup', onPointerEnd);
  scannerBtn.addEventListener('pointercancel', onPointerEnd);
  scannerBtn.addEventListener('lostpointercapture', (e) => {
    if (completingScan || state !== 'scanning') return;
    if (e.pointerId !== activePointerId) return;

    activePointerId = null;
    cancelScan(true);
  });
  scannerBtn.addEventListener('contextmenu', (e) => e.preventDefault());
  scannerBtn.addEventListener('dragstart', (e) => e.preventDefault());

  document.addEventListener('visibilitychange', () => {
    if (document.hidden && state === 'scanning') {
      cancelScan(true);
    }
  });

  function startScan() {
    state = 'scanning';
    scannerBtn.classList.remove('ready-hold');
    scannerBtn.classList.add('holding');
    document.body.classList.add('holding');
    progress = 0;

    progressWrap.classList.add('visible');
    statusBar.classList.add('scanning');
    statusLabel.textContent = 'SCANNING';
    scannerWrap.classList.add('scanning');
    scannerBtn.classList.add('scanning-state');
    scanHint.textContent = 'Keep holding — releasing will reset the scan to the beginning.';

    const tickMs = 80;
    const totalTicks = 9000 / tickMs;
    const progressPerTick = 100 / totalTicks;
    scanInterval = setInterval(() => {
      progress += progressPerTick * (0.9 + Math.random() * 0.2);
      if (progress > 100) progress = 100;
      progressBar.style.width = progress + '%';

      if (progress >= 100) {
        clearInterval(scanInterval);
        scanInterval = null;
        completingScan = true;
        setTimeout(completeScan, 400);
      }
    }, tickMs);
  }

  function cancelScan(releasedEarly = false) {
    if (scanInterval) clearInterval(scanInterval);
    scanInterval = null;
    completingScan = false;
    activePointerId = null;
    state = 'locked';
    progress = 0;

    progressBar.style.width = '0%';
    progressWrap.classList.remove('visible');
    statusBar.classList.remove('scanning');
    statusLabel.textContent = 'LOCKED';
    scannerWrap.classList.remove('scanning');
    scannerBtn.classList.remove('scanning-state', 'holding');
    scannerBtn.classList.add('ready-hold');
    document.body.classList.remove('holding');
    scanHint.textContent = releasedEarly
      ? 'Scan reset — press and hold the palm again without releasing until complete.'
      : 'Press and hold the palm on the scanner — do not release until complete.';
  }

  async function completeScan() {
    state = 'open';
    activePointerId = null;
    scannerBtn.classList.remove('holding', 'scanning-state', 'ready-hold');
    document.body.classList.remove('holding');
    scannerWrap.classList.remove('scanning');
    scannerBtn.classList.add('flash');

    try {
      const res = await fetch(COMPLETE_URL, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json',
        },
        credentials: 'same-origin',
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || 'Could not complete ceremony.');
      }
    } catch (err) {
      completingScan = false;
      alert(err.message || 'Ceremony could not be completed. Please try again.');
      cancelScan(false);
      scannerBtn.classList.remove('flash');
      return;
    }

    setTimeout(() => {
      scannerBtn.classList.remove('flash');
      panel.classList.add('hidden');
      openScreen.classList.add('visible');
      launchFireworks();
    }, 500);
  }

  document.getElementById('openScreenReset').addEventListener('click', () => {
    document.cookie = '{{ config('opening.cookie_name') }}=; Max-Age=0; path=/';
    location.reload();
  });

  const FW_COLORS = ['#FFEA00', '#C026D3', '#E879F9', '#FF6600', '#701A75', '#F0ABFC', '#CA8A04', '#FFFFFF'];
  let fireworksRAF = null;
  let fireworksActive = false;
  let fireworksResizeHandler = null;

  function launchFireworks() {
    const canvas = document.getElementById('fireworksCanvas');
    fireworksResizeHandler = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
    fireworksResizeHandler();
    window.addEventListener('resize', fireworksResizeHandler);
    canvas.classList.add('active');

    const ctx = canvas.getContext('2d');
    const rockets = [];
    const particles = [];

    class Rocket {
      constructor() {
        this.x = Math.random() * canvas.width;
        this.y = canvas.height;
        this.vy = -(8 + Math.random() * 6);
        this.vx = (Math.random() - 0.5) * 2;
        this.color = FW_COLORS[Math.floor(Math.random() * FW_COLORS.length)];
        this.exploded = false;
      }
      update() {
        this.y += this.vy;
        this.x += this.vx;
        this.vy *= 0.98;
        if (this.vy > -2) this.explode();
      }
      explode() {
        this.exploded = true;
        const count = 40 + Math.floor(Math.random() * 30);
        for (let i = 0; i < count; i++) {
          const angle = (Math.PI * 2 * i) / count + Math.random();
          const speed = 2 + Math.random() * 6;
          particles.push({
            x: this.x, y: this.y,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            color: this.color,
            life: 1,
            decay: 0.008 + Math.random() * 0.01,
            size: 1.5 + Math.random() * 1.5
          });
        }
      }
      draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, 3, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
      }
    }

    function animate() {
      if (!fireworksActive) return;
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      if (rockets.length + particles.length < 80 && Math.random() < 0.12) {
        rockets.push(new Rocket());
      }

      for (let i = rockets.length - 1; i >= 0; i--) {
        rockets[i].update();
        if (rockets[i].exploded) rockets.splice(i, 1);
        else rockets[i].draw();
      }

      for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.x += p.vx;
        p.y += p.vy;
        p.vy += 0.08;
        p.vx *= 0.99;
        p.vy *= 0.99;
        p.life -= p.decay;
        if (p.life <= 0) { particles.splice(i, 1); continue; }
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fillStyle = p.color;
        ctx.globalAlpha = p.life;
        ctx.fill();
        ctx.globalAlpha = 1;
      }

      fireworksRAF = requestAnimationFrame(animate);
    }

    fireworksActive = true;
    rockets.push(new Rocket(), new Rocket(), new Rocket());
    animate();
  }
</script>
</body>
</html>

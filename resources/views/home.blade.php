<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ config('app.name') }} · Municipality of Aborlan</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet" />
  <link rel="preload" as="image" href="{{ $heroImageUrl }}" fetchpriority="high" />
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
      --radius: 12px;
      --radius-sm: 8px;
      --shadow-sm: 0 1px 2px rgba(88, 28, 135, 0.08);
      --shadow: 0 4px 24px rgba(190, 24, 93, 0.1);
      --shadow-lg: 0 20px 50px rgba(126, 34, 206, 0.14);
      --font: "Source Sans 3", system-ui, -apple-system, "Segoe UI", sans-serif;
      --ease: cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
    }

    @media (prefers-reduced-motion: reduce) {
      html {
        scroll-behavior: auto;
      }

      .card:hover .card-visual img,
      .carousel-track,
      header,
      .brand-logo,
      .back-to-top,
      .fab-book {
        transition: none !important;
      }
    }

    .skip-link {
      position: absolute;
      left: 1rem;
      top: -100%;
      z-index: 100;
      padding: 0.65rem 1rem;
      background: var(--gold-light);
      color: var(--navy);
      font-weight: 700;
      font-size: 0.875rem;
      text-decoration: none;
      border-radius: var(--radius-sm);
      box-shadow: var(--shadow);
      transition: top 0.2s var(--ease);
    }

    .skip-link:focus {
      top: 1rem;
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    /* Anchor targets clear sticky top bar + header */
    section[id],
    .cta-wrap[id] {
      scroll-margin-top: 7.5rem;
    }

    body {
      font-family: var(--font);
      color: var(--text);
      background: linear-gradient(165deg, var(--bg-subtle) 0%, #fff4e6 35%, #fdf4ff 70%, #fef9c3 100%);
      line-height: 1.65;
      font-size: 1.0625rem;
      -webkit-font-smoothing: antialiased;
    }

    .container {
      width: min(92%, 1140px);
      margin-inline: auto;
    }

    /* Top bar */
    .top-bar {
      background: linear-gradient(115deg, #4c0519 0%, #701a75 38%, #1e1b4b 100%);
      color: rgba(255, 255, 255, 0.92);
      font-size: 0.8125rem;
      font-weight: 500;
      letter-spacing: 0.01em;
      box-shadow: inset 0 -2px 0 var(--gold-light);
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .top-bar .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      gap: 0.65rem 1.25rem;
      padding: 0.55rem 0;
    }

    .top-bar-meta {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.5rem 0.85rem;
      flex: 1;
      min-width: 0;
    }

    .top-bar-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.28rem 0.65rem 0.28rem 0.45rem;
      border-radius: 999px;
      background: rgba(255, 234, 0, 0.12);
      border: 1px solid rgba(255, 234, 0, 0.35);
      color: #fff;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .top-bar-badge svg {
      flex-shrink: 0;
      color: var(--gold-light);
    }

    .top-bar-divider {
      width: 1px;
      height: 1.1rem;
      background: rgba(255, 255, 255, 0.22);
      flex-shrink: 0;
    }

    .top-bar-link {
      display: inline-flex;
      align-items: center;
      gap: 0.38rem;
      color: rgba(255, 255, 255, 0.88);
      text-decoration: none;
      border-radius: var(--radius-sm);
      padding: 0.15rem 0.35rem;
      margin: -0.15rem -0.35rem;
      transition: color 0.15s var(--ease), background 0.15s var(--ease);
    }

    .top-bar-link:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.08);
    }

    .top-bar-link:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 2px;
    }

    .top-bar-link svg {
      flex-shrink: 0;
      color: var(--magenta-bright);
    }

    .top-bar strong {
      color: var(--gold-light);
      font-weight: 700;
    }

    .top-bar-actions {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      flex-shrink: 0;
    }

    .top-bar-actions .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.35rem;
      font-size: 0.8125rem;
      font-weight: 600;
      padding: 0.42rem 0.9rem;
      border-radius: var(--radius-sm);
      white-space: nowrap;
      line-height: 1.2;
      transition: background 0.15s var(--ease), color 0.15s var(--ease), border-color 0.15s var(--ease), box-shadow 0.15s var(--ease), transform 0.12s var(--ease);
    }

    .top-bar-actions .btn svg {
      flex-shrink: 0;
    }

    .btn-top-signin {
      background: rgba(232, 121, 249, 0.12);
      color: #fff;
      border: 1px solid rgba(232, 121, 249, 0.55);
    }

    .btn-top-signin:hover {
      color: var(--navy);
      border-color: var(--gold-light);
      background: var(--gold-light);
      box-shadow: 0 0 20px rgba(255, 234, 0, 0.35);
    }

    .btn-top-book {
      background: var(--gold-light);
      color: var(--navy);
      border: none;
      box-shadow: 0 1px 6px rgba(255, 234, 0, 0.45), 0 0 0 1px rgba(192, 38, 211, 0.35);
    }

    .btn-top-book:hover {
      background: #fff48f;
      color: var(--navy);
      box-shadow: 0 2px 12px rgba(255, 234, 0, 0.5), 0 0 0 2px var(--teal);
    }

    .btn-top-signin:active,
    .btn-top-book:active {
      transform: scale(0.97);
    }

    .top-bar-actions .btn:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 2px;
    }

    /* Header — editorial strip */
    header {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: none;
      box-shadow: 0 4px 20px rgba(88, 28, 135, 0.06);
      transition: box-shadow 0.25s var(--ease);
    }

    header.is-scrolled {
      box-shadow: 0 6px 28px rgba(42, 10, 50, 0.1);
    }

    header.is-scrolled .nav {
      padding: 0.5rem 0;
    }

    header.is-scrolled .brand-logo {
      height: 56px;
    }

    header.is-scrolled .brand-text .name {
      font-size: 1rem;
    }

    header::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--gold-light) 0%, var(--teal) 35%, var(--magenta-bright) 65%, var(--gold-light) 100%);
      pointer-events: none;
    }

    .nav {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.75rem 1.25rem;
      padding: 0.75rem 0;
    }

    .nav-cluster {
      display: flex;
      align-items: center;
      gap: 0;
      flex: 1;
      justify-content: flex-end;
      min-width: 0;
    }

    .nav-toggle {
      display: none;
      margin-left: auto;
      width: 44px;
      height: 44px;
      border-radius: var(--radius-sm);
      border: none;
      background: var(--navy);
      color: #fff;
      cursor: pointer;
      align-items: center;
      justify-content: center;
      transition: background 0.2s var(--ease), transform 0.15s var(--ease);
    }

    .nav-toggle:hover {
      background: var(--navy-soft);
    }

    .nav-toggle:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    .nav-toggle[aria-expanded="true"] {
      background: var(--teal);
    }

    .nav-toggle-bars {
      display: flex;
      flex-direction: column;
      gap: 5px;
      width: 20px;
      pointer-events: none;
    }

    .nav-toggle-bars span {
      display: block;
      height: 2px;
      background: #fff;
      border-radius: 1px;
      transition: transform 0.25s var(--ease), opacity 0.2s var(--ease);
    }

    .nav-toggle[aria-expanded="true"] .nav-toggle-bars span:nth-child(1) {
      transform: translateY(7px) rotate(45deg);
    }

    .nav-toggle[aria-expanded="true"] .nav-toggle-bars span:nth-child(2) {
      opacity: 0;
    }

    .nav-toggle[aria-expanded="true"] .nav-toggle-bars span:nth-child(3) {
      transform: translateY(-7px) rotate(-45deg);
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 0.875rem;
      text-decoration: none;
      color: inherit;
    }

    .brand-logo {
      height: 72px;
      width: auto;
      max-width: min(280px, 52vw);
      object-fit: contain;
      object-position: left center;
      flex-shrink: 0;
      display: block;
      transition: height 0.25s var(--ease);
    }

    .brand-text {
      display: flex;
      flex-direction: column;
      gap: 0.1rem;
      line-height: 1.2;
    }

    .brand-text .name {
      font-weight: 700;
      font-size: 1.125rem;
      color: var(--navy);
      letter-spacing: -0.02em;
    }

    .brand-text .tag {
      font-size: 0.75rem;
      font-weight: 500;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }

    .nav-links-wrap {
      padding: 0;
    }

    @media (min-width: 901px) {
      .nav-links-wrap {
        padding: 0.22rem 0.32rem;
        overflow: visible;
        background: linear-gradient(
          155deg,
          rgba(255, 234, 0, 0.28) 0%,
          rgba(255, 255, 255, 0.97) 42%,
          rgba(232, 121, 249, 0.16) 100%
        );
        border: 1px solid rgba(192, 38, 211, 0.2);
        border-radius: 999px;
        box-shadow:
          inset 0 1px 0 rgba(255, 255, 255, 0.9),
          0 2px 16px rgba(42, 10, 50, 0.06);
      }
    }

    .nav-links {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.12rem;
      list-style: none;
      justify-content: flex-end;
    }

    .nav-links li {
      display: flex;
      align-items: center;
    }

    .nav-links li.nav-link-accent {
      flex-shrink: 0;
    }

    .nav-links li + li::before {
      display: none;
    }

    .nav-links a {
      display: inline-block;
      text-decoration: none;
      color: var(--navy);
      font-weight: 600;
      font-size: 0.8125rem;
      letter-spacing: 0.02em;
      padding: 0.48rem 0.95rem;
      border-radius: 999px;
      border-bottom: none;
      transition:
        background 0.2s var(--ease),
        color 0.2s var(--ease),
        box-shadow 0.2s var(--ease),
        transform 0.15s var(--ease),
        filter 0.12s var(--ease);
    }

    .nav-links li:not(.nav-link-accent) a:hover {
      color: var(--navy);
      background: rgba(255, 234, 0, 0.45);
      box-shadow: 0 0 0 1px rgba(192, 38, 211, 0.18);
    }

    .nav-links a:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    .nav-links li.nav-link-accent a {
      color: #fff;
      font-weight: 700;
      white-space: nowrap;
      padding-inline: 1.05rem;
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      box-shadow: 0 2px 14px rgba(192, 38, 211, 0.35);
    }

    .nav-links li.nav-link-accent a:hover {
      color: #fff;
      background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
      transform: translateY(-1px);
      box-shadow: 0 4px 18px rgba(192, 38, 211, 0.42);
    }

    .nav-links li.nav-link-accent a:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 3px;
    }

    .nav-links li:not(.nav-link-accent) a:active {
      transform: scale(0.97);
    }

    .nav-links li.nav-link-accent a:active {
      transform: translateY(0) scale(0.98);
      filter: brightness(0.92);
    }

    .nav-links a[aria-current="page"] {
      background: rgba(192, 38, 211, 0.18);
      color: var(--navy);
      box-shadow: inset 0 0 0 2px var(--teal);
    }

    .nav-links a[aria-current="page"]:hover {
      background: rgba(255, 234, 0, 0.45);
      color: var(--navy);
      box-shadow: inset 0 0 0 2px var(--teal), 0 0 0 1px rgba(192, 38, 211, 0.18);
    }

    .nav-links li:not(.nav-link-accent) a.is-active {
      background: rgba(192, 38, 211, 0.22);
      color: var(--navy);
      box-shadow: inset 0 0 0 2px var(--teal);
    }

    .nav-links li:not(.nav-link-accent) a.is-active:hover {
      background: rgba(255, 234, 0, 0.5);
      box-shadow: inset 0 0 0 2px var(--teal), 0 0 0 1px rgba(192, 38, 211, 0.2);
    }

    .nav-links li.nav-link-accent a.is-active {
      color: #fff;
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      box-shadow:
        0 0 0 3px var(--gold-light),
        0 4px 20px rgba(192, 38, 211, 0.45);
      filter: none;
    }

    .nav-links li.nav-link-accent a.is-active:hover {
      color: #fff;
      background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
      transform: translateY(-1px);
    }

    .nav-links li.nav-link-accent a.is-active:active {
      transform: translateY(0) scale(0.98);
      filter: brightness(0.94);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      border-radius: var(--radius-sm);
      font-family: var(--font);
      font-weight: 600;
      font-size: 0.9375rem;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.2s var(--ease), color 0.2s var(--ease), box-shadow 0.2s var(--ease), transform 0.15s var(--ease);
      border: none;
      white-space: nowrap;
    }

    .btn-primary {
      background: var(--teal);
      color: #fff;
      padding: 0.65rem 1.25rem;
      box-shadow: 0 2px 8px rgba(192, 38, 211, 0.25);
    }

    .btn-primary:hover {
      background: var(--teal-hover);
      box-shadow: 0 4px 14px rgba(192, 38, 211, 0.3);
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: transparent;
      color: var(--navy);
      padding: 0.65rem 1.1rem;
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      border-color: var(--teal);
      color: var(--teal);
      background: var(--teal-muted);
    }

    .btn-ghost {
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
      padding: 0.65rem 1.25rem;
      border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
    }

    .btn-light {
      background: #fff;
      color: var(--navy);
      padding: 0.65rem 1.35rem;
      box-shadow: var(--shadow);
    }

    .btn-light:hover {
      background: var(--bg-subtle);
    }

    .btn:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 3px;
    }

    .btn-primary:active {
      transform: translateY(0) scale(0.98);
      filter: brightness(0.92);
    }

    .btn-secondary:active {
      transform: scale(0.98);
    }

    .btn-ghost:active {
      transform: scale(0.98);
      background: rgba(255, 255, 255, 0.14);
    }

    .btn-light:active {
      transform: scale(0.98);
      box-shadow: var(--shadow-sm);
    }

    /* Hero — original navy/teal overlay & glass card (hero only; page theme unchanged below) */
    .hero {
      position: relative;
      color: #fff;
      padding: clamp(4rem, 10vw, 6.5rem) 0 clamp(4.5rem, 12vw, 7rem);
      overflow: hidden;
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      background:
        linear-gradient(105deg, rgba(12, 25, 41, 0.9) 0%, rgba(15, 92, 85, 0.72) 48%, rgba(12, 25, 41, 0.88) 100%),
        var(--hero-image) center 22% / cover no-repeat;
      z-index: 0;
    }

    .hero-bg::after {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 80% 60% at 70% 20%, rgba(201, 162, 39, 0.12), transparent 55%);
      pointer-events: none;
    }

    .hero .container {
      position: relative;
      z-index: 1;
    }

    .hero-grid {
      display: grid;
      gap: 2.5rem;
      align-items: start;
    }

    @media (min-width: 900px) {
      .hero-grid {
        grid-template-columns: 1fr minmax(280px, 360px);
        gap: 3rem;
        align-items: center;
      }
    }

    .hero .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.14em;
      color: rgba(255, 255, 255, 0.85);
      margin-bottom: 1.25rem;
    }

    .hero .eyebrow::before {
      content: "";
      width: 28px;
      height: 2px;
      background: #c9a227;
      border-radius: 1px;
    }

    .hero h1 {
      font-size: clamp(2.15rem, 4.5vw, 3.35rem);
      font-weight: 700;
      line-height: 1.12;
      letter-spacing: -0.03em;
      margin-bottom: 1.25rem;
      max-width: 18ch;
    }

    .hero-lead {
      font-size: 1.125rem;
      line-height: 1.7;
      color: rgba(255, 255, 255, 0.88);
      max-width: 52ch;
      margin-bottom: 1.75rem;
      font-weight: 400;
    }

    .hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.875rem;
      margin-bottom: 2.25rem;
    }

    /* Original hero CTAs: solid white primary, ghost secondary */
    .hero-actions .btn-light {
      background: #fff;
      color: #0c1929;
      padding: 0.65rem 1.35rem;
      box-shadow: 0 4px 24px rgba(12, 25, 41, 0.08);
    }

    .hero-actions .btn-light:hover {
      background: #f8fafc;
      color: #0c1929;
    }

    .hero-actions .btn-ghost {
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
      padding: 0.65rem 1.25rem;
      border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .hero-actions .btn-ghost:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
      color: #fff;
    }

    .hero-actions .btn-light:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 3px;
    }

    .hero-actions .btn-ghost:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 3px;
    }

    .hero-actions .btn-light:active {
      transform: scale(0.98);
      background: #f1f5f9;
    }

    .hero-actions .btn-ghost:active {
      transform: scale(0.98);
      background: rgba(255, 255, 255, 0.18);
    }

    .hero-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.18);
    }

    .hero-meta dt {
      font-size: 0.6875rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: rgba(255, 255, 255, 0.55);
      margin-bottom: 0.25rem;
    }

    .hero-meta dd {
      font-size: 0.9375rem;
      font-weight: 600;
      color: #fff;
    }

    .hero-card {
      background: rgba(255, 255, 255, 0.16);
      border: 1px solid rgba(255, 255, 255, 0.22);
      border-radius: var(--radius);
      padding: 1.5rem 1.5rem 1.35rem;
    }

    .hero-card h2 {
      font-size: 0.8125rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255, 255, 255, 0.75);
      margin-bottom: 1rem;
    }

    .hero-card ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.875rem;
    }

    .hero-card li {
      display: flex;
      gap: 0.75rem;
      align-items: flex-start;
      font-size: 0.9375rem;
      color: rgba(255, 255, 255, 0.92);
      line-height: 1.45;
    }

    .hero-card svg {
      flex-shrink: 0;
      margin-top: 0.15rem;
      opacity: 0.9;
    }

    /* Sections */
    .section {
      padding: clamp(4rem, 8vw, 5.5rem) 0;
    }

    .section.alt {
      background: var(--bg);
    }

    .section-head {
      max-width: 640px;
      margin-bottom: 2.75rem;
    }

    .section-head.center {
      margin-left: auto;
      margin-right: auto;
      text-align: center;
    }

    .section-kicker {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--teal);
      margin-bottom: 0.65rem;
    }

    .section-head h2 {
      font-size: clamp(1.65rem, 3vw, 2.125rem);
      font-weight: 700;
      color: var(--navy);
      letter-spacing: -0.02em;
      line-height: 1.2;
      margin-bottom: 0.75rem;
    }

    .section-head p {
      color: var(--text-muted);
      font-size: 1.0625rem;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.25rem;
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 0;
      overflow: hidden;
      transition: border-color 0.2s var(--ease), box-shadow 0.2s var(--ease);
      position: relative;
    }

    .card.is-available {
      border-color: rgba(22, 163, 74, 0.25);
    }

    .card.is-soon {
      opacity: 0.92;
    }

    .card-badge {
      position: absolute;
      top: 0.75rem;
      left: 0.75rem;
      z-index: 2;
      font-size: 0.6875rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 0.3rem 0.6rem;
      border-radius: 999px;
    }

    .card-badge.available {
      background: #dcfce7;
      color: #166534;
      border: 1px solid rgba(22, 163, 74, 0.3);
    }

    .card-badge.soon {
      background: rgba(255, 255, 255, 0.92);
      color: var(--text-muted);
      border: 1px solid var(--border);
    }

    .card-link-stretch {
      position: absolute;
      inset: 0;
      z-index: 1;
      border-radius: inherit;
    }

    .card-link-stretch:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: -4px;
    }

    .card-body {
      position: relative;
      z-index: 0;
    }

    .card-body .card-link {
      position: relative;
      z-index: 2;
    }

    /* Featured service banner */
    .featured-service {
      display: grid;
      gap: 1.5rem;
      align-items: center;
      margin-bottom: 1.75rem;
      padding: 1.5rem;
      border-radius: var(--radius);
      background: linear-gradient(135deg, rgba(42, 10, 50, 0.96) 0%, rgba(112, 26, 117, 0.88) 55%, rgba(30, 27, 75, 0.94) 100%);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.12);
      box-shadow: var(--shadow-lg);
    }

    @media (min-width: 768px) {
      .featured-service {
        grid-template-columns: 1fr auto;
        padding: 1.75rem 2rem;
      }
    }

    .featured-service-content h3 {
      font-size: clamp(1.25rem, 2.5vw, 1.5rem);
      font-weight: 700;
      margin-bottom: 0.5rem;
      letter-spacing: -0.02em;
    }

    .featured-service-content p {
      color: rgba(255, 255, 255, 0.85);
      font-size: 0.9375rem;
      max-width: 52ch;
      line-height: 1.6;
    }

    .featured-service-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.65rem;
    }

    .featured-service-actions .btn-light {
      background: var(--gold-light);
      color: var(--navy);
    }

    .featured-service-actions .btn-light:hover {
      background: #fff48f;
    }

    .featured-service-actions .btn-ghost {
      border-color: rgba(255, 255, 255, 0.4);
    }

    .card:hover {
      border-color: rgba(255, 234, 0, 0.75);
      box-shadow: 0 0 0 1px rgba(192, 38, 211, 0.22), var(--shadow);
    }

    .card-visual {
      position: relative;
      aspect-ratio: 16 / 10;
      overflow: hidden;
      background: var(--bg);
    }

    .card-visual img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      transition: transform 0.45s var(--ease);
    }

    .card:hover .card-visual img {
      transform: scale(1.04);
    }

    .card-body {
      padding: 1.5rem 1.75rem 1.75rem;
    }

    .card-icon {
      width: 48px;
      height: 48px;
      border-radius: var(--radius-sm);
      background: var(--teal-muted);
      color: var(--teal);
      display: grid;
      place-items: center;
      margin-bottom: 1.1rem;
    }

    .card-icon svg {
      width: 24px;
      height: 24px;
    }

    .card h3 {
      font-size: 1.125rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 0.5rem;
      letter-spacing: -0.01em;
    }

    .card p {
      color: var(--text-muted);
      font-size: 0.9375rem;
      line-height: 1.6;
      margin-bottom: 1rem;
    }

    .card-link {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-size: 0.875rem;
      font-weight: 700;
      color: var(--teal);
      text-decoration: none;
    }

    .card-link:hover {
      text-decoration: underline;
    }

    .card-body .card-link:not([href]) {
      color: var(--text-muted);
      font-weight: 600;
      cursor: default;
    }

    .card-body .card-link:not([href]):hover {
      text-decoration: none;
    }

    .guide-item[id] {
      scroll-margin-top: 8.5rem;
    }

    .guide-sidebar {
      display: none;
    }

    /* Steps */
    .steps {
      display: grid;
      gap: 1.25rem;
    }

    @media (min-width: 768px) {
      .steps {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        position: relative;
      }

      .steps::before {
        content: "";
        position: absolute;
        top: 2.85rem;
        left: 18%;
        right: 18%;
        height: 2px;
        background: linear-gradient(90deg, var(--border), var(--teal-muted), var(--border));
        z-index: 0;
      }
    }

    .step {
      position: relative;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.75rem 1.5rem;
      z-index: 1;
    }

    .step-num {
      width: 2.25rem;
      height: 2.25rem;
      border-radius: 50%;
      background: var(--navy);
      color: #fff;
      font-weight: 700;
      font-size: 0.875rem;
      display: grid;
      place-items: center;
      margin-bottom: 1rem;
    }

    .step h3 {
      font-size: 1.0625rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 0.45rem;
    }

    .step p {
      font-size: 0.9375rem;
      color: var(--text-muted);
      line-height: 1.55;
    }

    /* CTA */
    .cta-wrap {
      padding: 0 0 clamp(4rem, 8vw, 5.5rem);
    }

    .cta {
      position: relative;
      isolation: isolate;
      overflow: hidden;
      color: #fff;
      border-radius: var(--radius);
      padding: clamp(2.5rem, 5vw, 3.5rem);
      text-align: center;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .cta::before {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 0;
      background:
        linear-gradient(
          135deg,
          rgba(42, 10, 50, 0.94) 0%,
          rgba(112, 26, 117, 0.82) 40%,
          rgba(255, 234, 0, 0.2) 62%,
          rgba(42, 10, 50, 0.95) 100%
        ),
        var(--cta-image) center 40% / cover no-repeat;
    }

    .cta > * {
      position: relative;
      z-index: 1;
    }

    .cta h2 {
      font-size: clamp(1.5rem, 2.8vw, 2rem);
      font-weight: 700;
      letter-spacing: -0.02em;
      margin-bottom: 0.75rem;
      line-height: 1.25;
    }

    .cta p {
      max-width: 560px;
      margin: 0 auto 1.5rem;
      color: rgba(255, 255, 255, 0.82);
      font-size: 1.0625rem;
    }

    .cta-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      justify-content: center;
    }

    /* Footer */
    footer {
      position: relative;
      background: linear-gradient(180deg, #3b0764 0%, var(--navy) 28%, #1a0520 100%);
      color: rgba(255, 255, 255, 0.75);
      font-size: 0.9375rem;
      padding: 3rem 0 2rem;
      border-top: none;
    }

    footer::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--gold-light), var(--teal), var(--magenta-bright), var(--gold-light));
    }

    .footer-grid {
      display: grid;
      gap: 2rem;
      margin-bottom: 2.5rem;
    }

    @media (min-width: 640px) {
      .footer-grid {
        grid-template-columns: 1.4fr 1fr 1fr;
        gap: 2.5rem;
      }
    }

    .footer-brand-lockup {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .footer-logo {
      height: 88px;
      width: auto;
      max-width: 130px;
      object-fit: contain;
      object-position: left top;
      flex-shrink: 0;
    }

    .footer-brand .name {
      font-weight: 700;
      color: #fff;
      font-size: 1.05rem;
      margin-bottom: 0.5rem;
    }

    .footer-brand p {
      line-height: 1.6;
      max-width: 320px;
    }

    .footer-col h3 {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: rgba(255, 255, 255, 0.5);
      margin-bottom: 1rem;
    }

    .footer-col ul {
      list-style: none;
    }

    .footer-col a {
      color: rgba(255, 255, 255, 0.88);
      text-decoration: none;
      display: block;
      padding: 0.4rem 0.5rem;
      margin-left: -0.5rem;
      border-radius: var(--radius-sm);
      font-weight: 500;
      font-size: 0.9rem;
      transition: color 0.2s var(--ease), background 0.2s var(--ease);
    }

    .footer-col a:hover {
      color: var(--navy);
      background: var(--gold-light);
      box-shadow: 0 0 0 1px var(--magenta-bright);
    }

    .footer-bottom {
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.12);
      font-size: 0.8125rem;
      color: rgba(255, 255, 255, 0.55);
      text-align: center;
      line-height: 1.6;
    }

    /* Image gallery / carousel */
    .carousel-section {
      background: linear-gradient(160deg, #4a044e 0%, var(--navy) 45%, #312e81 100%);
      color: #fff;
      padding: clamp(3rem, 6vw, 4.25rem) 0;
      box-shadow: inset 0 1px 0 rgba(255, 234, 0, 0.2);
    }

    .carousel-shell {
      max-width: 1000px;
      margin-inline: auto;
    }

    .carousel {
      position: relative;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .carousel-viewport {
      overflow: hidden;
    }

    .carousel-track {
      display: flex;
      transition: transform 0.55s var(--ease);
    }

    @media (prefers-reduced-motion: reduce) {
      .carousel-track {
        transition: none;
      }
    }

    .carousel-slide {
      flex: 0 0 100%;
      position: relative;
      margin: 0;
    }

    .carousel-slide img {
      width: 100%;
      height: min(52vh, 540px);
      object-fit: cover;
      object-position: center;
      display: block;
    }

    .carousel-caption {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      padding: 1.25rem 1.5rem;
      background: linear-gradient(transparent, rgba(42, 10, 50, 0.88));
      font-size: 0.9375rem;
      font-weight: 500;
      line-height: 1.45;
    }

    .carousel-nav {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      pointer-events: none;
      padding: 0 0.5rem;
    }

    .carousel-btn {
      pointer-events: auto;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, 0.35);
      background: rgba(42, 10, 50, 0.72);
      color: #fff;
      cursor: pointer;
      display: grid;
      place-items: center;
      transition: background 0.2s var(--ease), transform 0.15s var(--ease);
    }

    .carousel-btn:hover {
      background: rgba(192, 38, 211, 0.75);
      transform: scale(1.05);
    }

    .carousel-btn:active {
      transform: scale(0.95);
      background: rgba(192, 38, 211, 0.9);
    }

    .carousel-btn:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 2px;
    }

    .carousel-dots {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1.25rem;
      flex-wrap: wrap;
    }

    .carousel-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      border: none;
      padding: 0;
      background: rgba(255, 255, 255, 0.35);
      cursor: pointer;
      transition: background 0.2s var(--ease), transform 0.15s var(--ease);
    }

    .carousel-dot[aria-selected="true"] {
      background: var(--gold-light);
      transform: scale(1.15);
      box-shadow: 0 0 0 2px rgba(192, 38, 211, 0.55);
    }

    .carousel-dot:active {
      transform: scale(0.9);
    }

    .carousel-dot[aria-selected="true"]:active {
      transform: scale(1.05);
    }

    .carousel-dot:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 2px;
    }

    .carousel-live {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      border: 0;
    }

    /* Visitor guide (Nag Atup / docs workflow) */
    .guide-list {
      list-style: none;
      max-width: 720px;
      margin-inline: auto;
      counter-reset: guide;
    }

    .guide-item {
      position: relative;
      padding-left: 3.25rem;
      padding-bottom: 1.75rem;
      border-left: 2px solid rgba(192, 38, 211, 0.35);
      margin-left: 1rem;
    }

    .guide-item:last-child {
      border-left-color: transparent;
      padding-bottom: 0;
    }

    .guide-item::before {
      counter-increment: guide;
      content: counter(guide);
      position: absolute;
      left: -1rem;
      transform: translateX(-50%);
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      background: var(--teal);
      color: #fff;
      font-weight: 700;
      font-size: 0.875rem;
      display: grid;
      place-items: center;
      top: 0;
      box-shadow: 0 2px 8px rgba(192, 38, 211, 0.3);
    }

    .guide-item h3 {
      font-size: 1.0625rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 0.4rem;
    }

    .guide-item p {
      color: var(--text-muted);
      font-size: 0.9375rem;
      line-height: 1.6;
    }

    .guide-item .guide-doc {
      margin-top: 0.5rem;
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--teal);
    }

    .guide-item .guide-doc a {
      color: var(--teal-hover);
      text-decoration: underline;
    }

    .guide-item .guide-doc a:hover {
      color: var(--navy);
    }

    .guide-note {
      max-width: 640px;
      margin: 2rem auto 0;
      padding: 1rem 1.25rem;
      background: linear-gradient(135deg, rgba(255, 234, 0, 0.18), var(--teal-muted));
      border: 1px solid rgba(192, 38, 211, 0.22);
      border-left: 4px solid var(--gold-light);
      border-radius: var(--radius-sm);
      font-size: 0.9375rem;
      color: var(--text);
    }

    @media (min-width: 900px) {
      .guide-layout {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 2.5rem;
        align-items: start;
      }

      .guide-sidebar {
        display: block;
        position: sticky;
        top: 8.5rem;
        padding: 1.25rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
      }

      .guide-sidebar h3 {
        font-size: 0.8125rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--teal);
        margin-bottom: 0.75rem;
      }

      .guide-sidebar ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }

      .guide-sidebar a {
        display: block;
        padding: 0.5rem 0.65rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--navy);
        text-decoration: none;
        transition: background 0.15s var(--ease);
      }

      .guide-sidebar a:hover {
        background: var(--teal-muted);
      }
    }

    /* Contact details */
    .contact-grid {
      display: grid;
      gap: 1rem;
      max-width: 720px;
      margin: 0 auto 1.5rem;
      text-align: left;
    }

    @media (min-width: 560px) {
      .contact-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .contact-item {
      padding: 1rem 1.15rem;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: var(--radius-sm);
    }

    .contact-item dt {
      font-size: 0.6875rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255, 255, 255, 0.6);
      margin-bottom: 0.35rem;
    }

    .contact-item dd {
      font-size: 0.9375rem;
      font-weight: 600;
      color: #fff;
      line-height: 1.45;
    }

    .contact-item a {
      color: #fff;
      text-decoration: none;
    }

    .contact-item a:hover {
      text-decoration: underline;
    }

    /* Back to top & mobile FAB */
    .back-to-top,
    .fab-book {
      position: fixed;
      z-index: 45;
      display: grid;
      place-items: center;
      border: none;
      cursor: pointer;
      font-family: var(--font);
      transition: opacity 0.25s var(--ease), transform 0.25s var(--ease), visibility 0.25s;
    }

    .back-to-top {
      right: 1.25rem;
      bottom: 1.25rem;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: var(--navy);
      color: #fff;
      box-shadow: var(--shadow);
      opacity: 0;
      visibility: hidden;
      transform: translateY(12px);
    }

    .back-to-top.is-visible {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .back-to-top:hover {
      background: var(--navy-soft);
    }

    .back-to-top:focus-visible {
      outline: 2px solid var(--gold-light);
      outline-offset: 2px;
    }

    .fab-book {
      right: 1.25rem;
      bottom: 1.25rem;
      padding: 0.75rem 1.15rem;
      border-radius: 999px;
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      color: #fff;
      font-weight: 700;
      font-size: 0.875rem;
      text-decoration: none;
      box-shadow: 0 4px 20px rgba(192, 38, 211, 0.4);
    }

    .fab-book:hover {
      transform: translateY(-2px);
      color: #fff;
    }

    @media (min-width: 901px) {
      .fab-book {
        display: none;
      }
    }

    @media (max-width: 900px) {
      .back-to-top {
        bottom: 4.5rem;
      }
    }

    /* Download / forms grid */
    .doc-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.25rem;
    }

    .doc-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.5rem 1.5rem 1.35rem;
      display: flex;
      flex-direction: column;
      gap: 0.65rem;
      transition: border-color 0.2s var(--ease), box-shadow 0.2s var(--ease);
    }

    .doc-card:hover {
      border-color: rgba(255, 234, 0, 0.85);
      box-shadow: 0 0 0 1px rgba(192, 38, 211, 0.2), var(--shadow);
    }

    .doc-card .doc-step {
      font-size: 0.6875rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--teal);
    }

    .doc-card h3 {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--navy);
      line-height: 1.25;
    }

    .doc-card p {
      font-size: 0.9rem;
      color: var(--text-muted);
      line-height: 1.55;
      flex-grow: 1;
    }

    .btn-doc {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      margin-top: 0.35rem;
      padding: 0.55rem 1rem;
      border-radius: var(--radius-sm);
      background: var(--navy);
      color: #fff;
      font-family: var(--font);
      font-weight: 600;
      font-size: 0.875rem;
      text-decoration: none;
      transition: background 0.2s var(--ease), transform 0.12s var(--ease), filter 0.12s var(--ease);
    }

    .btn-doc:hover {
      background: var(--navy-soft);
    }

    .btn-doc:active {
      transform: scale(0.98);
      filter: brightness(0.95);
    }

    .btn-doc:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    @media (max-width: 1100px) {
      .nav-links a {
        font-size: 0.75rem;
        padding: 0.42rem 0.72rem;
      }
    }

    @media (max-width: 900px) {
      .nav-toggle {
        display: inline-flex;
      }

      .nav-cluster {
        display: none;
        order: 3;
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        padding: 1rem 0 0.35rem;
        margin-top: 0.35rem;
        border-top: 1px solid rgba(192, 38, 211, 0.15);
        flex: none;
        justify-content: flex-start;
      }

      .nav-cluster.is-open {
        display: flex;
      }

      .nav-links-wrap {
        width: 100%;
        padding: 0;
        background: transparent;
        border: none;
        border-radius: 0;
        box-shadow: none;
      }

      .nav-links {
        flex-direction: column;
        align-items: stretch;
        gap: 0.45rem;
        background: transparent;
        border: none;
        border-radius: 0;
        overflow: visible;
      }

      .nav-links li {
        display: block;
        border-bottom: none;
      }

      .nav-links li + li::before {
        display: none;
      }

      .nav-links a {
        text-align: left;
        padding: 0.9rem 1.15rem;
        font-size: 0.9375rem;
        font-weight: 600;
        border-radius: var(--radius);
        color: var(--navy);
        background: #fff;
        border: 1px solid var(--border);
        box-shadow: 0 2px 10px rgba(42, 10, 50, 0.05);
      }

      .nav-links li:not(.nav-link-accent) a:hover {
        background: linear-gradient(100deg, rgba(255, 234, 0, 0.28), #fff 55%);
        border-color: rgba(192, 38, 211, 0.35);
        color: var(--navy);
        transform: none;
        box-shadow: 0 2px 14px rgba(192, 38, 211, 0.12);
      }

      .nav-links li.nav-link-accent a {
        background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
        color: #fff;
        border: none;
        box-shadow: 0 3px 16px rgba(192, 38, 211, 0.35);
        white-space: nowrap;
      }

      .nav-links li.nav-link-accent a:hover {
        color: #fff;
        background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
        border: none;
      }

      .nav-links li.nav-link-accent a:focus-visible {
        outline: 2px solid var(--gold-light);
        outline-offset: 3px;
      }

      .nav-links li:not(.nav-link-accent) a:active {
        transform: scale(0.99);
      }

      .nav-links li.nav-link-accent a:active {
        transform: scale(0.98);
        filter: brightness(0.94);
      }

      .nav-links a[aria-current="page"] {
        background: linear-gradient(100deg, rgba(255, 234, 0, 0.35), #fff 65%);
        border-color: rgba(192, 38, 211, 0.45);
        box-shadow: 0 0 0 2px var(--teal), 0 2px 12px rgba(192, 38, 211, 0.12);
      }

      .nav-links li:not(.nav-link-accent) a.is-active {
        background: linear-gradient(100deg, rgba(255, 234, 0, 0.4), #fff 60%);
        border-color: rgba(192, 38, 211, 0.5);
        box-shadow: 0 0 0 2px var(--teal), 0 2px 12px rgba(192, 38, 211, 0.1);
      }

      .nav-links li:not(.nav-link-accent) a.is-active:hover {
        border-color: rgba(192, 38, 211, 0.55);
      }

      .nav-links li.nav-link-accent a.is-active {
        background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
        color: #fff;
        border: none;
        box-shadow: 0 0 0 3px var(--gold-light), 0 4px 18px rgba(192, 38, 211, 0.4);
      }

      .nav-links li.nav-link-accent a.is-active:hover {
        color: #fff;
        background: linear-gradient(135deg, var(--teal-hover) 0%, #9333ea 100%);
      }
    }

    @media (max-width: 768px) {
      .top-bar-divider {
        display: none;
      }

      .top-bar-meta {
        gap: 0.4rem 0.65rem;
      }

      .top-bar-badge {
        font-size: 0.6875rem;
        padding: 0.22rem 0.55rem 0.22rem 0.4rem;
      }
    }

    @media (max-width: 560px) {
      .top-bar .container {
        flex-direction: column;
        align-items: stretch;
        gap: 0.55rem;
      }

      .top-bar-meta {
        justify-content: flex-start;
      }

      .top-bar-actions {
        width: 100%;
        justify-content: stretch;
      }

      .top-bar-actions form {
        display: flex;
        flex: 1;
      }

      .top-bar-actions .btn {
        flex: 1;
        min-height: 2.5rem;
      }

      .hero h1 {
        max-width: none;
      }

      .hero-meta {
        flex-direction: column;
        gap: 1rem;
      }
    }
  </style>
</head>
<body>
  @php
    $officialDocs = [
      [
        'step' => 'Step 1',
        'file' => 'NAG ATUP INFORMATION.pdf',
        'title' => 'Nag Atup information',
        'desc' => 'Read site rules, orientation, and LGU guidance before planning your visit.',
      ],
      [
        'step' => 'Step 2',
        'file' => 'HEALTH DECLARATION FORM.pdf',
        'title' => 'Health declaration',
        'desc' => 'Complete the health declaration as required for visitor entry and activity participation.',
      ],
      [
        'step' => 'Step 3',
        'file' => 'ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf',
        'title' => 'Acknowledgement & waiver of risk',
        'desc' => 'Review and sign the acknowledgement and waiver where applicable to your activity.',
      ],
      [
        'step' => 'Step 4',
        'file' => 'NAG-ATUP Visitors Entry Permit.pdf',
        'title' => 'Visitors entry permit',
        'desc' => 'Apply for the official Nag-Atup visitors entry permit and follow verification steps at the municipal desk.',
      ],
    ];
  @endphp
  <a class="skip-link" href="#main-content">Skip to main content</a>
  <div class="top-bar" role="region" aria-label="Contact and account shortcuts">
    <div class="container">
      <div class="top-bar-meta">
        <span class="top-bar-badge">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          Official e-service
        </span>
        <span class="top-bar-divider" aria-hidden="true"></span>
        <a href="tel:+630000000000" class="top-bar-link" aria-label="Call municipal front desk at (XXX) XXX-XXXX">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>Front desk <strong>(XXX) XXX-XXXX</strong></span>
        </a>
      </div>
      <div class="top-bar-actions">
        @auth
          <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('bookings.index') }}" class="btn btn-top-signin">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            {{ auth()->user()->is_admin ? 'Admin dashboard' : 'My bookings' }}
          </a>
          <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-top-book" style="border:none; cursor:pointer;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
              Sign out
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-top-signin">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            Sign in
          </a>
          <a href="{{ route('atup.overview') }}" class="btn btn-top-book">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Book Atup-atup hike
          </a>
        @endauth
      </div>
    </div>
  </div>

  <header id="site-header">
    <div class="container nav">
      <a class="brand" href="{{ url('/') }}">
        <img
          class="brand-logo"
          src="{{ asset('images/Logo.png') }}"
          alt="Seal of the Municipality of Aborlan"
          width="347"
          height="96"
          decoding="async"
        />
        <span class="brand-text">
          <span class="name">{{ config('app.name') }}</span>
          <span class="tag">Municipality of Aborlan · Palawan</span>
        </span>
      </a>
      <button
        type="button"
        class="nav-toggle"
        id="nav-toggle"
        aria-expanded="false"
        aria-controls="nav-cluster"
      >
        <span class="nav-toggle-bars" aria-hidden="true">
          <span></span>
          <span></span>
          <span></span>
        </span>
        <span class="sr-only">Open menu</span>
      </button>
      <div class="nav-cluster" id="nav-cluster">
        <nav class="nav-links-wrap" aria-label="Primary navigation">
          <ul class="nav-links">
            <li>
              <a href="{{ url('/') }}" aria-current="page">Home</a>
            </li>
            <li><a href="#gallery">Gallery</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#visitor-guide">Visitor guide</a></li>
            <li><a href="#forms">Forms</a></li>
            <li><a href="#how">How it works</a></li>
            <li class="nav-link-accent"><a href="{{ route('atup.overview') }}">Book a hike</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>

  <main id="main-content">
  <section class="hero">
    <div
      class="hero-bg"
      style="--hero-image: url('{{ $heroImageUrl }}');"
      aria-hidden="true"
    ></div>
    <div class="container">
      <div class="hero-grid">
        <div>
          <p class="eyebrow">Municipality of Aborlan · Palawan</p>
          <h1>Plan your visit to Atup-atup Falls and municipal services</h1>
          <p class="hero-lead">
            Book hiking permits online, review official visitor requirements, and access LGU forms—all in one
            place. Start with the visitor guide, then reserve your slot when you are ready.
          </p>
          <div class="hero-actions">
            <a href="{{ route('atup.overview') }}" class="btn btn-light">Book Atup-atup Falls hike</a>
            <a href="#visitor-guide" class="btn btn-ghost">Read visitor guide</a>
          </div>
          <dl class="hero-meta">
            <div>
              <dt>Online booking</dt>
              <dd>Available 24 hours</dd>
            </div>
            <div>
              <dt>Confirmation</dt>
              <dd>During office hours (Mon–Fri)</dd>
            </div>
            <div>
              <dt>Primary destination</dt>
              <dd>Atup-atup Falls · Nag Atup</dd>
            </div>
          </dl>
        </div>
        <aside class="hero-card" aria-labelledby="hero-aside-title">
          <h2 id="hero-aside-title">Before you book</h2>
          <ul>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              <span>Complete the health declaration and waiver forms listed in the visitor guide.</span>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>Choose your visit date from published daily quotas on the booking page.</span>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <span>Bring valid ID and signed permits on the day of your hike.</span>
            </li>
          </ul>
        </aside>
      </div>
    </div>
  </section>

  <section class="carousel-section" id="gallery" aria-labelledby="gallery-title">
    <div class="container">
      <div class="section-head center" style="margin-bottom: 2rem;">
        <p class="section-kicker" style="color: rgba(255,255,255,0.65);">Destination gallery</p>
        <h2 id="gallery-title" style="color: #fff;">Aborlan &amp; Nag Atup in photographs</h2>
        <p style="color: rgba(255,255,255,0.82);">A sample of local landscapes and visitor experiences. Official requirements for entry are listed in the visitor guide and downloadable forms below.</p>
      </div>
      <div class="carousel-shell">
        <div class="carousel" data-carousel>
          <div class="carousel-viewport">
            <div class="carousel-track">
              @foreach ($gallerySlides as $slide)
                <figure class="carousel-slide" data-caption="{{ e($slide['caption']) }}">
                  <img
                    src="{{ $slide['url'] }}"
                    alt="{{ $slide['caption'] }}"
                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                    decoding="async"
                    @if ($loop->first) fetchpriority="high" @endif
                  />
                  <figcaption class="carousel-caption">{{ $slide['caption'] }}</figcaption>
                </figure>
              @endforeach
            </div>
          </div>
          <div class="carousel-nav" aria-hidden="false">
            <button type="button" class="carousel-btn carousel-prev" aria-label="Previous slide">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button type="button" class="carousel-btn carousel-next" aria-label="Next slide">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
            </button>
          </div>
          <div class="carousel-dots" role="tablist" aria-label="Slide selection">
            @foreach ($gallerySlides as $slide)
              <button
                type="button"
                class="carousel-dot"
                role="tab"
                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                aria-label="Show slide {{ $loop->iteration }}"
              ></button>
            @endforeach
          </div>
          <p class="carousel-live" aria-live="polite"></p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="services">
    <div class="container">
      <div class="section-head center">
        <p class="section-kicker">Service catalogue</p>
        <h2>Bookings we currently support</h2>
        <p>Atup-atup Falls hiking permits are available now. Additional municipal services will be added to this portal as programs expand.</p>
      </div>
      <div class="featured-service">
        <div class="featured-service-content">
          <h3>Atup-atup Falls · Nag Atup hiking permits</h3>
          <p>Our most popular destination. View daily availability, complete required declarations online, and receive confirmation from the municipal tourism desk.</p>
        </div>
        <div class="featured-service-actions">
          <a href="{{ route('atup.overview') }}" class="btn btn-light">View availability</a>
          @auth
            <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('bookings.create') }}" class="btn btn-ghost">{{ auth()->user()->is_admin ? 'Admin dashboard' : 'Start booking' }}</a>
          @else
            <a href="{{ route('register') }}" class="btn btn-ghost">Register to book</a>
          @endauth
        </div>
      </div>
      <div class="cards">
        <article class="card is-soon">
          <span class="card-badge soon">Coming soon</span>
          <div class="card-visual">
            <img
              src="{{ asset('images/IMG_20260319_095538_611.jpg') }}"
              alt="Clear pool and small waterfall in lush forest near Aborlan"
              width="640"
              height="400"
              loading="lazy"
              decoding="async"
            />
          </div>
          <div class="card-body">
            <div class="card-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h3>Office appointments</h3>
            <p>Plan visits to municipal offices for permits, certifications, and other frontline transactions.</p>
            <span class="card-link" aria-hidden="true">Not yet available online</span>
          </div>
        </article>
        <article class="card is-soon">
          <span class="card-badge soon">Coming soon</span>
          <div class="card-visual">
            <img
              src="{{ asset('images/IMG_20260319_110328_673.jpg') }}"
              alt="Dramatic rock gorge and forest canopy in Palawan"
              width="640"
              height="400"
              loading="lazy"
              decoding="async"
            />
          </div>
          <div class="card-body">
            <div class="card-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <h3>Facility reservations</h3>
            <p>Request use of community halls, multi-purpose venues, and other municipally managed spaces.</p>
            <span class="card-link" aria-hidden="true">Not yet available online</span>
          </div>
        </article>
        <article class="card is-available">
          <span class="card-badge available">Available now</span>
          <a class="card-link-stretch" href="{{ route('atup.overview') }}" aria-label="Explore Atup-atup Falls hiking permits"></a>
          <div class="card-visual">
            <img
              src="{{ asset('images/IMG_20260319_102401_340.jpg') }}"
              alt="Visitors trekking through a green canyon trail"
              width="640"
              height="400"
              loading="lazy"
              decoding="async"
            />
          </div>
          <div class="card-body">
            <div class="card-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="10" r="3"/><path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 6.9 8 11.7z"/></svg>
            </div>
            <h3>Tourism &amp; local programs</h3>
            <p>Book guided hikes to Atup-atup Falls and coordinate tourism-related activities supported by the LGU.</p>
            <a class="card-link" href="{{ route('atup.overview') }}">Explore Atup-atup Falls →</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="section" id="visitor-guide">
    <div class="container">
      <div class="section-head center">
        <p class="section-kicker">Nag Atup &amp; visitor access</p>
        <h2>Visitor guide — before you go</h2>
        <p>Follow this order so your paperwork matches the official forms published by the LGU. View each PDF below on screen; print or complete as instructed, and bring signed copies when required.</p>
      </div>
      <div class="guide-layout">
        <ol class="guide-list">
          <li class="guide-item" id="guide-step-1">
            <h3>Review Nag Atup information</h3>
            <p>Understand site orientation, local rules, fees (if any), and what the LGU expects from guests.</p>
            <p class="guide-doc">Document: <strong>NAG ATUP INFORMATION.pdf</strong> — <a href="{{ route('docs.view', ['f' => 'NAG ATUP INFORMATION.pdf']) }}">View in page</a></p>
          </li>
          <li class="guide-item" id="guide-step-2">
            <h3>Complete your health declaration</h3>
            <p>Declare health status truthfully. Keep a copy for your records and submit as instructed by the municipal or tourism desk.</p>
            <p class="guide-doc">Document: <strong>HEALTH DECLARATION FORM.pdf</strong> — <a href="{{ route('docs.view', ['f' => 'HEALTH DECLARATION FORM.pdf']) }}">View in page</a></p>
          </li>
          <li class="guide-item" id="guide-step-3">
            <h3>Sign the acknowledgement &amp; waiver of risk</h3>
            <p>Participants in outdoor or adventure activities must acknowledge hazards and sign where the form requires.</p>
            <p class="guide-doc">Document: <strong>ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf</strong> — <a href="{{ route('docs.view', ['f' => 'ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf']) }}">View in page</a></p>
          </li>
          <li class="guide-item" id="guide-step-4">
            <h3>Secure your visitors entry permit</h3>
            <p>File the <strong>Nag-Atup Visitors Entry Permit</strong> and wait for confirmation or endorsement before your visit date, unless the LGU directs otherwise.</p>
            <p class="guide-doc">Document: <strong>NAG-ATUP Visitors Entry Permit.pdf</strong> — <a href="{{ route('docs.view', ['f' => 'NAG-ATUP Visitors Entry Permit.pdf']) }}">View in page</a></p>
          </li>
          <li class="guide-item" id="guide-step-5">
            <h3>Arrive prepared</h3>
            <p>Bring valid ID, printed permits, and any equipment or companions noted in your application. Follow briefing instructions from guides or marshals.</p>
          </li>
        </ol>
        <aside class="guide-sidebar" aria-label="Guide shortcuts">
          <h3>Jump to step</h3>
          <ul>
            <li><a href="#guide-step-1">1 · Nag Atup information</a></li>
            <li><a href="#guide-step-2">2 · Health declaration</a></li>
            <li><a href="#guide-step-3">3 · Waiver of risk</a></li>
            <li><a href="#guide-step-4">4 · Entry permit</a></li>
            <li><a href="#guide-step-5">5 · Arrive prepared</a></li>
          </ul>
        </aside>
      </div>
      <p class="guide-note">
        <strong>Legal wording.</strong> Binding terms, declarations, and signatures are defined in the official PDFs (view in page or open in a PDF reader). Authoritative editable copies may be maintained as Word files offline by the LGU. Replace this summary with verbatim LGU text whenever your legal office finalizes copy for the web.
      </p>
    </div>
  </section>

  <section class="section alt" id="how">
    <div class="container">
      <div class="section-head center">
        <p class="section-kicker">Process</p>
        <h2>How online booking works</h2>
        <p>A straightforward workflow so residents and visitors can complete requests with minimal repetition.</p>
      </div>
      <div class="steps">
        <div class="step">
          <div class="step-num">1</div>
          <h3>Choose your service</h3>
          <p>Identify the office, facility, or program you need. Review documentary requirements where applicable.</p>
        </div>
        <div class="step">
          <div class="step-num">2</div>
          <h3>Select date &amp; time</h3>
          <p>Pick from published slots. Availability reflects office capacity and venue rules.</p>
        </div>
        <div class="step">
          <div class="step-num">3</div>
          <h3>Submit &amp; confirm</h3>
          <p>Provide accurate contact details. You will receive acknowledgment and next steps through the channel you select.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="forms">
    <div class="container">
      <div class="section-head center">
        <p class="section-kicker">Downloads</p>
        <h2>Official forms &amp; permits</h2>
        <p>PDF format for reliable in-browser viewing. Use “Open PDF in new tab” to download or print. Original <code>.docx</code> files remain in <strong>public/docs</strong> for staff editing when forms change.</p>
      </div>
      <div class="doc-grid">
        @foreach ($officialDocs as $doc)
          <article class="doc-card">
            <span class="doc-step">{{ $doc['step'] }}</span>
            <h3>{{ $doc['title'] }}</h3>
            <p>{{ $doc['desc'] }}</p>
            <a class="btn-doc" href="{{ route('docs.view', ['f' => $doc['file']]) }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              View in page
            </a>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="cta-wrap" id="contact">
    <div class="container">
      <div class="cta" style="--cta-image: url('{{ asset('images/IMG_20260319_120504_337.jpg') }}');">
        <h2>Need help with your booking?</h2>
        <p>
          For account access, permit changes, or technical issues with this portal, contact the municipal
          information desk during regular office hours.
        </p>
        <dl class="contact-grid">
          <div class="contact-item">
            <dt>Office hours</dt>
            <dd>Monday – Friday, 8:00 AM – 5:00 PM</dd>
          </div>
          <div class="contact-item">
            <dt>Front desk</dt>
            <dd>Municipal Information Office<br />Municipality of Aborlan, Palawan</dd>
          </div>
          <div class="contact-item">
            <dt>Phone</dt>
            <dd><a href="tel:+630000000000">(XXX) XXX-XXXX</a></dd>
          </div>
          <div class="contact-item">
            <dt>Email</dt>
            <dd><a href="mailto:info@aborlan.gov.ph">info@aborlan.gov.ph</a></dd>
          </div>
        </dl>
        <div class="cta-actions">
          <a href="{{ route('atup.overview') }}" class="btn btn-light">Book Atup-atup hike</a>
          <a href="#forms" class="btn btn-ghost">View official forms</a>
        </div>
      </div>
    </div>
  </section>
  </main>

  <a href="{{ route('atup.overview') }}" class="fab-book">Book a hike</a>
  <button type="button" class="back-to-top" id="back-to-top" aria-label="Back to top">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 15l-6-6-6 6"/></svg>
  </button>

  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="footer-brand-lockup">
            <img
              class="footer-logo"
              src="{{ asset('images/Logo.png') }}"
              alt=""
              width="130"
              height="88"
              decoding="async"
            />
            <div>
              <p class="name">{{ config('app.name') }}</p>
              <p>Official booking and information channel of the Local Government of Aborlan, Province of Palawan, Philippines.</p>
            </div>
          </div>
        </div>
        <div class="footer-col">
          <h3>Quick links</h3>
          <ul>
            <li><a href="#gallery">Gallery</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#visitor-guide">Visitor guide</a></li>
            <li><a href="#forms">Forms</a></li>
            <li><a href="#how">How it works</a></li>
            <li><a href="#contact">Support</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h3>Transparency</h3>
          <ul>
            <li><a href="#contact">Contact &amp; support</a></li>
            <li><a href="#forms">Official forms</a></li>
            <li><a href="{{ route('atup.overview') }}">Atup-atup Falls</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        &copy; {{ date('Y') }} Municipality of Aborlan. All rights reserved.<br />
        Republic of the Philippines · National Government Portal standards apply where relevant.
      </div>
    </div>
  </footer>
  <script>
    (function () {
      var header = document.getElementById("site-header");
      var backToTop = document.getElementById("back-to-top");

      function onScrollUi() {
        if (header) {
          header.classList.toggle("is-scrolled", window.scrollY > 24);
        }
        if (backToTop) {
          backToTop.classList.toggle("is-visible", window.scrollY > 480);
        }
      }

      window.addEventListener("scroll", onScrollUi, { passive: true });
      onScrollUi();

      if (backToTop) {
        backToTop.addEventListener("click", function () {
          window.scrollTo({ top: 0, behavior: window.matchMedia("(prefers-reduced-motion: reduce)").matches ? "auto" : "smooth" });
        });
      }
    })();

    (function () {
      var btn = document.getElementById("nav-toggle");
      var cluster = document.getElementById("nav-cluster");
      if (!btn || !cluster) return;

      function setNavOpen(open) {
        cluster.classList.toggle("is-open", open);
        btn.setAttribute("aria-expanded", open ? "true" : "false");
        btn.querySelector(".sr-only").textContent = open ? "Close menu" : "Open menu";
      }

      btn.addEventListener("click", function () {
        setNavOpen(!cluster.classList.contains("is-open"));
      });

      cluster.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener("click", function () {
          if (window.matchMedia("(max-width: 900px)").matches) {
            setNavOpen(false);
          }
        });
      });

      window.addEventListener("resize", function () {
        if (window.innerWidth > 900) {
          setNavOpen(false);
        }
      });

      var sectionIds = ["gallery", "services", "visitor-guide", "how", "forms", "contact"];
      var sectionTops = [];
      var stickyOffset = 0;
      var activeSectionId = "";
      var scrollScheduled = false;

      function measureSections() {
        var topBar = document.querySelector(".top-bar");
        var header = document.querySelector("header");
        stickyOffset = (topBar ? topBar.offsetHeight : 0) + (header ? header.offsetHeight : 0) + 12;
        sectionTops = sectionIds.map(function (id) {
          var el = document.getElementById(id);
          if (!el) return null;
          return { id: id, top: el.getBoundingClientRect().top + window.scrollY };
        }).filter(Boolean);
      }

      function syncSectionNavActive() {
        var line = window.scrollY + stickyOffset;
        var current = "";
        for (var i = 0; i < sectionTops.length; i++) {
          if (sectionTops[i].top <= line) current = sectionTops[i].id;
        }
        if (current === activeSectionId) return;
        activeSectionId = current;
        cluster.querySelectorAll('a[href^="#"]').forEach(function (a) {
          var href = a.getAttribute("href") || "";
          var id = href.indexOf("#") === 0 ? href.slice(1) : href.replace(/^.*#/, "");
          a.classList.toggle("is-active", id === current && current !== "");
        });
      }

      function scheduleSectionNavSync() {
        if (scrollScheduled) return;
        scrollScheduled = true;
        requestAnimationFrame(function () {
          scrollScheduled = false;
          syncSectionNavActive();
        });
      }

      function remeasureAndSync() {
        measureSections();
        activeSectionId = "";
        syncSectionNavActive();
      }

      window.addEventListener("scroll", scheduleSectionNavSync, { passive: true });
      window.addEventListener("resize", remeasureAndSync);
      window.addEventListener("hashchange", remeasureAndSync);
      window.addEventListener("load", function () {
        remeasureAndSync();
        window.setTimeout(remeasureAndSync, 150);
      });
      if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", remeasureAndSync);
      } else {
        remeasureAndSync();
      }
    })();

    (function () {
      var root = document.querySelector("[data-carousel]");
      if (!root) return;
      var track = root.querySelector(".carousel-track");
      var slides = root.querySelectorAll(".carousel-slide");
      var dots = root.querySelectorAll(".carousel-dot");
      var live = root.querySelector(".carousel-live");
      var prev = root.querySelector(".carousel-prev");
      var next = root.querySelector(".carousel-next");
      var i = 0;
      var n = slides.length;
      var timer;

      function caption(idx) {
        var s = slides[idx];
        return s && s.dataset.caption ? s.dataset.caption : "";
      }

      function go(idx) {
        i = (idx + n) % n;
        track.style.transform = "translateX(-" + i * 100 + "%)";
        dots.forEach(function (d, j) {
          d.setAttribute("aria-selected", j === i ? "true" : "false");
        });
        if (live) {
          live.textContent = "Slide " + (i + 1) + " of " + n + ": " + caption(i);
        }
      }

      function start() {
        stop();
        timer = setInterval(function () {
          go(i + 1);
        }, 6500);
      }

      function stop() {
        if (timer) clearInterval(timer);
        timer = null;
      }

      if (prev) prev.addEventListener("click", function () { go(i - 1); });
      if (next) next.addEventListener("click", function () { go(i + 1); });
      dots.forEach(function (d, j) {
        d.addEventListener("click", function () { go(j); });
      });
      root.addEventListener("mouseenter", stop);
      root.addEventListener("mouseleave", start);
      root.addEventListener("focusin", stop);
      root.addEventListener("focusout", function (e) {
        if (!root.contains(e.relatedTarget)) start();
      });
      go(0);
      start();
    })();
  </script>
  @auth
    @include('partials.logout-confirm-modal')
    <script src="{{ asset('js/logout-confirm.js') }}" defer></script>
  @endauth
</body>
</html>

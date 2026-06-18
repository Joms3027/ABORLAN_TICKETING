@extends('layouts.portal')

@section('title', 'Atup-atup Falls — overview')

@php
  $today = now()->toDateString();
  $daysWithSlots = collect($availability)->filter(fn ($r) => $r['accepts_new_bookings'])->count();
  $nextOpen = collect($availability)->first(fn ($r) => $r['accepts_new_bookings']);
  $todayRow = collect($availability)->first(fn ($r) => $r['date'] === $today);
  $bookCtaUrl = auth()->check()
    ? (auth()->user()->is_admin ? route('admin.dashboard') : route('bookings.create'))
    : route('register');
  $bookCtaLabel = auth()->check()
    ? (auth()->user()->is_admin ? 'Admin dashboard' : 'Book a hiking permit')
    : 'Register to book';
@endphp

@push('head')
  <style>
    html:has(.atup-overview) { scroll-behavior: smooth; }
    .atup-overview {
      --atup-sticky-top: 7.25rem;
      --atup-nav-h: 3.25rem;
      min-width: 0;
      max-width: 100%;
      overflow-x: clip;
    }

    .atup-hero-lead {
      font-size: 1rem;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.96);
      max-width: 42rem;
      margin: -0.25rem 0 0.85rem;
      line-height: 1.45;
    }

    .atup-hero-meta {
      display: flex; flex-wrap: wrap; gap: 0.5rem 0.75rem;
      margin: 1rem 0 1.35rem;
    }
    .atup-pill {
      display: inline-flex; align-items: center; gap: 0.4rem;
      padding: 0.35rem 0.75rem;
      border-radius: 999px;
      font-size: 0.78rem; font-weight: 600;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.35);
      color: rgba(255, 255, 255, 0.95);
    }
    .atup-pill svg { flex-shrink: 0; opacity: 0.9; }

    .atup-jump {
      display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem 0.5rem;
      margin-top: 0.35rem;
      padding-top: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.22);
    }
    .atup-jump .atup-jump-label {
      width: 100%;
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: rgba(255, 255, 255, 0.65);
      margin-bottom: 0.15rem;
    }
    .atup-jump a {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      font-size: 0.8125rem; font-weight: 600;
      color: var(--navy);
      text-decoration: none;
      padding: 0.45rem 0.85rem;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.92);
      border: 1px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
      transition: background 0.15s var(--ease), transform 0.15s var(--ease), box-shadow 0.15s var(--ease);
    }
    .atup-jump a:hover {
      background: #fff;
      transform: translateY(-1px);
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.16);
    }
    .atup-jump a:focus-visible {
      outline: 2px solid var(--gold-light); outline-offset: 3px;
    }

    .atup-section-title {
      font-size: 0.72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--text-muted);
      margin: 0 0 0.65rem;
    }

    .atup-summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 0.75rem;
      margin-bottom: 0.5rem;
    }
    .atup-summary-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1rem 1.1rem;
      box-shadow: var(--shadow-sm);
      display: flex;
      flex-direction: column;
      gap: 0.15rem;
      min-height: 100%;
      transition: box-shadow 0.2s var(--ease), border-color 0.2s var(--ease);
    }
    .atup-summary-card:hover {
      box-shadow: var(--shadow);
      border-color: rgba(192, 38, 211, 0.35);
    }
    .atup-summary-card .k {
      font-size: 0.7rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.06em;
      color: var(--text-muted);
      margin-bottom: 0.15rem;
    }
    .atup-summary-card .v {
      font-size: 1.35rem; font-weight: 700;
      color: var(--navy); line-height: 1.15;
    }
    .atup-summary-card .sub {
      font-size: 0.78rem; color: var(--text-muted);
      margin-top: 0.2rem; line-height: 1.4;
    }
    .atup-summary-foot {
      font-size: 0.8125rem;
      color: var(--text-muted);
      margin: 0 0 1.5rem;
      padding-left: 0.15rem;
      max-width: 65ch;
      line-height: 1.45;
    }

    .atup-layout {
      display: grid;
      grid-template-columns: minmax(0, 1fr);
      gap: 1.25rem;
      align-items: start;
      min-width: 0;
      max-width: 100%;
    }
    .atup-col-main {
      order: 2;
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
      min-width: 0;
      max-width: 100%;
    }
    .atup-col-main > .panel {
      margin-top: 0 !important;
      min-width: 0;
      max-width: 100%;
      overflow-x: clip;
    }
    .atup-sidebar {
      order: 1;
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
      min-width: 0;
      max-width: 100%;
    }
    .atup-sidebar .panel {
      min-width: 0;
      max-width: 100%;
      overflow-x: hidden;
    }
    @media (min-width: 900px) {
      .atup-layout { grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); }
      .atup-col-main { order: 1; }
      .atup-sidebar {
        order: 2;
        position: sticky;
        top: var(--atup-sticky-top);
        align-self: start;
        max-height: calc(100vh - var(--atup-sticky-top) - 1.5rem);
        overflow-y: auto;
        overscroll-behavior: contain;
      }
    }

    .atup-section-anchor {
      scroll-margin-top: var(--atup-sticky-top);
    }

    .atup-panel-lead {
      color: var(--text-muted);
      font-size: 0.9rem;
      line-height: 1.5;
      margin: -0.35rem 0 1rem;
    }

    .atup-gallery-wrap {
      position: relative;
      min-width: 0;
      max-width: 100%;
      overflow: hidden;
    }
    .atup-highlight-scroller {
      display: flex;
      gap: 1rem;
      width: 100%;
      max-width: 100%;
      min-width: 0;
      overflow-x: auto;
      overflow-y: hidden;
      padding-bottom: 0.35rem;
      margin: 0 -0.25rem;
      padding-left: 0.25rem;
      padding-right: 0.25rem;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
    }
    .atup-highlight-scroller .highlight {
      flex: 0 0 min(280px, 85vw);
      scroll-snap-align: start;
    }
    @media (min-width: 700px) {
      .atup-highlight-scroller {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        overflow: visible;
        padding: 0; margin: 0;
        scroll-snap-type: none;
      }
      .atup-highlight-scroller .highlight { flex: none; }
    }

    .atup-steps { list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem; }
    .atup-steps li {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 0.85rem 1rem;
      align-items: start;
    }
    .atup-steps .num {
      width: 2rem; height: 2rem;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.875rem; font-weight: 700;
      background: linear-gradient(135deg, var(--teal-muted) 0%, rgba(250, 204, 21, 0.35) 100%);
      color: var(--navy);
      border: 2px solid var(--border);
      flex-shrink: 0;
    }
    .atup-steps .text { color: var(--text); font-size: 0.9375rem; line-height: 1.55; }
    .atup-steps .text strong { color: var(--navy); }

    .atup-gallery-scroll-hint {
      display: flex;
      align-items: center;
      gap: 0.35rem;
      font-size: 0.78rem;
      font-weight: 600;
      color: var(--text-muted);
      margin: 0 0 0.65rem;
      padding: 0.35rem 0.6rem;
      background: var(--teal-muted);
      border-radius: var(--radius-sm);
      width: fit-content;
    }
    .atup-gallery-scroll-hint svg { flex-shrink: 0; opacity: 0.85; }
    @media (min-width: 700px) {
      .atup-gallery-scroll-hint { display: none; }
    }

    .atup-avail-legend {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem 0.75rem;
      margin: -0.25rem 0 0.85rem;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--text-muted);
    }
    .atup-avail-legend span.atup-legend-item {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }
    .atup-avail-legend .atup-lg-dot {
      width: 0.55rem;
      height: 0.55rem;
      border-radius: 999px;
      flex-shrink: 0;
    }
    .atup-avail-legend .lg-open { background: var(--success); }
    .atup-avail-legend .lg-tight { background: var(--warn); }
    .atup-avail-legend .lg-full { background: var(--danger); }

    .atup-slot-cta { margin-top: 1rem; }

    .atup-avail-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr);
      gap: 0.45rem;
      padding: 0.65rem 0.85rem;
      border-radius: var(--radius-sm);
      background: #fdf4ff;
      border: 1px solid var(--border);
      font-size: 0.875rem;
    }
    .atup-avail-row.full { background: #fee2e2; border-color: #fca5a5; }
    .atup-avail-row.tight { background: #fef3c7; border-color: #fcd34d; }
    .atup-avail-row.today {
      box-shadow: 0 0 0 2px var(--teal);
      border-color: rgba(192, 38, 211, 0.45);
    }
    .atup-avail-row.custom .row-top strong::before {
      content: "★ ";
      color: var(--teal);
    }
    .atup-avail-row .row-top {
      display: flex;
      flex-wrap: wrap;
      align-items: baseline;
      justify-content: space-between;
      gap: 0.35rem 0.75rem;
    }
    .atup-avail-row .row-top > div:first-child {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.35rem;
      min-width: 0;
      flex: 1 1 8rem;
    }
    .atup-avail-row .row-top > div:first-child strong {
      word-break: break-word;
      line-height: 1.3;
    }
    .atup-avail-row .row-stats {
      text-align: right;
      min-width: 0;
      flex: 1 1 6rem;
    }
    .atup-avail-row strong { color: var(--navy); }
    .avail-list {
      width: 100%;
      max-width: 100%;
      overflow: hidden;
    }
    .atup-avail-row .badge-today {
      font-size: 0.65rem; font-weight: 800;
      text-transform: uppercase; letter-spacing: 0.05em;
      padding: 0.15rem 0.45rem;
      border-radius: 999px;
      background: var(--teal);
      color: #fff;
    }
    .atup-avail-row .meter-wrap {
      height: 6px;
      border-radius: 999px;
      background: rgba(42, 10, 50, 0.08);
      overflow: hidden;
      margin-top: 0.15rem;
    }
    .atup-avail-row .meter {
      height: 100%;
      border-radius: 999px;
      background: linear-gradient(90deg, var(--success), #4ade80);
      transition: width 0.4s var(--ease);
    }
    .atup-avail-row.full .meter {
      background: linear-gradient(90deg, var(--danger), #f87171);
    }
    .atup-avail-row.tight .meter {
      background: linear-gradient(90deg, var(--warn), #fbbf24);
    }

    .atup-avail-foot {
      margin-top: 0.75rem;
      padding-top: 0.85rem;
      border-top: 1px dashed var(--border);
      font-size: 0.8125rem;
      color: var(--text-muted);
      line-height: 1.45;
    }

    .atup-reminders {
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 0.65rem;
    }
    .atup-reminders li {
      position: relative;
      padding: 0.65rem 0.85rem 0.65rem 2.35rem;
      background: linear-gradient(135deg, #fffbeb 0%, #fdf4ff 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-size: 0.875rem;
      color: var(--text-muted);
      line-height: 1.45;
    }
    .atup-reminders li::before {
      content: "";
      position: absolute;
      left: 0.85rem; top: 0.85rem;
      width: 0.5rem; height: 0.5rem;
      border-radius: 50%;
      background: var(--teal);
      box-shadow: 0 0 0 3px var(--teal-muted);
    }
    .atup-reminders strong { color: var(--navy); }

    /* Sticky section nav (below hero) */
    .atup-subnav-wrap {
      position: sticky;
      top: calc(var(--atup-sticky-top) - var(--atup-nav-h) - 0.5rem);
      z-index: 40;
      margin: -0.5rem 0 1.25rem;
      padding: 0.35rem 0;
      background: linear-gradient(180deg, rgba(252, 231, 243, 0.97) 70%, rgba(252, 231, 243, 0));
      backdrop-filter: blur(8px);
    }
    .atup-subnav {
      display: flex;
      gap: 0.4rem;
      overflow-x: auto;
      padding: 0.25rem 0.15rem 0.5rem;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .atup-subnav::-webkit-scrollbar { display: none; }
    .atup-subnav a {
      flex: 0 0 auto;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--navy);
      text-decoration: none;
      padding: 0.5rem 0.95rem;
      border-radius: 999px;
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      white-space: nowrap;
      transition: background 0.15s var(--ease), color 0.15s var(--ease), box-shadow 0.15s var(--ease), border-color 0.15s var(--ease);
    }
    .atup-subnav a:hover {
      border-color: rgba(192, 38, 211, 0.45);
      background: var(--teal-muted);
    }
    .atup-subnav a.is-active {
      background: linear-gradient(135deg, var(--teal) 0%, #a855f7 100%);
      color: #fff;
      border-color: transparent;
      box-shadow: 0 2px 12px rgba(192, 38, 211, 0.35);
    }
    .atup-subnav a:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    .atup-summary-card .icon-wrap {
      width: 2.25rem;
      height: 2.25rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--teal-muted);
      color: var(--teal-hover);
      margin-bottom: 0.35rem;
    }
    .atup-summary-card .icon-wrap svg { display: block; }

    .atup-status-banner {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.65rem 1rem;
      padding: 0.85rem 1rem;
      margin-bottom: 1.25rem;
      border-radius: var(--radius);
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow-sm);
    }
    .atup-status-banner .status-dot {
      width: 0.65rem;
      height: 0.65rem;
      border-radius: 50%;
      flex-shrink: 0;
      background: var(--success);
      box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.2);
    }
    .atup-status-banner.is-warn .status-dot {
      background: var(--warn);
      box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.2);
    }
    .atup-status-banner.is-full .status-dot {
      background: var(--danger);
      box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.2);
    }
    .atup-status-banner .status-text {
      flex: 1;
      min-width: 12rem;
      font-size: 0.9rem;
      color: var(--text);
      line-height: 1.45;
    }
    .atup-status-banner .status-text strong { color: var(--navy); }

    .atup-week-strip {
      display: grid;
      grid-template-columns: repeat(7, minmax(0, 1fr));
      gap: 0.35rem;
      margin-bottom: 1rem;
      width: 100%;
      max-width: 100%;
      overflow: hidden;
    }
    .atup-day-pill {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.15rem;
      min-width: 0;
      max-width: 100%;
      padding: 0.5rem 0.15rem;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border);
      background: #fff;
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--text-muted);
      text-decoration: none;
      transition: transform 0.15s var(--ease), box-shadow 0.15s var(--ease), border-color 0.15s var(--ease);
    }
    .atup-day-pill .dow {
      font-size: 0.6rem;
      opacity: 0.85;
      max-width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .atup-day-pill .dom {
      font-size: clamp(0.8rem, 2.8vw, 1rem);
      font-weight: 800;
      color: var(--navy);
      line-height: 1;
    }
    .atup-day-pill .cap {
      width: 0.45rem;
      height: 0.45rem;
      border-radius: 50%;
      margin-top: 0.1rem;
    }
    .atup-day-pill.open .cap { background: var(--success); }
    .atup-day-pill.tight .cap { background: var(--warn); }
    .atup-day-pill.full .cap { background: var(--danger); }
    .atup-day-pill.today {
      border-color: var(--teal);
      box-shadow: 0 0 0 2px rgba(192, 38, 211, 0.25);
    }
    @media (hover: hover) {
      .atup-day-pill:hover:not(.full) {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        border-color: rgba(192, 38, 211, 0.4);
      }
    }
    .atup-day-pill.full { opacity: 0.72; cursor: default; }
    .atup-day-pill:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }

    .atup-avail-toggle {
      display: none;
      width: 100%;
      margin-top: 0.65rem;
      padding: 0.55rem 0.85rem;
      font-family: inherit;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--navy);
      background: var(--teal-muted);
      border: 1px dashed rgba(192, 38, 211, 0.35);
      border-radius: var(--radius-sm);
      cursor: pointer;
    }
    .atup-avail-toggle:hover { background: rgba(192, 38, 211, 0.2); }
    .atup-avail-toggle:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }
    @media (max-width: 699px) {
      .atup-avail-toggle { display: block; }
      .avail-list.is-collapsed .atup-avail-row:nth-child(n+5) { display: none; }
    }

    .highlight { cursor: pointer; }
    .highlight .img-wrap {
      position: relative;
      overflow: hidden;
    }
    .highlight .img-wrap::after {
      content: "View";
      position: absolute;
      inset: auto 0.5rem 0.5rem auto;
      font-size: 0.68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      padding: 0.25rem 0.5rem;
      border-radius: 999px;
      background: rgba(42, 10, 50, 0.75);
      color: #fff;
      opacity: 0;
      transform: translateY(4px);
      transition: opacity 0.2s var(--ease), transform 0.2s var(--ease);
    }
    .highlight:hover .img-wrap::after,
    .highlight:focus-within .img-wrap::after {
      opacity: 1;
      transform: translateY(0);
    }
    .atup-highlight-scroller .highlight img {
      height: 220px;
      transition: transform 0.35s var(--ease);
    }
    .highlight:hover img { transform: scale(1.04); }
    .atup-highlight-scroller .highlight img { height: 220px; }

    .atup-lightbox {
      position: fixed;
      inset: 0;
      z-index: 200;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      background: rgba(26, 10, 31, 0.92);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.25s var(--ease), visibility 0.25s var(--ease);
    }
    .atup-lightbox.is-open {
      opacity: 1;
      visibility: visible;
    }
    .atup-lightbox-dialog {
      position: relative;
      max-width: min(920px, 100%);
      width: 100%;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }
    .atup-lightbox img {
      width: 100%;
      max-height: min(70vh, 640px);
      object-fit: contain;
      border-radius: var(--radius);
      background: #000;
    }
    .atup-lightbox-caption {
      color: rgba(255, 255, 255, 0.92);
      text-align: center;
      font-size: 0.95rem;
      line-height: 1.45;
      padding: 0 2.5rem;
    }
    .atup-lightbox-caption strong {
      display: block;
      font-size: 1.1rem;
      margin-bottom: 0.25rem;
      color: #fff;
    }
    .atup-lightbox-close,
    .atup-lightbox-nav {
      position: absolute;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      cursor: pointer;
      font-family: inherit;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      transition: background 0.15s var(--ease);
    }
    .atup-lightbox-close:hover,
    .atup-lightbox-nav:hover { background: rgba(255, 255, 255, 0.28); }
    .atup-lightbox-close {
      top: -0.25rem;
      right: 0;
      width: 2.5rem;
      height: 2.5rem;
      font-size: 1.5rem;
      line-height: 1;
    }
    .atup-lightbox-nav {
      top: 50%;
      transform: translateY(-50%);
      width: 2.75rem;
      height: 2.75rem;
    }
    .atup-lightbox-prev { left: -0.25rem; }
    .atup-lightbox-next { right: -0.25rem; }
    @media (max-width: 640px) {
      .atup-lightbox-prev { left: 0.25rem; }
      .atup-lightbox-next { right: 0.25rem; }
      .atup-lightbox-close { top: 0.25rem; right: 0.25rem; }
    }

    .atup-location-cards {
      display: grid;
      gap: 0.75rem;
      margin-top: 0.25rem;
    }
    @media (min-width: 560px) {
      .atup-location-cards { grid-template-columns: 1fr 1fr; }
    }
    .atup-loc-card {
      display: flex;
      gap: 0.75rem;
      align-items: flex-start;
      padding: 0.85rem 1rem;
      background: linear-gradient(135deg, #fdf4ff 0%, #fffbeb 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-size: 0.875rem;
      line-height: 1.45;
    }
    .atup-loc-card .loc-icon {
      flex-shrink: 0;
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--teal-muted);
      color: var(--teal-hover);
    }
    .atup-loc-card strong { color: var(--navy); display: block; margin-bottom: 0.15rem; }
    .atup-loc-card a {
      font-size: 0.78rem;
      font-weight: 600;
      margin-top: 0.35rem;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }

    .atup-mobile-cta {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 60;
      padding: 0.65rem 1rem calc(0.65rem + env(safe-area-inset-bottom, 0px));
      background: rgba(255, 255, 255, 0.96);
      border-top: 1px solid var(--border);
      box-shadow: 0 -8px 24px rgba(88, 28, 135, 0.12);
      backdrop-filter: blur(10px);
    }
    .atup-mobile-cta .btn { width: 100%; }
    @media (max-width: 899px) {
      .atup-mobile-cta { display: block; }
      .atup-overview { padding-bottom: 4.5rem; }
    }

    .atup-back-top {
      position: fixed;
      bottom: calc(5rem + env(safe-area-inset-bottom, 0px));
      right: 1rem;
      z-index: 55;
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--navy);
      box-shadow: var(--shadow);
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transform: translateY(8px);
      transition: opacity 0.2s var(--ease), visibility 0.2s var(--ease), transform 0.2s var(--ease), background 0.15s var(--ease);
    }
    .atup-back-top.is-visible {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .atup-back-top:hover { background: var(--gold-light); }
    .atup-back-top:focus-visible {
      outline: 2px solid var(--teal);
      outline-offset: 2px;
    }
    @media (min-width: 900px) {
      .atup-back-top { bottom: 1.5rem; }
    }

    /* Mobile & tablet — /atup-atup */
    @media (max-width: 899px) {
      .atup-overview {
        --atup-sticky-top: 10.75rem;
        --atup-nav-h: 3rem;
      }
      .atup-overview .place-hero {
        padding: clamp(1.35rem, 4.5vw, 2rem);
        margin-bottom: 1.25rem;
      }
      .atup-overview .place-hero h1 {
        max-width: none;
        font-size: clamp(1.35rem, 5.2vw, 1.9rem);
        line-height: 1.2;
        margin-bottom: 0.65rem;
      }
      .atup-overview .place-hero p {
        max-width: 100%;
        font-size: 0.9375rem;
        line-height: 1.55;
      }
      .atup-hero-lead {
        font-size: 0.9375rem;
        margin-bottom: 0.65rem;
      }
      .atup-hero-meta {
        flex-direction: column;
        align-items: stretch;
        gap: 0.45rem;
        margin: 0.85rem 0 1rem;
      }
      .atup-pill {
        width: 100%;
        justify-content: flex-start;
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        line-height: 1.35;
      }
      .atup-overview .cta-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
      }
      .atup-overview .cta-row .btn {
        width: 100%;
        justify-content: center;
        min-height: 44px;
        white-space: normal;
        text-align: center;
      }
      .atup-subnav-wrap {
        top: calc(var(--atup-sticky-top) - var(--atup-nav-h) - 0.35rem);
        margin-left: -2%;
        margin-right: -2%;
        padding-left: 2%;
        padding-right: 2%;
      }
      .atup-subnav a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        padding: 0.55rem 1rem;
      }
      .atup-status-banner {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        padding: 1rem;
      }
      .atup-status-banner .status-text {
        min-width: 0;
        font-size: 0.875rem;
      }
      .atup-status-banner .btn {
        width: 100%;
        justify-content: center;
        min-height: 44px;
      }
      .atup-summary {
        grid-template-columns: 1fr;
      }
      .atup-summary-card {
        padding: 0.9rem 1rem;
      }
      .atup-summary-card .v {
        font-size: 1.2rem;
      }
      .atup-col-main .panel-head,
      .atup-sidebar .panel-head {
        flex-direction: column;
        align-items: stretch;
      }
      .atup-col-main .panel-head .muted,
      .atup-sidebar .panel-head .muted {
        font-size: 0.8125rem;
      }
      .atup-highlight-scroller .highlight {
        flex: 0 0 min(88vw, 300px);
      }
      .atup-highlight-scroller .highlight img {
        height: 190px;
      }
      .atup-slot-cta .btn {
        min-height: 44px;
      }
      .atup-layout {
        min-width: 0;
        max-width: 100%;
        overflow-x: clip;
      }
      .atup-col-main > .panel,
      .atup-sidebar .panel {
        overflow-x: clip;
      }
      .atup-week-strip {
        gap: 0.25rem;
      }
      .atup-avail-row {
        overflow: hidden;
        word-wrap: break-word;
      }
      .atup-avail-row .row-stats .hint {
        word-break: break-word;
      }
    }

    @media (max-width: 520px) {
      .atup-overview {
        --atup-sticky-top: 11.75rem;
        padding-bottom: 5rem;
      }
      .atup-week-strip {
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.2rem;
        overflow: hidden;
      }
      .atup-day-pill {
        padding: 0.35rem 0.08rem;
      }
      .atup-day-pill .dow { font-size: 0.52rem; letter-spacing: 0; }
      .atup-day-pill .dom { font-size: 0.85rem; }
      .atup-day-pill.today {
        box-shadow: 0 0 0 1px rgba(192, 38, 211, 0.35);
      }
      .atup-avail-row .row-top {
        flex-direction: column;
        align-items: stretch;
        gap: 0.35rem;
      }
      .atup-avail-row .row-top > div:first-child {
        flex: none;
        width: 100%;
      }
      .atup-avail-row .row-top > div:first-child strong {
        font-size: 0.875rem;
      }
      .atup-avail-row .row-stats {
        text-align: left;
        width: 100%;
        flex: none;
      }
      .atup-avail-legend {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.35rem;
      }
      .atup-steps li {
        grid-template-columns: auto 1fr;
        gap: 0.65rem 0.75rem;
      }
      .atup-loc-card {
        flex-direction: column;
        align-items: flex-start;
      }
      .atup-lightbox {
        padding: 0.5rem;
        align-items: flex-end;
      }
      .atup-lightbox-dialog {
        max-height: 92vh;
      }
      .atup-lightbox img {
        max-height: min(55vh, 480px);
      }
      .atup-lightbox-caption {
        font-size: 0.875rem;
        padding: 0 0.5rem 0.5rem;
      }
      .atup-back-top {
        right: 0.75rem;
        width: 2.75rem;
        height: 2.75rem;
      }
    }

    @media (max-width: 380px) {
      .atup-overview { --atup-sticky-top: 12.5rem; }
      .atup-overview .place-hero h1 {
        font-size: 1.25rem;
      }
      .atup-pill { font-size: 0.7rem; }
      .atup-week-strip { gap: 0.15rem; }
      .atup-day-pill {
        min-width: 0;
        padding: 0.3rem 0.05rem;
      }
      .atup-day-pill .dow { font-size: 0.48rem; }
      .atup-day-pill .dom { font-size: 0.8rem; }
      .atup-day-pill .cap {
        width: 0.35rem;
        height: 0.35rem;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      html:has(.atup-overview) { scroll-behavior: auto; }
      .atup-avail-row .meter { transition: none; }
      .highlight { transition: none !important; }
      .highlight img { transition: none; }
      .atup-jump a { transition: none; transform: none; }
      .atup-lightbox { transition: none; }
      .atup-back-top { transition: none; }
    }
  </style>
@endpush

@section('content')
  <div class="atup-overview">
    <section
      class="place-hero atup-section-anchor"
      id="top"
      style="--hero-img: url('{{ asset('images/IMG_20260319_112116_746.jpg') }}');"
      aria-labelledby="atup-hero-title"
    >
      <p class="eyebrow">Emerging tourist destination</p>
      <h1 id="atup-hero-title">Atup-atup Falls — Aborlan, Palawan</h1>
      <p class="atup-hero-lead">
        Book your LGU hiking permit here, meet accredited guides at Sitio Manaile (Narra), then trek in as one guided group.
      </p>
      <p>
        Atup-atup Falls lies in <strong>Barangay Culandanum, Aborlan</strong>. Because of the terrain, the
        <strong>official meeting point</strong> for hikers is <strong>Sitio Manaile, Barangay Dumanguena, Narra</strong>.
        Choose your date below—daily caps keep the trail safe and supervised.
      </p>
      <div class="atup-hero-meta" aria-label="Key locations">
        <span class="atup-pill" title="Falls location">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/>
          </svg>
          Falls · Brgy. Culandanum, Aborlan
        </span>
        <span class="atup-pill" title="Where to meet your guides">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="12" cy="12" r="3"/><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
          </svg>
          Meet guides · Sitio Manaile, Narra
        </span>
      </div>
      <div class="cta-row">
        @auth
          @if (auth()->user()->is_admin)
            <a class="btn btn-light" href="{{ route('admin.dashboard') }}">Open admin dashboard</a>
          @else
            <a class="btn btn-light" href="{{ route('bookings.create') }}">Book a hiking permit</a>
            <a class="btn btn-ghost" href="{{ route('bookings.index') }}">View my bookings</a>
          @endif
        @else
          <a class="btn btn-light" href="{{ route('register') }}">Register to book</a>
          <a class="btn btn-ghost" href="{{ route('login') }}">Sign in</a>
        @endauth
      </div>
    </section>

    <nav class="atup-subnav-wrap" aria-label="Page sections">
      <div class="atup-subnav" id="atup-subnav">
        <a href="#slots" data-section="slots">Availability</a>
        <a href="#plan" data-section="plan">How to book</a>
        <a href="#gallery" data-section="gallery">Gallery</a>
        <a href="#locations" data-section="locations">Locations</a>
        <a href="#reminders" data-section="reminders">Reminders</a>
      </div>
    </nav>

    @php
      $bannerClass = 'is-warn';
      $bannerText = 'Checking the next seven days for open permit slots…';
      if ($todayRow) {
        if (! $todayRow['accepts_new_bookings']) {
          $bannerClass = 'is-full';
          $bannerText = '<strong>Today is fully booked.</strong> Choose another date when you apply, or watch for admin-released slots.';
        } elseif ($todayRow['remaining'] <= max(1, (int) ($todayRow['quota'] * 0.2))) {
          $bannerClass = 'is-warn';
          $bannerText = '<strong>Today is filling up.</strong> Only ' . $todayRow['remaining'] . ' of ' . $todayRow['quota'] . ' visitor slots remain—book soon if you plan to hike today.';
        } else {
          $bannerClass = '';
          $bannerText = '<strong>Permits available today.</strong> ' . $todayRow['remaining'] . ' visitor slots still open (' . $todayRow['quota'] . ' daily cap).';
        }
      } elseif ($nextOpen) {
        $bannerClass = '';
        $bannerText = '<strong>No slots today.</strong> Next opening: ' . \Illuminate\Support\Carbon::parse($nextOpen['date'])->format('l, M j') . ' (' . $nextOpen['remaining'] . ' slots left).';
      } else {
        $bannerClass = 'is-full';
        $bannerText = '<strong>No openings in the next 7 days.</strong> Check back later or contact the LGU for special arrangements.';
      }
    @endphp
    <div class="atup-status-banner {{ $bannerClass }}" role="status" aria-live="polite">
      <span class="status-dot" aria-hidden="true"></span>
      <span class="status-text">{!! $bannerText !!}</span>
      @unless (auth()->check() && auth()->user()->is_admin)
        <a class="btn btn-primary btn-sm" href="{{ $bookCtaUrl }}">{{ $bookCtaLabel }}</a>
      @endunless
    </div>

    <h2 class="atup-section-title">At a glance</h2>
    <div class="atup-summary" aria-label="Booking snapshot for the next week">
      <div class="atup-summary-card">
        <span class="icon-wrap" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </span>
        <div class="k">Open days (7-day view)</div>
        <div class="v">{{ $daysWithSlots }}</div>
        <div class="sub">
          {{ $daysWithSlots === 1 ? 'day still accepts' : 'days still accept' }} new permit applications
        </div>
      </div>
      <div class="atup-summary-card">
        <span class="icon-wrap" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
        <div class="k">Next available date</div>
        <div class="v" style="font-size: 1.05rem;">
          @if ($nextOpen)
            {{ \Illuminate\Support\Carbon::parse($nextOpen['date'])->format('D, M j') }}
          @else
            —
          @endif
        </div>
        <div class="sub">
          @if ($nextOpen)
            {{ $nextOpen['remaining'] }} visitor {{ $nextOpen['remaining'] === 1 ? 'slot' : 'slots' }} left
            @if ($nextOpen['max_bookings'] !== null)
              · {{ $nextOpen['bookings_remaining'] ?? 0 }} booking {{ ($nextOpen['bookings_remaining'] ?? 0) === 1 ? 'slot' : 'slots' }} left
            @endif
          @else
            No openings in this window—check again later.
          @endif
        </div>
      </div>
      <div class="atup-summary-card">
        <span class="icon-wrap" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </span>
        <div class="k">Today</div>
        <div class="v" style="font-size: 1.05rem;">
          @if ($todayRow)
            @if ($todayRow['accepts_new_bookings'])
              {{ $todayRow['remaining'] }} open
            @else
              Full
            @endif
          @else
            —
          @endif
        </div>
        <div class="sub">
          @if ($todayRow)
            @if ($todayRow['accepts_new_bookings'])
              of {{ $todayRow['quota'] }} visitor slots
            @else
              No new permits for today
            @endif
          @else
            Outside the 7-day preview
          @endif
        </div>
      </div>
    </div>
    <p class="atup-summary-foot">
      Figures update when you refresh the page. For the exact calendar and application form, use <strong>Book a hiking permit</strong> after you register or sign in.
    </p>

    <div class="atup-layout">
      <div class="atup-col-main">
        <div class="panel atup-section-anchor" id="gallery">
          <div class="panel-head">
            <h2>What you'll see along the trail</h2>
          </div>
          <p class="atup-panel-lead">
            Photos from the pools, gorge, and guided approach. Tap any image to view it full size—swipe sideways on mobile to browse the grid.
          </p>
          <div class="atup-gallery-wrap">
            <p class="atup-gallery-scroll-hint">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
              Swipe for more photos
            </p>
            <div
              class="highlight-grid atup-highlight-scroller"
              role="region"
              aria-label="Trail and falls photo gallery"
              tabindex="0"
            >
              @foreach ($highlights as $index => $h)
                <article
                  class="highlight"
                  role="button"
                  tabindex="0"
                  data-gallery-index="{{ $index }}"
                  data-gallery-src="{{ asset('images/'.$h['image']) }}"
                  data-gallery-title="{{ $h['title'] }}"
                  data-gallery-caption="{{ $h['caption'] }}"
                  aria-label="View photo: {{ $h['title'] }}"
                >
                  <div class="img-wrap">
                    <img src="{{ asset('images/'.$h['image']) }}" alt="" loading="lazy" width="400" height="220" />
                  </div>
                  <div class="body">
                    <h3>{{ $h['title'] }}</h3>
                    <p>{{ $h['caption'] }}</p>
                  </div>
                </article>
              @endforeach
            </div>
          </div>
        </div>

        <div class="panel atup-section-anchor" id="plan">
          <div class="panel-head">
            <h2>Plan your visit</h2>
          </div>
          <p class="atup-panel-lead">Five quick steps from account creation to the trailhead briefing.</p>
          <ol class="atup-steps">
            <li>
              <span class="num" aria-hidden="true">1</span>
              <span class="text">
                <strong>Register an account</strong> on this portal so the LGU can verify visitors and reach you for confirmations.
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">2</span>
              <span class="text">
                <strong>Pick a date</strong> from the booking calendar. Daily slots are limited to protect the falls and ensure safe guided treks.
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">3</span>
              <span class="text">
                <strong>Submit your permit application</strong> with how many people will visit, your mobile number, and an emergency contact.
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">4</span>
              <span class="text">
                <strong>Wait for approval.</strong> An LGU administrator will review and confirm. You will see the status update inside
                @auth
                  <a href="{{ route('bookings.index') }}">My bookings</a>.
                @else
                  <a href="{{ route('login') }}">My bookings</a> after you sign in.
                @endauth
              </span>
            </li>
            <li>
              <span class="num" aria-hidden="true">5</span>
              <span class="text">
                <strong>Arrive at Sitio Manaile, Brgy. Dumanguena, Narra</strong> on your booked date with a valid ID and your booking reference. Local guides will brief you before the trek.
              </span>
            </li>
          </ol>
        </div>

        <div class="panel atup-section-anchor" id="locations">
          <div class="panel-head">
            <h2>Where to go</h2>
          </div>
          <p class="atup-panel-lead">
            The falls are in Aborlan, but all hikers meet accredited guides in Narra before the trek.
          </p>
          <div class="atup-location-cards">
            <div class="atup-loc-card">
              <span class="loc-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10z"/><circle cx="12" cy="11" r="2.5"/></svg>
              </span>
              <div>
                <strong>Atup-atup Falls</strong>
                Barangay Culandanum, Municipality of Aborlan, Palawan
                <a href="https://www.google.com/maps/search/Atup-atup+Falls+Aborlan+Palawan" target="_blank" rel="noopener noreferrer">Open in Maps ↗</a>
              </div>
            </div>
            <div class="atup-loc-card">
              <span class="loc-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2"/></svg>
              </span>
              <div>
                <strong>Trailhead briefing (required)</strong>
                Sitio Manaile, Barangay Dumanguena, Narra, Palawan
                <a href="https://www.google.com/maps/search/Sitio+Manaile+Narra+Palawan" target="_blank" rel="noopener noreferrer">Open in Maps ↗</a>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="atup-sidebar">
        <div class="panel atup-section-anchor" id="slots">
          <div class="panel-head">
            <h2>Slots in the next 7 days</h2>
            <span class="muted">Live quota</span>
          </div>
          <p class="atup-panel-lead">
            Each row is one day. The bar is how much <strong>visitor and booking</strong> capacity is still open (not how crowded the trail feels).
          </p>
          <div class="atup-avail-legend" aria-hidden="true">
            <span class="atup-legend-item"><span class="atup-lg-dot lg-open" aria-hidden="true"></span> Good availability</span>
            <span class="atup-legend-item"><span class="atup-lg-dot lg-tight" aria-hidden="true"></span> Filling up</span>
            <span class="atup-legend-item"><span class="atup-lg-dot lg-full" aria-hidden="true"></span> Fully booked</span>
          </div>
          <div class="atup-week-strip" aria-label="Week at a glance">
            @foreach ($availability as $row)
              @php
                $isToday = $row['date'] === $today;
                $pillCls = ! $row['accepts_new_bookings'] ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : 'open');
                if ($isToday) {
                  $pillCls .= ' today';
                }
                $dateObj = \Illuminate\Support\Carbon::parse($row['date']);
              @endphp
              <a
                href="#avail-{{ $row['date'] }}"
                class="atup-day-pill {{ $pillCls }}"
                @if ($row['accepts_new_bookings'])
                  aria-label="{{ $row['label'] }}, {{ $row['remaining'] }} slots open"
                @else
                  aria-label="{{ $row['label'] }}, fully booked"
                  tabindex="-1"
                @endif
              >
                <span class="dow">{{ $dateObj->format('D') }}</span>
                <span class="dom">{{ $dateObj->format('j') }}</span>
                <span class="cap" aria-hidden="true"></span>
              </a>
            @endforeach
          </div>
          <div class="avail-list is-collapsed" id="atup-avail-list" role="list">
            @foreach ($availability as $row)
              @php
                $isToday = $row['date'] === $today;
                $cls = ! $row['accepts_new_bookings'] ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
                if ($row['custom']) {
                  $cls .= ' custom';
                }
                if ($isToday) {
                  $cls .= ' today';
                }
                $pct = (int) ($row['meter_pct'] ?? 0);
                $ariaRow = $row['label'] . '. ';
                if (! $row['accepts_new_bookings']) {
                    $ariaRow .= 'Fully booked; no new permits.';
                } else {
                    $ariaRow .= $row['remaining'] . ' of ' . $row['quota'] . ' visitor slots still open.';
                    if ($row['max_bookings'] !== null) {
                        $ariaRow .= ' ' . ($row['bookings_remaining'] ?? 0) . ' of ' . $row['max_bookings'] . ' booking slots left.';
                    }
                }
                if ($row['note']) {
                    $ariaRow .= ' Note: ' . $row['note'];
                }
                $meterText = ! $row['accepts_new_bookings']
                  ? 'No permit capacity remaining for this date.'
                  : 'About ' . $pct . ' percent of combined capacity still open.';
              @endphp
              <div id="avail-{{ $row['date'] }}" class="atup-avail-row {{ trim($cls) }}" role="listitem" aria-label="{{ $ariaRow }}">
                <div class="row-top">
                  <div>
                    <strong>{{ $row['label'] }}</strong>
                    @if ($isToday)<span class="badge-today">Today</span>@endif
                  </div>
                  <div class="row-stats">
                    @if (! $row['accepts_new_bookings'])
                      <strong style="color: var(--danger);">Fully booked</strong>
                    @else
                      <strong>{{ $row['remaining'] }}</strong> of <strong>{{ $row['quota'] }}</strong> people open
                      @if ($row['max_bookings'] !== null)
                        <div class="hint" style="font-size:0.72rem; margin-top: 0.15rem;">{{ $row['bookings_remaining'] ?? 0 }} / {{ $row['max_bookings'] }} bookings left</div>
                      @endif
                    @endif
                  </div>
                </div>
                <div
                  class="meter-wrap"
                  role="progressbar"
                  aria-valuemin="0"
                  aria-valuemax="100"
                  aria-valuenow="{{ $row['accepts_new_bookings'] ? $pct : 0 }}"
                  aria-valuetext="{{ $meterText }}"
                >
                  <div class="meter" style="width: {{ $pct }}%;" aria-hidden="true"></div>
                </div>
                @if ($row['note'])
                  <div class="hint" style="font-size:0.78rem; color: var(--text-muted);">{{ $row['note'] }}</div>
                @endif
              </div>
            @endforeach
          </div>
          <button type="button" class="atup-avail-toggle" id="atup-avail-toggle" aria-expanded="false" aria-controls="atup-avail-list">
            Show all 7 days
          </button>
          <p class="atup-avail-foot">
            Totals respect both the daily visitor cap and any separate bookings-per-day limit set by administrators.
          </p>
          @auth
            @unless (auth()->user()->is_admin)
              <div class="atup-slot-cta">
                <a class="btn btn-primary btn-block" href="{{ route('bookings.create') }}">Book a hiking permit</a>
              </div>
            @endunless
          @else
            <div class="atup-slot-cta">
              <a class="btn btn-primary btn-block" href="{{ route('register') }}">Register to book a slot</a>
              <a class="btn btn-secondary btn-block" href="{{ route('login') }}" style="margin-top: 0.5rem;">Already have an account? Sign in</a>
            </div>
          @endauth
        </div>

        <div class="panel atup-section-anchor" id="reminders">
          <div class="panel-head">
            <h2>Reminders</h2>
          </div>
          <ul class="atup-reminders">
            <li>Permits are <strong>per day, per group</strong>. One active booking per visitor at a time.</li>
            <li>Cancellations are accepted up to the day before your booked hike.</li>
            <li>Bring valid ID, water, snacks, sturdy shoes, and a small bag for trash.</li>
            <li>Hiking is at your own risk. Follow guide instructions throughout the trek.</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="atup-lightbox" id="atup-lightbox" role="dialog" aria-modal="true" aria-labelledby="atup-lightbox-title" hidden>
      <div class="atup-lightbox-dialog">
        <button type="button" class="atup-lightbox-close" id="atup-lightbox-close" aria-label="Close gallery">&times;</button>
        <button type="button" class="atup-lightbox-nav atup-lightbox-prev" id="atup-lightbox-prev" aria-label="Previous photo">&#8249;</button>
        <img id="atup-lightbox-img" src="" alt="" />
        <button type="button" class="atup-lightbox-nav atup-lightbox-next" id="atup-lightbox-next" aria-label="Next photo">&#8250;</button>
        <div class="atup-lightbox-caption" id="atup-lightbox-caption">
          <strong id="atup-lightbox-title"></strong>
          <span id="atup-lightbox-text"></span>
        </div>
      </div>
    </div>

    @unless (auth()->check() && auth()->user()->is_admin)
      <div class="atup-mobile-cta">
        <a class="btn btn-primary" href="{{ $bookCtaUrl }}">{{ $bookCtaLabel }}</a>
      </div>
    @endunless

    <button type="button" class="atup-back-top" id="atup-back-top" aria-label="Back to top">↑</button>
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      function syncStickyOffset() {
        var root = document.querySelector('.atup-overview');
        var header = document.querySelector('.portal-topbar');
        if (!root || !header) return;
        var px = header.offsetHeight + 10;
        root.style.setProperty('--atup-sticky-top', (px / 16) + 'rem');
      }
      syncStickyOffset();
      window.addEventListener('resize', syncStickyOffset, { passive: true });
      var menuToggle = document.getElementById('portalMenuToggle');
      if (menuToggle) {
        menuToggle.addEventListener('click', function () {
          window.setTimeout(syncStickyOffset, 280);
        });
      }

      var subnav = document.getElementById('atup-subnav');
      var sections = ['slots', 'plan', 'gallery', 'locations', 'reminders'];
      var links = subnav ? subnav.querySelectorAll('a[data-section]') : [];

      function setActiveSection(id) {
        links.forEach(function (link) {
          link.classList.toggle('is-active', link.getAttribute('data-section') === id);
        });
      }

      if (links.length && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) setActiveSection(entry.target.id);
          });
        }, { rootMargin: '-40% 0px -45% 0px', threshold: 0.05 });
        sections.forEach(function (id) {
          var el = document.getElementById(id);
          if (el) observer.observe(el);
        });
      }

      links.forEach(function (link) {
        link.addEventListener('click', function () {
          setActiveSection(link.getAttribute('data-section'));
        });
      });

      var availList = document.getElementById('atup-avail-list');
      var availToggle = document.getElementById('atup-avail-toggle');
      if (availList && availToggle) {
        availToggle.addEventListener('click', function () {
          var collapsed = availList.classList.toggle('is-collapsed');
          availToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
          availToggle.textContent = collapsed ? 'Show all 7 days' : 'Show fewer days';
        });
      }

      var cards = Array.prototype.slice.call(document.querySelectorAll('.highlight[data-gallery-src]'));
      var lightbox = document.getElementById('atup-lightbox');
      if (cards.length && lightbox) {
        var img = document.getElementById('atup-lightbox-img');
        var titleEl = document.getElementById('atup-lightbox-title');
        var textEl = document.getElementById('atup-lightbox-text');
        var closeBtn = document.getElementById('atup-lightbox-close');
        var prevBtn = document.getElementById('atup-lightbox-prev');
        var nextBtn = document.getElementById('atup-lightbox-next');
        var current = 0;
        var lastFocus = null;

        function show(index) {
          current = (index + cards.length) % cards.length;
          var card = cards[current];
          img.src = card.getAttribute('data-gallery-src');
          img.alt = card.getAttribute('data-gallery-title');
          titleEl.textContent = card.getAttribute('data-gallery-title');
          textEl.textContent = card.getAttribute('data-gallery-caption');
        }

        function open(index) {
          lastFocus = document.activeElement;
          show(index);
          lightbox.hidden = false;
          lightbox.classList.add('is-open');
          document.body.style.overflow = 'hidden';
          closeBtn.focus();
        }

        function close() {
          lightbox.classList.remove('is-open');
          lightbox.hidden = true;
          document.body.style.overflow = '';
          if (lastFocus) lastFocus.focus();
        }

        cards.forEach(function (card, index) {
          card.addEventListener('click', function () { open(index); });
          card.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              open(index);
            }
          });
        });

        closeBtn.addEventListener('click', close);
        prevBtn.addEventListener('click', function () { show(current - 1); });
        nextBtn.addEventListener('click', function () { show(current + 1); });
        lightbox.addEventListener('click', function (e) {
          if (e.target === lightbox) close();
        });
        document.addEventListener('keydown', function (e) {
          if (lightbox.hidden) return;
          if (e.key === 'Escape') close();
          if (e.key === 'ArrowLeft') show(current - 1);
          if (e.key === 'ArrowRight') show(current + 1);
        });
      }

      var backTop = document.getElementById('atup-back-top');
      if (backTop) {
        window.addEventListener('scroll', function () {
          backTop.classList.toggle('is-visible', window.scrollY > 480);
        }, { passive: true });
        backTop.addEventListener('click', function () {
          document.getElementById('top').scrollIntoView({ behavior: 'smooth' });
        });
      }
    })();
  </script>
@endpush

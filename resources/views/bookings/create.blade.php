@extends('layouts.portal')

@section('title', 'Book a hiking permit')

@push('head')
<style>
  .booking-create-header {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem 1.5rem;
    margin-bottom: 1.5rem;
  }
  .booking-create-header .back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    text-decoration: none;
    margin-bottom: 0.5rem;
  }
  .booking-create-header .back-link:hover { color: var(--teal-hover); }

  .booking-steps {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
    padding: 0.65rem;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
  }
  .booking-step {
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 0.65rem;
    border-radius: var(--radius-sm);
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-muted);
    background: transparent;
    border: none;
    cursor: pointer;
    font-family: inherit;
    text-align: left;
    transition: background 0.2s var(--ease), color 0.2s var(--ease);
  }
  .booking-step:hover { background: var(--teal-muted); color: var(--navy); }
  .booking-step:focus-visible { outline: 2px solid var(--teal); outline-offset: 2px; }
  .booking-step-num {
    flex-shrink: 0;
    width: 1.65rem;
    height: 1.65rem;
    display: grid;
    place-items: center;
    border-radius: 999px;
    background: var(--bg);
    border: 1px solid var(--border);
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-muted);
  }
  .booking-step.is-active .booking-step-num {
    background: var(--teal);
    border-color: var(--teal);
    color: #fff;
  }
  .booking-step.is-active { color: var(--navy); }
  .booking-step-label { line-height: 1.25; }
  .booking-step-label small {
    display: block;
    font-weight: 500;
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 0.1rem;
  }

  .info-accordion {
    margin-bottom: 1.25rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: #fff;
    overflow: hidden;
  }
  .info-accordion summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--navy);
    list-style: none;
    background: linear-gradient(135deg, rgba(255, 234, 0, 0.14), rgba(192, 38, 211, 0.04));
  }
  .info-accordion summary::-webkit-details-marker { display: none; }
  .info-accordion summary::after {
    content: "+";
    font-size: 1.1rem;
    color: var(--teal);
    font-weight: 700;
    transition: transform 0.2s var(--ease);
  }
  .info-accordion[open] summary::after { transform: rotate(45deg); }
  .info-accordion-body {
    padding: 0 1rem 1rem;
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.55;
    border-top: 1px solid var(--border);
  }
  .info-accordion-body p + p { margin-top: 0.65rem; }
  .info-accordion-body a { font-weight: 600; }

  .booking-layout { display: grid; grid-template-columns: 1fr; gap: 1.25rem; align-items: start; }
  @media (min-width: 960px) {
    .booking-layout { grid-template-columns: minmax(0, 1.45fr) minmax(280px, 1fr); }
  }

  .form-section {
    scroll-margin-top: 7rem;
    padding: 1.15rem 0;
    border-bottom: 1px solid var(--border);
  }
  .form-section:last-of-type { border-bottom: none; padding-bottom: 0; }
  .form-section-head {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1rem;
  }
  .form-section-icon {
    flex-shrink: 0;
    width: 2.25rem;
    height: 2.25rem;
    display: grid;
    place-items: center;
    border-radius: var(--radius-sm);
    background: var(--teal-muted);
    color: var(--teal-hover);
    font-size: 1rem;
    font-weight: 700;
  }
  .form-section-head h3 {
    font-size: 1.05rem;
    color: var(--navy);
    font-weight: 700;
    margin-bottom: 0.15rem;
  }
  .form-section-head p {
    font-size: 0.85rem;
    color: var(--text-muted);
    line-height: 1.45;
  }

  .field-span-2 { grid-column: 1 / -1; }

  .party-stepper {
    display: flex;
    align-items: stretch;
    gap: 0;
    max-width: 200px;
  }
  .party-stepper .input {
    border-radius: 0;
    text-align: center;
    font-weight: 700;
    font-size: 1.1rem;
    -moz-appearance: textfield;
  }
  .party-stepper .input::-webkit-outer-spin-button,
  .party-stepper .input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  .party-step-btn {
    flex-shrink: 0;
    width: 2.75rem;
    border: 1px solid var(--border);
    background: var(--bg-subtle);
    color: var(--navy);
    font-size: 1.25rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s var(--ease), border-color 0.15s var(--ease);
  }
  .party-step-btn:hover { background: var(--teal-muted); border-color: var(--teal); }
  .party-step-btn:focus-visible { outline: 2px solid var(--teal); outline-offset: -2px; }
  .party-step-btn:first-child { border-radius: var(--radius-sm) 0 0 var(--radius-sm); }
  .party-step-btn:last-child { border-radius: 0 var(--radius-sm) var(--radius-sm) 0; }

  .date-capacity-hint {
    margin-top: 0.5rem;
    padding: 0.55rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.8125rem;
    font-weight: 600;
    display: none;
  }
  .date-capacity-hint.is-visible { display: block; }
  .date-capacity-hint.is-ok { background: #dcfce7; color: #14532d; border: 1px solid #86efac; }
  .date-capacity-hint.is-warn { background: #fef3c7; color: #78350f; border: 1px solid #fcd34d; }
  .date-capacity-hint.is-error { background: #fee2e2; color: #7f1d1d; border: 1px solid #fca5a5; }

  .member-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: #fff;
    overflow: hidden;
    transition: box-shadow 0.2s var(--ease), border-color 0.2s var(--ease);
  }
  .member-card--leader {
    border-color: rgba(192, 38, 211, 0.35);
    background: linear-gradient(180deg, rgba(255, 234, 0, 0.08), #fff 40%);
  }
  .member-card.is-collapsed .member-card-body { display: none; }
  .member-card-toggle {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    min-height: 44px;
    padding: 0.75rem 0.9rem;
    border: none;
    background: transparent;
    cursor: pointer;
    font-family: inherit;
    text-align: left;
  }
  .member-card--leader .member-card-toggle { cursor: default; }
  .member-card-badge {
    flex-shrink: 0;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.2rem 0.5rem;
    border-radius: 999px;
    background: var(--bg);
    color: var(--text-muted);
    border: 1px solid var(--border);
  }
  .member-card--leader .member-card-badge {
    background: var(--teal-muted);
    color: var(--teal-hover);
    border-color: rgba(192, 38, 211, 0.25);
  }
  .member-card-title {
    flex: 1;
    font-weight: 700;
    font-size: 0.9375rem;
    color: var(--navy);
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .member-card-chevron {
    flex-shrink: 0;
    width: 0.5rem;
    height: 0.5rem;
    border-right: 2px solid var(--text-muted);
    border-bottom: 2px solid var(--text-muted);
    transform: rotate(45deg);
    transition: transform 0.2s var(--ease);
    margin-right: 0.25rem;
  }
  .member-card:not(.is-collapsed) .member-card-chevron { transform: rotate(-135deg); margin-top: 0.25rem; }
  .member-card--leader .member-card-chevron { display: none; }
  .member-card-body { padding: 0 0.9rem 0.9rem; }
  #members-container { display: grid; gap: 0.65rem; }

  .ack-card {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
    padding: 0.9rem 1rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--bg-subtle);
    cursor: pointer;
    transition: border-color 0.15s var(--ease), background 0.15s var(--ease);
  }
  .ack-card:has(input:checked) {
    border-color: var(--teal);
    background: var(--teal-muted);
  }
  .ack-card input { margin-top: 0.2rem; flex-shrink: 0; width: 1.1rem; height: 1.1rem; accent-color: var(--teal); }
  .ack-card span { font-size: 0.9rem; line-height: 1.5; color: var(--text); }

  .form-actions-sticky {
    position: sticky;
    bottom: 0;
    z-index: 20;
    margin: 1.25rem -1.25rem -1.25rem;
    padding: 0.85rem 1.25rem;
    padding-bottom: calc(0.85rem + env(safe-area-inset-bottom, 0px));
    background: linear-gradient(180deg, rgba(255,255,255,0.92), #fff 30%);
    border-top: 1px solid var(--border);
    backdrop-filter: blur(6px);
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
  }
  .form-actions-sticky .btn-primary { margin-left: auto; }
  @media (max-width: 640px) {
    .form-actions-sticky { flex-direction: column; margin-inline: -1rem; padding-inline: 1rem; }
    .form-actions-sticky .btn { width: 100%; margin-left: 0 !important; }
  }

  .avail-panel { position: sticky; top: 6.5rem; }
  .avail-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem 0.85rem;
    margin-bottom: 0.75rem;
    font-size: 0.72rem;
    color: var(--text-muted);
  }
  .avail-legend span { display: inline-flex; align-items: center; gap: 0.3rem; }
  .avail-legend i {
    display: inline-block;
    width: 0.65rem;
    height: 0.65rem;
    border-radius: 2px;
    border: 1px solid var(--border);
    background: #fdf4ff;
  }
  .avail-legend .leg-full i { background: #fee2e2; border-color: #fca5a5; }
  .avail-legend .leg-tight i { background: #fef3c7; border-color: #fcd34d; }
  .avail-legend .leg-custom i { background: #fdf4ff; border-color: var(--teal); }

  .avail-search {
    margin-bottom: 0.65rem;
  }
  .avail-search .input {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
  }

  .avail-list {
    display: grid;
    gap: 0.45rem;
    max-height: min(52vh, 480px);
    overflow-y: auto;
    padding-right: 0.15rem;
    scrollbar-width: thin;
  }
  .avail-row {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
    gap: 0.5rem;
    align-items: center;
    padding: 0.6rem 0.75rem;
    border-radius: var(--radius-sm);
    background: #fdf4ff;
    border: 1px solid var(--border);
    font-size: 0.875rem;
    cursor: pointer;
    text-align: left;
    font: inherit;
    width: 100%;
    transition: border-color 0.15s var(--ease), transform 0.15s var(--ease), box-shadow 0.15s var(--ease);
  }
  .avail-row:hover:not(:disabled) {
    border-color: var(--teal);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
  }
  .avail-row:focus-visible { outline: 2px solid var(--teal); outline-offset: 2px; }
  .avail-row.is-selected {
    border-color: var(--teal);
    box-shadow: 0 0 0 2px rgba(192, 38, 211, 0.2);
    background: var(--teal-muted);
  }
  .avail-row:disabled { cursor: not-allowed; opacity: 0.85; }
  .avail-row strong { color: var(--navy); display: block; }
  .avail-row .avail-meta { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.1rem; }
  .avail-row .avail-count { text-align: right; }
  .avail-row .avail-count strong { font-size: 0.95rem; }
  .avail-bar {
    height: 4px;
    border-radius: 999px;
    background: rgba(0,0,0,0.06);
    margin-top: 0.35rem;
    overflow: hidden;
  }
  .avail-bar span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: var(--success);
    transition: width 0.3s var(--ease);
  }
  .avail-row.full { background: #fee2e2; border-color: #fca5a5; }
  .avail-row.full .avail-bar span { background: var(--danger); width: 100% !important; }
  .avail-row.tight { background: #fef3c7; border-color: #fcd34d; }
  .avail-row.tight .avail-bar span { background: var(--warn); }
  .avail-row.custom .avail-meta::before { content: "★ "; color: var(--teal); }
  .avail-empty {
    padding: 1.5rem 1rem;
    text-align: center;
    color: var(--text-muted);
    font-size: 0.875rem;
  }

  @media (max-width: 959px) {
    .avail-panel { position: static; }
    .booking-step-label small { display: none; }
    .booking-step { flex: 1 1 calc(50% - 0.25rem); justify-content: center; }
    .booking-step-label { display: none; }
    .portal-content {
      padding-bottom: calc(2.5rem + env(safe-area-inset-bottom, 0px));
    }
  }
  @media (max-width: 480px) {
    .booking-step { flex: 1 1 calc(25% - 0.25rem); padding: 0.4rem; }
    .party-stepper { max-width: 100%; }
    .party-step-btn { width: 3rem; min-height: 44px; }
  }
</style>
@endpush

@section('content')
  @php
    $permitPdf = 'NAG-ATUP Visitors Entry Permit.pdf';
    $oldMembers = old('members', []);
    $defaultParty = (int) old('party_size', 1);
    if (count($oldMembers) < $defaultParty) {
      for ($i = count($oldMembers); $i < $defaultParty; $i++) {
        $oldMembers[] = [
          'name' => $i === 0 ? auth()->user()->name : '',
          'sex' => '',
          'address' => old('visitor_address', ''),
          'emergency_contact' => old('emergency_contact', ''),
          'body_marks' => '',
        ];
      }
    }
    $availData = collect($availability)->mapWithKeys(fn ($row) => [
      $row['date'] => [
        'remaining' => $row['remaining'],
        'quota' => $row['quota'],
        'accepts' => $row['accepts_new_bookings'],
      ],
    ]);
  @endphp

  <div class="booking-create-header">
    <div>
      <a href="{{ route('bookings.index') }}" class="back-link" aria-label="Back to my bookings">&larr; My bookings</a>
      <h1>Book a hiking permit</h1>
      <p>Complete the official <strong>Nag-Atup Visitors Entry Permit</strong>. Permit number and fees are assigned by the LGU after review.</p>
    </div>
  </div>

  <nav class="booking-steps" aria-label="Application steps">
    <button type="button" class="booking-step is-active" data-scroll-to="section-schedule" aria-label="Step 1: Schedule — date and group size">
      <span class="booking-step-num" aria-hidden="true">1</span>
      <span class="booking-step-label">Schedule<small>Date &amp; group size</small></span>
    </button>
    <button type="button" class="booking-step" data-scroll-to="section-details" aria-label="Step 2: Trip details — route and contacts">
      <span class="booking-step-num" aria-hidden="true">2</span>
      <span class="booking-step-label">Trip details<small>Route &amp; contacts</small></span>
    </button>
    <button type="button" class="booking-step" data-scroll-to="section-roster" aria-label="Step 3: Visitors — one row per person">
      <span class="booking-step-num" aria-hidden="true">3</span>
      <span class="booking-step-label">Visitors<small>One row per person</small></span>
    </button>
    <button type="button" class="booking-step" data-scroll-to="section-submit" aria-label="Step 4: Submit — rules and health form">
      <span class="booking-step-num" aria-hidden="true">4</span>
      <span class="booking-step-label">Submit<small>Rules &amp; health form</small></span>
    </button>
  </nav>

  <details class="info-accordion">
    <summary>Before you start — official forms &amp; daily limits</summary>
    <div class="info-accordion-body">
      <p>
        Your answers mirror the
        <a href="{{ route('docs.view', ['f' => $permitPdf]) }}" target="_blank" rel="noopener noreferrer">NAG-ATUP Visitors Entry Permit (PDF)</a>.
        Open it to compare field labels. You may also need the
        <a href="{{ route('docs.view', ['f' => 'ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf']) }}" target="_blank" rel="noopener noreferrer">waiver of risk</a>
        before hike day if required separately by the LGU.
        The <strong>health declaration</strong> is completed in the final step before you submit.
      </p>
      <p>
        Administrators set <strong>persons per day</strong> for the site and optionally a <strong>maximum number of bookings</strong> per day.
        New permits are only accepted when both still have room. Your <strong>party size</strong> must fit the remaining daily capacity — pick another date if needed.
      </p>
    </div>
  </details>

  <div class="booking-layout">
    <div>
      <div class="panel">
        <div class="panel-head">
          <h2>Visitors Entry Permit application</h2>
        </div>

        <form method="POST" action="{{ route('bookings.store') }}" novalidate id="booking-form">
          @csrf

          <section class="form-section" id="section-schedule">
            <div class="form-section-head">
              <span class="form-section-icon" aria-hidden="true">1</span>
              <div>
                <h3>Schedule &amp; group size</h3>
                <p>Choose your hike date and how many people are in your group (max 20 per application).</p>
              </div>
            </div>
            <div class="form-grid two-col">
              <div class="field">
                <label for="hike_date">Hike date</label>
                <input id="hike_date"
                       name="hike_date"
                       type="date"
                       class="input"
                       min="{{ $minDate }}"
                       max="{{ $maxDate }}"
                       value="{{ old('hike_date') }}"
                       required />
                <div class="hint">Earliest: tomorrow. Up to 3 months ahead.</div>
                <div id="date-capacity-hint" class="date-capacity-hint" role="status" aria-live="polite"></div>
                @error('hike_date')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="field">
                <label for="party_size">Party size</label>
                <div class="party-stepper">
                  <button type="button" class="party-step-btn" id="party-decrease" aria-label="Decrease party size">&minus;</button>
                  <input id="party_size"
                         name="party_size"
                         type="number"
                         class="input"
                         min="1" max="20"
                         value="{{ old('party_size', 1) }}"
                         required
                         aria-describedby="party-size-hint" />
                  <button type="button" class="party-step-btn" id="party-increase" aria-label="Increase party size">+</button>
                </div>
                <div class="hint" id="party-size-hint">Total head count, including yourself.</div>
                @error('party_size')<div class="error">{{ $message }}</div>@enderror
              </div>
            </div>
          </section>

          <section class="form-section" id="section-details">
            <div class="form-section-head">
              <span class="form-section-icon" aria-hidden="true">2</span>
              <div>
                <h3>Trip details</h3>
                <p>Information from page 1 of the official permit form.</p>
              </div>
            </div>
            <div class="form-grid two-col">
              <div class="field field-span-2">
                <label for="visitor_address">Address</label>
                <input id="visitor_address"
                       name="visitor_address"
                       type="text"
                       class="input"
                       placeholder="Home or mailing address of the group leader"
                       value="{{ old('visitor_address') }}"
                       required />
                @error('visitor_address')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="field">
                <label for="purpose_of_visit">Purpose of visit</label>
                <input id="purpose_of_visit"
                       name="purpose_of_visit"
                       type="text"
                       class="input"
                       placeholder="e.g. Trekking, sightseeing"
                       value="{{ old('purpose_of_visit') }}"
                       required />
                @error('purpose_of_visit')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="field">
                <label for="trekking_days">Days of trekking</label>
                <input id="trekking_days"
                       name="trekking_days"
                       type="text"
                       class="input"
                       placeholder="Defaults to hike date if blank"
                       value="{{ old('trekking_days') }}" />
                <div class="hint">Leave blank to use your selected hike date.</div>
                @error('trekking_days')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="field field-span-2">
                <label for="trekking_route">Mountain to climb / specific route</label>
                <input id="trekking_route"
                       name="trekking_route"
                       type="text"
                       class="input"
                       placeholder="e.g. Nag-Atup (Atup-atup) Waterfalls trail"
                       value="{{ old('trekking_route', 'Nag-Atup (Atup-atup) Waterfalls') }}"
                       required />
                @error('trekking_route')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="field">
                <label for="contact_phone">Contact number</label>
                <input id="contact_phone"
                       name="contact_phone"
                       type="tel"
                       class="input"
                       inputmode="tel"
                       autocomplete="tel"
                       value="{{ old('contact_phone', auth()->user()->phone) }}"
                       required />
                @error('contact_phone')<div class="error">{{ $message }}</div>@enderror
              </div>

              <div class="field">
                <label for="emergency_contact">Emergency contact (group)</label>
                <input id="emergency_contact"
                       name="emergency_contact"
                       type="text"
                       class="input"
                       placeholder="Name &amp; number of someone we can reach"
                       value="{{ old('emergency_contact') }}"
                       required />
                @error('emergency_contact')<div class="error">{{ $message }}</div>@enderror
              </div>
            </div>
          </section>

          <section class="form-section" id="section-roster">
            <div class="form-section-head">
              <span class="form-section-icon" aria-hidden="true">3</span>
              <div>
                <h3>Visitor roster</h3>
                <p>One entry per person — from page 2 of the permit. Tap a visitor card to expand or collapse.</p>
              </div>
            </div>
            @error('members')<div class="error" style="margin-bottom:0.75rem;">{{ $message }}</div>@enderror

            <div id="members-container">
              @foreach ($oldMembers as $index => $member)
                @include('bookings.partials.member-fields', ['index' => $index, 'member' => $member])
              @endforeach
            </div>
          </section>

          <section class="form-section" id="section-submit">
            <div class="form-section-head">
              <span class="form-section-icon" aria-hidden="true">4</span>
              <div>
                <h3>Confirm &amp; submit</h3>
                <p>Agree to site rules, add optional notes, then complete one health declaration for your entire group.</p>
              </div>
            </div>

            <div class="field">
              <label class="ack-card">
                <input type="checkbox"
                       name="permit_rules_ack"
                       value="1"
                       {{ old('permit_rules_ack') ? 'checked' : '' }}
                       required />
                <span>I/We have read, understand the rules and regulations of Nag-Atup (Atup-atup) Waterfalls and agree to abide by them, as stated on the Visitors Entry Permit.</span>
              </label>
              @error('permit_rules_ack')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-top: 1rem;">
              <label for="notes">Additional notes for the LGU (optional)</label>
              <textarea id="notes" name="notes" class="textarea" rows="3" placeholder="Allergies, accessibility needs, guide preferences, etc.">{{ old('notes') }}</textarea>
              @error('notes')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div id="health-declaration-fields" hidden aria-hidden="true"></div>
            @error('health_declarations')<div class="error" style="margin-top:0.75rem;">{{ $message }}</div>@enderror
          </section>

          <div class="form-actions-sticky">
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="button" class="btn btn-primary" id="open-health-declaration">
              Continue to health declaration &rarr;
            </button>
          </div>
        </form>
      </div>
    </div>

    <aside class="avail-panel">
      <div class="panel">
        <div class="panel-head">
          <h2>Slot availability</h2>
          <span class="muted">Next 30 days</span>
        </div>

        <div class="avail-legend" aria-hidden="true">
          <span><i></i> Open</span>
          <span class="leg-tight"><i></i> Limited</span>
          <span class="leg-full"><i></i> Full</span>
          <span class="leg-custom"><i></i> Custom quota</span>
        </div>

        <div class="avail-search">
          <label for="avail-filter" class="sr-only">Filter dates</label>
          <input type="search"
                 id="avail-filter"
                 class="input"
                 placeholder="Search by day or date…"
                 autocomplete="off" />
        </div>

        <div class="avail-list" id="avail-list" role="listbox" aria-label="Available hike dates">
          @foreach ($availability as $row)
            @php
              $cls = ! $row['accepts_new_bookings'] ? 'full' : ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2)) ? 'tight' : '');
              if ($row['custom']) $cls .= ' custom';
              $pct = $row['quota'] > 0 ? round((($row['quota'] - $row['remaining']) / $row['quota']) * 100) : 100;
            @endphp
            <button type="button"
                    class="avail-row {{ trim($cls) }}"
                    role="option"
                    data-date="{{ $row['date'] }}"
                    data-label="{{ strtolower($row['label']) }}"
                    data-remaining="{{ $row['remaining'] }}"
                    data-quota="{{ $row['quota'] }}"
                    data-accepts="{{ $row['accepts_new_bookings'] ? '1' : '0' }}"
                    {{ ! $row['accepts_new_bookings'] ? 'disabled' : '' }}
                    aria-selected="false">
              <div>
                <strong>{{ $row['label'] }}</strong>
                @if ($row['note'])<div class="avail-meta">{{ $row['note'] }}</div>@endif
                @if ($row['accepts_new_bookings'])
                  <div class="avail-bar" aria-hidden="true"><span style="width: {{ $pct }}%;"></span></div>
                @endif
              </div>
              <div class="avail-count">
                @if (! $row['accepts_new_bookings'])
                  <strong style="color: var(--danger);">Fully booked</strong>
                @else
                  <strong>{{ $row['remaining'] }}</strong>
                  <span class="avail-meta">of {{ $row['quota'] }} persons</span>
                  @if ($row['max_bookings'] !== null)
                    <div class="avail-meta">{{ $row['bookings_remaining'] ?? 0 }} / {{ $row['max_bookings'] }} bookings left</div>
                  @endif
                @endif
              </div>
            </button>
          @endforeach
        </div>
        <p class="hint" style="margin-top: 0.65rem;">Select a date to fill the hike date field. Fully booked dates cannot be selected.</p>
      </div>
    </aside>
  </div>

  @include('bookings.partials.health-declaration-modal')
@endsection

@push('scripts')
<script>
  (function () {
    var availByDate = @json($availData);
    var memberTemplate = @json(view('bookings.partials.member-fields', ['index' => 1, 'member' => []])->render());

    function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
    function qsa(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }

    function syncMemberRows() {
      var container = document.getElementById('members-container');
      var partyInput = document.getElementById('party_size');
      if (!container || !partyInput) return;

      var target = Math.min(20, Math.max(1, parseInt(partyInput.value, 10) || 1));
      var existing = container.querySelectorAll('[data-member-row]');

      while (existing.length < target) {
        var html = memberTemplate;
        var wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        container.appendChild(wrap.firstElementChild);
        existing = container.querySelectorAll('[data-member-row]');
      }

      while (existing.length > target) {
        container.removeChild(existing[existing.length - 1]);
        existing = container.querySelectorAll('[data-member-row]');
      }

      existing.forEach(function (row, i) {
        row.dataset.memberIndex = String(i);
        row.classList.toggle('member-card--leader', i === 0);
        row.classList.toggle('is-collapsed', i !== 0);
        var toggle = row.querySelector('.member-card-toggle');
        if (toggle) {
          toggle.setAttribute('aria-expanded', i === 0 ? 'true' : 'false');
          var chevron = row.querySelector('.member-card-chevron');
          if (chevron) chevron.style.display = i === 0 ? 'none' : '';
        }

        row.querySelectorAll('[name]').forEach(function (el) {
          el.name = el.name.replace(/members\[\d+\]/, 'members[' + i + ']');
        });

        var badge = row.querySelector('.member-card-badge');
        var title = row.querySelector('[data-member-title]');
        var nameInput = row.querySelector('[data-member-name]');
        var displayName = nameInput && nameInput.value.trim();

        if (badge) badge.textContent = i === 0 ? 'Group leader' : 'Visitor ' + (i + 1);
        if (title) {
          if (i === 0) {
            title.textContent = displayName ? displayName + ' (group leader)' : 'You (group leader)';
          } else {
            title.textContent = displayName || ('Visitor ' + (i + 1));
          }
        }
      });

      bindMemberToggles();
    }

    function bindMemberToggles() {
      qsa('.member-card-toggle').forEach(function (btn) {
        if (btn.dataset.bound) return;
        btn.dataset.bound = '1';
        var card = btn.closest('[data-member-row]');
        if (card && card.classList.contains('member-card--leader')) return;

        btn.addEventListener('click', function () {
          var collapsed = card.classList.toggle('is-collapsed');
          btn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
      });

      qsa('[data-member-name]').forEach(function (input) {
        if (input.dataset.bound) return;
        input.dataset.bound = '1';
        input.addEventListener('input', function () {
          var row = input.closest('[data-member-row]');
          var idx = parseInt(row.dataset.memberIndex || '0', 10);
          var title = row.querySelector('[data-member-title]');
          var val = input.value.trim();
          if (!title) return;
          if (idx === 0) {
            title.textContent = val ? val + ' (group leader)' : 'You (group leader)';
          } else {
            title.textContent = val || ('Visitor ' + (idx + 1));
          }
        });
      });
    }

    function setPartySize(val) {
      var input = document.getElementById('party_size');
      if (!input) return;
      input.value = Math.min(20, Math.max(1, val));
      syncMemberRows();
      updateCapacityHint();
    }

    document.getElementById('party-decrease')?.addEventListener('click', function () {
      var input = document.getElementById('party_size');
      setPartySize((parseInt(input.value, 10) || 1) - 1);
    });
    document.getElementById('party-increase')?.addEventListener('click', function () {
      var input = document.getElementById('party_size');
      setPartySize((parseInt(input.value, 10) || 1) + 1);
    });

    document.getElementById('party_size')?.addEventListener('change', function () { syncMemberRows(); updateCapacityHint(); });
    document.getElementById('party_size')?.addEventListener('input', function () { syncMemberRows(); updateCapacityHint(); });

    document.getElementById('visitor_address')?.addEventListener('change', function () {
      var addr = this.value;
      qsa('[data-member-address]').forEach(function (el) {
        if (!el.value) el.value = addr;
      });
    });

    function highlightSelectedDate(date) {
      qsa('[data-date]').forEach(function (row) {
        var selected = row.getAttribute('data-date') === date;
        row.classList.toggle('is-selected', selected);
        row.setAttribute('aria-selected', selected ? 'true' : 'false');
      });
    }

    function updateCapacityHint() {
      var hint = document.getElementById('date-capacity-hint');
      var dateInput = document.getElementById('hike_date');
      var partyInput = document.getElementById('party_size');
      if (!hint || !dateInput) return;

      var date = dateInput.value;
      var party = parseInt(partyInput?.value || '1', 10) || 1;
      if (!date || !availByDate[date]) {
        hint.className = 'date-capacity-hint';
        hint.textContent = '';
        return;
      }

      var info = availByDate[date];
      hint.classList.add('is-visible');

      if (!info.accepts) {
        hint.className = 'date-capacity-hint is-visible is-error';
        hint.textContent = 'This date is fully booked. Please choose another date from the availability list.';
      } else if (party > info.remaining) {
        hint.className = 'date-capacity-hint is-visible is-warn';
        hint.textContent = 'Only ' + info.remaining + ' slot' + (info.remaining === 1 ? '' : 's') + ' remain on this date. Reduce party size or pick another date.';
      } else {
        hint.className = 'date-capacity-hint is-visible is-ok';
        hint.textContent = info.remaining + ' of ' + info.quota + ' slots available — your group of ' + party + ' fits.';
      }
    }

    qsa('[data-date]').forEach(function (row) {
      row.addEventListener('click', function () {
        if (row.disabled) return;
        var d = row.getAttribute('data-date');
        var input = document.getElementById('hike_date');
        if (input) {
          input.value = d;
          highlightSelectedDate(d);
          updateCapacityHint();
          input.focus();
        }
      });
    });

    document.getElementById('hike_date')?.addEventListener('change', function () {
      highlightSelectedDate(this.value);
      updateCapacityHint();
    });

    var availFilter = document.getElementById('avail-filter');
    var availList = document.getElementById('avail-list');
    if (availFilter && availList) {
      availFilter.addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        var visible = 0;
        qsa('.avail-row', availList).forEach(function (row) {
          var label = row.getAttribute('data-label') || '';
          var date = row.getAttribute('data-date') || '';
          var show = !q || label.indexOf(q) !== -1 || date.indexOf(q) !== -1;
          row.hidden = !show;
          if (show) visible++;
        });
        var empty = qs('#avail-empty-msg');
        if (!visible && !empty) {
          empty = document.createElement('div');
          empty.id = 'avail-empty-msg';
          empty.className = 'avail-empty';
          empty.textContent = 'No dates match your search.';
          availList.appendChild(empty);
        } else if (visible && empty) {
          empty.remove();
        }
      });
    }

    qsa('[data-scroll-to]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = btn.getAttribute('data-scroll-to');
        var section = document.getElementById(id);
        if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        qsa('.booking-step').forEach(function (s) { s.classList.remove('is-active'); });
        btn.classList.add('is-active');
      });
    });

    var sections = qsa('.form-section');
    if ('IntersectionObserver' in window && sections.length) {
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting && entry.intersectionRatio >= 0.35) {
            var id = entry.target.id;
            qsa('.booking-step').forEach(function (step) {
              step.classList.toggle('is-active', step.getAttribute('data-scroll-to') === id);
            });
          }
        });
      }, { rootMargin: '-20% 0px -55% 0px', threshold: [0.35] });
      sections.forEach(function (s) { observer.observe(s); });
    }

    var healthChecklist = @json(config('health_declaration.checklist'));
    var healthWaiverTitle = @json(config('health_declaration.waiver_title'));
    var healthWaiverText = @json(config('health_declaration.waiver_text'));
    var healthKeys = @json(array_merge(array_keys(config('health_declaration.checklist')), ['waiver_acknowledged']));
    var bookingForm = document.getElementById('booking-form');
    var healthModal = document.getElementById('health-declaration-modal');
    var healthPanels = document.getElementById('health-declaration-panels');
    var healthFields = document.getElementById('health-declaration-fields');
    var openHealthBtn = document.getElementById('open-health-declaration');
    var confirmBtn = document.getElementById('health-confirm-submit');
    var healthAnswers = {};

    function blankHealthAnswers() {
      var answers = {};
      healthKeys.forEach(function (key) { answers[key] = false; });
      return answers;
    }

    function groupHealthIsComplete() {
      return healthKeys.every(function (key) { return !!healthAnswers[key]; });
    }

    function renderHealthPanel() {
      if (!healthPanels) return;
      var partySize = qsa('[data-member-row]').length;
      var leaderRow = qsa('[data-member-row]')[0];
      var leaderInput = leaderRow && leaderRow.querySelector('input[name*="[name]"]');
      var leaderName = leaderInput && leaderInput.value.trim() ? leaderInput.value.trim() : 'the group leader';

      var panel = document.createElement('section');
      panel.className = 'health-member-panel';

      var title = document.createElement('h3');
      title.className = 'health-member-name';
      title.textContent = partySize > 1
        ? 'Group health declaration (' + partySize + ' visitors)'
        : 'Health declaration';
      panel.appendChild(title);

      var intro = document.createElement('p');
      intro.className = 'hint';
      intro.style.marginBottom = '0.85rem';
      intro.innerHTML = partySize > 1
        ? '<strong>' + leaderName + '</strong> confirms this declaration on behalf of all <strong>' + partySize + '</strong> people in the party. Other members do not need to check the boxes.'
        : 'Confirm every statement below to continue.';
      panel.appendChild(intro);

      var checklist = document.createElement('div');
      checklist.className = 'health-checklist';
      Object.keys(healthChecklist).forEach(function (key) {
        var label = document.createElement('label');
        label.className = 'health-check-item';
        var input = document.createElement('input');
        input.type = 'checkbox';
        input.value = '1';
        input.setAttribute('data-health-key', key);
        input.id = 'health-group-' + key;
        input.checked = !!healthAnswers[key];
        input.addEventListener('change', function () {
          healthAnswers[key] = input.checked;
          var errorEl = panel.querySelector('[data-health-panel-error]');
          if (errorEl && groupHealthIsComplete()) errorEl.hidden = true;
        });
        label.htmlFor = input.id;
        var text = document.createElement('span');
        text.textContent = healthChecklist[key];
        label.appendChild(input);
        label.appendChild(text);
        checklist.appendChild(label);
      });
      panel.appendChild(checklist);

      var waiverBlock = document.createElement('div');
      waiverBlock.className = 'health-waiver-block';
      var waiverHeading = document.createElement('h4');
      waiverHeading.textContent = healthWaiverTitle;
      waiverBlock.appendChild(waiverHeading);
      var waiverCopy = document.createElement('p');
      waiverCopy.textContent = healthWaiverText;
      waiverBlock.appendChild(waiverCopy);
      var waiverLabel = document.createElement('label');
      waiverLabel.className = 'health-check-item health-check-item--waiver';
      var waiverInput = document.createElement('input');
      waiverInput.type = 'checkbox';
      waiverInput.value = '1';
      waiverInput.setAttribute('data-health-key', 'waiver_acknowledged');
      waiverInput.id = 'health-group-waiver_acknowledged';
      waiverInput.checked = !!healthAnswers.waiver_acknowledged;
      waiverInput.addEventListener('change', function () {
        healthAnswers.waiver_acknowledged = waiverInput.checked;
        var errorEl = panel.querySelector('[data-health-panel-error]');
        if (errorEl && groupHealthIsComplete()) errorEl.hidden = true;
      });
      waiverLabel.htmlFor = waiverInput.id;
      var waiverText = document.createElement('span');
      waiverText.textContent = partySize > 1
        ? 'I declare that the information above is true and correct for everyone in our group.'
        : 'I declare that the information above is true and correct.';
      waiverLabel.appendChild(waiverInput);
      waiverLabel.appendChild(waiverText);
      waiverBlock.appendChild(waiverLabel);
      panel.appendChild(waiverBlock);

      var errorEl = document.createElement('div');
      errorEl.className = 'error health-panel-error';
      errorEl.hidden = true;
      errorEl.setAttribute('data-health-panel-error', '');
      panel.appendChild(errorEl);

      healthPanels.innerHTML = '';
      healthPanels.appendChild(panel);
    }

    function validateHealthPanel() {
      if (!healthPanels) return false;
      var errorEl = healthPanels.querySelector('[data-health-panel-error]');
      if (groupHealthIsComplete()) {
        if (errorEl) errorEl.hidden = true;
        return true;
      }
      if (errorEl) {
        errorEl.textContent = 'Please confirm every health statement and the waiver for your group.';
        errorEl.hidden = false;
      }
      return false;
    }

    function openHealthModal() {
      if (!bookingForm || !healthModal) return;
      if (!bookingForm.reportValidity()) {
        var firstInvalid = bookingForm.querySelector(':invalid');
        if (firstInvalid) {
          firstInvalid.focus();
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
      }
      syncMemberRows();
      updateCapacityHint();
      var date = document.getElementById('hike_date')?.value;
      var party = parseInt(document.getElementById('party_size')?.value || '1', 10);
      if (date && availByDate[date]) {
        var info = availByDate[date];
        if (!info.accepts || party > info.remaining) {
          document.getElementById('hike_date')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
          updateCapacityHint();
          return;
        }
      }
      healthAnswers = blankHealthAnswers();
      renderHealthPanel();
      healthModal.hidden = false;
      document.body.style.overflow = 'hidden';
    }

    function closeHealthModal() {
      if (!healthModal) return;
      healthModal.hidden = true;
      document.body.style.overflow = '';
    }

    function appendHealthHiddenFields() {
      if (!healthFields) return;
      healthFields.innerHTML = '';
      if (!groupHealthIsComplete()) return;
      healthKeys.forEach(function (key) {
        if (!healthAnswers[key]) return;
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'health_declarations[0][' + key + ']';
        hidden.value = '1';
        healthFields.appendChild(hidden);
      });
      var leaderRow = qsa('[data-member-row]')[0];
      var leaderNameInput = leaderRow && leaderRow.querySelector('input[name*="[name]"]');
      if (leaderNameInput && leaderNameInput.value.trim()) {
        var nameHidden = document.createElement('input');
        nameHidden.type = 'hidden';
        nameHidden.name = 'health_declarations[0][member_name]';
        nameHidden.value = leaderNameInput.value.trim();
        healthFields.appendChild(nameHidden);
      }
    }

    function submitWithHealthDeclaration() {
      if (!validateHealthPanel()) return;
      appendHealthHiddenFields();
      closeHealthModal();
      bookingForm.submit();
    }

    openHealthBtn?.addEventListener('click', openHealthModal);
    confirmBtn?.addEventListener('click', function () {
      submitWithHealthDeclaration();
    });

    healthModal?.querySelectorAll('[data-health-close]').forEach(function (el) {
      el.addEventListener('click', closeHealthModal);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && healthModal && !healthModal.hidden) closeHealthModal();
    });

    bindMemberToggles();
    highlightSelectedDate(document.getElementById('hike_date')?.value || '');
    updateCapacityHint();
  })();
</script>
@endpush

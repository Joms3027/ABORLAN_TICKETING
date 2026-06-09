<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Visitor's Entry Permit - {{ $booking->reference_code }}</title>
  <style>
    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 9pt;
      line-height: 1.2;
      color: #000;
      margin: 30px;
      padding: 0;
    }
    .header {
      text-align: center;
      margin-bottom: 6pt;
    }
    .header-line {
      font-size: 8pt;
      margin: 0;
      line-height: 1.3;
    }
    .header-title {
      font-size: 10pt;
      font-weight: bold;
      margin-top: 5pt;
    }
    .header-subtitle {
      font-size: 8pt;
      margin-bottom: 2pt;
    }
    .permit-title {
      font-size: 11pt;
      font-weight: bold;
      text-decoration: underline;
      margin-top: 6pt;
      margin-bottom: 6pt;
    }
    .permit-row {
      margin-bottom: 3pt;
      font-size: 9pt;
    }
    .underline {
      border-bottom: 1px solid #000;
      display: inline-block;
      min-width: 80px;
      padding: 0 2px;
    }
    .underline-wide {
      border-bottom: 1px solid #000;
      display: inline-block;
      min-width: 140px;
      padding: 0 2px;
    }
    .underline-full {
      border-bottom: 1px solid #000;
      display: block;
      padding: 0 2px;
      margin-top: 2pt;
    }
    .names-table {
      width: 100%;
      border-collapse: collapse;
      margin: 5pt 0;
    }
    .names-table td {
      width: 50%;
      padding: 2pt 4pt 2pt 0;
      border-bottom: 1px solid #000;
      font-size: 9pt;
    }
    .inline-row {
      margin: 5pt 0;
    }
    .declaration {
      margin: 6pt 0;
      font-size: 8pt;
      text-align: justify;
    }
    .signature-table {
      width: 100%;
      margin-top: 10pt;
    }
    .signature-table td {
      width: 50%;
      padding-right: 10pt;
      vertical-align: bottom;
    }
    .signature-line {
      border-bottom: 1px solid #000;
      height: 18pt;
      margin-bottom: 2pt;
    }
    .signature-label {
      font-size: 7pt;
    }
    .note {
      margin-top: 6pt;
      font-size: 7pt;
      font-style: italic;
    }
    .roster-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8pt;
      font-size: 6pt;
    }
    .roster-table th,
    .roster-table td {
      border: 1px solid #000;
      padding: 2pt;
      text-align: left;
      vertical-align: top;
    }
    .roster-table th {
      background-color: #eee;
      font-weight: bold;
      font-size: 5pt;
      text-align: center;
    }
    .roster-table td {
      height: 12pt;
    }
    .page-break {
      page-break-before: always;
    }
    .page-number {
      text-align: center;
      font-size: 7pt;
      margin-top: 8pt;
      color: #666;
    }
    hr {
      border: none;
      border-top: 1px solid #000;
      margin: 6pt 0;
    }
  </style>
</head>
<body>
  @php
    $members = $booking->members ?? [];
  @endphp

  {{-- Page 1 --}}
  <div class="header">
    <p class="header-line">Republic of the Philippines</p>
    <p class="header-line">REGION IV-B MIMAROPA</p>
    <p class="header-line">Province of Palawan</p>
    <p class="header-line">Municipality of Aborlan</p>
    <p class="header-line" style="font-weight: bold;">OFFICE OF THE MUNICIPAL MAYOR</p>
    <p class="header-title">NAG-ATUP (ATUP-ATUP) WATERFALLS</p>
    <p class="header-subtitle">ABORLAN, PALAWAN</p>
    <p class="permit-title">VISITOR'S ENTRY PERMIT</p>
  </div>

  <div class="permit-row">
    Permit No. <span class="underline">{{ $booking->status === 'approved' ? $booking->reference_code : '' }}</span>
    &nbsp;&nbsp;&nbsp;&nbsp;
    Date Issued: <span class="underline">{{ $booking->status === 'approved' && $booking->decided_at ? $booking->decided_at->format('M j, Y') : '' }}</span>
  </div>

  <div class="permit-row" style="margin-top: 6pt;">Name of Visitors:</div>

  <table class="names-table">
    @for ($row = 0; $row < 6; $row++)
      <tr>
        <td>{{ $members[$row * 2]['name'] ?? '' }}</td>
        <td>{{ $members[$row * 2 + 1]['name'] ?? '' }}</td>
      </tr>
    @endfor
  </table>

  <p style="font-size: 7pt; font-style: italic; margin-bottom: 5pt;">(use the back pages for other information's)</p>

  <div class="permit-row">
    Address: <span class="underline-wide">{{ $booking->visitor_address }}</span>
  </div>

  <div class="permit-row">
    Purpose of Visit: <span class="underline-wide">{{ $booking->purpose_of_visit }}</span>
  </div>

  <div class="permit-row">
    Mountain to climb/Specific route:
    <span class="underline-full">{{ $booking->trekking_route }}</span>
  </div>

  <div class="inline-row">
    Days of Trekking: <span class="underline">{{ $booking->trekking_days ?: $booking->hike_date->format('M j, Y') }}</span>
    &nbsp;&nbsp;
    No. of Members: <span class="underline" style="min-width: 40px;">{{ $booking->party_size }}</span>
  </div>

  <div class="permit-row">
    Contact person in case of emergency: <span class="underline-wide">{{ $booking->emergency_contact }}</span>
  </div>

  <div class="permit-row">
    Contact no.: <span class="underline">{{ $booking->contact_phone }}</span>
  </div>

  <div class="permit-row">
    Total Fees: <span class="underline">______________</span>
  </div>

  <div class="declaration">
    I/We, have read, understand the rules and regulations of Nag-Atup (Atup-atup) Waterfalls and agree to abide them, failing which is shall be liable to disciplinary action.
  </div>

  <table class="signature-table">
    <tr>
      <td>
        <div class="signature-line"></div>
        <div class="signature-label">Visitor/Climber</div>
      </td>
      <td>
        <div class="signature-line"></div>
        <div class="signature-label">Issued by:</div>
      </td>
    </tr>
  </table>

  <hr>

  <p class="note">
    <strong>NOTE:</strong> This Permit is non-transferable and is only valid for the date approved. Please see the separate sheet for Rules and Regulations.
  </p>

  {{-- Roster Table - First 5 rows --}}
  <table class="roster-table">
    <thead>
      <tr>
        <th style="width: 4%;">#</th>
        <th style="width: 18%;">Name</th>
        <th style="width: 6%;">Sex (M/F)</th>
        <th style="width: 24%;">Address</th>
        <th style="width: 24%;">Emergency Contact</th>
        <th style="width: 24%;">Body ID/Marks</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < 5; $i++)
        @php $m = $members[$i] ?? null; @endphp
        <tr>
          <td style="text-align: center;">{{ $i + 1 }}.</td>
          <td>{{ $m['name'] ?? '' }}</td>
          <td style="text-align: center;">{{ $m['sex'] ?? '' }}</td>
          <td>{{ $m['address'] ?? '' }}</td>
          <td>{{ $m['emergency_contact'] ?? '' }}</td>
          <td>{{ $m['body_marks'] ?? '' }}</td>
        </tr>
      @endfor
    </tbody>
  </table>

  <div class="page-number">-- 1 of 2 --</div>

  {{-- Page 2 --}}
  <div class="page-break"></div>

  <div class="header">
    <p class="header-line">Republic of the Philippines</p>
    <p class="header-line">REGION IV-B MIMAROPA</p>
    <p class="header-line">Province of Palawan</p>
    <p class="header-line">Municipality of Aborlan</p>
    <p class="header-line" style="font-weight: bold;">OFFICE OF THE MUNICIPAL MAYOR</p>
  </div>

  {{-- Continuation of Roster Table - Rows 6-21 --}}
  <table class="roster-table" style="margin-top: 10pt;">
    <thead>
      <tr>
        <th style="width: 4%;">#</th>
        <th style="width: 18%;">Name</th>
        <th style="width: 6%;">Sex (M/F)</th>
        <th style="width: 24%;">Address</th>
        <th style="width: 24%;">Emergency Contact</th>
        <th style="width: 24%;">Body ID/Marks</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 5; $i < 21; $i++)
        @php $m = $members[$i] ?? null; @endphp
        <tr>
          <td style="text-align: center;">{{ $i + 1 }}.</td>
          <td>{{ $m['name'] ?? '' }}</td>
          <td style="text-align: center;">{{ $m['sex'] ?? '' }}</td>
          <td>{{ $m['address'] ?? '' }}</td>
          <td>{{ $m['emergency_contact'] ?? '' }}</td>
          <td>{{ $m['body_marks'] ?? '' }}</td>
        </tr>
      @endfor
    </tbody>
  </table>

  <div class="page-number">-- 2 of 2 --</div>
</body>
</html>

@if ($booking->visitor_address || $booking->members || $booking->purpose_of_visit)
  <div class="panel" style="margin-top: 1.25rem;">
    <div class="panel-head">
      <h2>Visitors Entry Permit</h2>
      <div class="panel-head-actions">
        <a href="{{ route('bookings.permit', $booking) }}" class="btn btn-primary btn-sm" title="Download your completed Visitor's Entry Permit as PDF">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 0.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Download PDF
        </a>
        <a href="{{ route('bookings.permit', ['booking' => $booking, 'preview' => 1]) }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" title="Preview your permit in a new tab">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 0.35rem;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          Preview
        </a>
        <a href="{{ route('docs.view', ['f' => 'NAG-ATUP Visitors Entry Permit.pdf']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" title="View the official blank permit template">Blank template</a>
      </div>
    </div>

    @include('bookings.partials.permit-application-body', ['booking' => $booking])
  </div>
@endif

@if ($booking->visitor_address || $booking->members || $booking->purpose_of_visit)
  <div class="panel" style="margin-top: 1.25rem;">
    <div class="panel-head">
      <h2>Visitors Entry Permit</h2>
      <a href="{{ route('docs.view', ['f' => 'NAG-ATUP Visitors Entry Permit.pdf']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm">View PDF template</a>
    </div>

    @include('bookings.partials.permit-application-body', ['booking' => $booking])
  </div>
@endif

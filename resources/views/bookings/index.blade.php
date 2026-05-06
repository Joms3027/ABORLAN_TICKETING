@extends('layouts.portal')

@section('title', 'My bookings')

@section('content')
  <div class="page-header">
    <h1>My hiking permits</h1>
    <p>Review the status of your Atup-atup Falls bookings or submit a new permit application.</p>
  </div>

  <div class="panel">
    <div class="panel-head">
      <h2>Bookings</h2>
      <a class="btn btn-primary" href="{{ route('bookings.create') }}">Book a new date</a>
    </div>

    @if ($bookings->isEmpty())
      <p style="color: var(--text-muted);">You haven't booked a hiking permit yet. Use the button above to reserve a date.</p>
    @else
      <div class="table-wrap">
        <table class="data">
          <thead>
            <tr>
              <th>Reference</th>
              <th>Hike date</th>
              <th>Party size</th>
              <th>Status</th>
              <th>Submitted</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($bookings as $b)
              <tr>
                <td><strong>{{ $b->reference_code }}</strong></td>
                <td>{{ $b->hike_date->format('D, M j, Y') }}</td>
                <td>{{ $b->party_size }}</td>
                <td>
                  <span class="pill pill-{{ $b->status }}">{{ $b->statusLabel() }}</span>
                </td>
                <td>{{ $b->created_at->format('M j, Y g:i A') }}</td>
                <td><a class="btn btn-secondary btn-sm" href="{{ route('bookings.show', $b) }}">View</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endsection

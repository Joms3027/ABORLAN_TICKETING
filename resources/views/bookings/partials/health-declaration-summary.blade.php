@php
  $declarations = $booking->health_declarations ?? [];
  $checklistLabels = config('health_declaration.checklist');
  $declaration = $declarations[0] ?? null;
@endphp

@if (! empty($declaration))
  @php
    $partySize = (int) ($declaration['party_size'] ?? $booking->party_size ?? count($declarations));
    $declaredBy = $declaration['declared_by']
      ?? $declaration['member_name']
      ?? ($booking->members[0]['name'] ?? $booking->user?->name ?? null);
  @endphp

  <div class="panel" style="margin-top: 1.25rem;">
    <div class="panel-head">
      <h2>Health declarations</h2>
      <span class="muted">Submitted with application</span>
    </div>

    <div style="padding: 0.85rem 0;">
      <div style="font-weight: 700; color: var(--navy); margin-bottom: 0.35rem;">
        @if ($partySize > 1)
          Group health declaration ({{ $partySize }} visitors)
        @else
          Health declaration
        @endif
      </div>
      @if ($declaredBy)
        <div class="hint" style="margin-bottom: 0.35rem;">
          Declared by {{ $declaredBy }}{{ $partySize > 1 ? ' on behalf of the party' : '' }}
        </div>
      @endif
      @if (! empty($declaration['declared_at']))
        <div class="hint" style="margin-bottom: 0.5rem;">Declared {{ \Illuminate\Support\Carbon::parse($declaration['declared_at'])->format('M j, Y g:i A') }}</div>
      @endif
      <ul style="padding-left: 1.1rem; display: grid; gap: 0.25rem; font-size: 0.9rem; color: var(--text-muted);">
        @foreach ($checklistLabels as $key => $label)
          <li style="color: {{ ($declaration['checklist'][$key] ?? false) ? 'var(--success)' : 'var(--danger)' }};">
            {{ ($declaration['checklist'][$key] ?? false) ? '✓' : '✗' }}
            <span style="color: var(--text);">{{ \Illuminate\Support\Str::limit($label, 120) }}</span>
          </li>
        @endforeach
        <li style="color: {{ ($declaration['waiver_acknowledged'] ?? false) ? 'var(--success)' : 'var(--danger)' }};">
          {{ ($declaration['waiver_acknowledged'] ?? false) ? '✓' : '✗' }}
          <span style="color: var(--text);">Waiver acknowledged</span>
        </li>
      </ul>
    </div>
  </div>
@endif

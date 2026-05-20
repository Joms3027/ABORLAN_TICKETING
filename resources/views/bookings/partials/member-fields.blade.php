@php
  $index = $index ?? 0;
  $member = $member ?? [];
@endphp
<div class="panel" data-member-row style="padding:1rem; background:var(--bg-subtle); border:1px solid var(--border);">
  <div style="font-weight:700; color:var(--navy); margin-bottom:0.65rem;" data-member-title>Visitor {{ (int) $index + 1 }}</div>
  <div class="form-grid two-col">
    <div class="field">
      <label>Name</label>
      <input type="text"
             name="members[{{ $index }}][name]"
             class="input"
             value="{{ $member['name'] ?? '' }}"
             required />
      @error('members.'.$index.'.name')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="field">
      <label>Sex (M/F)</label>
      <select name="members[{{ $index }}][sex]" class="select" required>
        <option value="" disabled {{ empty($member['sex'] ?? '') ? 'selected' : '' }}>Select</option>
        <option value="M" {{ ($member['sex'] ?? '') === 'M' ? 'selected' : '' }}>M</option>
        <option value="F" {{ ($member['sex'] ?? '') === 'F' ? 'selected' : '' }}>F</option>
      </select>
      @error('members.'.$index.'.sex')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="field" style="grid-column: 1 / -1;">
      <label>Address</label>
      <input type="text"
             name="members[{{ $index }}][address]"
             class="input"
             data-member-address
             value="{{ $member['address'] ?? '' }}"
             required />
      @error('members.'.$index.'.address')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="field">
      <label>Name &amp; contact number in case of emergency</label>
      <input type="text"
             name="members[{{ $index }}][emergency_contact]"
             class="input"
             value="{{ $member['emergency_contact'] ?? '' }}"
             required />
      @error('members.'.$index.'.emergency_contact')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div class="field">
      <label>Body identification / birth marks</label>
      <input type="text"
             name="members[{{ $index }}][body_marks]"
             class="input"
             placeholder="Optional"
             value="{{ $member['body_marks'] ?? '' }}" />
      @error('members.'.$index.'.body_marks')<div class="error">{{ $message }}</div>@enderror
    </div>
  </div>
</div>

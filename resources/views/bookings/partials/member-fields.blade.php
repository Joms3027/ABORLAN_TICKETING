@php
  $index = $index ?? 0;
  $member = $member ?? [];
  $isLeader = (int) $index === 0;
@endphp
<div class="member-card{{ $isLeader ? ' member-card--leader' : '' }}{{ $isLeader ? '' : ' is-collapsed' }}"
     data-member-row
     data-member-index="{{ (int) $index }}">
  <button type="button" class="member-card-toggle" aria-expanded="{{ $isLeader ? 'true' : 'false' }}">
    <span class="member-card-badge">{{ $isLeader ? 'Group leader' : 'Visitor ' . ((int) $index + 1) }}</span>
    <span class="member-card-title" data-member-title>{{ $isLeader ? 'You (group leader)' : 'Visitor ' . ((int) $index + 1) }}</span>
    <span class="member-card-chevron" aria-hidden="true"></span>
  </button>
  <div class="member-card-body">
    <div class="form-grid two-col">
      <div class="field">
        <label>Full name</label>
        <input type="text"
               name="members[{{ $index }}][name]"
               class="input"
               data-member-name
               placeholder="{{ $isLeader ? 'As on your ID' : 'Full legal name' }}"
               value="{{ $member['name'] ?? '' }}"
               required />
        @error('members.'.$index.'.name')<div class="error">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label>Sex</label>
        <select name="members[{{ $index }}][sex]" class="select" required>
          <option value="" disabled {{ empty($member['sex'] ?? '') ? 'selected' : '' }}>Select</option>
          <option value="M" {{ ($member['sex'] ?? '') === 'M' ? 'selected' : '' }}>Male</option>
          <option value="F" {{ ($member['sex'] ?? '') === 'F' ? 'selected' : '' }}>Female</option>
        </select>
        @error('members.'.$index.'.sex')<div class="error">{{ $message }}</div>@enderror
      </div>
      <div class="field field-span-2">
        <label>Address</label>
        <input type="text"
               name="members[{{ $index }}][address]"
               class="input"
               data-member-address
               placeholder="Home or mailing address"
               value="{{ $member['address'] ?? '' }}"
               required />
        @error('members.'.$index.'.address')<div class="error">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label>Emergency contact</label>
        <input type="text"
               name="members[{{ $index }}][emergency_contact]"
               class="input"
               placeholder="Name &amp; phone number"
               value="{{ $member['emergency_contact'] ?? '' }}"
               required />
        @error('members.'.$index.'.emergency_contact')<div class="error">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label>Body identification / birth marks</label>
        <input type="text"
               name="members[{{ $index }}][body_marks]"
               class="input"
               placeholder="Optional — scars, tattoos, etc."
               value="{{ $member['body_marks'] ?? '' }}" />
        @error('members.'.$index.'.body_marks')<div class="error">{{ $message }}</div>@enderror
      </div>
    </div>
  </div>
</div>

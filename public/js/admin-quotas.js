(function () {
  'use strict';

  var form = document.getElementById('quota-override-form');
  var panel = document.getElementById('quota-override-panel');
  var filterTabs = document.querySelectorAll('[data-quota-filter]');
  var rows = document.querySelectorAll('[data-quota-row]');
  var editButtons = document.querySelectorAll('[data-quota-edit]');
  var searchInput = document.getElementById('quota-search');

  if (!form) return;

  var fields = {
    date: form.querySelector('#quota_date'),
    slots: form.querySelector('#slots'),
    maxBookings: form.querySelector('#max_bookings'),
    note: form.querySelector('#note'),
  };

  var formTitle = document.getElementById('quota-override-title');
  var formHint = document.getElementById('quota-override-hint');

  function setActiveFilter(filter) {
    filterTabs.forEach(function (tab) {
      tab.classList.toggle('is-active', tab.getAttribute('data-quota-filter') === filter);
    });
    applyFilters(filter, searchInput ? searchInput.value : '');
  }

  function rowMatchesFilter(row, filter) {
    if (filter === 'all') return true;
    if (filter === 'custom') return row.getAttribute('data-custom') === '1';
    return row.getAttribute('data-status') === filter;
  }

  function rowMatchesSearch(row, query) {
    if (!query) return true;
    var haystack = (row.getAttribute('data-search') || '').toLowerCase();
    return haystack.indexOf(query.toLowerCase()) !== -1;
  }

  function applyFilters(filter, query) {
    var activeFilter = filter || 'all';
    var visible = 0;

    rows.forEach(function (row) {
      var show = rowMatchesFilter(row, activeFilter) && rowMatchesSearch(row, query);
      row.hidden = !show;
      if (show) visible++;
    });

    var empty = document.getElementById('quota-filter-empty');
    if (empty) empty.hidden = visible > 0;
  }

  function highlightForm() {
    if (!panel) return;
    panel.classList.add('is-editing');
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    window.setTimeout(function () {
      panel.classList.remove('is-editing');
    }, 1800);
  }

  function fillOverrideForm(payload) {
    if (fields.date) fields.date.value = payload.date || '';
    if (fields.slots) fields.slots.value = payload.slots != null ? String(payload.slots) : '';
    if (fields.maxBookings) {
      fields.maxBookings.value = payload.maxBookings != null && payload.maxBookings !== ''
        ? String(payload.maxBookings)
        : '';
    }
    if (fields.note) fields.note.value = payload.note || '';

    if (formTitle) {
      formTitle.textContent = payload.date
        ? 'Edit override — ' + (payload.label || payload.date)
        : 'Override a date';
    }
    if (formHint) {
      formHint.textContent = payload.date
        ? 'Update limits for this date or save to create a new override.'
        : 'Set custom limits for a specific day (holidays, events, closures).';
    }

    highlightForm();
    if (fields.slots) fields.slots.focus();
  }

  filterTabs.forEach(function (tab) {
    tab.addEventListener('click', function (event) {
      event.preventDefault();
      setActiveFilter(tab.getAttribute('data-quota-filter') || 'all');
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      var active = document.querySelector('[data-quota-filter].is-active');
      applyFilters(active ? active.getAttribute('data-quota-filter') : 'all', searchInput.value);
    });
  }

  editButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      fillOverrideForm({
        date: button.getAttribute('data-date') || '',
        label: button.getAttribute('data-label') || '',
        slots: button.getAttribute('data-slots') || '',
        maxBookings: button.getAttribute('data-max-bookings'),
        note: button.getAttribute('data-note') || '',
      });
    });
  });

  rows.forEach(function (row) {
    row.addEventListener('dblclick', function () {
      var edit = row.querySelector('[data-quota-edit]');
      if (edit) edit.click();
    });
  });

  if (fields.date && fields.date.value) {
    highlightForm();
  }
})();

(function () {
  'use strict';

  document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    var targetId = btn.getAttribute('aria-controls');
    var input = targetId ? document.getElementById(targetId) : null;
    if (!input) return;

    btn.addEventListener('click', function () {
      var isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
      btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');

      var showIcon = btn.querySelector('[data-icon-show]');
      var hideIcon = btn.querySelector('[data-icon-hide]');
      if (showIcon) showIcon.hidden = isHidden;
      if (hideIcon) hideIcon.hidden = !isHidden;
    });
  });

  document.querySelectorAll('form[data-auth-form]').forEach(function (form) {
    var submitBtn = form.querySelector('[type="submit"]');
    if (!submitBtn) return;

    form.addEventListener('submit', function () {
      submitBtn.disabled = true;
      submitBtn.setAttribute('aria-busy', 'true');
      var label = submitBtn.querySelector('[data-btn-label]');
      var loading = submitBtn.querySelector('[data-btn-loading]');
      if (label) label.hidden = true;
      if (loading) loading.hidden = false;
    });
  });
})();

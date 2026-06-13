(function () {
  var modal = null;
  var pendingForm = null;
  var previouslyFocused = null;

  function getModal() {
    if (!modal) {
      modal = document.getElementById('logout-confirm-modal');
    }
    return modal;
  }

  function openModal(form) {
    var el = getModal();
    if (!el) {
      if (window.confirm('Are you sure you want to sign out?')) {
        form.submit();
      }
      return;
    }

    pendingForm = form;
    previouslyFocused = document.activeElement;
    el.hidden = false;
    document.body.style.overflow = 'hidden';

    var confirmBtn = el.querySelector('[data-logout-confirm]');
    if (confirmBtn) {
      confirmBtn.focus();
    }
  }

  function closeModal() {
    var el = getModal();
    if (!el || el.hidden) {
      return;
    }

    el.hidden = true;
    document.body.style.overflow = '';
    pendingForm = null;

    if (previouslyFocused && typeof previouslyFocused.focus === 'function') {
      previouslyFocused.focus();
    }
  }

  function confirmLogout() {
    if (!pendingForm) {
      return;
    }

    var form = pendingForm;
    closeModal();
    form.submit();
  }

  document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form || form.tagName !== 'FORM') {
      return;
    }

    var action = (form.getAttribute('action') || '').replace(/\/+$/, '');
    if (!action.endsWith('/logout')) {
      return;
    }

    event.preventDefault();
    openModal(form);
  });

  document.addEventListener('click', function (event) {
    var target = event.target;
    if (!(target instanceof Element)) {
      return;
    }

    if (target.closest('[data-logout-close]')) {
      closeModal();
      return;
    }

    if (target.closest('[data-logout-confirm]')) {
      confirmLogout();
    }
  });

  document.addEventListener('keydown', function (event) {
    var el = getModal();
    if (!el || el.hidden) {
      return;
    }

    if (event.key === 'Escape') {
      event.preventDefault();
      closeModal();
    }
  });
})();

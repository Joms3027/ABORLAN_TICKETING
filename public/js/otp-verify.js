(function () {
  'use strict';

  const form = document.querySelector('[data-otp-form]');
  if (!form) return;

  const digits = Array.from(form.querySelectorAll('[data-otp-digit]'));
  const hiddenInput = document.getElementById('otp_code');
  const isLocked = form.dataset.locked === 'true';

  /**
   * Sync individual digit inputs into the hidden otp_code field.
   */
  function syncHiddenValue() {
    hiddenInput.value = digits.map((el) => el.value.replace(/\D/g, '')).join('');
  }

  digits.forEach((input, index) => {
    input.addEventListener('input', () => {
      const value = input.value.replace(/\D/g, '');
      input.value = value.slice(-1);

      if (value && index < digits.length - 1) {
        digits[index + 1].focus();
      }

      syncHiddenValue();

      if (hiddenInput.value.length === 6 && !isLocked) {
        form.requestSubmit();
      }
    });

    input.addEventListener('keydown', (event) => {
      if (event.key === 'Backspace' && !input.value && index > 0) {
        digits[index - 1].focus();
      }
    });

    input.addEventListener('paste', (event) => {
      event.preventDefault();
      const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);

      pasted.split('').forEach((char, offset) => {
        if (digits[offset]) {
          digits[offset].value = char;
        }
      });

      syncHiddenValue();

      const nextIndex = Math.min(pasted.length, digits.length - 1);
      digits[nextIndex].focus();

      if (hiddenInput.value.length === 6 && !isLocked) {
        form.requestSubmit();
      }
    });
  });

  form.addEventListener('submit', () => {
    syncHiddenValue();
    const submitBtn = form.querySelector('button[type="submit"]');
    const label = submitBtn?.querySelector('[data-btn-label]');
    const loading = submitBtn?.querySelector('[data-btn-loading]');
    if (label) label.hidden = true;
    if (loading) loading.hidden = false;
    if (submitBtn) submitBtn.disabled = true;
  });

  const expiryEl = document.querySelector('[data-expires-at]');
  const expiryCountdown = document.querySelector('[data-expiry-countdown]');

  if (expiryEl && expiryCountdown) {
    const expiresAt = new Date(expiryEl.dataset.expiresAt).getTime();

    const tickExpiry = () => {
      const remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
      const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
      const seconds = String(remaining % 60).padStart(2, '0');
      expiryCountdown.textContent = minutes + ':' + seconds;

      if (remaining <= 0) {
        expiryCountdown.textContent = 'Expired';
      }
    };

    tickExpiry();
    setInterval(tickExpiry, 1000);
  }

  const resendBtn = document.querySelector('[data-resend-btn]');
  const resendCountdown = document.querySelector('[data-resend-countdown]');

  if (resendBtn) {
    let cooldown = parseInt(resendBtn.dataset.cooldown || '0', 10);

    const tickResend = () => {
      if (cooldown <= 0) {
        resendBtn.disabled = isLocked;
        if (resendCountdown) resendCountdown.hidden = true;
        return;
      }

      resendBtn.disabled = true;
      if (resendCountdown) {
        resendCountdown.hidden = false;
        resendCountdown.textContent = '(' + cooldown + 's)';
      }
      cooldown -= 1;
    };

    if (cooldown > 0) {
      tickResend();
      setInterval(tickResend, 1000);
    }
  }
})();

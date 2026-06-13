<div id="logout-confirm-modal"
     class="logout-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="logout-modal-title"
     aria-describedby="logout-modal-desc"
     hidden>
  <div class="logout-modal-backdrop" data-logout-close tabindex="-1" aria-hidden="true"></div>
  <div class="logout-modal-dialog">
    <div class="logout-modal-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
      </svg>
    </div>
    <p class="logout-modal-eyebrow">Account</p>
    <h2 id="logout-modal-title">Sign out of your account?</h2>
    <p id="logout-modal-desc" class="logout-modal-text">
      You will need to sign in again to view bookings, permits, and your dashboard.
    </p>
    <div class="logout-modal-actions">
      <button type="button" class="logout-modal-btn logout-modal-btn-cancel" data-logout-close>
        Stay signed in
      </button>
      <button type="button" class="logout-modal-btn logout-modal-btn-confirm" data-logout-confirm>
        Sign out
      </button>
    </div>
  </div>
</div>

<style>
  .logout-modal[hidden] { display: none !important; }
  .logout-modal {
    --logout-navy: var(--navy, #2a0a32);
    --logout-muted: var(--text-muted, #6b4a6e);
    --logout-border: var(--border, #e9d5ef);
    --logout-surface: var(--surface, #ffffff);
    --logout-accent: var(--teal, var(--admin-accent, #c026d3));
    --logout-accent-hover: var(--teal-hover, var(--admin-accent-hover, #a21caf));
    --logout-radius: var(--radius, 12px);
    --logout-shadow: var(--shadow-lg, 0 20px 50px rgba(42, 10, 50, 0.18));
    position: fixed;
    inset: 0;
    z-index: 300;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    font-family: var(--font, "Source Sans 3", system-ui, sans-serif);
  }
  .logout-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(42, 10, 50, 0.58);
    backdrop-filter: blur(4px);
    animation: logout-fade-in 0.2s ease;
  }
  .logout-modal-dialog {
    position: relative;
    width: min(100%, 420px);
    padding: 1.75rem 1.75rem 1.5rem;
    background: var(--logout-surface);
    border: 1px solid var(--logout-border);
    border-radius: var(--logout-radius);
    box-shadow: var(--logout-shadow);
    text-align: center;
    animation: logout-slide-up 0.24s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .logout-modal-icon {
    width: 3.25rem;
    height: 3.25rem;
    margin: 0 auto 1rem;
    display: grid;
    place-items: center;
    border-radius: 999px;
    color: var(--logout-accent);
    background: linear-gradient(135deg, rgba(255, 234, 0, 0.18), rgba(192, 38, 211, 0.12));
    border: 1px solid rgba(192, 38, 211, 0.18);
  }
  .logout-modal-icon svg {
    width: 1.45rem;
    height: 1.45rem;
  }
  .logout-modal-eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--logout-muted);
    margin-bottom: 0.35rem;
  }
  .logout-modal-dialog h2 {
    font-size: 1.35rem;
    line-height: 1.25;
    color: var(--logout-navy);
    margin-bottom: 0.55rem;
  }
  .logout-modal-text {
    font-size: 0.95rem;
    line-height: 1.55;
    color: var(--logout-muted);
    margin-bottom: 1.35rem;
  }
  .logout-modal-actions {
    display: flex;
    flex-direction: column-reverse;
    gap: 0.65rem;
  }
  @media (min-width: 480px) {
    .logout-modal-actions {
      flex-direction: row;
      justify-content: center;
    }
  }
  .logout-modal-btn {
    flex: 1;
    min-width: 0;
    padding: 0.7rem 1.1rem;
    border-radius: var(--radius-sm, 8px);
    font: inherit;
    font-size: 0.9375rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
  }
  .logout-modal-btn:focus-visible {
    outline: 2px solid var(--logout-accent);
    outline-offset: 2px;
  }
  .logout-modal-btn-cancel {
    background: transparent;
    color: var(--logout-navy);
    border: 1px solid var(--logout-border);
  }
  .logout-modal-btn-cancel:hover {
    border-color: var(--logout-accent);
    color: var(--logout-accent);
    background: rgba(192, 38, 211, 0.06);
  }
  .logout-modal-btn-confirm {
    background: var(--logout-accent);
    color: #fff;
    border: 1px solid transparent;
    box-shadow: 0 2px 10px rgba(192, 38, 211, 0.28);
  }
  .logout-modal-btn-confirm:hover {
    background: var(--logout-accent-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(192, 38, 211, 0.32);
  }
  @keyframes logout-fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  @keyframes logout-slide-up {
    from {
      opacity: 0;
      transform: translateY(12px) scale(0.98);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
</style>

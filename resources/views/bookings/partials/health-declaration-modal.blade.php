<div id="health-declaration-modal"
     class="health-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="health-modal-title"
     hidden>
  <div class="health-modal-backdrop" data-health-close tabindex="-1" aria-hidden="true"></div>
  <div class="health-modal-dialog">
    <div class="health-modal-header">
      <div>
        <p class="health-modal-eyebrow">{{ config('health_declaration.subtitle') }}</p>
        <h2 id="health-modal-title">{{ config('health_declaration.title') }}</h2>
        <p class="health-modal-intro">Complete this form once for your entire group. The group leader confirms on behalf of everyone in the party — other members do not need to check the boxes separately.</p>
        <p class="health-modal-intro" style="margin-top:0.5rem;">
          <a href="{{ route('docs.view', ['f' => 'HEALTH DECLARATION FORM.pdf']) }}" target="_blank" rel="noopener noreferrer">View official PDF</a>
        </p>
      </div>
      <button type="button" class="health-modal-close" data-health-close aria-label="Close">&times;</button>
    </div>

    <div class="health-modal-body" id="health-declaration-panels"></div>

    <div class="health-modal-footer">
      <button type="button" class="btn btn-secondary" data-health-close>Back to application</button>
      <button type="button" class="btn btn-primary" id="health-confirm-submit">Confirm and submit application</button>
    </div>
  </div>
</div>

<style>
  .health-modal[hidden] { display: none !important; }
  .health-modal {
    position: fixed;
    inset: 0;
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
  }
  .health-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(42, 10, 50, 0.55);
    backdrop-filter: blur(2px);
  }
  .health-modal-dialog {
    position: relative;
    width: min(100%, 720px);
    max-height: min(92vh, 900px);
    display: flex;
    flex-direction: column;
    background: var(--surface);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
    overflow: hidden;
  }
  .health-modal-header {
    display: flex;
    gap: 1rem;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.15rem 1.25rem 0.85rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, rgba(255, 234, 0, 0.12), rgba(192, 38, 211, 0.05));
  }
  .health-modal-eyebrow {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
  }
  .health-modal-header h2 {
    font-size: 1.2rem;
    color: var(--navy);
    margin-bottom: 0.35rem;
  }
  .health-modal-intro {
    font-size: 0.92rem;
    color: var(--text-muted);
    line-height: 1.5;
  }
  .health-modal-close {
    border: none;
    background: transparent;
    font-size: 1.6rem;
    line-height: 1;
    cursor: pointer;
    color: var(--text-muted);
    padding: 0.15rem 0.35rem;
  }
  .health-modal-close:hover { color: var(--navy); }
  .health-modal-body {
    overflow-y: auto;
    padding: 1rem 1.25rem 1.25rem;
    flex: 1;
  }
  .health-member-name {
    font-size: 1.05rem;
    color: var(--navy);
    margin-bottom: 0.35rem;
  }
  .health-checklist {
    display: grid;
    gap: 0.65rem;
  }
  .health-check-item {
    display: flex;
    gap: 0.65rem;
    align-items: flex-start;
    min-height: 44px;
    padding: 0.35rem 0;
    cursor: pointer;
    font-size: 0.92rem;
    line-height: 1.45;
  }
  .health-check-item input {
    margin-top: 0.15rem;
    flex-shrink: 0;
    width: 1.15rem;
    height: 1.15rem;
    accent-color: var(--teal);
  }
  .health-waiver-block {
    margin-top: 1.15rem;
    padding: 0.9rem 1rem;
    border-radius: var(--radius-sm);
    background: var(--bg-subtle);
    border: 1px solid var(--border);
  }
  .health-waiver-block h4 {
    font-size: 0.95rem;
    color: var(--navy);
    margin-bottom: 0.45rem;
  }
  .health-waiver-block p {
    font-size: 0.88rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 0.75rem;
  }
  .health-check-item--waiver span { font-weight: 600; color: var(--text); }
  .health-modal-footer {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: flex-end;
    padding: 0.85rem 1.25rem;
    padding-bottom: calc(0.85rem + env(safe-area-inset-bottom, 0px));
    border-top: 1px solid var(--border);
    background: #fff;
  }
  .health-panel-error { margin-top: 0.85rem; }
  @media (max-width: 540px) {
    .health-modal { padding: 0; align-items: stretch; }
    .health-modal-dialog {
      width: 100%;
      max-height: 100dvh;
      border-radius: 0;
    }
    .health-modal-footer {
      flex-direction: column;
    }
    .health-modal-footer .btn {
      width: 100%;
      min-height: 44px;
      justify-content: center;
    }
  }
</style>

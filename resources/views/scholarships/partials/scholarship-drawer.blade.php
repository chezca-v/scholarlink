@push('styles')
<style>
  :root {
    --sl-teal:         #0F4C5C;
    --sl-teal-mid:     #1a6070;
    --sl-teal-light:   #e0f0f4;
    --sl-amber:        #C9A84C;
    --sl-amber-light:  #F9D679;
    --sl-white:        #FFFFFF;
    --sl-bg:           #F4F6FA;
    --sl-surface:      #EEF2F8;
    --sl-border:       #E2E8F0;
    --sl-text:         #1C1C2E;
    --sl-muted:        #8A95A3;
    --sl-green:        #157a56;
    --sl-green-bg:     #d4f5e7;
    --sl-warn:         #b98a22;
    --sl-warn-bg:      #fff7da;
    --sl-radius-sm:    8px;
    --sl-radius-md:    12px;
    --sl-radius-lg:    20px;
  }

  /* ── OVERLAY ── */
  .sl-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 40, 52, 0.48);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    z-index: 1000;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 0;
    opacity: 0;
    pointer-events: none;
    transition: opacity .28s ease;
  }

  @media (min-width: 640px) {
    .sl-overlay {
      align-items: center;
      padding: 24px;
    }
  }

  .sl-overlay.is-open {
    opacity: 1;
    pointer-events: all;
  }

  /* ── MODAL SHEET ── */
  .sl-modal {
    width: 100%;
    max-width: 460px;
    background: var(--sl-white);
    border-radius: var(--sl-radius-lg) var(--sl-radius-lg) 0 0;
    box-shadow: 0 -4px 40px rgba(15,76,92,0.18);
    position: relative;
    transform: translateY(40px);
    opacity: 0;
    transition: transform .35s cubic-bezier(0.22, 1, 0.36, 1),
                opacity   .28s ease;
    max-height: 92dvh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  @media (min-width: 640px) {
    .sl-modal {
      border-radius: var(--sl-radius-lg);
      box-shadow: 0 24px 64px rgba(15,76,92,0.2), 0 4px 12px rgba(0,0,0,0.06);
      max-height: 88vh;
    }
  }

  .sl-overlay.is-open .sl-modal {
    transform: translateY(0);
    opacity: 1;
  }

  /* ── SCROLLABLE BODY ── */
  .sl-modal-body {
    overflow-y: auto;
    flex: 1;
    padding-bottom: 110px;
  }

  /* ── DRAG HANDLE ── */
  .sl-handle { display: flex; justify-content: center; padding: 12px 0 4px; flex-shrink: 0; }
  .sl-handle span { width: 36px; height: 4px; background: var(--sl-border); border-radius: 99px; }

  /* ── CLOSE BUTTON ── */
  .sl-close {
    position: absolute;
    top: 14px;
    right: 16px;
    width: 30px;
    height: 30px;
    border: 1px solid var(--sl-border);
    border-radius: 8px;
    background: var(--sl-white);
    color: var(--sl-muted);
    font-size: 16px;
    cursor: pointer;
    display: grid;
    place-items: center;
    transition: background .15s, color .15s;
    z-index: 2;
  }
  .sl-close:hover { background: var(--sl-surface); color: var(--sl-teal); }

  /* ── HEADER ── */
  .sl-header { padding: 10px 22px 16px; }

  .sl-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 11px;
    border-radius: 99px;
    margin-bottom: 10px;
    letter-spacing: 0.01em;
  }
  .sl-status-badge.open   { background: var(--sl-green-bg); color: var(--sl-green); }
  .sl-status-badge.warn   { background: var(--sl-warn-bg);  color: var(--sl-warn);  }
  .sl-status-badge.closed { background: #f0f0f0;            color: #666;            }

  .sl-status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; flex-shrink: 0; }

  .sl-name {
    font-family: 'Fraunces', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--sl-text);
    line-height: 1.25;
    margin-bottom: 6px;
  }

  .sl-provider { font-size: 13px; color: var(--sl-teal); font-weight: 400; }
  .sl-provider strong { font-weight: 600; }

  /* ── MATCH SCORE ── */
  .sl-match { padding: 18px 40px; text-align: center; }
  .sl-match-label { font-size: 12px; color: var(--sl-muted); margin-bottom: 4px; letter-spacing: 0.02em; }
  .sl-match-score { font-family: 'Fraunces', serif; font-size: 36px; font-weight: 700; color: #E8A838; line-height: 1.1; margin-bottom: 10px; }
  .sl-bar-track { background: #cce8f0; border-radius: 99px; height: 7px; overflow: hidden; }
  .sl-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #0F4C5C, #E8A838); width: 0%; transition: width .9s cubic-bezier(0.22, 1, 0.36, 1); }

  /* ── DETAIL ROWS ── */
  .sl-details { padding: 16px 22px 0; }
  .sl-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #C8E8E4;
    border-radius: var(--sl-radius-sm);
    padding: 11px 14px;
    margin-bottom: 8px;
    gap: 12px;
  }
  .sl-row-key  { font-size: 13px; color: var(--sl-teal); font-weight: 400; flex-shrink: 0; }
  .sl-row-val  { font-size: 13.5px; color: var(--sl-teal); font-weight: 700; text-align: right; }

  /* ── DIVIDER ── */
  .sl-divider { height: 1px; background: var(--sl-border); margin: 16px 22px 0; }

  /* ── DESCRIPTION ── */
  .sl-desc { padding: 16px 22px 0; }
  .sl-desc p { font-size: 13.5px; color: #4a5568; line-height: 1.7; }

  /* ── ACTION BAR ── */
  .sl-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    gap: 10px;
    padding: 14px 22px 28px;
    background: linear-gradient(to bottom, transparent 0%, var(--sl-white) 28%);
  }

  .sl-btn-apply {
    flex: 1.5;
    background: linear-gradient(135deg, #E8A838, #F9D679);
    color: var(--sl-teal);
    border: none;
    border-radius: var(--sl-radius-md);
    padding: 15px 0;
    font-size: 15px;
    font-weight: 800;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    letter-spacing: 0.01em;
    transition: filter .2s, transform .1s, box-shadow .2s;
  }
  .sl-btn-apply:hover  { filter: brightness(.96); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
  .sl-btn-apply:active { transform: scale(0.97); }

  .sl-btn-save {
    flex: 1;
    background: var(--sl-white);
    color: var(--sl-text);
    border: 1.5px solid var(--sl-border);
    border-radius: var(--sl-radius-md);
    padding: 14px 0;
    font-size: 15px;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: background .15s, border-color .15s, transform .1s;
  }
  .sl-btn-save:hover  { background: var(--sl-surface); border-color: #c0cbd8; }
  .sl-btn-save:active { transform: scale(0.97); }
  .sl-btn-save.saved  { background: var(--sl-teal-light); border-color: var(--sl-teal); color: var(--sl-teal); }

  .sl-save-icon { width: 16px; height: 16px; flex-shrink: 0; }
</style>
@endpush

<div class="sl-overlay" id="slOverlay" role="dialog" aria-modal="true" aria-labelledby="slModalName">

  <div class="sl-modal" id="slModal">

    {{-- Close button --}}
    <button class="sl-close" id="slClose" aria-label="Close">✕</button>

    {{-- Drag handle --}}
    <div class="sl-handle"><span></span></div>

    {{-- Scrollable content --}}
    <div class="sl-modal-body">

      {{-- HEADER --}}
      <div class="sl-header">
        <div class="sl-status-badge" id="slStatusBadge">
          <span class="sl-status-dot"></span>
          <span id="slStatusLabel">Open</span>
          <span>·</span>
          <span id="slSlotsLabel">— slots left</span>
        </div>
        <h2 class="sl-name" id="slModalName">Scholarship Name</h2>
        <p class="sl-provider">
          <strong id="slProvider">Organization</strong>
          <span> · </span>
          <span id="slCoverage">Nationwide</span>
        </p>
      </div>

      {{-- MATCH SCORE --}}
      <div class="sl-match">
        <p class="sl-match-label">Your Match Score</p>
        <p class="sl-match-score" id="slMatchScore">—</p>
        <div class="sl-bar-track">
          <div class="sl-bar-fill" id="slMatchBar"></div>
        </div>
      </div>

      {{-- DETAIL ROWS --}}
      <div class="sl-details">
        <div class="sl-row">
          <span class="sl-row-key">Grant Amount</span>
          <span class="sl-row-val" id="slAmount">—</span>
        </div>
        <div class="sl-row">
          <span class="sl-row-key">GPA / QPI required</span>
          <span class="sl-row-val" id="slGpa">—</span>
        </div>
        <div class="sl-row">
          <span class="sl-row-key">Income Bracket</span>
          <span class="sl-row-val" id="slIncome">—</span>
        </div>
        <div class="sl-row">
          <span class="sl-row-key">Program</span>
          <span class="sl-row-val" id="slProgram">—</span>
        </div>
        <div class="sl-row">
          <span class="sl-row-key">Deadline</span>
          <span class="sl-row-val" id="slDeadline">—</span>
        </div>
      </div>

      <div class="sl-divider"></div>

      {{-- DESCRIPTION --}}
      <div class="sl-desc">
        <p id="slDescription">—</p>
      </div>

    </div>{{-- /sl-modal-body --}}

    {{-- ACTION BAR --}}
    <div class="sl-actions">
      <button class="sl-btn-apply" id="slApplyBtn">Apply Now</button>
      <button class="sl-btn-save"  id="slSaveBtn">
        <svg class="sl-save-icon" id="slSaveIcon" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5V15l-5-3-5 3V2.5Z"
            stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
        </svg>
        <span id="slSaveLabel">Save</span>
      </button>
    </div>

  </div>{{-- /sl-modal --}}
</div>{{-- /sl-overlay --}}

@push('scripts')
<script>
(function () {

  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

  let _currentId = null;
  let _isSaved   = false;

  function formatDeadline(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' });
  }

  function extractAmount(benefitsText) {
    if (!benefitsText) return '—';
    const match = benefitsText.match(/₱[\d,]+\/yr/);
    return match ? match[0] : benefitsText;
  }

  /**
   * openModal(data, matchScore, slotsLeft)
   *
   * @param data        scholarship record — see field map in the header comment
   * @param matchScore  0–100  (applications.ai_match_score)
   * @param slotsLeft   remaining slots (scholarships.slots - approved applications count)
   */
  window.openModal = function (data, matchScore, slotsLeft) {
    _currentId = data.id;
    _isSaved   = false;

    /* Status badge */
    const badge = document.getElementById('slStatusBadge');
    badge.className = 'sl-status-badge';
    const sl = (data.status || '').toLowerCase();
    if (sl === 'open')             badge.classList.add('open');
    else if (sl.includes('clos')) badge.classList.add('warn');
    else                           badge.classList.add('closed');
    document.getElementById('slStatusLabel').textContent =
      (data.status || 'Unknown').replace(/\b\w/g, c => c.toUpperCase());
    document.getElementById('slSlotsLabel').textContent = (slotsLeft ?? '?') + ' slots left';

    /* Header */
    document.getElementById('slModalName').textContent = data.name          || '—';
    document.getElementById('slProvider').textContent  = data.provider_name || '—';
    document.getElementById('slCoverage').textContent  = data.coverage      || '—';

    /* Match score */
    const score = Math.round(matchScore ?? 0);
    document.getElementById('slMatchScore').textContent = score + '%';
    document.getElementById('slMatchBar').style.width   = '0%';

    /* Detail rows */
    document.getElementById('slAmount').textContent   = extractAmount(data.benefits);
    document.getElementById('slGpa').textContent      = data.gpa_requirement ? '≥ ' + data.gpa_requirement : '—';
    document.getElementById('slIncome').textContent   = data.income_bracket  || '—';
    document.getElementById('slProgram').textContent  = data.eligibility     || 'Open';
    document.getElementById('slDeadline').textContent = formatDeadline(data.deadline);

    /* Description */
    document.getElementById('slDescription').textContent = data.description || '—';

    /* Reset save button */
    const saveBtn = document.getElementById('slSaveBtn');
    saveBtn.classList.remove('saved');
    document.getElementById('slSaveLabel').textContent = 'Save';
    document.getElementById('slSaveIcon').innerHTML = `
      <path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5V15l-5-3-5 3V2.5Z"
        stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>`;

    /* Open */
    document.getElementById('slOverlay').classList.add('is-open');
    document.body.style.overflow = 'hidden';

    requestAnimationFrame(() => {
      setTimeout(() => {
        document.getElementById('slMatchBar').style.width = score + '%';
      }, 120);
    });
  };

  window.closeModal = function () {
    document.getElementById('slOverlay').classList.remove('is-open');
    document.body.style.overflow = '';
  };

  /* ── Apply Now — navigate to application create route ── */
  document.getElementById('slApplyBtn').addEventListener('click', function () {
    window.location.href = '/applications/create/' + _currentId;
  });

  /* ── Save / Unsave — POST/DELETE /saved-scholarships ── */
  document.getElementById('slSaveBtn').addEventListener('click', function () {
    _isSaved = !_isSaved;
    const btn   = document.getElementById('slSaveBtn');
    const label = document.getElementById('slSaveLabel');
    const icon  = document.getElementById('slSaveIcon');

    if (_isSaved) {
      btn.classList.add('saved');
      label.textContent = 'Saved';
      icon.innerHTML = `
        <path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5V15l-5-3-5 3V2.5Z"
          fill="currentColor" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>`;
      /* POST to saved_scholarships */
      fetch('/saved-scholarships', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ scholarship_id: _currentId }),
      });
    } else {
      btn.classList.remove('saved');
      label.textContent = 'Save';
      icon.innerHTML = `
        <path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5V15l-5-3-5 3V2.5Z"
          stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>`;
      /* DELETE from saved_scholarships */
      fetch('/saved-scholarships/' + _currentId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
      });
    }
  });

  document.getElementById('slClose').addEventListener('click', window.closeModal);
  document.getElementById('slOverlay').addEventListener('click', function (e) {
    if (e.target === this) window.closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') window.closeModal();
  });

})();
</script>
@endpush

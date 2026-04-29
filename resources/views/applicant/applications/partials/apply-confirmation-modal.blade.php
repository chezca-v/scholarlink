{{--
    M1 · Apply Confirmation Modal
    File  : resources/views/applicant/applications/partials/apply-confirmation-modal.blade.php
    Usage : @include('applicant.applications.partials.apply-confirmation-modal')
    Trigger in parent view:
        <button type="button" onclick="openM1Modal()">Apply Now</button>
    Variables from controller:
        $scholarship      → Scholarship model  (->name, ->deadline)
        $attachedDocCount → int
--}}

@pushonce('sl-modal-styles')
<style>
    /* ── ScholarLink Modal Design Tokens ── */
    :root {
        --sl-teal:       #0F4C5C;
        --sl-teal-mid:   #1A6B7A;
        --sl-teal-light: #E8F4F5;
        --sl-mint:       #B5DADA;
        --sl-amber:      #C9A84C;
        --sl-gold:       #F9D679;
        --sl-surface:    #F4F6FA;
        --sl-border:     #E2E8F0;
        --sl-muted:      #8A95A3;
        --sl-dark:       #1C1C2E;
        --sl-red-bg:     #FEE2E2;
        --sl-red-text:   #B91C1C;
        --sl-red-border: #FECACA;
        --sl-green-text: #15803D;
    }
    .sl-overlay {
        position: fixed; inset: 0; z-index: 1000;
        background: rgba(15,40,52,.38);
        backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center; padding: 24px;
        opacity: 0; pointer-events: none; transition: opacity .25s ease;
    }
    .sl-overlay.is-open { opacity: 1; pointer-events: all; }
    .sl-modal {
        background: #fff; border-radius: 20px; width: 100%;
        overflow: hidden;
        box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 64px rgba(15,76,92,.18), 0 4px 16px rgba(0,0,0,.05);
        transform: scale(.94) translateY(10px); opacity: 0;
        transition: transform .35s cubic-bezier(.22,1,.36,1), opacity .28s ease;
        font-family: 'DM Sans', system-ui, sans-serif;
    }
    .sl-overlay.is-open .sl-modal { transform: scale(1) translateY(0); opacity: 1; }
    .sl-modal--sm  { max-width: 440px; }
    .sl-modal--md  { max-width: 520px; }
    .sl-modal--lg  { max-width: 660px; }
    .sl-modal--scroll { max-height: 90vh; overflow-y: auto; }
    .sl-modal-header { padding: 28px 28px 0; display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; }
    .sl-modal-header--center { justify-content: center; text-align: center; }
    .sl-modal-header--teal { background: var(--sl-teal); border-radius: 20px 20px 0 0; padding: 22px 24px; }
    .sl-modal-title { font-family: 'Fraunces', Georgia, serif; font-weight: 700; font-size: 24px; color: var(--sl-teal); line-height: 1.2; }
    .sl-modal-title--white { color: #fff; }
    .sl-modal-subtitle { font-size: 13px; color: var(--sl-muted); margin-top: 4px; }
    .sl-modal-subtitle--amber { color: var(--sl-amber); font-weight: 500; }
    .sl-modal-subtitle--teal { color: var(--sl-teal); opacity: .75; }
    .sl-modal-subtitle--teal-light { color: rgba(255,255,255,.78); }
    .sl-modal-close { width: 32px; height: 32px; min-width: 32px; border-radius: 8px; background: var(--sl-surface); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--sl-muted); font-size: 16px; transition: background .15s, color .15s; flex-shrink: 0; }
    .sl-modal-close:hover { background: var(--sl-border); color: var(--sl-dark); }
    .sl-modal-close--light { background: rgba(255,255,255,.20); color: #fff; }
    .sl-modal-close--light:hover { background: rgba(255,255,255,.30); }
    .sl-modal-body { padding: 20px 28px; }
    .sl-modal-body--centered { padding: 44px 28px; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 16px; }
    .sl-modal-lead { font-size: 14px; color: var(--sl-dark); line-height: 1.65; }
    .sl-divider { border: none; border-top: 1px solid var(--sl-border); margin: 0 -28px; }
    .sl-modal-footer { display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding: 18px 28px 24px; border-top: 1px solid var(--sl-border); background: #fafbfd; }
    .sl-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 11px 24px; border-radius: 12px; font-family: 'DM Sans', system-ui, sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; border: 1.5px solid transparent; text-decoration: none; transition: background .15s, transform .1s; white-space: nowrap; line-height: 1; }
    .sl-btn:active { transform: scale(.97); }
    .sl-btn:disabled { opacity: .45; cursor: not-allowed; }
    .sl-btn--primary { background: var(--sl-teal); color: var(--sl-gold); border-color: var(--sl-teal); }
    .sl-btn--primary:hover:not(:disabled) { background: var(--sl-teal-mid); }
    .sl-btn--secondary { background: transparent; color: var(--sl-teal); border-color: var(--sl-teal); }
    .sl-btn--secondary:hover { background: rgba(15,76,92,.06); }
    .sl-btn--ghost { background: transparent; color: var(--sl-muted); border-color: var(--sl-border); }
    .sl-btn--ghost:hover { background: var(--sl-surface); color: var(--sl-dark); }
    .sl-btn--full { width: 100%; justify-content: center; }
    .sl-detail-rows { display: flex; flex-direction: column; gap: 10px; margin: 18px 0 0; }
    .sl-detail-row { display: flex; align-items: center; justify-content: space-between; background: var(--sl-mint); border-radius: 14px; padding: 14px 18px; }
    .sl-detail-label { font-size: 14px; color: var(--sl-dark); font-weight: 400; }
    .sl-detail-value { font-size: 14px; font-weight: 700; color: var(--sl-teal); }
    .sl-form-group { margin-bottom: 16px; }
    .sl-form-label { display: block; font-size: 13px; font-weight: 600; color: var(--sl-dark); margin-bottom: 7px; }
    .sl-form-input, .sl-form-select, .sl-form-textarea { width: 100%; border: 1.5px solid var(--sl-border); border-radius: 10px; padding: 11px 14px; font-family: 'DM Sans', system-ui, sans-serif; font-size: 14px; color: var(--sl-dark); background: #fff; outline: none; transition: border-color .15s, box-shadow .15s; }
    .sl-form-input:focus, .sl-form-select:focus, .sl-form-textarea:focus { border-color: var(--sl-teal); box-shadow: 0 0 0 3px rgba(15,76,92,.09); }
    .sl-form-textarea { resize: vertical; min-height: 88px; }
    .sl-input-icon-wrap { position: relative; }
    .sl-input-icon-wrap .sl-form-input { padding-right: 42px; }
    .sl-input-icon { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); color: var(--sl-muted); pointer-events: none; }
    .sl-meta-row { display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--sl-dark); margin-bottom: 7px; }
    .sl-meta-label { color: var(--sl-muted); }
    .sl-deadline-badge { background: var(--sl-red-bg); color: var(--sl-red-text); border: 1px solid var(--sl-red-border); border-radius: 100px; padding: 3px 13px; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; }
    .sl-toggle-label { display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 13px 16px; background: var(--sl-teal-light); border-radius: 12px; font-size: 14px; font-weight: 500; color: var(--sl-dark); user-select: none; }
    .sl-toggle-wrap { position: relative; display: inline-block; width: 48px; height: 27px; flex-shrink: 0; }
    .sl-toggle-input { position: absolute; opacity: 0; width: 0; height: 0; }
    .sl-toggle-track { display: block; width: 48px; height: 27px; background: var(--sl-border); border-radius: 100px; position: relative; transition: background .2s; cursor: pointer; }
    .sl-toggle-track::after { content: ''; position: absolute; width: 21px; height: 21px; background: #fff; border-radius: 50%; top: 3px; left: 3px; transition: transform .2s; box-shadow: 0 1px 4px rgba(0,0,0,.22); }
    .sl-toggle-input:checked + .sl-toggle-track { background: var(--sl-teal); }
    .sl-toggle-input:checked + .sl-toggle-track::after { transform: translateX(21px); }
    .sl-preview-frame { background: var(--sl-teal-light); border-radius: 14px; min-height: 260px; display: flex; align-items: center; justify-content: center; }
    .sl-preview-inner { text-align: center; padding: 48px 32px; }
    .sl-preview-label { font-size: 15px; color: var(--sl-teal); margin-top: 12px; }
    .sl-preview-filename { font-size: 13px; color: var(--sl-muted); margin-top: 5px; word-break: break-all; }
    .sl-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .08em; color: var(--sl-muted); text-transform: uppercase; margin-bottom: 9px; }
    .sl-rejection-box { background: var(--sl-red-bg); border: 1px solid var(--sl-red-border); border-radius: 10px; padding: 15px 16px; margin-bottom: 20px; }
    .sl-rejection-box__label { font-size: 11px; font-weight: 700; letter-spacing: .07em; color: var(--sl-red-text); text-transform: uppercase; margin-bottom: 6px; }
    .sl-rejection-box__text { font-size: 14px; color: var(--sl-red-text); line-height: 1.55; }
    .sl-eval-notes-box { background: var(--sl-teal-light); border: 1.5px solid var(--sl-mint); border-radius: 14px; padding: 16px 18px; font-size: 14px; color: var(--sl-dark); line-height: 1.65; margin-bottom: 20px; }
    .sl-alt-item { display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--sl-border); border-radius: 12px; padding: 14px 16px; margin-bottom: 8px; }
    .sl-alt-name { font-size: 14px; font-weight: 500; color: var(--sl-dark); }
    .sl-alt-amount { font-size: 12px; color: var(--sl-muted); margin-top: 3px; }
    .sl-alt-match { background: var(--sl-teal-light); color: var(--sl-teal); font-size: 13px; font-weight: 600; padding: 5px 13px; border-radius: 100px; flex-shrink: 0; }
    .sl-info-box { display: flex; align-items: flex-start; gap: 10px; border-radius: 10px; padding: 13px 15px; font-size: 13px; line-height: 1.55; margin-bottom: 18px; }
    .sl-info-box--warning { background: var(--sl-red-bg); border: 1px solid var(--sl-red-border); color: var(--sl-red-text); }
    .sl-info-box__icon { flex-shrink: 0; font-size: 16px; line-height: 1.3; }
    .sl-weight-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .sl-weight-label { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 500; color: var(--sl-dark); }
    .sl-weight-pct { font-size: 26px; font-weight: 700; color: var(--sl-teal); min-width: 62px; text-align: right; }
    .sl-weight-slider { width: 100%; height: 6px; border-radius: 100px; -webkit-appearance: none; appearance: none; outline: none; cursor: pointer; margin-bottom: 4px; }
    .sl-weight-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 22px; height: 22px; border-radius: 50%; background: var(--sl-teal); border: 3px solid #fff; box-shadow: 0 0 0 2px var(--sl-teal); cursor: pointer; }
    .sl-weight-slider--income::-webkit-slider-thumb { background: var(--sl-amber); box-shadow: 0 0 0 2px var(--sl-amber); }
    .sl-weight-total { display: flex; align-items: baseline; gap: 8px; justify-content: flex-end; margin: 14px 0 6px; }
    .sl-weight-total-label { font-size: 14px; color: var(--sl-muted); }
    .sl-weight-total-value { font-size: 36px; font-weight: 700; color: var(--sl-teal); }
    .sl-weight-valid { font-size: 13px; color: var(--sl-green-text); font-weight: 500; }
    .sl-weight-invalid { font-size: 13px; color: var(--sl-red-text); font-weight: 500; }
    .sl-formula-box { background: var(--sl-teal-light); border-radius: 12px; padding: 16px 20px; text-align: center; margin-top: 6px; }
    .sl-formula-box__formula { font-size: 14px; font-weight: 700; color: var(--sl-teal); }
    .sl-formula-box__sub { font-size: 12px; color: var(--sl-muted); margin-top: 4px; }
    .sl-checkbox-row { display: flex; align-items: center; gap: 10px; margin: 12px 0 4px; }
    .sl-checkbox-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--sl-teal); cursor: pointer; flex-shrink: 0; }
    .sl-checkbox-row label { font-size: 14px; color: var(--sl-dark); cursor: pointer; }
    .sl-success-emoji { font-size: 52px; line-height: 1; }
    .sl-step-hidden { display: none !important; }
    #sl-toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; width: 340px; pointer-events: none; }
    .sl-toast { display: flex; align-items: flex-start; gap: 11px; background: #fff; border-radius: 14px; border: 1px solid var(--sl-border); border-left: 4px solid var(--sl-teal); padding: 14px 15px; box-shadow: 0 8px 28px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.06); position: relative; overflow: hidden; pointer-events: all; animation: sl-toast-in .32s cubic-bezier(.16,1,.3,1) forwards; font-family: 'DM Sans', system-ui, sans-serif; }
    @keyframes sl-toast-in { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .sl-toast--success { border-left-color: #22C55E; }
    .sl-toast--warning { border-left-color: #EAB308; }
    .sl-toast--error   { border-left-color: #EF4444; }
    .sl-toast__icon-wrap { width: 34px; height: 34px; min-width: 34px; background: var(--sl-teal-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
    .sl-toast__body { flex: 1; }
    .sl-toast__title { font-size: 14px; font-weight: 600; color: var(--sl-dark); margin-bottom: 2px; }
    .sl-toast__message { font-size: 13px; color: var(--sl-muted); line-height: 1.45; }
    .sl-toast__close { background: none; border: none; cursor: pointer; color: var(--sl-muted); font-size: 18px; line-height: 1; padding: 2px; border-radius: 4px; display: flex; align-items: center; transition: color .15s; flex-shrink: 0; }
    .sl-toast__close:hover { color: var(--sl-dark); }
    .sl-toast__progress { position: absolute; bottom: 0; left: 0; height: 3px; background: var(--sl-mint); border-radius: 0 0 14px 14px; animation: sl-toast-progress 5s linear forwards; }
    @keyframes sl-toast-progress { from { width: 100%; } to { width: 0%; } }
    .sl-search-wrap { position: relative; margin-bottom: 16px; }
    .sl-search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--sl-muted); pointer-events: none; }
    .sl-search-input { width: 100%; padding: 11px 14px 11px 38px; border: 1.5px solid var(--sl-border); border-radius: 100px; font-family: 'DM Sans', system-ui, sans-serif; font-size: 14px; color: var(--sl-dark); background: var(--sl-teal-light); outline: none; transition: all .15s; }
    .sl-search-input:focus { border-color: var(--sl-teal); background: #fff; }
    .sl-eval-list { display: flex; flex-direction: column; gap: 8px; max-height: 280px; overflow-y: auto; padding-right: 2px; }
    .sl-eval-item { display: flex; align-items: center; gap: 12px; border: 1.5px solid var(--sl-border); border-radius: 14px; padding: 13px 15px; cursor: pointer; transition: border-color .15s, background .15s; }
    .sl-eval-item:hover { background: var(--sl-teal-light); border-color: var(--sl-mint); }
    .sl-eval-item.is-selected { border-color: var(--sl-teal); background: rgba(15,76,92,.04); }
    .sl-eval-item.is-hidden { display: none; }
    .sl-eval-avatar { width: 38px; height: 38px; min-width: 38px; border-radius: 50%; color: #fff; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
    .sl-eval-info { flex: 1; min-width: 0; }
    .sl-eval-name { font-size: 14px; font-weight: 600; color: var(--sl-dark); }
    .sl-eval-role { font-size: 12px; color: var(--sl-muted); margin-top: 2px; }
    .sl-eval-queue { text-align: center; min-width: 56px; }
    .sl-eval-queue-num { display: block; font-size: 16px; font-weight: 700; color: var(--sl-teal); line-height: 1.2; }
    .sl-eval-queue-num--high { color: var(--sl-amber); }
    .sl-eval-queue-label { font-size: 11px; color: var(--sl-muted); }
    .sl-eval-radio { width: 22px; height: 22px; min-width: 22px; border: 2px solid var(--sl-border); border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all .15s; }
    .sl-eval-radio.is-checked { background: var(--sl-teal); border-color: var(--sl-teal); }
    .sl-eval-radio.is-checked::after { content: ''; display: block; width: 8px; height: 8px; background: #fff; border-radius: 50%; }
    .sl-selected-summary { display: flex; align-items: center; justify-content: space-between; background: var(--sl-surface); border: 1px solid var(--sl-border); border-radius: 12px; padding: 12px 16px; margin-top: 12px; font-size: 14px; color: var(--sl-dark); }
    .sl-queue-badge { background: var(--sl-teal-light); color: var(--sl-teal); font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 100px; }
</style>
@endpushonce

<div class="sl-overlay" id="m1Overlay" role="dialog" aria-modal="true" aria-labelledby="m1ModalTitle">
    <div class="sl-modal sl-modal--md">

        <div class="sl-modal-header sl-modal-header--center">
            <h2 class="sl-modal-title" id="m1ModalTitle">Confirm Application</h2>
        </div>

        <div class="sl-modal-body">
            <p class="sl-modal-lead">
                You're about to submit your application for
                <strong>{{ $scholarship->name }}</strong>.
                Please review the details below.
            </p>

            <hr class="sl-divider" style="margin-top: 18px;">

            <div class="sl-detail-rows">
                <div class="sl-detail-row">
                    <span class="sl-detail-label">Scholarship</span>
                    <span class="sl-detail-value">{{ $scholarship->name }}</span>
                </div>
                <div class="sl-detail-row">
                    <span class="sl-detail-label">Documents attached</span>
                    <span class="sl-detail-value">
                        {{ $attachedDocCount }} {{ Str::plural('file', $attachedDocCount) }}
                    </span>
                </div>
                <div class="sl-detail-row">
                    <span class="sl-detail-label">Deadline</span>
                    <span class="sl-detail-value">
                        {{ \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y') }}
                    </span>
                </div>
                <div class="sl-detail-row">
                    <span class="sl-detail-label">Status after submit</span>
                    <span class="sl-detail-value">Under Review</span>
                </div>
            </div>

            <hr class="sl-divider" style="margin-top: 18px; margin-bottom: 0;">
        </div>

        <div class="sl-modal-footer">
            <button type="button" class="sl-btn sl-btn--ghost" onclick="closeM1Modal()">Cancel</button>
            <button type="submit" form="apply-form" class="sl-btn sl-btn--primary">Submit Application</button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function openM1Modal() {
        document.getElementById('m1Overlay').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
    function closeM1Modal() {
        document.getElementById('m1Overlay').classList.remove('is-open');
        document.body.style.overflow = '';
    }
    document.getElementById('m1Overlay').addEventListener('click', function(e) {
        if (e.target === this) closeM1Modal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeM1Modal();
    });
</script>
@endpush
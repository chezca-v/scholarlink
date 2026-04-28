{{--
    resources/views/applicant/documents/index.blade.php
    Route      : GET /applicant/documents   (named: documents.index)
    Controller : DocumentWalletController@index
    Variables  :
        $documents    – Collection<Document> keyed by document_type
        $documentTypes – array<string, string>  e.g. ['psa_birth_certificate' => 'PSA Birth Certificate', ...]
    Document model fields : id, document_type, file_url, status, expiry_date, updated_at
    Document model method : isExpiringSoon()
--}}
@extends('layouts.applicant')

@section('title', 'Document Wallet')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════════
   ScholarLink – Document Wallet
   Design tokens: Brand Guide v1 (Deep Teal + Warm Amber)
═══════════════════════════════════════════════════════ */
:root {
  /* Brand colours */
  --teal:          #0F4C5C;
  --teal-dark:     #0a3a47;
  --amber:         #C9A84C;
  /* Backgrounds */
  --bg:            #FFFFFF;
  --surface:       #F4F6FA;
  --border:        #E2E8F0;
  /* Typography */
  --ink:           #1C1C2E;
  --muted:         #8A95A3;
  /* Status – ok / uploaded */
  --ok-badge-bg:   #C3FBD7;
  --ok-badge-text: #065F46;
  --ok-dot:        #10B981;
  --ok-icon-bg:    #D1FAE5;
  /* Status – expiring */
  --warn-badge-bg: #FEF3C7;
  --warn-text:     #92400E;
  --warn-dot:      #D97706;
  --warn-border:   #D97706;
  --warn-card-bg:  #FFFBEB;
  --warn-icon-bg:  #FEF3C7;
  /* Status – expired */
  --err-badge-bg:  #FEE2E2;
  --err-text:      #991B1B;
  --err-dot:       #EF4444;
  --err-border:    #EF4444;
  --err-card-bg:   #FFF5F5;
  --err-icon-bg:   #FEE2E2;
  /* Status – none */
  --none-bg:       #E2E8F0;
  --none-text:     #64748B;
  --none-icon-bg:  #EAF0F6;
  /* Shadows */
  --shadow:        0 1px 3px rgba(15,76,92,.07), 0 1px 2px rgba(15,76,92,.04);
  --shadow-hover:  0 8px 24px rgba(15,76,92,.11), 0 2px 8px rgba(15,76,92,.06);
  /* Radius */
  --r-card:  14px;
  --r-inner: 10px;
  --r-btn:   8px;
  --r-icon:  10px;
  --r-badge: 100px;
  --r-file:  8px;
  /* Fonts */
  --font-display: 'Fraunces', Georgia, serif;
  --font-ui:      'DM Sans', system-ui, sans-serif;
}

/* ── Page wrapper ───────────────────────────────────── */
.dw {
  font-family: var(--font-ui);
  padding: 44px 48px 80px;
  max-width: 1200px;
  margin: 0 auto;
}

/* ── Page header ────────────────────────────────────── */
.dw__title {
  font-family: var(--font-display);
  font-size: 36px;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -.5px;
  line-height: 1.15;
  margin: 0 0 6px;
}
.dw__sub {
  font-size: 14px;
  color: var(--muted);
  margin: 0 0 32px;
}

/* ── Flash ──────────────────────────────────────────── */
.dw__flash {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 16px;
  border-radius: var(--r-inner);
  font-size: 13.5px;
  font-weight: 500;
  margin-bottom: 24px;
}
.dw__flash--ok    { background:#ECFDF5; color:var(--ok-badge-text); border:1px solid #6EE7B7; }
.dw__flash--error { background:#FFF1F2; color:var(--err-text);      border:1px solid #FCA5A5; }

/* ── Stats row ──────────────────────────────────────── */
.dw__stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 40px;
}
.stat {
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: var(--r-card);
  padding: 22px 24px;
  box-shadow: var(--shadow);
}
.stat__label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 8px;
}
.stat__num {
  font-size: 38px;
  font-weight: 700;
  line-height: 1;
  color: var(--ink);
  margin-bottom: 6px;
}
.stat__num--warn   { color: #D97706; }
.stat__num--danger { color: #EF4444; }
.stat__sub {
  font-size: 13px;
  color: var(--muted);
}

/* ── Document grid ──────────────────────────────────── */
.dw__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

/* ── Document card ──────────────────────────────────── */
.doc-card {
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: var(--r-card);
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  box-shadow: var(--shadow);
  transition: box-shadow .2s, transform .2s;
}
.doc-card:hover {
  box-shadow: var(--shadow-hover);
  transform: translateY(-2px);
}
.doc-card--warn {
  border-color: var(--warn-border);
  background:   var(--warn-card-bg);
}
.doc-card--err {
  border-color: var(--err-border);
  background:   var(--err-card-bg);
}

/* card head: icon + name + badge */
.doc-card__head {
  display: flex;
  align-items: flex-start;
  gap: 14px;
}
.doc-card__icon {
  width: 42px;
  height: 42px;
  min-width: 42px;
  border-radius: var(--r-icon);
  background: var(--none-icon-bg);
  display: flex;
  align-items: center;
  justify-content: center;
}
.doc-card__icon--ok   { background: var(--ok-icon-bg); }
.doc-card__icon--warn { background: var(--warn-icon-bg); }
.doc-card__icon--err  { background: var(--err-icon-bg); }

.doc-card__icon svg { width: 20px; height: 20px; color: var(--muted); }
.doc-card__icon--ok   svg { color: var(--ok-badge-text); }
.doc-card__icon--warn svg { color: var(--warn-text); }
.doc-card__icon--err  svg { color: var(--err-text); }

.doc-card__meta { flex: 1; min-width: 0; }
.doc-card__type {
  font-size: 14px;
  font-weight: 600;
  color: var(--ink);
  line-height: 1.35;
  margin-bottom: 7px;
}

/* ── Badges ──────────────────────────────────────────── */
.badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: var(--r-badge);
  font-size: 11.5px;
  font-weight: 600;
  line-height: 1.2;
  white-space: nowrap;
}
.badge__dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  flex-shrink: 0;
}
.badge--ok {
  background: var(--ok-badge-bg);
  color: var(--ok-badge-text);
}
.badge--ok .badge__dot    { background: var(--ok-dot); }
.badge--warn {
  background: var(--warn-badge-bg);
  color: var(--warn-text);
}
.badge--warn .badge__dot  { background: var(--warn-dot); }
.badge--err {
  background: var(--err-badge-bg);
  color: var(--err-text);
}
.badge--err .badge__dot   { background: var(--err-dot); }
.badge--none {
  background: var(--none-bg);
  color: var(--none-text);
}

/* ── File row ────────────────────────────────────────── */
.doc-file {
  display: flex;
  align-items: center;
  gap: 12px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--r-file);
  padding: 11px 14px;
}
.doc-card--warn .doc-file {
  background: rgba(255,251,235,.9);
  border-color: #FDE68A;
}
.doc-card--err .doc-file {
  background: rgba(255,241,242,.9);
  border-color: #FCA5A5;
}
.doc-file__thumb {
  width: 30px;
  height: 30px;
  min-width: 30px;
  border-radius: 6px;
  background: var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
}
.doc-file__thumb svg { width: 14px; height: 14px; color: var(--muted); }
.doc-file__info { flex: 1; min-width: 0; }
.doc-file__name {
  font-size: 13px;
  font-weight: 600;
  color: var(--ink);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.doc-file__meta {
  font-size: 11.5px;
  color: var(--muted);
  margin-top: 2px;
}

/* used-in */
.doc-used     { font-size: 12.5px; color: var(--teal); font-weight: 500; }
.doc-used--no { font-size: 12.5px; color: var(--muted); }

/* ── Dropzone ────────────────────────────────────────── */
.doc-drop {
  border: 1.5px dashed #CBD5E1;
  border-radius: var(--r-inner);
  padding: 28px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: border-color .18s, background .18s;
  text-align: center;
}
.doc-drop:hover {
  border-color: var(--teal);
  background: rgba(15,76,92,.03);
}
.doc-drop input[type="file"] { display: none; }
.doc-drop__icon svg { width: 32px; height: 32px; color: #94A3B8; }
.doc-drop__label {
  font-size: 13px;
  font-weight: 500;
  color: var(--muted);
}
.doc-drop__hint {
  font-size: 12px;
  color: var(--muted);
}
.doc-drop__browse {
  color: var(--teal);
  font-weight: 600;
  text-decoration: underline;
  text-underline-offset: 2px;
}

/* ── Buttons ─────────────────────────────────────────── */
.doc-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: auto;
}
.doc-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  padding: 10px 14px;
  border-radius: var(--r-btn);
  border: 1.5px solid var(--border);
  background: var(--bg);
  color: var(--ink);
  font-family: var(--font-ui);
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: border-color .15s, color .15s, background .15s;
  white-space: nowrap;
  width: 100%;
  text-align: center;
}
.doc-btn svg { width: 14px; height: 14px; }
.doc-btn:hover {
  border-color: var(--teal);
  color: var(--teal);
  background: rgba(15,76,92,.04);
}
.doc-btn--danger {
  border-color: var(--err-border);
  color: var(--err-text);
}
.doc-btn--danger:hover {
  background: var(--err-badge-bg);
  border-color: var(--err-border);
  color: var(--err-text);
}

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 1100px) {
  .dw { padding: 32px 32px 60px; }
  .dw__stats { grid-template-columns: repeat(2, 1fr); }
  .dw__grid  { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .dw { padding: 24px 20px 60px; }
  .dw__stats { grid-template-columns: 1fr 1fr; }
  .dw__grid  { grid-template-columns: 1fr; }
  .dw__title { font-size: 28px; }
}
</style>
@endpush

@section('content')

@php
  /*
   * Compute summary stats from $documents collection.
   * No values are hardcoded — everything derives from DB-fetched data.
   */
  $allDocs           = $documents->values();
  $uploadedCount     = $allDocs->filter(fn($d) => $d->file_url)->count();
  $totalTypes        = count($documentTypes);
  $expiringSoonCount = $allDocs->filter(fn($d) =>
                           $d->file_url
                           && $d->expiry_date
                           && ! \Carbon\Carbon::parse($d->expiry_date)->isPast()
                           && $d->isExpiringSoon()
                       )->count();
  $expiredCount      = $allDocs->filter(fn($d) =>
                           $d->file_url
                           && $d->expiry_date
                           && \Carbon\Carbon::parse($d->expiry_date)->isPast()
                       )->count();
  $usedInCount       = auth()->user()
                           ->applications()
                           ->whereIn('status', ['pending', 'under_review', 'approved'])
                           ->count();
@endphp

<div class="dw">

  {{-- Flash messages --}}
  @if (session('success'))
    <div class="dw__flash dw__flash--ok">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="dw__flash dw__flash--error">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Page header --}}
  <h1 class="dw__title">Document Wallet</h1>
  <p class="dw__sub">Upload once, reuse across all your scholarship applications.</p>

  {{-- ── Summary stats ── --}}
  <div class="dw__stats">
    <div class="stat">
      <div class="stat__label">Uploaded</div>
      <div class="stat__num">{{ $uploadedCount }}</div>
      <div class="stat__sub">of {{ $totalTypes }} document types</div>
    </div>
    <div class="stat">
      <div class="stat__label">Expiring Soon</div>
      <div class="stat__num stat__num--warn">{{ $expiringSoonCount }}</div>
      <div class="stat__sub">within 30 days</div>
    </div>
    <div class="stat">
      <div class="stat__label">Expired</div>
      <div class="stat__num stat__num--danger">{{ $expiredCount }}</div>
      <div class="stat__sub">needs replacement</div>
    </div>
    <div class="stat">
      <div class="stat__label">Used In</div>
      <div class="stat__num">{{ $usedInCount }}</div>
      <div class="stat__sub">active applications</div>
    </div>
  </div>

  {{-- ── Document cards ──
       $documentTypes  => array<typeKey, typeLabel>
       $documents      => Collection keyed by document_type
  ── --}}
  <div class="dw__grid">
    @foreach ($documentTypes as $typeKey => $typeLabel)
      @php
        $doc        = $documents->get($typeKey);   // Document|null
        $hasFile    = $doc && $doc->file_url;

        $isExpired  = $hasFile
                      && $doc->expiry_date
                      && \Carbon\Carbon::parse($doc->expiry_date)->isPast();

        $isExpiring = $hasFile && ! $isExpired && $doc->isExpiringSoon();

        /* Visual state helpers */
        $cardClass  = $isExpired  ? 'doc-card--err'
                    : ($isExpiring ? 'doc-card--warn' : '');

        $iconClass  = $isExpired  ? 'doc-card__icon--err'
                    : ($isExpiring ? 'doc-card__icon--warn'
                    : ($hasFile    ? 'doc-card__icon--ok' : ''));

        $badgeClass = $isExpired  ? 'badge--err'
                    : ($isExpiring ? 'badge--warn'
                    : ($hasFile    ? 'badge--ok'   : 'badge--none'));

        $badgeText  = $isExpired  ? 'Expired ' . \Carbon\Carbon::parse($doc->expiry_date)->format('M d, Y')
                    : ($isExpiring ? '⚠ Expires ' . \Carbon\Carbon::parse($doc->expiry_date)->format('M d, Y')
                    : ($hasFile    ? 'Uploaded'   : 'Not Uploaded'));
      @endphp

      <div class="doc-card {{ $cardClass }}">

        {{-- ── Head: icon + type label + status badge ── --}}
        <div class="doc-card__head">
          <div class="doc-card__icon {{ $iconClass }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1.75"
                 stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
          </div>

          <div class="doc-card__meta">
            <div class="doc-card__type">{{ $typeLabel }}</div>
            <span class="badge {{ $badgeClass }}">
              @if ($hasFile && ! $isExpired)
                <span class="badge__dot"></span>
              @endif
              {{ $badgeText }}
            </span>
          </div>
        </div>

        {{-- ── Uploaded: file info + actions ── --}}
        @if ($hasFile)

          {{-- File row --}}
          <div class="doc-file">
            <div class="doc-file__thumb">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
              </svg>
            </div>
            <div class="doc-file__info">
              <div class="doc-file__name">{{ basename($doc->file_url) }}</div>
              <div class="doc-file__meta">— · Uploaded {{ $doc->updated_at->diffForHumans() }}</div>
            </div>
          </div>

          {{-- Used-in count --}}
          @php $usedIn = $doc->applicationDocuments()->count(); @endphp
          @if ($usedIn > 0)
            <p class="doc-used">Used in {{ $usedIn }} {{ Str::plural('application', $usedIn) }}</p>
          @else
            <p class="doc-used--no">Not used in any application yet</p>
          @endif

          {{-- Preview + Replace --}}
          <div class="doc-actions">

            {{-- Preview --}}
            <a href="{{ route('documents.preview', $doc->id) }}"
               target="_blank"
               class="doc-btn">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              Preview
            </a>

            {{-- Replace (re-uses documents.store, controller detects existing doc) --}}
            <form method="POST"
                  action="{{ route('documents.store') }}"
                  enctype="multipart/form-data"
                  id="replace-{{ $typeKey }}">
              @csrf
              <input type="hidden" name="document_type" value="{{ $typeKey }}">
              <input type="file"
                     id="file-replace-{{ $typeKey }}"
                     name="file"
                     accept=".pdf,.jpg,.jpeg,.png"
                     onchange="this.closest('form').submit()">
              <button type="button"
                      class="doc-btn {{ $isExpired ? 'doc-btn--danger' : '' }}"
                      onclick="document.getElementById('file-replace-{{ $typeKey }}').click()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Replace
              </button>
            </form>

          </div>

        {{-- ── Empty: dropzone ── --}}
        @else

          <form method="POST"
                action="{{ route('documents.store') }}"
                enctype="multipart/form-data"
                id="upload-{{ $typeKey }}">
            @csrf
            <input type="hidden" name="document_type" value="{{ $typeKey }}">

            <label class="doc-drop"
                   for="file-upload-{{ $typeKey }}"
                   ondragover="event.preventDefault(); this.style.borderColor='var(--teal)'"
                   ondragleave="this.style.borderColor=''"
                   ondrop="
                     event.preventDefault();
                     this.style.borderColor='';
                     var dt = new DataTransfer();
                     dt.items.add(event.dataTransfer.files[0]);
                     document.getElementById('file-upload-{{ $typeKey }}').files = dt.files;
                     document.getElementById('upload-{{ $typeKey }}').submit();
                   ">
              <input type="file"
                     id="file-upload-{{ $typeKey }}"
                     name="file"
                     accept=".pdf,.jpg,.jpeg,.png"
                     onchange="this.closest('form').submit()">

              <div class="doc-drop__icon">
                {{-- Paperclip --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.4"
                     stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                </svg>
              </div>
              <span class="doc-drop__label">Drop your document here</span>
              <span class="doc-drop__hint">
                PDF, JPG, PNG up to 5MB · <span class="doc-drop__browse">Browse Files</span>
              </span>
            </label>
          </form>

        @endif

      </div>{{-- /.doc-card --}}
    @endforeach
  </div>{{-- /.dw__grid --}}

</div>{{-- /.dw --}}

@endsection

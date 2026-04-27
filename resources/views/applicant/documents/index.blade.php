@extends('layouts.app')

@section('title', 'Document Wallet — ScholarLink')

@push('styles')
<style>
    /* ── ScholarLink Design Tokens ── */
    :root {
        --sl-deep:        #0E3240;
        --sl-teal:        #1A5269;
        --sl-teal-mid:    #236B83;
        --sl-teal-light:  #3D8FA8;
        --sl-teal-pale:   #D6EBF2;
        --sl-amber:       #E8A020;
        --sl-amber-light: #FBE8BC;
        --sl-bg:          #EBF4F8;
        --sl-surface:     #FFFFFF;
        --sl-border:      #C9DDE6;
        --sl-text:        #1A2E38;
        --sl-muted:       #5E7F8E;
        --sl-success:     #2C8C5E;
        --sl-success-bg:  #D7F0E4;
        --sl-warning:     #B86A00;
        --sl-warning-bg:  #FEF3D6;
        --sl-danger:      #C0392B;
        --sl-danger-bg:   #FDECEA;
        --sl-radius-sm:   6px;
        --sl-radius-md:   10px;
        --sl-radius-lg:   14px;
        --sl-shadow-sm:   0 1px 3px rgba(14,50,64,.07);
        --sl-shadow-md:   0 4px 12px rgba(14,50,64,.10);
    }

    body { background: var(--sl-bg); }

    /* ── Page Header ── */
    .dw-header h1 {
        font-family: 'DM Serif Display', Georgia, serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--sl-deep);
        line-height: 1.2;
    }
    .dw-header p { color: var(--sl-muted); font-size: .9375rem; }

    /* ── Stats Row ── */
    .dw-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
    .dw-stat-card {
        background: var(--sl-surface);
        border: 1px solid var(--sl-border);
        border-radius: var(--sl-radius-lg);
        padding: 1.25rem 1.5rem;
        box-shadow: var(--sl-shadow-sm);
    }
    .dw-stat-card .stat-label {
        font-size: .6875rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--sl-muted);
        margin-bottom: .35rem;
    }
    .dw-stat-card .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--sl-deep);
        line-height: 1;
    }
    .dw-stat-card .stat-sub {
        font-size: .8125rem;
        color: var(--sl-muted);
        margin-top: .2rem;
    }
    .dw-stat-card.stat--warning .stat-value { color: var(--sl-warning); }
    .dw-stat-card.stat--danger  .stat-value { color: var(--sl-danger); }

    /* ── Document Grid ── */
    .dw-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.125rem;
    }

    /* ── Document Card (base) ── */
    .doc-card {
        background: var(--sl-surface);
        border: 1px solid var(--sl-border);
        border-radius: var(--sl-radius-lg);
        padding: 1.25rem;
        box-shadow: var(--sl-shadow-sm);
        display: flex;
        flex-direction: column;
        gap: .875rem;
        transition: box-shadow .15s;
    }
    .doc-card:hover { box-shadow: var(--sl-shadow-md); }

    /* State modifiers */
    .doc-card.is-expiring { border-color: var(--sl-amber); }
    .doc-card.is-expired  { border-color: var(--sl-danger); }

    /* ── Card Header ── */
    .doc-card-head { display: flex; align-items: flex-start; gap: .75rem; }
    .doc-icon {
        width: 38px; height: 38px;
        border-radius: var(--sl-radius-sm);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .doc-icon.icon--teal    { background: var(--sl-teal-pale); color: var(--sl-teal); }
    .doc-icon.icon--amber   { background: var(--sl-amber-light); color: var(--sl-warning); }
    .doc-icon.icon--danger  { background: var(--sl-danger-bg); color: var(--sl-danger); }
    .doc-icon.icon--neutral { background: #EEF2F4; color: #8AA3AD; }
    .doc-icon svg { width: 18px; height: 18px; }

    .doc-meta { flex: 1; min-width: 0; }
    .doc-name {
        font-size: .875rem;
        font-weight: 700;
        color: var(--sl-deep);
        line-height: 1.3;
    }
    .doc-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .6875rem;
        font-weight: 700;
        padding: .2rem .55rem;
        border-radius: 99px;
        margin-top: .3rem;
        line-height: 1.4;
    }
    .badge--uploaded  { background: var(--sl-success-bg); color: var(--sl-success); }
    .badge--expiring  { background: var(--sl-warning-bg); color: var(--sl-warning); }
    .badge--expired   { background: var(--sl-danger-bg);  color: var(--sl-danger); }
    .badge--empty     { background: #EEF2F4; color: var(--sl-muted); }
    .badge-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
        display: inline-block;
    }

    /* ── File Row (when uploaded) ── */
    .doc-file-row {
        display: flex;
        align-items: center;
        gap: .625rem;
        background: #F4F8FA;
        border: 1px solid var(--sl-border);
        border-radius: var(--sl-radius-sm);
        padding: .625rem .75rem;
    }
    .doc-file-row .file-icon { color: var(--sl-muted); flex-shrink: 0; }
    .doc-file-row .file-icon svg { width: 16px; height: 16px; }
    .doc-file-info { flex: 1; min-width: 0; }
    .doc-file-name {
        font-size: .8125rem;
        font-weight: 600;
        color: var(--sl-teal);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .doc-file-meta { font-size: .725rem; color: var(--sl-muted); margin-top: .05rem; }

    /* ── Dropzone ── */
    .doc-dropzone {
        border: 1.5px dashed var(--sl-border);
        border-radius: var(--sl-radius-md);
        background: #F8FBFD;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        cursor: pointer;
        transition: border-color .15s, background .15s;
        text-align: center;
        min-height: 100px;
    }
    .doc-dropzone:hover {
        border-color: var(--sl-teal-light);
        background: var(--sl-teal-pale);
    }
    .doc-dropzone .dz-icon { color: #B0C8D2; }
    .doc-dropzone .dz-icon svg { width: 28px; height: 28px; }
    .doc-dropzone .dz-label {
        font-size: .8125rem;
        font-weight: 600;
        color: var(--sl-muted);
    }
    .doc-dropzone .dz-hint {
        font-size: .725rem;
        color: #9AB5BF;
    }
    .doc-dropzone .dz-browse {
        color: var(--sl-teal-light);
        font-weight: 700;
        cursor: pointer;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    /* ── Used-in link ── */
    .doc-used-in {
        font-size: .8rem;
        color: var(--sl-teal-light);
        font-weight: 600;
        text-decoration: none;
    }
    .doc-used-in:hover { text-decoration: underline; }

    /* ── Action Buttons ── */
    .doc-actions { display: flex; gap: .625rem; margin-top: auto; }
    .btn-doc {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .375rem;
        padding: .55rem 0;
        border-radius: var(--sl-radius-sm);
        font-size: .8125rem;
        font-weight: 600;
        border: 1.5px solid;
        cursor: pointer;
        transition: background .15s, color .15s;
        text-decoration: none;
    }
    .btn-doc svg { width: 15px; height: 15px; }
    .btn-doc--preview {
        border-color: var(--sl-border);
        color: var(--sl-text);
        background: transparent;
    }
    .btn-doc--preview:hover { background: #F0F6F9; }
    .btn-doc--replace {
        border-color: var(--sl-border);
        color: var(--sl-text);
        background: transparent;
    }
    .btn-doc--replace:hover { background: #F0F6F9; }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .dw-stats { grid-template-columns: repeat(2, 1fr); }
        .dw-grid  { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .dw-stats { grid-template-columns: 1fr 1fr; }
        .dw-grid  { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4" style="max-width: 1200px; margin: 0 auto;">

    {{-- ── Page Header ── --}}
    <div class="dw-header mb-4">
        <h1>Document Wallet</h1>
        <p>Upload once, reuse across all your scholarship applications.</p>
    </div>

    {{-- ── Stats Row ── --}}
    <div class="dw-stats mb-4">

        <div class="dw-stat-card">
            <div class="stat-label">Uploaded</div>
            <div class="stat-value">{{ $stats['uploaded'] }}</div>
            <div class="stat-sub">of {{ $totalDocumentTypes }} document types</div>
        </div>

        <div class="dw-stat-card stat--warning">
            <div class="stat-label">Expiring Soon</div>
            <div class="stat-value">{{ $stats['expiring_soon'] }}</div>
            <div class="stat-sub">within 30 days</div>
        </div>

        <div class="dw-stat-card stat--danger">
            <div class="stat-label">Expired</div>
            <div class="stat-value">{{ $stats['expired'] }}</div>
            <div class="stat-sub">needs replacement</div>
        </div>

        <div class="dw-stat-card">
            <div class="stat-label">Used In</div>
            <div class="stat-value">{{ $stats['used_in'] }}</div>
            <div class="stat-sub">active applications</div>
        </div>

    </div>

    {{-- ── Document Grid ── --}}
    <div class="dw-grid">

        @foreach ($documentTypes as $type)

            {{-- Look up the uploaded document for this type (null if not yet uploaded) --}}
            @php
                $doc = $documents->firstWhere('document_type', $type['key']);
                $isUploaded  = $doc && $doc->status === 'uploaded';
                $isExpiring  = $doc && $doc->status === 'expiring_soon';
                $isExpired   = $doc && $doc->status === 'expired';
                $hasFile     = $doc && $doc->file_path;
                $cardClass   = $isExpiring ? 'is-expiring' : ($isExpired ? 'is-expired' : '');
                $iconClass   = $isExpiring ? 'icon--amber'  : ($isExpired ? 'icon--danger' : ($hasFile ? 'icon--teal' : 'icon--neutral'));
            @endphp

            <div class="doc-card {{ $cardClass }}">

                {{-- Card Header --}}
                <div class="doc-card-head">
                    <div class="doc-icon {{ $iconClass }}">
                        {{-- Document icon SVG --}}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>

                    <div class="doc-meta">
                        <div class="doc-name">{{ $type['label'] }}</div>

                        @if ($isUploaded)
                            <span class="doc-badge badge--uploaded">
                                <span class="badge-dot"></span> Uploaded
                            </span>

                        @elseif ($isExpiring)
                            <span class="doc-badge badge--expiring">
                                ⚠ Expires {{ \Carbon\Carbon::parse($doc->expiry_date)->format('M d, Y') }}
                            </span>

                        @elseif ($isExpired)
                            <span class="doc-badge badge--expired">
                                Expired {{ \Carbon\Carbon::parse($doc->expiry_date)->format('M d, Y') }}
                            </span>

                        @else
                            <span class="doc-badge badge--empty">Not Uploaded</span>
                            @if (!empty($type['required_by']))
                                <span class="doc-badge badge--empty ms-1" style="background: #EBF4F8; color: var(--sl-teal);">
                                    {{ $type['required_by'] }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- File Row or Dropzone --}}
                @if ($hasFile)
                    <div class="doc-file-row">
                        <span class="file-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                                <polyline points="13 2 13 9 20 9"/>
                            </svg>
                        </span>
                        <div class="doc-file-info">
                            <div class="doc-file-name">{{ basename($doc->file_path) }}</div>
                            <div class="doc-file-meta">
                                {{ $doc->file_size_formatted }} &middot;
                                Uploaded {{ $doc->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Dropzone --}}
                    <label class="doc-dropzone" for="upload-{{ $type['key'] }}">
                        <span class="dz-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                        </span>
                        <span class="dz-label">Drop your document here</span>
                        <span class="dz-hint">
                            PDF, JPG, PNG up to 5MB &middot;
                            <span class="dz-browse">Browse Files</span>
                        </span>
                        <input
                            type="file"
                            id="upload-{{ $type['key'] }}"
                            name="document[{{ $type['key'] }}]"
                            accept=".pdf,.jpg,.jpeg,.png"
                            style="display: none;"
                            onchange="handleFileUpload(event, '{{ $type['key'] }}')"
                        >
                    </label>
                @endif

                {{-- Used In count --}}
                @if ($hasFile && $doc->used_in_count > 0)
                    <a href="{{ route('applicant.applications.index') }}" class="doc-used-in">
                        Used in {{ $doc->used_in_count }} {{ Str::plural('application', $doc->used_in_count) }}
                    </a>
                @elseif ($hasFile)
                    <span class="doc-used-in" style="color: var(--sl-muted); cursor: default; font-weight: 500; font-size: .8rem;">
                        Not used in any application yet
                    </span>
                @endif

                {{-- Action Buttons (only when a file exists) --}}
                @if ($hasFile)
                    <div class="doc-actions">
                        <a
                            href="{{ route('applicant.documents.preview', $doc->id) }}"
                            class="btn-doc btn-doc--preview"
                            target="_blank"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Preview
                        </a>

                        <label class="btn-doc btn-doc--replace" for="replace-{{ $type['key'] }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="16 16 12 12 8 16"/>
                                <line x1="12" y1="12" x2="12" y2="21"/>
                                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                            </svg>
                            Replace
                            <input
                                type="file"
                                id="replace-{{ $type['key'] }}"
                                name="replace_document[{{ $type['key'] }}]"
                                accept=".pdf,.jpg,.jpeg,.png"
                                style="display: none;"
                                onchange="handleFileReplace(event, '{{ $type['key'] }}', {{ $doc->id }})"
                            >
                        </label>
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Handle new file upload (dropzone).
 * Submits to the documents.store route via fetch.
 */
function handleFileUpload(event, documentType) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('document_type', documentType);
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('{{ route("applicant.documents.store") }}', {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Reload the page to reflect updated state
            window.location.reload();
        } else {
            alert(data.message ?? 'Upload failed. Please try again.');
        }
    })
    .catch(() => alert('Something went wrong. Please try again.'));
}

/**
 * Handle file replacement.
 * Submits to the documents.replace route via fetch.
 */
function handleFileReplace(event, documentType, documentId) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('document_type', documentType);
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('_method', 'PUT');

    fetch(`/applicant/documents/${documentId}`, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message ?? 'Replace failed. Please try again.');
        }
    })
    .catch(() => alert('Something went wrong. Please try again.'));
}
</script>
@endpush
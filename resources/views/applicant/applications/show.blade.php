@extends('layouts.applicant')
@section('title', 'ScholarLink - Application Details')

@section('content')
@php
    $statusMap = [
        'submitted'    => ['label' => 'Submitted',    'color' => '#D97706', 'bg' => '#FFFBEB'],
        'pending'      => ['label' => 'Submitted',    'color' => '#D97706', 'bg' => '#FFFBEB'],
        'under_review' => ['label' => 'Under Review', 'color' => '#7E22CE', 'bg' => '#F3E8FF'],
        'approved'     => ['label' => 'Approved',     'color' => '#16A34A', 'bg' => '#DCFCE7'],
        'rejected'     => ['label' => 'Rejected',     'color' => '#DC2626', 'bg' => '#FEE2E2'],
        'revision'     => ['label' => 'Action Needed','color' => '#0284C7', 'bg' => '#E0F2FE'],
    ];
    $st = $statusMap[$application->status] ?? $statusMap['submitted'];

    $stages = [
        'submitted'  => ['label' => 'Submitted',       'done' => true],
        'doc_review' => ['label' => 'Document Review', 'done' => in_array($application->stage, ['doc_review','scoring','decided'])],
        'scoring'    => ['label' => 'Blind Evaluation', 'done' => in_array($application->stage, ['scoring','decided'])],
        'decided'    => ['label' => 'Decision',        'done' => $application->stage === 'decided'],
    ];
@endphp

@push('styles')
<style>
:root{--teal:#0F4C5C;--teal-mid:#1A6B7A;--teal-light:#E8F4F7;--amber:#C9A84C;--cloud:#F4F6FA;--mist:#E2E8F0;--slate:#8A95A3;--ink:#1C1C2E;}
.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--teal);font-weight:600;font-size:13px;text-decoration:none;margin-bottom:20px;}
.back-link:hover{opacity:.75;}
.detail-grid{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;}
.card{background:#fff;border:1px solid var(--mist);border-radius:16px;overflow:hidden;margin-bottom:20px;}
.card-header{padding:18px 24px;border-bottom:1px solid var(--mist);display:flex;align-items:center;justify-content:space-between;}
.card-title{font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:var(--ink);}
.card-body{padding:20px 24px;}
.field-row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--mist);font-size:13px;}
.field-row:last-child{border-bottom:none;}
.field-key{color:var(--slate);font-weight:500;}
.field-val{font-weight:600;color:var(--ink);}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:999px;font-size:12px;font-weight:700;}
.stage-track{display:flex;align-items:center;gap:0;padding:20px 24px;}
.stage-step{display:flex;flex-direction:column;align-items:center;flex:1;position:relative;}
.stage-step:not(:last-child)::after{content:'';position:absolute;top:14px;left:calc(50% + 14px);right:calc(-50% + 14px);height:2px;background:var(--mist);}
.stage-step.done::after{background:var(--teal);}
.stage-dot{width:28px;height:28px;border-radius:50%;border:2px solid var(--mist);background:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--slate);z-index:1;position:relative;}
.stage-step.done .stage-dot{background:var(--teal);border-color:var(--teal);color:#fff;}
.stage-label{font-size:10px;font-weight:600;color:var(--slate);margin-top:6px;text-align:center;line-height:1.3;}
.stage-step.done .stage-label{color:var(--teal);}
.doc-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--mist);}
.doc-item:last-child{border-bottom:none;}
.doc-icon{width:36px;height:36px;border-radius:8px;background:var(--teal-light);color:var(--teal);font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.doc-name{font-size:13px;font-weight:500;color:var(--ink);}
.doc-type{font-size:11px;color:var(--slate);}
.pill{display:inline-block;padding:2px 8px;border-radius:999px;font-size:10px;font-weight:700;}
.pill-green{background:#DCFCE7;color:#16A34A;}
.pill-amber{background:#FFFBEB;color:#D97706;}
.pill-red{background:#FEE2E2;color:#DC2626;}
</style>
@endpush

<a href="{{ route('applications.index') }}" class="back-link">← Back to My Applications</a>

{{-- Status banner --}}
<div style="background:{{ $st['bg'] }};border:1px solid {{ $st['color'] }}33;border-radius:14px;padding:16px 24px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
    <div>
        <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:{{ $st['color'] }};margin-bottom:4px;">Application Status</div>
        <div style="font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:{{ $st['color'] }};">{{ $st['label'] }}</div>
    </div>
    <div style="text-align:right;">
        <div style="font-size:11px;color:var(--slate);margin-bottom:2px;">Reference Code</div>
        <div style="font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--ink);">{{ $application->reference_code }}</div>
    </div>
</div>

{{-- Stage tracker --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">📊 Application Progress</span></div>
    <div class="stage-track">
        @foreach($stages as $key => $stage)
        <div class="stage-step {{ $stage['done'] ? 'done' : '' }}">
            <div class="stage-dot">{{ $stage['done'] ? '✓' : '' }}</div>
            <div class="stage-label">{{ $stage['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

<div class="detail-grid">
    {{-- Left --}}
    <div>
        {{-- Scholarship Info --}}
        <div class="card">
            <div class="card-header"><span class="card-title">🎓 Scholarship Details</span></div>
            <div class="card-body">
                <div class="field-row"><span class="field-key">Scholarship</span><span class="field-val">{{ $application->scholarship->name ?? '—' }}</span></div>
                <div class="field-row"><span class="field-key">Provider</span><span class="field-val">{{ $application->scholarship->provider_name ?? '—' }}</span></div>
                <div class="field-row"><span class="field-key">Applied On</span><span class="field-val">{{ $application->submitted_at ? \Carbon\Carbon::parse($application->submitted_at)->format('M d, Y') : '—' }}</span></div>
                <div class="field-row"><span class="field-key">Deadline</span><span class="field-val">{{ $application->scholarship->deadline ? \Carbon\Carbon::parse($application->scholarship->deadline)->format('M d, Y') : '—' }}</span></div>
                <div class="field-row"><span class="field-key">Stage</span><span class="field-val">{{ ucwords(str_replace('_',' ',$application->stage)) }}</span></div>
            </div>
        </div>

        {{-- Submitted Documents --}}
        <div class="card">
            <div class="card-header"><span class="card-title">📎 Submitted Documents</span></div>
            <div class="card-body">
                @forelse($application->applicationDocuments as $appDoc)
                @php
                    $doc = $appDoc->document;
                    $ext = strtoupper(pathinfo($doc->file_url ?? '', PATHINFO_EXTENSION));
                    $statusClass = match($doc->status ?? 'pending') {
                        'verified' => 'pill-green',
                        'rejected' => 'pill-red',
                        default    => 'pill-amber',
                    };
                @endphp
                <div class="doc-item">
                    <div class="doc-icon">{{ substr($ext,0,3) ?: '📄' }}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="doc-name">{{ basename($doc->file_url ?? 'document') }}</div>
                        <div class="doc-type">{{ $doc->document_type ?? '—' }}</div>
                    </div>
                    <span class="pill {{ $statusClass }}">{{ ucfirst($doc->status ?? 'pending') }}</span>
                </div>
                @empty
                <p style="font-size:13px;color:var(--slate);text-align:center;padding:20px 0;">No documents attached.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right sidebar --}}
    <div>
        <div class="card">
            <div class="card-header"><span class="card-title">⚡ Quick Actions</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('scholarships.show', $application->scholarship_id) }}"
                   style="display:block;text-align:center;padding:10px;border-radius:10px;background:var(--teal-light);color:var(--teal);font-weight:600;font-size:13px;text-decoration:none;">
                    View Scholarship
                </a>
                <a href="{{ route('applicant.documents.index') }}"
                   style="display:block;text-align:center;padding:10px;border-radius:10px;border:1px solid var(--mist);color:var(--slate);font-weight:600;font-size:13px;text-decoration:none;">
                    Document Wallet
                </a>
                @if(in_array($application->status, ['pending','submitted','under_review']))
                <button onclick="alert('Withdraw feature coming soon')"
                   style="display:block;width:100%;text-align:center;padding:10px;border-radius:10px;background:#FEE2E2;color:#DC2626;font-weight:600;font-size:13px;border:none;cursor:pointer;">
                    Withdraw Application
                </button>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">ℹ️ Timeline</span></div>
            <div class="card-body">
                <div class="field-row"><span class="field-key">Created</span><span class="field-val">{{ $application->created_at->format('M d, Y') }}</span></div>
                <div class="field-row"><span class="field-key">Submitted</span><span class="field-val">{{ $application->submitted_at ? \Carbon\Carbon::parse($application->submitted_at)->format('M d, Y') : '—' }}</span></div>
                <div class="field-row"><span class="field-key">Last Updated</span><span class="field-val">{{ $application->updated_at->format('M d, Y') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection

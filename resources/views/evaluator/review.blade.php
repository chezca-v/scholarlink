@extends('layouts.evaluator')

@section('page_title', 'Application Review')


@section('topnav_actions')
  <a href="{{ route('evaluator.queue') }}" class="btn btn-outline btn-sm">← Back to Queue</a>
@endsection

@section('content')
<div class="breadcrumb">
  <span>Queue</span><span class="sep">/</span><span class="current">Review #{{ $application->reference_code ?? 'A-'.$application->id }}</span>
</div>

<!-- BLIND PROFILE CARD -->
<div class="blind-card" style="margin-bottom:20px">
  <div class="blind-avatar">🕵️</div>
  <div class="blind-fields">
    <div>
      <div class="label-row">Name</div>
      <div class="field-val">
        @if($blindScreening)
          <span class="masked">Hidden (Blind Mode)</span>
        @else
          {{ $application->applicant->first_name ?? '' }} {{ $application->applicant->last_name ?? '' }}
        @endif
      </div>
    </div>
    <div>
      <div class="label-row">School</div>
      <div class="field-val">
        @if($blindScreening)
          <span class="masked">Hidden (Blind Mode)</span>
        @else
          {{ $application->applicant->applicantProfile->university_name ?? 'Unknown' }}
        @endif
      </div>
    </div>
    <div>
      <div class="label-row">Application ID</div>
      <div class="field-val">#{{ $application->reference_code ?? 'A-'.$application->id }}</div>
    </div>
    <div>
      <div class="label-row">GPA</div>
      <div class="field-val">{{ $application->applicant->applicantProfile->gwa ?? 'N/A' }}</div>
    </div>
    <div>
      <div class="label-row">Family Income</div>
      <div class="field-val">₱{{ number_format(($application->applicant->applicantProfile->monthly_household_income ?? 0) * 12) }} / year</div>
    </div>
    <div>
      <div class="label-row">AI Match Score</div>
      <div class="field-val" style="color:var(--accent-light)">{{ $application->ai_match_score ?? 0 }}% match ✨</div>
    </div>
  </div>
  <div style="text-align:center;border-left:1px solid rgba(255,255,255,.15);padding-left:16px;flex-shrink:0">
    <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.5);margin-bottom:6px">Scholarship</div>
    <div style="font-size:13px;font-weight:700;color:white;line-height:1.3">{{ $application->scholarship->name ?? 'Unknown' }}</div>
    <div style="margin-top:8px"><span class="badge priority-high">🔴 High Priority</span></div>
  </div>
</div>

<form action="{{ route('evaluator.review.store', $application->id) }}" method="POST">
  @csrf

  @if($errors->any())
  <div style="background:#FEF2F2;border:1.5px solid #FCA5A5;border-radius:12px;padding:12px 16px;margin-bottom:16px;">
    <div style="font-size:13px;font-weight:600;color:#DC2626;margin-bottom:4px;">Please fix the following issues:</div>
    <ul style="font-size:12px;color:#EF4444;margin:0;padding-left:16px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="grid-2" style="gap:20px;align-items:start">
    <!-- LEFT COLUMN: DOCS + SCORING -->
    <div style="display:flex;flex-direction:column;gap:16px">
      <!-- DOCUMENTS -->
      <div class="card">
        <div class="section-header">
          <div class="section-title">📄 Submitted Documents</div>
          <span class="badge green">{{ $application->applicationDocuments->count() }} uploaded</span>
        </div>

        @forelse($application->applicationDocuments as $doc)
        <div class="doc-item">
          <div class="doc-icon" style="background:#FEF2F2">📄</div>
          <div>
            <div class="doc-name">{{ $doc->document->document_type ?? 'Document' }}</div>
            <div class="doc-meta">Uploaded {{ $doc->created_at->format('M d') }}</div>
          </div>
          <div class="doc-verify" style="display:flex; flex-direction:column; gap:4px; align-items:flex-end;">
            <div style="display:flex; gap:6px;">
              <select name="documents[{{ $doc->id }}][status]" class="form-select form-select-sm" style="width:130px; padding:4px 8px; font-size:12px; height:28px;">
                <option value="pending" {{ $doc->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                <option value="approved" {{ $doc->status === 'approved' ? 'selected' : '' }}>✓ Approved</option>
                <option value="rejected" {{ $doc->status === 'rejected' ? 'selected' : '' }}>✖ Rejected</option>
                <option value="revision_requested" {{ $doc->status === 'revision_requested' ? 'selected' : '' }}>🔄 Revision</option>
              </select>
              <button type="button" class="btn btn-ghost btn-sm">👁 View</button>
            </div>
            <input type="text" name="documents[{{ $doc->id }}][notes]" class="form-input form-input-sm" style="width:200px; padding:4px 8px; font-size:11px; height:24px;" placeholder="Optional note for this document..." value="{{ $doc->evaluator_notes }}">
          </div>
        </div>
        @empty
          <p style="color:var(--slate); font-size:12px;">No documents uploaded.</p>
        @endforelse
      </div>

      <!-- EVALUATOR NOTES -->
      <div class="card">
        <div class="section-title" style="margin-bottom:10px">💬 Evaluator Notes</div>
        <textarea name="notes" class="form-textarea" rows="4" placeholder="Add private notes about this application… (not visible to applicant)">{{ old('notes', $evaluation->notes ?? '') }}</textarea>
        <div style="font-size:11px;color:var(--muted);margin-top:6px">🔒 Private — visible only to evaluators and admins</div>
      </div>
    </div>

    <!-- RIGHT COLUMN: SCORING -->
    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card">
        <div class="section-header">
          <div class="section-title">📊 Scoring Form</div>
          <div class="overall-score-ring">
            <div class="ring-val" id="overall-score-display">{{ old('final_score', $evaluation->final_score ?? 0) }}</div>
            <input type="hidden" name="final_score" id="final_score" value="{{ old('final_score', $evaluation->final_score ?? 0) }}">
          </div>
        </div>
        <p style="font-size:12px;color:var(--slate);margin-bottom:16px">Weights set by admin: GPA {{ $application->scholarship->weight_gpa ?? 60 }}% · Financial Need {{ $application->scholarship->weight_income ?? 40 }}%</p>

        <div class="score-row" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); padding-bottom:10px; margin-bottom:10px;">
          <div class="score-label">GPA Score <small>Weight: {{ $application->scholarship->weight_gpa ?? 60 }}%</small></div>
          <div class="score-val" id="gpa_val" style="font-size:20px; font-weight:700; color:var(--ink);">{{ old('gpa_score', $precomputedGpa) }}</div>
          <input type="hidden" name="gpa_score" id="gpa_score" value="{{ old('gpa_score', $precomputedGpa) }}">
        </div>
        <div class="score-row" style="display:flex; justify-content:space-between; align-items:center;">
          <div class="score-label">Financial Need <small>Weight: {{ $application->scholarship->weight_income ?? 40 }}%</small></div>
          <div class="score-val" id="income_val" style="font-size:20px; font-weight:700; color:var(--ink);">{{ old('income_score', $precomputedIncome) }}</div>
          <input type="hidden" name="income_score" id="income_score" value="{{ old('income_score', $precomputedIncome) }}">
        </div>

        <div style="margin-top:16px;padding:14px;background:var(--page-bg);border-radius:12px;border:1.5px solid var(--border)">
          <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
              <div style="font-size:12px;color:var(--slate);font-weight:500">Computed Overall Score</div>
              <div id="computation_formula" style="font-size:11px;color:var(--slate);margin-top:2px;">
                 (<span id="calc_gpa">{{ old('gpa_score', $precomputedGpa) }}</span> × {{ $application->scholarship->weight_gpa ?? 60 }}%) + (<span id="calc_inc">{{ old('income_score', $precomputedIncome) }}</span> × {{ $application->scholarship->weight_income ?? 40 }}%)
              </div>
            </div>
            <div style="font-family:'Fraunces',serif;font-size:32px;font-weight:900;color:var(--primary)" id="computed_display">
              {{ old('final_score', $evaluation->final_score ?? 0) }}
            </div>
          </div>
          <div style="margin-top:10px">
            <div class="prog-bar-wrap"><div class="prog-bar teal" id="computed_bar" style="width:{{ old('final_score', $evaluation->final_score ?? 0) }}%"></div></div>
            <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--muted);margin-top:4px"><span>0</span><span>Passing: 65</span><span>100</span></div>
          </div>
        </div>
      </div>

      <!-- APPLICANT QUICK FACTS -->
      <div class="card compact">
        <div class="section-title" style="margin-bottom:12px">📋 Application Summary</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
          <div style="background:var(--page-bg);border-radius:10px;padding:10px 12px">
            <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:3px">Course</div>
            <div style="font-size:13px;font-weight:500">{{ $application->applicant->applicantProfile->course_program ?? 'N/A' }}</div>
          </div>
          <div style="background:var(--page-bg);border-radius:10px;padding:10px 12px">
            <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:3px">Year Level</div>
            <div style="font-size:13px;font-weight:500">{{ $application->applicant->applicantProfile->year_level ?? 'N/A' }}</div>
          </div>
          <div style="background:var(--page-bg);border-radius:10px;padding:10px 12px">
            <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:3px">Submitted</div>
            <div style="font-size:13px;font-weight:500">{{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : 'N/A' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- DECISION BAR -->
  <div class="decision-bar" style="margin-top:24px">
    <div class="help">
      <strong>Make a decision for #{{ $application->reference_code ?? 'A-'.$application->id }}</strong>
      Your decision will trigger an automated notification to the applicant.
    </div>
    <button type="submit" name="decision" value="revision_requested" class="btn btn-outline" style="gap:6px">💬 Request Info</button>
    <button type="submit" name="decision" value="rejected" class="btn btn-danger">✖ Reject</button>
    <button type="submit" name="decision" value="approved" class="btn btn-green btn-lg">✓ Approve Application</button>
  </div>
</form>

@endsection

@push('scripts')
<script>
  function calculateScore() {
    let gpaW = {{ $application->scholarship->weight_gpa ?? 60 }} / 100;
    let incW = {{ $application->scholarship->weight_income ?? 40 }} / 100;
    let gpa = parseFloat(document.getElementById('gpa_score').value) || 0;
    let inc = parseFloat(document.getElementById('income_score').value) || 0;
    
    let total = (gpa * gpaW) + (inc * incW);
    total = total.toFixed(1);
    
    document.getElementById('overall-score-display').innerText = total;
    document.getElementById('final_score').value = total;
    document.getElementById('computed_display').innerText = total;
    document.getElementById('computed_bar').style.width = total + '%';
  }
</script>
@endpush

@extends('layouts.evaluator')

@section('page_title', 'Rejection Form')


@section('topnav_actions')
  <a href="{{ route('evaluator.review.show', $application->id) }}" class="btn btn-outline btn-sm">← Back to Review</a>
@endsection

@section('content')
<div class="breadcrumb">
  <span>Queue</span><span class="sep">/</span>
  <a href="{{ route('evaluator.review.show', $application->id) }}" style="color:var(--slate); text-decoration:none;">Review #{{ $application->reference_code ?? 'A-'.$application->id }}</a>
  <span class="sep">/</span>
  <span class="current">Rejection Form</span>
</div>

<!-- WARNING -->
<div style="background:var(--red-bg);border:1.5px solid #FECACA;border-radius:14px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:flex-start;gap:12px">
  <span style="font-size:22px;flex-shrink:0">⚠️</span>
  <div>
    <div style="font-size:14px;font-weight:700;color:var(--red)">You are rejecting Application #{{ $application->reference_code ?? 'A-'.$application->id }}</div>
    <div style="font-size:13px;color:#B91C1C;margin-top:3px">This action will notify the applicant. Please provide a clear and constructive reason. The rejection can still be reversed by an Admin if needed.</div>
  </div>
</div>

<form action="{{ route('evaluator.rejection.store', $application->id) }}" method="POST">
  @csrf
  <div class="grid-2" style="gap:20px;align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
      <!-- APP SUMMARY -->
      <div class="card compact" style="border:1.5px solid #FECACA">
        <div class="section-title" style="margin-bottom:12px;color:var(--red)">📋 Application Being Rejected</div>
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px">
          <div class="blind-avatar" style="width:44px;height:44px;font-size:18px;background:rgba(220,38,38,.1);border:2px solid #FECACA">🕵️</div>
          <div>
            <div style="font-weight:600;font-size:14px">Applicant #{{ $application->reference_code ?? 'A-'.$application->id }}</div>
            <div style="font-size:12px;color:var(--slate)">{{ $application->scholarship->name ?? 'Unknown' }}</div>
          </div>
          <span class="badge red" style="margin-left:auto">Rejecting</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
          <div style="background:var(--page-bg);border-radius:8px;padding:8px 10px">
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px">GPA</div>
            <div style="font-size:14px;font-weight:700">{{ $application->applicant->applicantProfile->gwa ?? 'N/A' }}</div>
          </div>
          <div style="background:var(--page-bg);border-radius:8px;padding:8px 10px">
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px">Your Score</div>
            <div style="font-size:14px;font-weight:700;color:var(--red)">{{ $evaluation->final_score ?? 0 }} / 100</div>
          </div>
        </div>
      </div>

      <!-- REASON DROPDOWN -->
      <div class="card">
        <div class="form-group">
          <label class="form-label">Primary Rejection Reason <span class="req">*</span></label>
          <select name="rejection_reason" class="form-select" required>
            <option value="">— Select a reason —</option>
            <option value="gpa" {{ old('rejection_reason') == 'gpa' ? 'selected' : '' }}>GPA below minimum threshold</option>
            <option value="income_bracket" {{ old('rejection_reason') == 'income_bracket' ? 'selected' : '' }}>Income exceeds eligibility limit</option>
            <option value="docs" {{ old('rejection_reason') == 'docs' ? 'selected' : '' }}>Incomplete or invalid documents</option>
            <option value="mismatch" {{ old('rejection_reason') == 'mismatch' ? 'selected' : '' }}>Does not meet scholarship criteria</option>
            <option value="other" {{ old('rejection_reason') == 'other' ? 'selected' : '' }}>Other (specify below)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Written Feedback for Applicant <span class="req">*</span></label>
          <textarea name="notes" class="form-textarea" rows="5" placeholder="Write a constructive explanation for the applicant. Be specific — mention which criteria were not met." required>{{ old('notes') }}</textarea>
          <div style="display:flex;justify-content:space-between;margin-top:4px">
            <div style="font-size:11px;color:var(--muted)">👁 This message will be sent to the applicant via email and SMS.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: ALTERNATIVES -->
    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card">
        <div class="section-header" style="margin-bottom:6px">
          <div class="section-title">💡 Recommend Alternatives</div>
          <span class="badge teal">Optional</span>
        </div>
        <p style="font-size:12px;color:var(--slate);margin-bottom:14px">Suggest other scholarships this applicant may qualify for. These will be included in the rejection notice.</p>

        @foreach($alternatives as $alt)
        <label class="alt-option">
          <input type="checkbox" name="alternative_ids[]" value="{{ $alt->id }}">
          <div>
            <div class="alt-name">{{ $alt->name }}</div>
          </div>
        </label>
        @endforeach
      </div>

      <!-- CONFIRM REJECT -->
      <div style="background:var(--red-bg);border:1.5px solid #FECACA;border-radius:14px;padding:16px 18px">
        <div style="font-size:13px;font-weight:600;color:var(--red);margin-bottom:12px">Confirm Rejection</div>
        <div style="font-size:12px;color:#B91C1C;margin-bottom:14px">By submitting, the applicant will receive a rejection notification with your feedback. This action is logged and can be reversed by an Admin.</div>
        <div style="display:flex;gap:10px">
          <a href="{{ route('evaluator.review.show', $application->id) }}" class="btn btn-outline" style="flex:1; justify-content:center;">← Cancel</a>
          <button type="submit" class="btn btn-danger btn-lg" style="flex:2;border-color:#DC2626; justify-content:center;">✖ Confirm Rejection</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection

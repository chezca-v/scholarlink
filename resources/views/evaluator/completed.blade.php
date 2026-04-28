@extends('layouts.evaluator')

@section('page_title', 'My Completed Reviews')


@section('topnav_actions')
  <button class="btn btn-outline btn-sm">⬇ Export CSV</button>
@endsection

@section('content')
<div class="breadcrumb">
  <span>Dashboard</span><span class="sep">/</span><span class="current">Completed Reviews</span>
</div>

<!-- STATS -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
  <div class="stat-card">
    <div class="label">Total Reviewed</div>
    <div class="value">{{ $evaluations->total() ?? 0 }}</div>
    <div class="delta neutral">This semester</div>
  </div>
  <div class="stat-card">
    <div class="label">Approved</div>
    <div class="value">{{ \App\Models\Evaluation::where('evaluator_id', auth()->id())->where('decision', 'approved')->count() }}</div>
    <div class="delta up" style="color:var(--green)">Approval rate</div>
  </div>
  <div class="stat-card">
    <div class="label">Rejected</div>
    <div class="value">{{ \App\Models\Evaluation::where('evaluator_id', auth()->id())->where('decision', 'rejected')->count() }}</div>
    <div class="delta neutral">Rejection rate</div>
  </div>
  <div class="stat-card">
    <div class="label">Info Requested</div>
    <div class="value">{{ \App\Models\Evaluation::where('evaluator_id', auth()->id())->where('decision', 'revision_requested')->count() }}</div>
    <div class="delta neutral">Pending info</div>
  </div>
</div>

<!-- FILTER -->
<div class="card" style="padding:14px 20px;margin-bottom:16px">
  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
    <div class="search-wrap" style="flex:1;min-width:180px">
      <span class="search-icon">🔍</span>
      <input class="form-input" placeholder="Search by App ID, scholarship…" style="padding-left:34px">
    </div>
    <select class="form-select" style="width:auto">
      <option>All Scholarships</option>
    </select>
    <select class="form-select" style="width:auto">
      <option>All Outcomes</option>
      <option>✅ Approved</option>
      <option>❌ Rejected</option>
      <option>💬 Info Requested</option>
    </select>
    <div style="display:flex;align-items:center;gap:6px">
      <input class="form-input" type="date" style="width:auto" value="{{ now()->subDays(30)->format('Y-m-d') }}">
      <span style="color:var(--muted)">→</span>
      <input class="form-input" type="date" style="width:auto" value="{{ now()->format('Y-m-d') }}">
    </div>
    <button class="btn btn-primary btn-sm">Apply</button>
  </div>
</div>

<!-- COMPLETED TABLE -->
<div class="card" style="padding:0">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>App ID</th>
          <th>Scholarship</th>
          <th>Score Given</th>
          <th>Outcome</th>
          <th class="sorted">Reviewed At ↓</th>
          <th>Rejection Reason</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($evaluations as $eval)
        <tr>
          <td><span style="font-weight:600;color:var(--primary)">#{{ $eval->application->reference_code ?? 'A-'.$eval->application_id }}</span></td>
          <td>
            <div style="font-size:13px;font-weight:500">{{ $eval->application->scholarship->name ?? 'Unknown' }}</div>
            <div style="font-size:11px;color:var(--slate)">GPA: {{ $eval->application->applicant->applicantProfile->gwa ?? 'N/A' }}</div>
          </td>
          <td>
            @php
              $scoreColor = 'var(--slate)';
              if($eval->decision === 'approved') $scoreColor = 'var(--green)';
              elseif($eval->decision === 'rejected') $scoreColor = 'var(--red)';
              elseif($eval->decision === 'revision_requested') $scoreColor = 'var(--yellow)';
            @endphp
            <span style="font-weight:700;font-family:'Fraunces',serif;font-size:16px;color:{{$scoreColor}}">{{ $eval->final_score ?? 0 }}</span>
            <span style="font-size:11px;color:var(--slate)">/100</span>
          </td>
          <td>
            @if($eval->decision === 'approved')
              <span class="badge green">✅ Approved</span>
            @elseif($eval->decision === 'rejected')
              <span class="badge red">❌ Rejected</span>
            @elseif($eval->decision === 'revision_requested')
              <span class="badge teal">💬 Info Req.</span>
            @else
              <span class="badge gray">{{ ucfirst($eval->decision) }}</span>
            @endif
          </td>
          <td style="font-size:12px;color:var(--slate)">{{ $eval->evaluated_at ? $eval->evaluated_at->format('M d · g:i A') : 'N/A' }}</td>
          <td>
            @if($eval->decision === 'rejected' && $eval->rejection_reason)
              <span class="badge gray" style="font-size:10px">{{ Str::title(str_replace('_', ' ', $eval->rejection_reason)) }}</span>
            @else
              <span style="color:var(--muted);font-style:italic;font-size:12px">—</span>
            @endif
          </td>
          <td><button class="btn btn-ghost btn-sm">👁 View</button></td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:20px;color:var(--slate)">No completed reviews yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination" style="padding:12px 20px">
    {{ $evaluations->links() }}
  </div>
</div>
@endsection

@extends('layouts.evaluator')

@section('page_title', 'Review Queue')


@section('content')
<div class="breadcrumb">
  <span>Dashboard</span><span class="sep">/</span><span class="current">Review Queue</span>
</div>

<!-- FILTER BAR -->
<div class="card" style="padding:14px 20px;margin-bottom:16px">
  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
    <div class="search-wrap" style="flex:1;min-width:180px">
      <span class="search-icon">🔍</span>
      <input class="form-input" placeholder="Search by ID, scholarship…" style="padding-left:34px">
    </div>
    <select class="form-select" style="width:auto">
      <option>All Scholarships</option>
    </select>
    <select class="form-select" style="width:auto">
      <option>All Priorities</option>
      <option>🔴 High</option>
      <option>🟡 Medium</option>
      <option>🟢 Low</option>
    </select>
    <select class="form-select" style="width:auto">
      <option>Sort: Oldest First</option>
      <option>Sort: Newest First</option>
      <option>Sort: Priority ↑</option>
      <option>Sort: Score Potential ↓</option>
    </select>
  </div>
  <div style="margin-top:10px;display:flex;gap:8px;align-items:center">
    <span style="font-size:11px;color:var(--slate)">Filter:</span>
    <button class="badge teal" style="cursor:pointer;padding:4px 12px">All ({{ $applications->total() ?? 0 }})</button>
  </div>
</div>

@php
  $hasBlind = $applications->contains(function($app) { return $app->scholarship->blind_screening; });
@endphp
@if($hasBlind)
<!-- BLIND SCREENING NOTICE -->
<div style="background:#EFF6FF;border:1.5px solid #BFDBFE;border-radius:12px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px">
  <span style="font-size:18px">🔒</span>
  <div>
    <div style="font-size:13px;font-weight:600;color:#1D4ED8">Blind Screening Mode is ON</div>
    <div style="font-size:12px;color:#3B82F6">Applicant names and schools are hidden for some scholarships. You are evaluating based on merit only.</div>
  </div>
</div>
@endif

<!-- QUEUE TABLE -->
<div class="card" style="padding:0">
  <div style="padding:14px 20px;border-bottom:1.5px solid var(--border);display:flex;align-items:center;justify-content:space-between">
    <div class="section-title">Pending Applications <small>{{ $applications->total() ?? 0 }} total</small></div>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th class="sorted">App ID ↓</th>
          <th>Scholarship</th>
          <th>Date Submitted</th>
          <th>AI Match Score</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($applications as $app)
        <tr>
          <td><span style="font-weight:600;color:var(--primary)">#{{ $app->reference_code ?? 'A-'.$app->id }}</span></td>
          <td>
            <div style="font-size:13px;font-weight:500">{{ $app->scholarship->name ?? 'Unknown' }}</div>
            <div style="font-size:11px;color:var(--slate)">GPA: {{ $app->applicant->applicantProfile->gwa ?? 'N/A' }} · Income: ₱{{ number_format(($app->applicant->applicantProfile->monthly_household_income ?? 0) * 12) }}/yr</div>
            @if(!$app->scholarship->blind_screening)
            <div style="font-size:11px;color:var(--slate);margin-top:4px">
              <strong>Applicant:</strong> {{ $app->applicant->first_name ?? '—' }} {{ $app->applicant->last_name ?? '' }} ({{ $app->applicant->email ?? '' }})
            </div>
            @endif
          </td>
          <td style="color:var(--slate);font-size:12px">{{ $app->submitted_at ? $app->submitted_at->format('M d, Y g:i A') : 'N/A' }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              @php
                  $match = $app->ai_match_score ?? 0;
                  $color = $match >= 80 ? 'var(--green)' : ($match >= 60 ? 'var(--primary)' : 'var(--accent)');
              @endphp
              <div style="width:50px;height:6px;background:var(--border);border-radius:4px;overflow:hidden">
                <div style="width:{{ $match }}%;height:100%;background:{{ $color }};border-radius:4px"></div>
              </div>
              <span style="font-weight:700;color:{{ $color }}">{{ $match }}%</span>
            </div>
          </td>
          <td><span class="badge priority-med">🟡 Medium</span></td>
          <td>
            @if($app->status === 'pending')
              <span class="badge yellow">⏳ Pending</span>
            @elseif($app->status === 'under_review')
              <span class="badge teal">💬 Under Review</span>
            @elseif($app->status === 'revision')
              <span class="badge amber">🔄 Revision Requested</span>
            @else
              <span class="badge gray">{{ ucfirst($app->status) }}</span>
            @endif
          </td>
          <td><a href="{{ route('evaluator.review.show', $app->id) }}" class="btn btn-primary btn-sm">Start Review →</a></td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:20px;color:var(--slate)">No pending applications in your queue.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination" style="padding:12px 20px">
    {{ $applications->links() }}
  </div>
</div>
@endsection

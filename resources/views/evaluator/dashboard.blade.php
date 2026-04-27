@extends('layouts.evaluator')

@section('page_title', 'Evaluator Dashboard')
@section('page_subtitle', '/evaluator/dashboard')

@section('content')
<div class="breadcrumb">
  <span class="current">Dashboard</span>
</div>

<!-- STATS -->
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
  <div class="stat-card">
    <div class="label">Total Assigned</div>
    <div class="value">{{ $totalAssigned ?? 0 }}</div>
    <div class="delta neutral">Applications to review</div>
  </div>
  <div class="stat-card">
    <div class="label">Completed</div>
    <div class="value">{{ $totalCompleted ?? 0 }}</div>
    <div class="delta up" style="color:var(--green)">Evaluations done</div>
  </div>
  <div class="stat-card">
    <div class="label">Pending in Queue</div>
    <div class="value">{{ ($totalAssigned ?? 0) - ($totalCompleted ?? 0) }}</div>
    <div class="delta neutral">Waiting for you</div>
  </div>
</div>

<div class="grid-2" style="gap:20px; align-items:start;">
  <!-- QUEUE COUNTS PER SCHOLARSHIP -->
  <div class="card">
    <div class="section-title" style="margin-bottom:12px">Scholarship Queues</div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Scholarship</th>
            <th>Pending</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assignments as $assignment)
          <tr>
            <td>
              <div style="font-size:13px;font-weight:500">{{ $assignment->scholarship->name ?? 'Unknown' }}</div>
            </td>
            <td>
              <span class="badge yellow">{{ $queueCounts[$assignment->scholarship_id] ?? 0 }} pending</span>
            </td>
            <td>
              <a href="{{ route('evaluator.queue') }}" class="btn btn-outline btn-sm">View Queue</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" style="text-align:center;padding:20px;color:var(--slate)">No scholarships assigned yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- RECENT COMPLETIONS -->
  <div class="card">
    <div class="section-title" style="margin-bottom:12px">Recent Completions</div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>App ID</th>
            <th>Score</th>
            <th>Outcome</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentCompletions as $eval)
          <tr>
            <td><span style="font-weight:600;color:var(--primary)">#{{ $eval->application->reference_code ?? 'A-'.$eval->application_id }}</span></td>
            <td><span style="font-weight:700;">{{ $eval->final_score ?? 0 }}</span>/100</td>
            <td>
              @if($eval->decision === 'approved')
                <span class="badge green">✅ Approved</span>
              @elseif($eval->decision === 'rejected')
                <span class="badge red">❌ Rejected</span>
              @elseif($eval->decision === 'revision_requested')
                <span class="badge teal">💬 Info Req.</span>
              @endif
            </td>
            <td style="font-size:12px;color:var(--slate)">{{ $eval->evaluated_at ? $eval->evaluated_at->format('M d') : 'N/A' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center;padding:20px;color:var(--slate)">No completed evaluations yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if(count($recentCompletions) > 0)
    <div style="margin-top:12px; text-align:right;">
      <a href="{{ route('evaluator.completed') }}" style="font-size:12px; color:var(--primary); font-weight:600; text-decoration:none;">View All Completions →</a>
    </div>
    @endif
  </div>
</div>
@endsection

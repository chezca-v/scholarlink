@extends('layouts.applicant')
@section('title', 'ScholarLink - My Applications')

@push('styles')
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --teal:#0F4C5C;
  --teal-hover:#0c3f4d;
  --teal-light:#2A8FA0;
  --amber:#C9A84C;
  --amber-light:#F9D679;
  --cloud:#F4F6FA;
  --mist:#E2E8F0;
  --slate:#8A95A3;
  --ink:#1C1C2E;
  --light-green:#F0FAFA;
  --sidebar-w:210px;
}
body{font-family:'DM Sans',sans-serif;background:#F0FAFA;color:var(--ink);min-height:100vh;-webkit-font-smoothing:antialiased;}

/* ── NAVBAR ── */
.navbar{
  background:#FFFF;height:56px;
  display:flex;align-items:center;padding:0 22px;gap:14px;
  position:sticky;top:0;z-index:200;
  box-shadow:0 1px 4px rgba(0,0,0,0.18);
}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0;}
.logo-box{width:32px;height:32px;object-fit:contain;filter:drop-shadow(0 4px 10px rgba(15,76,92,0.18));}
.logo-text{font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:#0F4C5C;letter-spacing:-0.2px;}
.nav-search{flex:1;max-width:440px;margin:0 auto;position:relative;}
.nav-search input{width:100%;height:34px;background:var(--light-green);border:1px solid rgba(15,76,92,0.10);border-radius:30px;padding:0 54px 0 34px;font-family:'DM Sans',sans-serif;font-size:13px;color:var(--teal);outline:none;}
.nav-search input::placeholder{color:rgba(15,76,92,0.48);}
.si{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#0c3f4d;pointer-events:none;display:flex;}
.nav-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
.nav-ibtn{width:35px;height:35px;border-radius:10px;background:var(--light-green);border:2px solid rgba(15,76,92,0.12);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:all 0.2s ease;}
.nav-ibtn:hover{background:rgba(15,76,92,0.12);border-color:rgba(15,76,92,0.25);}
.nbadge{position:absolute;top:5px;right:5px;width:8px;height:8px;border-radius:50%;background:#F9D679;border:1.5px solid var(--teal);}
.nav-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(160deg,#0F4C5C,#2A8FA0);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid rgba(255,255,255,0.35);}

/* ── LAYOUT ── */
.app{display:flex;min-height:calc(100vh - 56px);}

/* ── SIDEBAR ── */
.sidebar{
  width:var(--sidebar-w);flex-shrink:0;
  background:#fff;
  border-right:1px solid var(--mist);
  display:flex;flex-direction:column;
  position:sticky;top:56px;
  height:calc(100vh - 56px);
  overflow-y:auto;
  padding:20px 0 16px;
}
.sidebar::-webkit-scrollbar{width:3px;}
.sidebar::-webkit-scrollbar-thumb{background:var(--mist);}
.sb-section-label{font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--slate);padding:0 18px;margin-bottom:6px;margin-top:18px;}
.sb-section-label:first-of-type{margin-top:0;}
.sb-nav-item{display:flex;align-items:center;gap:10px;padding:8px 18px;font-size:13px;font-weight:500;color:#4a5568;cursor:pointer;border-left:3px solid transparent;transition:all .15s;text-decoration:none;position:relative;}
.sb-nav-item:hover{background:var(--light-green);color:var(--teal);}
.sb-nav-item.active{background:#FDF8EC;color:var(--teal);font-weight:700;border-left-color:var(--teal);}
.sb-badge{margin-left:auto;background:#E8A838;color:#0F4C5C;font-size:10px;font-weight:700;border-radius:20px;padding:1px 7px;min-width:20px;text-align:center;}
.sb-badge-transparent{margin-left:auto;color:#E8A838;font-size:11px;font-weight:700;}
.sb-spacer{flex:1;}
.sb-user{display:flex;align-items:center;gap:10px;padding:12px 16px;margin:0 10px 4px;background:var(--light-green);border:2px solid rgba(15,76,92,0.2);border-radius:14px;}
.sb-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-name{font-size:12.5px;font-weight:600;color:var(--ink);}
.sb-sub{font-size:11px;color:var(--slate);}

/* ── MAIN CONTENT (APPLICATIONS) ── */
.main{flex:1;padding:24px 28px 40px;min-width:0;overflow-y:auto;display:flex;flex-direction:column;}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 24px;
}

.page-title-area {
    display: flex;
    flex-direction: column;
}

.page-eyebrow {
    font-size: 11px;
    font-weight: 700;
    color: #E8A838;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.page-title {
    font-family: 'Fraunces', serif;
    font-size: 28px;
    font-weight: 900;
    color: var(--teal);
    line-height: 1.1;
}

.btn-apply-new {
    background: var(--teal);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-apply-new:hover { background: var(--teal-hover); }

/* Tabs */
.tabs-container {
    display: flex;
    gap: 0;
    background: #fff;
    border: 1px solid var(--mist);
    border-radius: 8px;
    margin-bottom: 20px;
    overflow: hidden;
    width: fit-content;
}

.tab {
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 700;
    color: var(--teal-light);
    cursor: pointer;
    background: transparent;
    border: none;
    border-right: 1px solid var(--mist);
    transition: all 0.2s;
    outline: none;
}
.tab:last-child { border-right: none; }
.tab:hover { background: var(--light-green); }
.tab.active {
    background: var(--teal);
    color: #fff;
}

/* Table Area */
.table-card {
    background: #fff;
    border: 1px solid var(--mist);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(15, 76, 92, 0.03);
}

.table-wrap {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

th {
    text-align: left;
    padding: 16px 20px;
    font-size: 11px;
    font-weight: 700;
    color: var(--slate);
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--mist);
    background: #fff;
}

td {
    padding: 16px 20px;
    border-bottom: 1px solid var(--mist);
    vertical-align: top;
}
tr:last-child td {
    border-bottom: none;
}

.col-sch {
    display: flex;
    flex-direction: column;
}

.sch-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--teal);
    margin-bottom: 4px;
}

.sch-deadline {
    font-size: 11px;
    color: var(--slate);
}

.td-org {
    font-size: 13px;
    color: var(--slate);
}

.td-date {
    font-size: 13px;
    color: var(--slate);
}

.td-stage {
    font-size: 13px;
    color: var(--teal-light);
}

/* Status Badges inside table */
.st-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
}
.st-under-review { background: #F3E8FF; color: #7E22CE; }
.st-submitted { background: #FEF3C7; color: #D97706; }
.st-approved { background: #DCFCE7; color: #16A34A; }
.st-rejected { background: #FEE2E2; color: #DC2626; }
.st-pending { background: #E0F2FE; color: #0284C7; }

/* Action Buttons */
.td-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-action {
    padding: 6px 14px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid var(--mist);
    background: #fff;
    color: var(--teal);
    transition: all 0.2s;
    cursor: pointer;
}
.btn-action:hover {
    border-color: var(--teal);
}

.btn-withdraw {
    background: #FFF5F5;
    color: #DC2626;
    border: 1px solid transparent;
}
.btn-withdraw:hover {
    background: #FEE2E2;
}

.table-footer {
    padding: 16px 20px;
    font-size: 12px;
    color: var(--slate);
    border-top: 1px solid var(--mist);
    background: #FAFCFC;
}
</style>
@endpush

@section('content')
<div class="main-inner">
    <div class="header-row">
        <div class="page-title-area">
            <span class="page-eyebrow">MY APPLICATIONS</span>
            <h1 class="page-title">Track your submissions</h1>
        </div>
        <a href="{{ route('scholarships.index') }}" class="btn-apply-new">
            + Apply to Scholarship
        </a>
    </div>

    <div class="tabs-container">
        <button class="tab active" data-filter="all">All ({{ $stats['totalApplied'] ?? 0 }})</button>
        <button class="tab" data-filter="under-review">Under Review ({{ $stats['underReview'] ?? 0 }})</button>
        <button class="tab" data-filter="submitted">Submitted ({{ $applications->where('status', 'pending')->count() + $applications->where('status', 'submitted')->count() }})</button>
        <button class="tab" data-filter="approved">Approved ({{ $stats['approved'] ?? 0 }})</button>
    </div>

    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Scholarship</th>
                        <th>Organization</th>
                        <th>Applied On</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        @php
                            // Mapping logical statuses to the UI badge styling and text
                            $statusMapLocal = [
                                'under_review' => ['filter' => 'under-review', 'class' => 'st-under-review', 'label' => 'Under Review'],
                                'approved' => ['filter' => 'approved', 'class' => 'st-approved', 'label' => 'Approved'],
                                'rejected' => ['filter' => 'rejected', 'class' => 'st-rejected', 'label' => 'Rejected'],
                                'revision' => ['filter' => 'submitted', 'class' => 'st-pending', 'label' => 'Action Needed'],
                                'pending' => ['filter' => 'submitted', 'class' => 'st-submitted', 'label' => 'Submitted'],
                                'submitted' => ['filter' => 'submitted', 'class' => 'st-submitted', 'label' => 'Submitted'],
                            ];

                            $currentStatus = $statusMapLocal[$app->status] ?? $statusMapLocal['submitted'];

                            // Formatting deadline correctly
                            $deadlineFormatted = $app->scholarship && $app->scholarship->deadline 
                                ? \Carbon\Carbon::parse($app->scholarship->deadline)->format('M d, Y') 
                                : 'No deadline';
                                
                            $appliedOn = $app->submitted_at ? \Carbon\Carbon::parse($app->submitted_at)->format('M d, Y') : '—';
                            
                            $stageLabel = $remarksByStage[$app->stage] ?? ucfirst(str_replace('_', ' ', $app->stage));
                        @endphp
                        <tr class="app-row" data-status="{{ $currentStatus['filter'] }}">
                            <td>
                                <div class="col-sch">
                                    <span class="sch-name">{{ $app->scholarship->name ?? 'Unknown Scholarship' }}</span>
                                    <span class="sch-deadline">Deadline: {{ $deadlineFormatted }}</span>
                                </div>
                            </td>
                            <td class="td-org">{{ $app->scholarship->provider_name ?? 'Unknown Organization' }}</td>
                            <td class="td-date">{{ $appliedOn }}</td>
                            <td class="td-stage">{{ $stageLabel }}</td>
                            <td>
                                <span class="st-badge {{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
                            </td>
                            <td class="td-actions">
                                <a href="{{ route('applications.show', $app->id) }}" class="btn-action">View</a>
                                @if(in_array($app->status, ['pending', 'submitted', 'under_review']))
                                    <button class="btn-action btn-withdraw" onclick="alert('Withdraw feature coming soon')">Withdraw</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:40px; color:var(--slate);">
                                You haven't submitted any applications yet. <br><br>
                                <a href="{{ route('scholarships.index') }}" style="color:var(--teal); font-weight:bold;">Browse Scholarships</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->count() > 0)
        <div class="table-footer">
            {{ $applications->count() }} application{{ $applications->count() !== 1 ? 's' : '' }} total
        </div>
        @endif
    </div>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab');
    const rows = document.querySelectorAll('.app-row');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active to clicked tab
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            
            rows.forEach(row => {
                if (filter === 'all' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush

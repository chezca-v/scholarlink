@extends('layouts.applicant')

@section('title', 'ScholarLink — Applicant Dashboard')

@push('styles')
<style>
/* ── HERO BANNER ── */
.hero{
  border-radius:18px;
  background:linear-gradient(160deg, #0F4C5C, #2A8FA0);
  padding:28px 32px;
  display:flex;align-items:flex-start;justify-content:space-between;
  margin-bottom:20px;
  position:relative;
  overflow:hidden;
}
.hero-eyebrow{font-size:11px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,0.65);margin-bottom:4px;}
.hero-name{font-family:'Fraunces',serif;font-size:30px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px;}
.hero-name em{color:var(--amber-light);font-style:italic;}
.hero-sub{font-size:13px;color:rgba(255,255,255,0.65);margin-bottom:18px;}
.btn-ai{display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg, #E8A838, #F9D679);color:var(--teal);font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;border:none;border-radius:10px;padding:9px 18px;cursor:pointer;transition:all .15s;}
.btn-ai:hover{background:#F9D679;transform:translateY(-1px);}
.btn-ai svg{flex-shrink:0;}
.hero-right{display:flex;flex-direction:column;align-items:center;gap:4px;z-index:1;}
.progress-ring{position:relative;width:80px;height:80px;}
.progress-ring svg{transform:rotate(-90deg);}
.ring-bg{fill:none;stroke:rgba(255,255,255,0.12);stroke-width:7;}
.ring-fill{fill:none;stroke:var(--amber-light);stroke-width:7;stroke-dasharray:var(--ring-circumference, 207.35) var(--ring-circumference, 207.35);stroke-dashoffset:var(--ring-offset, 207.35);transition:stroke-dashoffset .6s ease;}
.ring-label{font-family:'Fraunces',serif;position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.ring-pct{font-size:18px;font-weight:700;color:#fff;line-height:1;}
.ring-sub{font-size:8.5px;color:rgba(255,255,255,0.55);margin-top:1px;}
.hero-right-label{font-size:10.5px;color:rgba(255,255,255,0.55);text-align:center;}
.hero-right-hint{font-size:10px;color:rgba(255,255,255,0.55);text-align:center;}

/* ── STAT CARDS ── */
.stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
.stat-card{background:#fff;border:1.5px solid var(--mist);border-radius:14px;padding:16px 18px;position:relative;overflow:hidden;}
.stat-badge{position:absolute;top:12px;right:12px;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;}
.stat-badge.active{background:var(--green-bg);color:var(--green-text);}
.stat-badge.ai{background:var(--violet-bg);color:var(--violet-text);}
.stat-badge.won{background:var(--warn-bg);color:var(--warn-text);}
.stat-badge.saved{background:var(--light-green);color:var(--teal);}
.stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;}
.stat-icon.teal{background:var(--light-green);}
.stat-icon.violet{background:var(--violet-bg);}
.stat-icon.green{background:var(--green-bg);}
.stat-icon.amber{background:#fff8e1;}
.stat-num{font-family:'Fraunces',serif;font-size:32px;font-weight:700;color:var(--ink);line-height:1;}
.stat-lbl{font-size:12px;color:var(--slate);margin-top:3px;}

/* ── SECTION HEADER ── */
.sec-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.sec-title{display:flex;align-items:center;gap:7px;font-size:15px;font-weight:700;color:var(--ink);}
.sec-title svg{color:var(--amber);}
.sec-sub{font-size:11.5px;color:var(--slate);margin-top:1px;}
.see-all{font-size:12.5px;color:var(--teal);font-weight:600;cursor:pointer;display:flex;align-items:center;gap:3px;text-decoration:none;}
.see-all:hover{text-decoration:underline;}

/* ── AI MATCH CARDS ── */
.ai-section{
  background:#fff; /* or any color you want */
  border:1.5px solid var(--mist);
  border-radius:16px;
  padding:18px 20px 2px;
}
.ai-scroll{display:grid;grid-template-columns:repeat(5,minmax(210px,1fr));gap:12px;margin-bottom:28px;overflow-x:auto;}
.ai-scroll::-webkit-scrollbar{height:3px;}
.ai-scroll::-webkit-scrollbar-thumb{background:var(--mist);}
.match-card{background:#fff;border:1.5px solid var(--mist);border-radius:14px;padding:16px;display:flex;flex-direction:column;gap:0;flex-shrink:0;transition:box-shadow .15s;}
.match-card:hover{transform:translateY(-7px);box-shadow:0 16px 36px rgba(15,76,92,0.13);}
.match-card.top{border-color:var(--amber);border-width:2px;}
.mc-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;}
.mc-cat{font-size:9.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--slate);}
.mc-fit{font-size:9.5px;font-weight:700;padding:2px 7px;border-radius:20px;}
.mc-fit.top{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-fit.great{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-fit.good{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-fit.explore{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-name{font-size:14px;font-weight:700;color:var(--ink);line-height:1.3;margin-bottom:3px;}
.mc-org{font-size:11px;color:var(--slate);margin-bottom:10px;}
.mc-amt{font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--teal);margin-bottom:2px;}
.mc-amt-unit{font-family:'DM Sans',sans-serif;font-size:11px;font-weight:500;color:var(--slate);}
.mc-score-lbl{font-size:10.5px;color:var(--slate);margin-bottom:4px;margin-top:10px;}
.mc-bar-row{display:flex;align-items:center;gap:8px;margin-bottom:12px;position:relative;}
.mc-bar{flex:1;height:5px;background:var(--cloud);border-radius:20px;overflow:hidden;position:relative;}
.mc-fill{height:100%;border-radius:20px;background:#0F4C5C;}
.mc-fill.gold{background: linear-gradient(135deg, #E8A838, #F9D679);}
.mc-pct{position:absolute;top: -20px;right:0;font-size:12.5px;font-weight:700;color:var(--teal);}
.mc-btn-row{display:flex;gap:6px;margin-top:auto;}
.btn-apply-full{flex:1;background:linear-gradient(135deg, #E8A838, #F9D679);color:var(--teal);font-family:'DM Sans',sans-serif;font-size:12px;font-weight:700;border:none;border-radius:8px;padding:8px 0;cursor:pointer;transition:background .12s;}
.btn-apply-full:hover{background:#F9D679;}
.btn-view{flex:1;background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#F9D679;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;border:1.5px solid var(--mist);border-radius:8px;padding:8px 0;cursor:pointer;transition:all .12s;}
.btn-view:hover{border-color:var(--teal);}

/* ── BOTTOM GRID ── */
.bottom-grid{display:grid;grid-template-columns:1fr 260px;gap:18px;}

/* ── ACTIVE APPLICATIONS ── */
.panel{background:#fff;border:1.5px solid var(--mist);border-radius:16px;padding:20px;}
.app-item{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--cloud);}
.app-item:last-child{border-bottom:none;padding-bottom:0;}
.app-avatar{width:38px;height:38px;border:1px solid var(--mist);border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',sans-serif;font-size:12px;font-weight:700;background: #F0FAFA;color:var(--teal);flex-shrink:0;}
.app-info{flex:1;min-width:0;}
.app-name{font-size:13.5px;font-weight:600;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.app-org{font-size:11px;color:var(--slate);margin-top:1px;}
.app-progress{display:flex;gap:4px;margin-top:6px;}
.ap-dot{width:18px;height:4px;border-radius:20px;}
.ap-dot.done{background:var(--teal);}
.ap-dot.current{background:var(--amber);}
.ap-dot.empty{background:var(--mist);}
.app-status{flex-shrink:0;}
.app-status-tag{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:20px;}
.app-status-tag.review{background:var(--warn-bg);color:var(--warn-text);}
.app-status-tag.docs{background:#fee2e2;color:#b91c1c;}
.app-status-tag.approved{background:var(--green-bg);color:var(--green-text);}

/* ── DEADLINES ── */
.deadline-item{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--cloud);}
.deadline-item:last-child{border-bottom:none;padding-bottom:0;}
.dl-cal{width:46px;height:52px;border-radius:12px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1px;}
.dl-cal.urgent{background:#fff3e0;border:1.5px solid #f6c36a;}
.dl-cal.normal{background:#eaf7f7;border:1.5px solid #b5dfe0;}
.dl-day{font-family:'Fraunces',serif;font-size:22px;font-weight:700;line-height:1;}
.dl-cal.urgent .dl-day{color:#b36b00;}
.dl-cal.normal .dl-day{color:#0F4C5C;}
.dl-month{font-size:9px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;line-height:1;}
.dl-cal.urgent .dl-month{color:#c47f17;}
.dl-cal.normal .dl-month{color:#2A8FA0;}
.dl-info{flex:1;min-width:0;}
.dl-name{font-size:13px;font-weight:600;color:var(--ink);line-height:1.3;}
.dl-left{font-size:11.5px;margin-top:3px;font-weight:500;display:flex;align-items:center;gap:4px;}
.dl-left.urgent{color:#c47f17;}
.dl-left.soon{color:var(--warn-text);}
.dl-left.ok{color:var(--teal);}

/* ── QUICK ACTIONS ── */
.qa-section{background:#fff;border:1.5px solid var(--mist);border-radius:16px;padding:10px 20px 20px;}
.qa-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px;}
.qa-card{background:#fff;border:1.5px solid var(--mist);border-radius:12px;padding:16px;cursor:pointer;transition:all .15s;display:flex;flex-direction:column;gap:8px;}
.qa-card:hover{border-color:var(--teal);box-shadow:0 2px 12px rgba(15,76,92,0.08);}
.qa-icon{width:34px;height:34px;border-radius:9px;background:var(--light-green);display:flex;align-items:center;justify-content:center;color:var(--teal);}
.qa-label{font-size:13px;font-weight:600;color:var(--ink);}
.qa-hint{font-size:11px;color:var(--slate);}

/* ── NOTIFICATIONS ── */
.notif-item{display:flex;align-items:flex-start;gap:9px;padding:9px 0;border-bottom:1px solid var(--cloud);font-size:12px;}
.notif-item:last-child{border-bottom:none;}
.notif-dot{width:7px;height:7px;border-radius:50%;background:var(--teal);flex-shrink:0;margin-top:4px;}
.notif-dot.amber{background:var(--amber);}
.notif-dot.red{background:#e53e3e;}
.notif-txt{color:var(--ink);line-height:1.45;}
.notif-time{font-size:10.5px;color:var(--slate);margin-top:2px;}

/* ── SECTION SPACING ── */
.section{margin-bottom:24px;}
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="hero section">
  <div class="hero-left">
    <div class="hero-eyebrow">Good Afternoon</div>
    <div class="hero-name">Kumusta, <em>{{ $user->first_name }}!</em></div>
    <div class="hero-sub">You have {{ $upcomingDeadlines->count() }} deadlines this week and {{ $stats['ai_matched'] }} new AI-matched scholarships ready.</div>
    <a href="#ai-recommendations" class="btn-ai" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      View AI Recommendations
    </a>
  </div>
  <div class="hero-right">
    <div class="hero-right-label">Profile Completeness</div>
    <div class="progress-ring">
      <svg width="80" height="80" viewBox="0 0 80 80">
        @php
          $normalizedCompleteness = min(max($profileCompleteness, 0), 100);
          $ringCircumference = 2 * pi() * 33;
          $ringOffset = $normalizedCompleteness >= 100
            ? 0
            : round($ringCircumference * (1 - ($normalizedCompleteness / 100)), 3);
          $ringLinecap = $normalizedCompleteness >= 100 ? 'butt' : 'round';
        @endphp
        <circle class="ring-fill" cx="40" cy="40" r="33" style="--ring-circumference:{{ $ringCircumference }};--ring-offset:{{ $ringOffset }};stroke-linecap:{{ $ringLinecap }};"/>
      <div class="ring-label">
        <span class="ring-pct">{{ $profileCompleteness }}%</span>
      </div>
    </div>
    <div class="hero-right-hint">Complete for better matches</div>
  </div>
</div>

<!-- STATS -->
<div class="stat-row section">
  <div class="stat-card">
    <span class="stat-badge active">Active</span>
    <div class="stat-icon teal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0F4C5C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div class="stat-num">{{ $stats['active_applications'] }}</div>
    <div class="stat-lbl">Active Applications</div>
  </div>
  <div class="stat-card">
    <span class="stat-badge ai">AI</span>
    <div class="stat-icon violet">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div class="stat-num">{{ $stats['ai_matched'] }}</div>
    <div class="stat-lbl">AI-Matched Scholarships</div>
  </div>
  <div class="stat-card">
    <span class="stat-badge won">Won</span>
    <div class="stat-icon green">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="stat-num">{{ $stats['awarded'] }}</div>
    <div class="stat-lbl">Scholarships Awarded</div>
  </div>
  <div class="stat-card">
    <span class="stat-badge saved">Saved</span>
    <div class="stat-icon amber">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C9A84C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
    </div>
    <div class="stat-num">{{ $stats['saved'] }}</div>
    <div class="stat-lbl">Saved Scholarships</div>
  </div>
</div>

<!-- AI MATCHED -->
<div class="section ai-section" id="ai-recommendations">
  <div class="sec-hd">
    <div class="sec-title-wrap">
      <div class="sec-title">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        AI-Matched for You
      </div>
      <div class="sec-sub">Based on your GPA, income bracket &amp; course — updated today.</div>
    </div>
    <a class="see-all" href="{{ route('scholarships.index') }}">See all {{ $stats['ai_matched'] }} →</a>
  </div>
  <div class="ai-scroll">

    @forelse($recommendedScholarships as $index => $application)
    @php
        $scholarship = $application->scholarship ?? $application;
        $matchScore = $application->match_score ?? $application->ai_match_score ?? null;
        $isTop = $index === 0 && $matchScore && $matchScore >= 90;
        $fitLabel = $matchScore >= 90 ? 'Top Match' : ($matchScore >= 80 ? 'Great Fit' : ($matchScore >= 70 ? 'Good Fit' : 'Explore'));
        $benefitAmount = $scholarship->benefit_snippet_1 ? (int) preg_replace('/[^0-9]/', '', $scholarship->benefit_snippet_1) : 0;
    @endphp
    <div class="match-card {{ $isTop ? 'top' : '' }}" onclick="window.location='{{ route('scholarships.show', $scholarship->id) }}'" style="cursor:pointer;">
      <div class="mc-top"><span class="mc-cat">{{ $scholarship->provider_name ?? 'Scholarship' }}</span><span class="mc-fit {{ $isTop ? 'top' : ($matchScore >= 80 ? 'great' : ($matchScore >= 70 ? 'good' : 'explore')) }}">{{ $fitLabel }}</span></div>
      <div class="mc-name">{{ $scholarship->name }}</div>
      <div class="mc-org">{{ $scholarship->tagline ?? '' }}</div>
      <div class="mc-amt">₱{{ number_format($benefitAmount) }} <span class="mc-amt-unit">/ year</span></div>
      @if($matchScore)
      <div class="mc-score-lbl">Match Score</div>
      <div class="mc-bar-row"><div class="mc-bar"><div class="mc-fill {{ $isTop ? 'gold' : '' }}" style="width:{{ $matchScore }}%"></div></div><span class="mc-pct">{{ round($matchScore) }}%</span></div>
      @endif
      <div class="mc-btn-row">
        <a href="{{ route('applications.create', $scholarship->id) }}" onclick="event.stopPropagation();" class="btn-apply-full" style="text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">Apply Now</a>
        <a href="{{ route('scholarships.show', $scholarship->id) }}" onclick="event.stopPropagation();" class="btn-view" style="text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">View</a>
      </div>
    </div>
    @empty
    <div class="match-card" onclick="window.location='{{ route('profile.show') }}'" style="cursor:pointer;">
      <div class="mc-top"><span class="mc-cat">No matches</span></div>
      <div class="mc-name">Complete your profile</div>
      <div class="mc-org">Add your GPA and income details to get personalized scholarship recommendations.</div>
      <div class="mc-btn-row"><a href="{{ route('profile.show') }}" onclick="event.stopPropagation();" class="btn-view" style="text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">Set Up Profile</a></div>
    </div>
    @endforelse

  </div>
</div>

<!-- BOTTOM GRID -->
<div class="bottom-grid">

  <!-- LEFT: Active Applications -->
  <div>
    <div class="panel section">
      <div class="sec-hd" style="margin-bottom:10px;">
        <div class="sec-title">Active Applications</div>
        <a class="see-all" href="{{ route('applications.index') }}">View all →</a>
      </div>

      @forelse($activeApplications as $application)
      @php
          $scholarship = $application->scholarship;
          $initials = strtoupper(substr($scholarship->name ?? 'S', 0, 2));
          $stage = $application->stage;
          $status = $application->status;
          $stages = ['submitted' => 1, 'doc_review' => 2, 'scoring' => 3, 'interview' => 4, 'decided' => 5];
          $currentStage = $stages[$stage] ?? 1;
      @endphp
      <a href="{{ route('applications.track', $application->id) }}" class="app-item" style="text-decoration:none">
        <div class="app-avatar" style="background:#F0FAFA;">{{ $initials }}</div>
        <div class="app-info">
          <div class="app-name">{{ $scholarship->name ?? 'Scholarship' }}</div>
          <div class="app-org">{{ $scholarship->provider_name ?? '' }}</div>
          <div class="app-progress">
            @for($i = 1; $i <= 5; $i++)
            <div class="ap-dot {{ $i < $currentStage ? 'done' : ($i == $currentStage ? 'current' : 'empty') }}"></div>
            @endfor
          </div>
        </div>
        <div class="app-status">
          @if($status === 'approved')
          <span class="app-status-tag approved">Approved! 🎉</span>
          @elseif($status === 'under_review')
          <span class="app-status-tag review">Under Review</span>
          @elseif($status === 'rejected')
          <span class="app-status-tag docs">Rejected</span>
          @else
          <span class="app-status-tag review">Pending</span>
          @endif
        </div>
      </a>
      @empty
      <div class="app-item">
        <div class="app-info">
          <div class="app-name">No active applications</div>
          <div class="app-org">Browse scholarships to start your application journey.</div>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Quick Actions -->
    <div class="section qa-section">
        <div class="sec-hd">
        <div class="sec-title">Quick Actions</div>
        </div>
        <div class="qa-row">
        <a href="{{ route('scholarships.index') }}" class="qa-card" style="text-decoration:none">
          <div class="qa-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </div>
          <div class="qa-label">Browse All</div>
          <div class="qa-hint">Find scholarships</div>
        </a>
        <a href="{{ route('applicant.documents.index') }}" class="qa-card" style="text-decoration:none">
          <div class="qa-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </div>
          <div class="qa-label">Upload Docs</div>
          <div class="qa-hint">Add to wallet</div>
        </a>
      </div>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:18px;">

    <!-- Deadlines -->
    <div class="panel">
      <div class="sec-hd" style="margin-bottom:10px;">
        <div class="sec-title">Upcoming Deadlines</div>
        <a class="see-all" href="{{ route('applications.index') }}">All Deadlines →</a>
      </div>

      @forelse($upcomingDeadlines as $application)
      @php
          $scholarship = $application->scholarship;
          $deadline = $scholarship->deadline ?? null;
          $daysLeft = $deadline ? now()->diffInDays(\Carbon\Carbon::parse($deadline), false) : null;
          $isUrgent = $daysLeft !== null && $daysLeft <= 3;
          $isSoon = $daysLeft !== null && $daysLeft <= 7;
      @endphp
      <a href="{{ route('applications.track', $application->id) }}" class="deadline-item" style="text-decoration:none">
        <div class="dl-cal {{ $isUrgent ? 'urgent' : 'normal' }}">
          <div class="dl-day">{{ $deadline ? \Carbon\Carbon::parse($deadline)->format('d') : '--' }}</div>
          <div class="dl-month">{{ $deadline ? \Carbon\Carbon::parse($deadline)->format('M') : '' }}</div>
        </div>
        <div class="dl-info">
          <div class="dl-name">{{ $scholarship->name ?? 'Scholarship' }}</div>
          <div class="dl-left {{ $isUrgent ? 'urgent' : ($isSoon ? 'soon' : 'ok') }}">
            @if($daysLeft !== null)
            @if($daysLeft <= 3)
            ⚠ {{ $daysLeft }} days left — submit docs!
            @else
            {{ $daysLeft }} days left
            @endif
            @else
            Deadline not set
            @endif
          </div>
        </div>
      </a>
      @empty
      <div class="deadline-item">
        <div class="dl-info">
          <div class="dl-name">No upcoming deadlines</div>
          <div class="dl-left ok">All caught up!</div>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Notifications -->
    <div class="panel">
      <div class="sec-hd" style="margin-bottom:10px;">
        <div class="sec-title">Notifications</div>
        <a class="see-all" href="{{ route('notifications.index') }}">View all →</a>
      </div>

      @forelse($notifications as $notif)
      @php
          $type = $notif->type ?? 'info';
          $dotClass = $type === 'success' ? 'amber' : ($type === 'error' ? 'red' : '');
      @endphp
      <div class="notif-item">
        <div class="notif-dot {{ $dotClass }}"></div>
        <div>
          <div class="notif-txt">{!! $notif->body !!}</div>
          <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
        </div>
      </div>
      @empty
      <div class="notif-item">
        <div class="notif-dot"></div>
        <div>
          <div class="notif-txt">No new notifications</div>
          <div class="notif-time">You're all caught up!</div>
        </div>
      </div>
      @endforelse
    </div>

  </div>
</div>
@endsection

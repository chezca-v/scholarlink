@extends('layouts.applicant')
@section('title', 'ScholarLink — Applicant Dashboard')

@section('content')
<!-- HERO -->
    <div class="hero section">
      <div class="hero-left">
        <div class="hero-eyebrow">Good Afternoon</div>
        <div class="hero-name">Kamusta, <em>{{ $user->first_name }}!</em></div>
        <div class="hero-sub">You have {{ $upcomingDeadlines->count() }} deadlines this week and {{ $stats['ai_matched'] }} new AI-matched scholarships ready.</div>
        <a href="{{ route('scholarships.index') }}" class="btn-ai" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
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
    <div class="section ai-section">
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
        <div class="match-card {{ $isTop ? 'top' : '' }}">
          <div class="mc-top"><span class="mc-cat">{{ $scholarship->provider_name ?? 'Scholarship' }}</span><span class="mc-fit {{ $isTop ? 'top' : ($matchScore >= 80 ? 'great' : ($matchScore >= 70 ? 'good' : 'explore')) }}">{{ $fitLabel }}</span></div>
          <div class="mc-name">{{ $scholarship->name }}</div>
          <div class="mc-org">{{ $scholarship->tagline ?? '' }}</div>
          <div class="mc-amt">₱{{ number_format($benefitAmount) }} <span class="mc-amt-unit">/ year</span></div>
          @if($matchScore)
          <div class="mc-score-lbl">Match Score</div>
          <div class="mc-bar-row"><div class="mc-bar"><div class="mc-fill {{ $isTop ? 'gold' : '' }}" style="width:{{ $matchScore }}%"></div></div><span class="mc-pct">{{ round($matchScore) }}%</span></div>
          @endif
          <div class="mc-btn-row">
            <a href="{{ route('applications.create', $scholarship->id) }}" class="btn-apply-full" style="text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">Apply Now</a>
            <a href="{{ route('scholarships.show', $scholarship->id) }}" class="btn-view" style="text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">View</a>
          </div>
        </div>
        @empty
        <div class="match-card">
          <div class="mc-top"><span class="mc-cat">No matches</span></div>
          <div class="mc-name">Complete your profile</div>
          <div class="mc-org">Add your GPA and income details to get personalized scholarship recommendations.</div>
          <div class="mc-btn-row"><a href="{{ route('profile.show') }}" class="btn-view" style="text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center;">Set Up Profile</a></div>
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

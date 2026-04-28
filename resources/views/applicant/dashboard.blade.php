{{-- resources/views/scholarships/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholarLink - Browse Scholarships</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "DM Sans", sans-serif; background: #edf3f5; color: #1f3f4f; }
        .topbar { height: 58px; border-bottom: 1px solid #d8e7ea; background: #ffffff; display: flex; justify-content: center; align-items: center; }
        .topbar-inner { width: 100%; max-width: 1380px; padding: 0 20px; display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 18px; }
        .brand { display: flex; align-items: center; gap: 8px; font-family: "Fraunces", serif; font-weight: 800; font-size: 28px; color: #194456; }
        .brand-icon { width: 24px; height: 24px; border-radius: 7px; background: #0b6378; color: #fff; font-size: 11px; font-weight: 700; display: grid; place-items: center; }
        .search-wrap { position: relative; max-width: 470px; width: 100%; justify-self: center; }
        .search { width: 100%; height: 36px; border-radius: 999px; border: 1px solid #d9e8ec; background: #f8fbfc; padding: 0 70px 0 34px; font-size: 12px; color: #5c7b89; outline: none; }
        .search-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #8fa7b0; font-size: 12px; }
        .search-kbd { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: 1px solid #e1ecef; border-radius: 6px; padding: 2px 6px; font-size: 10px; color: #93aab2; background: #fff; }
        .top-actions { display: flex; align-items: center; gap: 8px; }
        .icon-btn { width: 30px; height: 30px; border: 1px solid #dce9ed; border-radius: 8px; background: #fff; color: #88a1ab; display: grid; place-items: center; font-size: 12px; cursor: pointer; }
        .avatar { width: 30px; height: 30px; border-radius: 50%; background: #0b6378; color: #fff; display: grid; place-items: center; font-size: 10px; font-weight: 700; }
        .layout { max-width: 1380px; margin: 0 auto; display: grid; grid-template-columns: 275px 1fr; min-height: calc(100vh - 58px); }
        .sidebar { background: #f7fbfc; border-right: 1px solid #dce9ed; padding: 14px 16px; }
        .row-between { display: flex; justify-content: space-between; align-items: center; }
        .filters-title { font-size: 28px; line-height: 1; font-family: "Fraunces", serif; font-weight: 800; color: #21485a; }
        .clear { font-size: 11px; font-weight: 700; color: #0b6378; cursor: pointer; text-decoration: none; }
        .group { margin-top: 16px; padding-bottom: 14px; border-bottom: 1px solid #ecf3f6; }
        .group h4 { margin-bottom: 9px; color: #88a3ad; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .16em; }
        .option { margin: 7px 0; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #355564; gap: 6px; }
        .option label { display: flex; align-items: center; gap: 5px; cursor: pointer; }
        .count { color: #8da7b0; font-size: 11px; }
        .chip-row { display: flex; flex-wrap: wrap; gap: 6px; }
        .chip { border: 1px solid #d9e8ec; border-radius: 999px; background: #fff; padding: 4px 8px; font-size: 10px; color: #496b79; text-decoration: none; display: inline-block; }
        .chip.active { background: #0b6378; color: #fff; border-color: #0b6378; }

        /* ─── Mini profile card ─── */
        .mini-profile { margin-top: 16px; border: 1px solid #e2ecf0; border-radius: 10px; background: #fff; padding: 10px; }
        .mini-profile-top { display: flex; align-items: center; gap: 8px; }
        .mini-profile .photo { width: 28px; height: 28px; border-radius: 50%; background: #0b6378; color: #fff; font-size: 10px; font-weight: 700; display: grid; place-items: center; flex-shrink: 0; }
        .mini-profile strong { font-size: 12px; color: #244c5d; }
        .mini-profile small { font-size: 9px; color: #8ba4ad; display: block; }

        /* ─── Profile completeness ring ─── */
        .completeness-wrap { margin-top: 10px; display: flex; align-items: center; gap: 8px; }
        .ring-svg { flex-shrink: 0; }
        .ring-track { fill: none; stroke: #e7eff2; stroke-width: 3; }
        .ring-fill  { fill: none; stroke: #C9A84C; stroke-width: 3; stroke-linecap: round;
                      transition: stroke-dashoffset 0.6s ease; transform-origin: center;
                      transform: rotate(-90deg); }
        .completeness-text { font-size: 9px; color: #6f8e9a; line-height: 1.4; }
        .completeness-text strong { font-size: 11px; color: #C9A84C; font-weight: 700; display: block; }

        /* ─── Quick-stats row ─── */
        .quick-stats { margin-top: 10px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; }
        .qs-item { background: #f0f8fa; border: 1px solid #ddeef2; border-radius: 8px; padding: 6px 4px; text-align: center; }
        .qs-num  { font-family: "Fraunces", serif; font-size: 18px; font-weight: 700; color: #0b6378; line-height: 1; }
        .qs-label { font-size: 8px; color: #89a2ac; margin-top: 2px; text-transform: uppercase; letter-spacing: .08em; }
        .qs-item.notif .qs-num { color: #C9A84C; }

        .main { padding: 16px 18px 24px; }
        .eyebrow { font-size: 10px; letter-spacing: .2em; color: #cb9f3e; font-weight: 700; text-transform: uppercase; }
        .page-title { margin-top: 1px; font-family: "Fraunces", serif; font-size: 46px; line-height: 1.05; color: #1d4254; font-weight: 800; }
        .active-filters { margin-top: 8px; display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
        .active-filters .label { color: #839ea8; font-size: 11px; }
        .active-filters .tag { font-size: 10px; color: #3f6270; background: #f6fbfc; border: 1px solid #d8e8ec; border-radius: 999px; padding: 4px 9px; }
        .subhead { margin-top: 12px; display: flex; justify-content: space-between; align-items: center; gap: 10px; }
        .result-count { color: #466979; font-size: 15px; }
        .result-count strong { font-family: "Fraunces", serif; font-size: 31px; line-height: 1; color: #1e4255; margin-right: 4px; }
        .view-tools { display: flex; align-items: center; gap: 6px; }
        .select { border: 1px solid #d8e8ec; border-radius: 8px; background: #fff; color: #3f6270; font-size: 11px; padding: 8px 12px; cursor: pointer; }
        .cards { margin-top: 10px; display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; }
        .card { min-height: 194px; border-radius: 14px; border: 1px solid #deebef; background: #fff; padding: 12px; }
        .card.featured { border-color: #e2d6aa; box-shadow: 0 0 0 2px #f7f0db inset; }
        .org-row { display: flex; justify-content: space-between; align-items: center; color: #8ca5ae; font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .status { border-radius: 999px; padding: 2px 7px; background: #e8f8ed; color: #1a9653; font-size: 9px; letter-spacing: 0; text-transform: none; }
        .status.closed { background: #f1f5f9; color: #64748b; }
        .card h3 { margin-top: 8px; font-family: "Fraunces", serif; font-size: 28px; line-height: 1.05; color: #1b4153; font-weight: 700; }
        .meta { margin-top: 6px; font-size: 10px; color: #819ca6; }
        .kpis { margin-top: 9px; display: flex; flex-wrap: wrap; gap: 5px; }
        .kpi { border: 1px solid #deebef; border-radius: 999px; background: #f9fcfd; color: #5a7584; font-size: 9px; padding: 3px 6px; }
        .score { margin-top: 10px; font-size: 10px; color: #89a2ac; }
        .score strong { float: right; color: #cd9d3c; }
        .bar { margin-top: 4px; height: 4px; border-radius: 999px; background: #e7eff2; overflow: hidden; }
        .bar > span { display: block; height: 100%; background: linear-gradient(90deg, #0b6378, #d2a33f); }
        .apply { width: 100%; margin-top: 10px; border: none; border-radius: 9px; padding: 9px 0; background: #0b6378; color: #fff; font-size: 12px; font-weight: 700; cursor: pointer; }
        .empty-state { grid-column: 1/-1; text-align: center; padding: 40px; color: #8ca5ae; font-size: 14px; }
        .pagination { margin-top: 12px; display: flex; justify-content: center; align-items: center; gap: 6px; }
        .pg { width: 28px; height: 28px; border-radius: 8px; border: 1px solid #d8e8ec; background: #fff; color: #627d8a; font-size: 11px; display: grid; place-items: center; text-decoration: none; }
        .pg.active { background: #0b6378; color: #fff; border-color: #0b6378; font-weight: 700; }
        .pg.disabled { opacity: 0.4; pointer-events: none; }
        .dots { color: #8fa8b1; font-size: 12px; padding: 0 2px; }
    </style>
</head>
<body>

    {{-- NOTE: Replace with shared nav component on integration --}}
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                {{-- NOTE: Replace with actual ScholarLink logo asset --}}
                <span class="brand-icon">S</span>ScholarLink
            </div>
            <div class="search-wrap">
                <span class="search-icon">🔎</span>
                <form method="GET" action="{{ route('scholarships.index') }}" style="width:100%">
                    <input class="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search scholarship, organizations..." />
                </form>
                <span class="search-kbd">⌘K</span>
            </div>
            <div class="top-actions">
                <a href="{{ route('notifications.index') }}" class="icon-btn" style="text-decoration:none;">🔔</a>
                <a href="{{ route('dashboard') }}" class="icon-btn" style="text-decoration:none;">▦</a>
                @auth
                    <a href="{{ route('profile.show') }}" class="avatar" style="text-decoration:none;">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <form method="GET" action="{{ route('scholarships.index') }}" id="filter-form">

                <div class="row-between">
                    <h2 class="filters-title">Filters</h2>
                    <a href="{{ route('scholarships.index') }}" class="clear">Clear all</a>
                </div>

                {{-- Status filter --}}
                <div class="group">
                    <h4>Status</h4>
                    @foreach($statusCounts as $statusValue => $count)
                    <div class="option">
                        <label>
                            <input type="checkbox" name="status[]" value="{{ $statusValue }}"
                                {{ in_array($statusValue, request('status', [])) ? 'checked' : '' }}
                                onchange="document.getElementById('filter-form').submit()">
                            {{ ucfirst($statusValue) }}
                        </label>
                        <span class="count">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Income bracket filter --}}
                <div class="group">
                    <h4>Income Bracket</h4>
                    @foreach($incomeBrackets as $bracket)
                    <div class="option">
                        <label>
                            <input type="checkbox" name="income_bracket[]" value="{{ $bracket }}"
                                {{ in_array($bracket, request('income_bracket', [])) ? 'checked' : '' }}
                                onchange="document.getElementById('filter-form').submit()">
                            {{ $bracket }}
                        </label>
                    </div>
                    @endforeach
                </div>

                {{-- Deadline filter --}}
                <div class="group">
                    <h4>Deadline</h4>
                    <div class="chip-row">
                        @foreach(['this_week' => 'This week', 'this_month' => 'This month', 'next_3_months' => 'Next 3 months', 'any' => 'Any time'] as $val => $label)
                        <a href="{{ route('scholarships.index', array_merge(request()->except('deadline', 'page'), ['deadline' => $val])) }}"
                           class="chip {{ request('deadline') === $val ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Min match score --}}
                @auth
                <div class="group">
                    <h4>Min. Match Score</h4>
                    <div class="option">
                        <span>0%</span>
                        <span>≥ {{ request('min_match', 0) }}%</span>
                        <span>100%</span>
                    </div>
                    <input type="range" name="min_match" min="0" max="100" step="5"
                           value="{{ request('min_match', 0) }}"
                           style="width:100%; margin-top:6px"
                           onchange="document.getElementById('filter-form').submit()">
                </div>
                @endauth

            </form>

            @auth
            @php
                $authProfile  = auth()->user()->applicantProfile;

                {{--
                    Profile completeness — 10 fields tracked.
                    Each filled field = 10 points.

                    Fields checked (from applicant_profiles table):
                      1.  date_of_birth
                      2.  sex
                      3.  home_address
                      4.  city
                      5.  province
                      6.  mobile_number
                      7.  university_name
                      8.  course_program
                      9.  gwa
                      10. monthly_household_income

                    avatar_url, profile_completed_at are excluded — they are
                    system/optional fields, not user-fillable checklist items.
                --}}
                $profileFields = [
                    'date_of_birth',
                    'sex',
                    'home_address',
                    'city',
                    'province',
                    'mobile_number',
                    'university_name',
                    'course_program',
                    'gwa',
                    'monthly_household_income',
                ];

                $filledCount = 0;
                if ($authProfile) {
                    foreach ($profileFields as $field) {
                        if (!empty($authProfile->$field)) {
                            $filledCount++;
                        }
                    }
                }
                $totalFields = count($profileFields);
                $profilePct  = $totalFields > 0 ? (int) round(($filledCount / $totalFields) * 100) : 0;

                {{--
                    SVG ring math.
                    r = 14, circumference = 2π × 14 ≈ 87.96
                    dashoffset = circumference × (1 − pct/100)
                --}}
                $ringCircumference = 2 * M_PI * 14;
                $ringOffset        = $ringCircumference * (1 - $profilePct / 100);
            @endphp

            <div class="mini-profile">
                <div class="mini-profile-top">
                    <div class="photo">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong>
                        <small>Applicant · GPA {{ $authProfile->gwa ?? 'N/A' }}</small>
                    </div>
                </div>

                {{-- Profile completeness ring --}}
                <div class="completeness-wrap">
                    <svg class="ring-svg" width="36" height="36" viewBox="0 0 36 36">
                        <circle class="ring-track" cx="18" cy="18" r="14"/>
                        <circle class="ring-fill"
                                cx="18" cy="18" r="14"
                                stroke-dasharray="{{ number_format($ringCircumference, 2) }}"
                                stroke-dashoffset="{{ number_format($ringOffset, 2) }}"
                        />
                        <text x="18" y="18" text-anchor="middle" dominant-baseline="central"
                              font-size="7" font-weight="700" fill="#C9A84C"
                              font-family="DM Sans, sans-serif">
                            {{ $profilePct }}%
                        </text>
                    </svg>
                    <div class="completeness-text">
                        <strong>{{ $profilePct }}% Complete</strong>
                        {{ $filledCount }}/{{ $totalFields }} profile fields filled
                        @if($profilePct < 100)
                            — <a href="{{ route('profile.show') }}" style="color:#0b6378;font-size:9px;">Complete now</a>
                        @endif
                    </div>
                </div>
                
                <div class="quick-stats">
                    <div class="qs-item">
                        <div class="qs-num">{{ $applicationCount ?? 0 }}</div>
                        <div class="qs-label">Applied</div>
                    </div>
                    <div class="qs-item">
                        <div class="qs-num">{{ $savedCount ?? 0 }}</div>
                        <div class="qs-label">Saved</div>
                    </div>
                    <div class="qs-item notif">
                        <div class="qs-num">{{ $unreadCount ?? 0 }}</div>
                        <div class="qs-label">Notifs</div>
                    </div>
                </div>
            </div>
            @endauth
        </aside>

        <main class="main">
            <div class="eyebrow">Browse</div>
            <h1 class="page-title">Scholarship Opportunities</h1>

            {{-- Active filter chips --}}
            @if(request()->anyFilled(['status', 'income_bracket', 'deadline', 'min_match', 'search']))
            <div class="active-filters">
                <span class="label">Active:</span>
                @foreach(request('status', []) as $s)
                    <span class="tag">{{ ucfirst($s) }}</span>
                @endforeach
                @foreach(request('income_bracket', []) as $ib)
                    <span class="tag">{{ $ib }}</span>
                @endforeach
                @if(request('deadline') && request('deadline') !== 'any')
                    <span class="tag">{{ ucfirst(str_replace('_', ' ', request('deadline'))) }}</span>
                @endif
                @if(request('min_match') > 0)
                    <span class="tag">Match ≥ {{ request('min_match') }}%</span>
                @endif
                @if(request('search'))
                    <span class="tag">"{{ request('search') }}"</span>
                @endif
                <a href="{{ route('scholarships.index') }}" class="clear">Clear all</a>
            </div>
            @endif

            <div class="subhead">
                <div class="result-count">
                    <strong>{{ $scholarships->total() }}</strong> scholarships found
                </div>
                <div class="view-tools">
                    <select class="select"
                        onchange="location.href='{{ route('scholarships.index') }}?sort='+this.value">
                        <option value="latest"   {{ request('sort','latest') === 'latest'   ? 'selected' : '' }}>Latest</option>
                        <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline ↑</option>
                        <option value="slots"    {{ request('sort') === 'slots'    ? 'selected' : '' }}>Most Slots</option>
                    </select>
                    <button class="icon-btn" style="background:#0b6378;color:#fff;border-color:#0b6378;">▦</button>
                    <button class="icon-btn">☰</button>
                </div>
            </div>

            <section class="cards">
                @forelse($scholarships as $index => $scholarship)
                @php
                    $isFeatured  = $index === 0;
                    $statusClass = $scholarship->status === 'closed' ? 'closed' : '';
                @endphp
                <article class="card {{ $isFeatured ? 'featured' : '' }}">
                    <div class="org-row">
                        <span>{{ strtoupper($scholarship->provider_name) }}</span>
                        <span class="status {{ $statusClass }}">{{ ucfirst($scholarship->status) }}</span>
                    </div>

                    <h3>{{ $scholarship->name }}</h3>

                    <p class="meta">
                        Deadline: {{ \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y') }}
                    </p>

                    <div class="kpis">
                        @if($scholarship->gpa_requirement)
                            <span class="kpi">GPA {{ $scholarship->gpa_requirement }}+</span>
                        @endif
                        @if($scholarship->income_bracket)
                            <span class="kpi">{{ $scholarship->income_bracket }}</span>
                        @endif
                        @if($scholarship->slots)
                            <span class="kpi">{{ $scholarship->slots }} slots</span>
                        @endif
                        @if($scholarship->benefit_snippet_1)
                            <span class="kpi">{{ $scholarship->benefit_snippet_1 }}</span>
                        @endif
                    </div>

                    @auth
                    @if(isset($scholarship->ai_match_score))
                    <div class="score">
                        Your Match Score <strong>{{ round($scholarship->ai_match_score) }}%</strong>
                    </div>
                    <div class="bar">
                        <span style="width:{{ $scholarship->ai_match_score }}%"></span>
                    </div>
                    @endif
                    @endauth

                    <a href="{{ route('scholarships.show', $scholarship->id) }}" class="apply" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                        Apply Now
                    </a>
                </article>
                @empty
                <div class="empty-state">No scholarships found matching your filters.</div>
                @endforelse
            </section>

            {{-- Pagination --}}
            <div class="pagination">
                @if($scholarships->onFirstPage())
                    <span class="pg disabled">‹</span>
                @else
                    <a class="pg" href="{{ $scholarships->previousPageUrl() }}">‹</a>
                @endif

                @foreach($scholarships->getUrlRange(1, $scholarships->lastPage()) as $page => $url)
                    @if($page == $scholarships->currentPage())
                        <span class="pg active">{{ $page }}</span>
                    @elseif($page == 1 || $page == $scholarships->lastPage() || abs($page - $scholarships->currentPage()) <= 1)
                        <a class="pg" href="{{ $url }}">{{ $page }}</a>
                    @elseif(abs($page - $scholarships->currentPage()) == 2)
                        <span class="dots">...</span>
                    @endif
                @endforeach

                @if($scholarships->hasMorePages())
                    <a class="pg" href="{{ $scholarships->nextPageUrl() }}">›</a>
                @else
                    <span class="pg disabled">›</span>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
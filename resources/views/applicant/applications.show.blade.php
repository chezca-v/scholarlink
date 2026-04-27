{{--
|--------------------------------------------------------------------------
| my-applications.blade.php
|--------------------------------------------------------------------------
| ScholarLink — Applicant "My Applications" Dashboard Page
|
| Sections:
|   01 — Page Header (breadcrumb + title)
|   02 — App Shell (nav bar + sidebar + main content)
|   03 — Sidebar Navigation
|   04 — Main: Filter Tabs + Applications Table
|   05 — Table Rows (per application)
|
| Usage:
|   @include('components.my-applications', [
|       'user'         => $user,
|       'applications' => $applications,
|       'activeFilter' => request('filter', 'all'),
|   ])
--}}

@php
    /* ── Prop defaults ── */
    $user = $user ?? [
        'name'     => 'Ysa Frigillana',
        'initials' => 'YF',
        'role'     => 'Applicant',
    ];

    $applications = $applications ?? [
        [
            'id'           => 1,
            'scholarship'  => 'Gabay Dunong Scholarship 2025',
            'deadline'     => 'Dec 31, 2025',
            'organization' => 'Gabay Foundation PH',
            'applied_on'   => 'Oct 12, 2025',
            'stage'        => 'Document Review',
            'status'       => 'under_review',
            'can_withdraw' => true,
        ],
        [
            'id'           => 2,
            'scholarship'  => 'Abot-Kaya Excellence Grant',
            'deadline'     => 'Nov 15, 2025',
            'organization' => 'Abot-Kaya Inc.',
            'applied_on'   => 'Oct 10, 2025',
            'stage'        => 'Submitted',
            'status'       => 'submitted',
            'can_withdraw' => false,
        ],
        [
            'id'           => 3,
            'scholarship'  => 'PH Merit Scholars Program',
            'deadline'     => 'Dec 1, 2025',
            'organization' => 'PH Merit Foundation',
            'applied_on'   => 'Sep 28, 2025',
            'stage'        => 'Final Decision',
            'status'       => 'approved',
            'can_withdraw' => false,
        ],
    ];

    $activeFilter = $activeFilter ?? 'all';

    /* ── Tab counts ── */
    $counts = [
        'all'          => count($applications),
        'under_review' => collect($applications)->where('status', 'under_review')->count(),
        'submitted'    => collect($applications)->where('status', 'submitted')->count(),
        'approved'     => collect($applications)->where('status', 'approved')->count(),
    ];

    /* ── Nav items ── */
    $mainNav = [
        ['icon' => '🏠', 'label' => 'Dashboard',    'href' => '/applicant/dashboard',    'active' => false],
        ['icon' => '🔍', 'label' => 'Browse',        'href' => '/applicant/browse',        'active' => false],
        ['icon' => '📋', 'label' => 'Applications',  'href' => '/applicant/applications',  'active' => true,  'badge' => $counts['all']],
        ['icon' => '🤖', 'label' => 'AI Matches',    'href' => '/applicant/ai-matches',    'active' => false],
    ];
    $accountNav = [
        ['icon' => '👤', 'label' => 'My Profile',    'href' => '/applicant/profile',       'active' => false],
        ['icon' => '📁', 'label' => 'Documents',     'href' => '/applicant/documents',     'active' => false],
        ['icon' => '🔔', 'label' => 'Notifications', 'href' => '/applicant/notifications', 'active' => false],
    ];

    /* ── Status config ── */
    $statusConfig = [
        'under_review' => ['label' => 'Under Review', 'class' => 'ma-status--review'],
        'submitted'    => ['label' => 'Submitted',    'class' => 'ma-status--submitted'],
        'approved'     => ['label' => 'Approved',     'class' => 'ma-status--approved'],
        'rejected'     => ['label' => 'Rejected',     'class' => 'ma-status--rejected'],
    ];
@endphp

{{-- ════════════════════════════════════════════════════════════════════════
     STYLES
════════════════════════════════════════════════════════════════════════ --}}
@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,700;0,800;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

/* ── Variables ── */
:root {
    --ma-teal:         #0d4a44;
    --ma-teal-mid:     #1a6b63;
    --ma-teal-light:   #e6f3f1;
    --ma-teal-xlight:  #f0f7f6;
    --ma-teal-nav:     #1b5e56;
    --ma-amber:        #d4a017;
    --ma-amber-light:  #fef6e4;
    --ma-amber-border: #f0cc80;
    --ma-green:        #27a96c;
    --ma-green-light:  #e8f7ef;
    --ma-orange:       #f07a30;
    --ma-purple:       #7c6eea;
    --ma-purple-light: #f0eeff;
    --ma-white:        #ffffff;
    --ma-bg:           #eaf4f3;
    --ma-surface:      #ffffff;
    --ma-border:       #e2ecea;
    --ma-text-dark:    #0d2e2b;
    --ma-text-mid:     #4a6460;
    --ma-text-light:   #8aaba6;
    --ma-shadow-sm:    0 1px 4px rgba(0,0,0,.06);
    --ma-shadow-md:    0 4px 20px rgba(0,0,0,.09);
    --ma-shadow-lg:    0 8px 36px rgba(0,0,0,.12);
    --ma-radius-sm:    6px;
    --ma-radius-md:    10px;
    --ma-radius-lg:    16px;
    --ma-radius-pill:  9999px;
    --ma-font:         'DM Sans', sans-serif;
    --ma-font-display: 'Fraunces', serif;
    --ma-sidebar-w:    220px;
    --ma-transition:   .22s cubic-bezier(.4,0,.2,1);
}

/* ── Reset ── */
.ma-root *, .ma-root *::before, .ma-root *::after {
    box-sizing: border-box; margin: 0; padding: 0;
}
.ma-root {
    font-family: var(--ma-font);
    background:  var(--ma-bg);
    min-height:  100vh;
    padding:     36px 28px 60px;
    color:       var(--ma-text-dark);
    -webkit-font-smoothing: antialiased;
}

/* ════════════════════════════════════════════
   01 — PAGE HEADER
════════════════════════════════════════════ */
.ma-page-header { margin-bottom: 24px; }
.ma-page-eyebrow {
    font-size:      11px;
    font-weight:    700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color:          var(--ma-teal-mid);
    margin-bottom:  8px;
}
.ma-page-title {
    font-family:  var(--ma-font-display);
    font-size:    38px;
    font-weight:  800;
    color:        var(--ma-teal);
    line-height:  1.1;
    margin-bottom:10px;
}
.ma-page-breadcrumb {
    display:       inline-block;
    padding:       4px 12px;
    background:    var(--ma-white);
    border:        1px solid var(--ma-border);
    border-radius: var(--ma-radius-sm);
    font-size:     12px;
    color:         var(--ma-text-mid);
    font-family:   monospace;
}

/* ════════════════════════════════════════════
   02 — APP SHELL
════════════════════════════════════════════ */
.ma-shell {
    background:    var(--ma-surface);
    border-radius: var(--ma-radius-lg);
    box-shadow:    var(--ma-shadow-lg);
    overflow:      hidden;
    border:        1px solid var(--ma-border);
    animation:     ma-rise .4s cubic-bezier(.4,0,.2,1) both;
}
@keyframes ma-rise {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0);    }
}

/* Top nav bar */
.ma-topbar {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         0 24px;
    height:          60px;
    border-bottom:   1px solid var(--ma-border);
    background:      var(--ma-white);
}
.ma-topbar__brand {
    display:     flex;
    align-items: center;
    gap:         10px;
    font-weight: 700;
    font-size:   17px;
    color:       var(--ma-teal);
    letter-spacing: -.3px;
}
.ma-topbar__logo {
    width:           36px;
    height:          36px;
    border-radius:   var(--ma-radius-md);
    background:      var(--ma-teal);
    display:         flex;
    align-items:     center;
    justify-content: center;
    font-size:       18px;
}
.ma-topbar__search {
    flex:          1;
    max-width:     340px;
    margin:        0 32px;
    position:      relative;
}
.ma-topbar__search input {
    width:         100%;
    padding:       9px 16px 9px 38px;
    border-radius: var(--ma-radius-pill);
    border:        1.5px solid var(--ma-border);
    background:    var(--ma-teal-xlight);
    font-family:   var(--ma-font);
    font-size:     13.5px;
    color:         var(--ma-text-dark);
    outline:       none;
    transition:    border-color var(--ma-transition), box-shadow var(--ma-transition);
}
.ma-topbar__search input:focus {
    border-color: var(--ma-teal-mid);
    box-shadow:   0 0 0 3px rgba(26,107,99,.1);
}
.ma-topbar__search input::placeholder { color: var(--ma-text-light); }
.ma-topbar__search-icon {
    position:  absolute;
    left:      13px;
    top:       50%;
    transform: translateY(-50%);
    font-size: 14px;
    color:     var(--ma-text-light);
    pointer-events: none;
}
.ma-topbar__actions { display: flex; align-items: center; gap: 12px; }
.ma-topbar__bell {
    position: relative;
    width:    38px;
    height:   38px;
    border-radius: var(--ma-radius-pill);
    background:    var(--ma-teal-xlight);
    border:        1.5px solid var(--ma-border);
    display:       flex;
    align-items:   center;
    justify-content:center;
    cursor:        pointer;
    font-size:     16px;
    transition:    background var(--ma-transition);
}
.ma-topbar__bell:hover { background: var(--ma-teal-light); }
.ma-topbar__bell-dot {
    position:      absolute;
    top:           6px;
    right:         6px;
    width:         8px;
    height:        8px;
    border-radius: var(--ma-radius-pill);
    background:    var(--ma-amber);
    border:        2px solid var(--ma-white);
}
.ma-topbar__avatar {
    width:           36px;
    height:          36px;
    border-radius:   var(--ma-radius-pill);
    background:      var(--ma-teal);
    color:           var(--ma-white);
    font-weight:     700;
    font-size:       13px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    cursor:          pointer;
    transition:      transform var(--ma-transition), box-shadow var(--ma-transition);
}
.ma-topbar__avatar:hover { transform: scale(1.07); box-shadow: 0 2px 8px rgba(0,0,0,.16); }

/* Body: sidebar + content */
.ma-body {
    display:               grid;
    grid-template-columns: var(--ma-sidebar-w) 1fr;
    min-height:            540px;
}

/* ════════════════════════════════════════════
   03 — SIDEBAR
════════════════════════════════════════════ */
.ma-sidebar {
    border-right:   1px solid var(--ma-border);
    padding:        20px 12px;
    display:        flex;
    flex-direction: column;
    gap:            24px;
    background:     var(--ma-white);
}
.ma-sidebar__group-label {
    font-size:      10px;
    font-weight:    700;
    letter-spacing: .13em;
    text-transform: uppercase;
    color:          var(--ma-text-light);
    padding:        0 10px;
    margin-bottom:  6px;
}
.ma-nav-item {
    display:         flex;
    align-items:     center;
    gap:             9px;
    padding:         9px 12px;
    border-radius:   var(--ma-radius-md);
    font-size:       13.5px;
    font-weight:     500;
    color:           var(--ma-text-mid);
    text-decoration: none;
    cursor:          pointer;
    transition:      background var(--ma-transition), color var(--ma-transition);
    position:        relative;
    user-select:     none;
}
.ma-nav-item:hover:not(.is-active) { background: var(--ma-teal-xlight); color: var(--ma-teal); }
.ma-nav-item.is-active {
    background: var(--ma-teal-light);
    color:      var(--ma-teal);
    font-weight:700;
}
.ma-nav-item__icon { font-size: 15px; flex-shrink: 0; }
.ma-nav-item__badge {
    margin-left:    auto;
    min-width:      20px;
    height:         20px;
    padding:        0 6px;
    border-radius:  var(--ma-radius-pill);
    background:     var(--ma-teal);
    color:          var(--ma-white);
    font-size:      11px;
    font-weight:    700;
    text-align:     center;
    line-height:    20px;
}

/* Sidebar user card */
.ma-sidebar__user {
    margin-top:      auto;
    display:         flex;
    align-items:     center;
    gap:             10px;
    padding:         10px 12px;
    border-radius:   var(--ma-radius-md);
    background:      var(--ma-teal-xlight);
    border:          1px solid var(--ma-border);
    cursor:          pointer;
    transition:      background var(--ma-transition);
}
.ma-sidebar__user:hover { background: var(--ma-teal-light); }
.ma-sidebar__user-avatar {
    width:           34px;
    height:          34px;
    border-radius:   var(--ma-radius-pill);
    background:      var(--ma-teal);
    color:           var(--ma-white);
    font-weight:     700;
    font-size:       13px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}
.ma-sidebar__user-name { font-size: 13px; font-weight: 600; color: var(--ma-text-dark); }
.ma-sidebar__user-role { font-size: 11px; color: var(--ma-text-light); }

/* ════════════════════════════════════════════
   04 — MAIN CONTENT
════════════════════════════════════════════ */
.ma-main {
    padding:        28px 28px 32px;
    background:     var(--ma-white);
    display:        flex;
    flex-direction: column;
    gap:            20px;
}

/* Content header */
.ma-content-header {
    display:         flex;
    align-items:     flex-end;
    justify-content: space-between;
    gap:             16px;
}
.ma-content-eyebrow {
    font-size:      10.5px;
    font-weight:    700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color:          var(--ma-teal-mid);
    margin-bottom:  5px;
}
.ma-content-title {
    font-family: var(--ma-font-display);
    font-size:   28px;
    font-weight: 800;
    color:       var(--ma-teal);
    line-height: 1.1;
}

/* Apply button */
.ma-btn-apply {
    display:       inline-flex;
    align-items:   center;
    gap:           6px;
    padding:       11px 20px;
    border-radius: var(--ma-radius-md);
    background:    var(--ma-teal);
    color:         var(--ma-white);
    font-family:   var(--ma-font);
    font-size:     13.5px;
    font-weight:   600;
    border:        none;
    cursor:        pointer;
    white-space:   nowrap;
    flex-shrink:   0;
    transition:    background var(--ma-transition), transform .15s ease, box-shadow var(--ma-transition);
}
.ma-btn-apply:hover  { background: var(--ma-teal-mid); transform: translateY(-1px); box-shadow: var(--ma-shadow-md); }
.ma-btn-apply:active { transform: translateY(0); }

/* Filter tabs */
.ma-tabs {
    display:    flex;
    gap:        4px;
    background: var(--ma-teal-xlight);
    padding:    4px;
    border-radius: var(--ma-radius-md);
    width:      fit-content;
}
.ma-tab {
    padding:       7px 16px;
    border-radius: var(--ma-radius-sm);
    font-size:     13px;
    font-weight:   500;
    color:         var(--ma-text-mid);
    cursor:        pointer;
    border:        none;
    background:    transparent;
    font-family:   var(--ma-font);
    transition:    background var(--ma-transition), color var(--ma-transition), box-shadow var(--ma-transition);
    white-space:   nowrap;
}
.ma-tab:hover:not(.is-active) { background: var(--ma-white); color: var(--ma-teal); }
.ma-tab.is-active {
    background: var(--ma-white);
    color:      var(--ma-teal);
    font-weight:700;
    box-shadow: var(--ma-shadow-sm);
}

/* ════════════════════════════════════════════
   05 — APPLICATIONS TABLE
════════════════════════════════════════════ */
.ma-table-wrap {
    border:        1px solid var(--ma-border);
    border-radius: var(--ma-radius-lg);
    overflow:      hidden;
}
.ma-table {
    width:           100%;
    border-collapse: collapse;
}

/* Table head */
.ma-table thead tr {
    background:    var(--ma-teal-xlight);
    border-bottom: 1px solid var(--ma-border);
}
.ma-table th {
    padding:        10px 16px;
    font-size:      10.5px;
    font-weight:    700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color:          var(--ma-text-light);
    text-align:     left;
    white-space:    nowrap;
}

/* Table body */
.ma-table tbody tr {
    border-bottom:  1px solid var(--ma-border);
    transition:     background var(--ma-transition);
    animation:      ma-row-in .3s ease both;
}
.ma-table tbody tr:last-child { border-bottom: none; }
.ma-table tbody tr:hover { background: var(--ma-teal-xlight); }

@keyframes ma-row-in {
    from { opacity: 0; transform: translateX(-6px); }
    to   { opacity: 1; transform: translateX(0);    }
}
.ma-table tbody tr:nth-child(1) { animation-delay: .05s; }
.ma-table tbody tr:nth-child(2) { animation-delay: .12s; }
.ma-table tbody tr:nth-child(3) { animation-delay: .19s; }
.ma-table tbody tr:nth-child(4) { animation-delay: .26s; }
.ma-table tbody tr:nth-child(5) { animation-delay: .33s; }

.ma-table td { padding: 14px 16px; vertical-align: middle; }

/* Scholarship name cell */
.ma-app-name {
    font-size:   14px;
    font-weight: 600;
    color:       var(--ma-text-dark);
    margin-bottom:3px;
}
.ma-app-deadline { font-size: 11.5px; color: var(--ma-teal-mid); }

.ma-org   { font-size: 13px; color: var(--ma-text-mid); }
.ma-date  { font-size: 13px; color: var(--ma-text-mid); white-space: nowrap; }
.ma-stage { font-size: 13px; color: var(--ma-text-mid); }

/* Status badges */
.ma-status {
    display:       inline-block;
    padding:       5px 12px;
    border-radius: var(--ma-radius-pill);
    font-size:     12px;
    font-weight:   600;
    white-space:   nowrap;
}
.ma-status--review    { background: var(--ma-purple-light); color: var(--ma-purple); border: 1px solid #d0c8f8; }
.ma-status--submitted { background: var(--ma-amber-light);  color: var(--ma-amber);  border: 1px solid var(--ma-amber-border); }
.ma-status--approved  { background: var(--ma-green-light);  color: var(--ma-green);  border: 1px solid #a8e6c8; }
.ma-status--rejected  { background: #fdeaea;                color: #e05050;          border: 1px solid #f5bcbc; }

/* Action buttons */
.ma-actions { display: flex; align-items: center; gap: 6px; }
.ma-btn {
    padding:       6px 14px;
    border-radius: var(--ma-radius-sm);
    font-family:   var(--ma-font);
    font-size:     12.5px;
    font-weight:   600;
    cursor:        pointer;
    border:        1.5px solid var(--ma-border);
    background:    var(--ma-white);
    color:         var(--ma-text-dark);
    transition:    all var(--ma-transition);
    white-space:   nowrap;
}
.ma-btn:hover { background: var(--ma-teal-xlight); border-color: var(--ma-teal-mid); color: var(--ma-teal); }

.ma-btn--withdraw {
    border-color: #f5a08a;
    color:        var(--ma-orange);
}
.ma-btn--withdraw:hover {
    background:   #fff4ef;
    border-color: var(--ma-orange);
    color:        var(--ma-orange);
}

/* Table footer */
.ma-table-footer {
    padding:     12px 16px;
    font-size:   12.5px;
    color:       var(--ma-text-light);
    background:  var(--ma-teal-xlight);
    border-top:  1px solid var(--ma-border);
}

/* ── Responsive ── */
@media (max-width: 800px) {
    .ma-body { grid-template-columns: 1fr; }
    .ma-sidebar { display: none; }
    .ma-table th:nth-child(3),
    .ma-table td:nth-child(3) { display: none; } /* hide Applied On */
}
</style>
@endpush


{{-- ════════════════════════════════════════════════════════════════════════
     HTML
════════════════════════════════════════════════════════════════════════ --}}
<div class="ma-root">

    {{-- 01 — PAGE HEADER --}}
    <div class="ma-page-header">
        <div class="ma-page-eyebrow">Applicant</div>
        <h1 class="ma-page-title">My Applications</h1>
        <span class="ma-page-breadcrumb">/applicant/applications</span>
    </div>

    {{-- 02 — APP SHELL --}}
    <div class="ma-shell">

        {{-- Top nav bar --}}
        <div class="ma-topbar">
            <div class="ma-topbar__brand">
                <div class="ma-topbar__logo">🎓</div>
                ScholarLink
            </div>

            <div class="ma-topbar__search">
                <span class="ma-topbar__search-icon">🔍</span>
                <input type="text" placeholder="Search…" aria-label="Search scholarships" />
            </div>

            <div class="ma-topbar__actions">
                <div class="ma-topbar__bell" aria-label="Notifications">
                    🔔
                    <span class="ma-topbar__bell-dot"></span>
                </div>
                <div class="ma-topbar__avatar" title="{{ $user['name'] }}">
                    {{ $user['initials'] }}
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="ma-body">

            {{-- 03 — SIDEBAR --}}
            <aside class="ma-sidebar">
                {{-- Main nav group --}}
                <div>
                    <div class="ma-sidebar__group-label">Main</div>
                    @foreach ($mainNav as $item)
                        <a href="{{ $item['href'] }}"
                           class="ma-nav-item {{ $item['active'] ? 'is-active' : '' }}"
                           aria-current="{{ $item['active'] ? 'page' : 'false' }}">
                            <span class="ma-nav-item__icon">{{ $item['icon'] }}</span>
                            {{ $item['label'] }}
                            @if (!empty($item['badge']))
                                <span class="ma-nav-item__badge">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                {{-- Account nav group --}}
                <div>
                    <div class="ma-sidebar__group-label">Account</div>
                    @foreach ($accountNav as $item)
                        <a href="{{ $item['href'] }}"
                           class="ma-nav-item {{ $item['active'] ? 'is-active' : '' }}"
                           aria-current="{{ $item['active'] ? 'page' : 'false' }}">
                            <span class="ma-nav-item__icon">{{ $item['icon'] }}</span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- User card --}}
                <div class="ma-sidebar__user">
                    <div class="ma-sidebar__user-avatar">{{ $user['initials'] }}</div>
                    <div>
                        <div class="ma-sidebar__user-name">{{ $user['name'] }}</div>
                        <div class="ma-sidebar__user-role">{{ $user['role'] }}</div>
                    </div>
                </div>
            </aside>

            {{-- 04 — MAIN CONTENT --}}
            <main class="ma-main">

                {{-- Content header --}}
                <div class="ma-content-header">
                    <div>
                        <div class="ma-content-eyebrow">My Applications</div>
                        <h2 class="ma-content-title">Track your submissions</h2>
                    </div>
                    <button class="ma-btn-apply"
                            onclick="MyApplications.applyNew()">
                        + Apply to Scholarship
                    </button>
                </div>

                {{-- Filter tabs --}}
                <div class="ma-tabs"
                     role="tablist"
                     aria-label="Filter applications">
                    <button class="ma-tab {{ $activeFilter === 'all' ? 'is-active' : '' }}"
                            role="tab"
                            aria-selected="{{ $activeFilter === 'all' ? 'true' : 'false' }}"
                            onclick="MyApplications.filter('all', this)">
                        All ({{ $counts['all'] }})
                    </button>
                    @if ($counts['under_review'] > 0)
                    <button class="ma-tab {{ $activeFilter === 'under_review' ? 'is-active' : '' }}"
                            role="tab"
                            aria-selected="{{ $activeFilter === 'under_review' ? 'true' : 'false' }}"
                            onclick="MyApplications.filter('under_review', this)">
                        Under Review ({{ $counts['under_review'] }})
                    </button>
                    @endif
                    @if ($counts['submitted'] > 0)
                    <button class="ma-tab {{ $activeFilter === 'submitted' ? 'is-active' : '' }}"
                            role="tab"
                            aria-selected="{{ $activeFilter === 'submitted' ? 'true' : 'false' }}"
                            onclick="MyApplications.filter('submitted', this)">
                        Submitted ({{ $counts['submitted'] }})
                    </button>
                    @endif
                    @if ($counts['approved'] > 0)
                    <button class="ma-tab {{ $activeFilter === 'approved' ? 'is-active' : '' }}"
                            role="tab"
                            aria-selected="{{ $activeFilter === 'approved' ? 'true' : 'false' }}"
                            onclick="MyApplications.filter('approved', this)">
                        Approved ({{ $counts['approved'] }})
                    </button>
                    @endif
                </div>

                {{-- 05 — APPLICATIONS TABLE --}}
                <div class="ma-table-wrap">
                    <table class="ma-table" id="ma-applications-table">
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
                        <tbody id="ma-table-body">
                            @forelse ($applications as $app)
                                <tr data-status="{{ $app['status'] }}" data-id="{{ $app['id'] }}">

                                    {{-- Scholarship name + deadline --}}
                                    <td>
                                        <div class="ma-app-name">{{ $app['scholarship'] }}</div>
                                        <div class="ma-app-deadline">Deadline: {{ $app['deadline'] }}</div>
                                    </td>

                                    {{-- Organization --}}
                                    <td><span class="ma-org">{{ $app['organization'] }}</span></td>

                                    {{-- Applied on --}}
                                    <td><span class="ma-date">{{ $app['applied_on'] }}</span></td>

                                    {{-- Stage --}}
                                    <td><span class="ma-stage">{{ $app['stage'] }}</span></td>

                                    {{-- Status badge --}}
                                    <td>
                                        @php
                                            $sc = $statusConfig[$app['status']] ?? ['label' => ucfirst($app['status']), 'class' => ''];
                                        @endphp
                                        <span class="ma-status {{ $sc['class'] }}">
                                            {{ $sc['label'] }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="ma-actions">
                                            <button class="ma-btn"
                                                    onclick="MyApplications.view({{ $app['id'] }})">
                                                View
                                            </button>
                                            @if ($app['can_withdraw'])
                                                <button class="ma-btn ma-btn--withdraw"
                                                        onclick="MyApplications.withdraw({{ $app['id'] }})">
                                                    Withdraw
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align:center;padding:32px;color:var(--ma-text-light);">
                                        No applications found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Table footer --}}
                    <div class="ma-table-footer" id="ma-table-footer">
                        {{ $counts['all'] }} application{{ $counts['all'] !== 1 ? 's' : '' }} total
                    </div>
                </div>

            </main>{{-- /ma-main --}}
        </div>{{-- /ma-body --}}
    </div>{{-- /ma-shell --}}
</div>{{-- /ma-root --}}


{{-- ════════════════════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    'use strict';

    /**
     * MyApplications — client-side interactions.
     *
     * Filter tabs work entirely in JS (no page reload) for a smooth UX.
     * Replace view() / withdraw() stubs with real route redirects or
     * Axios calls to your Laravel API.
     */
    window.MyApplications = {

        /**
         * filter — show only rows matching the given status key.
         * @param {string} status  'all' | 'under_review' | 'submitted' | 'approved'
         * @param {HTMLElement} tabEl  The clicked tab button
         */
        filter(status, tabEl) {
            // Update active tab
            document.querySelectorAll('.ma-tab').forEach(t => {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });
            tabEl.classList.add('is-active');
            tabEl.setAttribute('aria-selected', 'true');

            // Show / hide rows
            const rows    = document.querySelectorAll('#ma-table-body tr[data-status]');
            let   visible = 0;

            rows.forEach(row => {
                const match = status === 'all' || row.dataset.status === status;
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            // Update footer count
            const footer = document.getElementById('ma-table-footer');
            if (footer) {
                footer.textContent = visible + ' application' + (visible !== 1 ? 's' : '') + (status === 'all' ? ' total' : ' found');
            }
        },

        /**
         * view — navigate to the application detail page.
         * Replace with: window.location.href = `/applicant/applications/${id}`;
         * @param {number} id
         */
        view(id) {
            console.log('[MyApplications] View →', id);
            // TODO: window.location.href = `/applicant/applications/${id}`;
            this._toast('Opening application #' + id + '…', 'info');
        },

        /**
         * withdraw — confirm then POST withdraw request.
         * @param {number} id
         */
        withdraw(id) {
            if (!confirm('Are you sure you want to withdraw this application? This action cannot be undone.')) return;
            console.log('[MyApplications] Withdraw →', id);
            // TODO: axios.post(`/api/applications/${id}/withdraw`)
            //             .then(() => window.location.reload());
            this._toast('Application withdrawn.', 'warning');

            // Optimistically hide the row
            const row = document.querySelector(`#ma-table-body tr[data-id="${id}"]`);
            if (row) {
                row.style.transition = 'opacity .3s ease';
                row.style.opacity    = '0';
                setTimeout(() => row.remove(), 300);
            }
        },

        /**
         * applyNew — redirect to the scholarship browser / apply page.
         */
        applyNew() {
            console.log('[MyApplications] Apply to new scholarship');
            // TODO: window.location.href = '/applicant/browse';
            this._toast('Redirecting to scholarship browser…', 'info');
        },

        /**
         * Simple toast notification.
         */
        _toast(message, type = 'info') {
            const colors = {
                info:    'var(--ma-teal)',
                warning: 'var(--ma-orange)',
                error:   '#e05050',
            };
            const t = document.createElement('div');
            Object.assign(t.style, {
                position:     'fixed',
                bottom:       '24px',
                right:        '24px',
                padding:      '12px 20px',
                borderRadius: '10px',
                background:   colors[type] || colors.info,
                color:        '#fff',
                fontFamily:   'DM Sans, sans-serif',
                fontSize:     '13.5px',
                fontWeight:   '600',
                boxShadow:    '0 4px 18px rgba(0,0,0,.18)',
                zIndex:       '9999',
                opacity:      '0',
                transform:    'translateY(10px)',
                transition:   'opacity .25s ease, transform .25s ease',
                maxWidth:     '280px',
            });
            t.textContent = message;
            document.body.appendChild(t);
            requestAnimationFrame(() => requestAnimationFrame(() => {
                t.style.opacity   = '1';
                t.style.transform = 'translateY(0)';
            }));
            setTimeout(() => {
                t.style.opacity   = '0';
                t.style.transform = 'translateY(10px)';
                setTimeout(() => t.remove(), 300);
            }, 3000);
        },
    };
})();
</script>
@endpush
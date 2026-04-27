{{--
|--------------------------------------------------------------------------
| my-profile.blade.php
|--------------------------------------------------------------------------
| ScholarLink — Applicant "My Profile" Page
|
| Sections:
|   01 — Page Header (eyebrow + title + breadcrumb)
|   02 — App Shell (topbar + sidebar + main)
|   03 — Sidebar Navigation
|   04 — Profile Hero Banner
|   05 — Profile Completion Bar
|   06 — Personal Information Card
|   07 — Academic Information Card
|   08 — Document Wallet Status Card
|
| Usage:
|   @include('components.my-profile', [
|       'user'      => $user,
|       'personal'  => $personal,
|       'academic'  => $academic,
|       'documents' => $documents,
|       'completion'=> $completion,
|   ])
--}}

@php
    /* ── Database-Driven Data from authenticated user ─────────────────── */
    
    $applicantUser = auth()->user();
    $applicantProfile = $applicantUser?->applicantProfile;
    
    // Document status configuration
    $docStatusConfig = [
        'verified' => ['label' => 'Verified', 'class' => 'mp-doc--verified'],
        'expired'  => ['label' => 'Expired',  'class' => 'mp-doc--expired'],
        'pending'  => ['label' => 'Pending',  'class' => 'mp-doc--pending'],
        'missing'  => ['label' => 'Missing',  'class' => 'mp-doc--missing'],
    ];
@endphp

{{-- ════════════════════════════════════════════════════════════════════
     STYLES
════════════════════════════════════════════════════════════════════ --}}
@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@700;800;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap');

/* ── Variables ── */
:root {
    --mp-teal:         #0c4a44;
    --mp-teal-mid:     #1a6b63;
    --mp-teal-hero:    #0e5550;      /* hero banner bg */
    --mp-teal-light:   #e6f3f1;
    --mp-teal-xlight:  #f0f8f6;
    --mp-amber:        #c8920a;
    --mp-amber-light:  #fef6e4;
    --mp-amber-border: #f0cc80;
    --mp-green:        #27a96c;
    --mp-green-light:  #e8f7ef;
    --mp-red:          #e05050;
    --mp-red-light:    #fdeaea;
    --mp-white:        #ffffff;
    --mp-bg:           #f0f4f3;
    --mp-surface:      #ffffff;
    --mp-border:       #e2ecea;
    --mp-text-dark:    #0d2e2b;
    --mp-text-mid:     #4a6460;
    --mp-text-light:   #8aaba6;
    --mp-shadow-sm:    0 1px 4px rgba(0,0,0,.06);
    --mp-shadow-md:    0 4px 20px rgba(0,0,0,.09);
    --mp-shadow-lg:    0 10px 40px rgba(0,0,0,.12);
    --mp-radius-sm:    6px;
    --mp-radius-md:    10px;
    --mp-radius-lg:    16px;
    --mp-radius-pill:  9999px;
    --mp-font:         'DM Sans', sans-serif;
    --mp-font-display: 'Fraunces', serif;
    --mp-sidebar-w:    220px;
    --mp-transition:   .22s cubic-bezier(.4,0,.2,1);
}

/* ── Reset ── */
.mp-root *, .mp-root *::before, .mp-root *::after {
    box-sizing: border-box; margin: 0; padding: 0;
}
.mp-root {
    font-family:             var(--mp-font);
    background:              var(--mp-bg);
    min-height:              100vh;
    padding:                 36px 28px 60px;
    color:                   var(--mp-text-dark);
    -webkit-font-smoothing:  antialiased;
}

/* ════════════════════════════════════════════
   01 — PAGE HEADER
════════════════════════════════════════════ */
.mp-page-header { margin-bottom: 22px; }
.mp-page-eyebrow {
    font-size:      11px;
    font-weight:    700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color:          var(--mp-teal-mid);
    margin-bottom:  6px;
}
.mp-page-title {
    font-family:  var(--mp-font-display);
    font-size:    36px;
    font-weight:  800;
    color:        var(--mp-teal);
    line-height:  1.1;
    margin-bottom:8px;
}
.mp-page-breadcrumb {
    display:       inline-block;
    padding:       4px 12px;
    background:    var(--mp-white);
    border:        1px solid var(--mp-border);
    border-radius: var(--mp-radius-sm);
    font-size:     12px;
    color:         var(--mp-text-mid);
    font-family:   monospace;
}

/* ════════════════════════════════════════════
   02 — APP SHELL
════════════════════════════════════════════ */
.mp-shell {
    background:    var(--mp-surface);
    border-radius: var(--mp-radius-lg);
    box-shadow:    var(--mp-shadow-lg);
    overflow:      hidden;
    border:        1px solid var(--mp-border);
    animation:     mp-rise .38s cubic-bezier(.4,0,.2,1) both;
}
@keyframes mp-rise {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0);    }
}

/* Top nav */
.mp-topbar {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         0 24px;
    height:          60px;
    background:      var(--mp-white);
    border-bottom:   1px solid var(--mp-border);
    position:        sticky;
    top:             0;
    z-index:         10;
}
.mp-topbar__brand {
    display:     flex;
    align-items: center;
    gap:         10px;
    font-weight: 700;
    font-size:   17px;
    color:       var(--mp-teal);
    letter-spacing: -.3px;
}
.mp-topbar__logo {
    width:           36px;
    height:          36px;
    border-radius:   var(--mp-radius-md);
    background:      var(--mp-teal);
    display:         flex;
    align-items:     center;
    justify-content: center;
    font-size:       18px;
}
.mp-topbar__search {
    flex:       1;
    max-width:  320px;
    margin:     0 28px;
    position:   relative;
}
.mp-topbar__search input {
    width:         100%;
    padding:       9px 16px 9px 36px;
    border-radius: var(--mp-radius-pill);
    border:        1.5px solid var(--mp-border);
    background:    var(--mp-teal-xlight);
    font-family:   var(--mp-font);
    font-size:     13.5px;
    color:         var(--mp-text-dark);
    outline:       none;
    transition:    border-color var(--mp-transition), box-shadow var(--mp-transition);
}
.mp-topbar__search input::placeholder { color: var(--mp-text-light); }
.mp-topbar__search input:focus {
    border-color: var(--mp-teal-mid);
    box-shadow:   0 0 0 3px rgba(26,107,99,.1);
}
.mp-topbar__search-icon {
    position:       absolute;
    left:           12px;
    top:            50%;
    transform:      translateY(-50%);
    font-size:      14px;
    pointer-events: none;
    color:          var(--mp-text-light);
}
.mp-topbar__actions { display: flex; align-items: center; gap: 10px; }
.mp-topbar__bell {
    position:        relative;
    width:           36px;
    height:          36px;
    border-radius:   var(--mp-radius-pill);
    background:      var(--mp-teal-xlight);
    border:          1.5px solid var(--mp-border);
    display:         flex;
    align-items:     center;
    justify-content: center;
    cursor:          pointer;
    font-size:       16px;
    transition:      background var(--mp-transition);
}
.mp-topbar__bell:hover { background: var(--mp-teal-light); }
.mp-topbar__bell-dot {
    position:      absolute;
    top:           5px;
    right:         5px;
    width:         8px;
    height:        8px;
    border-radius: var(--mp-radius-pill);
    background:    var(--mp-amber);
    border:        2px solid var(--mp-white);
}
.mp-topbar__avatar {
    width:           36px;
    height:          36px;
    border-radius:   var(--mp-radius-pill);
    background:      var(--mp-teal);
    color:           var(--mp-white);
    font-weight:     700;
    font-size:       13px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    cursor:          pointer;
    transition:      transform var(--mp-transition), box-shadow var(--mp-transition);
}
.mp-topbar__avatar:hover { transform: scale(1.07); box-shadow: 0 2px 8px rgba(0,0,0,.15); }

/* Body grid */
.mp-body {
    display:               grid;
    grid-template-columns: var(--mp-sidebar-w) 1fr;
    min-height:            700px;
}

/* ════════════════════════════════════════════
   03 — SIDEBAR
════════════════════════════════════════════ */
.mp-sidebar {
    border-right:    1px solid var(--mp-border);
    padding:         20px 12px;
    display:         flex;
    flex-direction:  column;
    gap:             22px;
    background:      var(--mp-white);
}
.mp-sidebar__group-label {
    font-size:      10px;
    font-weight:    700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color:          var(--mp-text-light);
    padding:        0 10px;
    margin-bottom:  5px;
}
.mp-nav-item {
    display:         flex;
    align-items:     center;
    gap:             9px;
    padding:         9px 12px;
    border-radius:   var(--mp-radius-md);
    font-size:       13.5px;
    font-weight:     500;
    color:           var(--mp-text-mid);
    text-decoration: none;
    cursor:          pointer;
    transition:      background var(--mp-transition), color var(--mp-transition);
}
.mp-nav-item:hover:not(.is-active) { background: var(--mp-teal-xlight); color: var(--mp-teal); }
.mp-nav-item.is-active {
    background:  var(--mp-teal-light);
    color:       var(--mp-teal);
    font-weight: 700;
}
.mp-nav-item__icon  { font-size: 15px; flex-shrink: 0; }
.mp-nav-item__label { flex: 1; }
.mp-nav-item__badge {
    min-width:     20px;
    height:        20px;
    padding:       0 5px;
    border-radius: var(--mp-radius-pill);
    background:    var(--mp-teal);
    color:         var(--mp-white);
    font-size:     11px;
    font-weight:   700;
    text-align:    center;
    line-height:   20px;
}
.mp-sidebar__user {
    margin-top:     auto;
    display:        flex;
    align-items:    center;
    gap:            10px;
    padding:        10px 12px;
    border-radius:  var(--mp-radius-md);
    background:     var(--mp-teal-xlight);
    border:         1px solid var(--mp-border);
    cursor:         pointer;
    transition:     background var(--mp-transition);
}
.mp-sidebar__user:hover { background: var(--mp-teal-light); }
.mp-sidebar__user-avatar {
    width:           34px;
    height:          34px;
    border-radius:   var(--mp-radius-pill);
    background:      var(--mp-teal);
    color:           var(--mp-white);
    font-weight:     700;
    font-size:       13px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}
.mp-sidebar__user-name { font-size: 13px; font-weight: 600; color: var(--mp-text-dark); }
.mp-sidebar__user-role { font-size: 11px; color: var(--mp-text-light); }

/* ════════════════════════════════════════════
   MAIN CONTENT
════════════════════════════════════════════ */
.mp-main {
    display:        flex;
    flex-direction: column;
    overflow:       hidden;
    background:     var(--mp-teal-xlight);
}

/* ════════════════════════════════════════════
   04 — HERO BANNER
════════════════════════════════════════════ */
.mp-hero {
    background:      var(--mp-teal-hero);
    padding:         28px 32px 28px;
    display:         flex;
    align-items:     center;
    gap:             20px;
    position:        relative;
    overflow:        hidden;
}

/* Subtle decorative circles */
.mp-hero::before,
.mp-hero::after {
    content:       '';
    position:      absolute;
    border-radius: 50%;
    background:    rgba(255,255,255,.04);
    pointer-events:none;
}
.mp-hero::before { width:280px; height:280px; right:-60px; top:-80px; }
.mp-hero::after  { width:180px; height:180px; right:60px;  top:80px;  }

.mp-hero__avatar {
    width:           72px;
    height:          72px;
    border-radius:   var(--mp-radius-pill);
    background:      rgba(255,255,255,.18);
    border:          3px solid rgba(255,255,255,.3);
    display:         flex;
    align-items:     center;
    justify-content: center;
    font-weight:     800;
    font-size:       26px;
    color:           var(--mp-white);
    flex-shrink:     0;
    overflow:        hidden;
    backdrop-filter: blur(4px);
}
.mp-hero__avatar img { width:100%; height:100%; object-fit:cover; }

.mp-hero__info { flex: 1; }
.mp-hero__name {
    font-family:  var(--mp-font-display);
    font-size:    28px;
    font-weight:  800;
    color:        var(--mp-white);
    margin-bottom:4px;
    line-height:  1.1;
}
.mp-hero__sub {
    font-size:    13.5px;
    color:        rgba(255,255,255,.75);
    margin-bottom:10px;
}
.mp-hero__updated {
    font-size:  12px;
    color:      rgba(255,255,255,.55);
    display:    flex;
    align-items:center;
    gap:        5px;
}

.mp-hero__edit-btn {
    display:        flex;
    align-items:    center;
    gap:            6px;
    padding:        9px 18px;
    border-radius:  var(--mp-radius-md);
    border:         1.5px solid rgba(255,255,255,.35);
    background:     rgba(255,255,255,.12);
    color:          var(--mp-white);
    font-family:    var(--mp-font);
    font-size:      13px;
    font-weight:    600;
    cursor:         pointer;
    white-space:    nowrap;
    backdrop-filter:blur(6px);
    transition:     background var(--mp-transition), border-color var(--mp-transition);
    flex-shrink:    0;
}
.mp-hero__edit-btn:hover { background: rgba(255,255,255,.22); border-color: rgba(255,255,255,.55); }

/* ════════════════════════════════════════════
   05 — PROFILE COMPLETION
════════════════════════════════════════════ */
.mp-section { padding: 20px 32px; }
.mp-section + .mp-section { padding-top: 0; }

.mp-completion {
    background:    var(--mp-white);
    border-radius: var(--mp-radius-lg);
    padding:       20px 24px;
    border:        1px solid var(--mp-border);
    box-shadow:    var(--mp-shadow-sm);
    animation:     mp-card-in .35s ease .05s both;
}
@keyframes mp-card-in {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0);   }
}

.mp-completion__header {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    margin-bottom:   12px;
}
.mp-completion__label  { font-size:14px; font-weight:600; color:var(--mp-text-dark); }
.mp-completion__right  { display:flex; align-items:baseline; gap:12px; }
.mp-completion__pct    { font-size:22px; font-weight:800; color:var(--mp-teal); }
.mp-completion__note   { font-size:12px; color:var(--mp-text-light); text-align:right; }

.mp-completion__bar {
    height:        10px;
    border-radius: var(--mp-radius-pill);
    background:    var(--mp-border);
    overflow:      hidden;
}
.mp-completion__fill {
    height:         100%;
    border-radius:  var(--mp-radius-pill);
    background:     linear-gradient(90deg, var(--mp-teal-mid) 0%, #c8920a 100%);
    transform-origin:left;
    animation:      mp-bar-grow .9s cubic-bezier(.4,0,.2,1) .15s both;
}
@keyframes mp-bar-grow {
    from { transform:scaleX(0); }
    to   { transform:scaleX(1); }
}

/* ════════════════════════════════════════════
   INFO CARDS (Personal / Academic)
════════════════════════════════════════════ */
.mp-info-card {
    background:    var(--mp-white);
    border-radius: var(--mp-radius-lg);
    border:        1px solid var(--mp-border);
    box-shadow:    var(--mp-shadow-sm);
    overflow:      hidden;
    animation:     mp-card-in .35s ease both;
}
.mp-info-card + .mp-info-card { margin-top: 16px; }

.mp-info-card__header {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         18px 24px;
    border-bottom:   1px solid var(--mp-border);
}
.mp-info-card__title  { font-size:15px; font-weight:700; color:var(--mp-text-dark); }
.mp-info-card__edit {
    display:     inline-flex;
    align-items: center;
    gap:         5px;
    font-size:   13px;
    font-weight: 600;
    color:       var(--mp-teal-mid);
    cursor:      pointer;
    padding:     5px 10px;
    border-radius: var(--mp-radius-sm);
    border:      none;
    background:  transparent;
    font-family: var(--mp-font);
    transition:  background var(--mp-transition), color var(--mp-transition);
}
.mp-info-card__edit:hover { background: var(--mp-teal-light); }

.mp-info-card__body { padding: 20px 24px; }

/* Info grid */
.mp-info-grid {
    display:               grid;
    grid-template-columns: 1fr 1fr;
    gap:                   18px 24px;
}
.mp-info-grid--wide { grid-template-columns: 1fr; }

.mp-field__label {
    font-size:      10.5px;
    font-weight:    700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color:          var(--mp-text-light);
    margin-bottom:  5px;
}
.mp-field__value {
    font-size:   14.5px;
    font-weight: 400;
    color:       var(--mp-text-dark);
    line-height: 1.4;
}
.mp-field__value--bold {
    font-size:   22px;
    font-weight: 800;
    color:       var(--mp-teal);
}

/* ════════════════════════════════════════════
   08 — DOCUMENT WALLET
════════════════════════════════════════════ */
.mp-doc-wallet {
    background:    var(--mp-white);
    border-radius: var(--mp-radius-lg);
    border:        1px solid var(--mp-border);
    box-shadow:    var(--mp-shadow-sm);
    margin-top:    16px;
    animation:     mp-card-in .35s ease .1s both;
}
.mp-doc-wallet__header {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         18px 24px;
    border-bottom:   1px solid var(--mp-border);
}
.mp-doc-wallet__title { font-size:15px; font-weight:700; color:var(--mp-text-dark); }
.mp-doc-wallet__manage {
    font-size:   13px;
    font-weight: 600;
    color:       var(--mp-teal-mid);
    cursor:      pointer;
    text-decoration: none;
    display:     flex;
    align-items: center;
    gap:         4px;
    transition:  color var(--mp-transition);
}
.mp-doc-wallet__manage:hover { color: var(--mp-teal); }

.mp-doc-wallet__body {
    padding:     18px 24px;
    display:     flex;
    flex-wrap:   wrap;
    gap:         10px;
}
.mp-doc-chip {
    display:       inline-flex;
    align-items:   center;
    gap:           7px;
    padding:       9px 14px;
    border-radius: var(--mp-radius-md);
    border:        1.5px solid var(--mp-border);
    background:    var(--mp-teal-xlight);
    font-size:     13px;
    font-weight:   500;
    color:         var(--mp-text-dark);
    cursor:        default;
    transition:    box-shadow var(--mp-transition), border-color var(--mp-transition);
}
.mp-doc-chip:hover { box-shadow: var(--mp-shadow-md); border-color: #c0dbd8; }
.mp-doc-chip__icon { font-size: 15px; flex-shrink: 0; }
.mp-doc-chip__status {
    font-size:   11.5px;
    font-weight: 700;
    margin-left: 2px;
}
.mp-doc--verified .mp-doc-chip__status { color: var(--mp-green);  }
.mp-doc--expired  .mp-doc-chip__status { color: var(--mp-red);    }
.mp-doc--pending  .mp-doc-chip__status { color: var(--mp-amber);  }
.mp-doc--missing  .mp-doc-chip__status { color: var(--mp-text-light); }

/* ── Responsive ── */
@media (max-width: 760px) {
    .mp-body                { grid-template-columns: 1fr; }
    .mp-sidebar             { display: none; }
    .mp-info-grid           { grid-template-columns: 1fr; }
    .mp-hero__name          { font-size: 22px; }
}
</style>
@endpush


{{-- ════════════════════════════════════════════════════════════════════
     HTML
════════════════════════════════════════════════════════════════════ --}}
<div class="mp-root">

    {{-- 01 — PAGE HEADER --}}
    <div class="mp-page-header">
        <div class="mp-page-eyebrow">Applicant</div>
        <h1 class="mp-page-title">My Profile</h1>
        <span class="mp-page-breadcrumb">/applicant/profile</span>
    </div>

    {{-- 02 — APP SHELL --}}
    <div class="mp-shell">

        {{-- Top nav --}}
        <div class="mp-topbar">
            <div class="mp-topbar__brand">
                <div class="mp-topbar__logo">🎓</div>
                ScholarLink
            </div>

            <div class="mp-topbar__search">
                <span class="mp-topbar__search-icon">🔍</span>
                <input type="text" placeholder="Search scholarships…" aria-label="Search" />
            </div>

            <div class="mp-topbar__actions">
                @php
                    $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
                        ->where('read', false)
                        ->count();
                @endphp
                <div class="mp-topbar__bell" aria-label="Notifications">
                    🔔
                    @if($unreadNotifications > 0)
                        <span class="mp-topbar__bell-dot"></span>
                    @endif
                </div>
                <div class="mp-topbar__avatar" title="{{ $applicantUser?->name }}">
                    {{ strtoupper(substr($applicantUser?->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(explode(' ', $applicantUser?->name ?? 'A')[1] ?? '', 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="mp-body">

            {{-- 03 — SIDEBAR --}}
            <aside class="mp-sidebar">
                <div>
                    <div class="mp-sidebar__group-label">Main</div>
                    @foreach ($mainNav as $item)
                        <a href="{{ $item['href'] }}"
                           class="mp-nav-item {{ $item['active'] ? 'is-active' : '' }}"
                           aria-current="{{ $item['active'] ? 'page' : 'false' }}">
                            <span class="mp-nav-item__icon">{{ $item['icon'] }}</span>
                            <span class="mp-nav-item__label">{{ $item['label'] }}</span>
                            @if (!empty($item['badge']))
                                <span class="mp-nav-item__badge">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div>
                    <div class="mp-sidebar__group-label">Account</div>
                    @foreach ($accountNav as $item)
                        <a href="{{ $item['href'] }}"
                           class="mp-nav-item {{ $item['active'] ? 'is-active' : '' }}"
                           aria-current="{{ $item['active'] ? 'page' : 'false' }}">
                            <span class="mp-nav-item__icon">{{ $item['icon'] }}</span>
                            <span class="mp-nav-item__label">{{ $item['label'] }}</span>
                            @if (!empty($item['badge']))
                                <span class="mp-nav-item__badge">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="mp-sidebar__user">
                    <div class="mp-sidebar__user-avatar">{{ $user['initials'] }}</div>
                    <div>
                        <div class="mp-sidebar__user-name">{{ $user['name'] }}</div>
                        <div class="mp-sidebar__user-role">{{ $user['role'] }}</div>
                    </div>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="mp-main">

                {{-- 04 — HERO BANNER --}}
                <div class="mp-hero">
                    <div class="mp-hero__avatar">
                        @if (!empty($applicantUser?->avatar_url))
                            <img src="{{ $applicantUser->avatar_url }}" alt="{{ $applicantUser->name }}" />
                        @else
                            {{ strtoupper(substr($applicantUser?->name ?? 'A', 0, 1)) }}{{ strtoupper(substr(explode(' ', $applicantUser?->name ?? 'A')[1] ?? '', 0, 1)) }}
                        @endif
                    </div>

                    <div class="mp-hero__info">
                        <div class="mp-hero__name">{{ $applicantUser?->name ?? 'Applicant' }}</div>
                        <div class="mp-hero__sub">
                            {{ $applicantProfile?->program ?? 'Unknown Program' }} · {{ $applicantProfile?->school ?? 'Unknown School' }}
                        </div>
                        <div class="mp-hero__updated">
                            📅 Last updated {{ $applicantProfile?->updated_at?->format('M d, Y') ?? now()->format('M d, Y') }}
                        </div>
                    </div>

                    <button class="mp-hero__edit-btn"
                            onclick="MyProfile.editSection('hero')">
                        ✏️ Edit Profile
                    </button>
                </div>

                <div class="mp-section">

                    {{-- 05 — PROFILE COMPLETION --}}
                    <div class="mp-completion">
                        @php
                            $profileFields = [
                                $applicantProfile?->date_of_birth,
                                $applicantProfile?->phone_number,
                                $applicantProfile?->address,
                                $applicantProfile?->school,
                                $applicantProfile?->program,
                                $applicantProfile?->year_level,
                                $applicantProfile?->gpa,
                            ];
                            $completedFields = count(array_filter($profileFields, fn($f) => !is_null($f)));
                            $totalFields = count($profileFields);
                            $percent = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
                            $incompleteCount = $totalFields - $completedFields;
                        @endphp
                        <div class="mp-completion__header">
                            <span class="mp-completion__label">Profile Completion</span>
                            <div class="mp-completion__right">
                                <span class="mp-completion__pct">{{ $percent }}%</span>
                                <span class="mp-completion__note">
                                    {{ $incompleteCount }} sections<br>incomplete
                                </span>
                            </div>
                        </div>
                        <div class="mp-completion__bar">
                            <div class="mp-completion__fill"
                                 style="width:{{ $percent }}%">
                            </div>
                        </div>
                    </div>

                    {{-- 06 — PERSONAL INFORMATION --}}
                    <div class="mp-info-card" style="margin-top:16px; --i:1">
                        <div class="mp-info-card__header">
                            <span class="mp-info-card__title">Personal Information</span>
                            <button class="mp-info-card__edit"
                                    onclick="MyProfile.editSection('personal')">
                                ✏️ Edit
                            </button>
                        </div>
                        <div class="mp-info-card__body">
                            <div class="mp-info-grid">
                                {{-- Full name --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">Full Name</div>
                                    <div class="mp-field__value">{{ $applicantUser?->name ?? 'Not provided' }}</div>
                                </div>

                                {{-- Date of birth --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">Date of Birth</div>
                                    <div class="mp-field__value">{{ $applicantProfile?->date_of_birth?->format('F j, Y') ?? 'Not provided' }}</div>
                                </div>

                                {{-- Contact --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">Contact Number</div>
                                    <div class="mp-field__value">{{ $applicantProfile?->phone_number ?? 'Not provided' }}</div>
                                </div>

                                {{-- Email --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">Email Address</div>
                                    <div class="mp-field__value">{{ $applicantUser?->email ?? 'Not provided' }}</div>
                                </div>
                            </div>

                            {{-- Address — full width --}}
                            <div class="mp-info-grid mp-info-grid--wide" style="margin-top:18px;">
                                <div class="mp-field">
                                    <div class="mp-field__label">Home Address</div>
                                    <div class="mp-field__value">{{ $applicantProfile?->address ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 07 — ACADEMIC INFORMATION --}}
                    <div class="mp-info-card" style="--i:2">
                        <div class="mp-info-card__header">
                            <span class="mp-info-card__title">Academic Information</span>
                            <button class="mp-info-card__edit"
                                    onclick="MyProfile.editSection('academic')">
                                ✏️ Edit
                            </button>
                        </div>
                        <div class="mp-info-card__body">
                            <div class="mp-info-grid">
                                {{-- School --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">School / University</div>
                                    <div class="mp-field__value">{{ $applicantProfile?->school ?? 'Not provided' }}</div>
                                </div>

                                {{-- Program --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">Program / Course</div>
                                    <div class="mp-field__value">{{ $applicantProfile?->program ?? 'Not provided' }}</div>
                                </div>

                                {{-- Year level --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">Year Level</div>
                                    <div class="mp-field__value">{{ $applicantProfile?->year_level ?? 'Not provided' }}</div>
                                </div>

                                {{-- GPA --}}
                                <div class="mp-field">
                                    <div class="mp-field__label">GPA / QPI</div>
                                    <div class="mp-field__value mp-field__value--bold">
                                        {{ $applicantProfile?->gpa ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 08 — DOCUMENT WALLET STATUS --}}
                    <div class="mp-doc-wallet">
                        <div class="mp-doc-wallet__header">
                            <span class="mp-doc-wallet__title">Document Wallet Status</span>
                            <a href="{{ route('applicant.documents.index') }}"
                               class="mp-doc-wallet__manage">
                                Manage →
                            </a>
                        </div>
                        <div class="mp-doc-wallet__body">
                            @php
                                $documents = \App\Models\ApplicationDocument::where('user_id', auth()->id())->get();
                            @endphp
                            @forelse($documents as $doc)
                                @php
                                    $ds = $docStatusConfig[$doc->status] ?? ['label' => ucfirst($doc->status), 'class' => ''];
                                    $docIcon = match($doc->status) {
                                        'verified' => '📄',
                                        'expired'  => '📄',
                                        'pending'  => '🕐',
                                        default    => '📋',
                                    };
                                @endphp
                                <div class="mp-doc-chip {{ $ds['class'] }}">
                                    <span class="mp-doc-chip__icon">{{ $docIcon }}</span>
                                    {{ $doc->document_type }}
                                    <span class="mp-doc-chip__status">{{ $ds['label'] }}</span>
                                </div>
                            @empty
                                <p style="color: #999; font-size: 13px;">No documents uploaded yet. <a href="{{ route('applicant.documents.index') }}" style="color: #1a6b63; text-decoration: underline;">Upload now</a></p>
                            @endforelse
                        </div>
                    </div>

                </div>{{-- /mp-section --}}
            </main>
        </div>{{-- /mp-body --}}
    </div>{{-- /mp-shell --}}
</div>{{-- /mp-root --}}


{{-- ════════════════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    'use strict';

    /**
     * MyProfile — interaction handlers.
     *
     * Replace stubs with real route redirects or modal triggers.
     * Example: window.location.href = '/applicant/profile/edit/personal';
     */
    window.MyProfile = {

        /**
         * editSection — open the edit modal or redirect to edit page.
         * @param {'hero'|'personal'|'academic'} section
         */
        editSection(section) {
            console.log('[MyProfile] Edit section →', section);
            // TODO: trigger your modal or navigate, e.g.:
            // window.location.href = `/applicant/profile/edit/${section}`;
            this._toast(`Opening editor for: ${section}`, 'info');
        },

        /**
         * Simple toast notification.
         */
        _toast(message, type = 'info') {
            const colors = { info: 'var(--mp-teal)', warning: '#f07a30', error: '#e05050' };
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
                maxWidth:     '260px',
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
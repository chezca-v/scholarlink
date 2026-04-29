<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Superadmin') — ScholarLink</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page-specific styles -->
    @stack('styles')

    <style>
        /* ── Variables ── */
        :root {
            --primary:      #0F4C5C;
            --primary-mid:  #1A6B7A;
            --primary-light:#C8E8E4;
            --primary-pale: #F0FAFA;
            --accent:       #E8A838;
            --accent-pale:  #FEF6E4;
            --ink:          #0A3040;
            --slate:        #4A7A80;
            --muted:        #9CBBBB;
            --border:       #E2ECEA;
            --page-bg:      #F0F4F3;
            --surface:      #FFFFFF;
            --sidebar-w:    220px;
            --topbar-h:     60px;
            --red:          #DC2626;
            --red-light:    #FEE2E2;
            --green:        #16A34A;
            --green-light:  #DCFCE7;
            --yellow:       #D97706;
            --yellow-bg:    #FFFBEB;
            --amber:        #E8A838;
            --shadow-sm:    0 1px 4px rgba(0,0,0,.06);
            --shadow-md:    0 4px 20px rgba(0,0,0,.09);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family:            'DM Sans', sans-serif;
            background:             var(--page-bg);
            color:                  var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ── SHELL ── */
        .sa-shell {
            display:               grid;
            grid-template-columns: var(--sidebar-w) 1fr;
            grid-template-rows:    var(--topbar-h) 1fr;
            min-height:            100vh;
        }

        /* ── SIDEBAR ── */
        .sa-sidebar {
            grid-row:      1 / -1;
            background:    var(--primary);
            display:       flex;
            flex-direction:column;
            padding:       0;
            position:      sticky;
            top:           0;
            height:        100vh;
            overflow-y:    auto;
        }

        .sa-sidebar__brand {
            display:        flex;
            align-items:    center;
            gap:            10px;
            padding:        20px 18px 16px;
            border-bottom:  1px solid rgba(255,255,255,.1);
            text-decoration:none;
        }
        .sa-sidebar__logo {
            width:           36px;
            height:          36px;
            object-fit:      contain;
            filter:          drop-shadow(0 4px 10px rgba(0,0,0,.2));
            flex-shrink:     0;
        }
        .sa-sidebar__wordmark {
            font-family:  'Fraunces', serif;
            font-weight:  700;
            font-size:    18px;
            color:        #F9D679;
            letter-spacing: -.3px;
        }
        .sa-sidebar__role {
            font-size:    10px;
            color:        rgba(255,255,255,.45);
            letter-spacing: .1em;
            text-transform: uppercase;
            padding:      4px 18px 12px;
        }

        /* Nav groups */
        .sa-nav-group { padding: 8px 10px; }
        .sa-nav-group__label {
            font-size:      10px;
            font-weight:    700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color:          rgba(255,255,255,.3);
            padding:        0 8px;
            margin-bottom:  4px;
        }
        .sa-nav-item {
            display:         flex;
            align-items:     center;
            gap:             8px;
            padding:         9px 12px;
            border-radius:   8px;
            font-size:       13.5px;
            font-weight:     500;
            color:           rgba(255,255,255,.7);
            text-decoration: none;
            transition:      background .15s, color .15s;
            cursor:          pointer;
        }
        .sa-nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sa-nav-item.active { background: rgba(255,255,255,.15); color: #fff; font-weight: 700; }
        .sa-nav-item__icon { font-size: 15px; flex-shrink: 0; }

        /* Sidebar bottom */
        .sa-sidebar__footer {
            margin-top:     auto;
            padding:        12px 10px;
            border-top:     1px solid rgba(255,255,255,.1);
        }
        .sa-sidebar__user {
            display:      flex;
            align-items:  center;
            gap:          10px;
            padding:      10px 12px;
            border-radius:8px;
            cursor:       pointer;
            transition:   background .15s;
        }
        .sa-sidebar__user:hover { background: rgba(255,255,255,.08); }
        .sa-sidebar__avatar {
            width:           34px;
            height:          34px;
            border-radius:   50%;
            background:      rgba(255,255,255,.2);
            color:           #fff;
            font-weight:     700;
            font-size:       13px;
            display:         flex;
            align-items:     center;
            justify-content: center;
            flex-shrink:     0;
        }
        .sa-sidebar__user-name { font-size: 13px; font-weight: 600; color: #fff; }
        .sa-sidebar__user-role { font-size: 11px; color: rgba(255,255,255,.45); }

        /* ── TOPBAR ── */
        .sa-topbar {
            grid-column:     2;
            background:      var(--primary);
            height:          var(--topbar-h);
            display:         flex;
            align-items:     center;
            justify-content: space-between;
            padding:         0 28px;
            position:        sticky;
            top:             0;
            z-index:         10;
            border-bottom:   1px solid rgba(255,255,255,.08);
        }
        .sa-topbar__left {}
        .sa-topbar__title {
            font-family:  'Fraunces', serif;
            font-size:    18px;
            font-weight:  700;
            color:        #fff;
        }
        .sa-topbar__subtitle {
            font-size:  11px;
            color:      rgba(255,255,255,.45);
            font-family:monospace;
        }
        .sa-topbar__actions { display: flex; align-items: center; gap: 10px; }
        .sa-topbar__bell {
            position:        relative;
            width:           34px;
            height:          34px;
            border-radius:   50%;
            background:      rgba(255,255,255,.1);
            display:         flex;
            align-items:     center;
            justify-content: center;
            cursor:          pointer;
            font-size:       16px;
            transition:      background .15s;
            text-decoration: none;
        }
        .sa-topbar__bell:hover { background: rgba(255,255,255,.18); }
        .sa-topbar__bell-dot {
            position:      absolute;
            top:           5px;
            right:         5px;
            width:         8px;
            height:        8px;
            border-radius: 50%;
            background:    #F9D679;
            border:        2px solid var(--primary);
        }
        .sa-topbar__avatar {
            width:           34px;
            height:          34px;
            border-radius:   50%;
            background:      rgba(255,255,255,.2);
            color:           #F9D679;
            font-weight:     700;
            font-size:       13px;
            font-family:     'Fraunces', serif;
            display:         flex;
            align-items:     center;
            justify-content: center;
            cursor:          pointer;
            transition:      transform .15s;
            text-decoration: none;
        }
        .sa-topbar__avatar:hover { transform: scale(1.07); }

        /* ── MAIN CONTENT ── */
        .sa-main {
            grid-column: 2;
            padding:     28px;
            overflow-x:  hidden;
        }

        /* ── SHARED COMPONENTS ── */

        /* Breadcrumb */
        .breadcrumb {
            display:     flex;
            align-items: center;
            gap:         6px;
            font-size:   12px;
            color:       var(--slate);
            margin-bottom: 20px;
        }
        .breadcrumb .sep { color: var(--muted); }
        .breadcrumb .current { color: var(--ink); font-weight: 600; }

        /* Stat grid */
        .stat-grid {
            display:               grid;
            grid-template-columns: repeat(4, 1fr);
            gap:                   16px;
            margin-bottom:         24px;
        }
        .stat-card {
            background:    var(--surface);
            border-radius: 14px;
            padding:       20px;
            border:        1px solid var(--border);
            box-shadow:    var(--shadow-sm);
        }
        .stat-card .label { font-size: 12px; color: var(--slate); font-weight: 600; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
        .stat-card .value { font-family: 'Fraunces', serif; font-size: 32px; font-weight: 700; color: var(--ink); line-height: 1; margin-bottom: 6px; }
        .stat-card .delta { font-size: 12px; font-weight: 600; }
        .stat-card .delta.up { color: var(--green); }
        .stat-card .delta.down { color: var(--red); }
        .stat-card .delta.neutral { color: var(--slate); }
        .stat-card .icon-wrap { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }

        /* Cards */
        .card {
            background:    var(--surface);
            border-radius: 14px;
            padding:       20px;
            border:        1px solid var(--border);
            box-shadow:    var(--shadow-sm);
            margin-bottom: 16px;
        }
        .card.compact { padding: 16px; }

        /* Grids */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

        /* Section headers */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .section-title { font-size: 14px; font-weight: 700; color: var(--ink); }
        .section-title small { font-size: 11px; color: var(--slate); font-weight: 400; margin-left: 6px; }

        /* Badges */
        .badge {
            display:       inline-flex;
            align-items:   center;
            padding:       3px 10px;
            border-radius: 999px;
            font-size:     11px;
            font-weight:   700;
            white-space:   nowrap;
        }
        .badge.teal   { background: var(--primary-light); color: var(--primary); }
        .badge.green  { background: var(--green-light);   color: var(--green); }
        .badge.red    { background: var(--red-light);     color: var(--red); }
        .badge.yellow { background: var(--yellow-bg);     color: var(--yellow); }
        .badge.amber  { background: #FEF6E4;              color: var(--amber); }
        .badge.gray   { background: #F3F4F6;              color: #6B7280; }

        /* Buttons */
        .btn {
            display:        inline-flex;
            align-items:    center;
            gap:            6px;
            padding:        9px 18px;
            border-radius:  9px;
            font-family:    'DM Sans', sans-serif;
            font-size:      13px;
            font-weight:    600;
            cursor:         pointer;
            border:         none;
            text-decoration:none;
            transition:     opacity .15s, background .15s;
            white-space:    nowrap;
        }
        .btn:hover { opacity: .88; }
        .btn.btn-primary { background: linear-gradient(104deg, var(--primary) 0%, var(--primary-mid) 100%); color: #F9D679; }
        .btn.btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--ink); }
        .btn.btn-outline:hover { background: var(--primary-pale); }
        .btn.btn-ghost { background: transparent; color: var(--primary); padding: 6px 10px; }
        .btn.btn-ghost:hover { background: var(--primary-pale); }
        .btn.btn-danger { background: var(--red-light); color: var(--red); border: 1px solid #FECACA; }
        .btn.btn-amber { background: #FEF6E4; color: var(--amber); border: 1px solid #F0CC80; }
        .btn.btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 7px; }

        /* Forms */
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--slate); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
        .form-label .req { color: var(--red); }
        .form-input {
            width: 100%; padding: 9px 12px; border-radius: 8px;
            border: 1.5px solid var(--border); background: var(--primary-pale);
            font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--ink);
            outline: none; transition: border-color .15s, box-shadow .15s;
        }
        .form-input:focus { border-color: var(--primary-mid); box-shadow: 0 0 0 3px rgba(26,107,122,.1); }
        .form-select {
            width: 100%; padding: 9px 12px; border-radius: 8px;
            border: 1.5px solid var(--border); background: var(--primary-pale);
            font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--ink);
            outline: none; cursor: pointer;
        }
        .form-textarea {
            width: 100%; padding: 9px 12px; border-radius: 8px;
            border: 1.5px solid var(--border); background: var(--primary-pale);
            font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--ink);
            outline: none; resize: vertical; min-height: 80px;
        }
        .search-wrap { position: relative; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 14px; }

        /* Tables */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { border-bottom: 1.5px solid var(--border); }
        th { padding: 10px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--slate); text-align: left; white-space: nowrap; }
        td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--primary-pale); }

        /* Pagination */
        .pagination { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-top: 1.5px solid var(--border); }
        .pagination .info { font-size: 12px; color: var(--slate); }
        .page-btns { display: flex; gap: 4px; }
        .page-btn { width: 30px; height: 30px; border-radius: 7px; border: 1.5px solid var(--border); background: var(--surface); font-size: 12px; font-weight: 600; color: var(--ink); cursor: pointer; transition: background .15s; }
        .page-btn:hover { background: var(--primary-pale); }
        .page-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* Modals */
        .modal-overlay {
            display:         none;
            position:        fixed;
            inset:           0;
            background:      rgba(0,0,0,.45);
            z-index:         1000;
            align-items:     center;
            justify-content: center;
            padding:         20px;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background:    var(--surface);
            border-radius: 16px;
            padding:       28px;
            width:         100%;
            max-width:     480px;
            box-shadow:    0 20px 60px rgba(0,0,0,.2);
            max-height:    90vh;
            overflow-y:    auto;
        }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .modal-title { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: var(--ink); }
        .modal-close { background: none; border: none; font-size: 18px; cursor: pointer; color: var(--slate); padding: 4px 8px; border-radius: 6px; transition: background .15s; }
        .modal-close:hover { background: var(--primary-pale); }
        .modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border); }

        /* Org rows / bar charts */
        .org-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .org-row .name { font-size: 12px; font-weight: 600; color: var(--ink); width: 140px; flex-shrink: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bar-track { flex: 1; height: 8px; background: var(--border); border-radius: 999px; overflow: hidden; }
        .bar-track .fill { height: 100%; border-radius: 999px; transition: width .5s; }
        .org-row .count { font-size: 12px; font-weight: 700; color: var(--slate); width: 36px; text-align: right; }

        /* Alert items */
        .alert-item { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .alert-item:last-child { border-bottom: none; }
        .alert-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
        .alert-dot.red { background: var(--red); }
        .alert-dot.yellow { background: var(--yellow); }
        .alert-dot.green { background: var(--green); }

        /* Health grid */
        .health-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .health-item { background: var(--primary-pale); border-radius: 10px; padding: 12px; border: 1px solid var(--border); }
        .h-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--slate); margin-bottom: 4px; }
        .h-value { font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 2px; }
        .h-status { font-size: 11px; font-weight: 700; }
        .h-status.ok { color: var(--green); }
        .h-status.warn { color: var(--yellow); }
        .h-status.error { color: var(--red); }

        /* Chart area */
        .chart-area { display: flex; align-items: flex-end; gap: 8px; height: 120px; padding-top: 12px; }
        .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end; }
        .bar-fill { width: 100%; border-radius: 6px 6px 0 0; background: var(--primary-light); transition: height .5s; min-height: 4px; }
        .bar-fill.accent { background: linear-gradient(180deg, #F9D679 0%, var(--amber) 100%); }
        .bar-label { font-size: 10px; color: var(--slate); font-weight: 600; }

        /* Log entries */
        .log-entry { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); }
        .log-entry:last-child { border-bottom: none; }
        .log-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .log-action { font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 3px; }
        .log-meta { font-size: 11px; color: var(--slate); display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .log-time { font-size: 12px; color: var(--slate); text-align: right; flex-shrink: 0; white-space: nowrap; }

        /* Org cards */
        .org-card { background: var(--surface); border-radius: 14px; padding: 16px; border: 1px solid var(--border); display: flex; flex-direction: column; gap: 12px; }
        .org-avatar { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .org-meta { font-size: 11px; color: var(--slate); }
        .prog-bar-wrap { height: 6px; background: var(--border); border-radius: 999px; overflow: hidden; }
        .prog-bar { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%); }

        /* Toggle */
        .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); }
        .toggle-row:last-child { border-bottom: none; }
        .toggle-info { flex: 1; padding-right: 16px; }
        .toggle-label { font-size: 13.5px; font-weight: 600; color: var(--ink); margin-bottom: 2px; }
        .toggle-desc { font-size: 12px; color: var(--slate); }
        .toggle { position: relative; display: inline-block; width: 42px; height: 24px; flex-shrink: 0; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track { position: absolute; inset: 0; background: var(--border); border-radius: 999px; cursor: pointer; transition: background .2s; }
        .toggle-track::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: transform .2s; }
        .toggle input:checked + .toggle-track { background: var(--primary-mid); }
        .toggle input:checked + .toggle-track::before { transform: translateX(18px); }

        /* Permissions matrix */
        .perm-row { padding: 10px 0; border-bottom: 1px solid var(--border); }
        .perm-row:last-child { border-bottom: none; }
        .perm-role-label { font-size: 12px; font-weight: 700; color: var(--slate); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
        .perm-actions { display: flex; flex-wrap: wrap; gap: 6px; }
        .perm-action-tag { padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; cursor: pointer; user-select: none; transition: background .15s, color .15s; }
        .perm-action-tag.on { background: var(--primary-light); color: var(--primary); }
        .perm-action-tag.off { background: #F3F4F6; color: #9CA3AF; }
    </style>
</head>
<body>

<div class="sa-shell">

    {{-- ══════════ SIDEBAR ══════════ --}}
    <aside class="sa-sidebar">

        {{-- Brand --}}
        <a href="{{ route('superadmin.dashboard') }}" class="sa-sidebar__brand">
            <img src="{{ asset('logo-light.png.png') }}" alt="ScholarLink logo" class="sa-sidebar__logo">
            <span class="sa-sidebar__wordmark">ScholarLink</span>
        </a>
        <div class="sa-sidebar__role">Superadmin Panel</div>

        {{-- Main Nav --}}
        <nav class="sa-nav-group">
            <div class="sa-nav-group__label">Overview</div>
            <a href="{{ route('superadmin.dashboard') }}"
               class="sa-nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <span class="sa-nav-item__icon">📊</span> Dashboard
            </a>
            <a href="{{ route('superadmin.logs') }}"
               class="sa-nav-item {{ request()->routeIs('superadmin.logs*') ? 'active' : '' }}">
                <span class="sa-nav-item__icon">📋</span> System Logs
            </a>
        </nav>

        <nav class="sa-nav-group">
            <div class="sa-nav-group__label">Management</div>
            <a href="{{ route('superadmin.organizations') }}"
               class="sa-nav-item {{ request()->routeIs('superadmin.organizations*') ? 'active' : '' }}">
                <span class="sa-nav-item__icon">🏛️</span> Scholarships
            </a>
            <a href="{{ route('superadmin.admins') }}"
               class="sa-nav-item {{ request()->routeIs('superadmin.admins*') ? 'active' : '' }}">
                <span class="sa-nav-item__icon">👤</span> Admin Accounts
            </a>
        </nav>

        <nav class="sa-nav-group">
            <div class="sa-nav-group__label">System</div>
            <a href="{{ route('superadmin.settings') }}"
               class="sa-nav-item {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}">
                <span class="sa-nav-item__icon">⚙️</span> Settings
            </a>
        </nav>

        {{-- User footer --}}
        <div class="sa-sidebar__footer">
            <div class="sa-sidebar__user">
                <div class="sa-sidebar__avatar">
                    {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
                </div>
                <div>
                    <div class="sa-sidebar__user-name">
                        {{ auth()->user()->first_name ?? auth()->user()->name }} {{ auth()->user()->last_name ?? '' }}
                    </div>
                    <div class="sa-sidebar__user-role">Superadmin</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 4px;">
                @csrf
                <button type="submit" class="sa-nav-item" style="width: 100%; background: none; border: none; font-family: inherit; cursor: pointer; color: rgba(255,255,255,.5);">
                    <span class="sa-nav-item__icon">🚪</span> Log Out
                </button>
            </form>
        </div>

    </aside>

    {{-- ══════════ TOPBAR ══════════ --}}
    <header class="sa-topbar">
        <div class="sa-topbar__left">
            <div class="sa-topbar__title">@yield('topnav_title', 'Dashboard')</div>
        </div>
        <div class="sa-topbar__actions">
            {{-- Page-specific topbar actions --}}
            @yield('topnav_actions')

            {{-- Notifications --}}
            <a href="{{ route('superadmin.notifications') }}" class="sa-topbar__bell" title="Notifications">
                🔔
                @php $unreadNotifs = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                @if($unreadNotifs > 0)
                    <span class="sa-topbar__bell-dot"></span>
                @endif
            </a>

            {{-- Avatar --}}
            <a href="{{ route('superadmin.settings') }}" class="sa-topbar__avatar" title="Settings">
                {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}
            </a>
        </div>
    </header>

    {{-- ══════════ MAIN ══════════ --}}
    <main class="sa-main">
        @yield('content')
    </main>

</div>

{{-- Modals (page-specific) --}}
@yield('modals')

{{-- Page-specific scripts --}}
@stack('scripts')

</body>
</html>

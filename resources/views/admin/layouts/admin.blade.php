<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — ScholarLink</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary: #0F4C5C;
            --primary-hover: #1A6B7A;
            --primary-light: #2A8FA0;
            --accent: #E8A838;
            --accent-light: #F9D679;
            --accent-pale: #FDF4E3;
            --page-bg: #F0FAFA;
            --card-bg: #FFFFFF;
            --border: #DFF0EE;
            --border-mid: #C8E8E4;
            --ink: #0A3040;
            --slate: #4A7A80;
            --muted: #7AACAA;
        }
        body { font-family: 'DM Sans', sans-serif; background-color: var(--page-bg); color: var(--ink); }
        .font-display { font-family: 'Fraunces', serif; }
        .sidebar { width: 220px; min-height: 100vh; background: var(--primary); position: fixed; left: 0; top: 0; z-index: 40; }
        .main-content { margin-left: 220px; min-height: 100vh; }
        .admin-nav { background: var(--primary); }
        .nav-link { color: rgba(255,255,255,0.7); transition: all 0.2s; padding: 10px 16px; border-radius: 10px; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.12); }
        .nav-link.active { color: var(--accent-light); }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        .section-label { font-size: 10px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255,255,255,0.35); padding: 0 16px; margin: 20px 0 6px; }
        .badge-count { background: var(--accent); color: var(--primary); font-size: 10px; font-weight: 700; padding: 1px 7px; border-radius: 99px; margin-left: auto; }
        .card { background: var(--card-bg); border-radius: 20px; border: 1px solid var(--border); box-shadow: 0 2px 12px rgba(15,76,92,0.06); }
        .card-compact { border-radius: 16px; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(15,76,92,0.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,76,92,0.35); }
        .btn-amber { background: linear-gradient(135deg, var(--accent), #F0B94A); color: var(--primary); padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(232,168,56,0.3); }
        .btn-amber:hover { transform: translateY(-1px); }
        .btn-secondary { border: 1.5px solid var(--primary); color: var(--primary); padding: 9px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.2s; background: transparent; }
        .btn-secondary:hover { background: rgba(15,76,92,0.06); }
        .btn-ghost { border: 1.5px solid var(--border-mid); color: var(--slate); padding: 9px 16px; border-radius: 10px; font-weight: 500; font-size: 14px; transition: all 0.2s; background: white; }
        .btn-ghost:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger { background: #FEE2E2; color: #DC2626; padding: 9px 16px; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.2s; }
        .btn-danger:hover { background: #FECACA; }
        .status-badge { padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; }
        .status-active { background: #DCFCE7; color: #16A34A; }
        .status-draft { background: #F1F5F9; color: #64748B; }
        .status-closed { background: #FEE2E2; color: #DC2626; }
        .status-pending { background: var(--accent-pale); color: #B45309; }
        .status-review { background: #EFF6FF; color: #2563EB; }
        .status-approved { background: #DCFCE7; color: #16A34A; }
        .status-rejected { background: #FEE2E2; color: #DC2626; }
        .input-field { width: 100%; border: 1.5px solid var(--border-mid); border-radius: 10px; padding: 10px 14px; font-size: 14px; font-family: 'DM Sans', sans-serif; color: var(--ink); background: white; transition: all 0.2s; outline: none; }
        .input-field:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15,76,92,0.08); }
        .input-label { font-size: 13px; font-weight: 600; color: var(--ink); margin-bottom: 6px; display: block; }
        .input-hint { font-size: 12px; color: var(--slate); margin-top: 5px; }
        .table-header { background: #F8FCFC; border-bottom: 1px solid var(--border); }
        .table-row { border-bottom: 1px solid var(--border); transition: background 0.15s; }
        .table-row:hover { background: #F8FCFC; }
        .table-row:last-child { border-bottom: none; }
        .page-header { padding: 28px 32px 0; }
        .page-content { padding: 24px 32px; }
        .stat-card { background: white; border-radius: 16px; border: 1px solid var(--border); padding: 20px; transition: all 0.2s; }
        .stat-card:hover { border-color: var(--border-mid); box-shadow: 0 4px 16px rgba(15,76,92,0.08); }
        select.input-field { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234A7A80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
        .toggle { position: relative; display: inline-flex; align-items: center; cursor: pointer; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track { width: 44px; height: 24px; background: #CBD5E1; border-radius: 99px; transition: background 0.2s; }
        .toggle input:checked ~ .toggle-track { background: var(--primary); }
        .toggle-thumb { position: absolute; left: 3px; width: 18px; height: 18px; background: white; border-radius: 99px; transition: transform 0.2s; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
        .toggle input:checked ~ .toggle-thumb { transform: translateX(20px); }
        .tag-chip { display: inline-flex; align-items: center; gap: 5px; background: rgba(15,76,92,0.08); color: var(--primary); padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 500; }
        .tag-chip button { color: var(--slate); line-height: 1; }
        .tab-btn { padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; transition: all 0.2s; color: var(--slate); }
        .tab-btn.active { background: var(--primary); color: white; }
        .tab-btn:hover:not(.active) { background: rgba(15,76,92,0.06); color: var(--primary); }
        .progress-bar-track { background: var(--border); border-radius: 99px; height: 6px; overflow: hidden; }
        .progress-bar-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--primary-light)); border-radius: 99px; }
        .funnel-bar { background: linear-gradient(90deg, var(--primary), var(--primary-light)); border-radius: 8px; height: 32px; transition: width 0.6s ease; display: flex; align-items: center; padding: 0 12px; color: white; font-size: 12px; font-weight: 600; }
        .chart-bar { background: linear-gradient(180deg, var(--primary-light), var(--primary)); border-radius: 6px 6px 0 0; transition: height 0.5s ease; }
        .chart-bar-amber { background: linear-gradient(180deg, var(--accent-light), var(--accent)); border-radius: 6px 6px 0 0; }
        .dropdown-menu { position: absolute; right: 0; top: calc(100% + 6px); background: white; border: 1px solid var(--border); border-radius: 14px; box-shadow: 0 8px 30px rgba(15,76,92,0.12); z-index: 50; min-width: 160px; overflow: hidden; }
        .dropdown-item { display: flex; align-items: center; gap: 8px; padding: 10px 14px; font-size: 13px; color: var(--ink); transition: background 0.15s; }
        .dropdown-item:hover { background: #F8FCFC; }
        .dropdown-item.danger { color: #DC2626; }
        .dropdown-item.danger:hover { background: #FEF2F2; }
        .toast { position: fixed; bottom: 24px; right: 24px; z-index: 100; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 14px; background: white; box-shadow: 0 8px 32px rgba(15,76,92,0.18); border: 1px solid var(--border); min-width: 280px; font-size: 14px; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--slate); }
        .empty-state svg { width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.4; }
        .pagination-btn { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; border: 1.5px solid var(--border-mid); color: var(--slate); transition: all 0.2s; }
        .pagination-btn:hover { border-color: var(--primary); color: var(--primary); }
        .pagination-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .skeleton { background: linear-gradient(90deg, #f0f4f4 25%, #e8f0f0 50%, #f0f4f4 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 6px; }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar flex flex-col">
    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
            <div style="width:32px;height:32px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0F4C5C" stroke-width="2.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <span class="font-display font-bold text-white text-lg leading-tight">Scholar<span style="color:var(--accent-light)">Link</span></span>
        </a>
    </div>

    {{-- Role badge --}}
    <div class="px-5 py-3 border-b border-white/10">
        <span style="background:rgba(232,168,56,0.2);color:var(--accent-light);font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;letter-spacing:0.08em;">ADMIN</span>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-1">
        <p class="section-label">Overview</p>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Analytics
        </a>

        <p class="section-label mt-4">Scholarships</p>
        <a href="{{ route('admin.scholarships.index') }}" class="nav-link {{ request()->routeIs('admin.scholarships.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            Scholarships
            <span class="badge-count">{{ $scholarshipCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.scholarships.create') }}" class="nav-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Create New
        </a>

        <p class="section-label mt-4">Management</p>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Users
        </a>
        <a href="{{ route('admin.calendar') }}" class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Calendar
        </a>
        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M12 2v2M12 20v2M2 12h2M20 12h2M19.07 19.07l-1.41-1.41M4.93 19.07l1.41-1.41"/></svg>
            Settings
        </a>
    </nav>

    {{-- User footer --}}
    <div class="px-4 py-4 border-t border-white/10">
        <div class="flex items-center gap-3">
            <div style="width:34px;height:34px;background:var(--accent);border-radius:99px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--primary);flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p style="font-size:13px;font-weight:600;color:white;" class="truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.5);" class="truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full nav-link justify-center text-xs">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- MAIN CONTENT --}}
<div class="main-content">
    {{-- Top bar --}}
    <header class="admin-nav sticky top-0 z-30 flex items-center gap-4 px-8 h-14">
        <div class="flex-1">
            <div class="relative" style="max-width:380px;">
                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:rgba(255,255,255,0.5);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" placeholder="Search scholarships, users..." style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:10px;padding:8px 12px 8px 36px;color:white;font-size:13px;width:100%;outline:none;font-family:'DM Sans',sans-serif;" placeholder-style="color:rgba(255,255,255,0.4)">
            </div>
        </div>
        <div class="flex items-center gap-3">
            {{-- Notification bell --}}
            <button style="width:36px;height:36px;background:rgba(255,255,255,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;position:relative;">
                <svg style="width:18px;height:18px;color:white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--accent);border-radius:99px;border:2px solid var(--primary);"></span>
            </button>
            {{-- Role badge --}}
            <span style="background:rgba(232,168,56,0.2);color:var(--accent-light);font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;">ADMIN</span>
            {{-- Top Navbar Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="ml-2 m-0">
                @csrf
                <button type="submit" style="background: rgba(220,38,38,0.1); color: #FCA5A5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='rgba(220,38,38,0.2)'; this.style.color='#FECACA';" onmouseout="this.style.background='rgba(220,38,38,0.1)'; this.style.color='#FCA5A5';">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main>
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="toast">
            <div style="width:32px;height:32px;background:#DCFCE7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:16px;height:16px;color:#16A34A;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <p style="font-size:14px;font-weight:500;">{{ session('success') }}</p>
            <button @click="show = false" style="margin-left:auto;color:var(--slate);">
                <svg style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
    @include('components.toast-notification')
    @include('components.modals.session-timeout')
</body>
</html>
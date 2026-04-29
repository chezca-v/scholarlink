@props(['initials', 'unreadNotifications' => 0])

<header class="header-bar">
    <div class="breadcrumb">ScholarLink <span style="opacity:0.3">›</span> <b style="color:white">Dashboard</b></div>
    <nav class="tab-group">
        <a href="{{ route('admin.dashboard') }}" class="tab-link active">Overview</a>
        <a href="{{ route('admin.applications') }}" class="tab-link">Applications</a>
        <a href="{{ route('admin.scholarships.index') }}" class="tab-link">Scholarships</a>
        <a href="{{ route('admin.reviews') }}" class="tab-link">Evaluators</a>
        <a href="{{ route('admin.analytics') }}" class="tab-link">Reports</a>
    </nav>
    <div class="topbar-right">
        <div class="admin-pill">Admin</div>
        <x-notification-dropdown />
        <div class="top-avatar">{{ $initials }}</div>
        {{-- Logout Button --}}
        <div style="margin:0;">
            <button type="button" x-data @click.prevent="$dispatch('open-modal', 'confirm-logout')" style="background: rgba(217, 72, 72, 0.15); color: #FCA5A5; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px; border: 1px solid rgba(217, 72, 72, 0.3); transition: all 0.2s; cursor: pointer;" onmouseover="this.style.background='rgba(217, 72, 72, 0.25)';" onmouseout="this.style.background='rgba(217, 72, 72, 0.15)';">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </div>
    </div>
</header>
<x-logout-modal />

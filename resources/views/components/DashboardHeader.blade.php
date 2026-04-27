@props(['initials', 'unreadNotifications' => 0])

<header class="header-bar">
    <div class="breadcrumb">ScholarLink <span style="opacity:0.3">›</span> <b style="color:white">Dashboard</b></div>
    <nav class="tab-group">
        <a href="{{ route('admin.dashboard') ?? '#' }}" class="tab-link active">Overview</a>
        <a href="{{ route('admin.applications.index') ?? '#' }}" class="tab-link">Applications</a>
        <a href="{{ route('admin.scholarships.index') ?? '#' }}" class="tab-link">Scholarships</a>
        <a href="{{ route('admin.evaluators.index') ?? '#' }}" class="tab-link">Evaluators</a>
        <a href="{{ route('admin.reports.index') ?? '#' }}" class="tab-link">Reports</a>
    </nav>
    <div class="topbar-right">
        <div class="admin-pill">Admin</div>
        <button class="notif-btn" type="button" aria-label="Notifications">
            <span style="font-size:14px;">🔔</span>
            @if($unreadNotifications > 0)
                <span class="notif-dot"></span>
            @endif
        </button>
        <div class="top-avatar">{{ $initials }}</div>
    </div>
</header>

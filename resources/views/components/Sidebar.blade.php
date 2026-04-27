@props(['user', 'adminName', 'initials', 'currentRoute' => 'admin.dashboard', 'organization'])

<aside class="sidebar">
    <div class="side-brand">
        <div class="logo-icon">🎓</div>
        <div>
            <h3 style="font-family:'Fraunces'; font-size: 16px;">Scholar<span style="color:var(--warm-amber)">Link</span></h3>
            <p style="font-size:9px; color:rgba(255,255,255,0.3); text-transform:uppercase;">{{ $organization?->name ?? 'Admin Panel' }}</p>
        </div>
    </div>

    <nav class="side-nav">
        <div class="nav-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}"><span>📊</span> Dashboard</a>
        <a href="{{ route('admin.analytics') ?? '#' }}" class="nav-item"><span>📈</span> Analytics</a>

        <div class="nav-label">Scholarships</div>
        <a href="{{ route('admin.scholarships.index') ?? '#' }}" class="nav-item"><span>📋</span> Scholarship List</a>
        <a href="{{ route('admin.scholarships.create') ?? '#' }}" class="nav-item"><span>➕</span> Create Scholarship</a>
        <a href="{{ route('admin.calendar') ?? '#' }}" class="nav-item"><span>📅</span> Deadline Calendar</a>

        <div class="nav-label">Applications</div>
        @php
            $allApplicationsCount = \App\Models\Application::count();
            $pendingReviewsCount = \App\Models\Application::where('status', 'pending')->count();
        @endphp
        <a href="{{ route('admin.applications.index') ?? '#' }}" class="nav-item"><span>📂</span> All Applications <span class="badge-nav" style="background:var(--warm-amber); color:var(--deep-teal)">{{ $allApplicationsCount }}</span></a>
        <a href="{{ route('admin.applications.pending') ?? '#' }}" class="nav-item"><span>⚠️</span> Pending Reviews <span class="badge-nav" style="background:var(--red-alert); color:white">{{ $pendingReviewsCount }}</span></a>

        <div class="nav-label">Management</div>
        <a href="{{ route('admin.users.index') ?? '#' }}" class="nav-item">
            <span>👥</span> User Management
        </a>
        <a href="{{ route('admin.settings') ?? '#' }}" class="nav-item">
            <span>⚙️</span> Settings
        </a>
    </nav>

    <div class="side-user">
        <div class="user-av">{{ $initials }}</div>
        <div style="font-size:12px;">
            <p style="font-weight:700;">{{ $adminName }}</p>
            <div style="font-size:9.5px; background:rgba(234,140,85,0.2); color:var(--amber-light); padding:2px 6px; border-radius:20px; display:inline-block; margin-top:4px; font-weight:700;">ADMIN</div>
        </div>
    </div>
</aside>

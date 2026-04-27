<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholarLink - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --deep-teal: #0F4C5C;
            --teal-mid: #1A6B7A;
            --teal-light: #2A8FA0;
            --warm-amber: #E8A838;
            --amber-light: #F9D679;
            --bg-grey: #EEF8F8;
            --border-light: #DFF0EE;
            --sidebar-bg: #091E2A;
            --white: #FFFFFF;
            --muted: #6B8E94;

            --red-alert: #D94848;
            --green-success: #1A9E6A;
            --violet-review: #7C5CBF;
            --orange-warning: #B07B10;

            --sidebar-width: 230px;
            --topbar-height: 56px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-grey);
            color: var(--deep-teal);
            overflow-x: hidden;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: white;
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 100;
            border-right: 1px solid rgba(255, 255, 255, 0.07);
        }

        .side-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--teal-mid), var(--warm-amber));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .side-nav {
            flex: 1;
            padding: 10px 12px;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.3);
            padding: 15px 15px 8px;
            font-weight: 700;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            font-size: 13.5px;
            transition: 0.2s;
            cursor: pointer;
        }

        .nav-item:hover { background: rgba(255, 255, 255, 0.05); }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-weight: 600;
            box-shadow: inset 3px 0 0 var(--warm-amber);
        }

        .badge-nav {
            margin-left: auto;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .side-user {
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-av {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--warm-amber), var(--amber-light));
            color: var(--deep-teal);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-width: 0;
        }

        .header-bar {
            height: var(--topbar-height);
            background: var(--deep-teal);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: sticky;
            top: 0;
            z-index: 90;
            color: white;
            gap: 12px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-pill {
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(232, 168, 56, 0.25);
            color: var(--amber-light);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            border: 1px solid rgba(232, 168, 56, 0.3);
            line-height: 1.2;
        }

        .notif-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.06);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.85);
            position: relative;
            cursor: pointer;
        }

        .notif-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--warm-amber);
            border: 2px solid var(--deep-teal);
            position: absolute;
            top: 4px;
            right: 4px;
        }

        .top-avatar {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--warm-amber), var(--amber-light));
            color: var(--deep-teal);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fraunces', serif;
            font-size: 12px;
            font-weight: 700;
        }

        .breadcrumb {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
        }

        .tab-group {
            display: flex;
            gap: 4px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .tab-link {
            padding: 6px 14px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            border-radius: 20px;
            transition: 0.2s;
            white-space: nowrap;
        }

        .tab-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
        }

        .dashboard-body {
            padding: 32px 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 26px;
            flex-wrap: wrap;
        }

        .heading-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 21px;
            min-height: 176px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(15,76,92,0.05);
        }

        .stat-card h1 {
            font-family: 'Fraunces', serif;
            font-size: 36px;
            font-weight: 900;
            line-height: 1;
            margin: 10px 0;
        }

        .stat-card-foot {
            font-size: 11px;
            color: var(--muted);
            border-top: 1px solid var(--border-light);
            padding-top: 8px;
            margin-top: 10px;
        }

        .stat-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: var(--bg-grey);
            color: var(--green-success);
        }

        .dashboard-main-area {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 20px;
            align-items: start;
        }

        .box-container,
        .activity-section,
        .alerts-section {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-light);
            padding: 20px;
            margin-bottom: 20px;
        }

        .box-header,
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            gap: 10px;
        }

        .box-title,
        .section-header h3 {
            font-family: 'Fraunces', serif;
            font-size: 16px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 12px;
            color: var(--teal-mid);
            font-weight: 700;
            white-space: nowrap;
        }

        .alert-row {
            display: flex;
            gap: 12px;
            padding: 14px;
            border-radius: 12px;
            margin-top: 10px;
            border: 1px solid transparent;
        }

        .alert-red { background: #FEF2F2; border-color: #FECACA; color: var(--red-alert); }
        .alert-orange { background: #FFFBEB; border-color: #FDE68A; color: var(--orange-warning); }
        .alert-blue { background: #F0F9FF; border-color: #BAE6FD; color: #1A5A7D; }

        .activity-list,
        .activity-feed {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .activity-item,
        .activity-node {
            display: flex;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .activity-item:last-child,
        .activity-node:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .activity-content { min-width: 0; flex: 1; }
        .activity-text { font-size: 13px; line-height: 1.45; }

        .activity-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 4px;
            font-size: 11px;
            color: var(--muted);
        }

        .activity-time { margin-left: auto; }

        .activity-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 999px;
            background: var(--bg-grey);
            color: var(--teal-mid);
        }

        table { width: 100%; border-collapse: collapse; }

        th {
            text-align: left;
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            padding: 12px;
            border-bottom: 2px solid var(--border-light);
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid var(--border-light);
            font-size: 13.5px;
        }

        tbody tr:last-child td { border-bottom: none; }

        .prog-bar-container {
            width: 80px;
            height: 5px;
            background: var(--bg-grey);
            border-radius: 3px;
            overflow: hidden;
        }

        .prog-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--deep-teal), var(--teal-mid));
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .action-card {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .action-card:hover {
            border-color: var(--teal-mid);
            background: var(--bg-grey);
        }

        .breakdown-item,
        .deadline-item {
            display: grid;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .breakdown-item {
            grid-template-columns: 8px 1fr auto auto;
        }

        .deadline-item {
            grid-template-columns: 8px 1fr auto;
        }

        .breakdown-item:last-child,
        .deadline-item:last-child {
            border-bottom: none;
        }

        .breakdown-color,
        .deadline-color {
            width: 8px;
            height: 8px;
            border-radius: 99px;
        }

        .breakdown-label,
        .deadline-name { font-size: 12px; font-weight: 600; }

        .breakdown-number { font-weight: 700; font-size: 13px; }
        .breakdown-percent { font-size: 11px; color: var(--muted); }

        .stacked-bar {
            display: flex;
            height: 8px;
            border-radius: 20px;
            overflow: hidden;
            margin: 15px 0 8px;
            background: var(--bg-grey);
        }

        .stacked-bar-segment { height: 100%; }

        .deadline-date { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .deadline-days {
            font-size: 11px;
            font-weight: 700;
            min-width: 34px;
            text-align: right;
        }

        .deadline-days.urgent { color: var(--red-alert); }
        .deadline-days.warning { color: var(--orange-warning); }
        .deadline-days.safe { color: var(--green-success); }
        .deadline-days.info { color: var(--teal-mid); }

        .btn-pri {
            background: linear-gradient(135deg, var(--deep-teal), var(--teal-mid));
            color: var(--amber-light);
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
        }

        @media (max-width: 1240px) {
            .dashboard-main-area { grid-template-columns: 1fr; }
            aside { width: 100%; }
        }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 86px; }

            .side-brand h3,
            .side-brand p,
            .nav-label,
            .nav-item span:not(:first-child),
            .side-user > div:nth-child(2) {
                display: none;
            }

            .nav-item { justify-content: center; }
            .badge-nav { display: none; }
            .side-user { justify-content: center; }

            .stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .header-bar { height: auto; min-height: var(--topbar-height); flex-wrap: wrap; padding: 10px 16px; }
        }

        @media (max-width: 680px) {
            :root { --sidebar-width: 0px; }
            .sidebar { display: none; }
            .main-wrapper { margin-left: 0; }
            .dashboard-body { padding: 20px 14px; }
            .stat-grid { grid-template-columns: 1fr; }
            .tab-group { justify-content: flex-start; }
            .activity-time { margin-left: 0; }
            .breakdown-item { grid-template-columns: 8px 1fr auto; }
            .breakdown-percent { display: none; }
        }
    </style>
</head>
<body>
@php
    $adminName = trim((auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? ''));
    $adminName = $adminName !== '' ? $adminName : (auth()->user()->email ?? 'Admin User');
    $initials = strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'D', 0, 1));
    $totalForBreakdown = max(1, $totalApplications ?? 0);
@endphp

<div class="app-container">
    <aside class="sidebar">
        <div class="side-brand">
            <div class="logo-icon">🎓</div>
            <div>
                <h3 style="font-family:'Fraunces'; font-size: 16px;">Scholar<span style="color:var(--warm-amber)">Link</span></h3>
                <p style="font-size:9px; color:rgba(255,255,255,0.3); text-transform:uppercase;">Admin Panel</p>
            </div>
        </div>

        <nav class="side-nav">
            <div class="nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item active"><span>📊</span> Dashboard</a>
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

    <div class="main-wrapper">
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
                    @if(($unreadNotifications ?? 0) > 0)
                        <span class="notif-dot"></span>
                    @endif
                </button>
                <div class="top-avatar">{{ $initials }}</div>
            </div>
        </header>

        <main class="dashboard-body">
            <div class="dashboard-heading">
                <div>
                    @php
                        $organization = auth()->user()->organization ?? null;
                    @endphp
                    <p style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--teal-mid);">{{ $organization?->name ?? 'ScholarLink' }}</p>
                    <h2 style="font-family:'Fraunces'; font-size:28px; font-weight:700;">Admin Dashboard</h2>
                    <p style="font-size:12px; color:var(--muted); margin-top:2px;">{{ $now->format('l, F j, Y') }} · Academic Year {{ $now->year }}–{{ $now->copy()->addYear()->year }}</p>
                </div>
                <div class="heading-actions">
                    <button class="btn-pri" style="background:white; border:1px solid var(--border-light); color:var(--deep-teal);">Export Report</button>
                    <button class="btn-pri">New Scholarship</button>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div style="display:flex; justify-content:space-between;"><span class="icon">📖</span><span class="stat-badge">↑ {{ $newScholarships }} new</span></div>
                    <h1>{{ $openScholarships }}</h1>
                    <p style="font-size:12px; font-weight:600; color:var(--teal-mid)">Open Scholarships</p>
                    <p class="stat-card-foot">{{ $closingSoonScholarships }} closing soon · {{ $draftScholarships }} drafting</p>
                </div>
                <div class="stat-card">
                    <div style="display:flex; justify-content:space-between;"><span class="icon">📝</span><span class="stat-badge" style="color:var(--red-alert)">↑ {{ $pendingToday }} today</span></div>
                    <h1>{{ $pendingReviews }}</h1>
                    <p style="font-size:12px; font-weight:600; color:var(--teal-mid)">Pending Reviews</p>
                    <p class="stat-card-foot">Oldest: {{ $oldestPendingDays }} days ago</p>
                </div>
                <div class="stat-card">
                    <div style="display:flex; justify-content:space-between;"><span class="icon">📈</span><span class="stat-badge">{{ $applicationsGrowth >= 0 ? '↑' : '↓' }} {{ abs($applicationsGrowth) }}%</span></div>
                    <h1>{{ $totalApplications }}</h1>
                    <p style="font-size:12px; font-weight:600; color:var(--teal-mid)">Total Applications</p>
                    <p class="stat-card-foot">This academic year</p>
                </div>
                <div class="stat-card">
                    <div style="display:flex; justify-content:space-between;"><span class="icon">💰</span><span class="stat-badge">↑ 8%</span></div>
                    <h1>{{ $approvedAwarded }}</h1>
                    <p style="font-size:12px; font-weight:600; color:var(--teal-mid)">Approved / Awarded</p>
                    <p class="stat-card-foot">{{ $approvalRate }}% approval rate</p>
                </div>
            </div>

            <div class="dashboard-main-area">
                <div>
                    <div class="box-container">
                        <div class="box-header">
                            <h3 class="box-title">⚠ Bottleneck Alerts</h3>
                            <span style="font-size:10px; color:var(--muted)">Auto-refreshes every 5 min</span>
                        </div>
                        <div class="alert-row alert-red">
                            <div style="font-size:18px;">🚨</div>
                            <div>
                                <b style="font-size:13px;">{{ $unassignedApplications ?? 0 }} Applications Unassigned for 4+ Days</b>
                                <p style="font-size:12px; opacity:0.8;">Applications with no evaluations assigned yet. Risk of missing SLA.</p>
                                <a href="{{ route('admin.applications.unassigned') ?? '#' }}" style="color:inherit; font-weight:700; font-size:11px; margin-top:6px; display:block;">Assign Evaluators →</a>
                            </div>
                        </div>
                        <div class="alert-row alert-orange">
                            <div style="font-size:18px;">⏳</div>
                            <div>
                                <b style="font-size:13px;">Upcoming Deadlines — {{ $incompleteDocsApplications ?? 0 }} Applicants with Incomplete Docs</b>
                                <p style="font-size:12px; opacity:0.8;">Applicants still missing at least one required document.</p>
                                <a href="{{ route('admin.applications.incomplete_docs') ?? '#' }}" style="color:inherit; font-weight:700; font-size:11px; margin-top:6px; display:block;">View Applicants →</a>
                            </div>
                        </div>
                        <div class="alert-row alert-blue">
                            <div style="font-size:18px;">📢</div>
                            <div>
                                <b style="font-size:13px;">{{ $awaitingApprovalScholarships ?? 0 }} Scholarships Awaiting Approval</b>
                                <p style="font-size:12px; opacity:0.8;">Draft scholarships are ready for review and publication.</p>
                                <a href="{{ route('admin.scholarships.drafts') ?? '#' }}" style="color:inherit; font-weight:700; font-size:11px; margin-top:6px; display:block;">Review Drafts →</a>
                            </div>
                        </div>
                    </div>

                    <div class="activity-section">
                        <div class="section-header">
                            <h3>Recent Activity</h3>
                            <span class="subtitle">View all →</span>
                        </div>

                        <div class="activity-list">
                            @forelse($recentActivity as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon" style="background: #f3f4f6; color: #0f4c5c;">📌</div>
                                    <div class="activity-content">
                                        <div class="activity-text"><strong>{{ trim((optional($activity->user)->first_name ?? '').' '.(optional($activity->user)->last_name ?? '')) ?: 'System' }}</strong> {{ $activity->action }}</div>
                                        <div class="activity-meta">
                                            <span class="activity-badge">{{ ucfirst(str_replace('_', ' ', $activity->target_type ?? 'system')) }}</span>
                                            <span>{{ $activity->target_type ? 'Target ID: '.$activity->target_id : 'System activity' }}</span>
                                            <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="activity-item">
                                    <div class="activity-icon" style="background: #f3f4f6; color: #0f4c5c;">ℹ️</div>
                                    <div class="activity-content">
                                        <div class="activity-text"><strong>No recent activity yet.</strong> Activity logs will appear here as users interact with the system.</div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="box-container">
                        <div class="box-header">
                            <h3 class="box-title">Scholarship Overview</h3>
                            <a href="{{ route('admin.scholarships.index') ?? '#' }}" style="font-size:12px; color:var(--teal-mid); font-weight:700;">Manage all →</a>
                        </div>
                        <table style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Scholarship</th>
                                <th>Status</th>
                                <th>Apps</th>
                                <th>Capacity</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($scholarshipOverview as $scholarship)
                                @php
                                    $appsCount = $scholarship->applications_count ?? 0;
                                    $slots = max(1, (int) $scholarship->slots);
                                    $fill = min(100, (int) round(($appsCount / $slots) * 100));
                                    $statusColor = match($scholarship->status) {
                                        'open' => 'var(--green-success)',
                                        'closed' => 'var(--red-alert)',
                                        'coming_soon' => 'var(--orange-warning)',
                                        default => 'var(--muted)',
                                    };
                                @endphp
                                <tr>
                                    <td><b>{{ $scholarship->name }}</b><br><small style="color:var(--muted)">{{ $scholarship->provider_name }}</small></td>
                                    <td><span style="color:{{ $statusColor }}; font-weight:700;">● {{ ucfirst(str_replace('_', ' ', $scholarship->status)) }}</span></td>
                                    <td>{{ $appsCount }} / {{ $scholarship->slots }}</td>
                                    <td><div class="prog-bar-container"><div class="prog-bar-fill" style="width:{{ $fill }}%"></div></div></td>
                                    <td style="text-align:right;"><a href="{{ route('admin.scholarships.show', $scholarship->id) }}" style="color:var(--teal-mid); font-weight:700;">View →</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; color:var(--muted);">No scholarships found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <aside>
                    <div class="box-container">
                        <h3 class="box-title" style="margin-bottom:12px;">Quick Actions</h3>
                        <div class="action-grid">
                            <div class="action-card">
                                <div style="font-size:18px; margin-bottom:5px;">➕</div>
                                <b style="font-size:11px;">New Scholarship</b>
                            </div>
                            <div class="action-card">
                                <div style="font-size:18px; margin-bottom:5px;">👥</div>
                                <b style="font-size:11px;">Assign</b>
                            </div>
                            <div class="action-card">
                                <div style="font-size:18px; margin-bottom:5px;">⚙️</div>
                                <b style="font-size:11px;">Weight Config</b>
                            </div>
                            <div class="action-card">
                                <div style="font-size:18px; margin-bottom:5px;">📊</div>
                                <b style="font-size:11px;">Analytics</b>
                            </div>
                        </div>
                    </div>

                    <div class="alerts-section">
                        <div class="section-header">
                            <h3>Application Breakdown</h3>
                            <span class="subtitle">{{ $totalApplications }} total</span>
                        </div>

                        <div>
                            <div class="breakdown-item">
                                <div class="breakdown-color" style="background: #ea8c55;"></div>
                                <div class="breakdown-label">Pending Review</div>
                                <div class="breakdown-number">{{ $statusCounts['pending'] }}</div>
                                <div class="breakdown-percent">{{ round(($statusCounts['pending'] / $totalForBreakdown) * 100) }}%</div>
                            </div>
                            <div class="breakdown-item">
                                <div class="breakdown-color" style="background: #1a8fa0;"></div>
                                <div class="breakdown-label">Under Evaluation</div>
                                <div class="breakdown-number">{{ $statusCounts['under_review'] }}</div>
                                <div class="breakdown-percent">{{ round(($statusCounts['under_review'] / $totalForBreakdown) * 100) }}%</div>
                            </div>
                            <div class="breakdown-item">
                                <div class="breakdown-color" style="background: #8b5cf6;"></div>
                                <div class="breakdown-label">Awaiting Docs</div>
                                <div class="breakdown-number">{{ $statusCounts['revision'] }}</div>
                                <div class="breakdown-percent">{{ round(($statusCounts['revision'] / $totalForBreakdown) * 100) }}%</div>
                            </div>
                            <div class="breakdown-item">
                                <div class="breakdown-color" style="background: #10b981;"></div>
                                <div class="breakdown-label">Approved</div>
                                <div class="breakdown-number">{{ $statusCounts['approved'] }}</div>
                                <div class="breakdown-percent">{{ round(($statusCounts['approved'] / $totalForBreakdown) * 100) }}%</div>
                            </div>
                            <div class="breakdown-item">
                                <div class="breakdown-color" style="background: #ef5350;"></div>
                                <div class="breakdown-label">Rejected</div>
                                <div class="breakdown-number">{{ $statusCounts['rejected'] }}</div>
                                <div class="breakdown-percent">{{ round(($statusCounts['rejected'] / $totalForBreakdown) * 100) }}%</div>
                            </div>

                            <div class="stacked-bar">
                                <div class="stacked-bar-segment" style="background: #ea8c55; width: {{ ($statusCounts['pending'] / $totalForBreakdown) * 100 }}%;"></div>
                                <div class="stacked-bar-segment" style="background: #1a8fa0; width: {{ ($statusCounts['under_review'] / $totalForBreakdown) * 100 }}%;"></div>
                                <div class="stacked-bar-segment" style="background: #8b5cf6; width: {{ ($statusCounts['revision'] / $totalForBreakdown) * 100 }}%;"></div>
                                <div class="stacked-bar-segment" style="background: #10b981; width: {{ ($statusCounts['approved'] / $totalForBreakdown) * 100 }}%;"></div>
                            </div>
                            <div style="font-size: 12px; color: #3da9b8; margin-top: 8px;">Stacked distribution across all scholarships</div>
                        </div>
                    </div>

                    <div class="alerts-section" style="margin-top: 20px;">
                        <div class="section-header">
                            <h3>Upcoming Deadlines</h3>
                            <span class="subtitle">Calendar →</span>
                        </div>

                        <div>
                            @forelse($upcomingDeadlines as $deadlineScholarship)
                                @php
                                    $daysLeft = max(0, $now->diffInDays($deadlineScholarship->deadline, false));
                                    $deadlineClass = $daysLeft <= 3 ? 'urgent' : ($daysLeft <= 10 ? 'warning' : ($daysLeft <= 20 ? 'safe' : 'info'));
                                    $deadlineColor = $daysLeft <= 3 ? '#ef5350' : ($daysLeft <= 10 ? '#ea8c55' : ($daysLeft <= 20 ? '#10b981' : '#1a8fa0'));
                                @endphp
                                <div class="deadline-item">
                                    <div class="deadline-color" style="background: {{ $deadlineColor }};"></div>
                                    <div class="deadline-content">
                                        <div class="deadline-name">{{ $deadlineScholarship->name }}</div>
                                        <div class="deadline-date">{{ $deadlineScholarship->deadline->format('M j') }} · {{ $deadlineScholarship->applications_count }}/{{ $deadlineScholarship->slots }} slots filled</div>
                                    </div>
                                    <div class="deadline-days {{ $deadlineClass }}">{{ $daysLeft }}d</div>
                                </div>
                            @empty
                                <div class="deadline-item">
                                    <div class="deadline-color" style="background: #1a8fa0;"></div>
                                    <div class="deadline-content">
                                        <div class="deadline-name">No upcoming deadlines</div>
                                        <div class="deadline-date">Create or open a scholarship to see timelines here.</div>
                                    </div>
                                    <div class="deadline-days info">--</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<script>
    // INTERACTIVITY LOGIC
    document.addEventListener('DOMContentLoaded', () => {
        console.log("Admin Dashboard Live");
        // 5 minute auto-refresh simulation
        setInterval(() => { console.log("Checking for updates..."); }, 300000);
    });
</script>
</body>
</html>

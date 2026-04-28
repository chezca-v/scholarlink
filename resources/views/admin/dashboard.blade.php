<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholarLink - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
@endphp

<div class="app-container">
    <x-sidebar :user="auth()->user()" :adminName="$adminName" :initials="$initials" :organization="auth()->user()->organization" />

    <div class="main-wrapper">
        <x-dashboard-header :initials="$initials" :unreadNotifications="$unreadNotifications ?? 0" />

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
                    <form method="POST" action="{{ route('admin.reports.export') ?? '#' }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-pri" style="background:white; border:1px solid var(--border-light); color:var(--deep-teal);">Export Report</button>
                    </form>
                    <a href="{{ route('admin.scholarships.create') ?? '#' }}" class="btn-pri" style="text-decoration:none; display:inline-block;">New Scholarship</a>
                </div>
            </div>

            @php
                $stats = $stats ?? [];
            @endphp
            <x-stat-cards :stats="$stats" />

            <div class="dashboard-main-area">
                <div>
                    @php
                        $alerts = $alerts ?? [];
                    @endphp
                    <x-alerts-section :alerts="$alerts" />

                    <x-activity-section :activities="$recentActivity ?? []" />

                    <x-scholarship-overview :scholarships="$scholarshipOverview ?? []" />
                </div>

                <aside>
                    @php
                        $quickActions = $quickActions ?? [];
                    @endphp
                    <x-quick-actions :actions="$quickActions" />

                    @php
                        $breakdownItems = $breakdownItems ?? [];
                    @endphp
                    <x-application-breakdown :statusCounts="$statusCounts ?? []" :totalApplications="$totalApplications ?? 0" :breakdownItems="$breakdownItems" />

                    <x-upcoming-deadlines :deadlines="$upcomingDeadlines ?? []" :now="$now" />
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

    /**
     * Session Timeout Tracker
     */
    document.addEventListener('alpine:init', () => {
        Alpine.data('sessionTracker', () => ({
            idleSeconds: 0,
            warningLimit: 13 * 60,
            timeoutLimit: 15 * 60,
            interval: null,
            isWarningShown: false,

            init() {
                this.resetIdleTime();
                const events = ['mousemove', 'keydown', 'scroll', 'click', 'touchstart'];
                events.forEach(event => {
                    window.addEventListener(event, () => this.resetIdleTime(), true);
                });

                this.interval = setInterval(() => {
                    this.idleSeconds++;
                    if (this.idleSeconds === this.warningLimit && !this.isWarningShown) {
                        this.isWarningShown = true;
                        const modal = document.getElementById('session-timeout-modal');
                        if (modal) modal.style.display = 'flex';
                    }
                    if (this.idleSeconds >= this.timeoutLimit) {
                        clearInterval(this.interval);
                        document.getElementById('auto-logout-form').submit();
                    }
                }, 1000);
            },

            resetIdleTime() {
                this.idleSeconds = 0;
                this.isWarningShown = false;
                const modal = document.getElementById('session-timeout-modal');
                if (modal) modal.style.display = 'none';
            },

            logout() {
                document.getElementById('auto-logout-form').submit();
            },

            stayLoggedIn() {
                this.resetIdleTime();
                const modal = document.getElementById('session-timeout-modal');
                if (modal) modal.style.display = 'none';
            }
        }));
    });

    // Make tracker globally available
    window.sessionTracker = null;
    document.addEventListener('alpine:init', () => {
        const root = document.querySelector('[x-data]');
        if (root && root.__x && root.__x.$data) {
            window.sessionTracker = root.__x.$data;
        }
    });

    // Countdown timer
    setInterval(() => {
        const modal = document.getElementById('session-timeout-modal');
        if (modal && modal.style.display !== 'none') {
            const countdownEl = document.getElementById('session-timeout-countdown');
            const text = countdownEl.textContent;
            const [minutes, seconds] = text.split(':').map(Number);
            let totalSeconds = minutes * 60 + seconds - 1;
            if (totalSeconds < 0) totalSeconds = 0;
            const newMinutes = Math.floor(totalSeconds / 60);
            const newSeconds = totalSeconds % 60;
            countdownEl.textContent = `${newMinutes}:${newSeconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
</script>

<!-- Session Timeout Modal -->
<div id="session-timeout-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 400px; width: 90%; box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15); animation: slideUp 0.3s ease-out;">
        <div style="text-align: center; margin-bottom: 20px; font-size: 48px;">⏱️</div>
        <h2 style="font-size: 20px; font-weight: 700; color: #1a2e2c; margin-bottom: 12px; text-align: center;">Session Expiring Soon</h2>
        <p style="font-size: 14px; color: #4a6460; line-height: 1.6; margin-bottom: 24px; text-align: center;">You've been inactive for 13 minutes. Your session will expire in 2 minutes for your security. Please click "Stay Logged In" to continue.</p>
        <div style="display: flex; gap: 12px;">
            <button onclick="if (window.sessionTracker) window.sessionTracker.stayLoggedIn(); else location.reload();" style="flex: 1; padding: 12px 16px; background: linear-gradient(135deg, #0F4C5C, #1A6B7A); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Stay Logged In</button>
            <button onclick="if (window.sessionTracker) window.sessionTracker.logout(); else document.getElementById('auto-logout-form').submit();" style="flex: 1; padding: 12px 16px; background: #f5f5f5; color: #1a2e2c; border: 1px solid #e2e8e6; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Log Out</button>
        </div>
        <div style="text-align: center; margin-top: 16px; font-size: 12px; color: #8aaba6;">Automatically logging out in <span id="session-timeout-countdown">2:00</span></div>
    </div>
</div>

<!-- Auto Logout Form -->
<form id="auto-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</body>
</html>

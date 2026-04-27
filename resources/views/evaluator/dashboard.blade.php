<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholarLink - Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
</script>
</body>
</html>

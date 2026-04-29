@extends('admin.layouts.admin')

@section('title', 'Analytics')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="page-header">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">Analytics</h1>
            <p style="color:var(--slate);font-size:14px;margin-top:4px;">Track scholarship performance, applicant flow, and approval metrics.</p>
        </div>
        {{-- Date range filter --}}
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1 p-1" style="background:white;border-radius:10px;border:1.5px solid var(--border);">
                @foreach([['7d','7 days'],['30d','30 days'],['3m','3 months'],['1y','1 year'],['all','All time']] as [$val, $label])
                <button onclick="updateFilter('range', '{{ $val }}')"
                    class="tab-btn {{ request('range', '30d') === $val ? 'active' : '' }}"
                    style="font-size:12px;padding:6px 14px;">{{ $label }}</button>
                @endforeach
            </div>
            <a href="{{ route('admin.analytics.export') }}" class="btn-ghost flex items-center gap-2" style="font-size:13px;">
                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </a>
        </div>
    </div>
</div>

<div class="page-content space-y-6">
    {{-- Stat Cards Row --}}
    <div class="grid grid-cols-4 gap-4">
        @php
            $statCards = [
                ['label' => 'Total Applications', 'value' => number_format($stats['total_applications'] ?? 0), 'change' => $stats['apps_change'] ?? '+0%', 'up' => true, 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'],
                ['label' => 'Approval Rate', 'value' => ($stats['approval_rate'] ?? 0) . '%', 'change' => $stats['approval_change'] ?? '+0%', 'up' => true, 'icon' => 'M22 11.08V12a10 10 0 1 1-5.93-9.14'],
                ['label' => 'Avg. Review Time', 'value' => ($stats['avg_review_days'] ?? 0) . 'd', 'change' => $stats['review_change'] ?? '0d', 'up' => false, 'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'],
                ['label' => 'Active Scholarships', 'value' => number_format($stats['active_scholarships'] ?? 0), 'change' => $stats['active_change'] ?? '0', 'up' => true, 'icon' => 'M22 10v6M2 10l10-5 10 5-10 5z'],
            ];
        @endphp
        @foreach($statCards as $card)
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div style="width:38px;height:38px;background:rgba(15,76,92,0.08);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <svg style="width:18px;height:18px;color:var(--primary);" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $card['icon'] }}"/></svg>
                </div>
                <span style="font-size:12px;font-weight:600;padding:3px 8px;border-radius:99px;background:{{ $card['up'] ? '#DCFCE7' : '#FEE2E2' }};color:{{ $card['up'] ? '#16A34A' : '#DC2626' }};">
                    {{ $card['change'] }}
                </span>
            </div>
            <p style="font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">{{ $card['label'] }}</p>
            <p class="font-display font-bold text-3xl mt-1" style="color:var(--ink);">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Row 2: Applications over time + Funnel --}}
    <div class="grid grid-cols-3 gap-5">
        {{-- Applications over time chart --}}
        <div class="card p-6 col-span-2">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:var(--ink);">Applications Over Time</h2>
                    <p style="font-size:13px;color:var(--slate);margin-top:2px;">Daily submission volume</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5"><div style="width:10px;height:10px;background:var(--primary);border-radius:3px;"></div><span style="font-size:12px;color:var(--slate);">Applications</span></div>
                    <div class="flex items-center gap-1.5"><div style="width:10px;height:10px;background:var(--accent);border-radius:3px;"></div><span style="font-size:12px;color:var(--slate);">Approvals</span></div>
                </div>
            </div>
            <div style="position:relative;height:200px;">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>

        {{-- Application Funnel --}}
        <div class="card p-6">
            <h2 class="font-display font-bold text-base mb-1" style="color:var(--ink);">Application Funnel</h2>
            <p style="font-size:13px;color:var(--slate);margin-bottom:20px;">Conversion at each stage</p>
            <div class="space-y-3">
                @php
                    $funnel = [
                        ['stage' => 'Viewed', 'count' => $funnel['viewed'] ?? 0, 'pct' => 100],
                        ['stage' => 'Started', 'count' => $funnel['started'] ?? 0, 'pct' => $funnel['viewed'] > 0 ? round(($funnel['started'] ?? 0) / $funnel['viewed'] * 100) : 0],
                        ['stage' => 'Submitted', 'count' => $funnel['submitted'] ?? 0, 'pct' => $funnel['viewed'] > 0 ? round(($funnel['submitted'] ?? 0) / $funnel['viewed'] * 100) : 0],
                        ['stage' => 'Under Review', 'count' => $funnel['under_review'] ?? 0, 'pct' => $funnel['viewed'] > 0 ? round(($funnel['under_review'] ?? 0) / $funnel['viewed'] * 100) : 0],
                        ['stage' => 'Approved', 'count' => $funnel['approved'] ?? 0, 'pct' => $funnel['viewed'] > 0 ? round(($funnel['approved'] ?? 0) / $funnel['viewed'] * 100) : 0],
                    ];
                @endphp
                @foreach($funnel as $step)
                <div>
                    <div class="flex justify-between mb-1">
                        <span style="font-size:12px;font-weight:600;color:var(--ink);">{{ $step['stage'] }}</span>
                        <span style="font-size:12px;color:var(--slate);">{{ number_format($step['count']) }} · {{ $step['pct'] }}%</span>
                    </div>
                    <div style="background:var(--border);border-radius:99px;height:8px;overflow:hidden;">
                        <div style="width:{{ $step['pct'] }}%;height:100%;background:linear-gradient(90deg,var(--primary),var(--primary-light));border-radius:99px;transition:width 0.6s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Row 3: Scholarship performance table + Stage duration --}}
    <div class="grid grid-cols-2 gap-5">
        {{-- Scholarship Performance --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b" style="border-color:var(--border);">
                <h2 class="font-display font-bold text-base" style="color:var(--ink);">Scholarship Performance</h2>
                <p style="font-size:13px;color:var(--slate);margin-top:2px;">Applications and approval rate per scholarship</p>
            </div>
            <div>
                @forelse($scholarshipPerformance ?? [] as $item)
                <div class="flex items-center gap-4 px-5 py-4 border-b" style="border-color:var(--border);">
                    <div class="flex-1 min-w-0">
                        <p style="font-size:14px;font-weight:600;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->name }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="status-badge status-{{ $item->status }}" style="font-size:10px;padding:2px 8px;">{{ ucfirst($item->status) }}</span>
                            <span style="font-size:12px;color:var(--slate);">{{ $item->applications_count }} apps</span>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <p style="font-size:16px;font-weight:700;color:{{ $item->approval_rate >= 50 ? '#16A34A' : ($item->approval_rate >= 25 ? '#B45309' : '#DC2626') }};">{{ $item->approval_rate ?? 0 }}%</p>
                        <p style="font-size:11px;color:var(--slate);">approval rate</p>
                    </div>
                    <div style="width:80px;">
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" style="width:{{ min(100, $item->fill_rate ?? 0) }}%;"></div>
                        </div>
                        <p style="font-size:10px;color:var(--slate);margin-top:3px;text-align:right;">{{ $item->filled_slots ?? 0 }}/{{ $item->total_slots }} slots</p>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:40px 20px;">
                    <p style="font-size:14px;color:var(--slate);">No scholarship data yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Stage Duration + Approval Rate chart --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:var(--ink);">Avg. Stage Duration</h2>
                    <p style="font-size:13px;color:var(--slate);margin-top:2px;">Days spent at each review stage</p>
                </div>
            </div>
            <div style="position:relative;height:200px;">
                <canvas id="stageDurationChart"></canvas>
            </div>
            <div class="grid grid-cols-3 gap-3 mt-5">
                @php
                    $stageDurations = [
                        ['stage' => 'Submission → Review', 'days' => $durations['submission_to_review'] ?? 0],
                        ['stage' => 'Review → Decision', 'days' => $durations['review_to_decision'] ?? 0],
                        ['stage' => 'Total Avg. Time', 'days' => $durations['total_avg'] ?? 0],
                    ];
                @endphp
                @foreach($stageDurations as $d)
                <div style="text-align:center;padding:12px;background:var(--page-bg);border-radius:12px;">
                    <p style="font-size:20px;font-weight:700;color:var(--primary);">{{ $d['days'] }}d</p>
                    <p style="font-size:11px;color:var(--slate);margin-top:2px;">{{ $d['stage'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Row 4: Demographic breakdown --}}
    <div class="grid grid-cols-2 gap-5">
        {{-- Course Distribution --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:var(--ink);">Applicants by Course</h2>
                    <p style="font-size:13px;color:var(--slate);margin-top:2px;">Top courses among applicants</p>
                </div>
            </div>
            <div style="position:relative;height:200px;">
                <canvas id="courseChart"></canvas>
            </div>
        </div>

        {{-- Income Distribution --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-display font-bold text-base" style="color:var(--ink);">Family Income Breakdown</h2>
                    <p style="font-size:13px;color:var(--slate);margin-top:2px;">Monthly household income distribution</p>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $incomeBrackets = [
                        ['label' => 'Below ₱10,000', 'count' => $demographics['income']['below_10k'] ?? 0, 'color' => 'var(--primary)'],
                        ['label' => '₱10,001 – ₱20,000', 'count' => $demographics['income']['10k_20k'] ?? 0, 'color' => 'var(--primary-light)'],
                        ['label' => '₱20,001 – ₱40,000', 'count' => $demographics['income']['20k_40k'] ?? 0, 'color' => 'var(--accent)'],
                        ['label' => '₱40,001 – ₱80,000', 'count' => $demographics['income']['40k_80k'] ?? 0, 'color' => 'var(--accent-light)'],
                        ['label' => 'Above ₱80,000', 'count' => $demographics['income']['above_80k'] ?? 0, 'color' => 'var(--muted)'],
                    ];
                    $maxIncome = max(array_column($incomeBrackets, 'count')) ?: 1;
                @endphp
                @foreach($incomeBrackets as $bracket)
                <div class="flex items-center gap-3">
                    <p style="font-size:12px;color:var(--ink);width:160px;flex-shrink:0;">{{ $bracket['label'] }}</p>
                    <div style="flex:1;background:var(--border);border-radius:99px;height:10px;overflow:hidden;">
                        <div style="width:{{ round($bracket['count'] / $maxIncome * 100) }}%;height:100%;background:{{ $bracket['color'] }};border-radius:99px;transition:width 0.6s ease;"></div>
                    </div>
                    <p style="font-size:12px;font-weight:600;color:var(--ink);width:36px;text-align:right;">{{ $bracket['count'] }}</p>
                </div>
                @endforeach
            </div>
            <hr style="border-color:var(--border);margin:16px 0;">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p style="font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Median Income</p>
                    <p style="font-size:18px;font-weight:700;color:var(--ink);margin-top:3px;">₱{{ number_format($demographics['median_income'] ?? 0) }}</p>
                </div>
                <div>
                    <p style="font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Financial Need Flag</p>
                    <p style="font-size:18px;font-weight:700;color:var(--ink);margin-top:3px;">{{ $demographics['below_poverty_pct'] ?? 0 }}%</p>
                    <p style="font-size:11px;color:var(--slate);">below ₱10k/mo</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Chart.js global defaults
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.color = '#4A7A80';

// Applications over time
const appsCtx = document.getElementById('applicationsChart').getContext('2d');
new Chart(appsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels'] ?? []) !!},
        datasets: [
            {
                label: 'Applications',
                data: {!! json_encode($chartData['applications'] ?? []) !!},
                borderColor: '#0F4C5C',
                backgroundColor: 'rgba(15,76,92,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#0F4C5C',
            },
            {
                label: 'Approvals',
                data: {!! json_encode($chartData['approvals'] ?? []) !!},
                borderColor: '#E8A838',
                backgroundColor: 'rgba(232,168,56,0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#E8A838',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 8 } },
            y: { grid: { color: '#DFF0EE' }, border: { display: false }, ticks: { precision: 0 } }
        }
    }
});

// Stage Duration bar chart
const stageCtx = document.getElementById('stageDurationChart').getContext('2d');
new Chart(stageCtx, {
    type: 'bar',
    data: {
        labels: ['Submitted', 'Under Review', 'Approved', 'Rejected'],
        datasets: [{
            label: 'Avg Days',
            data: {!! json_encode($chartData['stage_durations'] ?? [3.2, 7.5, 5.1, 2.3]) !!},
            backgroundColor: ['rgba(15,76,92,0.8)', 'rgba(42,143,160,0.8)', 'rgba(22,163,74,0.8)', 'rgba(220,38,38,0.7)'],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: { grid: { color: '#DFF0EE' }, border: { display: false }, ticks: { callback: v => v + 'd' } }
        }
    }
});

// Course Distribution doughnut
const courseCtx = document.getElementById('courseChart').getContext('2d');
new Chart(courseCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_column($demographics['courses'] ?? [], 'label')) !!},
        datasets: [{
            data: {!! json_encode(array_column($demographics['courses'] ?? [], 'count')) !!},
            backgroundColor: ['#0F4C5C', '#1A6B7A', '#2A8FA0', '#E8A838', '#F9D679', '#7AACAA'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right', labels: { boxWidth: 10, padding: 14, font: { size: 12 } } }
        },
        cutout: '68%',
    }
});

function updateFilter(key, value) {
    const url = new URL(window.location.href);
    if (value) url.searchParams.set(key, value);
    else url.searchParams.delete(key);
    window.location.href = url.toString();
}
</script>
@endpush
@endsection
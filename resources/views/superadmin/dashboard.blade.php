{{--
|--------------------------------------------------------------------------
| resources/views/superadmin/dashboard.blade.php
| Route : superadmin.dashboard
| Purpose: Nationwide stats + fraud alert feed
|--------------------------------------------------------------------------
| Controller should pass:
|   $stats           array  of stat cards
|   $orgPerformance  array  bar chart rows
|   $fraudAlerts     array  alert feed items
|   $fraudAlertCount int
|   $systemHealth    array  health indicator cards
|   $chartMonths     array  monthly bar data [{month,pct,accent}]
--}}
@extends('layouts.superadmin')

@section('page_title', 'Dashboard')
@section('topnav_title', 'Dashboard')
@section('topnav_subtitle', '/superadmin/dashboard')

@section('content')
    <div class="breadcrumb">
        <span>Home</span><span class="sep">/</span><span class="current">Dashboard</span>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">
        @foreach ($stats as $stat)
            <div class="stat-card">
                <div class="icon-wrap" style="background:{{ $stat['icon_bg'] }};margin-bottom:10px">{{ $stat['icon'] }}</div>
                <div class="label">{{ $stat['label'] }}</div>
                <div class="value">{{ $stat['value'] }}</div>
                <div class="delta {{ $stat['delta_class'] }}">{{ $stat['delta'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- TWO-COLUMN --}}
    <div class="grid-2">

        {{-- Org Performance Chart --}}
        <div class="card">
            <div class="section-header">
                <div class="section-title">Organization Performance <small>by applications processed</small></div>
                <button class="btn btn-outline btn-sm">Export</button>
            </div>
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <span style="font-size:11px;color:var(--slate)">Sort by:</span>
                <button class="badge teal" style="cursor:pointer">Volume</button>
                <button class="badge gray" style="cursor:pointer">Approval %</button>
                <button class="badge gray" style="cursor:pointer">Avg Score</button>
            </div>
            @foreach ($orgPerformance as $org)
                <div class="org-row">
                    <span class="name">{{ $org['name'] }}</span>
                    <div class="bar-track">
                        <div class="fill" style="width:{{ $org['pct'] }}%;background:{{ $org['color'] }}"></div>
                    </div>
                    <span class="count">{{ $org['count'] }}</span>
                </div>
            @endforeach
        </div>

        {{-- Alert feed + health --}}
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card compact">
                <div class="section-header">
                    <div class="section-title">🚨 Fraud Alert Feed</div>
                    <span class="badge red">{{ $fraudAlertCount }} active</span>
                </div>
                @foreach ($fraudAlerts as $alert)
                    <div class="alert-item">
                        <div class="alert-dot {{ $alert['dot'] }}"></div>
                        <div>
                            <div style="font-size:13px;font-weight:500;color:var(--ink)">{{ $alert['title'] }}</div>
                            <div style="font-size:11px;color:var(--slate)">{{ $alert['meta'] }}</div>
                        </div>
                    </div>
                @endforeach
                <div style="margin-top:10px;text-align:center">
                    <a href="{{ route('superadmin.logs') }}" class="btn btn-outline btn-sm" style="width:100%">View All Alerts →</a>
                </div>
            </div>

            <div class="card compact">
                <div class="section-title" style="margin-bottom:12px">System Health</div>
                <div class="health-grid">
                    @foreach ($systemHealth as $h)
                        <div class="health-item">
                            <div class="h-label">{{ $h['label'] }}</div>
                            <div class="h-value">{{ $h['value'] }}</div>
                            <div class="h-status {{ $h['status'] }}">{{ $h['status_text'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- VOLUME CHART --}}
    <div class="card" style="margin-top:20px">
        <div class="section-header">
            <div class="section-title">Nationwide Application Volume <small>last 12 months</small></div>
            <div style="display:flex;gap:8px">
                <span class="badge teal">■ Applications</span>
                <span class="badge amber">■ Approvals</span>
            </div>
        </div>
        <div class="chart-area">
            @foreach ($chartMonths as $bar)
                <div class="bar-col">
                    <div class="bar-fill {{ $bar['accent'] ? 'accent' : '' }}" style="height:{{ $bar['pct'] }}%"></div>
                    <div class="bar-label">{{ $bar['month'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

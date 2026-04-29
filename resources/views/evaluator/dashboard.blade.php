@extends('layouts.evaluator')

@section('title', 'ScholarLink — Evaluator Dashboard')
@section('page_title', 'Dashboard')

@section('topnav_actions')
    <a href="{{ route('evaluator.queue') }}" class="btn btn-primary btn-sm">📥 Review Queue</a>
@endsection

@section('content')
@php
    $initials = strtoupper(
        substr(auth()->user()->first_name ?? 'E', 0, 1) .
        substr(auth()->user()->last_name  ?? 'C', 0, 1)
    );
    $name = trim((auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? ''));
@endphp

{{-- ── HERO BANNER ── --}}
<div style="background:linear-gradient(135deg,#0F4C5C,#1A6B7A); border-radius:20px; padding:28px 32px; display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute;top:-40px;right:-40px;width:240px;height:240px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-60px;right:100px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <div style="z-index:1;">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.55);margin-bottom:4px;">📋 Evaluator Portal</p>
        <h1 style="font-family:'Fraunces',serif;font-size:26px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px;">Welcome back, <span style="color:#F9D679;">{{ $name ?: 'Evaluator' }}</span>!</h1>
        <p style="font-size:13px;color:rgba(255,255,255,.65);">{{ $now->format('l, F j, Y') }} · Review applications assigned to you below.</p>
    </div>
    <div style="z-index:1; text-align:center;">
        <div style="width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-size:26px;font-weight:900;color:#F9D679;margin:0 auto 6px;">{{ $initials }}</div>
        <p style="font-size:11px;color:rgba(255,255,255,.55);">Active Evaluator</p>
    </div>
</div>

{{-- ── STAT CARDS ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
    @foreach($stats as $stat)
    <div style="background:#fff;border:1.5px solid var(--border);border-radius:16px;padding:18px 20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
            <span style="font-size:22px;">{{ $stat['icon'] }}</span>
            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:rgba(15,76,92,.08);color:var(--primary);">{{ $stat['badge_text'] }}</span>
        </div>
        <div style="font-family:'Fraunces',serif;font-size:30px;font-weight:700;color:var(--ink);line-height:1;">{{ $stat['value'] }}</div>
        <div style="font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-top:4px;">{{ $stat['label'] }}</div>
        <div style="font-size:11px;color:var(--slate);margin-top:2px;">{{ $stat['footer'] }}</div>
    </div>
    @endforeach

    {{-- Unread Notifications card --}}
    <div style="background:#fff;border:1.5px solid var(--border);border-radius:16px;padding:18px 20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
            <span style="font-size:22px;">🔔</span>
            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:#FFFBEB;color:#D97706;">Unread</span>
        </div>
        <div style="font-family:'Fraunces',serif;font-size:30px;font-weight:700;color:var(--ink);line-height:1;">{{ $unreadNotifications }}</div>
        <div style="font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-top:4px;">Notifications</div>
        <div style="font-size:11px;color:var(--slate);margin-top:2px;"><a href="{{ route('evaluator.notifications') }}" style="color:var(--primary);text-decoration:none;">View all →</a></div>
    </div>

    {{-- Quick link card --}}
    <div style="background:linear-gradient(135deg,#E8A838,#F9D679);border-radius:16px;padding:18px 20px;cursor:pointer;" onclick="window.location='{{ route('evaluator.queue') }}'">
        <div style="font-size:22px;margin-bottom:10px;">📥</div>
        <div style="font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:#5a3a00;line-height:1.2;margin-bottom:4px;">Start Reviewing</div>
        <div style="font-size:11px;color:rgba(90,58,0,.7);">Open review queue →</div>
    </div>
</div>

{{-- ── RECENT PENDING APPLICATIONS ── --}}
@php
    $pending = \App\Models\Application::with('scholarship', 'applicant')
        ->where('status', 'pending')
        ->latest()
        ->take(8)
        ->get();
@endphp

<div style="background:#fff;border:1.5px solid var(--border);border-radius:20px;padding:22px 24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
        <div>
            <h2 style="font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--ink);">📋 Pending Applications</h2>
            <p style="font-size:12px;color:var(--slate);margin-top:2px;">Applications awaiting your review</p>
        </div>
        <a href="{{ route('evaluator.queue') }}" class="btn btn-primary btn-sm" style="text-decoration:none;">View Full Queue</a>
    </div>

    @if($pending->isEmpty())
        <div style="text-align:center;padding:40px 20px;color:var(--muted);">
            <div style="font-size:40px;margin-bottom:12px;">🎉</div>
            <p style="font-size:15px;font-weight:600;color:var(--slate);">All caught up!</p>
            <p style="font-size:12px;">No pending applications to review.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Applicant</th>
                        <th>Scholarship</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $app)
                    <tr>
                        <td style="font-weight:600;color:var(--muted);">#{{ $app->id }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:50%;background:rgba(15,76,92,.1);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:var(--primary);flex-shrink:0;">
                                    {{ strtoupper(substr($app->applicant->first_name ?? 'A', 0, 1) . substr($app->applicant->last_name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;">{{ $app->applicant->first_name ?? '—' }} {{ $app->applicant->last_name ?? '' }}</div>
                                    <div style="font-size:11px;color:var(--slate);">{{ $app->applicant->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:500;font-size:13px;">{{ Str::limit($app->scholarship->name ?? '—', 35) }}</div>
                        </td>
                        <td style="font-size:12px;color:var(--slate);">{{ $app->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge yellow">⏳ Pending</span>
                        </td>
                        <td>
                            <a href="{{ route('evaluator.review.show', $app->id) }}" class="btn btn-outline btn-sm" style="text-decoration:none;">Review →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

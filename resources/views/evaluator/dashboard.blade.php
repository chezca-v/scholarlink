<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholarLink - Evaluator Dashboard</title>
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

{{-- ── HERO BANNER ── --}}
<div style="background:linear-gradient(135deg,#0F4C5C,#1A6B7A); border-radius:20px; padding:28px 32px; display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; position:relative; overflow:hidden;">
    <div style="position:absolute;top:-40px;right:-40px;width:240px;height:240px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-60px;right:100px;width:180px;height:180px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <div style="z-index:1;">
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.55);margin-bottom:4px;">📋 Evaluator Portal</p>
        <h1 style="font-family:'Fraunces',serif;font-size:26px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px;">Welcome back, <span style="color:#F9D679;">{{ $name ?: 'Evaluator' }}</span>!</h1>
        <p style="font-size:13px;color:rgba(255,255,255,.65);">{{ $now->format('l, F j, Y') }} · Assigned by: {{ $adminName }}</p>
    </div>
    <div style="z-index:1; text-align:center;">
        <div style="width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-size:26px;font-weight:900;color:#F9D679;margin:0 auto 6px;">{{ $initials }}</div>
        <p style="font-size:11px;color:rgba(255,255,255,.55);">Active Evaluator</p>
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

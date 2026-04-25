<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ScholarLink') }} — Philippines' AI Scholarship Platform</title>
    <meta name="description" content="Build your profile once, apply to every scholarship in the Philippines. AI-matched, just for you.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">

    <style>
    /* ─── TOKENS ─── */
    :root {
        --teal-950: #071820;
        --teal-900: #0F4C5C;
        --teal-800: #1A6B7A;
        --teal-600: #22889a;
        --teal-300: #7AACAA;
        --teal-200: #C8E8E4;
        --teal-100: #EAF4F3;
        --teal-50:  #F0FAFA;
        --gold:     #E8A838;
        --gold-dark:#c9a227;
        --gold-bg:  #FDF4E3;
        --white:    #ffffff;
        --dark:     #0A3040;
        --body:     #4A7A80;
        --muted:    #7AACAA;
        --border:   #EAF4F3;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'DM Sans', sans-serif; background: var(--white); color: var(--dark); overflow-x: hidden; }
    a { text-decoration: none; }

    /* ─── NAV ─── */
    nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 100;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
        transition: box-shadow 0.3s;
    }
    nav.scrolled { box-shadow: 0 4px 24px rgba(15,76,92,0.1); }
    .nav-inner {
        max-width: 1160px; margin: 0 auto; padding: 0 48px;
        height: 64px; display: flex; align-items: center; justify-content: space-between;
    }
    .logo {
        font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700;
        color: var(--teal-900); letter-spacing: -0.5px;
        display: flex; align-items: center; gap: 10px;
    }
    .logo-mark {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, var(--teal-900), var(--teal-800));
        border-radius: 9px; display: flex; align-items: center; justify-content: center;
        color: var(--gold); font-size: 15px;
    }
    .nav-links { display: flex; gap: 36px; list-style: none; }
    .nav-links a {
        font-size: 14px; font-weight: 500; color: var(--muted);
        transition: color 0.2s;
    }
    .nav-links a:hover { color: var(--teal-900); }
    .nav-actions { display: flex; gap: 10px; align-items: center; }
    .btn-text {
        font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600;
        padding: 8px 18px; border: none; background: transparent;
        color: var(--teal-900); cursor: pointer; border-radius: 8px;
        transition: background 0.2s;
    }
    .btn-text:hover { background: var(--teal-50); }
    .btn-pill {
        font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700;
        padding: 9px 22px; border: none; background: var(--teal-900);
        color: var(--gold); border-radius: 999px; cursor: pointer;
        box-shadow: 0 4px 14px rgba(15,76,92,0.22);
        transition: all 0.25s;
    }
    .btn-pill:hover { background: var(--teal-800); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,76,92,0.3); }

    /* ─── HERO ─── */
    .hero {
        min-height: 100vh;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 120px 48px 80px; text-align: center;
        background: var(--white); position: relative; overflow: hidden;
    }
    .hero-bg-gradient {
        position: absolute; top: 0; left: 0; right: 0;
        height: 640px;
        background: radial-gradient(ellipse 80% 60% at 50% -10%, #E8F8F5 0%, transparent 65%);
        pointer-events: none;
    }
    /* Floating icons */
    .fi {
        position: absolute; opacity: 0.13; pointer-events: none;
        animation: floatUpDown 8s ease-in-out infinite;
        font-size: 52px; z-index: 0;
    }
    .fi:nth-child(2) { animation-delay: -2s; font-size: 38px; }
    .fi:nth-child(3) { animation-delay: -4s; font-size: 48px; }
    .fi:nth-child(4) { animation-delay: -6s; font-size: 44px; }
    @keyframes floatUpDown {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50%       { transform: translateY(-28px) rotate(4deg); }
    }

    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--teal-50); border: 1px solid var(--teal-200);
        border-radius: 999px; padding: 6px 16px;
        font-size: 12px; font-weight: 600; color: var(--body);
        margin-bottom: 32px; position: relative; z-index: 1;
        animation: fadeSlideDown 0.6s ease both;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:0.55;transform:scale(0.8);} }
    @keyframes fadeSlideDown { from{opacity:0;transform:translateY(-16px);} to{opacity:1;transform:none;} }

    .hero-title {
        font-family: 'Fraunces', serif; font-size: clamp(52px, 6vw, 90px);
        font-weight: 900; letter-spacing: -3px; line-height: 0.95;
        color: var(--dark); max-width: 820px; margin-bottom: 28px;
        position: relative; z-index: 1;
        animation: fadeSlideUp 0.7s 0.1s ease both;
    }
    .hero-title em { font-style: italic; font-weight: 300; color: var(--teal-900); }
    .hero-title .gold { color: var(--gold); }
    @keyframes fadeSlideUp { from{opacity:0;transform:translateY(24px);} to{opacity:1;transform:none;} }

    .hero-sub {
        font-size: 17px; color: var(--muted); line-height: 1.75;
        max-width: 520px; margin: 0 auto 40px;
        position: relative; z-index: 1;
        animation: fadeSlideUp 0.7s 0.2s ease both;
    }
    .hero-actions {
        display: flex; align-items: center; gap: 16px; justify-content: center;
        margin-bottom: 64px; position: relative; z-index: 1;
        animation: fadeSlideUp 0.7s 0.3s ease both;
    }
    .btn-hero-main {
        font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 700;
        padding: 14px 32px; border: none; background: var(--teal-900);
        color: var(--gold); border-radius: 12px; cursor: pointer;
        box-shadow: 0 8px 24px rgba(15,76,92,0.24);
        transition: all 0.25s;
    }
    .btn-hero-main:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(15,76,92,0.32); background: var(--teal-800); }
    .btn-hero-ghost {
        font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600;
        color: var(--body); background: transparent; border: none;
        cursor: pointer; display: flex; align-items: center; gap: 8px;
        transition: color 0.2s, gap 0.2s;
    }
    .btn-hero-ghost:hover { color: var(--teal-900); gap: 12px; }

    .hero-social-proof {
        display: flex; align-items: center; gap: 16px;
        position: relative; z-index: 1;
        animation: fadeSlideUp 0.7s 0.4s ease both;
    }
    .avatars { display: flex; }
    .avatar {
        width: 32px; height: 32px; border-radius: 50%;
        border: 2px solid var(--white); background: var(--teal-200);
        color: var(--teal-900); font-size: 12px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        margin-left: -8px;
    }
    .avatar:first-child { margin-left: 0; }
    .proof-text { font-size: 13px; color: var(--muted); }
    .proof-text strong { color: var(--teal-900); }

    /* Hero cards */
    .hero-visual {
        max-width: 900px; width: 100%; margin: 64px auto 0;
        position: relative; z-index: 1;
        animation: fadeSlideUp 0.8s 0.5s ease both;
    }
    .cards-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
    .mini-card {
        background: var(--white); border: 1px solid var(--border);
        border-radius: 16px; padding: 20px; text-align: left;
        box-shadow: 0 4px 16px rgba(15,76,92,0.06);
        transition: all 0.3s;
    }
    .mini-card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(15,76,92,0.12); border-color: var(--teal-200); }
    .mc-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .mc-org { font-size: 10px; font-weight: 700; letter-spacing: 0.5px; color: var(--muted); text-transform: uppercase; }
    .mc-badge { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
    .mc-badge.open    { color: #16A34A; background: #DCFCE7; }
    .mc-badge.closing { color: #B45309; background: #FEF9C3; }
    .mc-title { font-family: 'Fraunces', serif; font-size: 14px; font-weight: 700; color: var(--dark); margin-bottom: 12px; line-height: 1.3; }
    .mc-pct { font-family: 'Fraunces', serif; font-size: 24px; font-weight: 700; color: var(--gold); }
    .mc-pct-label { font-size: 10px; color: var(--muted); margin-top: 1px; }
    .mc-bar { height: 3px; background: var(--border); border-radius: 999px; margin-top: 10px; overflow: hidden; }
    .mc-bar-fill { height: 100%; background: linear-gradient(90deg, var(--teal-900), var(--gold)); border-radius: 999px; }

    /* ─── LOGOS ─── */
    .logos { padding: 40px 48px; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .logos-inner { max-width: 1160px; margin: 0 auto; display: flex; align-items: center; gap: 48px; }
    .logos-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--teal-200); white-space: nowrap; }
    .logos-row { display: flex; gap: 48px; align-items: center; flex: 1; flex-wrap: wrap; }
    .logo-item { font-family: 'Fraunces', serif; font-size: 15px; font-weight: 700; color: var(--teal-200); letter-spacing: -0.5px; transition: color 0.2s; cursor: default; }
    .logo-item:hover { color: var(--muted); }

    /* ─── SECTIONS SHARED ─── */
    .section-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); margin-bottom: 14px; }
    .section-title { font-family: 'Fraunces', serif; font-size: clamp(32px,3vw,50px); font-weight: 800; letter-spacing: -1.5px; color: var(--dark); line-height: 1.05; margin-bottom: 56px; }
    .section-title em { font-style: italic; font-weight: 300; color: var(--teal-900); }

    /* ─── HOW IT WORKS ─── */
    .how { padding: 112px 48px; background: var(--white); }
    .how-inner { max-width: 1160px; margin: 0 auto; }
    .steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 40px; }
    .step { display: flex; flex-direction: column; gap: 16px; }
    .step-num {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--teal-50); border: 1px solid var(--teal-200);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: var(--teal-900);
    }
    .step-title { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: var(--dark); }
    .step-desc { font-size: 14px; color: var(--muted); line-height: 1.75; }

    /* ─── SCHOLARSHIPS ─── */
    .scholarships { padding: 112px 48px; background: var(--teal-50); }
    .scholarships-inner { max-width: 1160px; margin: 0 auto; }
    .sch-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 36px; }
    .filters { display: flex; gap: 8px; flex-wrap: wrap; }
    .filter {
        font-size: 12px; font-weight: 600; padding: 7px 16px;
        border-radius: 999px; border: 1px solid var(--teal-200);
        background: var(--white); color: var(--body); cursor: pointer;
        transition: all 0.2s;
    }
    .filter:hover, .filter.active { background: var(--teal-900); color: var(--gold); border-color: var(--teal-900); }
    .s-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
    .s-card {
        background: var(--white); border: 1px solid var(--border);
        border-radius: 18px; padding: 24px;
        display: flex; flex-direction: column; cursor: pointer;
        transition: all 0.3s;
    }
    .s-card:hover { border-color: var(--teal-900); box-shadow: 0 12px 32px rgba(15,76,92,0.1); transform: translateY(-3px); }
    .sc-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .sc-org-name { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
    .sc-status { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 999px; }
    .sc-status.open    { background: #DCFCE7; color: #16A34A; }
    .sc-status.closing { background: #FEF9C3; color: #B45309; }
    .sc-title { font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: var(--dark); margin-bottom: 10px; line-height: 1.3; }
    .sc-details { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
    .sc-detail { font-size: 12px; color: var(--body); display: flex; align-items: center; gap: 8px; }
    .sc-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--teal-200); flex-shrink: 0; }
    .sc-match { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border); }
    .sc-match-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .sc-match-label { font-size: 11px; color: var(--muted); }
    .sc-match-pct { font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: var(--gold); }
    .sc-bar-bg { height: 4px; background: var(--border); border-radius: 999px; overflow: hidden; }
    .sc-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--teal-900), var(--gold)); }

    /* ─── FEATURES ─── */
    .features { padding: 112px 48px; background: var(--white); }
    .features-inner { max-width: 1160px; margin: 0 auto; }
    .feat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 56px; }
    .feat-card {
        background: var(--teal-50); border: 1px solid var(--border);
        border-radius: 20px; padding: 32px;
        transition: all 0.3s;
    }
    .feat-card:hover { border-color: var(--teal-200); box-shadow: 0 8px 24px rgba(15,76,92,0.07); }
    .feat-card.highlighted {
        background: var(--teal-900); border-color: transparent;
        grid-column: span 2;
        display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;
    }
    .feat-icon {
        width: 44px; height: 44px; background: var(--white);
        border: 1px solid var(--teal-200); border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; margin-bottom: 16px;
    }
    .feat-card.highlighted .feat-icon { background: rgba(255,255,255,0.12); border-color: transparent; width: 52px; height: 52px; font-size: 24px; }
    .feat-title { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 10px; }
    .feat-card.highlighted .feat-title { color: var(--gold); font-size: 24px; }
    .feat-desc { font-size: 13px; color: var(--body); line-height: 1.75; }
    .feat-card.highlighted .feat-desc { color: rgba(255,255,255,0.58); font-size: 14px; }
    .feat-right { display: flex; flex-direction: column; gap: 14px; }
    .feat-right-item {
        background: rgba(255,255,255,0.08); border-radius: 12px;
        padding: 14px 16px; display: flex; gap: 12px; align-items: center;
    }
    .fri-icon { font-size: 18px; flex-shrink: 0; }
    .fri-title { font-size: 13px; font-weight: 600; color: var(--white); }
    .fri-sub { font-size: 11px; color: rgba(255,255,255,0.48); margin-top: 2px; }

    /* ─── CTA ─── */
    .cta { padding: 112px 48px; background: var(--white); text-align: center; }
    .cta-inner { max-width: 640px; margin: 0 auto; }
    .cta-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--gold-bg); border: 1px solid rgba(232,168,56,0.28);
        border-radius: 999px; padding: 6px 16px;
        font-size: 12px; font-weight: 600; color: var(--gold); margin-bottom: 28px;
    }
    .cta-title { font-family: 'Fraunces', serif; font-size: clamp(36px,4vw,58px); font-weight: 900; letter-spacing: -2px; color: var(--dark); line-height: 1; margin-bottom: 16px; }
    .cta-title em { font-style: italic; font-weight: 300; color: var(--teal-900); }
    .cta-sub { font-size: 15px; color: var(--muted); line-height: 1.75; margin-bottom: 36px; }
    .cta-btns { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
    .btn-cta-main {
        font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 700;
        padding: 14px 32px; border: none; background: var(--teal-900);
        color: var(--gold); border-radius: 12px; cursor: pointer;
        box-shadow: 0 8px 24px rgba(15,76,92,0.22);
        transition: all 0.25s;
    }
    .btn-cta-main:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(15,76,92,0.32); }
    .btn-cta-sec {
        font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600;
        padding: 14px 32px; border: 1px solid var(--teal-200);
        background: transparent; color: var(--body); border-radius: 12px; cursor: pointer;
        transition: all 0.2s;
    }
    .btn-cta-sec:hover { border-color: var(--teal-900); color: var(--teal-900); }
    .stats-row { display: flex; justify-content: center; gap: 48px; margin-top: 56px; padding-top: 56px; border-top: 1px solid var(--border); flex-wrap: wrap; }
    .stat { text-align: center; }
    .stat-num { font-family: 'Fraunces', serif; font-size: 32px; font-weight: 700; color: var(--gold); }
    .stat-label { font-size: 12px; color: var(--muted); margin-top: 4px; }

    /* ─── FOOTER ─── */
    footer { background: var(--teal-950); padding: 64px 48px 32px; }
    .footer-inner { max-width: 1160px; margin: 0 auto; }
    .footer-grid {
        display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 48px; padding-bottom: 48px;
        border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 28px;
    }
    .footer-logo { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: var(--gold); letter-spacing: -0.5px; margin-bottom: 10px; }
    .footer-tagline { font-size: 13px; color: rgba(255,255,255,0.32); line-height: 1.7; }
    .footer-col-title { font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.22); margin-bottom: 14px; }
    .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .footer-links li a, .footer-links li { font-size: 13px; color: rgba(255,255,255,0.42); cursor: pointer; transition: color 0.2s; }
    .footer-links li a:hover, .footer-links li:hover { color: var(--gold); }
    .footer-bottom { display: flex; justify-content: space-between; align-items: center; }
    .footer-copy { font-size: 12px; color: rgba(255,255,255,0.18); }
    .footer-badge { font-size: 10px; font-weight: 700; color: var(--gold); border: 1px solid rgba(232,168,56,0.22); border-radius: 999px; padding: 4px 12px; letter-spacing: 1.5px; }

    /* ─── SCROLL REVEALS ─── */
    .reveal { opacity: 0; transform: translateY(22px); transition: opacity 0.55s ease, transform 0.55s ease; }
    .reveal.visible { opacity: 1; transform: none; }
    .d1 { transition-delay: .1s; }
    .d2 { transition-delay: .2s; }
    .d3 { transition-delay: .3s; }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 768px) {
        .nav-inner { padding: 0 20px; }
        .nav-links { display: none; }
        .hero { padding: 100px 24px 60px; }
        .cards-row { grid-template-columns: 1fr; }
        .steps { grid-template-columns: 1fr; }
        .s-grid { grid-template-columns: 1fr; }
        .feat-grid { grid-template-columns: 1fr; }
        .feat-card.highlighted { grid-column: span 1; grid-template-columns: 1fr; }
        .footer-grid { grid-template-columns: 1fr 1fr; }
        .logos-inner { flex-direction: column; align-items: flex-start; gap: 16px; }
        .logos-row { gap: 24px; }
        .stats-row { gap: 28px; }
        .sch-header { flex-direction: column; align-items: flex-start; gap: 16px; }
    }
    </style>
</head>
<body>

<!-- ════════════ NAV ════════════ -->
<nav id="mainNav">
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-mark">🎓</div>
            {{ config('app.name', 'ScholarLink') }}
        </a>
        <ul class="nav-links">
            <li><a href="#scholarships">Browse</a></li>
            <li><a href="#how">How It Works</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#about">About</a></li>
        </ul>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-text">Log In</a>
            <a href="{{ route('login') }}?panel=signup" class="btn-pill">Get Started →</a>
        </div>
    </div>
</nav>


<!-- ════════════ HERO ════════════ -->
<section class="hero" id="home">
    <div class="hero-bg-gradient"></div>

    <!-- Floating decorative icons -->
    <div class="fi" style="top:15%;left:8%;">🎓</div>
    <div class="fi" style="top:22%;right:14%;">📜</div>
    <div class="fi" style="bottom:22%;left:18%;">💡</div>
    <div class="fi" style="bottom:12%;right:18%;">📘</div>

    <div class="hero-badge">
        <span class="badge-dot"></span>
        Now live — Philippines' first AI scholarship platform
    </div>

    <h1 class="hero-title">
        <span id="typing-target"></span>
    </h1>

    <p class="hero-sub">
        Stop repeating yourself. Build your academic profile once and apply to every scholarship in the Philippines — AI-matched, just for you.
    </p>

    <div class="hero-actions">
        <a href="/scholarships" class="btn-hero-main">🎓 Browse Scholarships</a>
        <button class="btn-hero-ghost" onclick="document.getElementById('how').scrollIntoView({behavior:'smooth'})">
            ▶ How it works
        </button>
    </div>

    <div class="hero-social-proof">
        <div class="avatars">
            <div class="avatar">J</div>
            <div class="avatar">M</div>
            <div class="avatar">A</div>
            <div class="avatar">R</div>
            <div class="avatar">+</div>
        </div>
        <p class="proof-text">Joined by <strong>8,400+ students</strong> across the Philippines</p>
    </div>

    <!-- Preview Cards — populated by Blade/JS, can be made dynamic via controller -->
    <div class="hero-visual">
        <div class="cards-row">
            @php
                $previewScholarships = $featuredScholarships ?? [
                    ['org' => 'Gabay Foundation', 'title' => 'Gabay Dunong Scholarship 2025', 'match' => 94, 'status' => 'open'],
                    ['org' => 'Abot-Kaya Inc.',   'title' => 'Abot-Kaya Excellence Grant',    'match' => 87, 'status' => 'open'],
                    ['org' => 'TechBridge Corp.',  'title' => 'TechBridge STEM Scholarship',   'match' => 78, 'status' => 'closing'],
                ];
            @endphp
            @foreach ($previewScholarships as $s)
            <div class="mini-card">
                <div class="mc-top">
                    <span class="mc-org">{{ $s['org'] }}</span>
                    <span class="mc-badge {{ $s['status'] }}">{{ ucfirst($s['status']) }}</span>
                </div>
                <div class="mc-title">{{ $s['title'] }}</div>
                <div class="mc-pct">{{ $s['match'] }}%</div>
                <div class="mc-pct-label">Match Score</div>
                <div class="mc-bar"><div class="mc-bar-fill" style="width:{{ $s['match'] }}%"></div></div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ════════════ LOGOS ════════════ -->
<div class="logos">
    <div class="logos-inner">
        <span class="logos-label">Trusted by</span>
        <div class="logos-row">
            @php
                $partners = $trustedPartners ?? ['Gabay Foundation', 'Abot-Kaya Inc.', 'TechBridge Corp.', 'Lumina Grants', 'PH Merit Fund'];
            @endphp
            @foreach ($partners as $partner)
                <span class="logo-item">{{ $partner }}</span>
            @endforeach
        </div>
    </div>
</div>


<!-- ════════════ HOW IT WORKS ════════════ -->
<section class="how" id="how">
    <div class="how-inner">
        <div class="section-eyebrow reveal">How It Works</div>
        <h2 class="section-title reveal">From profile to scholarship —<br><em>simplified.</em></h2>
        <div class="steps">
            @php
                $steps = $howItWorksSteps ?? [
                    ['num' => '01', 'title' => 'Build Your Profile',    'desc' => 'Create your academic identity once — GPA, income bracket, course, and documents. Your Student Wallet stores everything securely.'],
                    ['num' => '02', 'title' => 'Get Matched by AI',     'desc' => 'Our AI analyzes your profile and shows your match percentage for every scholarship — apply only where you actually qualify.'],
                    ['num' => '03', 'title' => 'Track in Real-Time',    'desc' => 'Follow every stage of your application. Get notified via in-app, email, or SMS — even with limited internet access.'],
                ];
            @endphp
            @foreach ($steps as $step)
            <div class="step reveal d{{ $loop->index + 1 }}">
                <div class="step-num">{{ $step['num'] }}</div>
                <div class="step-title">{{ $step['title'] }}</div>
                <div class="step-desc">{{ $step['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ════════════ SCHOLARSHIPS ════════════ -->
<section class="scholarships" id="scholarships">
    <div class="scholarships-inner">
        <div class="sch-header reveal">
            <div>
                <div class="section-eyebrow">Featured Scholarships</div>
                <h2 class="section-title" style="margin-bottom:0;font-size:clamp(28px,3vw,42px);">Find yours today.</h2>
            </div>
            <div class="filters">
                <button class="filter active" data-filter="all">All</button>
                <button class="filter" data-filter="merit">Merit-Based</button>
                <button class="filter" data-filter="need">Need-Based</button>
                <button class="filter" data-filter="stem">STEM</button>
                <button class="filter" data-filter="arts">Arts</button>
            </div>
        </div>
        <div class="s-grid">
            @php
                $scholarships = $scholarships ?? [
                    ['org' => 'Gabay Foundation', 'title' => 'Gabay Dunong Scholarship 2025',  'status' => 'open',    'match' => 94, 'details' => ['GPA 1.75 or higher','Family income ≤ ₱250,000/yr','50 available slots']],
                    ['org' => 'Abot-Kaya Inc.',   'title' => 'Abot-Kaya Excellence Grant',     'status' => 'open',    'match' => 87, 'details' => ['GPA 1.50 or higher','Open income bracket','30 available slots']],
                    ['org' => 'TechBridge Corp.', 'title' => 'TechBridge STEM Scholarship',    'status' => 'closing', 'match' => 78, 'details' => ['GPA 2.00 or higher','STEM course only','40 available slots']],
                ];
            @endphp
            @foreach ($scholarships as $sch)
            <div class="s-card reveal d{{ $loop->index + 1 }}">
                <div class="sc-top">
                    <span class="sc-org-name">{{ $sch['org'] }}</span>
                    <span class="sc-status {{ $sch['status'] }}">{{ ucfirst($sch['status']) }}</span>
                </div>
                <div class="sc-title">{{ $sch['title'] }}</div>
                <div class="sc-details">
                    @foreach ($sch['details'] as $detail)
                    <div class="sc-detail"><span class="sc-dot"></span>{{ $detail }}</div>
                    @endforeach
                </div>
                <div class="sc-match">
                    <div class="sc-match-row">
                        <span class="sc-match-label">Your Match Score</span>
                        <span class="sc-match-pct">{{ $sch['match'] }}%</span>
                    </div>
                    <div class="sc-bar-bg"><div class="sc-bar-fill" style="width:{{ $sch['match'] }}%"></div></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ════════════ FEATURES ════════════ -->
<section class="features" id="features">
    <div class="features-inner">
        <div class="section-eyebrow reveal">Platform Features</div>
        <h2 class="section-title reveal">Built for fairness,<br>accessibility, <em>and you.</em></h2>
        <div class="feat-grid">

            <!-- Highlighted card — AI Matching -->
            <div class="feat-card highlighted reveal">
                <div>
                    <div class="feat-icon">🤖</div>
                    <div class="feat-title">AI-Powered Scholarship Matching</div>
                    <div class="feat-desc">Our engine analyzes your GPA, income, course, and location — then ranks every scholarship by how likely you are to qualify. Stop applying blindly.</div>
                </div>
                <div class="feat-right">
                    <div class="feat-right-item"><span class="fri-icon">⚡</span><div><div class="fri-title">Instant Match Calculation</div><div class="fri-sub">Results in under 2 seconds</div></div></div>
                    <div class="feat-right-item"><span class="fri-icon">🎯</span><div><div class="fri-title">86% Average Accuracy</div><div class="fri-sub">Powered by Gemini AI</div></div></div>
                    <div class="feat-right-item"><span class="fri-icon">🔄</span><div><div class="fri-title">Auto-Updates</div><div class="fri-sub">Re-matches as you update profile</div></div></div>
                </div>
            </div>

            @php
                $features = $platformFeatures ?? [
                    ['icon' => '🙈', 'title' => 'Blind Screening',           'desc' => 'Evaluators review without seeing your name, gender, or school — promoting merit-based, bias-free selection.'],
                    ['icon' => '⚖️', 'title' => 'Dynamic Weighted Scoring',   'desc' => 'Organizations customize GPA vs. financial need weighting — every scholarship plays by its own fair rules.'],
                    ['icon' => '📡', 'title' => 'SMS Hardware Gateway',       'desc' => 'Critical updates via SMS even without internet — powered by ESP32 + GSM. No student left behind.'],
                    ['icon' => '🗂️', 'title' => 'Student Document Wallet',   'desc' => 'Upload your documents once. Use them for every application. No more re-scanning the same transcripts.'],
                ];
            @endphp
            @foreach ($features as $feat)
            <div class="feat-card reveal d{{ $loop->index % 2 + 1 }}">
                <div class="feat-icon">{{ $feat['icon'] }}</div>
                <div class="feat-title">{{ $feat['title'] }}</div>
                <div class="feat-desc">{{ $feat['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ════════════ CTA ════════════ -->
<section class="cta" id="about">
    <div class="cta-inner">
        <div class="cta-badge">✨ Free for all Filipino students</div>
        <h2 class="cta-title reveal">Your scholarship<br>is <em>waiting for you.</em></h2>
        <p class="cta-sub reveal">Join thousands of Filipino students who found their funding through ScholarLink.</p>
        <div class="cta-btns reveal">
            <a href="{{ route('login') }}?panel=signup" class="btn-cta-main">🎓 Create Free Account</a>
            <a href="#scholarships" class="btn-cta-sec">Browse Scholarships →</a>
        </div>
        <div class="stats-row reveal">
            @php
                $stats = $platformStats ?? [
                    ['num' => '120+',  'label' => 'Active Scholarships'],
                    ['num' => '8,400', 'label' => 'Students Helped'],
                    ['num' => '₱2.1M', 'label' => 'Grants Facilitated'],
                ];
            @endphp
            @foreach ($stats as $stat)
            <div class="stat">
                <div class="stat-num">{{ $stat['num'] }}</div>
                <div class="stat-label">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ════════════ FOOTER ════════════ -->
<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">{{ config('app.name', 'ScholarLink') }}</div>
                <div class="footer-tagline">Bridging Filipino students to scholarship opportunities — one profile, every scholarship.</div>
            </div>
            <div>
                <div class="footer-col-title">Platform</div>
                <ul class="footer-links">
                    <li><a href="#scholarships">Browse</a></li>
                    <li><a href="#how">How It Works</a></li>
                    <li><a href="#features">For Organizations</a></li>
                    <li><a href="#features">AI Matching</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Account</div>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}?panel=signup">Sign Up</a></li>
                    <li><a href="{{ route('login') }}">Log In</a></li>
                    <li><a href="#">My Applications</a></li>
                    <li><a href="#">Document Wallet</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Legal</div>
                <ul class="footer-links">
                    <li><a href="{{ Route::has('privacy') ? route('privacy') : '#' }}">Privacy Policy</a></li>
                    <li><a href="{{ Route::has('terms')   ? route('terms')   : '#' }}">Terms of Service</a></li>
                    <li><a href="#">Data Privacy Act</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">© {{ date('Y') }} {{ config('app.name', 'ScholarLink') }}. Philippines 🇵🇭</div>
            <div class="footer-badge">SOFTWARE DESIGN PROJECT</div>
        </div>
    </div>
</footer>


<script>
(function () {
    'use strict';

    // ── Nav scroll shadow ──
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });

    // ── Filter buttons ──
    document.querySelectorAll('.filter').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            // TODO: wire to actual scholarship filter when backend is connected
        });
    });

    // ── Scroll reveal ──
    const obs = new IntersectionObserver(entries => {
        entries.forEach(el => {
            if (el.isIntersecting) { el.target.classList.add('visible'); obs.unobserve(el.target); }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    // ── Hero typewriter ──
    const phrases = [
        "One Profile.\nEvery Scholarship.",
        "Your Future\nStarts Here.",
        "AI-Matched.\nPH-Built.",
    ];
    const target = document.getElementById('typing-target');
    let phraseIdx = 0, charIdx = 0, deleting = false;

    function typeWriter() {
        const phrase = phrases[phraseIdx];
        const displayed = phrase.substring(0, charIdx);
        // render newlines as <br>
        target.innerHTML = displayed.replace(/\n/g, '<br>');

        if (!deleting && charIdx < phrase.length) {
            charIdx++;
            setTimeout(typeWriter, 80);
        } else if (!deleting && charIdx === phrase.length) {
            setTimeout(() => { deleting = true; typeWriter(); }, 3200);
        } else if (deleting && charIdx > 0) {
            charIdx--;
            setTimeout(typeWriter, 40);
        } else {
            deleting = false;
            phraseIdx = (phraseIdx + 1) % phrases.length;
            setTimeout(typeWriter, 400);
        }
    }
    typeWriter();

})();
</script>

</body>
</html>

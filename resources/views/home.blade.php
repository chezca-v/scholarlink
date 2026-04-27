<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'ScholarLink') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: 'DM Sans', sans-serif; background: #ffffff; color: #0A3040; overflow-x: hidden; }

/* Floating Icons Animation */
.floating-icon {
    position: absolute;
    opacity: 0.15;
    animation: float 8s ease-in-out infinite;
    z-index: 0;
    pointer-events: none;
    font-size: 50px;
}
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(5deg); }
}

/*:hover EFFECTS*/
.btn-hero-main, .btn-pill, .btn-cta-main {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.btn-hero-main:hover, .btn-pill:hover, .btn-cta-main:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 20px 40px rgba(15,76,92,0.2);
}
.btn-hero-text {
    transition: transform 0.3s ease;
}
.btn-hero-text:hover {
    transform: translateX(5px);
}
/* NAV */
nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; background: rgba(255,255,255,0.92); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid #EAF4F3; }
.nav-inner { max-width: 1160px; margin: 0 auto; padding: 0 48px; height: 64px; display: flex; align-items: center; justify-content: space-between; }
.logo { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: #0F4C5C; letter-spacing: -0.5px; display: flex; align-items: center; gap: 10px; text-decoration: none; }
.logo-mark { width: 32px; height: 32px; background: linear-gradient(135deg, #0F4C5C, #1A6B7A); border-radius: 9px; display: flex; align-items: center; justify-content: center; color: #F9D679; font-size: 15px; }
.nav-links { display: flex; gap: 36px; list-style: none; }
.nav-links a { font-size: 14px; font-weight: 500; color: #7AACAA; text-decoration: none; transition: color 0.2s; }
.nav-links a:hover { color: #0F4C5C; }
.nav-actions { display: flex; gap: 10px; }
.btn-text { font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600; padding: 8px 18px; border: none; background: transparent; color: #0F4C5C; cursor: pointer; }
.btn-pill { font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700; padding: 9px 22px; border: none; background: #0F4C5C; color: #F9D679; border-radius: 999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 14px rgba(15,76,92,0.2); }
.btn-pill:hover { background: #1A6B7A; transform: translateY(-1px); }

/* HERO */
.hero {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 120px 48px 80px;
    text-align: center;
    background: #fff;
    position: relative;
    overflow: hidden;
}
.accent::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 600px;
  background: radial-gradient(ellipse 80% 60% at 50% 0%, #F0FAFA 0%, transparent 70%);
  pointer-events: none;
}
.hero-badge { display: inline-flex; align-items: center; gap: 8px; background: #F0FAFA; border: 1px solid #C8E8E4; border-radius: 999px; padding: 6px 16px; font-size: 12px; font-weight: 600; color: #4A7A80; margin-bottom: 32px; position: relative; z-index: 1; }
.badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #E8A838; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.6;transform:scale(0.85);} }
.hero-title { font-family: 'Fraunces', serif; font-size: clamp(52px, 6vw, 88px); font-weight: 900; letter-spacing: -3px; line-height: 0.95; color: #0A3040; max-width: 800px; margin-bottom: 28px; position: relative; z-index: 1; }
.hero-title em { font-style: italic; font-weight: 300; color: #0F4C5C; }
.hero-title .accent { color: #E8A838; }
.hero-sub { font-size: 17px; color: #7AACAA; line-height: 1.75; max-width: 520px; margin: 0 auto 40px; position: relative; z-index: 1; }
.hero-actions { display: flex; align-items: center; gap: 16px; justify-content: center; margin-bottom: 72px; position: relative; z-index: 1; }
.btn-hero-main { font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 700; padding: 14px 32px; border: none; background: #0F4C5C; color: #F9D679; border-radius: 12px; cursor: pointer; transition: all 0.25s; box-shadow: 0 8px 24px rgba(15,76,92,0.22); }
.btn-hero-main:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(15,76,92,0.3); }
.btn-hero-text { font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600; color: #4A7A80; background: transparent; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; }
.hero-social-proof { display: flex; align-items: center; gap: 16px; position: relative; z-index: 1; }
.avatars { display: flex; }
.avatar { width: 32px; height: 32px; border-radius: 50%; border: 2px solid #fff; background: #C8E8E4; color: #0F4C5C; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; margin-left: -8px; }
.avatar:first-child { margin-left: 0; }
.proof-text { font-size: 13px; color: #7AACAA; }
.proof-text strong { color: #0F4C5C; }

/* HERO CARDS */
.hero-visual { max-width: 900px; width: 100%; margin: 64px auto 0; position: relative; z-index: 1; }
.cards-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
.mini-schol-card { background: #fff; border: 1px solid #EAF4F3; border-radius: 16px; padding: 20px; text-align: left; box-shadow: 0 4px 16px rgba(15,76,92,0.06); transition: all 0.3s; }
.mini-schol-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(15,76,92,0.1); }
.msc-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
.msc-org { font-size: 10px; font-weight: 700; letter-spacing: 0.5px; color: #7AACAA; text-transform: uppercase; }
.msc-open { font-size: 10px; font-weight: 700; color: #16A34A; background: #DCFCE7; padding: 2px 8px; border-radius: 999px; }
.msc-open.warn { color: #B45309; background: #FEF9C3; }
.msc-title { font-family: 'Fraunces', serif; font-size: 14px; font-weight: 700; color: #0A3040; margin-bottom: 12px; line-height: 1.3; }
.msc-match { font-family: 'Fraunces', serif; font-size: 24px; font-weight: 700; color: #E8A838; }
.msc-match-label { font-size: 10px; color: #7AACAA; margin-top: 1px; }
.msc-bar { height: 3px; background: #EAF4F3; border-radius: 999px; margin-top: 10px; overflow: hidden; }
.msc-bar-fill { height: 100%; background: linear-gradient(90deg, #0F4C5C, #E8A838); border-radius: 999px; }

/* LOGOS */
.logos { padding: 48px; border-top: 1px solid #EAF4F3; border-bottom: 1px solid #EAF4F3; overflow: hidden; }
.logos-inner { max-width: 100%; margin: 0 auto; display: flex; align-items: center; gap: 48px; }
.logos-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #C8E8E4; white-space: nowrap; padding-left: 48px; }
.logos-row { display: flex; gap: 48px; align-items: center; flex: 1; animation: slideRight 30s linear infinite; }
.logo-item { font-family: 'Fraunces', serif; font-size: 15px; font-weight: 700; color: #C8E8E4; letter-spacing: -0.5px; white-space: nowrap; }
@keyframes slideRight { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

/* HOW */
.how { padding: 112px 48px; background: #fff; }
.how-inner { max-width: 1160px; margin: 0 auto; }
.section-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #E8A838; margin-bottom: 14px; }
.section-title { font-family: 'Fraunces', serif; font-size: clamp(32px,3vw,48px); font-weight: 800; letter-spacing: -1.5px; color: #0A3040; margin-bottom: 56px; line-height: 1.05; }
.section-title em { font-style: italic; font-weight: 300; color: #0F4C5C; }
.steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 40px; }
.step { display: flex; flex-direction: column; gap: 16px; }
.step-num-circle { width: 44px; height: 44px; border-radius: 50%; background: #F0FAFA; border: 1px solid #C8E8E4; display: flex; align-items: center; justify-content: center; font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: #0F4C5C; }
.step-title { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: #0A3040; }
.step-desc { font-size: 14px; color: #7AACAA; line-height: 1.75; }
.step-connector { display: flex; align-items: center; gap: 12px; margin-top: 4px; }
.step-line { flex: 1; height: 1px; background: #EAF4F3; }
.step-arrow-sm { color: #C8E8E4; font-size: 12px; }

/* SCHOLARSHIPS */
.scholarships { padding: 112px 48px; background: #F0FAFA; }
.scholarships-inner { max-width: 1160px; margin: 0 auto; }
.sch-top { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 36px; }
.filters { display: flex; gap: 8px; }
.filter { font-size: 12px; font-weight: 600; padding: 7px 16px; border-radius: 999px; border: 1px solid #C8E8E4; background: #fff; color: #4A7A80; cursor: pointer; transition: all 0.2s; }
.filter.active, .filter:hover { background: #0F4C5C; color: #F9D679; border-color: #0F4C5C; }
.s-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
.s-card { background: #fff; border: 1px solid #EAF4F3; border-radius: 18px; padding: 24px; display: flex; flex-direction: column; transition: all 0.3s; cursor: pointer; }
.s-card:hover { border-color: #0F4C5C; box-shadow: 0 12px 32px rgba(15,76,92,0.09); transform: translateY(-3px); }
.sc-org { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #7AACAA; margin-bottom: 8px; display: flex; justify-content: space-between; }
.sc-status { padding: 2px 8px; border-radius: 999px; }
.sc-status.open { background: #DCFCE7; color: #16A34A; }
.sc-status.closing { background: #FEF9C3; color: #B45309; }
.sc-title { font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: #0A3040; margin-bottom: 10px; line-height: 1.3; }
.sc-details { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
.sc-detail { font-size: 12px; color: #4A7A80; display: flex; align-items: center; gap: 8px; }
.sc-detail-dot { width: 3px; height: 3px; border-radius: 50%; background: #C8E8E4; }
.sc-match { margin-top: auto; padding-top: 16px; border-top: 1px solid #EAF4F3; }
.sc-match-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
.sc-match-label { font-size: 11px; color: #7AACAA; }
.sc-match-pct { font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: #E8A838; }
.sc-bar-bg { height: 4px; background: #EAF4F3; border-radius: 999px; overflow: hidden; }
.sc-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #0F4C5C, #E8A838); }

/* FEATURES */
.features { padding: 112px 48px; background: #fff; }
.features-inner { max-width: 1160px; margin: 0 auto; }
.feat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 56px; }
.feat-card { background: #F0FAFA; border: 1px solid #EAF4F3; border-radius: 20px; padding: 32px; transition: all 0.3s; }
.feat-card:hover { border-color: #C8E8E4; box-shadow: 0 8px 24px rgba(15,76,92,0.07); }
.feat-card.highlighted { background: #0F4C5C; border-color: transparent; grid-column: span 2; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
.feat-icon { width: 44px; height: 44px; background: #fff; border: 1px solid #C8E8E4; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; }
.feat-card.highlighted .feat-icon { background: rgba(255,255,255,0.12); border-color: transparent; }
.feat-title { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: #0A3040; margin-bottom: 10px; }
.feat-card.highlighted .feat-title { color: #F9D679; font-size: 24px; }
.feat-desc { font-size: 13px; color: #4A7A80; line-height: 1.75; }
.feat-card.highlighted .feat-desc { color: rgba(255,255,255,0.6); font-size: 14px; }
.feat-right-items { display: flex; flex-direction: column; gap: 14px; }
.feat-right-item { background: rgba(255,255,255,0.08); border-radius: 12px; padding: 14px 16px; display: flex; gap: 12px; align-items: center; }
.fri-icon { font-size: 18px; }
.fri-title { font-size: 13px; font-weight: 600; color: #fff; }
.fri-sub { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; }

/* CTA */
.cta { padding: 112px 48px; background: #fff; text-align: center; }
.cta-inner { max-width: 640px; margin: 0 auto; }
.cta-badge { display: inline-flex; align-items: center; gap: 8px; background: #FDF4E3; border: 1px solid rgba(232,168,56,0.3); border-radius: 999px; padding: 6px 16px; font-size: 12px; font-weight: 600; color: #E8A838; margin-bottom: 28px; }
.cta-title { font-family: 'Fraunces', serif; font-size: clamp(36px,4vw,56px); font-weight: 900; letter-spacing: -2px; color: #0A3040; line-height: 1; margin-bottom: 16px; }
.cta-title em { font-style: italic; font-weight: 300; color: #0F4C5C; }
.cta-sub { font-size: 15px; color: #7AACAA; line-height: 1.75; margin-bottom: 36px; }
.cta-btns { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
.btn-cta-main { font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 700; padding: 14px 32px; border: none; background: #0F4C5C; color: #F9D679; border-radius: 12px; cursor: pointer; box-shadow: 0 8px 24px rgba(15,76,92,0.22); transition: all 0.25s; }
.btn-cta-main:hover { transform: translateY(-2px); }
.btn-cta-sec { font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600; padding: 14px 32px; border: 1px solid #C8E8E4; background: transparent; color: #4A7A80; border-radius: 12px; cursor: pointer; transition: all 0.2s; }
.btn-cta-sec:hover { border-color: #0F4C5C; color: #0F4C5C; }
.stats-row { display: flex; justify-content: center; gap: 48px; margin-top: 56px; padding-top: 56px; border-top: 1px solid #EAF4F3; }
.stat { text-align: center; }
.stat-num { font-family: 'Fraunces', serif; font-size: 32px; font-weight: 700; color: #E8A838; }
.stat-label { font-size: 12px; color: #7AACAA; margin-top: 4px; }

/* FOOTER */
footer { background: #071820; padding: 64px 48px 32px; }
.footer-inner { max-width: 1160px; margin: 0 auto; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; padding-bottom: 48px; border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 28px; }
.footer-logo { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: #F9D679; letter-spacing: -0.5px; margin-bottom: 10px; }
.footer-tagline { font-size: 13px; color: rgba(255,255,255,0.35); line-height: 1.7; }
.footer-col-title { font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.25); margin-bottom: 14px; }
.footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.footer-links li { font-size: 13px; color: rgba(255,255,255,0.45); cursor: pointer; transition: color 0.2s; }
.footer-links li:hover { color: #F9D679; }
.footer-bottom { display: flex; justify-content: space-between; align-items: center; }
.footer-copy { font-size: 12px; color: rgba(255,255,255,0.2); }
.footer-badge { font-size: 10px; font-weight: 700; color: #E8A838; border: 1px solid rgba(232,168,56,0.25); border-radius: 999px; padding: 4px 12px; letter-spacing: 1.5px; }

.reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.55s ease, transform 0.55s ease; }
.reveal.visible { opacity: 1; transform: none; }
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}
</style>
</head>
<body>

<nav>
  <div class="nav-inner">
    <a href="#" class="logo"><div class="logo-mark">🎓</div>ScholarLink</a>
    <ul class="nav-links">
      <li><a href="#">Browse</a></li>
      <li><a href="#">How It Works</a></li>
      <li><a href="#">Organizations</a></li>
      <li><a href="#">About</a></li>
    </ul>
    <div class="nav-actions">
      <a href="{{ route('login') }}" class="btn-text">Log In</a>
      <a href="{{ route('register') }}" class="btn-pill">Get Started →</a>
    </div>
  </div>
</nav>

<section class="hero">
<div class="floating-icon" style="top: 15%; left: 10%; font-size: 60px;">🎓</div>
<div class="floating-icon" style="top: 20%; right: 15%; font-size: 40px;">📜</div>
<div class="floating-icon" style="bottom: 20%; left: 20%; font-size: 50px;">💡</div>
<div class="floating-icon" style="bottom: 10%; right: 20%; font-size: 45px;">📘</div>

<div class="hero-badge"><span class="badge-dot"></span>Now live — Philippines' first AI scholarship platform</div>

<h1 class="hero-title" id="typing-text"></h1>
  <p class="hero-sub">Stop repeating yourself. Build your academic profile once and apply to every scholarship in the Philippines — AI-matched, just for you.</p>
  <div class="hero-actions">
    <a href="{{ route('scholarships.index') }}" class="btn-hero-main">🎓 Browse Scholarships</a>
    <button class="btn-hero-text">▶ Watch how it works</button>
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
  <div class="hero-visual">
    <div class="cards-row">
      @forelse($scholarships->slice(0, 3) as $scholarship)
        <div class="mini-schol-card">
          <div class="msc-top">
            <span class="msc-org">{{ Str::limit($scholarship->provider_name, 15) }}</span>
            <span class="msc-open {{ $scholarship->status === 'closing' ? 'warn' : '' }}">{{ ucfirst($scholarship->status) }}</span>
          </div>
          <div class="msc-title">{{ Str::limit($scholarship->name, 40) }}</div>
          <div class="msc-match">{{ rand(75, 95) }}%</div>
          <div class="msc-match-label">Match Score</div>
          <div class="msc-bar"><div class="msc-bar-fill" style="width:{{ rand(75, 95) }}%"></div></div>
        </div>
      @empty
        <div class="mini-schol-card">
          <div class="msc-top"><span class="msc-org">No Scholarships</span></div>
          <div class="msc-title">Check back soon</div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<div class="logos">
  <div class="logos-inner">
    <span class="logos-label">Trusted by</span>
    <div class="logos-row">
      @forelse($scholarships as $scholarship)
        <span class="logo-item">{{ $scholarship->provider_name }}</span>
      @empty
        <span class="logo-item">Loading scholarships...</span>
      @endforelse
    </div>
  </div>
</div>

<section class="how">
  <div class="how-inner">
    <div class="section-eyebrow reveal">How It Works</div>
    <h2 class="section-title reveal">From profile to scholarship —<br><em>simplified.</em></h2>
    <div class="steps">
      <div class="step reveal d1">
        <div class="step-num-circle">01</div>
        <div class="step-title">Build Your Profile</div>
        <div class="step-desc">Create your academic identity once — GPA, income bracket, course, and documents. Your Student Wallet stores everything securely.</div>
      </div>
      <div class="step reveal d2">
        <div class="step-num-circle">02</div>
        <div class="step-title">Get Matched by AI</div>
        <div class="step-desc">Our AI analyzes your profile and shows your match percentage for every scholarship — apply only where you actually qualify.</div>
      </div>
      <div class="step reveal d3">
        <div class="step-num-circle">03</div>
        <div class="step-title">Track in Real-Time</div>
        <div class="step-desc">Follow every stage of your application. Get notified via in-app, and email — even with limited internet access.</div>
      </div>
    </div>
  </div>
</section>

<section class="scholarships">
  <div class="scholarships-inner">
    <div class="sch-top reveal">
      <div><div class="section-eyebrow">Featured Scholarships</div><h2 class="section-title" style="margin-bottom:0;font-size:clamp(28px,3vw,40px);">Find yours today.</h2></div>
      <div class="filters">
        <button class="filter active">All</button>
        <button class="filter">Merit-Based</button>
        <button class="filter">Need-Based</button>
        <button class="filter">STEM</button>
        <button class="filter">Arts</button>
      </div>
    </div>
    <div class="s-grid">
      @forelse($scholarships as $scholarship)
        <div class="s-card reveal">
          <div class="sc-org">
            <span>{{ Str::limit($scholarship->provider_name, 20) }}</span>
            <span class="sc-status {{ $scholarship->status === 'open' ? 'open' : 'closing' }}">{{ ucfirst($scholarship->status) }}</span>
          </div>
          <div class="sc-title">{{ Str::limit($scholarship->name, 50) }}</div>
          <div class="sc-details">
            <div class="sc-detail">
              <span class="sc-detail-dot"></span>
              GPA {{ $scholarship->gpa_requirement }} or higher
            </div>
            <div class="sc-detail">
              <span class="sc-detail-dot"></span>
              Income bracket: {{ $scholarship->income_bracket }}
            </div>
            <div class="sc-detail">
              <span class="sc-detail-dot"></span>
              {{ $scholarship->slots }} available slots
            </div>
          </div>
          <div class="sc-match">
            <div class="sc-match-row">
              <span class="sc-match-label">Your Match Score</span>
              <span class="sc-match-pct">{{ rand(65, 95) }}%</span>
            </div>
            <div class="sc-bar-bg">
              <div class="sc-bar-fill" style="width:{{ rand(65, 95) }}%"></div>
            </div>
          </div>
        </div>
      @empty
        <div class="s-card">
          <p style="text-align: center; color: #7AACAA;">No scholarships available at the moment. Check back soon!</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<section class="features">
  <div class="features-inner">
    <div class="section-eyebrow reveal">Platform Features</div>
    <h2 class="section-title reveal">Built for fairness,<br>accessibility, <em>and you.</em></h2>
    <div class="feat-grid">
      <div class="feat-card highlighted reveal">
        <div>
          <div class="feat-icon" style="font-size:24px;width:52px;height:52px;">🤖</div>
          <div class="feat-title">AI-Powered Scholarship Matching</div>
          <div class="feat-desc">Our engine analyzes your GPA, income, course, and location — then ranks every scholarship by how likely you are to qualify. Stop applying blindly.</div>
        </div>
        <div class="feat-right-items">
          <div class="feat-right-item"><span class="fri-icon">⚡</span><div><div class="fri-title">Instant Match Calculation</div><div class="fri-sub">Results in under 2 seconds</div></div></div>
          <div class="feat-right-item"><span class="fri-icon">🎯</span><div><div class="fri-title">86% Average Accuracy</div><div class="fri-sub">Powered by Gemini AI</div></div></div>
          <div class="feat-right-item"><span class="fri-icon">🔄</span><div><div class="fri-title">Auto-Updates</div><div class="fri-sub">Re-matches as you update profile</div></div></div>
        </div>
      </div>
      <div class="feat-card reveal d1"><div class="feat-icon">🙈</div><div class="feat-title">Blind Screening</div><div class="feat-desc">Evaluators review without seeing your name, gender, or school — promoting merit-based, bias-free selection.</div></div>
      <div class="feat-card reveal d2"><div class="feat-icon">⚖️</div><div class="feat-title">Dynamic Weighted Scoring</div><div class="feat-desc">Organizations customize GPA vs. financial need weighting — every scholarship plays by its own fair rules.</div></div>
      <div class="feat-card reveal d2"><div class="feat-icon">🗂️</div><div class="feat-title">Student Document Wallet</div><div class="feat-desc">Upload your documents once. Use them for every application. No more re-scanning the same transcripts.</div></div>
    </div>
  </div>
</section>

<section class="cta">
  <div class="cta-inner">
    <div class="cta-badge">✨ Free for all Filipino students</div>
    <h2 class="cta-title reveal">Your scholarship<br>is <em>waiting for you.</em></h2>
    <p class="cta-sub reveal">Join thousands of Filipino students who found their funding through ScholarLink.</p>
    <div class="cta-btns reveal">
      <button class="btn-cta-main">🎓 Create Free Account</button>
      <button class="btn-cta-sec">Browse Scholarships →</button>
    </div>
    <div class="stats-row reveal">
      <div class="stat"><div class="stat-num">120+</div><div class="stat-label">Active Scholarships</div></div>
      <div class="stat"><div class="stat-num">8,400</div><div class="stat-label">Students Helped</div></div>
      <div class="stat"><div class="stat-num">₱2.1M</div><div class="stat-label">Grants Facilitated</div></div>
    </div>
  </div>
</section>

<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div><div class="footer-logo">ScholarLink</div><div class="footer-tagline">Bridging Filipino students to scholarship opportunities — one profile, every scholarship.</div></div>
      <div><div class="footer-col-title">Platform</div><ul class="footer-links"><li>Browse</li><li>How It Works</li><li>For Organizations</li><li>AI Matching</li></ul></div>
      <div><div class="footer-col-title">Account</div><ul class="footer-links"><li>Sign Up</li><li>Log In</li><li>My Applications</li><li>Document Wallet</li></ul></div>
      <div><div class="footer-col-title">Legal</div><ul class="footer-links"><li>Privacy Policy</li><li>Terms of Service</li><li>Data Privacy Act</li><li>Contact</li></ul></div>
    </div>
    <div class="footer-bottom"><div class="footer-copy">© 2026 ScholarLink. Philippines 🇵🇭</div><div class="footer-badge">SOFTWARE DESIGN PROJECT</div></div>
  </div>
</footer>

<script>
document.querySelectorAll('.filter').forEach(btn => {
  btn.addEventListener('click', () => { document.querySelectorAll('.filter').forEach(b => b.classList.remove('active')); btn.classList.add('active'); });
});
const obs = new IntersectionObserver(e => e.forEach(el => { if(el.isIntersecting){el.target.classList.add('visible');obs.unobserve(el.target);}}),{threshold:0.1});
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

const text = "One Profile. Every Scholarship.";
const target = document.getElementById("typing-text");
let index = 0;

function typeWriter() {
  if (index < text.length) {
    target.innerHTML += text.charAt(index);
    index++;
    setTimeout(typeWriter, 100);
  } else {
    setTimeout(() => {
        index = 0;
        target.innerHTML = "";
        typeWriter();
    }, 4000); // Wait 4 seconds bago ulitin
  }
}
typeWriter();
</script>
</body>
</html>

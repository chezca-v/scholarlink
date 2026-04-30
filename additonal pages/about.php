<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us — ScholarLink</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,300;1,9..144,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --teal: #0F4C5C; --teal-light: #1A6B7A; --teal-lighter: #2A8FA0;
      --amber: #E8A838; --gold: #C9A84C; --gold-light: #F9D679;
      --white: #FFFFFF; --cloud: #F4F6FA; --mist: #E2E8F0;
      --slate: #8A95A3; --ink: #1C1C2E;
      --grad-primary: linear-gradient(135deg,#0F4C5C,#1A6B7A);
      --grad-amber: linear-gradient(135deg,#E8A838,#F9D679);
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);line-height:1.6}

    /* ── NAV ── */
    nav{
      position:fixed;top:16px;left:20px;right:20px;height:60px;
      display:flex;align-items:center;padding:0 40px;border-radius:12px;
      background:linear-gradient(135deg,#0F4C5C,#1A6B7A);
      box-shadow:0 8px 28px rgba(0,0,0,.15);z-index:100;
      transition:background .35s ease,box-shadow .35s ease,border .35s ease;
      border:1.5px solid transparent;
    }
  nav.scrolled {
  background: linear-gradient(135deg,#0F4C5C,#1A6B7A) !important;
  box-shadow: 0 8px 28px rgba(0,0,0,.15) !important;
  border: 1.5px solid transparent !important;
}
    .nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none}
    .nav-logo-icon{width:34px;height:34px}
    .nav-logo-icon img{width:10rem;height:auto;max-width:100%}
    .nav-logo-text{font-family:'Fraunces',serif;font-size:21px;font-weight:700;color:var(--white);letter-spacing:-.3px;transition:color .35s}
nav.scrolled .nav-logo-text { color: #FFFFFF !important; }    .nav-links{margin-left:auto;display:flex;gap:32px;list-style:none;align-items:center}
    .nav-links a{color:rgba(255,255,255,.82);text-decoration:none;font-size:14px;font-weight:500;transition:color .2s;position:relative;padding-bottom:2px}
nav.scrolled .nav-links a { color: rgba(255,255,255,.82) !important; }    .nav-links a::after{content:'';position:absolute;bottom:-2px;left:0;right:0;height:2px;background:var(--grad-amber);border-radius:2px;transform:scaleX(0);transform-origin:left;transition:transform .25s}
    .nav-links a:hover::after,.nav-links a.active::after{transform:scaleX(1)}
    .nav-links a:hover{color:var(--white)}
nav.scrolled .nav-links a:hover { color: #FFFFFF !important; }    .nav-links a.active{color:var(--gold-light)}
nav.scrolled .nav-links a.active { color: #F9D679 !important; }
    /* ── HERO — editorial split layout ── */
    .hero{
      min-height:92vh;background:var(--ink);
      display:grid;grid-template-columns:1fr 1fr;
      position:relative;overflow:hidden;padding-top:92px;
    }
    .hero-left{
      padding:80px 60px 80px 80px;display:flex;flex-direction:column;
      justify-content:center;position:relative;z-index:2;
    }
    .hero-right{
      background:linear-gradient(160deg,#0F4C5C 0%,#2A8FA0 60%,#C9A84C 100%);
      position:relative;overflow:hidden;
      display:flex;align-items:center;justify-content:center;
    }
    .hero-right::before{
      content:'';position:absolute;inset:0;
      background:radial-gradient(ellipse 70% 70% at 30% 40%,rgba(249,214,121,.15) 0%,transparent 70%),
                 radial-gradient(ellipse 50% 50% at 80% 80%,rgba(15,76,92,.4) 0%,transparent 60%);
    }
    /* decorative grid */
    .hero-right::after{
      content:'';position:absolute;inset:0;
      background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),
                       linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);
      background-size:40px 40px;
    }
    .hero-badge{
      display:inline-flex;align-items:center;gap:8px;
      background:rgba(201,168,76,.18);border:1px solid rgba(201,168,76,.4);
      color:#F9D679;font-size:11px;font-weight:600;letter-spacing:1.4px;
      text-transform:uppercase;padding:6px 16px;border-radius:100px;margin-bottom:32px;
      width:fit-content;
    }
    .hero-title{
  font-family:'Fraunces',serif;
  font-size:clamp(48px,6vw,72px);
  line-height:0.95; /* tighter */
  letter-spacing:-0.8px; /* slightly tighter */
  margin-bottom:16px;
}

/* First line */
.hero-title .line1{
  display:block;
  font-weight:700;
  color:#FFFF;
}

/* Second line */
.hero-title .line2{
  display:block;
  font-weight:300;
  margin-top:8px; /* controls spacing between lines */
}

/* italic part */
.hero-title em{
  font-style:italic;
  font-weight:300;
  color:#FFFF;
}

/* highlight part */
.hero-title strong{
  font-weight:700;
  color:#E8A838;
}
    .hero-sub{
      font-size:17px;color:rgba(255,255,255,.6);max-width:420px;
      font-weight:400;line-height:1.8;margin-bottom:48px;
    }
    .hero-stats{display:flex;gap:40px}
    .hero-stat-num{
      font-family:'Fraunces',serif;font-size:40px;font-weight:700;
      color:#F9D679;line-height:1;display:block;margin-bottom:4px;
    }
    .hero-stat-label{font-size:12px;font-weight:500;color:rgba(255,255,255,.5);letter-spacing:.6px}
    /* right panel content */
    .hero-right-inner{position:relative;z-index:2;padding:48px;text-align:center}
    .hero-icon-big{
      width:120px;height:120px;border-radius:28px;
      background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
      display:flex;align-items:center;justify-content:center;
      margin:0 auto 24px;backdrop-filter:blur(10px);
    }
    .hero-icon-big svg{width:56px;height:56px;stroke:rgba(255,255,255,.9);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round}
    .hero-right-title{font-family:'Fraunces',serif;font-size:28px;font-weight:700;color:#fff;margin-bottom:12px;line-height:1.2}
    .hero-right-sub{font-size:14px;color:rgba(255,255,255,.6);line-height:1.7;max-width:280px;margin:0 auto 32px}
    /* value chips in hero right */
    .hero-chips{display:flex;flex-direction:column;gap:10px;align-items:flex-start;text-align:left;max-width:260px;margin:0 auto}
    .hero-chip{
      display:flex;align-items:center;gap:10px;
      background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);
      border-radius:10px;padding:10px 16px;font-size:13px;font-weight:500;color:rgba(255,255,255,.85);
      width:100%;
    }
    .hero-chip-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}

    /* animations */
    @keyframes fadeUp{from{opacity:0;transform:translateY(30px);filter:blur(6px)}to{opacity:1;transform:translateY(0);filter:blur(0)}}
    .hero-badge{animation:fadeUp .6s ease both .05s}
    .hero-title{animation:fadeUp .6s ease both .18s}
    .hero-sub{animation:fadeUp .6s ease both .30s}
    .hero-stats{animation:fadeUp .6s ease both .42s}
    .hero-right-inner{animation:fadeUp .7s ease both .5s}

    /* ── SHARED ── */
    section{padding:88px 40px}
    .container{max-width:1120px;margin:0 auto}
    .section-label{display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;letter-spacing:1.6px;text-transform:uppercase;color:var(--amber);margin-bottom:12px}
    .section-label::before{content:'';display:inline-block;width:20px;height:2px;background:linear-gradient(90deg,#0F4C5C,#E8A838);border-radius:2px;flex-shrink:0}
    .section-title{font-family:'Fraunces',serif;font-size:34px;font-weight:700;color:var(--ink);margin-bottom:18px;line-height:1.2}
    .section-body{font-size:16px;color:#4A5568;line-height:1.8;max-width:680px}

    /* reveal */
    .reveal{opacity:0;transform:translateY(40px) scale(.98);filter:blur(6px);transition:opacity .9s cubic-bezier(.16,1,.3,1),transform .9s cubic-bezier(.16,1,.3,1),filter .9s ease}
    .reveal.visible{opacity:1;transform:translateY(0) scale(1);filter:blur(0)}

    /* ── MISSION ── */
    .mission{background:var(--white)}
    .mission-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start}

    /* ── IMPACT NUMBERS ── */
    .impact-bar{background:var(--ink);padding:64px 40px}
    .impact-row{max-width:1120px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:40px;text-align:center}
    .impact-num{font-family:'Fraunces',serif;font-size:52px;font-weight:700;color:var(--gold-light);line-height:1;display:block;margin-bottom:6px}
    .impact-label{font-size:13px;font-weight:500;color:rgba(255,255,255,.5);letter-spacing:.5px}
    .impact-divider{width:1px;background:rgba(255,255,255,.08);margin:0 auto}

    /* ── VALUES ── */
    .values{background:#F0FAFA;padding:88px 40px}
    .values-header{text-align:center;margin-bottom:56px}
    .values-grid{
      max-width:1120px;margin:0 auto;
      display:grid;grid-template-columns:repeat(3,1fr);gap:24px;
    }
    .value-card{
      background:var(--white);border:1px solid var(--mist);
      border-radius:20px;padding:36px 28px;
      box-shadow:0 2px 8px rgba(0,0,0,.04);
      transition:box-shadow .25s,transform .25s;
    }
    .value-card:hover{box-shadow:0 12px 36px rgba(15,76,92,.12);transform:translateY(-5px)}
    .value-icon{width:52px;height:52px;background:var(--grad-primary);border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px}
    .value-icon svg{width:24px;height:24px;stroke:#fff;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
    .value-title{font-size:17px;font-weight:700;color:var(--ink);margin-bottom:10px}
    .value-desc{font-size:14px;color:#5A6475;line-height:1.75}

    /* ── STORY ── */
    .story{background:var(--white)}
    .story-inner{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start}
    .story-text p{font-size:16px;color:#4A5568;line-height:1.85;margin-bottom:18px}
    /* timeline */
    .timeline{display:flex;flex-direction:column;gap:0}
    .tl-item{display:flex;gap:20px;position:relative}
    .tl-item:not(:last-child) .tl-line{flex:1;width:2px;background:var(--mist);position:absolute;left:15px;top:36px;bottom:0}
    .tl-dot{width:32px;height:32px;border-radius:50%;background:var(--grad-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:'Fraunces',serif;font-size:12px;font-weight:700;color:#fff;position:relative;z-index:1}
    .tl-content{padding:0 0 36px;flex:1}
    .tl-date{font-size:11px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;color:var(--amber);margin-bottom:4px}
    .tl-title{font-size:16px;font-weight:700;color:var(--ink);margin-bottom:6px}
    .tl-desc{font-size:14px;color:#6B7280;line-height:1.65}

    /* ── TEAM ── */
    .team{background:#F0FAFA;padding:88px 40px}
    .team-header{text-align:center;margin-bottom:56px}
    .team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1120px;margin:0 auto}
    .team-card{background:var(--white);border:1px solid var(--mist);border-radius:20px;padding:36px 24px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.04);transition:box-shadow .25s,transform .25s}
    .team-card:hover{box-shadow:0 12px 36px rgba(15,76,92,.12);transform:translateY(-5px)}
    .avatar{width:72px;height:72px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-size:26px;font-weight:700;color:var(--white);background:var(--grad-primary);border:3px solid var(--mist)}
    .avatar.amber{background:var(--grad-amber);color:var(--teal)}
    .team-name{font-size:16px;font-weight:700;color:var(--ink);margin-bottom:4px}
    .team-role{font-size:13px;color:var(--slate);font-weight:400;margin-bottom:14px;line-height:1.4}
    .team-badge{display:inline-flex;align-items:center;background:rgba(15,76,92,.07);color:var(--teal);font-size:11px;font-weight:600;padding:4px 12px;border-radius:100px}

    /* ── CTA ── */
    .cta-banner{background:var(--grad-primary);padding:88px 40px;text-align:center;position:relative;overflow:hidden}
    .cta-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 100% at 50% 0%,rgba(249,214,121,.13) 0%,transparent 70%);pointer-events:none}
    .cta-banner h2{font-family:'Fraunces',serif;font-size:40px;font-weight:700;color:var(--white);margin-bottom:16px;position:relative}
    .cta-banner p{font-size:17px;color:rgba(255,255,255,.72);margin-bottom:36px;max-width:520px;margin-left:auto;margin-right:auto;position:relative}
    .btn-primary{background:var(--grad-amber);color:var(--teal);font-size:15px;font-weight:600;padding:14px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:transform .15s;margin:0 8px;position:relative}
    .btn-primary:hover{transform:translateY(-2px) scale(1.03);box-shadow:0 10px 25px rgba(232,168,56,.35)}
    .btn-secondary{border:2px solid rgba(255,255,255,.3);color:var(--white);font-size:15px;font-weight:600;padding:12px 32px;border-radius:10px;text-decoration:none;display:inline-block;margin:0 8px;transition:transform .15s}
    .btn-secondary:hover{transform:translateY(-2px)}

    /* ── FOOTER ── */
    footer{background:#0d1f26;padding:64px 64px 36px;font-size:14px}
    .footer-inner{max-width:1200px;margin:0 auto}
    .footer-top{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:64px;margin-bottom:64px}
    .footer-brand-name{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--amber);margin-bottom:16px;display:block}
    .footer-brand-desc{font-size:14px;color:rgba(255,255,255,.45);line-height:1.7;max-width:280px}
    .footer-col-title{font-size:11px;font-weight:600;letter-spacing:1.6px;text-transform:uppercase;color:rgba(255,255,255,.35);margin-bottom:22px}
    .footer-col ul{list-style:none;display:flex;flex-direction:column;gap:14px}
    .footer-col ul li a{color:rgba(255,255,255,.75);text-decoration:none;font-size:14px;transition:color .18s}
    .footer-col ul li a:hover{color:var(--white)}
    .footer-bottom{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;padding-top:32px;border-top:1px solid rgba(255,255,255,.07)}
    .footer-copy{font-size:13px;color:rgba(255,255,255,.4)}
    .footer-sdp-badge{font-size:11px;font-weight:600;letter-spacing:1.4px;text-transform:uppercase;color:var(--amber);border:1px solid var(--amber);padding:7px 18px;border-radius:100px;opacity:.8}

    /* responsive */
    @media(max-width:900px){
      .hero{grid-template-columns:1fr}
      .hero-right{display:none}
      .mission-grid,.story-inner{grid-template-columns:1fr;gap:48px}
      .values-grid,.team-grid{grid-template-columns:1fr 1fr}
      .impact-row{grid-template-columns:1fr 1fr}
    }
    @media(max-width:600px){
      nav{padding:0 20px}
      .nav-links{display:none}
      section{padding:60px 20px}
      .values-grid,.team-grid{grid-template-columns:1fr}
      .hero-left{padding:48px 24px 60px}
      .impact-row{grid-template-columns:1fr 1fr}
    }
  </style>
</head>
<body>

<nav id="navbar">
  <a href="#" class="nav-logo">
    <div class="nav-logo-icon"><img src="/public/white-logo.png" alt="ScholarLink"></div>
    <span class="nav-logo-text">ScholarLink</span>
  </a>
  <ul class="nav-links">
    <li><a href="how-it-works.html">How It Works</a></li>
    <li><a href="organizations.html">Organizations</a></li>
    <li><a href="about.html" class="active">About Us</a></li>
  </ul>
</nav>

<!-- HERO — editorial split -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-badge">About ScholarLink</div>
    <h1 class="hero-title">
  <span class="line1">Bridging Filipino</span>
<span class="line2">
  <em>Students to</em>
  <strong>Scholarship<br>Opportunities</strong>
</span>
</h1>
    <p class="hero-sub">A platform designed to make scholarship access equitable, transparent, and simple — for every student from Luzon to Mindanao.</p>
    <div class="hero-stats">
      <div>
        <span class="hero-stat-num">20+</span>
        <span class="hero-stat-label">Scholarships Listed</span>
      </div>
      <div>
        <span class="hero-stat-num">3</span>
        <span class="hero-stat-label">User Roles Supported</span>
      </div>
      <div>
        <span class="hero-stat-num">100%</span>
        <span class="hero-stat-label">Free for Students</span>
      </div>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-right-inner">
      <div class="hero-icon-big">
        <svg viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
      </div>
      <div class="hero-right-title">What ScholarLink stands for</div>
      <div class="hero-right-sub">Six values drive every feature we build</div>
      <div class="hero-chips">
        <div class="hero-chip"><span class="hero-chip-dot" style="background:#F9D679"></span>Transparency in every listing</div>
        <div class="hero-chip"><span class="hero-chip-dot" style="background:#2A8FA0"></span>Accessibility across all regions</div>
        <div class="hero-chip"><span class="hero-chip-dot" style="background:#E8A838"></span>Integrity — zero fees for students</div>
        <div class="hero-chip"><span class="hero-chip-dot" style="background:#8B5CF6"></span>Community-driven growth</div>
        <div class="hero-chip"><span class="hero-chip-dot" style="background:#0F6E56"></span>Innovation through AI matching</div>
        <div class="hero-chip"><span class="hero-chip-dot" style="background:#993C1D"></span>Equity for underserved groups</div>
      </div>
    </div>
  </div>
</section>

<!-- IMPACT NUMBERS -->
<div class="impact-bar">
  <div class="impact-row reveal">
    <div>
      <span class="impact-num">15</span>
      <span class="impact-label">Closed Scholarships</span>
    </div>
    <div>
      <span class="impact-num">5</span>
      <span class="impact-label">Open Scholarships</span>
    </div>
    <div>
      <span class="impact-num">10+</span>
      <span class="impact-label">Student Applicants</span>
    </div>
    <div>
      <span class="impact-num">0 ₱</span>
      <span class="impact-label">Charged to Students</span>
    </div>
  </div>
</div>

<!-- MISSION -->
<section class="mission">
  <div class="container">
    <div class="mission-grid">
      <div class="reveal">
        <p class="section-label">Our Mission</p>
        <h2 class="section-title">Empowering the Next Generation of Filipino Leaders</h2>
        <p class="section-body">ScholarLink was developed to address a critical gap in access to educational funding. Many deserving Filipino students miss scholarship opportunities simply due to limited awareness, fragmented information, and complex application processes.</p>
        <br/>
        <p class="section-body">Our mission is to simplify and centralize scholarship access by providing a platform that consolidates opportunities from government agencies — like CHED and DOST — private institutions, and academic organizations, all in one place with zero cost to students.</p>
        <br/>
        <p class="section-body">We've built intelligent tools like AI-powered matching, Blind Screening for fair evaluation, and a Student Wallet for document reuse — because a great applicant should never lose a scholarship to a broken process.</p>
      </div>

      <div class="reveal" style="transition-delay:.13s">
        <p class="section-label">The Problem We Solve</p>
        <h2 class="section-title">Why ScholarLink Exists</h2>
        <div style="display:flex;flex-direction:column;gap:16px;margin-top:8px">
          <div style="background:#F0FAFA;border:1px solid var(--mist);border-radius:16px;padding:24px">
            <div style="font-size:13px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:var(--amber);margin-bottom:8px">The Gap</div>
            <p style="font-size:15px;color:#4A5568;line-height:1.7">Thousands of qualified Filipino students never apply for scholarships they deserve — because they don't know those scholarships exist, or they can't navigate the requirements.</p>
          </div>
          <div style="background:#F0FAFA;border:1px solid var(--mist);border-radius:16px;padding:24px">
            <div style="font-size:13px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:var(--teal);margin-bottom:8px">Our Solution</div>
            <p style="font-size:15px;color:#4A5568;line-height:1.7">A centralized, AI-matched, mobile-friendly platform that surfaces the right scholarships to the right students — and helps them apply with one click using pre-stored documents.</p>
          </div>
          <div style="background:var(--ink);border-radius:16px;padding:24px">
            <div style="font-size:13px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:var(--gold-light);margin-bottom:8px">The Impact</div>
            <p style="font-size:15px;color:rgba(255,255,255,.65);line-height:1.7">Every approved application on ScholarLink is a student whose trajectory changes. We believe education funding should be decided by merit, not by who knows the right information.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VALUES -->
<section class="values">
  <div class="values-header">
    <p class="section-label reveal" style="justify-content:center">What We Stand For</p>
    <h2 class="section-title reveal" style="text-align:center;transition-delay:.07s">Our Core Values</h2>
  </div>
  <div class="values-grid">
    <div class="value-card reveal" style="transition-delay:.05s">
      <div class="value-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
      <div class="value-title">Transparency</div>
      <p class="value-desc">Every scholarship listing on ScholarLink includes complete requirements, timelines, and decision criteria. No hidden steps, no surprises — students know exactly what to expect at every stage of the process.</p>
    </div>
    <div class="value-card reveal" style="transition-delay:.10s">
      <div class="value-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
      <div class="value-title">Accessibility</div>
      <p class="value-desc">Designed for students across all regions and economic backgrounds. With a mobile-first interface and multilingual plans, ScholarLink ensures geography and device access never become barriers to opportunity.</p>
    </div>
    <div class="value-card reveal" style="transition-delay:.15s">
      <div class="value-icon"><svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
      <div class="value-title">Integrity</div>
      <p class="value-desc">We verify every scholarship and partner institution before it goes live. Students are never charged any fee — ever. We're committed to being a platform students can trust unconditionally.</p>
    </div>
    <div class="value-card reveal" style="transition-delay:.20s">
      <div class="value-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <div class="value-title">Community</div>
      <p class="value-desc">ScholarLink connects scholars with mentors, alumni, and peers — building networks that outlast the scholarship period itself. A scholar's growth doesn't stop when the stipend arrives.</p>
    </div>
    <div class="value-card reveal" style="transition-delay:.25s">
      <div class="value-icon"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
      <div class="value-title">Innovation</div>
      <p class="value-desc">Our Gemini AI-powered matching engine, Blind Screening portal, and hardware SMS notifications (ESP32 + SIM800L) are not features for their own sake — they solve real inefficiencies in scholarship administration.</p>
    </div>
    <div class="value-card reveal" style="transition-delay:.30s">
      <div class="value-icon"><svg viewBox="0 0 24 24"><path d="M4.5 9.5V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v4.5M12 12v9M8 16l4 4 4-4"/></svg></div>
      <div class="value-title">Equity</div>
      <p class="value-desc">We actively surface scholarships for underserved groups — PWDs, indigenous peoples, 4Ps beneficiaries, and students from conflict-affected areas — because equitable access is the foundation of social mobility.</p>
    </div>
  </div>
</section>

<!-- STORY -->
<section class="story">
  <div class="container">
    <div class="story-inner">
      <div class="story-text reveal">
        <p class="section-label">Our Story</p>
        <h2 class="section-title">From a Project to a Movement</h2>
        <p>ScholarLink is a Software Design Project developed by six BSCpE 2-2 students from Pamantasan ng Lungsod ng Maynila. The goal of the project is to create a centralized platform where students can easily find and apply for scholarship opportunities.</p>
        <p>The system was designed to address common problems such as scattered information, unclear requirements, and difficulty in managing applications. It includes features like scholarship listings, application tracking, and basic matching of students to opportunities.</p>
        <p>This project demonstrates how technology can be used to simplify processes and improve access to educational resources for students.</p>
      </div>

      <div class="reveal" style="transition-delay:.13s">
        <p class="section-label">Platform Timeline</p>
        <h2 class="section-title">Project Development</h2>
        <div class="timeline">
          <div class="tl-item">
            <div class="tl-dot">1</div>
            <div class="tl-content">
              <div class="tl-date">February 2025</div>
              <div class="tl-title">Project Planning</div>
<div class="tl-desc">Initial concept, problem identification, and system proposal were developed.</div>
            </div>
            <div class="tl-line"></div>
          </div>
          <div class="tl-item">
            <div class="tl-dot">2</div>
            <div class="tl-content">
              <div class="tl-date">February 2026</div>
              <div class="tl-title">System Design</div>
<div class="tl-desc">UI/UX design, database structure, and system architecture were planned.</div>
            </div>
            <div class="tl-line"></div>
          </div>
          <div class="tl-item">
            <div class="tl-dot">3</div>
            <div class="tl-content">
              <div class="tl-title">Development Phase</div>
<div class="tl-desc">Core features such as scholarship listings, application flow, and user roles were implemented.</div>
            </div>
            <div class="tl-line"></div>
          </div>
          <div class="tl-item">
            <div class="tl-dot">4</div>
            <div class="tl-content">
              <div class="tl-date">March–April 2026</div>
              <div class="tl-title">Testing & Finalization</div>
<div class="tl-desc">System testing, debugging, and final improvements were completed before presentation.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="team">
  <div class="team-header reveal">
    <p class="section-label" style="justify-content:center">The People Behind ScholarLink</p>
    <h2 class="section-title" style="text-align:center">Meet Our Team</h2>
    <p style="font-size:16px;color:var(--slate);text-align:center;max-width:480px;margin:0 auto">Six BSCpE students from PLM College of Engineering — building real technology to solve a real problem.</p>
  </div>
  <div class="team-grid">
    <div class="team-card reveal" style="transition-delay:.05s">
      <div class="avatar">F</div>
      <div class="team-name">Franchezca Banayad</div>
      <div class="team-role">Full Stack Developer &amp; Back End Lead</div>
      <span class="team-badge">BSCpE 2-2</span>
    </div>
    <div class="team-card reveal" style="transition-delay:.10s">
      <div class="avatar amber">J</div>
      <div class="team-name">Jose Jerico Escaño</div>
      <div class="team-role">Front End Developer</div>
      <span class="team-badge">BSCpE 2-2</span>
    </div>
    <div class="team-card reveal" style="transition-delay:.15s">
      <div class="avatar">K</div>
      <div class="team-name">Karl Joseph Esteban</div>
      <div class="team-role">Full Stack Developer</div>
      <span class="team-badge">BSCpE 2-2</span>
    </div>
    <div class="team-card reveal" style="transition-delay:.20s">
      <div class="avatar amber">N</div>
      <div class="team-name">Niña Ysabelle Frigillana</div>
      <div class="team-role">UI/UX, Full Stack &amp; Front End Lead</div>
      <span class="team-badge">BSCpE 2-2</span>
    </div>
    <div class="team-card reveal" style="transition-delay:.25s">
      <div class="avatar">E</div>
      <div class="team-name">Elena Vale Lanuza</div>
      <div class="team-role">Front End Developer</div>
      <span class="team-badge">BSCpE 2-2</span>
    </div>
    <div class="team-card reveal" style="transition-delay:.30s">
      <div class="avatar amber">P</div>
      <div class="team-name">Princess Mae Sanchez</div>
      <div class="team-role">Back End Developer &amp; Database Lead</div>
      <span class="team-badge">BSCpE 2-2</span>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-banner reveal">
  <h2>Your scholarship is waiting.</h2>
  <p>Join thousands of Filipino students who found their funding through ScholarLink.</p>
  <a href="#" class="btn-primary">Browse Scholarships</a>
  <a href="contact.html" class="btn-secondary">Contact Us</a>
</section>

<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <span class="footer-brand-name">ScholarLink</span>
        <p class="footer-brand-desc">Bridging Filipino students to scholarship opportunities — one profile, every scholarship.</p>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Platform</div>
        <ul><li><a href="#">Browse</a></li><li><a href="how-it-works.html">How It Works</a></li><li><a href="organizations.html">For Organizations</a></li><li><a href="#">AI Matching</a></li></ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Account</div>
        <ul><li><a href="#">Sign Up</a></li><li><a href="#">Log In</a></li><li><a href="#">My Applications</a></li><li><a href="#">Document Wallet</a></li></ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Legal</div>
        <ul><li><a href="legal.html">Privacy Policy</a></li><li><a href="legal.html">Terms of Service</a></li><li><a href="legal.html">Data Privacy Act</a></li><li><a href="contact.html">Contact Us</a></li></ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p class="footer-copy">© 2026 ScholarLink. Philippines 🇵🇭</p>
      <span class="footer-sdp-badge">Software Design Project</span>
    </div>
  </div>
</footer>

<script>
  const nav = document.getElementById('navbar');
  const ro = new IntersectionObserver(e=>{e.forEach(x=>{if(x.isIntersecting){x.target.classList.add('visible');ro.unobserve(x.target)}})},{threshold:.1,rootMargin:'0px 0px -48px 0px'});
  document.querySelectorAll('.reveal').forEach(el=>ro.observe(el));
</script>
</body>
</html>

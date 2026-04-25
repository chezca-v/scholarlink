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
html { scroll-behavior: smooth; scroll-padding-top: 84px;}
body { font-family: 'DM Sans', sans-serif; background: #ffffff; color: #0A3040; overflow-x: hidden; }

/* Floating Icons Animation */
.floating-icon {
  position: absolute;
  opacity: 0.2;
  animation: float 6s ease-in-out infinite;
  z-index: 0;
  pointer-events: none;
}
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
  100% { transform: translateY(0px); }
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
@@ -60,66 +60,75 @@
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
.logos { padding: 48px; border-top: 1px solid #EAF4F3; border-bottom: 1px solid #EAF4F3; }
.logos-inner { max-width: 1160px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 20px; }
.logos-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #A5D0CD; white-space: nowrap; }
.logos-row { width: 100%; overflow: hidden; display: flex; justify-content: center; }
.logos-track { display: flex; gap: 48px; align-items: center; width: max-content; animation: logos-slide-right 30s ease-in-out infinite alternate; }
.logo-item { font-family: 'Fraunces', serif; font-size: 15px; font-weight: 700; color: #6EA9A5; letter-spacing: -0.5px; transition: transform 0.25s ease, color 0.25s ease, text-shadow 0.25s ease; cursor: pointer; }
.logo-item:hover { color: #0F4C5C; transform: translateY(-2px) scale(1.03); text-shadow: 0 8px 20px rgba(15,76,92,0.18); }
@keyframes logos-slide-right {
  from { transform: translateX(-7%); }
  to { transform: translateX(7%); }
}

/* HOW */
.how { padding: 112px 48px; background: #fff; }
.how-inner { max-width: 1160px; margin: 0 auto; }
.section-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #E8A838; margin-bottom: 14px; }
.section-title { font-family: 'Fraunces', serif; font-size: clamp(32px,3vw,48px); font-weight: 800; letter-spacing: -1.5px; color: #0A3040; margin-bottom: 56px; line-height: 1.05; }
.section-title em { font-style: italic; font-weight: 300; color: #0F4C5C; }
.steps { display: grid; grid-template-columns: repeat(3,1fr); gap: 40px; }

.step { display: flex; flex-direction: column; gap: 16px; background: #F8FCFC; border: 1px solid #DCEFED; border-radius: 18px; padding: 24px; transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease; cursor: pointer; }
.step:hover { transform: translateY(-6px); border-color: #9FD4CF; box-shadow: 0 14px 28px rgba(15,76,92,0.12); }
.step-num-circle { width: 44px; height: 44px; border-radius: 50%; background: #F0FAFA; border: 1px solid #C8E8E4; display: flex; align-items: center; justify-content: center; font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: #0F4C5C; transition: transform 0.3s ease, background 0.3s ease, color 0.3s ease; }
.step:hover .step-num-circle { background: #0F4C5C; color: #F9D679; transform: scale(1.07); }
.step-title { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: #0A3040; transition: color 0.25s ease; }
.step:hover .step-title { color: #0F4C5C; }
.step-desc { font-size: 14px; color: #5F8F95; line-height: 1.75; }
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
@@ -155,63 +164,63 @@
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

.reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.65s cubic-bezier(0.22, 1, 0.36, 1), transform 0.65s cubic-bezier(0.22, 1, 0.36, 1); }
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
      <li><a href="#how-it-works">How It Works</a></li>
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
    <a href="/scholarships" class="btn-hero-main">🎓 Browse Scholarships</a>
    <button class="btn-hero-text">▶ Watch how it works</button>
  </div>
  <div class="hero-social-proof">
@@ -233,60 +242,62 @@
        <div class="msc-match-label">Match Score</div>
        <div class="msc-bar"><div class="msc-bar-fill" style="width:94%"></div></div>
      </div>
      <div class="mini-schol-card">
        <div class="msc-top"><span class="msc-org">Abot-Kaya Inc.</span><span class="msc-open">Open</span></div>
        <div class="msc-title">Abot-Kaya Excellence Grant</div>
        <div class="msc-match">87%</div>
        <div class="msc-match-label">Match Score</div>
        <div class="msc-bar"><div class="msc-bar-fill" style="width:87%"></div></div>
      </div>
      <div class="mini-schol-card">
        <div class="msc-top"><span class="msc-org">TechBridge Corp.</span><span class="msc-open warn">Closing</span></div>
        <div class="msc-title">TechBridge STEM Scholarship</div>
        <div class="msc-match">78%</div>
        <div class="msc-match-label">Match Score</div>
        <div class="msc-bar"><div class="msc-bar-fill" style="width:78%"></div></div>
      </div>
    </div>
  </div>
</section>

<div class="logos">
  <div class="logos-inner">
    <span class="logos-label">Trusted by</span>
    <div class="logos-row">
    <div class="logos-track">
        <span class="logo-item">Gabay Foundation</span>
        <span class="logo-item">Abot-Kaya Inc.</span>
        <span class="logo-item">TechBridge Corp.</span>
        <span class="logo-item">Lumina Grants</span>
        <span class="logo-item">PH Merit Fund</span>
      </div>
    </div>
  </div>
</div>
<section class="how" id="how-it-works">
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
        <div class="step-desc">Follow every stage of your application. Get notified via in-app, email, or SMS — even with limited internet access.</div>
      </div>
    </div>
  </div>
</section>

<section class="scholarships">
  <div class="scholarships-inner">
@@ -381,28 +392,38 @@
document.querySelectorAll('.filter').forEach(btn => {
  btn.addEventListener('click', () => { document.querySelectorAll('.filter').forEach(b => b.classList.remove('active')); btn.classList.add('active'); });
});
const obs = new IntersectionObserver(e => e.forEach(el => { if(el.isIntersecting){el.target.classList.add('visible');obs.unobserve(el.target);}}),{threshold:0.1});
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

// Typing effect para sa Hero Title
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

const watchButton = document.querySelector('.btn-hero-text');
watchButton?.addEventListener('click', () => {
  const howSection = document.getElementById('how-it-works');
  const navHeight = document.querySelector('nav')?.offsetHeight ?? 0;
  if (!howSection) return;

  const targetY = howSection.getBoundingClientRect().top + window.scrollY - navHeight - 16;
  window.scrollTo({ top: targetY, behavior: 'smooth' });
});
</script>
</body>
</html>

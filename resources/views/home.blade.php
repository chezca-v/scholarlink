<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ScholarLink — One Profile. Every Scholarship.</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;0,9..144,900;1,9..144,300;1,9..144,700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/SplitText.min.js"></script>
<style>
:root {
  --teal-deep: #071820;
  --teal-dark: #0A3040;
  --teal-mid: #0F4C5C;
  --teal-light: #1A6B7A;
  --teal-pale: #7AACAA;
  --teal-ghost: #C8E8E4;
  --teal-mist: #EAF4F3;
  --teal-foam: #F0FAFA;
  --gold: #E8A838;
  --gold-light: #F9D679;
  --gold-pale: #FDF4E3;
  --white: #ffffff;
  --navy: #0A3040;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: auto; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--white);
  color: var(--navy);
  overflow-x: hidden;
}

/* ── NAV ───────────────────────────────────────── */
nav {
  position: fixed; top: 0; left: 0; right: 0;
  z-index: 200;
  background: rgba(255,255,255,0.88);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-bottom: 1px solid var(--teal-mist);
  transform: translateY(-100%);
  transition: background 0.4s;
}
.nav-inner {
  max-width: 1160px; margin: 0 auto; padding: 0 48px;
  height: 64px; display: flex; align-items: center; justify-content: space-between;
}
.logo {
  font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700;
  color: var(--teal-mid); letter-spacing: -0.5px;
  display: flex; align-items: center; gap: 10px; text-decoration: none;
}
.logo-mark {
  width: 34px; height: 34px;
  display: block;
  object-fit: contain;
  filter: drop-shadow(0 4px 10px rgba(15,76,92,0.18));
}
.nav-links { display: flex; gap: 36px; list-style: none; }
.nav-links a {
  font-size: 14px; font-weight: 500; color: var(--teal-pale);
  text-decoration: none; transition: color 0.2s; position: relative;
}
.nav-links a::after {
  content: ''; position: absolute; bottom: -2px; left: 0; right: 0;
  height: 1px; background: var(--gold); transform: scaleX(0); transition: transform 0.3s;
}
.nav-links a:hover { color: var(--teal-mid); }
.nav-links a:hover::after { transform: scaleX(1); }
.nav-actions { display: flex; gap: 10px; align-items: center; }
.btn-text {
  font-size: 14px; font-weight: 600; padding: 8px 18px;
  border: none; background: transparent; color: var(--teal-mid); cursor: pointer;
  text-decoration: none;
}
.btn-pill {
  font-size: 13px; font-weight: 700; padding: 9px 22px;
  border: none; background: var(--teal-mid); color: var(--gold-light);
  border-radius: 999px; cursor: pointer;
  box-shadow: 0 4px 16px rgba(15,76,92,0.25);
  text-decoration: none; display: inline-block;
  transition: all 0.3s cubic-bezier(.17,.67,.35,1.3);
  position: relative; overflow: hidden;
}
.btn-pill::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, var(--teal-light), var(--teal-mid));
  opacity: 0; transition: opacity 0.3s;
}
.btn-pill:hover { transform: translateY(-2px) scale(1.03); box-shadow: 0 8px 28px rgba(15,76,92,0.35); }
.btn-pill:hover::before { opacity: 1; }
.btn-pill span { position: relative; z-index: 1; }

/* ── PARTICLE CANVAS ───────────────────────────── */
#particle-canvas {
  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  pointer-events: none; z-index: 1;
}

/* ── HERO ──────────────────────────────────────── */
.hero {
  min-height: 100vh;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 120px 48px 80px;
  text-align: center;
  position: relative; overflow: hidden;
  background: var(--white);
}
.hero-bg-gradient {
  position: absolute; top: 0; left: 50%; transform: translateX(-50%);
  width: 900px; height: 600px;
  background: radial-gradient(ellipse 80% 70% at 50% 10%, rgba(240,250,250,0.9) 0%, transparent 70%);
  pointer-events: none; z-index: 0;
}
.hero-content { position: relative; z-index: 2; }

.hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--teal-foam); border: 1px solid var(--teal-ghost);
  border-radius: 999px; padding: 6px 16px;
  font-size: 12px; font-weight: 600; color: #4A7A80;
  margin-bottom: 32px; opacity: 0; transform: translateY(12px);
}
.badge-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--gold); animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.6;transform:scale(0.85);} }

/* ── HANDWRITING HEADLINE ──────────────────────── */
.hero-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(52px, 6vw, 86px);
  font-weight: 900; letter-spacing: -3px; line-height: 1.05;
  color: var(--navy); max-width: 820px;
  margin: 0 auto 16px;
  min-height: 2.3em;
}
.hero-title em { font-style: italic; font-weight: 300; color: var(--teal-mid); }
.hero-title .accent-gold { color: var(--gold); }

/* SVG writing path overlay */
#headline-svg {
  position: absolute; top: 0; left: 0; width: 100%; height: 100%;
  pointer-events: none; overflow: visible;
}
.write-path {
  fill: none; stroke: var(--teal-mid); stroke-width: 3;
  stroke-linecap: round; stroke-linejoin: round;
  stroke-dasharray: 3000; stroke-dashoffset: 3000;
}

.hero-sub {
  font-size: 17px; color: var(--teal-pale); line-height: 1.75;
  max-width: 520px; margin: 0 auto 40px;
  opacity: 0; transform: translateY(16px);
}
.hero-actions {
  display: flex; align-items: center; gap: 16px; justify-content: center;
  margin-bottom: 56px; opacity: 0; transform: translateY(16px);
}
.btn-hero-main {
  font-size: 15px; font-weight: 700; padding: 15px 34px;
  border: none; background: var(--teal-mid); color: var(--gold-light);
  border-radius: 14px; cursor: pointer;
  box-shadow: 0 8px 30px rgba(15,76,92,0.28),
              0 0 0 0 rgba(15,76,92,0);
  text-decoration: none; display: inline-block;
  position: relative; overflow: hidden;
  transition: transform 0.3s cubic-bezier(.17,.67,.35,1.3), box-shadow 0.3s;
}
.btn-hero-main::after {
  content: ''; position: absolute; inset: 0;
  background: radial-gradient(circle at var(--mx,50%) var(--my,50%), rgba(249,214,121,0.2), transparent 60%);
  opacity: 0; transition: opacity 0.3s;
}
.btn-hero-main:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 16px 48px rgba(15,76,92,0.35), 0 0 0 6px rgba(15,76,92,0.08); }
.btn-hero-main:hover::after { opacity: 1; }

.btn-ghost {
  font-size: 15px; font-weight: 600; color: var(--teal-pale);
  background: transparent; border: 1.5px solid var(--teal-ghost);
  border-radius: 14px; padding: 14px 28px; cursor: pointer;
  display: flex; align-items: center; gap: 10px;
  transition: all 0.3s; text-decoration: none;
}
.btn-ghost:hover { border-color: var(--teal-mid); color: var(--teal-mid); transform: translateX(4px); }

.play-ring {
  width: 28px; height: 28px; border-radius: 50%;
  background: var(--teal-foam); border: 1px solid var(--teal-ghost);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; transition: all 0.3s;
}
.btn-ghost:hover .play-ring { background: var(--teal-mid); color: var(--gold-light); }

.hero-social-proof {
  display: flex; align-items: center; gap: 16px;
  justify-content: center; opacity: 0;
}
.avatars { display: flex; }
.avatar {
  width: 32px; height: 32px; border-radius: 50%;
  border: 2px solid var(--white); background: var(--teal-ghost);
  color: var(--teal-mid); font-size: 12px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  margin-left: -8px; transition: transform 0.3s;
}
.avatar:first-child { margin-left: 0; }
.avatars:hover .avatar { transform: translateX(0); }
.avatars .avatar:hover { transform: translateY(-4px) scale(1.1); z-index: 10; }
.proof-text { font-size: 13px; color: var(--teal-pale); }
.proof-text strong { color: var(--teal-mid); }

/* ── HERO CARDS ────────────────────────────────── */
.hero-visual {
  max-width: 920px; width: 100%; margin: 72px auto 0;
  position: relative; z-index: 2; opacity: 0; transform: translateY(32px);
}
.cards-row {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px;
}
.mini-schol-card {
  background: var(--white);
  border: 1px solid var(--teal-mist);
  border-radius: 18px; padding: 22px; text-align: left;
  box-shadow: 0 4px 20px rgba(15,76,92,0.06);
  transition: all 0.4s cubic-bezier(.17,.67,.35,1.3);
  position: relative; overflow: hidden; cursor: pointer;
}
.mini-schol-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: linear-gradient(90deg, var(--teal-mid), var(--gold));
  transform: scaleX(0); transform-origin: left; transition: transform 0.4s;
}
.mini-schol-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 24px 48px rgba(15,76,92,0.14); border-color: var(--teal-ghost); }
.mini-schol-card:hover::before { transform: scaleX(1); }
.msc-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
.msc-org { font-size: 10px; font-weight: 700; letter-spacing: 0.5px; color: var(--teal-pale); text-transform: uppercase; }
.msc-open { font-size: 10px; font-weight: 700; color: #16A34A; background: #DCFCE7; padding: 2px 8px; border-radius: 999px; }
.msc-open.warn { color: #B45309; background: #FEF9C3; }
.msc-title { font-family: 'Fraunces', serif; font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 14px; line-height: 1.35; }
.msc-match { font-family: 'Fraunces', serif; font-size: 26px; font-weight: 700; color: var(--gold); }
.msc-match-label { font-size: 10px; color: var(--teal-pale); margin-top: 2px; }
.msc-bar { height: 3px; background: var(--teal-mist); border-radius: 999px; margin-top: 12px; overflow: hidden; }
.msc-bar-fill { height: 100%; background: linear-gradient(90deg, var(--teal-mid), var(--gold)); border-radius: 999px; width: 0; transition: width 1.2s cubic-bezier(.17,.67,.35,1); }

/* ── LOGOS ─────────────────────────────────────── */
.logos {
  padding: 48px 0;
  border-top: 1px solid var(--teal-mist);
  border-bottom: 1px solid var(--teal-mist);
  overflow: hidden; position: relative;
}
.logos::before, .logos::after {
  content: ''; position: absolute; top: 0; bottom: 0;
  width: 120px; z-index: 2;
}
.logos::before { left: 0; background: linear-gradient(90deg, var(--white), transparent); }
.logos::after { right: 0; background: linear-gradient(-90deg, var(--white), transparent); }
.logos-track {
  display: flex; gap: 0; align-items: center;
  animation: scrollLogos 35s linear infinite;
  width: max-content;
}
.logos-track:hover { animation-play-state: paused; }
.logo-item {
  font-family: 'Fraunces', serif; font-size: 15px; font-weight: 700;
  color: var(--teal-ghost); letter-spacing: -0.5px; white-space: nowrap;
  padding: 0 36px; transition: color 0.3s; cursor: pointer;
}
.logo-item:hover { color: var(--teal-pale); }
@keyframes scrollLogos { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.logos-label {
  font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
  color: var(--teal-ghost); text-align: center; margin-bottom: 20px;
}

/* ── HOW IT WORKS ──────────────────────────────── */
.how { padding: 128px 48px; background: var(--white); overflow: hidden; }
.how-inner { max-width: 1160px; margin: 0 auto; }
.section-eyebrow {
  font-size: 11px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase;
  color: var(--gold); margin-bottom: 14px;
}
.section-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(32px, 3.5vw, 52px);
  font-weight: 800; letter-spacing: -1.5px; color: var(--navy);
  line-height: 1.05; margin-bottom: 64px;
}
.section-title em { font-style: italic; font-weight: 300; color: var(--teal-mid); }

.steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 48px; }
.step { display: flex; flex-direction: column; gap: 18px; opacity: 0; transform: translateY(40px); }
.step-num-circle {
  width: 52px; height: 52px; border-radius: 50%;
  background: var(--teal-foam); border: 1px solid var(--teal-ghost);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700;
  color: var(--teal-mid); position: relative; transition: all 0.4s;
}
.step:hover .step-num-circle { background: var(--teal-mid); color: var(--gold-light); border-color: var(--teal-mid); transform: scale(1.1) rotate(5deg); }
.step-connector {
  display: flex; align-items: center; gap: 0;
  position: absolute; top: 26px; left: 52px; width: calc(100% + 48px);
  height: 1px; background: linear-gradient(90deg, var(--teal-ghost), transparent);
}
.step-title { font-family: 'Fraunces', serif; font-size: 22px; font-weight: 700; color: var(--navy); }
.step-desc { font-size: 14px; color: var(--teal-pale); line-height: 1.8; }

/* ── SCHOLARSHIPS ──────────────────────────────── */
.scholarships { padding: 112px 48px; background: var(--teal-foam); }
.scholarships-inner { max-width: 1160px; margin: 0 auto; }
.sch-top { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; }
.filters { display: flex; gap: 8px; flex-wrap: wrap; }
.filter {
  font-size: 12px; font-weight: 600; padding: 8px 18px;
  border-radius: 999px; border: 1px solid var(--teal-ghost);
  background: var(--white); color: #4A7A80; cursor: pointer;
  transition: all 0.25s cubic-bezier(.17,.67,.35,1.3);
}
.filter.active, .filter:hover { background: var(--teal-mid); color: var(--gold-light); border-color: var(--teal-mid); transform: translateY(-2px); }

.s-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.s-card {
  background: var(--white); border: 1px solid var(--teal-mist);
  border-radius: 20px; padding: 26px;
  display: flex; flex-direction: column;
  transition: all 0.4s cubic-bezier(.17,.67,.35,1.3); cursor: pointer;
  opacity: 0; transform: translateY(30px) scale(0.98);
  position: relative; overflow: hidden;
}
.s-card::after {
  content: ''; position: absolute;
  bottom: 0; left: 0; right: 0; height: 0;
  background: linear-gradient(0deg, rgba(15,76,92,0.04), transparent);
  transition: height 0.4s;
}
.s-card:hover { border-color: var(--teal-mid); box-shadow: 0 20px 48px rgba(15,76,92,0.12); transform: translateY(-6px) scale(1.01); }
.s-card:hover::after { height: 100%; }
.sc-org { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--teal-pale); margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }
.sc-status { padding: 2px 10px; border-radius: 999px; font-size: 10px; }
.sc-status.open { background: #DCFCE7; color: #16A34A; }
.sc-status.closing { background: #FEF9C3; color: #B45309; }
.sc-title { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: var(--navy); margin-bottom: 12px; line-height: 1.3; }
.sc-details { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
.sc-detail { font-size: 12px; color: #4A7A80; display: flex; align-items: center; gap: 8px; }
.sc-detail-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--teal-ghost); flex-shrink: 0; }
.sc-match { margin-top: auto; padding-top: 18px; border-top: 1px solid var(--teal-mist); }
.sc-match-row { display: flex; justify-content: space-between; margin-bottom: 8px; align-items: center; }
.sc-match-label { font-size: 11px; color: var(--teal-pale); }
.sc-match-pct { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: var(--gold); }
.sc-bar-bg { height: 4px; background: var(--teal-mist); border-radius: 999px; overflow: hidden; }
.sc-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--teal-mid), var(--gold)); width: 0; transition: width 1.4s cubic-bezier(.17,.67,.35,1); }

/* ── FEATURES ──────────────────────────────────── */
.features { padding: 128px 48px; background: var(--white); }
.features-inner { max-width: 1160px; margin: 0 auto; }
.feat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; margin-top: 64px; }
.feat-card {
  background: var(--teal-foam); border: 1px solid var(--teal-mist);
  border-radius: 22px; padding: 34px;
  transition: all 0.4s cubic-bezier(.17,.67,.35,1.3);
  opacity: 0; transform: translateY(30px);
}
.feat-card:hover { border-color: var(--teal-ghost); box-shadow: 0 12px 36px rgba(15,76,92,0.09); transform: translateY(-4px); }
.feat-card.highlighted {
  background: var(--teal-mid); border-color: transparent;
  grid-column: span 2; display: grid; grid-template-columns: 1fr 1fr;
  gap: 52px; align-items: center;
}
.feat-card.highlighted:hover { transform: translateY(-4px); box-shadow: 0 24px 64px rgba(15,76,92,0.3); }
.feat-icon {
  width: 48px; height: 48px; background: var(--white);
  border: 1px solid var(--teal-ghost); border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; margin-bottom: 18px;
  transition: transform 0.3s cubic-bezier(.17,.67,.35,1.3);
}
.feat-card:hover .feat-icon { transform: scale(1.1) rotate(-5deg); }
.feat-card.highlighted .feat-icon { background: rgba(255,255,255,0.12); border-color: transparent; }
.feat-title { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
.feat-card.highlighted .feat-title { color: var(--gold-light); font-size: 26px; }
.feat-desc { font-size: 14px; color: #4A7A80; line-height: 1.8; }
.feat-card.highlighted .feat-desc { color: rgba(255,255,255,0.6); font-size: 15px; }
.feat-right-items { display: flex; flex-direction: column; gap: 16px; }
.feat-right-item {
  background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);
  border-radius: 14px; padding: 16px 18px;
  display: flex; gap: 14px; align-items: center;
  transition: all 0.3s; cursor: pointer;
}
.feat-right-item:hover { background: rgba(255,255,255,0.14); transform: translateX(6px); }
.fri-icon { font-size: 20px; }
.fri-title { font-size: 14px; font-weight: 600; color: var(--white); }
.fri-sub { font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 2px; }

/* ── CTA ───────────────────────────────────────── */
.cta { padding: 128px 48px; background: var(--white); text-align: center; overflow: hidden; }
.cta-inner { max-width: 680px; margin: 0 auto; position: relative; }
.cta-glow {
  position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
  width: 600px; height: 400px;
  background: radial-gradient(ellipse, rgba(232,168,56,0.07), transparent 70%);
  pointer-events: none;
}
.cta-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--gold-pale); border: 1px solid rgba(232,168,56,0.3);
  border-radius: 999px; padding: 6px 18px;
  font-size: 12px; font-weight: 600; color: var(--gold);
  margin-bottom: 28px; opacity: 0; transform: translateY(16px);
}
.cta-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(38px, 5vw, 62px);
  font-weight: 900; letter-spacing: -2px; color: var(--navy);
  line-height: 1; margin-bottom: 18px;
  opacity: 0; transform: translateY(20px);
}
.cta-title em { font-style: italic; font-weight: 300; color: var(--teal-mid); }
.cta-sub { font-size: 16px; color: var(--teal-pale); line-height: 1.75; margin-bottom: 40px; opacity: 0; transform: translateY(16px); }
.cta-btns { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; opacity: 0; transform: translateY(16px); }
.btn-cta-main {
  font-size: 15px; font-weight: 700; padding: 15px 36px;
  border: none; background: var(--teal-mid); color: var(--gold-light);
  border-radius: 14px; cursor: pointer;
  box-shadow: 0 8px 28px rgba(15,76,92,0.25);
  text-decoration: none; display: inline-block;
  transition: all 0.35s cubic-bezier(.17,.67,.35,1.3);
  position: relative; overflow: hidden;
}
.btn-cta-main::before {
  content: ''; position: absolute; inset: -2px;
  background: linear-gradient(135deg, var(--gold), var(--teal-mid), var(--gold));
  background-size: 200%;
  opacity: 0; transition: opacity 0.3s;
  border-radius: 15px; z-index: -1;
  animation: glowShift 3s linear infinite;
}
@keyframes glowShift { from{background-position:0% 50%} to{background-position:200% 50%} }
.btn-cta-main:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 20px 60px rgba(15,76,92,0.35); }
.btn-cta-main:hover::before { opacity: 1; }
.btn-cta-sec {
  font-size: 15px; font-weight: 600; padding: 14px 32px;
  border: 1.5px solid var(--teal-ghost); background: transparent;
  color: #4A7A80; border-radius: 14px; cursor: pointer;
  transition: all 0.3s; text-decoration: none; display: inline-block;
}
.btn-cta-sec:hover { border-color: var(--teal-mid); color: var(--teal-mid); transform: translateY(-2px); }

/* ── STATS ─────────────────────────────────────── */
.stats-row {
  display: flex; justify-content: center; gap: 56px;
  margin-top: 72px; padding-top: 56px; border-top: 1px solid var(--teal-mist);
}
.stat { text-align: center; opacity: 0; transform: translateY(20px); }
.stat-num { font-family: 'Fraunces', serif; font-size: 36px; font-weight: 700; color: var(--gold); }
.stat-label { font-size: 12px; color: var(--teal-pale); margin-top: 6px; }

/* ── FOOTER ────────────────────────────────────── */
footer { background: var(--teal-deep); padding: 72px 48px 36px; }
.footer-inner { max-width: 1160px; margin: 0 auto; }
.footer-grid {
  display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 52px;
  padding-bottom: 52px; border-bottom: 1px solid rgba(255,255,255,0.06);
  margin-bottom: 32px;
}
.footer-logo { display: flex; align-items: center; gap: 10px; font-family: 'Fraunces', serif; font-size: 22px; font-weight: 700; color: var(--gold-light); letter-spacing: -0.5px; margin-bottom: 12px; }
.footer-logo-mark { width: 36px; height: 36px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.2)); }
.footer-tagline { font-size: 13px; color: rgba(255,255,255,0.3); line-height: 1.8; max-width: 280px; }
.footer-col-title { font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.2); margin-bottom: 16px; }
.footer-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
.footer-links li { font-size: 13px; color: rgba(255,255,255,0.4); cursor: pointer; transition: color 0.2s; }
.footer-links li:hover { color: var(--gold-light); }
.footer-bottom { display: flex; justify-content: space-between; align-items: center; }
.footer-copy { font-size: 12px; color: rgba(255,255,255,0.18); }
.footer-badge { font-size: 10px; font-weight: 700; color: var(--gold); border: 1px solid rgba(232,168,56,0.25); border-radius: 999px; padding: 4px 14px; letter-spacing: 1.5px; }

/* ── AMBIENT BG ORBS ───────────────────────────── */
.ambient-orb {
  position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
  filter: blur(80px); opacity: 0.04;
  animation: orbFloat 12s ease-in-out infinite;
}
@keyframes orbFloat { 0%,100%{transform:translate(0,0)} 33%{transform:translate(30px,-20px)} 66%{transform:translate(-20px,30px)} }
.orb-teal { background: var(--teal-mid); width: 600px; height: 600px; top: -100px; right: -100px; animation-delay: 0s; }
.orb-gold { background: var(--gold); width: 400px; height: 400px; bottom: 20%; left: -80px; animation-delay: -6s; }

/* ── SCROLL PROGRESS ───────────────────────────── */
#scroll-progress {
  position: fixed; top: 0; left: 0; height: 2px; z-index: 999;
  background: linear-gradient(90deg, var(--teal-mid), var(--gold));
  transform-origin: left; transform: scaleX(0);
  transition: none;
}

/* ── SECTION DIVIDERS ──────────────────────────── */
.wave-divider { width: 100%; overflow: hidden; line-height: 0; margin-bottom: -2px; }
.wave-divider svg { display: block; width: 100%; }

/* ── MAGNETIC BUTTON WRAPPER ───────────────────── */
.magnetic-wrap { display: inline-block; }

/* ── RESPONSIVE ────────────────────────────────── */
@media (max-width: 900px) {
  .nav-links { display: none; }
  .cards-row, .steps, .s-grid, .feat-grid { grid-template-columns: 1fr 1fr; }
  .feat-card.highlighted { grid-column: span 2; grid-template-columns: 1fr; }
  .footer-grid { grid-template-columns: 1fr 1fr; }
  .stats-row { gap: 32px; }
}
@media (max-width: 600px) {
  .cards-row, .steps, .s-grid, .feat-grid { grid-template-columns: 1fr; }
  .feat-card.highlighted { grid-column: span 1; }
  .hero { padding: 100px 24px 60px; }
  .how, .scholarships, .features, .cta { padding: 80px 24px; }
  nav .nav-inner { padding: 0 24px; }
  .footer-grid { grid-template-columns: 1fr; gap: 32px; }
  .stats-row { flex-direction: column; gap: 24px; }
  .hero-actions { flex-direction: column; }
}
</style>
</head>
<body>

<!-- Scroll progress bar -->
<div id="scroll-progress"></div>

<!-- Ambient orbs -->
<div class="ambient-orb orb-teal"></div>
<div class="ambient-orb orb-gold"></div>

<!-- Particle canvas -->
<canvas id="particle-canvas"></canvas>

<!-- NAV -->
<nav id="main-nav">
  <div class="nav-inner">
    <a href="{{ route('landing') }}" class="logo">
      <img src="{{ asset('logo-light.png.png') }}" alt="ScholarLink logo" class="logo-mark">
      <span>ScholarLink</span>
    </a>
    <ul class="nav-links">
      <li><a href="{{ route('scholarships.index') }}">Browse</a></li>
      <li><a href="#how">How It Works</a></li>
      <li><a href="{{ route('about') }}">About</a></li>
      <li><a href="{{ route('organizations') }}">Organizations</a></li>
    </ul>
    <div class="nav-actions">
      <a href="{{ route('login') }}" class="btn-text">Log In</a>
      <a href="{{ route('register') }}" class="btn-pill"><span>Get Started →</span></a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-bg-gradient"></div>
  <div class="hero-content">
    <div class="hero-badge"><span class="badge-dot"></span>Now live — Philippines' premier scholarship platform</div>

    <!-- Handwriting Headline -->
    <h1 class="hero-title" id="hero-headline">
      <span id="headline-line1"></span><br>
      <em id="headline-line2"></em><span id="headline-line2b"> </span><span class="accent-gold" id="headline-line3"></span>
    </h1>
    <div id="headline-underline" style="height:4px;background:linear-gradient(90deg,var(--teal-mid),var(--gold));border-radius:999px;max-width:520px;margin:0 auto 28px;transform:scaleX(0);transform-origin:left;transition:transform 0.7s cubic-bezier(.17,.67,.35,1);"></div>

    <p class="hero-sub" id="hero-sub">Stop repeating yourself. Build your academic profile once and discover every scholarship opportunity in the Philippines — effortlessly and seamlessly.</p>

    <div class="hero-actions" id="hero-actions">
      <div class="magnetic-wrap">
        <a href="{{ route('scholarships.index') }}" class="btn-hero-main" id="btn-primary">🎓 Browse Scholarships</a>
      </div>
      <a href="#how" class="btn-ghost">
        <div class="play-ring">▶</div>
        Watch how it works
      </a>
    </div>

    <div class="hero-social-proof" id="hero-proof">
      <div class="avatars">
        <div class="avatar">J</div>
        <div class="avatar">M</div>
        <div class="avatar">A</div>
        <div class="avatar">R</div>
        <div class="avatar">+</div>
      </div>
      <p class="proof-text">Joined by <strong>8,400+ students</strong> across the Philippines</p>
    </div>
  </div>

  <div class="hero-visual" id="hero-visual">
    <div class="cards-row">
      @forelse($scholarships->slice(0, 3) as $scholarship)
        <div class="mini-schol-card" data-tilt>
          <div class="msc-top">
            <span class="msc-org">{{ Str::limit($scholarship->provider_name, 15) }}</span>
            <span class="msc-open {{ $scholarship->status === 'closing' ? 'warn' : '' }}">{{ ucfirst($scholarship->status) }}</span>
          </div>
          <div class="msc-title">{{ Str::limit($scholarship->name, 40) }}</div>
          <div class="msc-match">{{ $scholarship->slots }}</div>
          <div class="msc-match-label">Available Slots</div>
          <div class="msc-bar"><div class="msc-bar-fill" data-width="75"></div></div>
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

<!-- LOGOS -->
<div class="logos">
  <p class="logos-label">Trusted scholarship providers</p>
  <div class="logos-track" id="logos-track">
    @forelse($scholarships as $scholarship)
      <span class="logo-item">{{ $scholarship->provider_name }}</span>
    @empty
      <span class="logo-item">Loading scholarships...</span>
    @endforelse
    {{-- Duplicate for seamless loop --}}
    @forelse($scholarships as $scholarship)
      <span class="logo-item">{{ $scholarship->provider_name }}</span>
    @empty
    @endforelse
  </div>
</div>



<!-- HOW IT WORKS -->
<section class="how" id="how">
  <div class="how-inner">
    <div class="section-eyebrow" id="how-eyebrow">How It Works</div>
    <h2 class="section-title" id="how-title">From profile to scholarship —<br><em>simplified.</em></h2>
    <div class="steps">
      <div class="step" id="step-1">
        <div class="step-num-circle">01</div>
        <div class="step-title">Build Your Profile</div>
        <div class="step-desc">Create your academic identity once — GPA, income bracket, course, and documents. Your Student Wallet stores everything securely.</div>
      </div>
      <div class="step" id="step-2">
        <div class="step-num-circle">02</div>
        <div class="step-title">Find Your Fit</div>
        <div class="step-desc">Our platform helps you discover scholarships that align with your qualifications — apply where you actually qualify.</div>
      </div>
      <div class="step" id="step-3">
        <div class="step-num-circle">03</div>
        <div class="step-title">Track in Real-Time</div>
        <div class="step-desc">Follow every stage of your application. Get notified via in-app and email — even with limited internet access.</div>
      </div>
    </div>
  </div>
</section>

<!-- SCHOLARSHIPS -->
<section class="scholarships" id="scholarships">
  <div class="scholarships-inner">
    <div class="sch-top">
      <div>
        <div class="section-eyebrow">Featured Scholarships</div>
        <h2 class="section-title" style="margin-bottom:0;font-size:clamp(28px,3vw,42px);">Find yours today.</h2>
      </div>
      <div class="filters">
        <button class="filter active">All</button>
        <button class="filter">Merit-Based</button>
        <button class="filter">Need-Based</button>
        <button class="filter">STEM</button>
        <button class="filter">Arts</button>
      </div>
    </div>
    <div class="s-grid" id="s-grid">
      @forelse($scholarships as $scholarship)
        <div class="s-card">
          <div class="sc-org">
            <span>{{ Str::limit($scholarship->provider_name, 20) }}</span>
            <span class="sc-status {{ $scholarship->status === 'open' ? 'open' : 'closing' }}">{{ ucfirst($scholarship->status) }}</span>
          </div>
          <div class="sc-title">{{ Str::limit($scholarship->name, 50) }}</div>
          <div class="sc-details">
            <div class="sc-detail"><span class="sc-detail-dot"></span>GPA {{ $scholarship->gpa_requirement }} or higher</div>
            <div class="sc-detail"><span class="sc-detail-dot"></span>Income bracket: {{ $scholarship->income_bracket }}</div>
            <div class="sc-detail"><span class="sc-detail-dot"></span>{{ $scholarship->slots }} available slots</div>
          </div>
          <div class="sc-match">
            <div class="sc-match-row">
              <span class="sc-match-label">Available Slots</span>
              <span class="sc-match-pct">{{ $scholarship->slots }}</span>
            </div>
            <div class="sc-bar-bg"><div class="sc-bar-fill" data-width="75"></div></div>
          </div>
        </div>
      @empty
        <div class="s-card" style="grid-column:span 3;">
          <p style="text-align:center;color:var(--teal-pale);">No scholarships available at the moment. Check back soon!</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features" id="features">
  <div class="features-inner">
    <div class="section-eyebrow">Platform Features</div>
    <h2 class="section-title" id="feat-title">Built for fairness,<br>accessibility, <em>and you.</em></h2>
    <div class="feat-grid">
      <div class="feat-card highlighted" id="feat-highlight">
        <div>
          <div class="feat-icon" style="font-size:26px;width:56px;height:56px;">🎯</div>
          <div class="feat-title">Smart Scholarship Discovery</div>
          <div class="feat-desc">Easily browse and find scholarships based on your GPA, income, course, and location. Stop applying blindly — find the best fit for your academic journey.</div>
        </div>
        <div class="feat-right-items">
          <div class="feat-right-item"><span class="fri-icon">⚡</span><div><div class="fri-title">Instant Searching</div><div class="fri-sub">Browse thousands of grants instantly</div></div></div>
          <div class="feat-right-item"><span class="fri-icon">🔍</span><div><div class="fri-title">Advanced Filtering</div><div class="fri-sub">Narrow down your best options</div></div></div>
          <div class="feat-right-item"><span class="fri-icon">🔄</span><div><div class="fri-title">Real-time Updates</div><div class="fri-sub">Never miss a new deadline</div></div></div>
        </div>
      </div>
      <div class="feat-card" id="feat-1">
        <div class="feat-icon">🙈</div>
        <div class="feat-title">Blind Screening</div>
        <div class="feat-desc">Evaluators review without seeing your name, gender, or school — promoting merit-based, bias-free selection.</div>
      </div>
      <div class="feat-card" id="feat-2">
        <div class="feat-icon">⚖️</div>
        <div class="feat-title">Dynamic Weighted Scoring</div>
        <div class="feat-desc">Organizations customize GPA vs. financial need weighting — every scholarship plays by its own fair rules.</div>
      </div>
      <div class="feat-card" id="feat-3">
        <div class="feat-icon">🗂️</div>
        <div class="feat-title">Student Document Wallet</div>
        <div class="feat-desc">Upload your documents once. Use them for every application. No more re-scanning the same transcripts.</div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta" id="cta">
  <div class="cta-inner">
    <div class="cta-glow"></div>
    <div class="cta-badge" id="cta-badge">✨ Free for all Filipino students</div>
    <h2 class="cta-title" id="cta-title">Your scholarship<br>is <em>waiting for you.</em></h2>
    <p class="cta-sub" id="cta-sub">Join thousands of Filipino students who found their funding through ScholarLink.</p>
    <div class="cta-btns" id="cta-btns">
      <div class="magnetic-wrap">
        <a href="{{ route('register') }}" class="btn-cta-main">🎓 Create Free Account</a>
      </div>
      <a href="{{ route('scholarships.index') }}" class="btn-cta-sec">Browse Scholarships →</a>
    </div>
    <div class="stats-row">
      <div class="stat" id="stat-1"><div class="stat-num" data-target="120">0</div><div class="stat-label">Active Scholarships</div></div>
      <div class="stat" id="stat-2"><div class="stat-num" data-target="8400">0</div><div class="stat-label">Students Helped</div></div>
      <div class="stat" id="stat-3"><div class="stat-num" data-target="21">0</div><div class="stat-label">₱ Million in Grants</div></div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-logo">
          <img src="{{ asset('logo-light.png.png') }}" alt="ScholarLink logo" class="footer-logo-mark">
          <span>ScholarLink</span>
        </div>
        <div class="footer-tagline">Bridging Filipino students to scholarship opportunities — one profile, every scholarship.</div>
      </div>
      <div>
        <div class="footer-col-title">Platform</div>
        <ul class="footer-links">
          <li><a href="{{ route('scholarships.index') }}" style="color:inherit;text-decoration:none;">Browse</a></li>
          <li><a href="#how" style="color:inherit;text-decoration:none;">How It Works</a></li>
          <li><a href="{{ route('organizations') }}" style="color:inherit;text-decoration:none;">For Organizations</a></li>
          <li><a href="#features" style="color:inherit;text-decoration:none;">Smart Discovery</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Account</div>
        <ul class="footer-links">
          <li><a href="{{ route('register') }}" style="color:inherit;text-decoration:none;">Sign Up</a></li>
          <li><a href="{{ route('login') }}" style="color:inherit;text-decoration:none;">Log In</a></li>
          <li><a href="{{ route('applications.index') }}" style="color:inherit;text-decoration:none;">My Applications</a></li>
          <li><a href="{{ route('applicant.documents.index') }}" style="color:inherit;text-decoration:none;">Document Wallet</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <li><a href="{{ route('privacy') }}" style="color:inherit;text-decoration:none;">Privacy Policy</a></li>
          <li><a href="{{ route('terms') }}" style="color:inherit;text-decoration:none;">Terms of Service</a></li>
          <li><a href="{{ route('data-privacy') }}" style="color:inherit;text-decoration:none;">Data Privacy Act</a></li>
          <li><a href="mailto:support@scholarlink.ph" style="color:inherit;text-decoration:none;">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© 2026 ScholarLink. Philippines 🇵🇭</div>
      <div class="footer-badge">SOFTWARE DESIGN PROJECT</div>
    </div>
  </div>
</footer>

<script>
// ── GSAP + ScrollTrigger Init ───────────────────────────────
gsap.registerPlugin(ScrollTrigger);

// ── SCROLL PROGRESS ──────────────────────────────────────────
const progressBar = document.getElementById('scroll-progress');
window.addEventListener('scroll', () => {
  const pct = window.scrollY / (document.body.scrollHeight - window.innerHeight);
  progressBar.style.transform = `scaleX(${pct})`;
});

// ── PARTICLE SYSTEM ──────────────────────────────────────────
const canvas = document.getElementById('particle-canvas');
const ctx = canvas.getContext('2d');
let W, H, particles = [], mouse = { x: -9999, y: -9999 };

function resizeCanvas() {
  W = canvas.width = window.innerWidth;
  H = canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);
document.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });

const COLORS = ['rgba(15,76,92,', 'rgba(232,168,56,', 'rgba(122,172,170,'];

class Particle {
  constructor() { this.reset(true); }
  reset(initial = false) {
    this.x = Math.random() * W;
    this.y = initial ? Math.random() * H : -10;
    this.baseX = this.x; this.baseY = this.y;
    this.vx = (Math.random() - 0.5) * 0.3;
    this.vy = Math.random() * 0.4 + 0.1;
    this.size = Math.random() * 3.5 + 1;
    this.alpha = Math.random() * 0.5 + 0.15;
    this.color = COLORS[Math.floor(Math.random() * COLORS.length)];
    this.life = 1; this.decay = Math.random() * 0.001 + 0.0002;
  }
  update() {
    const dx = mouse.x - this.x, dy = mouse.y - this.y;
    const dist = Math.sqrt(dx * dx + dy * dy);
    const force = Math.max(0, (120 - dist) / 120);
    if (dist < 120) {
      this.x -= (dx / dist) * force * 2.5;
      this.y -= (dy / dist) * force * 2.5;
    } else {
      this.x += (this.baseX - this.x) * 0.02;
      this.y += (this.baseY - this.y) * 0.02;
    }
    this.x += this.vx; this.y += this.vy;
    this.baseX += this.vx; this.baseY += this.vy;
    this.life -= this.decay;
    if (this.y > H + 10 || this.life <= 0) this.reset();
  }
  draw() {
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
    ctx.fillStyle = this.color + (this.alpha * this.life) + ')';
    ctx.fill();
  }
}

for (let i = 0; i < 180; i++) particles.push(new Particle());

function animateParticles() {
  ctx.clearRect(0, 0, W, H);
  // Draw connection lines
  for (let i = 0; i < particles.length; i++) {
    for (let j = i + 1; j < particles.length; j++) {
      const dx = particles[i].x - particles[j].x;
      const dy = particles[i].y - particles[j].y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 120) {
        ctx.beginPath();
        ctx.moveTo(particles[i].x, particles[i].y);
        ctx.lineTo(particles[j].x, particles[j].y);
        ctx.strokeStyle = `rgba(122,172,170,${(1 - dist / 120) * 0.18})`;
        ctx.lineWidth = 0.8;
        ctx.stroke();
      }
    }
    particles[i].update();
    particles[i].draw();
  }
  requestAnimationFrame(animateParticles);
}
animateParticles();

// ── MAGNETIC BUTTONS ─────────────────────────────────────────
document.querySelectorAll('.magnetic-wrap').forEach(wrap => {
  const btn = wrap.querySelector('a, button');
  wrap.addEventListener('mousemove', e => {
    const rect = wrap.getBoundingClientRect();
    const cx = rect.left + rect.width / 2;
    const cy = rect.top + rect.height / 2;
    const dx = (e.clientX - cx) * 0.35;
    const dy = (e.clientY - cy) * 0.35;
    gsap.to(btn, { x: dx, y: dy, duration: 0.4, ease: 'power2.out' });
    // Update radial gradient position
    const px = ((e.clientX - rect.left) / rect.width) * 100;
    const py = ((e.clientY - rect.top) / rect.height) * 100;
    btn.style.setProperty('--mx', px + '%');
    btn.style.setProperty('--my', py + '%');
  });
  wrap.addEventListener('mouseleave', () => {
    gsap.to(btn, { x: 0, y: 0, duration: 0.6, ease: 'elastic.out(1,0.5)' });
  });
});

// ── 3D TILT CARDS ────────────────────────────────────────────
document.querySelectorAll('[data-tilt]').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const cx = rect.left + rect.width / 2;
    const cy = rect.top + rect.height / 2;
    const rx = (e.clientY - cy) / rect.height * -16;
    const ry = (e.clientX - cx) / rect.width * 16;
    gsap.to(card, { rotateX: rx, rotateY: ry, scale: 1.04, duration: 0.3, ease: 'power2.out', transformPerspective: 800 });
  });
  card.addEventListener('mouseleave', () => {
    gsap.to(card, { rotateX: 0, rotateY: 0, scale: 1, duration: 0.5, ease: 'elastic.out(1,0.6)' });
  });
});

// ── NAV REVEAL ───────────────────────────────────────────────
gsap.to('#main-nav', { y: 0, duration: 0.7, delay: 0.4, ease: 'power3.out' });

// ── HERO ENTRANCE ─────────────────────────────────────────────
const tl = gsap.timeline({ delay: 0.6 });
tl.to('.hero-badge', { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' })
  .call(() => startHandwritingAnimation(), null, 0.2)
  .to('#hero-sub', { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, '+=0.4')
  .to('#hero-actions', { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3')
  .to('#hero-proof', { opacity: 1, duration: 0.6, ease: 'power3.out' }, '-=0.3')
  .to('#hero-visual', { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out' }, '-=0.4');

// ── HANDWRITING ANIMATION ─────────────────────────────────────
// Simulates writing with character-by-character reveal + underline draw effect
function startHandwritingAnimation() {
  const lines = [
    { el: document.getElementById('headline-line1'), text: 'One Profile.' },
    { el: document.getElementById('headline-line2'), text: 'Every' },
    { el: document.getElementById('headline-line3'), text: 'Scholarship.' }
  ];

  // Ensure all start empty
  lines.forEach(l => { l.el.textContent = ''; });

  let lineIdx = 0, charIdx = 0;
  const SPEED = 55;

  // Blinking caret
  const pen = document.createElement('span');
  pen.id = 'typing-caret';
  pen.style.cssText = 'display:inline-block;width:3px;height:0.85em;background:var(--teal-mid);margin-left:3px;vertical-align:middle;border-radius:2px;';
  const blinkStyle = document.createElement('style');
  blinkStyle.textContent = '#typing-caret{animation:caretBlink 0.65s steps(1) infinite}@keyframes caretBlink{0%,100%{opacity:1}50%{opacity:0}}';
  document.head.appendChild(blinkStyle);
  lines[0].el.appendChild(pen);

  function typeNextWithCursor() {
    if (lineIdx >= lines.length) {
      pen.remove();
      drawUnderlineSweep();
      setTimeout(animateBars, 400);
      return;
    }
    const current = lines[lineIdx];
    if (charIdx < current.text.length) {
      pen.remove();
      current.el.textContent += current.text[charIdx];
      current.el.appendChild(pen);
      charIdx++;
      const jitter = Math.random() * 28 - 10;
      setTimeout(typeNextWithCursor, SPEED + jitter);
    } else {
      lineIdx++; charIdx = 0;
      if (lineIdx < lines.length) {
        pen.remove();
        lines[lineIdx].el.appendChild(pen);
      }
      setTimeout(typeNextWithCursor, lineIdx === 1 ? 130 : 70);
    }
  }
  typeNextWithCursor();
}

function drawUnderlineSweep() {
  const underline = document.getElementById('headline-underline');
  if (underline) {
    requestAnimationFrame(() => requestAnimationFrame(() => {
      underline.style.transform = 'scaleX(1)';
    }));
  }
}

function animateBars() {
  document.querySelectorAll('.msc-bar-fill').forEach(bar => {
    const w = bar.dataset.width || 75;
    bar.style.width = w + '%';
  });
}

// ── SCROLL ANIMATIONS ─────────────────────────────────────────
// Steps
['#step-1','#step-2','#step-3'].forEach((id, i) => {
  gsap.to(id, {
    opacity: 1, y: 0, duration: 0.7, delay: i * 0.15,
    ease: 'power3.out',
    scrollTrigger: { trigger: '#how', start: 'top 70%', once: true }
  });
});

// Scholar cards stagger
gsap.utils.toArray('.s-card').forEach((card, i) => {
  gsap.to(card, {
    opacity: 1, y: 0, scale: 1, duration: 0.65, delay: i * 0.1,
    ease: 'power3.out',
    scrollTrigger: { trigger: '#s-grid', start: 'top 75%', once: true,
      onEnter: () => {
        card.querySelectorAll('.sc-bar-fill').forEach(b => {
          b.style.width = (b.dataset.width || 75) + '%';
        });
      }
    }
  });
});

// Features
gsap.to('#feat-highlight', {
  opacity: 1, y: 0, duration: 0.8, ease: 'power3.out',
  scrollTrigger: { trigger: '#features', start: 'top 70%', once: true }
});
['#feat-1','#feat-2','#feat-3'].forEach((id, i) => {
  gsap.to(id, {
    opacity: 1, y: 0, duration: 0.7, delay: 0.15 + i * 0.12,
    ease: 'power3.out',
    scrollTrigger: { trigger: '#features', start: 'top 70%', once: true }
  });
});

// CTA section
const ctaTl = gsap.timeline({
  scrollTrigger: { trigger: '#cta', start: 'top 70%', once: true }
});
ctaTl.to('#cta-badge', { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' })
     .to('#cta-title', { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, '-=0.3')
     .to('#cta-sub', { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3')
     .to('#cta-btns', { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.3');

// Stats counter
function animateCounter(el, target, suffix = '') {
  let start = 0;
  const step = target / 60;
  const timer = setInterval(() => {
    start += step;
    if (start >= target) { el.textContent = target.toLocaleString() + suffix; clearInterval(timer); return; }
    el.textContent = Math.floor(start).toLocaleString() + suffix;
  }, 20);
}

ScrollTrigger.create({
  trigger: '.stats-row', start: 'top 80%', once: true,
  onEnter: () => {
    gsap.to('.stat', { opacity: 1, y: 0, duration: 0.6, stagger: 0.15, ease: 'power3.out' });
    setTimeout(() => {
      animateCounter(document.querySelector('#stat-1 .stat-num'), 120, '+');
      animateCounter(document.querySelector('#stat-2 .stat-num'), 8400, '');
      animateCounter(document.querySelector('#stat-3 .stat-num'), 21, 'M+');
    }, 300);
  }
});

// Parallax hero bg on scroll
gsap.to('.hero-bg-gradient', {
  y: -80, ease: 'none',
  scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true }
});

// Hero visual parallax
gsap.to('#hero-visual', {
  y: 60, ease: 'none',
  scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true }
});

// ── FILTER BUTTONS ───────────────────────────────────────────
document.querySelectorAll('.filter').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    // Animate cards out and back in
    gsap.to('.s-card', { opacity: 0, y: 10, scale: 0.97, duration: 0.2, stagger: 0.04, ease: 'power2.in',
      onComplete: () => {
        gsap.to('.s-card', { opacity: 1, y: 0, scale: 1, duration: 0.35, stagger: 0.07, ease: 'back.out(1.5)' });
      }
    });
  });
});

// ── HOVER GLOW ON CARDS ──────────────────────────────────────
document.querySelectorAll('.mini-schol-card, .s-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    card.style.background = `radial-gradient(circle at ${x}% ${y}%, rgba(240,250,250,0.8), #ffffff 60%)`;
  });
  card.addEventListener('mouseleave', () => { card.style.background = ''; });
});

// ── NAV SCROLL BEHAVIOR ──────────────────────────────────────
let lastScroll = 0;
window.addEventListener('scroll', () => {
  const nav = document.getElementById('main-nav');
  const current = window.scrollY;
  if (current > lastScroll && current > 100) {
    nav.style.transform = 'translateY(-100%)';
  } else {
    nav.style.transform = 'translateY(0)';
  }
  lastScroll = current;
  nav.style.transition = 'transform 0.4s cubic-bezier(.4,0,.2,1), background 0.4s';
  if (current > 20) {
    nav.style.boxShadow = '0 2px 32px rgba(15,76,92,0.07)';
  } else {
    nav.style.boxShadow = 'none';
  }
});

// ── AMBIENT ORB PARALLAX ──────────────────────────────────────
document.addEventListener('mousemove', e => {
  const x = (e.clientX / window.innerWidth - 0.5) * 20;
  const y = (e.clientY / window.innerHeight - 0.5) * 20;
  gsap.to('.orb-teal', { x: x, y: y, duration: 1.5, ease: 'power2.out' });
  gsap.to('.orb-gold', { x: -x * 0.6, y: -y * 0.6, duration: 2, ease: 'power2.out' });
});

// ── SECTION EYEBROW REVEAL ────────────────────────────────────
document.querySelectorAll('.section-eyebrow, .section-title').forEach(el => {
  gsap.from(el, {
    opacity: 0, y: 24, duration: 0.7, ease: 'power3.out',
    scrollTrigger: { trigger: el, start: 'top 85%', once: true }
  });
});

// ── FOOTER LINKS STAGGER ──────────────────────────────────────
gsap.from('footer .footer-col-title, footer .footer-links li', {
  opacity: 0, y: 16, duration: 0.5, stagger: 0.04, ease: 'power2.out',
  scrollTrigger: { trigger: 'footer', start: 'top 85%', once: true }
});

console.log('%cScholarLink ✦ World-class UI loaded', 'color:#E8A838;font-family:serif;font-size:14px;font-weight:bold;');
</script>

<x-chatbot-widget />
<!-- SORO AI MASCOT -->
<style>
  #soro-mascot {
    position: fixed;
    bottom: 28px;
    width: 110px;
    cursor: pointer;
    z-index: 9998;
    transition: left 0.7s cubic-bezier(0.34, 1.56, 0.64, 1),
                right 0.7s cubic-bezier(0.34, 1.56, 0.64, 1),
                transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
    filter: drop-shadow(0 8px 24px rgba(15,76,92,0.22));
    will-change: left, right;
  }

  #soro-mascot.side-left  { left: -120px; right: auto; transform: scaleX(1); }
  #soro-mascot.side-right { right: -120px; left: auto; transform: scaleX(-1); }

  #soro-mascot.side-left.visible  { left: 16px; }
  #soro-mascot.side-right.visible { right: 16px; }

  #soro-mascot img {
    width: 100%;
    display: block;
    transform-origin: bottom center;
  }

  @keyframes soroWave {
    0%   { transform: rotate(0deg); }
    15%  { transform: rotate(-22deg); }
    30%  { transform: rotate(12deg); }
    45%  { transform: rotate(-16deg); }
    60%  { transform: rotate(9deg); }
    75%  { transform: rotate(-5deg); }
    100% { transform: rotate(0deg); }
  }

  #soro-mascot.waving img {
    animation: soroWave 0.9s ease-in-out;
  }

  /* Tooltip bubble */
  #soro-bubble {
    position: fixed;
    bottom: 148px;
    background: var(--white);
    border: 1px solid var(--teal-ghost);
    border-radius: 14px 14px 14px 4px;
    padding: 8px 14px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: var(--teal-mid);
    white-space: nowrap;
    box-shadow: 0 4px 16px rgba(15,76,92,0.12);
    opacity: 0;
    transform: translateY(6px) scale(0.92);
    transition: opacity 0.3s ease, transform 0.3s ease;
    pointer-events: none;
    z-index: 9997;
  }
  #soro-bubble.show {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
</style>

<div id="soro-mascot" class="side-left">
  <img src="{{ asset('soro.png') }}" alt="Soro, the ScholarLink mascot">
</div>
<div id="soro-bubble" id="soro-bubble"></div>

<script>
(function() {
  const mascot  = document.getElementById('soro-mascot');
  const bubble  = document.getElementById('soro-bubble');
  const INTERVAL = 15000;

  const messages = [
    "Hi! I'm Soro! 👋",
    "Found a scholarship yet? 🎓",
    "Apply once, apply everywhere!",
    "Your dream scholar awaits! ✨",
    "Need help finding a grant? 🔍",
    "Don't forget to complete your profile!",
  ];

  let currentSide = 'left';
  let hideTimeout, bubbleTimeout, timerInterval;

  function showSoro() {
    clearTimeout(hideTimeout);
    clearTimeout(bubbleTimeout);

    // Pick a random side
    currentSide = Math.random() > 0.5 ? 'left' : 'right';
    mascot.className = 'side-' + currentSide;

    // Position the bubble
    if (currentSide === 'left') {
      bubble.style.left  = '132px';
      bubble.style.right = 'auto';
      bubble.style.borderRadius = '14px 14px 14px 4px';
    } else {
      bubble.style.right = '132px';
      bubble.style.left  = 'auto';
      bubble.style.borderRadius = '14px 14px 4px 14px';
    }

    // Random message
    bubble.textContent = messages[Math.floor(Math.random() * messages.length)];

    // Slide in
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        mascot.classList.add('visible', 'waving');

        // Show bubble after 0.4s
        bubbleTimeout = setTimeout(() => bubble.classList.add('show'), 400);

        // Remove wave class after animation
        setTimeout(() => mascot.classList.remove('waving'), 1000);

        // Hide after 3.5s
        hideTimeout = setTimeout(hideSoro, 3500);
      });
    });
  }

  function hideSoro() {
    bubble.classList.remove('show');
    mascot.classList.remove('visible');
  }

  // Click to dismiss / re-trigger
  mascot.addEventListener('click', () => {
    if (mascot.classList.contains('visible')) {
      hideSoro();
      resetTimer();
    }
  });

  function resetTimer() {
    clearInterval(timerInterval);
    timerInterval = setInterval(showSoro, INTERVAL);
  }

  // First appearance after 2s, then every 15s
  setTimeout(() => {
    showSoro();
    resetTimer();
  }, 2000);
})();
</script>
@stack('scripts')
</body>
</html>

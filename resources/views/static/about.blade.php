@extends('layouts.public')

@section('title', 'About Us — ScholarLink')

@push('styles')
<style>
    /* ── HERO ── */
    #particle-canvas {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      pointer-events: none; z-index: 10; opacity: 1;
    }
    .about-hero {
      min-height: 70vh; background: var(--teal-dark);
      display: grid; grid-template-columns: 1fr 1fr;
      position: relative; overflow: hidden;
      z-index: 2; /* Sit above canvas if needed, or sit below if canvas is background */
    }
    /* Let the canvas be the absolute background */
    body { position: relative; background: var(--white); }
    section, .impact-bar { position: relative; z-index: 5; background: transparent; }
    .about-hero { z-index: 5; background: var(--teal-dark) !important; color: #fff; }
    .impact-bar { z-index: 5; background: var(--teal-deep) !important; }
    .team-section { background: transparent; }
    .values-section { background: transparent; }
    .hero-left { padding: 80px; display: flex; flex-direction: column; justify-content: center; z-index: 2; }
    .hero-right {
      background: linear-gradient(160deg, var(--teal-mid) 0%, var(--teal-light) 60%, var(--gold) 100%);
      position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;
    }
    .hero-right::before {
      content: ''; position: absolute; inset: 0;
      background: radial-gradient(ellipse 70% 70% at 30% 40%, rgba(249, 214, 121, 0.15) 0%, transparent 70%),
                 radial-gradient(ellipse 50% 50% at 80% 80%, rgba(15, 76, 92, 0.4) 0%, transparent 60%);
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: rgba(201, 168, 76, 0.18); border: 1px solid rgba(201, 168, 76, 0.4);
      color: #F9D679; font-size: 11px; font-weight: 600; letter-spacing: 1.4px;
      text-transform: uppercase; padding: 6px 16px; border-radius: 100px; margin-bottom: 32px; width: fit-content;
    }
    .hero-title { font-family: 'Fraunces', serif; font-size: clamp(40px, 5vw, 64px); line-height: 0.95; letter-spacing: -0.8px; margin-bottom: 16px; color: #fff; }
    .hero-title em { font-weight: 300; font-style: italic; color: var(--gold-light); }
    .hero-sub { font-size: 17px; color: rgba(255, 255, 255, 0.6); max-width: 460px; margin-bottom: 48px; line-height: 1.6; }
    .hero-stats { display: flex; gap: 40px; }
    .hero-stat-num { font-family: 'Fraunces', serif; font-size: 40px; font-weight: 700; color: #F9D679; display: block; }
    .hero-stat-label { font-size: 11px; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; letter-spacing: 1px; }

    /* ── IMPACT BAR ── */
    .impact-bar { background: var(--teal-deep); padding: 50px 48px; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .impact-row { max-width: 1160px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; text-align: center; }
    .impact-num { font-family: 'Fraunces', serif; font-size: 44px; font-weight: 700; color: var(--gold-light); display: block; }
    .impact-label { font-size: 11px; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; letter-spacing: 1px; }

    /* ── TEAM ── */
    .team-section { background: var(--white); padding: 100px 48px; }
    .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 1160px; margin: 0 auto; }
    .team-card { background: rgba(240, 250, 250, 0.7); padding: 48px 24px; border-radius: 24px; text-align: center; border: 1px solid var(--teal-mist); transition: transform 0.3s ease; }
    .team-card:hover { transform: translateY(-8px); border-color: var(--teal-pale); }
    .avatar { width: 90px; height: 90px; border-radius: 50%; background: var(--teal-mid); margin: 0 auto 24px; display: flex; align-items: center; justify-content: center; color: var(--gold-light); font-family: 'Fraunces', serif; font-size: 32px; font-weight: 700; border: 3px solid #fff; box-shadow: 0 8px 24px rgba(15,76,92,0.12); }
    .avatar.amber { background: var(--gold); color: var(--teal-mid); }
    .team-name { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
    .team-role { font-size: 13px; color: var(--teal-pale); margin-bottom: 16px; font-weight: 500; }
    .team-badge { background: rgba(15, 76, 92, 0.08); color: var(--teal-mid); padding: 5px 14px; border-radius: 100px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }

    .section-label { display: inline-flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 700; letter-spacing: 1.6px; text-transform: uppercase; color: var(--gold); margin-bottom: 12px; }
    .section-title { font-family: 'Fraunces', serif; font-size: 36px; font-weight: 700; color: var(--navy); margin-bottom: 24px; opacity: 0; transform: translateY(20px); }
    
    /* ── FUN ELEMENTS ── */
    .floating-icon { position: absolute; font-size: 32px; opacity: 0.15; pointer-events: none; z-index: 1; }
    .story-card { background: var(--white); border: 1px solid var(--teal-ghost); border-radius: var(--radius-md); padding: 48px; transition: all 0.5s cubic-bezier(.17,.67,.35,1); box-shadow: var(--shadow-sm); }
    .story-card:hover { transform: translateY(-10px) rotate(1deg); box-shadow: var(--shadow-lg); border-color: var(--teal-mid-border); }
    
    .team-card { 
      background: var(--white); 
      padding: 48px 24px; 
      border-radius: 24px; 
      text-align: center; 
      border: 1px solid var(--teal-ghost); 
      transition: all 0.4s cubic-bezier(.17,.67,.35,1.3); 
      position: relative;
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    .team-card:hover { 
      transform: translateY(-12px) scale(1.02); 
      border-color: var(--gold); 
      box-shadow: 0 32px 64px rgba(15,76,92,0.15); 
    }
    .team-card::after {
      content: '✨'; position: absolute; top: 12px; right: 12px; font-size: 18px; 
      opacity: 0; transition: opacity 0.3s;
    }
    .team-card:hover::after { opacity: 1; }
    
    .team-fact {
      font-size: 13px; color: var(--teal-mid); margin-top: 16px;
      font-style: italic; font-weight: 500;
      opacity: 0; transform: translateY(10px); transition: all 0.4s cubic-bezier(.17,.67,.35,1.3);
      background: var(--gold-pale); padding: 8px 12px; border-radius: 12px;
    }
    .team-card:hover .team-fact { opacity: 1; transform: translateY(0); }
    
    @media (max-width: 900px) {
        .about-hero { grid-template-columns: 1fr; }
        .hero-right { display: none; }
        .impact-row, .team-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .impact-row, .team-grid { grid-template-columns: 1fr; }
        .hero-left { padding: 48px 24px; }
    }
</style>
@endpush

@section('content')
<canvas id="particle-canvas"></canvas>

<!-- HERO SECTION -->
<section class="about-hero">
    <div class="hero-left">
        <div class="hero-badge">About ScholarLink</div>
        <h1 class="hero-title">
            Bridging Filipino<br>
            <em>Students to</em> <strong>Scholarships</strong>
        </h1>
        <p class="hero-sub">A centralized platform making scholarship access equitable, transparent, and simple for every student in the Philippines.</p>
        <div class="hero-stats">
            <div><span class="hero-stat-num">{{ $stats['total'] }}+</span><span class="hero-stat-label">Listed</span></div>
            <div><span class="hero-stat-num">100%</span><span class="hero-stat-label">Free</span></div>
        </div>
    </div>
    <div class="hero-right">
        <div style="position: relative; z-index: 2; text-align: center; color: #fff;">
            <div style="width: 100px; height: 100px; border-radius: 24px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; backdrop-filter: blur(10px);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <h3 style="font-family: 'Fraunces', serif; font-size: 24px;">Our Core Values</h3>
            <div style="display: flex; flex-direction: column; gap: 10px; text-align: left; margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 12px 20px; font-size: 13px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #F9D679;"></span> Transparency in listings
                </div>
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 12px 20px; font-size: 13px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #2A8FA0;"></span> Regional Accessibility
                </div>
                <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; padding: 12px 20px; font-size: 13px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #E8A838;"></span> Zero student fees
                </div>
            </div>
        </div>
    </div>
</section>

<!-- IMPACT BAR -->
<div class="impact-bar">
    <div class="impact-row">
        <div><span class="impact-num">15</span><span class="impact-label">Closed Apps</span></div>
        <div><span class="impact-num">{{ $stats['open'] }}</span><span class="impact-label">Open Now</span></div>
        <div><span class="impact-num">{{ $stats['applicants'] }}+</span><span class="impact-label">Applicants</span></div>
        <div><span class="impact-num">₱0</span><span class="impact-label">Student Cost</span></div>
    </div>
</div>

<!-- STORY SECTION -->
<section style="padding: 100px 48px; position: relative; overflow: hidden;">
    <div class="floating-icon" style="top: 10%; left: 5%; transform: rotate(-15deg);">🎓</div>
    <div class="floating-icon" style="bottom: 15%; right: 8%; transform: rotate(20deg);">📚</div>
    
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: stretch;">
            <div class="story-card reveal">
                <p class="section-label">Our Mission</p>
                <h2 class="section-title">Empowering Leaders</h2>
                <p style="font-size: 16px; color: var(--teal-pale); line-height: 1.8; margin-bottom: 24px;">ScholarLink was born from a simple observation: thousands of Filipino students miss out on scholarships simply because they don't know they exist or find the application process too daunting. We've built a world-class platform that centralizes opportunities from CHED, DOST, and private partners into one intelligent portal.</p>
                <p style="font-size: 16px; color: var(--teal-pale); line-height: 1.8;">We simplify educational funding by consolidating opportunities, automating matches, and promoting fair evaluation through blind screening.</p>
            </div>
            <div class="story-card reveal" style="margin-top: 60px;">
                <p class="section-label">The Strategy</p>
                <h2 class="section-title">Smart Matching</h2>
                <p style="font-size: 16px; color: var(--teal-pale); line-height: 1.8; margin-bottom: 24px;">We use AI matching and "Blind Screening" to ensure fairness, letting merit — not connections — decide a student's future. By masking personal identifiable information during the initial review, we guarantee an objective evaluation process.</p>
                <p style="font-size: 16px; color: var(--teal-pale); line-height: 1.8;">Our technology doesn't just list scholarships; it helps students discover the ones they are most likely to win, saving time and increasing success rates.</p>
            </div>
        </div>
    </div>
</section>

<!-- VALUES SECTION -->
<section style="padding: 80px 48px;" class="values-section">
    <div style="max-width: 1160px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 60px;">
            <p class="section-label">Foundations</p>
            <h2 class="section-title">What We Stand For</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            <div style="background: rgba(255,255,255,0.7); padding: 40px; border-radius: 24px; border: 1px solid var(--teal-mist); transition: transform 0.3s;">
                <div style="width: 48px; height: 48px; background: var(--teal-mid); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">Transparency</h4>
                <p style="font-size: 14px; color: var(--teal-pale); line-height: 1.6;">No hidden steps. Students know exactly where they stand in the application process at all times with real-time tracking.</p>
            </div>
            <div style="background: rgba(255,255,255,0.7); padding: 40px; border-radius: 24px; border: 1px solid var(--teal-mist); transition: transform 0.3s;">
                <div style="width: 48px; height: 48px; background: var(--teal-mid); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/></svg>
                </div>
                <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">Equity</h4>
                <p style="font-size: 14px; color: var(--teal-pale); line-height: 1.6;">Actively surfacing opportunities for PWDs, indigenous groups, and underserved communities to ensure no one is left behind.</p>
            </div>
            <div style="background: rgba(255,255,255,0.7); padding: 40px; border-radius: 24px; border: 1px solid var(--teal-mist); transition: transform 0.3s;">
                <div style="width: 48px; height: 48px; background: var(--teal-mid); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">Innovation</h4>
                <p style="font-size: 14px; color: var(--teal-pale); line-height: 1.6;">Using AI matching and centralized document storage to bridge the digital divide for all Filipino students.</p>
            </div>
        </div>
    </div>
</section>

<!-- TEAM SECTION -->
<section class="team-section" style="padding: 100px 48px;">
    <div style="text-align: center; margin-bottom: 64px;">
        <p class="section-label">The Creators</p>
        <h2 class="section-title">Meet the Team</h2>
        <p style="color: var(--teal-pale); font-size: 15px;">BSCpE 2-2 Students from Pamantasan ng Lungsod ng Maynila</p>
    </div>
    <div class="team-grid">
        <div class="team-card reveal-item">
            <div class="avatar amber">F</div>
            <h5 class="team-name">Franchezca Banayad</h5>
            <p class="team-role">Full Stack — Backend Lead</p>
            <span class="team-badge">BSCpE 2-2</span>
            <p class="team-fact">"The logic architect. Making sure every query is as sharp as a diamond."</p>
        </div>
        <div class="team-card reveal-item">
            <div class="avatar amber">Y</div>
            <h5 class="team-name">Ysa Frigillana</h5>
            <p class="team-role">UI/UX & Full Stack — Frontend Lead</p>
            <span class="team-badge">BSCpE 2-2</span>
            <p class="team-fact">"Designing with empathy. If it's beautiful and works, she probably built it."</p>
        </div>
        <div class="team-card reveal-item">
            <div class="avatar amber">P</div>
            <h5 class="team-name">Princess Sanchez</h5>
            <p class="team-role">Full Stack — Database Lead</p>
            <span class="team-badge">BSCpE 2-2</span>
            <p class="team-fact">"Guardian of the data. Ensuring your profile stays safe and matched."</p>
        </div>
        <div class="team-card reveal-item">
            <div class="avatar">K</div>
            <h5 class="team-name">Karl Esteban</h5>
            <p class="team-role">Full Stack Developer</p>
            <span class="team-badge">BSCpE 2-2</span>
            <p class="team-fact">"Code ninja. Tackling complex features with speed and precision."</p>
        </div>
        <div class="team-card reveal-item">
            <div class="avatar">E</div>
            <h5 class="team-name">Elena Lanuza</h5>
            <p class="team-role">Front End Developer</p>
            <span class="team-badge">BSCpE 2-2</span>
            <p class="team-fact">"The visual specialist. Bringing colors and components to life."</p>
        </div>
        <div class="team-card reveal-item">
            <div class="avatar">J</div>
            <h5 class="team-name">Jose Jerico Escaño</h5>
            <p class="team-role">Front End Developer</p>
            <span class="team-badge">BSCpE 2-2</span>
            <p class="team-fact">"Master of the layout. Ensuring everything is in its perfect place."</p>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section style="background: var(--teal-mid); padding: 100px 48px; text-align: center; color: #fff;">
    <div style="max-width: 600px; margin: 0 auto;">
        <h2 style="font-family: 'Fraunces', serif; font-size: 42px; margin-bottom: 24px;">Start your journey today.</h2>
        <p style="font-size: 18px; opacity: 0.8; margin-bottom: 40px;">Join the movement of students finding their future through ScholarLink.</p>
        <div style="display: flex; gap: 16px; justify-content: center;">
            <a href="{{ route('scholarships.index') }}" style="background: var(--gold); color: var(--teal-mid); padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: transform 0.2s;">Find a Scholarship</a>
            <a href="{{ route('register') }}" style="border: 2px solid rgba(255,255,255,0.3); color: #fff; padding: 12px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: background 0.2s;">Create Profile</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
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

// ── REVEAL ANIMATIONS ───────────────────────────────────────
gsap.registerPlugin(ScrollTrigger);

// Hero Entrance
const heroTl = gsap.timeline();
heroTl.to('.hero-title', { opacity: 1, y: 0, duration: 1, ease: 'power4.out', delay: 0.2 })
      .to('.hero-sub', { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.6')
      .from('.hero-stats div', { opacity: 0, y: 20, stagger: 0.1, duration: 0.8, ease: 'power3.out' }, '-=0.4');

// Section Reveals
gsap.utils.toArray('.section-title').forEach(title => {
  gsap.to(title, {
    opacity: 1, y: 0, duration: 1,
    scrollTrigger: { trigger: title, start: 'top 85%' }
  });
});

gsap.utils.toArray('.story-card').forEach(card => {
  gsap.from(card, {
    opacity: 0, y: 40, duration: 1.2, ease: 'power3.out',
    scrollTrigger: { trigger: card, start: 'top 80%' }
  });
});

// Team Stagger
gsap.from('.reveal-item', {
  opacity: 0, y: 30, scale: 0.95, stagger: 0.1, duration: 0.8, ease: 'back.out(1.7)',
  scrollTrigger: { trigger: '.team-grid', start: 'top 75%' }
});

// Floating Icons Parallax
document.addEventListener('mousemove', e => {
  const x = (e.clientX / window.innerWidth - 0.5) * 40;
  const y = (e.clientY / window.innerHeight - 0.5) * 40;
  gsap.to('.floating-icon', { x: x, y: y, duration: 2, ease: 'power2.out', stagger: 0.1 });
});

// Impact Counters
function animateCounter(el, target) {
  let obj = { val: 0 };
  gsap.to(obj, {
    val: target, duration: 2, ease: 'power3.out',
    onUpdate: () => { el.textContent = Math.floor(obj.val).toLocaleString() + (target === 100 ? '%' : (el.textContent.includes('₱') ? '' : '+')); }
  });
}

ScrollTrigger.create({
  trigger: '.impact-row', start: 'top 80%',
  onEnter: () => {
    document.querySelectorAll('.impact-num').forEach(el => {
      const target = parseInt(el.textContent.replace(/[₱+,%]/g, ''));
      animateCounter(el, target);
    });
  }
});

</script>
@endpush

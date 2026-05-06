@extends('layouts.public')

@section('title', 'For Organizations — ScholarLink')

@push('styles')
<style>
    #particle-canvas {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      pointer-events: none; z-index: 10; opacity: 1;
    }
    body { position: relative; background: var(--teal-foam); }
    .org-container { position: relative; z-index: 5; }
    .feature-card { background: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(8px); }
</style>
@endpush

@section('content')
<canvas id="particle-canvas"></canvas>

<div class="org-container" style="max-width: 1100px; margin: 0 auto; padding: 80px 48px;">
    <div style="text-align: center; margin-bottom: 64px;">
        <span style="font-size: 11px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--gold);">Partnership</span>
        <h1 style="font-family: 'Fraunces', serif; font-size: 52px; font-weight: 900; color: var(--navy); margin-top: 12px;">Scale your impact with<br><em style="color: var(--teal-mid); font-style: italic; font-weight: 300;">smart scholarship management.</em></h1>
        <p style="font-size: 18px; color: var(--teal-pale); max-width: 600px; margin: 24px auto 0;">From local foundations to national agencies, we provide the tools to reach the right students and manage your awards seamlessly.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 64px;">
        <div class="feature-card" style="border-radius: 20px; padding: 32px; border: 1px solid var(--teal-mist); text-align: center;">
            <div style="font-size: 32px; margin-bottom: 16px;">🏢</div>
            <h3 style="font-family: 'Fraunces', serif; font-size: 20px; color: var(--navy); margin-bottom: 12px;">Organization Portal</h3>
            <p style="font-size: 14px; color: #4A7A80;">A centralized dashboard to list, manage, and track all your scholarship programs in real-time.</p>
        </div>
        <div class="feature-card" style="border-radius: 20px; padding: 32px; border: 1px solid var(--teal-mist); text-align: center;">
            <div style="font-size: 32px; margin-bottom: 16px;">🙈</div>
            <h3 style="font-family: 'Fraunces', serif; font-size: 20px; color: var(--navy); margin-bottom: 12px;">Blind Screening</h3>
            <p style="font-size: 14px; color: #4A7A80;">Reduce bias in your selection process with our automated PII masking for evaluators.</p>
        </div>
        <div class="feature-card" style="border-radius: 20px; padding: 32px; border: 1px solid var(--teal-mist); text-align: center;">
            <div style="font-size: 32px; margin-bottom: 16px;">⚖️</div>
            <h3 style="font-family: 'Fraunces', serif; font-size: 20px; color: var(--navy); margin-bottom: 12px;">Weighted Scoring</h3>
            <p style="font-size: 14px; color: #4A7A80;">Define your own criteria—weight GPA vs. financial need to find the perfect fit for your mission.</p>
        </div>
    </div>

    <div style="background: var(--teal-mid); border-radius: 24px; padding: 56px; color: var(--white); display: flex; align-items: center; justify-content: space-between; gap: 48px; position: relative; overflow: hidden; z-index: 5;">
        <div style="max-width: 500px; position: relative; z-index: 2;">
            <h2 style="font-family: 'Fraunces', serif; font-size: 32px; margin-bottom: 16px; color: var(--gold-light);">Ready to join the movement?</h2>
            <p style="font-size: 16px; opacity: 0.8; margin-bottom: 32px;">Partner with us to streamline your operations and reach thousands of deserving Filipino students instantly.</p>
            <a href="mailto:partnerships@scholarlink.ph" style="display: inline-block; background: var(--gold); color: var(--teal-mid); padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: transform 0.2s;">Get Started as a Partner</a>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; position: relative; z-index: 2;">
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: var(--gold-light);">100%</div>
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Digital Workflow</div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: var(--gold-light);">50+</div>
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Partner Orgs</div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: var(--gold-light);">Zero</div>
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Paper Waste</div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 700; color: var(--gold-light);">AI</div>
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Matching Engine</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush

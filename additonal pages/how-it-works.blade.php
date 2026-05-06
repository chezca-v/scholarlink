@extends('layouts.app')

@section('title', 'How It Works')
@section('meta_description', 'From profile setup to receiving your scholarship — ScholarLink makes the entire process simple, transparent, and guided.')

@push('styles')
<style>
  :root {
    --teal:#0F4C5C;--teal-light:#1A6B7A;--teal-lighter:#2A8FA0;
    --amber:#E8A838;--gold-light:#F9D679;--white:#FFFFFF;--cloud:#F4F6FA;
    --mist:#E2E8F0;--slate:#8A95A3;--ink:#1C1C2E;
    --grad-primary:linear-gradient(135deg,#0F4C5C,#1A6B7A);
    --grad-amber:linear-gradient(135deg,#E8A838,#F9D679);
    --grad-hero:linear-gradient(160deg,#0F4C5C,#2A8FA0);
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
  html{scroll-behavior:smooth;}
  body{font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);line-height:1.6;}

  /* HERO */
  .hero{background:var(--grad-hero);padding:148px 40px 60px;text-align:center;position:relative;overflow:hidden;}
  .hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 20% 50%,rgba(201,168,76,.12) 0%,transparent 70%),radial-gradient(ellipse 50% 70% at 80% 40%,rgba(249,214,121,.08) 0%,transparent 60%);pointer-events:none;animation:floatBg 12s ease-in-out infinite alternate;}
  @keyframes floatBg{from{transform:translateY(0) scale(1);}to{transform:translateY(-20px) scale(1.05);}}
  .hero>*{position:relative;}
  .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.35);color:var(--gold-light);font-size:12px;font-weight:600;letter-spacing:1.3px;text-transform:uppercase;padding:6px 18px;border-radius:100px;margin-bottom:28px;animation:fadeUp .6s ease both .05s;}
  .hero-title{font-family:'DM Sans',sans-serif;font-size:clamp(38px,6vw,62px);font-weight:700;color:var(--white);line-height:1.1;max-width:740px;margin:0 auto 24px;animation:fadeUp .6s ease both .18s;}
  .hero-title em{font-family:'Fraunces',serif;font-style:italic;color:var(--gold-light);}
  .hero-sub{font-size:18px;color:rgba(255,255,255,.72);max-width:560px;margin:0 auto 0;font-weight:400;animation:fadeUp .6s ease both .30s;}
  @keyframes fadeUp{from{opacity:0;transform:translateY(30px);filter:blur(6px);}to{opacity:1;transform:translateY(0);filter:blur(0);}}

  /* ROLE PILLS */
  .hero-role-pills{display:flex;justify-content:center;gap:12px;padding:28px 40px 0;background:var(--grad-hero);flex-wrap:wrap;}
  .role-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:100px;border:1.5px solid rgba(255,255,255,.2);color:rgba(255,255,255,.7);font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;background:rgba(255,255,255,.06);}
  .role-pill.active,.role-pill:hover{background:rgba(255,255,255,.14);color:var(--white);border-color:rgba(255,255,255,.4);}
  .role-pill-dot{width:8px;height:8px;border-radius:50%;background:var(--gold-light);flex-shrink:0;}

  /* SHARED */
  section{padding:88px 40px;}
  .container{max-width:1100px;margin:0 auto;}
  .section-label{display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;letter-spacing:1.6px;text-transform:uppercase;color:var(--amber);margin-bottom:12px;}
  .section-label::before{content:'';display:inline-block;width:20px;height:2px;background:linear-gradient(90deg,#0F4C5C,#E8A838);border-radius:2px;flex-shrink:0;}
  .section-title{font-family:'Fraunces',serif;font-size:32px;font-weight:700;color:var(--ink);margin-bottom:20px;line-height:1.25;}

  /* STEPS — alternating */
  .steps-section{padding:88px 0;background:var(--white);}
  .steps-alt{max-width:1100px;margin:0 auto;padding:0 40px;display:flex;flex-direction:column;gap:80px;}
  .step-alt{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;}
  .step-alt.flip .step-alt-text{order:2;} .step-alt.flip .step-alt-visual{order:1;}
  .step-badge{display:inline-block;font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--amber);margin-bottom:12px;background:rgba(232,168,56,.1);padding:4px 12px;border-radius:100px;}
  .step-h3{font-family:'Fraunces',serif;font-size:26px;font-weight:700;color:var(--ink);margin-bottom:16px;line-height:1.25;}
  .step-p{font-size:15px;color:#4A5568;line-height:1.75;margin-bottom:20px;}
  .step-features{display:flex;flex-direction:column;gap:8px;}
  .step-feat{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--teal);font-weight:600;}
  .step-feat::before{content:'✓';width:20px;height:20px;border-radius:50%;background:rgba(15,76,92,.1);display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;}
  .step-alt-visual{border-radius:24px;height:280px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;}
  .bg-teal{background:linear-gradient(135deg,#0F4C5C,#1A6B7A);}
  .bg-amber{background:linear-gradient(135deg,#E8A838,#F9D679);}
  .bg-cloud{background:var(--cloud);border:1.5px solid var(--mist);}
  .bg-ink{background:#1C1C2E;}
  .bg-mint{background:linear-gradient(135deg,#D1FAE5,#A7F3D0);}
  .bg-purple{background:linear-gradient(135deg,#EDE9FE,#DDD6FE);}
  .step-num-big{position:absolute;font-family:'Fraunces',serif;font-size:120px;font-weight:900;line-height:1;right:16px;bottom:-10px;pointer-events:none;}
  .step-icon-circle{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;position:relative;z-index:1;}
  .step-icon-circle svg{width:36px;height:36px;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;}

  /* FEATURES BENTO */
  .features{background:var(--cloud);}
  .bento{display:grid;grid-template-columns:repeat(3,1fr);grid-template-rows:auto auto;gap:16px;margin-top:48px;}
  .bento-card{background:var(--white);border:1px solid var(--mist);border-radius:20px;padding:28px;transition:box-shadow .25s,transform .25s;box-shadow:0 1px 4px rgba(0,0,0,.04);}
  .bento-card:hover{box-shadow:0 8px 28px rgba(15,76,92,.1);transform:translateY(-3px);}
  .hero-card{grid-column:span 2;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:var(--white);display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;}
  .hero-card-label{font-size:11px;font-weight:600;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:8px;}
  .ai-badge{display:inline-block;background:rgba(249,214,121,.15);border:1px solid rgba(249,214,121,.3);color:var(--gold-light);font-size:11px;font-weight:600;padding:3px 12px;border-radius:100px;margin-bottom:12px;letter-spacing:.6px;}
  .bento-title{font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--ink);margin-bottom:10px;}
  .hero-card .bento-title{color:var(--white);}
  .bento-desc{font-size:14px;color:#5A6475;line-height:1.7;}
  .hero-card .bento-desc{color:rgba(255,255,255,.65);}
  .bento-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;}
  .bento-icon svg{width:22px;height:22px;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;}
  .bi-teal{background:#E1F5EE;} .bi-teal svg{stroke:#0F6E56;}
  .bi-amber{background:#FAEEDA;} .bi-amber svg{stroke:#854F0B;}
  .bi-purple{background:#EEEDFE;} .bi-purple svg{stroke:#534AB7;}
  .bi-blue{background:#E6F1FB;} .bi-blue svg{stroke:#185FA5;}
  .bi-coral{background:#FEE9E1;} .bi-coral svg{stroke:#993C1D;}

  /* ROLES */
  .roles{background:var(--white);}
  .roles-tabs{display:flex;gap:8px;margin:32px 0 32px;flex-wrap:wrap;}
  .role-tab{padding:10px 22px;border-radius:100px;border:1.5px solid var(--mist);font-size:13px;font-weight:600;color:var(--slate);cursor:pointer;transition:all .2s;}
  .role-tab.active,.role-tab:hover{background:var(--teal);border-color:var(--teal);color:var(--white);}
  .role-panel{display:none;grid-template-columns:1fr 1fr;gap:48px;align-items:start;}
  .role-panel.active{display:grid;}
  .role-steps{display:flex;flex-direction:column;gap:24px;}
  .role-step{display:flex;gap:16px;}
  .role-num{width:32px;height:32px;border-radius:50%;background:rgba(15,76,92,.08);color:var(--teal);font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .role-step-body h4{font-size:14px;font-weight:700;color:var(--ink);margin-bottom:4px;}
  .role-step-body p{font-size:13px;color:#5A6475;line-height:1.6;}
  .role-visual{background:var(--cloud);border:1px solid var(--mist);border-radius:20px;padding:28px;}
  .role-visual-title{font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:var(--ink);margin-bottom:16px;}
  .fp{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:500;color:var(--teal);padding:6px 0;border-bottom:1px solid var(--mist);}
  .fp:last-child{border-bottom:none;}
  .fp svg{width:14px;height:14px;stroke:var(--teal);fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0;}

  /* CTA */
  .cta-banner{background:var(--grad-primary);padding:88px 40px;text-align:center;position:relative;overflow:hidden;}
  .cta-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 100% at 50% 0%,rgba(249,214,121,.13) 0%,transparent 70%);pointer-events:none;}
  .cta-banner h2{font-family:'Fraunces',serif;font-size:40px;font-weight:700;color:var(--white);margin-bottom:16px;position:relative;}
  .cta-banner p{font-size:17px;color:rgba(255,255,255,.72);margin-bottom:36px;max-width:520px;margin-left:auto;margin-right:auto;position:relative;}
  .btn-primary{background:var(--grad-amber);color:var(--teal);font-size:15px;font-weight:600;padding:14px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:opacity .2s,transform .15s;margin:0 8px;position:relative;}
  .btn-primary:hover{transform:translateY(-2px) scale(1.03);box-shadow:0 10px 25px rgba(232,168,56,.35);}
  .btn-secondary{border:2px solid rgba(255,255,255,.3);color:var(--white);font-size:15px;font-weight:600;padding:12px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:border-color .2s,background .2s;margin:0 8px;position:relative;}
  .btn-secondary:hover{transform:translateY(-2px);}

  .reveal{opacity:0;transform:translateY(50px) scale(.98);filter:blur(8px);transition:opacity .9s cubic-bezier(.16,1,.3,1),transform .9s cubic-bezier(.16,1,.3,1),filter .9s ease;}
  .reveal.visible{opacity:1;transform:translateY(0) scale(1);filter:blur(0);}

  @media(max-width:900px){
    .step-alt,.role-panel.active{grid-template-columns:1fr;}
    .step-alt.flip .step-alt-text,.step-alt.flip .step-alt-visual{order:unset;}
    .bento{grid-template-columns:1fr;}
    .hero-card{grid-column:span 1;grid-template-columns:1fr;}
    section{padding:60px 20px;}
    .steps-alt{padding:0 20px;}
  }
</style>
@endpush

@section('content')

<section class="hero">
  <div class="container">
    <div class="hero-eyebrow">How It Works</div>
    <h1 class="hero-title">Your Scholarship Journey,<br><em>Step by Step</em></h1>
    <p class="hero-sub">From profile setup to receiving your scholarship — ScholarLink makes the entire process simple, transparent, and guided for every type of user.</p>
  </div>
</section>
<div class="hero-role-pills">
  <div class="role-pill active" onclick="switchRole('student');document.querySelector('.roles').scrollIntoView({behavior:'smooth'})">
    <span class="role-pill-dot"></span>Student Applicant
  </div>
  <div class="role-pill" onclick="switchRole('org');document.querySelector('.roles').scrollIntoView({behavior:'smooth'})">
    <span class="role-pill-dot" style="background:#2A8FA0"></span>Organization
  </div>
  <div class="role-pill" onclick="switchRole('eval');document.querySelector('.roles').scrollIntoView({behavior:'smooth'})">
    <span class="role-pill-dot" style="background:#8B5CF6"></span>Evaluator
  </div>
</div>

{{-- STEPS --}}
<section class="steps-section">
  <div class="steps-alt">
    @foreach($steps as $i => $step)
    <div class="step-alt {{ $i % 2 !== 0 ? 'flip' : '' }} reveal">
      <div class="step-alt-text">
        <div class="step-badge">Step {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</div>
        <h3 class="step-h3">{{ $step['title'] }}</h3>
        <p class="step-p">{{ $step['desc'] }}</p>
        <div class="step-features">
          @foreach($step['features'] as $feat)
            <div class="step-feat">{{ $feat }}</div>
          @endforeach
        </div>
      </div>
      <div class="step-alt-visual {{ $step['bg'] }}">
        <span class="step-num-big" style="{{ $step['numStyle'] }}">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
        <div class="step-icon-circle" style="{{ $step['circleStyle'] }}">
          {!! $step['icon'] !!}
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>

{{-- BENTO FEATURES --}}
<section class="features">
  <div class="container">
    <p class="section-label reveal">Platform Features</p>
    <h2 class="section-title reveal" style="transition-delay:.07s">Everything Built In</h2>
    <div class="bento">
      <div class="bento-card hero-card reveal" style="transition-delay:.05s">
        <div>
          <div class="hero-card-label">Core Intelligence</div>
          <div class="ai-badge">✦ Gemini AI</div>
          <div class="bento-title">AI-Powered Matching Engine</div>
          <p class="bento-desc">Analyzes your full profile — GWA, course, region, financial need, extracurriculars — and ranks scholarship opportunities by compatibility score. Prevents ineligible applications entirely, saving your time and avoiding rejection.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px">
          <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:20px">
            <div style="font-size:12px;color:rgba(255,255,255,.4);margin-bottom:8px;letter-spacing:.5px">MATCH SCORE</div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
              <div style="font-family:'Fraunces',serif;font-size:36px;font-weight:700;color:#F9D679">92%</div>
              <div style="font-size:13px;color:rgba(255,255,255,.5)">Gabay Dunong<br>Scholarship</div>
            </div>
            <div style="background:rgba(255,255,255,.08);border-radius:4px;height:6px;overflow:hidden">
              <div style="width:92%;height:100%;background:linear-gradient(90deg,#F9D679,#E8A838);border-radius:4px"></div>
            </div>
          </div>
          <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:16px;display:flex;align-items:center;gap:12px">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(195,251,215,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C3FBD7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style="font-size:13px;color:rgba(255,255,255,.65)">Eligibility validated before submission</div>
          </div>
        </div>
      </div>
      @foreach($bentoFeatures as $i => $f)
      <div class="bento-card reveal" style="transition-delay:{{ ($i+2)*0.04 }}s">
        <div class="bento-icon {{ $f['iconClass'] }}">{!! $f['icon'] !!}</div>
        <div class="bento-title">{{ $f['title'] }}</div>
        <p class="bento-desc">{{ $f['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ROLE TABS --}}
<section class="roles">
  <div class="container">
    <p class="section-label reveal">For Every User</p>
    <h2 class="section-title reveal" style="transition-delay:.07s">Tailored for Your Role</h2>
    <div class="roles-tabs reveal" style="transition-delay:.13s">
      <div class="role-tab active" onclick="switchRole('student')">Student / Applicant</div>
      <div class="role-tab" onclick="switchRole('org')">Organization / Admin</div>
      <div class="role-tab" onclick="switchRole('eval')">Evaluator</div>
    </div>
    @foreach($rolePanels as $key => $panel)
    <div class="role-panel {{ $key === 'student' ? 'active' : '' }}" id="panel-{{ $key }}">
      <div class="role-steps">
        @foreach($panel['steps'] as $step)
        <div class="role-step">
          <div class="role-num">{{ $loop->iteration }}</div>
          <div class="role-step-body"><h4>{{ $step['title'] }}</h4><p>{{ $step['desc'] }}</p></div>
        </div>
        @endforeach
      </div>
      <div class="role-visual">
        <div class="role-visual-title">{{ $panel['label'] }}</div>
        <div>
          @foreach($panel['features'] as $feat)
          <span class="fp"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>{{ $feat }}</span>
          @endforeach
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>

<section class="cta-banner">
  <h2>Ready to find your scholarship?</h2>
  <p>Create your free profile in minutes and let ScholarLink match you with the right opportunity.</p>
  <a href="#" class="btn-primary">Get Started Free</a>
  <a href="{{ route('organizations') }}" class="btn-secondary">For Organizations</a>
</section>

@endsection

@push('scripts')
<script>
  function switchRole(r) {
    document.querySelectorAll('.role-tab').forEach((t,i) => t.classList.toggle('active', ['student','org','eval'][i] === r));
    document.querySelectorAll('.role-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-'+r).classList.add('active');
    document.querySelectorAll('.role-pill').forEach((p,i) => p.classList.toggle('active', ['student','org','eval'][i] === r));
  }
</script>
@endpush

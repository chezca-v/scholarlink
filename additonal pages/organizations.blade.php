@extends('layouts.app')

@section('title', 'For Organizations')
@section('meta_description', 'Partner with ScholarLink to reach thousands of qualified Filipino students — efficiently, transparently, and without the paperwork burden.')

@push('styles')
<style>
  :root{--teal:#0F4C5C;--teal-light:#1A6B7A;--teal-lighter:#2A8FA0;--amber:#E8A838;--gold-light:#F9D679;--white:#FFFFFF;--cloud:#F4F6FA;--mist:#E2E8F0;--slate:#8A95A3;--ink:#1C1C2E;--grad-primary:linear-gradient(135deg,#0F4C5C,#1A6B7A);--grad-amber:linear-gradient(135deg,#E8A838,#F9D679);--grad-hero:linear-gradient(160deg,#0F4C5C,#2A8FA0);}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
  html{scroll-behavior:smooth;}
  body{font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);line-height:1.6;}

  /* HERO */
  .hero{background:var(--grad-hero);padding:148px 40px 110px;text-align:center;position:relative;overflow:hidden;}
  .hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 20% 50%,rgba(201,168,76,.12) 0%,transparent 70%),radial-gradient(ellipse 50% 70% at 80% 40%,rgba(249,214,121,.08) 0%,transparent 60%);pointer-events:none;animation:floatBg 12s ease-in-out infinite alternate;}
  @keyframes floatBg{from{transform:translateY(0) scale(1);}to{transform:translateY(-20px) scale(1.05);}}
  .hero>*{position:relative;}
  .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.35);color:var(--gold-light);font-size:12px;font-weight:600;letter-spacing:1.3px;text-transform:uppercase;padding:6px 18px;border-radius:100px;margin-bottom:28px;animation:fadeUp .6s ease both .05s;}
  .hero-title{font-family:'DM Sans',sans-serif;font-size:clamp(40px,6vw,62px);font-weight:700;color:var(--white);line-height:1.1;max-width:740px;margin:0 auto 24px;animation:fadeUp .6s ease both .18s;}
  .hero-title em{font-family:'Fraunces',serif;font-style:italic;color:var(--gold-light);}
  .hero-sub{font-size:18px;color:rgba(255,255,255,.72);max-width:560px;margin:0 auto;font-weight:400;animation:fadeUp .6s ease both .30s;}
  @keyframes fadeUp{from{opacity:0;transform:translateY(40px) scale(.98);filter:blur(6px);}to{opacity:1;transform:translateY(0) scale(1);filter:blur(0);}}

  /* SHARED */
  section{padding:88px 40px;}
  .container{max-width:1100px;margin:0 auto;}
  .section-label{display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;letter-spacing:1.6px;text-transform:uppercase;color:var(--amber);margin-bottom:12px;}
  .section-label::before{content:'';display:inline-block;width:20px;height:2px;background:linear-gradient(90deg,#0F4C5C,#E8A838);border-radius:2px;flex-shrink:0;}
  .section-title{font-family:'Fraunces',serif;font-size:32px;font-weight:700;color:var(--ink);margin-bottom:20px;line-height:1.25;}
  .section-body{font-size:16px;color:#4A5568;line-height:1.75;max-width:680px;}

  /* WHY JOIN */
  .why-join{background:var(--white);}
  .why-grid{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:start;}
  .steps-section-title{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--ink);margin-bottom:20px;}
  .step-stack{display:flex;flex-direction:column;gap:20px;}
  .step-item{display:flex;gap:16px;align-items:flex-start;}
  .step-num{width:32px;height:32px;border-radius:50%;background:var(--teal);color:var(--white);font-family:'Fraunces',serif;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .step-title{font-size:14px;font-weight:700;color:var(--ink);margin-bottom:4px;}
  .step-desc{font-size:13px;color:#6B7280;line-height:1.6;}

  /* BENEFITS */
  .benefits{background:var(--cloud);}
  .benefits-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:48px;}
  .benefit-card{background:var(--white);border:1px solid var(--mist);border-radius:20px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.04);transition:box-shadow .25s,transform .25s;}
  .benefit-card:hover{box-shadow:0 8px 28px rgba(15,76,92,.1);transform:translateY(-3px);}
  .benefit-icon-wrap{width:48px;height:48px;border-radius:14px;background:rgba(15,76,92,.07);display:flex;align-items:center;justify-content:center;margin-bottom:16px;}
  .benefit-icon-wrap svg{width:22px;height:22px;stroke:var(--teal);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;}
  .benefit-title{font-family:'Fraunces',serif;font-size:18px;font-weight:700;color:var(--ink);margin-bottom:8px;}
  .benefit-desc{font-size:14px;color:#5A6475;line-height:1.7;}

  /* ORG TYPES */
  .org-types{background:var(--white);}
  .org-types-inner{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start;}
  .org-types-text p{font-size:16px;color:#4A5568;line-height:1.8;margin-bottom:18px;}
  .org-cat-grid{display:flex;flex-direction:column;gap:12px;}
  .org-cat-card{background:var(--cloud);border:1px solid var(--mist);border-radius:16px;padding:18px 20px;display:flex;align-items:center;gap:16px;transition:background .2s,transform .2s;}
  .org-cat-card:hover{background:#E8F5F8;transform:translateX(4px);}
  .org-cat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .org-cat-icon svg{width:20px;height:20px;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;}
  .bg-blue{background:#E6F1FB;} .bg-blue svg{stroke:#185FA5;}
  .bg-teal{background:#E1F5EE;} .bg-teal svg{stroke:#0F6E56;}
  .bg-amber{background:#FAEEDA;} .bg-amber svg{stroke:#854F0B;}
  .bg-purple{background:#EEEDFE;} .bg-purple svg{stroke:#534AB7;}
  .bg-coral{background:#FEE9E1;} .bg-coral svg{stroke:#993C1D;}
  .org-cat-label{font-size:14px;font-weight:700;color:var(--ink);margin-bottom:2px;}
  .org-cat-sub{font-size:12px;color:#6B7280;}

  /* ORG FEATURES */
  .org-features{background:var(--cloud);}
  .feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:48px;}
  .feature-card{background:var(--white);border:1px solid var(--mist);border-radius:20px;padding:28px;transition:box-shadow .25s,transform .25s;}
  .feature-card:hover{box-shadow:0 8px 28px rgba(15,76,92,.1);transform:translateY(-3px);}
  .feature-icon-wrap{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;}
  .feature-icon-wrap svg{width:20px;height:20px;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;}
  .fi-teal{background:rgba(15,76,92,.08);} .fi-teal svg{stroke:var(--teal);}
  .fi-amber{background:#FAEEDA;} .fi-amber svg{stroke:#854F0B;}
  .feature-name{font-family:'Fraunces',serif;font-size:17px;font-weight:700;color:var(--ink);margin-bottom:8px;}
  .feature-desc{font-size:14px;color:#5A6475;line-height:1.7;}

  /* CTA */
  .cta-banner{background:var(--grad-primary);padding:88px 40px;text-align:center;position:relative;overflow:hidden;}
  .cta-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 100% at 50% 0%,rgba(249,214,121,.13) 0%,transparent 70%);pointer-events:none;}
  .cta-banner h2{font-family:'Fraunces',serif;font-size:40px;font-weight:700;color:var(--white);margin-bottom:16px;position:relative;}
  .cta-banner p{font-size:17px;color:rgba(255,255,255,.72);margin-bottom:36px;max-width:520px;margin-left:auto;margin-right:auto;position:relative;}
  .btn-primary{background:var(--grad-amber);color:var(--teal);font-size:15px;font-weight:600;padding:14px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:opacity .2s,transform .15s;margin:0 8px;position:relative;}
  .btn-primary:hover{transform:translateY(-2px) scale(1.03);box-shadow:0 10px 25px rgba(232,168,56,.35);}
  .btn-secondary{border:2px solid rgba(255,255,255,.3);color:var(--white);font-size:15px;font-weight:600;padding:12px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:border-color .2s,background .2s;margin:0 8px;position:relative;}

  .reveal{opacity:0;transform:translateY(50px) scale(.98);filter:blur(8px);transition:opacity .9s cubic-bezier(.16,1,.3,1),transform .9s cubic-bezier(.16,1,.3,1),filter .9s ease;}
  .reveal.visible{opacity:1;transform:translateY(0) scale(1);filter:blur(0);}

  @media(max-width:900px){
    .why-grid,.org-types-inner{grid-template-columns:1fr;gap:40px;}
    .benefits-row,.feature-grid{grid-template-columns:1fr;}
    section{padding:60px 20px;}
  }
</style>
@endpush

@section('content')

<section class="hero">
  <div class="hero-eyebrow">For Organizations</div>
  <h1 class="hero-title">Post Scholarships. <em>Change Lives.</em></h1>
  <p class="hero-sub">Partner with ScholarLink to reach thousands of qualified Filipino students — efficiently, transparently, and without the paperwork burden.</p>
</section>

{{-- WHY JOIN --}}
<section class="why-join">
  <div class="container">
    <div class="why-grid">
      <div class="reveal">
        <p class="section-label">Why Partner With Us</p>
        <h2 class="section-title">A Smarter Way to Administer Scholarships</h2>
        <p class="section-body">Managing scholarship applications manually is time-consuming and prone to error. ScholarLink gives your organization a centralized, digital-first platform to post opportunities, define eligibility criteria, and oversee the entire screening lifecycle — all in one place.</p>
        <br>
        <p class="section-body">From government agencies and private foundations to universities and corporations, ScholarLink supports every type of scholarship provider in reaching the right applicants faster and with greater fairness.</p>
      </div>
      <div class="reveal" style="transition-delay:.13s">
        <p class="section-label" style="margin-bottom:8px">Getting started</p>
        <p class="steps-section-title">How it works for you</p>
        <div class="step-stack">
          @foreach($orgSteps as $step)
          <div class="step-item">
            <div class="step-num">{{ $loop->iteration }}</div>
            <div>
              <div class="step-title">{{ $step['title'] }}</div>
              <p class="step-desc">{{ $step['desc'] }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- BENEFITS --}}
<section class="benefits">
  <div class="container">
    <p class="section-label reveal">What You Get</p>
    <h2 class="section-title reveal" style="transition-delay:.07s">Platform Benefits</h2>
    <div class="benefits-row reveal" style="transition-delay:.13s">
      @foreach($benefits as $b)
      <div class="benefit-card">
        <div class="benefit-icon-wrap">{!! $b['icon'] !!}</div>
        <div class="benefit-title">{{ $b['title'] }}</div>
        <p class="benefit-desc">{{ $b['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ORG TYPES --}}
<section class="org-types">
  <div class="container">
    <div class="org-types-inner">
      <div class="org-types-text reveal">
        <p class="section-label">Who Can Join</p>
        <h2 class="section-title">Open to All Scholarship Providers</h2>
        <p>ScholarLink is designed to accommodate every type of organization that offers educational funding to Filipino students. Whether you're a government body, a private corporation, or an academic institution, our platform scales to your needs.</p>
        <p>Each organization account is verified and managed by the ScholarLink superadmin, ensuring platform integrity. Once onboarded, your administrators have full control over scholarship postings, evaluator assignments, and applicant communications.</p>
        <p>There are no platform fees for scholarship providers — our mission is to maximize access for students, not to create barriers for the institutions that support them.</p>
      </div>
      <div class="reveal" style="transition-delay:.13s">
        <div class="org-cat-grid">
          @foreach($orgTypes as $type)
          <div class="org-cat-card">
            <div class="org-cat-icon {{ $type['bg'] }}">{!! $type['icon'] !!}</div>
            <div>
              <div class="org-cat-label">{{ $type['label'] }}</div>
              <div class="org-cat-sub">{{ $type['sub'] }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ADMIN FEATURES --}}
<section class="org-features">
  <div class="container">
    <p class="section-label reveal">Admin Capabilities</p>
    <h2 class="section-title reveal" style="transition-delay:.07s">Everything You Need to Run Your Program</h2>
    <div class="feature-grid">
      @foreach($adminFeatures as $i => $f)
      <div class="feature-card reveal" style="transition-delay:{{ $i*0.06+0.07 }}s">
        <div class="feature-icon-wrap {{ $f['iconClass'] }}">{!! $f['icon'] !!}</div>
        <div class="feature-name">{{ $f['name'] }}</div>
        <p class="feature-desc">{{ $f['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="cta-banner reveal">
  <h2>Ready to reach the right scholars?</h2>
  <p>Join ScholarLink and let your organization connect with qualified Filipino students — efficiently and fairly.</p>
  <a href="#" class="btn-primary">Register Your Organization</a>
  <a href="{{ route('contact') }}" class="btn-secondary">Contact Us</a>
</section>

@endsection

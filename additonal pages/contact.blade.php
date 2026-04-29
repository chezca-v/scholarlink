@extends('layouts.app')

@section('title', 'Contact Us')
@section('meta_description', 'Have a question, a partnership inquiry, or need support? Reach out to the ScholarLink team.')

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

  /* CONTACT GRID */
  .contact-main{background:var(--white);}
  .contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:start;}

  /* FORM */
  .form-wrap{background:rgba(255,255,255,.9);border:1px solid var(--mist);border-radius:24px;padding:40px;box-shadow:0 10px 40px rgba(15,76,92,.08);}
  .form-title{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--ink);margin-bottom:6px;}
  .form-subtitle{font-size:14px;color:var(--slate);margin-bottom:32px;}
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:18px;}
  .form-group label{font-size:12px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:var(--teal);}
  .form-group input,.form-group select,.form-group textarea{font-family:'DM Sans',sans-serif;font-size:14px;color:var(--ink);background:var(--cloud);border:1.5px solid var(--mist);border-radius:10px;padding:12px 16px;outline:none;transition:border-color .2s,box-shadow .2s,background .2s;width:100%;resize:none;}
  .form-group input::placeholder,.form-group textarea::placeholder{color:#A0ADB8;}
  .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--teal-lighter);background:var(--white);box-shadow:0 0 0 3px rgba(42,143,160,.12);}
  .form-group textarea{height:130px;line-height:1.6;}
  .form-group select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A95A3' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:38px;}
  .form-submit{width:100%;background:var(--grad-primary);color:var(--white);font-family:'DM Sans',sans-serif;font-size:15px;font-weight:600;padding:14px 24px;border:none;border-radius:10px;cursor:pointer;transition:opacity .2s,transform .15s,box-shadow .2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:8px;}
  .form-submit:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(15,76,92,.28);}
  .form-submit svg{width:18px;height:18px;stroke:var(--white);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}

  /* Error styles */
  .form-group.has-error input,.form-group.has-error select,.form-group.has-error textarea{border-color:#DC2626;box-shadow:0 0 0 3px rgba(220,38,38,.1);}
  .form-error{font-size:12px;color:#DC2626;margin-top:2px;}
  .form-success-banner{display:none;flex-direction:column;align-items:center;justify-content:center;gap:16px;text-align:center;padding:48px 24px;}
  .form-success-banner.visible{display:flex;}
  .form-success-icon{width:64px;height:64px;border-radius:50%;background:var(--grad-primary);display:flex;align-items:center;justify-content:center;}
  .form-success-icon svg{width:30px;height:30px;stroke:white;fill:none;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;}
  .form-success-banner h3{font-family:'Fraunces',serif;font-size:22px;font-weight:700;color:var(--ink);}
  .form-success-banner p{font-size:14px;color:var(--slate);max-width:280px;line-height:1.6;}

  /* INFO CARDS */
  .info-stack{display:flex;flex-direction:column;gap:16px;}
  .info-card{background:var(--white);border:1px solid var(--mist);border-radius:20px;padding:24px 26px;display:flex;align-items:flex-start;gap:18px;box-shadow:0 4px 16px rgba(15,76,92,.05);transition:box-shadow .25s,transform .25s;}
  .info-card:hover{box-shadow:0 10px 30px rgba(15,76,92,.12);transform:translateX(4px);}
  .info-icon{width:46px;height:46px;border-radius:13px;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
  .info-icon svg{width:22px;height:22px;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;}
  .ic-teal{background:#E1F5EE;} .ic-teal svg{stroke:#0F6E56;}
  .ic-blue{background:#E6F1FB;} .ic-blue svg{stroke:#185FA5;}
  .ic-amber{background:#FAEEDA;} .ic-amber svg{stroke:#854F0B;}
  .ic-purple{background:#EEEDFE;} .ic-purple svg{stroke:#534AB7;}
  .info-label{font-size:11px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;color:var(--slate);margin-bottom:4px;}
  .info-value{font-size:15px;font-weight:600;color:var(--ink);margin-bottom:2px;}
  .info-sub{font-size:13px;color:#6B7280;line-height:1.5;}

  /* USER TYPES */
  .user-types{background:var(--white);}
  .user-types-inner{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;}
  .user-types-text p{font-size:16px;color:#4A5568;line-height:1.8;margin-bottom:18px;}
  .concern-grid{display:flex;flex-direction:column;gap:12px;}
  .concern-item{background:#F0FAFA;border:1px solid var(--mist);border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:14px;transition:background .2s,transform .2s;cursor:default;}
  .concern-item:hover{background:#E8F5F8;transform:translateX(4px);}
  .concern-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
  .dot-blue{background:#185FA5;} .dot-teal{background:#0F6E56;} .dot-amber{background:#E8A838;} .dot-purple{background:#534AB7;} .dot-coral{background:#993C1D;}
  .concern-text{font-size:14px;font-weight:600;color:var(--ink);}
  .concern-sub{font-size:12px;color:#6B7280;margin-top:2px;}

  /* FAQ */
  .faq{background:#F0FAFA;}
  .faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:48px;}
  .faq-item{background:var(--white);border:1px solid var(--mist);border-radius:16px;padding:0;box-shadow:0 1px 3px rgba(0,0,0,.04);overflow:hidden;transition:box-shadow .25s;}
  .faq-item:hover{box-shadow:0 6px 22px rgba(15,76,92,.1);}
  .faq-question{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;cursor:pointer;font-size:15px;font-weight:600;color:var(--ink);gap:16px;user-select:none;}
  .faq-chevron{width:28px;height:28px;border-radius:50%;flex-shrink:0;background:var(--cloud);border:1px solid var(--mist);display:flex;align-items:center;justify-content:center;transition:transform .3s ease,background .2s;}
  .faq-chevron svg{width:14px;height:14px;stroke:var(--teal);fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round;}
  .faq-item.open .faq-chevron{transform:rotate(180deg);background:rgba(15,76,92,.08);}
  .faq-answer{max-height:0;overflow:hidden;transition:max-height .35s cubic-bezier(.22,1,.36,1),padding .3s ease;font-size:14px;color:#5A6475;line-height:1.75;}
  .faq-answer-inner{padding:0 24px 20px;border-top:1px solid var(--mist);padding-top:16px;}
  .faq-item.open .faq-answer{max-height:300px;}

  /* CTA */
  .cta-banner{background:var(--grad-primary);padding:88px 40px;text-align:center;position:relative;overflow:hidden;}
  .cta-banner::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 100% at 50% 0%,rgba(249,214,121,.13) 0%,transparent 70%);pointer-events:none;}
  .cta-banner h2{font-family:'Fraunces',serif;font-size:40px;font-weight:700;color:var(--white);margin-bottom:16px;position:relative;}
  .cta-banner p{font-size:17px;color:rgba(255,255,255,.72);margin-bottom:36px;max-width:520px;margin-left:auto;margin-right:auto;position:relative;}
  .btn-primary{background:var(--grad-amber);color:var(--teal);font-size:15px;font-weight:600;padding:14px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:opacity .2s,transform .15s;margin:0 8px;position:relative;}
  .btn-primary:hover{transform:translateY(-2px) scale(1.03);box-shadow:0 10px 25px rgba(232,168,56,.35);}
  .btn-secondary{border:2px solid rgba(255,255,255,.3);color:var(--white);font-size:15px;font-weight:600;padding:12px 32px;border-radius:10px;text-decoration:none;display:inline-block;transition:border-color .2s;margin:0 8px;position:relative;}

  .reveal{opacity:0;transform:translateY(50px) scale(.98);filter:blur(8px);transition:opacity .9s cubic-bezier(.16,1,.3,1),transform .9s cubic-bezier(.16,1,.3,1),filter .9s ease;}
  .reveal.visible{opacity:1;transform:translateY(0) scale(1);filter:blur(0);}

  @keyframes shake{0%,100%{transform:translateX(0);}20%{transform:translateX(-6px);}40%{transform:translateX(6px);}60%{transform:translateX(-4px);}80%{transform:translateX(4px);}}

  @media(max-width:900px){
    .contact-grid,.user-types-inner{grid-template-columns:1fr;gap:40px;}
    .faq-grid{grid-template-columns:1fr;}
    .form-row{grid-template-columns:1fr;}
    section{padding:60px 20px;}
  }
</style>
@endpush

@section('content')

<section class="hero">
  <div class="hero-eyebrow">Contact Us</div>
  <h1 class="hero-title">We're Here to <em>Help You</em></h1>
  <p class="hero-sub">Have a question, a partnership inquiry, or need support? Reach out to the ScholarLink team — we'll get back to you as soon as we can.</p>
</section>

{{-- SUCCESS flash message (server-side redirect) --}}
@if(session('success'))
<div style="background:#ECFDF5;border:1px solid #6EE7B7;color:#065F46;padding:16px 40px;text-align:center;font-size:14px;font-weight:600;">
  ✅ {{ session('success') }}
</div>
@endif

<section class="contact-main">
  <div class="container">
    <div class="contact-grid">

      {{-- FORM --}}
      <div class="reveal">
        <p class="section-label">Send Us a Message</p>
        <h2 class="section-title">Get in Touch</h2>
        <div class="form-wrap">

          @if(!session('success'))
          <div id="contactForm">
            <p class="form-title">How can we help?</p>
            <p class="form-subtitle">Fill out the form and our team will respond within 1–2 business days.</p>

            <form method="POST" action="{{ route('contact.send') }}" novalidate>
              @csrf

              <div class="form-row">
                <div class="form-group @error('first_name') has-error @enderror">
                  <label for="first_name">First Name</label>
                  <input type="text" id="first_name" name="first_name"
                         value="{{ old('first_name') }}" placeholder="e.g. Maria">
                  @error('first_name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group @error('last_name') has-error @enderror">
                  <label for="last_name">Last Name</label>
                  <input type="text" id="last_name" name="last_name"
                         value="{{ old('last_name') }}" placeholder="e.g. Santos">
                  @error('last_name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
              </div>

              <div class="form-group @error('email') has-error @enderror">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}" placeholder="you@example.com">
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
              </div>

              <div class="form-group @error('role') has-error @enderror">
                <label for="role">I am a…</label>
                <select id="role" name="role">
                  <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role</option>
                  <option value="student"   {{ old('role')=='student'   ? 'selected' : '' }}>Student / Applicant</option>
                  <option value="org"       {{ old('role')=='org'       ? 'selected' : '' }}>Organization / Scholarship Provider</option>
                  <option value="evaluator" {{ old('role')=='evaluator' ? 'selected' : '' }}>Evaluator</option>
                  <option value="other"     {{ old('role')=='other'     ? 'selected' : '' }}>Other</option>
                </select>
                @error('role')<span class="form-error">{{ $message }}</span>@enderror
              </div>

              <div class="form-group @error('subject') has-error @enderror">
                <label for="subject">Subject</label>
                <select id="subject" name="subject">
                  <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a topic</option>
                  <option value="application"      {{ old('subject')=='application'      ? 'selected' : '' }}>Application Support</option>
                  <option value="scholarship"      {{ old('subject')=='scholarship'      ? 'selected' : '' }}>Scholarship Inquiry</option>
                  <option value="org-partnership"  {{ old('subject')=='org-partnership'  ? 'selected' : '' }}>Organization Partnership</option>
                  <option value="account"          {{ old('subject')=='account'          ? 'selected' : '' }}>Account Issue</option>
                  <option value="technical"        {{ old('subject')=='technical'        ? 'selected' : '' }}>Technical Problem</option>
                  <option value="feedback"         {{ old('subject')=='feedback'         ? 'selected' : '' }}>Feedback / Suggestion</option>
                  <option value="other"            {{ old('subject')=='other'            ? 'selected' : '' }}>Other</option>
                </select>
                @error('subject')<span class="form-error">{{ $message }}</span>@enderror
              </div>

              <div class="form-group @error('message') has-error @enderror">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Tell us what's on your mind…">{{ old('message') }}</textarea>
                @error('message')<span class="form-error">{{ $message }}</span>@enderror
              </div>

              <button type="submit" class="form-submit">
                Send Message
                <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              </button>
            </form>
          </div>
          @else
          <div class="form-success-banner visible">
            <div class="form-success-icon">
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3>Message Sent!</h3>
            <p>Thank you for reaching out. We'll get back to you within 1–2 business days.</p>
          </div>
          @endif

        </div>
      </div>

      {{-- INFO CARDS --}}
      <div class="reveal" style="transition-delay:.13s">
        <p class="section-label" style="margin-bottom:20px">Contact Information</p>
        <div class="info-stack">
          @foreach($contactInfo as $info)
          <div class="info-card">
            <div class="info-icon {{ $info['iconClass'] }}">{!! $info['icon'] !!}</div>
            <div class="info-body">
              <div class="info-label">{{ $info['label'] }}</div>
              <div class="info-value">{{ $info['value'] }}</div>
              <div class="info-sub">{!! $info['sub'] !!}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</section>

{{-- WHO TO CONTACT --}}
<section class="user-types">
  <div class="container">
    <div class="user-types-inner">
      <div class="user-types-text reveal">
        <p class="section-label">Who Can Reach Out</p>
        <h2 class="section-title">We Support Every Type of User</h2>
        <p>Whether you're a student looking for help with your application, an organization wanting to post a scholarship, or an evaluator with a platform question — the ScholarLink team is ready to assist.</p>
        <p>For faster resolution, please select the correct topic and your role when submitting your message. Critical issues and organization partnership requests are prioritized in our queue.</p>
      </div>
      <div class="reveal" style="transition-delay:.13s">
        <div class="concern-grid">
          @foreach($userTypes as $type)
          <div class="concern-item">
            <div class="concern-dot {{ $type['dot'] }}"></div>
            <div>
              <div class="concern-text">{{ $type['title'] }}</div>
              <div class="concern-sub">{{ $type['sub'] }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section class="faq">
  <div class="container">
    <p class="section-label reveal">Common Questions</p>
    <h2 class="section-title reveal" style="transition-delay:.07s">Frequently Asked Questions</h2>
    <div class="faq-grid reveal" style="transition-delay:.13s">
      @foreach($faqs as $faq)
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          {{ $faq['q'] }}
          <div class="faq-chevron"><svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div>
        </div>
        <div class="faq-answer">
          <div class="faq-answer-inner">{{ $faq['a'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="cta-banner reveal">
  <h2>Still have questions?</h2>
  <p>Browse our scholarship listings or head to the About page to learn more about the ScholarLink team and mission.</p>
  <a href="#" class="btn-primary">Browse Scholarships</a>
  <a href="{{ route('about') }}" class="btn-secondary">About Us</a>
</section>

@endsection

@push('scripts')
<script>
  function toggleFaq(el) {
    const item = el.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!isOpen) item.classList.add('open');
  }
</script>
@endpush

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ScholarLink — One Profile. Every Scholarship.')</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;0,9..144,900;1,9..144,300;1,9..144,700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
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
        body { font-family: 'DM Sans', sans-serif; background: var(--teal-foam); color: var(--navy); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── NAV ───────────────────────────────────────── */
        nav {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--teal-mist);
            position: sticky; top: 0; z-index: 200;
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
        .logo-mark { width: 34px; height: 34px; object-fit: contain; }
        .nav-links { display: flex; gap: 36px; list-style: none; }
        .nav-links a { font-size: 14px; font-weight: 500; color: var(--teal-pale); text-decoration: none; transition: color 0.2s; }
        .nav-links a:hover { color: var(--teal-mid); }
        .nav-actions { display: flex; gap: 10px; align-items: center; }
        .btn-text { font-size: 14px; font-weight: 600; padding: 8px 18px; color: var(--teal-mid); text-decoration: none; }
        .btn-pill {
            font-size: 13px; font-weight: 700; padding: 9px 22px;
            background: var(--teal-mid); color: var(--gold-light);
            border-radius: 999px; text-decoration: none;
            box-shadow: 0 4px 16px rgba(15,76,92,0.25);
        }

        /* ── CONTENT ───────────────────────────────────── */
        main { flex: 1; }

        /* ── FOOTER ────────────────────────────────────── */
        footer { background: var(--teal-deep); padding: 72px 48px 36px; margin-top: auto; }
        .footer-inner { max-width: 1160px; margin: 0 auto; }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 52px;
            padding-bottom: 52px; border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 32px;
        }
        .footer-logo { display: flex; align-items: center; gap: 10px; font-family: 'Fraunces', serif; font-size: 22px; font-weight: 700; color: var(--gold-light); text-decoration: none; }
        .footer-logo-mark { width: 36px; height: 36px; }
        .footer-col-title { font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.2); margin-bottom: 16px; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .footer-links li a { font-size: 13px; color: rgba(255,255,255,0.4); text-decoration: none; transition: color 0.2s; }
        .footer-links li a:hover { color: var(--gold-light); }
        .footer-bottom { display: flex; justify-content: space-between; align-items: center; }
        .footer-copy { font-size: 12px; color: rgba(255,255,255,0.18); }
        .footer-badge { font-size: 10px; font-weight: 700; color: var(--gold); border: 1px solid rgba(232,168,56,0.25); border-radius: 999px; padding: 4px 14px; letter-spacing: 1.5px; }

        @media (max-width: 900px) {
            .nav-links { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav>
    <div class="nav-inner">
        <a href="{{ route('landing') }}" class="logo">
            <img src="{{ asset('logo-light.png.png') }}" alt="ScholarLink logo" class="logo-mark">
            <span>ScholarLink</span>
        </a>
        <ul class="nav-links">
            <li><a href="{{ route('scholarships.index') }}">Browse</a></li>
            <li><a href="{{ route('landing') }}#how">How It Works</a></li>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('organizations') }}">Organizations</a></li>
        </ul>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-pill"><span>Dashboard →</span></a>
            @else
                <a href="{{ route('login') }}" class="btn-text">Log In</a>
                <a href="{{ route('register') }}" class="btn-pill"><span>Get Started →</span></a>
            @endauth
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div>
                <a href="{{ route('landing') }}" class="footer-logo">
                    <img src="{{ asset('logo-light.png.png') }}" alt="ScholarLink logo" class="footer-logo-mark">
                    <span>ScholarLink</span>
                </a>
                <p style="font-size: 13px; color: rgba(255,255,255,0.3); line-height: 1.8; max-width: 280px; margin-top: 12px;">Bridging Filipino students to scholarship opportunities — one profile, every scholarship.</p>
            </div>
            <div>
                <div class="footer-col-title">Platform</div>
                <ul class="footer-links">
                    <li><a href="{{ route('scholarships.index') }}">Browse</a></li>
                    <li><a href="{{ route('landing') }}#how">How It Works</a></li>
                    <li><a href="{{ route('organizations') }}">For Organizations</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Account</div>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                    <li><a href="{{ route('login') }}">Log In</a></li>
                    <li><a href="{{ route('applications.index') }}">My Applications</a></li>
                </ul>
            </div>
            <div>
                <div class="footer-col-title">Legal</div>
                <ul class="footer-links">
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    <li><a href="{{ route('data-privacy') }}">Data Privacy Act</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">© {{ date('Y') }} ScholarLink. Philippines 🇵🇭</div>
            <div class="footer-badge">SOFTWARE DESIGN PROJECT</div>
        </div>
    </div>
</footer>

<x-chatbot-widget />
<x-visitor-counter />
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'ScholarLink') }} — Authentication</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet" />

    <style>
        /* ── TOKENS ── */
        :root {
            --teal-950:  #0f3a44;
            --teal-900:  #17535f;
            --teal-700:  #1b6878;
            --teal-600:  #1e7585;
            --teal-500:  #22889a;
            --teal-200:  #a8d8e0;
            --gold:      #c9a227;
            --gold-light:#deb84e;
            --left-bg:   #e8f3f5;
            --left-bg2:  #d4eaef;
            --white:     #ffffff;
            --text-dark: #0d2d38;
            --text-body: #2c5a65;
            --text-muted:#6b98a4;
            --border:    #cde4e9;
            --input-bg:  #ffffff;
            --rp-input:  rgba(255,255,255,0.13);
            --rp-border: rgba(255,255,255,0.4);
            --rp-label:  rgba(255,255,255,0.85);
            --radius-card: 20px;
            --radius-input: 8px;
            --radius-btn: 8px;
            --t-panel: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            --blur-amount: 6px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        a { text-decoration: none; }
        button, input, select, textarea { font-family: inherit; font-size: inherit; }

        /* ── BODY ── */
        body {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
            position: relative;
            overflow: hidden;
            background: var(--teal-950);
        }

        /* Animated background orbs */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(34,136,154,0.25) 0%, transparent 70%);
            top: -200px; left: -200px;
            animation: floatOrb1 12s ease-in-out infinite alternate;
        }
        body::after {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(201,162,39,0.12) 0%, transparent 70%);
            bottom: -150px; right: -100px;
            animation: floatOrb2 15s ease-in-out infinite alternate;
        }

        @keyframes floatOrb1 { from { transform: translate(0,0) scale(1); } to { transform: translate(80px,60px) scale(1.15); } }
        @keyframes floatOrb2 { from { transform: translate(0,0) scale(1); } to { transform: translate(-60px,-80px) scale(1.1); } }

        /* ── AUTH CARD ── */
        .auth-card {
            display: flex;
            width: 100%;
            max-width: 960px;
            min-height: 610px;
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow:
                0 24px 64px rgba(0,0,0,0.4),
                0 4px 16px rgba(0,0,0,0.25),
                inset 0 1px 0 rgba(255,255,255,0.08);
            position: relative;
            z-index: 1;

            /* Card entrance animation */
            animation: cardReveal 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(32px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        /* ══════════════════════════════════════
           LEFT PANEL — LOGIN
           ══════════════════════════════════════ */
        .panel-login {
            flex: 0 0 50%;
            background: linear-gradient(150deg, var(--left-bg) 0%, var(--left-bg2) 100%);
            padding: 56px 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circle top-right of left panel */
        .panel-login::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(27,104,120,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-eyebrow {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }
        .login-eyebrow-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--teal-700);
        }
        .login-eyebrow-text {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--teal-700);
        }

        .login-heading {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 2.2rem;
            color: var(--text-dark);
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .login-subheading {
            font-size: 0.875rem;
            color: var(--text-body);
            margin-bottom: 32px;
        }

        /* Alerts */
        .alert {
            padding: 10px 14px;
            border-radius: 7px;
            font-size: 0.82rem;
            margin-bottom: 18px;
        }
        .alert-success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); color: #166534; }
        .alert-danger  { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.3);  color: #991b1b; }

        /* Field group */
        .field-group { margin-bottom: 16px; }

        .field-label {
            display: block;
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 7px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            width: 18px; height: 18px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-input);
            background: var(--input-bg);
            color: var(--text-dark);
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-input:focus {
            border-color: var(--teal-700);
            box-shadow: 0 0 0 3px rgba(27,104,120,0.14);
        }
        .form-input:hover:not(:focus) { border-color: var(--teal-200); }
        .form-input::placeholder { color: #b8d4da; }

        /* Remember + Forgot row */
        .login-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .remember-wrap input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--teal-700);
            cursor: pointer;
        }
        .remember-wrap span { font-size: 0.84rem; color: var(--text-body); }

        .forgot-link {
            font-size: 0.82rem;
            color: var(--teal-700);
            font-weight: 500;
            transition: color 0.18s;
            position: relative;
        }
        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 1px;
            background: var(--teal-700);
            transform: scaleX(0);
            transition: transform 0.2s;
        }
        .forgot-link:hover::after { transform: scaleX(1); }
        .forgot-link:hover { color: var(--teal-500); }

        /* Login button */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--teal-900);
            color: var(--gold);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            transition: background 0.22s, transform 0.14s, box-shadow 0.22s;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, transparent 60%);
            pointer-events: none;
        }
        .btn-login:hover  {
            background: var(--teal-700);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(23,83,95,0.4);
        }
        .btn-login:active { transform: translateY(0); }

        /* OR divider */
        .or-row {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        .or-row::before, .or-row::after {
            content: ''; flex: 1; height: 1px;
            background: var(--border);
        }

        /* Social buttons */
        .social-row {
            display: flex; gap: 12px; justify-content: center;
        }
        .btn-social {
            width: 46px; height: 46px;
            border: 1.5px solid var(--border);
            border-radius: 50%;
            background: var(--input-bg);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: border-color 0.18s, box-shadow 0.18s, transform 0.15s;
            color: var(--text-body);
        }
        .btn-social:hover {
            border-color: var(--teal-700);
            box-shadow: 0 3px 12px rgba(27,104,120,0.18);
            transform: translateY(-2px);
        }
        .btn-social svg { width: 19px; height: 19px; }

        /* Switch prompt */
        .signup-prompt {
            text-align: center;
            margin-top: 24px;
            font-size: 0.875rem;
            color: var(--text-body);
        }
        .switch-link-lp {
            color: var(--teal-900);
            font-weight: 700;
            border-bottom: 1.5px solid var(--teal-900);
            padding-bottom: 1px;
            cursor: pointer;
            transition: color 0.18s, border-color 0.18s;
        }
        .switch-link-lp:hover { color: var(--teal-600); border-color: var(--teal-600); }

        .field-error { display: block; margin-top: 5px; font-size: 0.77rem; color: #dc2626; }


        /* ══════════════════════════════════════
           RIGHT PANEL — SIGN UP
           ══════════════════════════════════════ */
        .panel-signup {
            flex: 0 0 50%;
            background: linear-gradient(165deg, var(--teal-600) 0%, var(--teal-700) 50%, var(--teal-950) 100%);
            padding: 44px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative ring */
        .panel-signup::before {
            content: '';
            position: absolute;
            bottom: -120px; left: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            border: 40px solid rgba(255,255,255,0.04);
            pointer-events: none;
        }
        .panel-signup::after {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,162,39,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .signup-heading {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 700;
            font-size: 1.95rem;
            color: var(--gold);
            text-align: center;
            margin-bottom: 24px;
            line-height: 1.15;
            position: relative;
        }

        /* Two-column name row */
        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 10px;
        }

        .field-group-rp { margin-bottom: 10px; }

        .field-label-rp {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--rp-label);
            margin-bottom: 5px;
            letter-spacing: 0.01em;
        }

        .req { color: var(--gold-light); margin-left: 2px; }

        .form-input-rp {
            width: 100%;
            padding: 10px 13px;
            border: 1.5px solid var(--rp-border);
            border-radius: var(--radius-input);
            background: var(--rp-input);
            color: #fff;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            backdrop-filter: blur(4px);
        }
        .form-input-rp:focus {
            border-color: var(--gold-light);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.2);
            background: rgba(255,255,255,0.18);
        }
        .form-input-rp:hover:not(:focus) { border-color: rgba(255,255,255,0.6); }
        .form-input-rp::placeholder { color: rgba(255,255,255,0.3); }

        /* Role select */
        .form-select-rp {
            width: 100%;
            padding: 10px 32px 10px 13px;
            border: 1.5px solid var(--rp-border);
            border-radius: var(--radius-input);
            background: var(--teal-950);
            color: #fff;
            font-size: 0.875rem;
            outline: none;
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='rgba(255,255,255,0.55)' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            transition: border-color 0.2s;
        }
        .form-select-rp:focus {
            border-color: var(--gold-light);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.2);
        }
        .form-select-rp option { background: var(--teal-900); }

        /* Terms row */
        .terms-row {
            display: flex; align-items: flex-start; gap: 9px;
            margin-bottom: 14px;
            font-size: 0.79rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.5;
        }
        .terms-row input[type="checkbox"] {
            width: 14px; height: 14px;
            flex-shrink: 0; margin-top: 2px;
            accent-color: var(--gold);
            cursor: pointer;
        }
        .terms-row a { color: rgba(255,255,255,0.9); text-decoration: underline; text-underline-offset: 2px; }
        .terms-row a:hover { color: var(--gold-light); }

        /* Sign Up button */
        .btn-signup {
            width: 100%;
            padding: 13px;
            background: var(--teal-950);
            color: var(--gold);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            transition: background 0.22s, transform 0.14s, box-shadow 0.22s;
            position: relative;
            overflow: hidden;
        }
        .btn-signup::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, transparent 60%);
            pointer-events: none;
        }
        .btn-signup:hover {
            background: #0a1e26;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.35);
        }
        .btn-signup:active { transform: translateY(0); }

        /* OR divider right */
        .or-row-rp {
            display: flex; align-items: center; gap: 12px;
            margin: 16px 0;
            color: rgba(255,255,255,0.4);
            font-size: 0.8rem;
        }
        .or-row-rp::before, .or-row-rp::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(255,255,255,0.2);
        }

        /* OAuth buttons */
        .btn-oauth {
            display: block; width: 100%;
            padding: 10px 14px;
            background: rgba(255,255,255,0.95);
            color: var(--text-dark);
            font-size: 0.855rem;
            font-weight: 500;
            text-align: center;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            margin-bottom: 8px;
            transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
        }
        .btn-oauth:last-of-type { margin-bottom: 0; }
        .btn-oauth:hover {
            background: #eef7f9;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,0,0,0.14);
        }

        .login-prompt {
            text-align: center;
            margin-top: 18px;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.65);
        }
        .switch-link-rp {
            color: var(--gold-light);
            font-weight: 700;
            border-bottom: 1.5px solid var(--gold-light);
            padding-bottom: 1px;
            cursor: pointer;
            transition: color 0.18s;
        }
        .switch-link-rp:hover { color: var(--gold); }

        .field-error-rp { display: block; margin-top: 4px; font-size: 0.77rem; color: #fca5a5; }


        /* ══════════════════════════════════════
           PANEL BLUR TRANSITIONS
           ══════════════════════════════════════ */
        .panel-login,
        .panel-signup {
            transition:
                filter   var(--t-panel),
                opacity  var(--t-panel),
                transform var(--t-panel);
            will-change: filter, opacity;
        }

        /* Login active → blur right */
        .auth-card.active-login .panel-signup {
            filter:  blur(var(--blur-amount));
            opacity: 0.5;
            pointer-events: none;
            user-select: none;
            transform: scale(0.99);
        }

        /* Signup active → blur left */
        .auth-card.active-signup .panel-login {
            filter:  blur(var(--blur-amount));
            opacity: 0.5;
            pointer-events: none;
            user-select: none;
            transform: scale(0.99);
        }

        /* Active panels always crisp */
        .auth-card.active-login  .panel-login,
        .auth-card.active-signup .panel-signup {
            filter:  blur(0px);
            opacity: 1;
            transform: scale(1);
        }

        /* Highlight strip on active panel edge */
        .panel-login::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 3px; height: 100%;
            background: linear-gradient(to bottom, transparent, var(--teal-500), transparent);
            opacity: 0;
            transition: opacity var(--t-panel);
        }
        .auth-card.active-login .panel-login::after { opacity: 1; }


        /* ══════════════════════════════════════
           RESPONSIVE
           ══════════════════════════════════════ */
        @media (max-width: 720px) {
            .auth-card { flex-direction: column; }
            .panel-login, .panel-signup { flex: none; width: 100%; padding: 40px 28px; border-radius: 0; }
            .panel-signup { border-radius: 0 0 var(--radius-card) var(--radius-card); }
            .panel-login  { border-radius: var(--radius-card) var(--radius-card) 0 0; }
            .name-row { grid-template-columns: 1fr; }
            .auth-card.active-login  .panel-signup { display: none; }
            .auth-card.active-signup .panel-login  { display: none; }
        }
    </style>
</head>

<body>

    {{-- ═══════════════════════════════════════════
         AUTH CARD — JS toggles .active-login / .active-signup
         Default: active-login (from /login)
                  active-signup (from /register or "Get Started")
         ═══════════════════════════════════════════ --}}
    <div class="auth-card {{ request()->routeIs('register') || request()->query('panel') === 'signup' ? 'active-signup' : 'active-login' }}" id="authCard">


        {{-- ╔══════════════════════════╗
             ║   LEFT PANEL — LOGIN     ║
             ╚══════════════════════════╝ --}}
        <div class="panel-login" id="panelLogin" aria-label="Login form">

            <div class="login-eyebrow">
                <span class="login-eyebrow-dot"></span>
                <span class="login-eyebrow-text">ScholarLink</span>
            </div>

            <h1 class="login-heading">Welcome Back!</h1>
            <p class="login-subheading">Sign in to continue your scholarship journey.</p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field-group">
                    <label for="login_email" class="field-label">Email Address</label>
                    <div class="input-wrap">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m2 7 10 7 10-7"/>
                        </svg>
                        <input
                            type="email"
                            id="login_email"
                            name="email"
                            class="form-input"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="email"
                        />
                    </div>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <label for="login_password" class="field-label">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="login_password"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        />
                    </div>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="login-meta">
                    <label class="remember-wrap">
                        <input type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }} />
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="or-row">or continue with</div>

            <div class="social-row">
                {{-- SSO --}}
                <button type="button" class="btn-social" title="Sign in with SSO">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z"/>
                    </svg>
                </button>
                {{-- Facebook --}}
                <a href="{{ route('socialite.redirect', 'facebook') }}" class="btn-social" title="Sign in with Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#1877f2">
                        <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.514c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                    </svg>
                </a>
                {{-- Google --}}
                <a href="{{ route('socialite.redirect', 'google') }}" class="btn-social" title="Sign in with Google">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="19" height="19">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </a>
            </div>

            <p class="signup-prompt">
                Don't have an account yet?
                <a href="#" class="switch-link-lp" id="toSignupLink">Sign up</a>
            </p>

        </div>{{-- /panel-login --}}


        {{-- ╔══════════════════════════╗
             ║   RIGHT PANEL — SIGN UP  ║
             ╚══════════════════════════╝ --}}
        <div class="panel-signup" id="panelSignup" aria-label="Sign up form">

            <h2 class="signup-heading">Create an account!</h2>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="name-row">
                    <div>
                        <label for="first_name" class="field-label-rp">First Name <span class="req">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-input-rp"
                            placeholder="Juan" value="{{ old('first_name') }}" required autocomplete="given-name" />
                        @error('first_name') <span class="field-error-rp">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="field-label-rp">Last Name <span class="req">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-input-rp"
                            placeholder="Dela Cruz" value="{{ old('last_name') }}" required autocomplete="family-name" />
                        @error('last_name') <span class="field-error-rp">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="field-group-rp">
                    <label for="register_email" class="field-label-rp">Email <span class="req">*</span></label>
                    <input type="email" id="register_email" name="email" class="form-input-rp"
                        placeholder="you@example.com" value="{{ old('email') }}" required autocomplete="email" />
                    @error('email') <span class="field-error-rp">{{ $message }}</span> @enderror
                </div>

                <div class="field-group-rp">
                    <label for="register_password" class="field-label-rp">Password <span class="req">*</span></label>
                    <input type="password" id="register_password" name="password" class="form-input-rp"
                        placeholder="Create a strong password" required autocomplete="new-password" />
                    @error('password') <span class="field-error-rp">{{ $message }}</span> @enderror
                </div>

                <div class="field-group-rp">
                    <label for="role" class="field-label-rp">Role <span class="req">*</span></label>
                    <select id="role" name="role" class="form-select-rp" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role</option>
                        @foreach ($roles ?? ['Applicant', 'Employer', 'Admin'] as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="field-error-rp">{{ $message }}</span> @enderror
                </div>

                <div class="terms-row">
                    <input type="checkbox" id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }} />
                    <label for="terms">
                        I agree to the
                        <a href="{{ Route::has('terms') ? route('terms') : '#' }}" target="_blank">Terms &amp; Conditions</a>
                        and the
                        <a href="{{ Route::has('privacy') ? route('privacy') : '#' }}" target="_blank">Data Privacy Policy</a>.
                    </label>
                </div>
                @error('terms')
                    <span class="field-error-rp" style="margin-top:-8px;margin-bottom:10px;display:block;">{{ $message }}</span>
                @enderror

                <button type="submit" class="btn-signup">Create Account</button>
            </form>

            <div class="or-row-rp">or sign up with</div>

            <a href="{{ route('socialite.redirect', 'google') }}"    class="btn-oauth">Sign up with Google</a>
            <a href="{{ route('socialite.redirect', 'facebook') }}"  class="btn-oauth">Sign up with Facebook</a>
            <a href="{{ route('socialite.redirect', 'microsoft') }}" class="btn-oauth">Sign up with Outlook</a>

            <p class="login-prompt">
                Already have an account?
                <a href="#" class="switch-link-rp" id="toLoginLink">Log in</a>
            </p>

        </div>{{-- /panel-signup --}}


    </div>{{-- /auth-card --}}


    <script>
    (function () {
        'use strict';

        const card     = document.getElementById('authCard');
        const toSignup = document.getElementById('toSignupLink');
        const toLogin  = document.getElementById('toLoginLink');

        function activateSignup(e) {
            if (e) e.preventDefault();
            card.classList.replace('active-login', 'active-signup');
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            // Update URL without reload so browser back works
            history.replaceState(null, '', '?panel=signup');
        }

        function activateLogin(e) {
            if (e) e.preventDefault();
            card.classList.replace('active-signup', 'active-login');
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.replaceState(null, '', '?panel=login');
        }

        toSignup.addEventListener('click', activateSignup);
        toLogin.addEventListener('click', activateLogin);

        {{-- Auto-switch to signup panel on register validation errors --}}
        @if ($errors->hasAny(['first_name', 'last_name', 'role', 'terms']))
            activateSignup();
        @endif

    })();
    </script>

</body>
</html>

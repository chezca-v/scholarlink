{{--
    ============================================================
    auth.blade.php — Authentication Page (Login & Sign Up)
    Two-panel split card: light left (Login) | teal right (Sign Up)
    Features: blur transition, panel switching, Laravel Blade hooks
    ============================================================
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'App') }} — Authentication</title>

    {{-- CSRF token for AJAX / fetch requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Google Fonts: Playfair Display (headings) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- =====================================================
         STYLES
         Organized in sections:
         1. CSS Custom Properties (tokens)
         2. Reset & Base
         3. Page / Body
         4. Card Wrapper
         5. Left Panel — Login
         6. Right Panel — Sign Up
         7. Shared Utilities (divider, errors, alerts)
         8. Blur Transition Logic
         9. Responsive
         ===================================================== -->
    <style>

        /* ─────────────────────────────────────────────
           1. CSS CUSTOM PROPERTIES
           ───────────────────────────────────────────── */
        :root {
            /* Brand palette — extracted directly from the image */
            --clr-teal-900:   #17535f;   /* darkest teal — Login button bg */
            --clr-teal-700:   #1b6878;   /* card right panel base */
            --clr-teal-600:   #1e7585;   /* right panel gradient top */
            --clr-teal-500:   #22889a;   /* right panel gradient bottom */
            --clr-gold:       #c9a227;   /* "Create an account!" heading + Login btn label */
            --clr-gold-light: #deb84e;
            --clr-left-bg:    #e8f3f5;   /* left panel background */
            --clr-white:      #ffffff;
            --clr-text-dark:  #0d2d38;   /* heading dark navy */
            --clr-text-body:  #2c5a65;
            --clr-text-muted: #6b98a4;
            --clr-border:     #cde4e9;   /* light input border */
            --clr-input-bg:   #ffffff;

            /* Right-panel inputs (white-tinted on teal) */
            --clr-rp-input-bg:     rgba(255,255,255,0.12);
            --clr-rp-input-border: rgba(255,255,255,0.45);
            --clr-rp-label:        rgba(255,255,255,0.88);

            /* Spacing & shape */
            --radius-card:  18px;
            --radius-input: 7px;
            --radius-btn:   7px;

            /* Transition used for blur / opacity swap */
            --t-panel: 0.42s cubic-bezier(0.4, 0, 0.2, 1);

            /* How much to blur the inactive panel */
            --blur-inactive: 5px;
        }

        /* ─────────────────────────────────────────────
           2. RESET & BASE
           ───────────────────────────────────────────── */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        a { text-decoration: none; }

        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
        }

        /* ─────────────────────────────────────────────
           3. PAGE / BODY
           ───────────────────────────────────────────── */
        body {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--clr-text-dark);
            background: var(--clr-teal-900);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
        }

        /* ─────────────────────────────────────────────
           4. CARD WRAPPER
           The outer card that holds both panels side-by-side.
           ───────────────────────────────────────────── */
        .auth-card {
            display: flex;
            width: 100%;
            max-width: 950px;
            min-height: 600px;
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.28),
                0 2px 8px  rgba(0, 0, 0, 0.18);
            position: relative;
        }


        /* ══════════════════════════════════════════════
           5. LEFT PANEL — LOGIN
           ══════════════════════════════════════════════ */

        .panel-login {
            flex: 0 0 50%;
            background: var(--clr-left-bg);
            padding: 56px 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        /* ── Login heading ── */
        .login-heading {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 2.15rem;
            color: var(--clr-text-dark);
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .login-subheading {
            font-size: 0.9rem;
            color: var(--clr-text-body);
            margin-bottom: 34px;
        }

        /* ── Session / error banners ── */
        .alert {
            padding: 10px 14px;
            border-radius: 7px;
            font-size: 0.82rem;
            margin-bottom: 18px;
        }
        .alert-success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #166534; }
        .alert-danger  { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.3);  color: #991b1b; }

        /* ── Field group ── */
        .field-group { margin-bottom: 16px; }

        .field-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--clr-text-dark);
            margin-bottom: 7px;
        }

        /* ── Input with optional leading icon ── */
        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 19px;
            height: 19px;
            color: var(--clr-text-muted);
            pointer-events: none;
            flex-shrink: 0;
        }

        /* Base input — left panel */
        .form-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid var(--clr-border);
            border-radius: var(--radius-input);
            background: var(--clr-input-bg);
            color: var(--clr-text-dark);
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .form-input:focus {
            border-color: var(--clr-teal-700);
            box-shadow: 0 0 0 3px rgba(27, 104, 120, 0.14);
        }

        .form-input::placeholder { color: #a8c5cc; }

        /* ── Remember me + Forgot password row ── */
        .login-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-wrap input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--clr-teal-700);
            cursor: pointer;
        }

        .remember-wrap span {
            font-size: 0.85rem;
            color: var(--clr-text-body);
            line-height: 1;
        }

        .forgot-link {
            font-size: 0.82rem;
            color: var(--clr-teal-700);
            font-weight: 500;
            transition: color 0.18s;
        }
        .forgot-link:hover { color: var(--clr-teal-500); text-decoration: underline; }

        /* ── Login button ── */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: var(--clr-teal-900);
            color: var(--clr-gold);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            transition: background 0.2s, transform 0.12s;
        }
        .btn-login:hover  { background: var(--clr-teal-700); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        /* ── OR divider (left panel) ── */
        .or-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: var(--clr-text-muted);
            font-size: 0.82rem;
        }
        .or-row::before,
        .or-row::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--clr-border);
        }

        /* ── Social icon buttons ── */
        .social-row {
            display: flex;
            gap: 14px;
            justify-content: center;
            margin-bottom: 0;
        }

        .btn-social {
            width: 48px;
            height: 48px;
            border: 1.5px solid var(--clr-border);
            border-radius: 50%;
            background: var(--clr-input-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.18s, box-shadow 0.18s, transform 0.15s;
            color: var(--clr-text-body);
        }
        .btn-social:hover {
            border-color: var(--clr-teal-700);
            box-shadow: 0 3px 10px rgba(27,104,120,0.15);
            transform: translateY(-2px);
        }
        .btn-social svg { width: 20px; height: 20px; }

        /* ── "Don't have an account?" prompt ── */
        .signup-prompt {
            text-align: center;
            margin-top: 26px;
            font-size: 0.875rem;
            color: var(--clr-text-body);
        }

        .signup-prompt .switch-link {
            color: var(--clr-teal-900);
            font-weight: 700;
            border-bottom: 1.5px solid var(--clr-teal-900);
            padding-bottom: 1px;
            cursor: pointer;
            transition: color 0.18s, border-color 0.18s;
        }
        .signup-prompt .switch-link:hover {
            color: var(--clr-teal-600);
            border-color: var(--clr-teal-600);
        }


        /* ══════════════════════════════════════════════
           6. RIGHT PANEL — SIGN UP
           ══════════════════════════════════════════════ */

        .panel-signup {
            flex: 0 0 50%;
            /* Teal gradient matching the screenshot */
            background: linear-gradient(170deg, var(--clr-teal-600) 0%, var(--clr-teal-700) 55%, var(--clr-teal-900) 100%);
            padding: 44px 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            border-radius: 0 var(--radius-card) var(--radius-card) 0;
        }

        /* ── "Create an account!" heading ── */
        .signup-heading {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 700;
            font-size: 1.9rem;
            color: var(--clr-gold);
            text-align: center;
            margin-bottom: 26px;
            line-height: 1.15;
        }

        /* ── Two-column name row ── */
        .name-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 12px;
        }

        /* ── Field group — right panel ── */
        .field-group-rp { margin-bottom: 12px; }

        .field-label-rp {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--clr-rp-label);
            margin-bottom: 6px;
        }

        .req { color: var(--clr-gold-light); margin-left: 2px; }

        /* Base input — right panel */
        .form-input-rp {
            width: 100%;
            padding: 10px 13px;
            border: 1.5px solid var(--clr-rp-input-border);
            border-radius: var(--radius-input);
            background: var(--clr-rp-input-bg);
            color: #fff;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-input-rp:focus {
            border-color: var(--clr-gold-light);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.22);
        }
        .form-input-rp::placeholder { color: rgba(255,255,255,0.35); }

        /* ── Role select ── */
        .form-select-rp {
            width: 100%;
            padding: 10px 32px 10px 13px;
            border: 1.5px solid var(--clr-rp-input-border);
            border-radius: var(--radius-input);
            background: var(--clr-teal-900);
            color: #fff;
            font-size: 0.875rem;
            outline: none;
            appearance: none;
            cursor: pointer;
            /* Custom caret arrow */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='rgba(255,255,255,0.6)' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            transition: border-color 0.18s;
        }
        .form-select-rp:focus {
            border-color: var(--clr-gold-light);
            box-shadow: 0 0 0 3px rgba(201,162,39,0.22);
        }
        .form-select-rp option { background: var(--clr-teal-900); }

        /* ── Terms checkbox row ── */
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 16px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.82);
            line-height: 1.5;
        }
        .terms-row input[type="checkbox"] {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            margin-top: 2px;
            accent-color: var(--clr-gold);
            cursor: pointer;
        }
        .terms-row a {
            color: rgba(255,255,255,0.9);
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .terms-row a:hover { color: var(--clr-gold-light); }

        /* ── Sign Up CTA button ── */
        .btn-signup {
            width: 100%;
            padding: 13px;
            background: var(--clr-teal-900);
            color: var(--clr-gold);
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            transition: background 0.2s, transform 0.12s;
        }
        .btn-signup:hover  { background: #0d1f28; transform: translateY(-1px); }
        .btn-signup:active { transform: translateY(0); }

        /* ── OR divider (right panel) ── */
        .or-row-rp {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0;
            color: rgba(255,255,255,0.45);
            font-size: 0.82rem;
        }
        .or-row-rp::before,
        .or-row-rp::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.22);
        }

        /* ── OAuth buttons — full-width white pill buttons ── */
        .btn-oauth {
            display: block;
            width: 100%;
            padding: 11px 14px;
            background: #fff;
            color: var(--clr-text-dark);
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
            border: none;
            border-radius: var(--radius-btn);
            cursor: pointer;
            margin-bottom: 10px;
            transition: background 0.18s, transform 0.12s, box-shadow 0.18s;
        }
        .btn-oauth:last-child { margin-bottom: 0; }
        .btn-oauth:hover {
            background: #eef7f9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* ── "Already have an account?" prompt ── */
        .login-prompt {
            text-align: center;
            margin-top: 20px;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.7);
        }
        .login-prompt .switch-link {
            color: var(--clr-gold-light);
            font-weight: 700;
            border-bottom: 1.5px solid var(--clr-gold-light);
            padding-bottom: 1px;
            cursor: pointer;
            transition: color 0.18s;
        }
        .login-prompt .switch-link:hover { color: var(--clr-gold); }


        /* ══════════════════════════════════════════════
           7. SHARED UTILITIES
           ══════════════════════════════════════════════ */

        /* Inline validation error messages */
        .field-error {
            display: block;
            margin-top: 5px;
            font-size: 0.77rem;
            color: #dc2626;
        }
        .field-error-rp {
            display: block;
            margin-top: 5px;
            font-size: 0.77rem;
            color: #fca5a5;
        }


        /* ══════════════════════════════════════════════
           8. BLUR TRANSITION LOGIC
           .auth-card carries either .active-login (default)
           or .active-signup. CSS blurs the inactive panel.
           ══════════════════════════════════════════════ */

        .panel-login,
        .panel-signup {
            transition:
                filter   var(--t-panel),
                opacity  var(--t-panel);
            will-change: filter, opacity;
        }

        /* When Login is active → blur the Sign Up panel */
        .auth-card.active-login .panel-signup {
            filter:  blur(var(--blur-inactive));
            opacity: 0.55;
            pointer-events: none;
            user-select: none;
        }

        /* When Sign Up is active → blur the Login panel */
        .auth-card.active-signup .panel-login {
            filter:  blur(var(--blur-inactive));
            opacity: 0.55;
            pointer-events: none;
            user-select: none;
        }

        /* Active panel is always crisp */
        .auth-card.active-login .panel-login,
        .auth-card.active-signup .panel-signup {
            filter:  blur(0px);
            opacity: 1;
        }


        /* ══════════════════════════════════════════════
           9. RESPONSIVE
           ══════════════════════════════════════════════ */
        @media (max-width: 720px) {
            .auth-card {
                flex-direction: column;
                border-radius: var(--radius-card);
            }

            .panel-login,
            .panel-signup {
                flex: none;
                width: 100%;
                padding: 40px 28px;
                border-radius: 0;
            }

            .panel-signup { border-radius: 0 0 var(--radius-card) var(--radius-card); }
            .panel-login  { border-radius: var(--radius-card) var(--radius-card) 0 0; }

            .name-row { grid-template-columns: 1fr; }

            /* On mobile: collapse the blurred panel out of view */
            .auth-card.active-login  .panel-signup { display: none; }
            .auth-card.active-signup .panel-login  { display: none; }
        }
    </style>
</head>

<body>

    {{-- ════════════════════════════════════════════════════════
         AUTH CARD
         JS toggles .active-login / .active-signup here.
         ════════════════════════════════════════════════════════ --}}
    <div class="auth-card active-login" id="authCard">


        {{-- ╔══════════════════════════════╗
             ║   LEFT PANEL — LOGIN         ║
             ╚══════════════════════════════╝ --}}
        <div class="panel-login" id="panelLogin" aria-label="Login form">

            {{-- Heading --}}
            <h1 class="login-heading">Welcome Back!</h1>
            <p class="login-subheading">Login your account</p>

            {{-- ── Session status (e.g. password-reset email sent) ── --}}
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ── Login form ── --}}
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="field-group">
                    <label for="login_email" class="field-label">Email</label>
                    <div class="input-wrap">
                        {{-- Mail icon --}}
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m2 7 10 7 10-7"/>
                        </svg>
                        <input
                            type="email"
                            id="login_email"
                            name="email"
                            class="form-input"
                            placeholder="Enter your email address"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
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
                        {{-- Lock icon --}}
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            type="password"
                            id="login_password"
                            name="password"
                            class="form-input"
                            placeholder="• • • • • • • • • • • • •"
                            required
                            autocomplete="current-password"
                        />
                    </div>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember me + Forgot password --}}
                <div class="login-meta">
                    <label class="remember-wrap">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember_me"
                            {{ old('remember') ? 'checked' : '' }}
                        />
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                {{-- Login CTA --}}
                <button type="submit" class="btn-login">Login</button>

            </form>{{-- /login form --}}

            {{-- OR divider --}}
            <div class="or-row">or</div>

            {{-- Social / OAuth icon buttons --}}
            <div class="social-row">

                {{-- Globe / SSO --}}
                <button type="button" class="btn-social" title="Sign in with SSO">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z"/>
                    </svg>
                </button>

                {{-- Facebook --}}
                <a href="{{ route('socialite.redirect', 'facebook') }}"
                   class="btn-social" title="Sign in with Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#1877f2">
                        <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.514c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                    </svg>
                </a>

                {{-- Mastercard-style circle icon (third social) --}}
                <button type="button" class="btn-social" title="Sign in with another provider">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 38 24">
                        <circle cx="12" cy="12" r="11" fill="#eb001b" opacity="0.9"/>
                        <circle cx="26" cy="12" r="11" fill="#3c2a8f" opacity="0.75"/>
                    </svg>
                </button>

            </div>{{-- /social-row --}}

            {{-- ── "Don't have an account? Sign up" (the red-marked area) ── --}}
            <p class="signup-prompt">
                Don't have an account?
                <a href="#" class="switch-link" id="toSignupLink" role="button" aria-label="Switch to Sign Up form">
                    Sign up
                </a>
            </p>

        </div>{{-- /panel-login --}}


        {{-- ╔══════════════════════════════╗
             ║   RIGHT PANEL — SIGN UP      ║
             ╚══════════════════════════════╝ --}}
        <div class="panel-signup" id="panelSignup" aria-label="Sign up form">

            {{-- Heading --}}
            <h2 class="signup-heading">Create an account!</h2>

            {{-- ── Sign Up form ── --}}
            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- First Name + Last Name (two columns) --}}
                <div class="name-row">
                    <div>
                        <label for="first_name" class="field-label-rp">
                            First Name <span class="req">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="form-input-rp"
                            value="{{ old('first_name') }}"
                            required
                            autocomplete="given-name"
                        />
                        @error('first_name')
                            <span class="field-error-rp">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="field-label-rp">
                            Last Name <span class="req">*</span>
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="form-input-rp"
                            value="{{ old('last_name') }}"
                            required
                            autocomplete="family-name"
                        />
                        @error('last_name')
                            <span class="field-error-rp">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="field-group-rp">
                    <label for="register_email" class="field-label-rp">
                        Email <span class="req">*</span>
                    </label>
                    <input
                        type="email"
                        id="register_email"
                        name="email"
                        class="form-input-rp"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    />
                    @error('email')
                        <span class="field-error-rp">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-group-rp">
                    <label for="register_password" class="field-label-rp">
                        Password <span class="req">*</span>
                    </label>
                    <input
                        type="password"
                        id="register_password"
                        name="password"
                        class="form-input-rp"
                        required
                        autocomplete="new-password"
                    />
                    @error('password')
                        <span class="field-error-rp">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Role — options injected by controller via $roles --}}
                <div class="field-group-rp">
                    <label for="role" class="field-label-rp">
                        Role <span class="req">*</span>
                    </label>
                    <select id="role" name="role" class="form-select-rp" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Applicant</option>
                        @foreach ($roles ?? ['Applicant', 'Employer', 'Admin'] as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="field-error-rp">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Terms & Conditions --}}
                <div class="terms-row">
                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required
                        {{ old('terms') ? 'checked' : '' }}
                    />
                    <label for="terms">
                        I agree to the
                        <a href="{{ route('terms') ?? '#' }}" target="_blank">Terms &amp; Conditions</a>
                        and acknowledge the
                        <a href="{{ route('privacy') ?? '#' }}" target="_blank">Data Privacy Policy</a>.
                    </label>
                </div>
                @error('terms')
                    <span class="field-error-rp" style="margin-top:-10px;margin-bottom:12px;display:block;">
                        {{ $message }}
                    </span>
                @enderror

                {{-- Sign Up CTA --}}
                <button type="submit" class="btn-signup">Sign up</button>

            </form>{{-- /register form --}}

            {{-- OR divider --}}
            <div class="or-row-rp">or</div>

            {{-- OAuth sign-up buttons --}}
            <a href="{{ route('socialite.redirect', 'google') }}"    class="btn-oauth">Sign up with Google</a>
            <a href="{{ route('socialite.redirect', 'facebook') }}"  class="btn-oauth">Sign up with Facebook</a>
            <a href="{{ route('socialite.redirect', 'microsoft') }}" class="btn-oauth">Sign up with Outlook</a>

            {{-- "Already have an account?" prompt --}}
            <p class="login-prompt">
                Already have an account?
                <a href="#" class="switch-link" id="toLoginLink" role="button" aria-label="Switch to Login form">
                    Log in
                </a>
            </p>

        </div>{{-- /panel-signup --}}


    </div>{{-- /auth-card --}}


    <!-- =====================================================
         JAVASCRIPT
         Handles panel switching (Login ↔ Sign Up) and drives
         the blur-transition by toggling .active-login /
         .active-signup on #authCard.
         ===================================================== -->
    <script>
        (function () {
            'use strict';

            const card        = document.getElementById('authCard');
            const toSignup    = document.getElementById('toSignupLink');
            const toLogin     = document.getElementById('toLoginLink');

            /**
             * activateSignup — shows Sign Up panel, blurs Login panel.
             */
            function activateSignup(e) {
                if (e) e.preventDefault();
                card.classList.replace('active-login', 'active-signup');
                /* Scroll to top of card on mobile */
                card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            /**
             * activateLogin — shows Login panel, blurs Sign Up panel.
             */
            function activateLogin(e) {
                if (e) e.preventDefault();
                card.classList.replace('active-signup', 'active-login');
                card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            /* Bind click events */
            toSignup.addEventListener('click', activateSignup);
            toLogin.addEventListener('click',  activateLogin);

            /**
             * Auto-activate Sign Up panel when the page reloads
             * after a failed registration attempt (Blade injects the flag).
             * Uses @if so this block is only emitted when needed.
             */
            @if ($errors->hasAny(['first_name', 'last_name', 'role', 'terms']))
                activateSignup();
            @endif

        })();
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set new password | ScholarLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:#F0FAFA;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:20px;}
        .page-frame{background:#FFFFFF;border:1px solid #DFF0EE;border-radius:20px;padding:40px;width:100%;max-width:450px;box-shadow:0 8px 24px rgba(15,76,92,0.1);}
        .logo-box{display:flex;align-items:center;gap:9px;margin-bottom:20px;}
        .logo-icon{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);display:flex;align-items:center;justify-content:center;color:#fff;}
        .auth-title{font-family:'Fraunces',serif;font-size:26px;font-weight:800;color:#0A3040;margin-bottom:4px;}
        .auth-sub{font-size:13px;color:#4A7A80;margin-bottom:24px;}
        .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:16px;}
        .form-label{font-size:12px;font-weight:600;color:#0A3040;}
        .input{font-size:13px;color:#0A3040;border:1.5px solid #DFF0EE;border-radius:10px;padding:10px 14px;width:100%;}
        .btn-primary{background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;border:none;padding:12px;border-radius:10px;font-weight:700;width:100%;cursor:pointer;margin-top:10px;}
    </style>
</head>
<body>

<div class="page-frame" x-data="{ password: '', confirm: '' }">
    <div class="logo-box">
        <div class="logo-icon">🔑</div>
        <span style="font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:#0F4C5C;">ScholarLink</span>
    </div>

    <h1 class="auth-title">Set new password</h1>
    <p class="auth-sub">Must be at least 8 characters with a number and symbol.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label class="form-label">New Password *</label>
            <input class="input" type="password" name="password" x-model="password" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm New Password *</label>
            <input class="input" type="password" name="password_confirmation" x-model="confirm" placeholder="••••••••" required>

            <div x-show="confirm !== ''"
                 :style="password === confirm ? 'color:green;' : 'color:red;'"
                 style="font-size:11px; font-weight:600; margin-top:4px;">
                <span x-text="password === confirm ? '✓ Passwords match' : '✕ Passwords do not match'"></span>
            </div>
        </div>

        <div style="background:#F0FAFA; padding:12px; border-radius:10px; margin-bottom:16px;">
            <div style="font-size:11px; color:#4A7A80; margin-bottom:4px;">Password strength:</div>
            <div x-show="password.length >= 8" style="font-size:11px; color:green;">✓ At least 8 characters</div>
            <div x-show="/\d/.test(password)" style="font-size:11px; color:green;">✓ Contains number</div>
            <div x-show="/[!@#$%^&*]/.test(password)" style="font-size:11px; color:green;">✓ Contains symbol</div>
            <div style="height:5px; background:#DFF0EE; border-radius:999px; margin-top:6px;">
                <div style="height:100%; border-radius:999px; background:green;" :style="'width:' + (password.length > 8 ? '100%' : (password.length * 10) + '%')"></div>
            </div>
        </div>

        <button type="submit" class="btn-primary">Reset Password</button>
    </form>
</div>

</body>
</html>

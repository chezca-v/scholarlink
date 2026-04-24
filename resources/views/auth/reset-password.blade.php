<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | ScholarLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:#F0FAFA;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:20px;}
        .page-frame{background:#FFFFFF;border:1px solid #DFF0EE;border-radius:20px;padding:40px;width:100%;max-width:450px;box-shadow:0 8px 24px rgba(15,76,92,0.1);}
        .auth-title{font-family:'Fraunces',serif;font-size:26px;font-weight:800;color:#0A3040;margin-bottom:4px;}
        .auth-sub{font-size:13px;color:#4A7A80;margin-bottom:24px;}
        .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:16px;}
        .form-label{font-size:12px;font-weight:600;color:#0A3040;}
        .input{font-size:13px;color:#0A3040;border:1.5px solid #DFF0EE;border-radius:10px;padding:10px 14px;width:100%;}
        .btn-primary{background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;border:none;padding:10px;border-radius:10px;font-weight:700;width:100%;cursor:pointer;margin-top:10px;}
    </style>
</head>
<body>

<div class="page-frame">
    <div style="display:flex;align-items:center;gap:9px;margin-bottom:20px;">
        <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);display:flex;align-items:center;justify-content:center;">🔑</div>
        <span style="font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:#0F4C5C;">ScholarLink</span>
    </div>

    <h1 class="auth-title">Set new password</h1>
    <p class="auth-sub">Must be at least 8 characters with a number and symbol.</p>

    @if ($errors->any())
        <div style="color:red; font-size:12px; margin-bottom:15px;">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input class="input" type="email" name="email" value="{{ $email ?? old('email') }}" readonly>
        </div>

        <div class="form-group">
            <label class="form-label">New Password *</label>
            <input class="input" type="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm New Password *</label>
            <input class="input" type="password" name="password_confirmation" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary">Reset Password</button>
    </form>
</div>

</body>
</html>
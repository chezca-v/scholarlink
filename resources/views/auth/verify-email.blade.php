<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | ScholarLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:#F0FAFA;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:20px;}
        .page-frame{background:#FFFFFF;border:1px solid #DFF0EE;border-radius:20px;padding:40px;width:100%;max-width:450px;text-align:center;box-shadow:0 8px 24px rgba(15,76,92,0.1);}
        .auth-title{font-family:'Fraunces',serif;font-size:26px;font-weight:800;color:#0A3040;margin-bottom:12px;}
        .auth-sub{font-size:13px;color:#4A7A80;margin-bottom:24px;line-height:1.6;}
        .btn-primary{background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;border:none;padding:12px;border-radius:10px;font-weight:700;width:100%;cursor:pointer;}
        .btn-ghost{background:transparent;color:#4A7A80;font-size:12px;text-decoration:underline;margin-top:16px;border:none;cursor:pointer;}
    </style>
</head>
<body>

<div class="page-frame">
    <div style="font-size:48px;margin-bottom:16px;">✉️</div>
    <h1 class="auth-title">Verify your email</h1>
    <p class="auth-sub">
        We've sent a verification link to <strong>{{ auth()->user()->email ?? 'your email' }}</strong>. 
        Please click the link to activate your ScholarLink account.
    </p>

    <form action="{{ route('verification.send') }}" method="POST">
        @csrf
        <button type="submit" class="btn-primary">Resend Verification Email</button>
    </form>

    @if (session('message'))
        <div style="color:green; font-size:12px; margin-top:15px;">{{ session('message') }}</div>
    @endif

    <div style="margin-top:20px;">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-ghost">Logout</button>
        </form>
    </div>
</div>

</body>
</html>
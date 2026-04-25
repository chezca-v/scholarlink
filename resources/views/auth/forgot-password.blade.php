<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | ScholarLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base Reset */
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:#F0FAFA;display:flex;justify-content:center;align-items:center;min-height:100vh;}
        
        /* Layout Component (Split Screen) */
        .page-frame{
            background:#FFFFFF;
            border:1px solid #DFF0EE;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 8px 24px rgba(15,76,92,0.10);
            max-width:900px;
            width:100%;
        }
        .auth-split{display:grid;grid-template-columns:1fr 1fr;min-height:540px;}
        
        /* Left Panel (Teal) */
        .auth-left{
            background:linear-gradient(160deg,#0F4C5C,#1A6B7A);
            padding:48px 40px;
            display:flex;
            flex-direction:column;
            justify-content:center;
            color:#FFFFFF;
            position:relative;
        }
        .auth-brand{
            font-family:'Fraunces',serif;
            font-size:40px;
            font-weight:900;
            line-height:1;
        }
        .auth-brand em{font-style:italic;font-weight:300;color:#F9D679;}
        .auth-desc{font-size:13px;color:rgba(255,255,255,0.6);margin-top:14px;line-height:1.6;}
        
        /* Right Panel (Form) */
        .auth-right{padding:40px 44px;display:flex;flex-direction:column;justify-content:center;}
        .auth-title{font-family:'Fraunces',serif;font-size:26px;font-weight:800;color:#0A3040;margin-bottom:4px;}
        .auth-sub{font-size:13px;color:#4A7A80;margin-bottom:28px;}
        .auth-link{font-size:12px;font-weight:600;color:#0F4C5C;cursor:pointer;text-decoration:underline;display:inline-flex;align-items:center;}
        
        /* Form Components */
        .form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:14px;}
        .form-label{font-size:12px;font-weight:600;color:#0A3040;}
        .input{font-size:13px;color:#0A3040;border:1.5px solid #DFF0EE;border-radius:10px;padding:9px 13px;width:100%;transition:border-color 0.2s;}
        .input:focus{border-color:#0F4C5C;outline:none;}
        .btn{font-weight:700;cursor:pointer;border-radius:10px;font-size:13px;padding:9px 20px;border:none;width:100%;transition:opacity 0.2s;}
        .btn:hover{opacity:0.9;}
        .btn-primary{background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;}
    </style>
</head>
<body>

<div class="page-frame">
  <div class="auth-split">
    <div class="auth-left">
      <div class="auth-brand">Forgot<br>your <em>pass&shy;word?</em></div>
      <div class="auth-desc">No worries. Enter your email and we'll send a reset link to your account within a few seconds.</div>
    </div>
    
    <div class="auth-right">
      <div class="auth-title">Reset your password</div>
      <div class="auth-sub">Enter the PLM email address linked to your ScholarLink account.</div>
      
      @if (session('status'))
          <div style="color:green; font-size:12px; margin-bottom:10px; text-align:center;">{{ session('status') }}</div>
      @endif

      <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="nycfrigillana2024@plm.edu.ph" required>
          @error('email')
              <div style="color:red; font-size:11px; margin-top:3px;">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
      </form>

      <div style="text-align:center;margin-top:16px;">
        <a href="{{ route('login') }}" class="auth-link">← Back to Login</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
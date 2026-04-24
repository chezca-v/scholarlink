<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ScholarLink — Forgot Password</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  /* Base reset and shared variables */
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'DM Sans',sans-serif;background:#F0FAFA;color:#0A3040;padding:40px;}
  :root{
    --teal:#0F4C5C;--teal-mid:#1A6B7A;--amber:#E8A838;--amber-light:#F9D679;
    --white:#FFFFFF;--ink:#0A3040;--slate:#4A7A80;--muted:#7AACAA;--border:#DFF0EE;
    --r-md:10px;--r-xl:20px;--shadow-m:0 8px 24px rgba(15,76,92,0.10);
  }
  
  /* Component Styles */
  .page-frame{background:var(--white);border:1px solid var(--border);border-radius:var(--r-xl);overflow:hidden;box-shadow:var(--shadow-m);max-width:900px;margin:0 auto;}
  .auth-split{display:grid;grid-template-columns:1fr 1fr;min-height:540px;}
  .auth-left{background:linear-gradient(160deg,var(--teal),var(--teal-mid));padding:48px 40px;display:flex;flex-direction:column;justify-content:center;position:relative;}
  .auth-brand{font-family:'Fraunces',serif;font-size:40px;font-weight:900;color:#fff;line-height:1;}
  .auth-brand em{font-style:italic;font-weight:300;color:var(--amber-light);}
  .auth-desc{font-size:13px;color:rgba(255,255,255,0.6);margin-top:14px;}
  .auth-right{padding:40px 44px;display:flex;flex-direction:column;justify-content:center;}
  .auth-title{font-family:'Fraunces',serif;font-size:26px;font-weight:800;color:var(--ink);margin-bottom:4px;}
  .auth-sub{font-size:13px;color:var(--slate);margin-bottom:28px;}
  .auth-link{font-size:12px;font-weight:600;color:var(--teal);cursor:pointer;text-decoration:underline;}
  .auth-centered{display:flex;flex-direction:column;align-items:center;text-align:center;}
  .auth-icon-big{font-size:48px;margin-bottom:16px;}
  
  .form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:14px;}
  .form-label{font-size:12px;font-weight:600;color:var(--ink);}
  .input{font-size:13px;color:var(--ink);border:1.5px solid var(--border);border-radius:var(--r-md);padding:9px 13px;width:100%;}
  .btn{font-weight:700;cursor:pointer;border-radius:var(--r-md);font-size:13px;padding:9px 20px;border:none;width:100%;}
  .btn-primary{background:linear-gradient(135deg,var(--teal),var(--teal-mid));color:var(--amber-light);}
  .btn-ghost{background:var(--white);color:var(--slate);border:1px solid var(--border);}
</style>
</head>
<body>

<div class="page-frame">
  <div class="auth-split">
    <div class="auth-left">
      <div class="auth-brand">Forgot<br>your <em>pass&shy;word?</em></div>
      <div class="auth-desc">No worries. Enter your email and we'll send you a reset link within a few seconds.</div>
    </div>
    
    <div class="auth-right" id="forgot-right">
      <div class="auth-title">Reset your password</div>
      <div class="auth-sub">Enter the email address linked to your ScholarLink account.</div>
      <div class="form-group">
        <label class="form-label">Email Address *</label>
        <input class="input" placeholder="nycfrigillana2024@plm.edu.ph" value="nycfrigillana2024@plm.edu.ph">
      </div>
      <button class="btn btn-primary" onclick="showForgotSuccess()">Send Reset Link</button>
      <div style="text-align:center;margin-top:16px;"><a class="auth-link">← Back to Login</a></div>
    </div>

    <div class="auth-right" id="forgot-success" style="display:none;">
      <div class="auth-centered">
        <div class="auth-icon-big">📬</div>
        <div class="auth-title">Check your inbox!</div>
        <div class="auth-sub" style="max-width:280px;">We sent a reset link to <strong>nycfrigillana2024@plm.edu.ph</strong>.</div>
        <div style="width:100%;display:flex;flex-direction:column;gap:10px;">
          <button class="btn btn-primary">Open Gmail →</button>
          <button class="btn btn-ghost" onclick="showForgotForm()">Resend email</button>
        </div>
        <div style="margin-top:16px;"><a class="auth-link">← Back to Login</a></div>
      </div>
    </div>
  </div>
</div>

<script>
  function showForgotSuccess(){
    document.getElementById('forgot-right').style.display='none';
    document.getElementById('forgot-success').style.display='flex';
  }
  function showForgotForm(){
    document.getElementById('forgot-right').style.display='flex';
    document.getElementById('forgot-success').style.display='none';
  }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | ScholarLink</title>
    </head>
<body>

<div class="page-frame">
  <div class="auth-split">
    <div class="auth-left">
      <div class="auth-brand">Forgot<br>your <em>pass&shy;word?</em></div>
      <div class="auth-desc">No worries. Enter your email and we'll send a reset link to your account.</div>
    </div>
    
    <div class="auth-right" id="forgot-right">
      <div class="auth-title">Reset your password</div>
      <div class="auth-sub">Enter your PLM email address.</div>
      
      @if ($errors->any())
        <div style="color:red; font-size:12px; margin-bottom:10px;">{{ $errors->first() }}</div>
      @endif

      <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">Email Address *</label>
          <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="username@plm.edu.ph" required>
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
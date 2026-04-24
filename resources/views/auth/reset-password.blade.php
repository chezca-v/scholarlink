<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | ScholarLink</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Simplified styles for this specific component */
        :root { --teal:#0F4C5C; --teal-mid:#1A6B7A; --border:#DFF0EE; --r-md:10px; --ink:#0A3040; --muted:#7AACAA; }
        body { font-family:'DM Sans',sans-serif; background:#F0FAFA; display:flex; justify-content:center; padding:40px; }
        .page-frame { background:#FFF; border:1px solid var(--border); border-radius:20px; width:100%; max-width:450px; overflow:hidden; box-shadow:0 8px 24px rgba(15,76,92,0.1); }
        .auth-right { padding:40px; }
        .form-group { display:flex; flex-direction:column; gap:6px; margin-bottom:16px; }
        .input { border:1.5px solid var(--border); border-radius:var(--r-md); padding:10px 14px; width:100%; }
        .btn-primary { background:linear-gradient(135deg,var(--teal),var(--teal-mid)); color:#FFF; border:none; padding:10px; border-radius:var(--r-md); font-weight:700; width:100%; cursor:pointer; }
    </style>
</head>
<body>

<div class="page-frame">
    <div class="auth-right">
        <div style="display:flex;align-items:center;gap:9px;margin-bottom:20px;">
            <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--teal),var(--teal-mid));display:flex;align-items:center;justify-content:center;">🔑</div>
            <span style="font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:var(--teal);">ScholarLink</span>
        </div>
        
        <h1 style="font-family:'Fraunces',serif;font-size:26px;color:var(--ink);margin-bottom:4px;">Set new password</h1>
        <p style="font-size:13px;color:var(--muted);margin-bottom:22px;">Must be at least 8 characters with a number and symbol.</p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
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
</div>

</body>
</html>
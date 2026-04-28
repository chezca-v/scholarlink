<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'ScholarLink')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Fraunces:opsz,wght@9..144,700;9..144,900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --teal:#0F4C5C;
  --teal-hover:#0c3f4d;
  --teal-light:#2A8FA0;
  --amber:#C9A84C;
  --amber-light:#F9D679;
  --cloud:#F4F6FA;
  --mist:#E2E8F0;
  --slate:#8A95A3;
  --ink:#1C1C2E;
  --light-green:#F0FAFA;
  --sidebar-w:210px;
}
body{font-family:'DM Sans',sans-serif;background:#F0FAFA;color:var(--ink);min-height:100vh;-webkit-font-smoothing:antialiased;}

/* ── NAVBAR ── */
.navbar{
  background:#FFFF;height:56px;
  display:flex;align-items:center;padding:0 22px;gap:14px;
  position:sticky;top:0;z-index:200;
  box-shadow:0 1px 4px rgba(0,0,0,0.18);
}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0;}
.logo-box{width:32px;height:32px;background:#0F4C5C;color:#fff;font-size:18px;box-shadow:0 4px 12px rgba(0,0,0,0.12);border:1.5px solid rgba(255,255,255,0.25);border-radius:8px;display:flex;align-items:center;justify-content:center;}
.logo-text{font-family:'Fraunces',serif;font-size:16px;font-weight:700;color:#0F4C5C;letter-spacing:-0.2px;}
.nav-search{flex:1;max-width:440px;margin:0 auto;position:relative;}
.nav-search input{width:100%;height:34px;background:var(--light-green);border:1px solid rgba(15,76,92,0.10);border-radius:30px;padding:0 54px 0 34px;font-family:'DM Sans',sans-serif;font-size:13px;color:var(--teal);outline:none;}
.nav-search input::placeholder{color:rgba(15,76,92,0.48);}
.si{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#0c3f4d;pointer-events:none;display:flex;}
.nav-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
.nav-ibtn{width:35px;height:35px;border-radius:10px;background:var(--light-green);border:2px solid rgba(15,76,92,0.12);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:all 0.2s ease;color:var(--teal);}
.nav-ibtn:hover{background:rgba(15,76,92,0.12);border-color:rgba(15,76,92,0.25);}
.nbadge{position:absolute;top:5px;right:5px;width:8px;height:8px;border-radius:50%;background:#F9D679;border:1.5px solid var(--teal);}
.nav-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(160deg,#0F4C5C,#2A8FA0);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid rgba(255,255,255,0.35);text-decoration:none;}

/* ── LAYOUT ── */
.app{display:flex;min-height:calc(100vh - 56px);}

/* ── SIDEBAR ── */
.sidebar{
  width:var(--sidebar-w);flex-shrink:0;
  background:#fff;
  border-right:1px solid var(--mist);
  display:flex;flex-direction:column;
  position:sticky;top:56px;
  height:calc(100vh - 56px);
  overflow-y:auto;
  padding:20px 0 16px;
}
.sidebar::-webkit-scrollbar{width:3px;}
.sidebar::-webkit-scrollbar-thumb{background:var(--mist);}
.sb-section-label{font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--slate);padding:0 18px;margin-bottom:6px;margin-top:18px;}
.sb-section-label:first-of-type{margin-top:0;}
.sb-nav-item{display:flex;align-items:center;gap:10px;padding:8px 18px;font-size:13px;font-weight:500;color:#4a5568;cursor:pointer;border-left:3px solid transparent;transition:all .15s;text-decoration:none;position:relative;}
.sb-nav-item:hover{background:var(--light-green);color:var(--teal);}
.sb-nav-item.active{background:#FDF8EC;color:var(--teal);font-weight:700;border-left-color:var(--teal);}
.sb-badge{margin-left:auto;background:#E8A838;color:#0F4C5C;font-size:10px;font-weight:700;border-radius:20px;padding:1px 7px;min-width:20px;text-align:center;}
.sb-badge-transparent{margin-left:auto;color:#E8A838;font-size:11px;font-weight:700;}
.sb-spacer{flex:1;}
.sb-user-btn{display:flex;align-items:center;gap:10px;padding:12px 16px;margin:0 10px 4px;background:var(--light-green);border:2px solid rgba(15,76,92,0.2);border-radius:14px;cursor:pointer;text-align:left;width:calc(100% - 20px);transition:background 0.2s;text-decoration:none;}
.sb-user-btn:hover{background:rgba(220,38,38,0.05);border-color:rgba(220,38,38,0.2);}
.sb-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-name{font-size:12.5px;font-weight:600;color:var(--ink);}
.sb-sub{font-size:11px;color:var(--slate);}

/* MAIN */
.main{flex:1;padding:0;min-width:0;overflow-y:auto;display:flex;flex-direction:column;}
.main-inner{padding:24px 28px 40px;flex:1;}

@media (max-width: 768px) {
  .sidebar { display: none; }
}
</style>
@stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <a class="nav-logo" href="{{ route('dashboard') }}">
    <img src="/favicon.ico" alt="ScholarLink Logo" style="width: 28px; height: 28px; object-fit: contain; border-radius: 4px;" />
    <span class="logo-text">ScholarLink</span>
  </a>
  <div class="nav-search">
    <span class="si"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
    <form action="{{ route('scholarships.index') }}" method="GET" style="display:inline; width:100%;">
        <input type="text" name="q" placeholder="Search scholarship, organizations..." value="{{ request('q') }}">
    </form>
  </div>
  <div class="nav-right">
    <a href="{{ route('notifications.index') }}" class="nav-ibtn" title="Notifications">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      @php $navUnread = App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
      @if($navUnread > 0)
      <span class="nbadge"></span>
      @endif
    </a>
    <a href="{{ route('profile.show') }}" class="nav-av" title="My Profile">{{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1) . substr(auth()->user()->last_name ?? '', 0, 1)) }}</a>
  </div>
</nav>

<!-- APP LAYOUT -->
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-section-label">Main</div>
    <a class="sb-nav-item {{ request()->routeIs('dashboard') || request()->routeIs('scholarships.index') ? 'active' : '' }}" href="{{ route('scholarships.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Browse
    </a>
    <a class="sb-nav-item {{ request()->routeIs('applications.*') ? 'active' : '' }}" href="{{ route('applications.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Applications
      @php
          $totalApplied = App\Models\Application::where('applicant_id', auth()->id())->count();
      @endphp
      @if($totalApplied > 0)
        <span class="sb-badge-transparent">{{ $totalApplied }}</span>
      @endif
    </a>
    <a class="sb-nav-item {{ request()->routeIs('applicant.saved') ? 'active' : '' }}" href="{{ route('applicant.saved') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
      Saved
      @php $savedCount = App\Models\SavedScholarship::where('user_id', auth()->id())->count(); @endphp
      @if($savedCount > 0)
      <span class="sb-badge-transparent">{{ $savedCount }}</span>
      @endif
    </a>

    <div class="sb-section-label">Account</div>
    <a class="sb-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      My Profile
    </a>
    <a class="sb-nav-item {{ request()->routeIs('applicant.documents.*') ? 'active' : '' }}" href="{{ route('applicant.documents.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      Documents
    </a>
    <a class="sb-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      Notifications
      @if($navUnread > 0)
      <span class="sb-badge">{{ $navUnread }}</span>
      @endif
    </a>

    <div class="sb-spacer"></div>

    <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0;">
      @csrf
      <button type="submit" class="sb-user-btn" onclick="return confirm('Are you sure you want to log out?');">
        <div class="sb-av">{{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1) . substr(auth()->user()->last_name ?? '', 0, 1)) }}</div>
        <div style="flex:1;">
          <div class="sb-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</div>
          <div class="sb-sub" style="display:flex; justify-content:space-between;">
             <span>Applicant</span>
             <span style="color:#DC2626;font-weight:700;">Log Out</span>
          </div>
        </div>
      </button>
    </form>
  </aside>

  <!-- MAIN -->
  <main class="main">
    @yield('content')
  </main>
</div>

<!-- Chatbot Widget -->
<x-chatbot-widget />

@stack('scripts')
</body>
</html>

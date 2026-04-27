<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ScholarLink — Notifications</title>
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
  --green-bg:#dcfce7;
  --green-text:#15803d;
  --warn-bg:#fef9c3;
  --warn-text:#854d0e;
  --violet-bg:#ede9fe;
  --violet-text:#6d28d9;
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
.nav-ibtn{width:35px;height:35px;border-radius:10px;background:var(--light-green);border:2px solid rgba(15,76,92,0.12);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:all 0.2s ease;}
.nav-ibtn:hover{background:rgba(15,76,92,0.12);border-color:rgba(15,76,92,0.25);}
.nbadge{position:absolute;top:5px;right:5px;width:8px;height:8px;border-radius:50%;background:#F9D679;border:1.5px solid var(--teal);}
.nav-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(160deg,#0F4C5C,#2A8FA0);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid rgba(255,255,255,0.35);}

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
.sb-nav-item.active{background:var(--light-green);color:var(--teal);font-weight:700;border-left-color:var(--teal);}
.sb-badge{margin-left:auto;background:#E8A838;color:#0F4C5C;font-size:10px;font-weight:700;border-radius:20px;padding:1px 7px;min-width:20px;text-align:center;}
.sb-spacer{flex:1;}
.sb-user{display:flex;align-items:center;gap:10px;padding:12px 16px;margin:0 10px 4px;background:var(--light-green);border:2px solid rgba(15,76,92,0.2);border-radius:14px;}
.sb-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-name{font-size:12.5px;font-weight:600;color:var(--ink);}
.sb-sub{font-size:11px;color:var(--slate);}

/* ── MAIN CONTENT (NOTIFICATIONS) ── */
.main{flex:1;padding:24px 28px 40px;min-width:0;overflow-y:auto;display:flex;}

.inbox-container {
    background: #fff;
    border: 1px solid var(--mist);
    border-radius: 16px;
    padding: 30px;
    width: 100%;
    box-shadow: 0 4px 20px rgba(15, 76, 92, 0.04);
}

.inbox-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.inbox-title-area {
    display: flex;
    flex-direction: column;
}

.inbox-eyebrow {
    font-size: 11px;
    font-weight: 700;
    color: #E8A838;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.inbox-title {
    font-family: 'Fraunces', serif;
    font-size: 28px;
    font-weight: 900;
    color: var(--teal);
    line-height: 1.1;
}

.btn-mark-read {
    background: #fff;
    border: 1.5px solid var(--mist);
    color: var(--teal);
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-mark-read:hover {
    border-color: var(--teal);
    background: var(--light-green);
}

/* Filters */
.filters-row {
    display: flex;
    gap: 8px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.filter-pill {
    background: #fff;
    border: 1.5px solid var(--mist);
    color: var(--slate);
    font-size: 13px;
    font-weight: 600;
    padding: 6px 16px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.filter-pill:hover {
    border-color: var(--teal-light);
    color: var(--teal);
}

.filter-pill.active {
    background: var(--teal);
    border-color: var(--teal);
    color: #fff;
}

/* Notification List */
.notif-list {
    display: flex;
    flex-direction: column;
}

.n-item {
    display: flex;
    align-items: flex-start;
    padding: 20px 0;
    border-bottom: 1px solid var(--mist);
    position: relative;
}

.n-item:last-child {
    border-bottom: none;
}

.n-item.unread::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 20px;
    bottom: 20px;
    width: 3px;
    background: var(--teal);
    border-radius: 0 4px 4px 0;
}

/* For inner layout where we want the border inside the padding */
.n-item.unread {
    border-left: 3px solid var(--teal);
    padding-left: 17px;
    margin-left: -20px;
}
.n-item.read {
    padding-left: 20px;
    margin-left: -20px;
}

.n-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    margin-right: 16px;
}

.n-icon.green { background: #e0f8e9; color: #15803d; }
.n-icon.purple { background: #f3e8ff; color: #7e22ce; }
.n-icon.yellow { background: #fef08a; color: #a16207; }
.n-icon.blue { background: #e0f2fe; color: #0369a1; }
.n-icon.orange { background: #ffedd5; color: #c2410c; }

.n-content {
    flex: 1;
    min-width: 0;
}

.n-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--teal);
    margin-bottom: 4px;
}

.n-body {
    font-size: 13.5px;
    color: #4a5568;
    line-height: 1.4;
    margin-bottom: 6px;
}

.n-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.n-time {
    font-size: 11.5px;
    color: var(--slate);
}

.n-new-badge {
    background: #184A59;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.n-action {
    margin-left: 16px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.btn-action {
    background: #fff;
    border: 1px solid var(--mist);
    color: var(--teal);
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-action:hover {
    border-color: var(--teal);
    background: var(--light-green);
}

.btn-action.primary {
    background: var(--teal);
    color: #fff;
    border-color: var(--teal);
}

.btn-action.primary:hover {
    background: var(--teal-hover);
}

.btn-action.warning {
    background: #F9D679;
    color: var(--teal);
    border-color: #F9D679;
}

.btn-action.warning:hover {
    background: #f5c853;
}

</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <a class="nav-logo" href="{{ route('dashboard') }}">
    <div class="logo-box">🎓</div>
    <span class="logo-text">ScholarLink</span>
  </a>
  <div class="nav-search">
    <span class="si"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
    <input type="text" placeholder="Search...">
  </div>
  <div class="nav-right">
    <button class="nav-ibtn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      @if($notifications->where('is_read', false)->count() > 0)
      <span class="nbadge"></span>
      @endif
    </button>
    <div class="nav-av">{{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</div>
  </div>
</nav>

<!-- APP LAYOUT -->
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-section-label">Main</div>
    <a class="sb-nav-item" href="{{ route('dashboard') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
    <a class="sb-nav-item" href="{{ route('scholarships.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Browse
    </a>
    <a class="sb-nav-item" href="{{ route('applicant.documents.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Applications
    </a>
    <div class="sb-section-label">Account</div>
    <a class="sb-nav-item" href="{{ route('profile.edit') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      My Profile
    </a>
    <a class="sb-nav-item" href="{{ route('documents.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      Documents
    </a>
    <a class="sb-nav-item active" href="{{ route('notifications.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      Notifications
      @php $unreadCount = $notifications->where('is_read', false)->count(); @endphp
      @if($unreadCount > 0)
      <span class="sb-badge">{{ $unreadCount }}</span>
      @endif
    </a>
    <div class="sb-spacer"></div>
    <div class="sb-user">
      <div class="sb-av">{{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</div>
      <div>
        <div class="sb-name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="sb-sub">Applicant</div>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <div class="inbox-container">

        <div class="inbox-header">
            <div class="inbox-title-area">
                <span class="inbox-eyebrow">INBOX</span>
                <h1 class="inbox-title">Notifications</h1>
            </div>

            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn-mark-read">Mark all as read</button>
            </form>
        </div>

        @php
            $counts = [
                'all' => $notifications->count(),
                'apps' => $notifications->filter(fn($n) => str_contains(strtolower($n->title), 'application'))->count(),
                'docs' => $notifications->filter(fn($n) => str_contains(strtolower($n->title), 'document') || str_contains(strtolower($n->title), 'action required'))->count(),
                'sms' => $notifications->where('type', 'sms')->count(),
                'matches' => $notifications->filter(fn($n) => str_contains(strtolower($n->title), 'match') || str_contains(strtolower($n->title), 'congratulations'))->count()
            ];
        @endphp

        <div class="filters-row">
            <a href="#" class="filter-pill active">All ({{ $counts['all'] }})</a>
            <a href="#" class="filter-pill">Applications ({{ $counts['apps'] }})</a>
            <a href="#" class="filter-pill">Documents ({{ $counts['docs'] }})</a>
            <a href="#" class="filter-pill">SMS ({{ $counts['sms'] }})</a>
            <a href="#" class="filter-pill">Matches ({{ $counts['matches'] }})</a>
        </div>

        <div class="notif-list">
            @forelse($notifications as $notif)
                @php
                    $isUnread = !$notif->is_read;
                    $titleLower = strtolower($notif->title);

                    // Determine icon and color
                    $iconColor = 'purple';
                    $iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';
                    $actionText = 'View';
                    $btnClass = '';

                    if (str_contains($titleLower, 'approved') || str_contains($titleLower, 'verified')) {
                        $iconColor = 'green';
                        $iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
                        $btnClass = 'primary';
                    } elseif (str_contains($titleLower, 'rejected') || str_contains($titleLower, 'required') || str_contains($titleLower, 'expiring')) {
                        $iconColor = 'yellow';
                        $iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
                        if (str_contains($titleLower, 'expiring')) {
                            $actionText = 'Upload';
                            $btnClass = 'warning';
                        }
                    } elseif ($notif->type === 'sms') {
                        $iconColor = 'blue';
                        $iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>';
                    } elseif (str_contains($titleLower, 'match') || str_contains($titleLower, 'congratulations')) {
                        $iconColor = 'orange';
                        $iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
                        $actionText = 'See all';
                    } elseif (str_contains($titleLower, 'assigned')) {
                        $iconColor = 'purple';
                        $iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';
                    }
                @endphp

                <div class="n-item {{ $isUnread ? 'unread' : 'read' }}">
                    <div class="n-icon {{ $iconColor }}">
                        {!! $iconSvg !!}
                    </div>
                    <div class="n-content">
                        <div class="n-title">{{ $notif->title }}</div>
                        <div class="n-body">{{ $notif->body }}</div>
                        <div class="n-meta">
                            <span class="n-time">{{ $notif->created_at->diffForHumans() }}</span>
                            @if($isUnread)
                                <span class="n-new-badge">New</span>
                            @endif
                        </div>
                    </div>
                    <div class="n-action">
                        <a href="#" class="btn-action {{ $btnClass }}">{{ $actionText }}</a>
                    </div>
                </div>
            @empty
                <div class="n-item" style="justify-content: center; padding: 40px 0; color: var(--slate);">
                    No notifications available.
                </div>
            @endforelse
        </div>
    </div>
  </main>
</div>

</body>
</html>

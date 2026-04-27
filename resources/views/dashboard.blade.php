<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ScholarLink — Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Fraunces:opsz,wght@9..144,700&display=swap" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

/* ── NAVBAR (copied from browse page) ── */
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
.nav-search input:focus{background:rgba(15,76,92,0);}
.si{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#0c3f4d;pointer-events:none;display:flex;}
.skb{position:absolute;right:8px;top:50%;transform:translateY(-50%);display:flex;gap:2px;pointer-events:none;}
.kb{background:rgba(255,255,255,0.14);border:1px solid rgba(15,76,92,0.5);border-radius:3px;color:#0c3f4d;font-size:10px;font-weight:600;padding:0 4px;line-height:16px;}
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

/* ── MAIN CONTENT ── */
.main{flex:1;padding:24px 28px 40px;min-width:0;overflow-y:auto;}

/* ── HERO BANNER ── */
.hero{
  border-radius:18px;
  background:linear-gradient(160deg, #0F4C5C, #2A8FA0);
  padding:28px 32px;
  display:flex;align-items:flex-start;justify-content:space-between;
  margin-bottom:20px;
  position:relative;
  overflow:hidden;
}
.hero-eyebrow{font-size:11px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,0.65);margin-bottom:4px;}
.hero-name{font-family:'Fraunces',serif;font-size:30px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px;}
.hero-name em{color:var(--amber-light);font-style:italic;}
.hero-sub{font-size:13px;color:rgba(255,255,255,0.65);margin-bottom:18px;}
.btn-ai{display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg, #E8A838, #F9D679);color:var(--teal);font-family:'DM Sans',sans-serif;font-size:13px;font-weight:700;border:none;border-radius:10px;padding:9px 18px;cursor:pointer;transition:all .15s;}
.btn-ai:hover{background:#F9D679;transform:translateY(-1px);}
.btn-ai svg{flex-shrink:0;}
.hero-right{display:flex;flex-direction:column;align-items:center;gap:4px;z-index:1;}
.progress-ring{position:relative;width:80px;height:80px;}
.progress-ring svg{transform:rotate(-90deg);}
.ring-bg{fill:none;stroke:rgba(255,255,255,0.12);stroke-width:7;}
.ring-fill{fill:none;stroke:var(--amber-light);stroke-width:7;stroke-linecap:round;stroke-dasharray:220;stroke-dashoffset:55;transition:stroke-dashoffset .6s ease;}
.ring-label{font-family:'Fraunces',serif;position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.ring-pct{font-size:18px;font-weight:700;color:#fff;line-height:1;}
.ring-sub{font-size:8.5px;color:rgba(255,255,255,0.55);margin-top:1px;}
.hero-right-label{font-size:10.5px;color:rgba(255,255,255,0.55);text-align:center;}
.hero-right-hint{font-size:10px;color:rgba(255,255,255,0.55);text-align:center;}

/* ── STAT CARDS ── */
.stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
.stat-card{background:#fff;border:1.5px solid var(--mist);border-radius:14px;padding:16px 18px;position:relative;overflow:hidden;}
.stat-badge{position:absolute;top:12px;right:12px;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;}
.stat-badge.active{background:var(--green-bg);color:var(--green-text);}
.stat-badge.ai{background:var(--violet-bg);color:var(--violet-text);}
.stat-badge.won{background:var(--warn-bg);color:var(--warn-text);}
.stat-badge.saved{background:var(--light-green);color:var(--teal);}
.stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;}
.stat-icon.teal{background:var(--light-green);}
.stat-icon.violet{background:var(--violet-bg);}
.stat-icon.green{background:var(--green-bg);}
.stat-icon.amber{background:#fff8e1;}
.stat-num{font-family:'Fraunces',serif;font-size:32px;font-weight:700;color:var(--ink);line-height:1;}
.stat-lbl{font-size:12px;color:var(--slate);margin-top:3px;}

/* ── SECTION HEADER ── */
.sec-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.sec-title{display:flex;align-items:center;gap:7px;font-size:15px;font-weight:700;color:var(--ink);}
.sec-title svg{color:var(--amber);}
.sec-sub{font-size:11.5px;color:var(--slate);margin-top:1px;}
.see-all{font-size:12.5px;color:var(--teal);font-weight:600;cursor:pointer;display:flex;align-items:center;gap:3px;text-decoration:none;}
.see-all:hover{text-decoration:underline;}

/* ── AI MATCH CARDS ── */
.ai-section{
  background:#fff; /* or any color you want */
  border:1.5px solid var(--mist);
  border-radius:16px;
  padding:18px 20px 2px;
}
.ai-scroll{display:grid;grid-template-columns:repeat(5,minmax(210px,1fr));gap:12px;margin-bottom:28px;overflow-x:auto;}
.ai-scroll::-webkit-scrollbar{height:3px;}
.ai-scroll::-webkit-scrollbar-thumb{background:var(--mist);}
.match-card{background:#fff;border:1.5px solid var(--mist);border-radius:14px;padding:16px;display:flex;flex-direction:column;gap:0;flex-shrink:0;transition:box-shadow .15s;}
.match-card:hover{transform:translateY(-7px);box-shadow:0 16px 36px rgba(15,76,92,0.13);}
.match-card.top{border-color:var(--amber);border-width:2px;}
.mc-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;}
.mc-cat{font-size:9.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--slate);}
.mc-fit{font-size:9.5px;font-weight:700;padding:2px 7px;border-radius:20px;}
.mc-fit.top{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-fit.great{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-fit.good{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-fit.explore{background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#FFFF;}
.mc-name{font-size:14px;font-weight:700;color:var(--ink);line-height:1.3;margin-bottom:3px;}
.mc-org{font-size:11px;color:var(--slate);margin-bottom:10px;}
.mc-amt{font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--teal);margin-bottom:2px;}
.mc-amt-unit{font-family:'DM Sans',sans-serif;font-size:11px;font-weight:500;color:var(--slate);}
.mc-score-lbl{font-size:10.5px;color:var(--slate);margin-bottom:4px;margin-top:10px;}
.mc-bar-row{display:flex;align-items:center;gap:8px;margin-bottom:12px;position:relative;}
.mc-bar{flex:1;height:5px;background:var(--cloud);border-radius:20px;overflow:hidden;position:relative;}
.mc-fill{height:100%;border-radius:20px;background:#0F4C5C;}
.mc-fill.gold{background: linear-gradient(135deg, #E8A838, #F9D679);}
.mc-pct{position:absolute;top: -20px;right:0;font-size:12.5px;font-weight:700;color:var(--teal);}
.mc-btn-row{display:flex;gap:6px;margin-top:auto;}
.btn-apply-full{flex:1;background:linear-gradient(135deg, #E8A838, #F9D679);color:var(--teal);font-family:'DM Sans',sans-serif;font-size:12px;font-weight:700;border:none;border-radius:8px;padding:8px 0;cursor:pointer;transition:background .12s;}
.btn-apply-full:hover{background:#F9D679;}
.btn-view{flex:1;background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#F9D679;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:600;border:1.5px solid var(--mist);border-radius:8px;padding:8px 0;cursor:pointer;transition:all .12s;}
.btn-view:hover{border-color:var(--teal);}

/* ── BOTTOM GRID ── */
.bottom-grid{display:grid;grid-template-columns:1fr 260px;gap:18px;}

/* ── ACTIVE APPLICATIONS ── */
.panel{background:#fff;border:1.5px solid var(--mist);border-radius:16px;padding:20px;}
.app-item{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--cloud);}
.app-item:last-child{border-bottom:none;padding-bottom:0;}
.app-avatar{width:38px;height:38px;border:1px solid var(--mist);border-radius:10px;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',sans-serif;font-size:12px;font-weight:700;background: #F0FAFA;color:var(--teal);flex-shrink:0;}
.app-info{flex:1;min-width:0;}
.app-name{font-size:13.5px;font-weight:600;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.app-org{font-size:11px;color:var(--slate);margin-top:1px;}
.app-progress{display:flex;gap:4px;margin-top:6px;}
.ap-dot{width:18px;height:4px;border-radius:20px;}
.ap-dot.done{background:var(--teal);}
.ap-dot.current{background:var(--amber);}
.ap-dot.empty{background:var(--mist);}
.app-status{flex-shrink:0;}
.app-status-tag{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:20px;}
.app-status-tag.review{background:var(--warn-bg);color:var(--warn-text);}
.app-status-tag.docs{background:#fee2e2;color:#b91c1c;}
.app-status-tag.approved{background:var(--green-bg);color:var(--green-text);}

/* ── DEADLINES ── */
.deadline-item{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--cloud);}
.deadline-item:last-child{border-bottom:none;padding-bottom:0;}
.dl-cal{width:46px;height:52px;border-radius:12px;flex-shrink:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1px;}
.dl-cal.urgent{background:#fff3e0;border:1.5px solid #f6c36a;}
.dl-cal.normal{background:#eaf7f7;border:1.5px solid #b5dfe0;}
.dl-day{font-family:'Fraunces',serif;font-size:22px;font-weight:700;line-height:1;}
.dl-cal.urgent .dl-day{color:#b36b00;}
.dl-cal.normal .dl-day{color:#0F4C5C;}
.dl-month{font-size:9px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;line-height:1;}
.dl-cal.urgent .dl-month{color:#c47f17;}
.dl-cal.normal .dl-month{color:#2A8FA0;}
.dl-info{flex:1;min-width:0;}
.dl-name{font-size:13px;font-weight:600;color:var(--ink);line-height:1.3;}
.dl-left{font-size:11.5px;margin-top:3px;font-weight:500;display:flex;align-items:center;gap:4px;}
.dl-left.urgent{color:#c47f17;}
.dl-left.soon{color:var(--warn-text);}
.dl-left.ok{color:var(--teal);}

/* ── QUICK ACTIONS ── */
.qa-section{background:#fff;border:1.5px solid var(--mist);border-radius:16px;padding:10px 20px 20px;}
.qa-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px;}
.qa-card{background:#fff;border:1.5px solid var(--mist);border-radius:12px;padding:16px;cursor:pointer;transition:all .15s;display:flex;flex-direction:column;gap:8px;}
.qa-card:hover{border-color:var(--teal);box-shadow:0 2px 12px rgba(15,76,92,0.08);}
.qa-icon{width:34px;height:34px;border-radius:9px;background:var(--light-green);display:flex;align-items:center;justify-content:center;color:var(--teal);}
.qa-label{font-size:13px;font-weight:600;color:var(--ink);}
.qa-hint{font-size:11px;color:var(--slate);}

/* ── NOTIFICATIONS ── */
.notif-item{display:flex;align-items:flex-start;gap:9px;padding:9px 0;border-bottom:1px solid var(--cloud);font-size:12px;}
.notif-item:last-child{border-bottom:none;}
.notif-dot{width:7px;height:7px;border-radius:50%;background:var(--teal);flex-shrink:0;margin-top:4px;}
.notif-dot.amber{background:var(--amber);}
.notif-dot.red{background:#e53e3e;}
.notif-txt{color:var(--ink);line-height:1.45;}
.notif-time{font-size:10.5px;color:var(--slate);margin-top:2px;}

/* ── SECTION SPACING ── */
.section{margin-bottom:24px;}
</style>

<button class="fab-ai" title="AI Assistant">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  <span class="fab-badge">AI</span>
</button>

<style>
.fab-ai{
  position:fixed;
  bottom:24px;right:24px;
  width:54px;height:54px;
  border-radius:50%;
  background:linear-gradient(160deg,#0F4C5C,#2A8FA0);
  border:none;
  color:#fff;
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;
  box-shadow:0 6px 24px rgba(15,76,92,0.35);
  z-index:400;
  transition:transform .2s ease, box-shadow .2s ease;
  position:fixed;
}
.fab-ai:hover{transform:scale(1.08);box-shadow:0 10px 32px rgba(15,76,92,0.45);}
.fab-badge{
  position:absolute;
  top:2px;right:2px;
  background:var(--amber);
  color:#fff;
  font-family:'DM Sans',sans-serif;
  font-size:9px;font-weight:800;
  border-radius:20px;
  padding:2px 5px;
  border:2px solid #fff;
  line-height:1;
}
</style>
</head>
<body>

<!-- NAVBAR (from browse page) -->
<nav class="navbar">
  <a class="nav-logo" href="#">
    <div class="logo-box">🎓</div>
    <span class="logo-text">ScholarLink</span>
  </a>
  <div class="nav-search">
    <span class="si"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
    <input type="text" placeholder="Search scholarships, requirements…">
  </div>
  <div class="nav-right">
    <button class="nav-ibtn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
    </button>
    <button class="nav-ibtn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <span class="nbadge"></span>
    </button>
    <div class="nav-av">{{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</div>
  </div>
</nav>

<!-- APP LAYOUT -->
<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-section-label">Main</div>
    <a class="sb-nav-item active" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
    <a class="sb-nav-item" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Browse Scholarships
    </a>
    <a class="sb-nav-item" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      My Applications
      <span class="sb-badge">3</span>
    </a>
    <div class="sb-section-label">Resources</div>
    <a class="sb-nav-item" href="{{ route('documents.index') }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      Document Wallet
    </a>
    <a class="sb-nav-item" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      Saved
      <span class="sb-badge teal">5</span>
    </a>
    <a class="sb-nav-item" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      AI Recommendations
    </a>
    <div class="sb-section-label">Account</div>
    <a class="sb-nav-item" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      My Profile
    </a>
    <a class="sb-nav-item" href="#">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      Notifications
      <span class="sb-badge">2</span>
    </a>
    <div class="sb-spacer"></div>
    <div class="sb-user">
      <div class="sb-av">{{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</div>
      <div>
        <div class="sb-name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="sb-sub">Applicant · {{ $profile->university_name ?? 'No university' }}</div>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">

    <!-- HERO -->
    <div class="hero section">
      <div class="hero-left">
        <div class="hero-eyebrow">Good Afternoon</div>
        <div class="hero-name">Kamusta, <em>{{ $user->first_name }}!</em></div>
        <div class="hero-sub">You have {{ $upcomingDeadlines->count() }} deadlines this week and {{ $stats['ai_matched'] }} new AI-matched scholarships ready.</div>
        <button class="btn-ai">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          View AI Recommendations
        </button>
      </div>
      <div class="hero-right">
        <div class="hero-right-label">Profile Completeness</div>
        <div class="progress-ring">
          <svg width="80" height="80" viewBox="0 0 80 80">
            <circle class="ring-bg" cx="40" cy="40" r="33"/>
            <circle class="ring-fill" cx="40" cy="40" r="33"/>
          </svg>
          <div class="ring-label">
            <span class="ring-pct">{{ $profileCompleteness }}%</span>
          </div>
        </div>
        <div class="hero-right-hint">Complete for better matches</div>
      </div>
    </div>

    <!-- STATS -->
    <div class="stat-row section">
      <div class="stat-card">
        <span class="stat-badge active">Active</span>
        <div class="stat-icon teal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0F4C5C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="stat-num">{{ $stats['active_applications'] }}</div>
        <div class="stat-lbl">Active Applications</div>
      </div>
      <div class="stat-card">
        <span class="stat-badge ai">AI</span>
        <div class="stat-icon violet">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="stat-num">{{ $stats['ai_matched'] }}</div>
        <div class="stat-lbl">AI-Matched Scholarships</div>
      </div>
      <div class="stat-card">
        <span class="stat-badge won">Won</span>
        <div class="stat-icon green">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="stat-num">{{ $stats['awarded'] }}</div>
        <div class="stat-lbl">Scholarships Awarded</div>
      </div>
      <div class="stat-card">
        <span class="stat-badge saved">Saved</span>
        <div class="stat-icon amber">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C9A84C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <div class="stat-num">{{ $stats['saved'] }}</div>
        <div class="stat-lbl">Saved Scholarships</div>
      </div>
    </div>

    <!-- AI MATCHED -->
    <div class="section ai-section">
      <div class="sec-hd">
        <div class="sec-title-wrap">
          <div class="sec-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            AI-Matched for You
          </div>
          <div class="sec-sub">Based on your GPA, income bracket &amp; course — updated today.</div>
        </div>
        <a class="see-all" href="#">See all {{ $stats['ai_matched'] }} →</a>
      </div>
      <div class="ai-scroll">

        @forelse($recommendedScholarships as $index => $application)
        @php
            $scholarship = $application->scholarship ?? $application;
            $matchScore = $application->match_score ?? $application->ai_match_score ?? null;
            $isTop = $index === 0 && $matchScore && $matchScore >= 90;
            $fitLabel = $matchScore >= 90 ? 'Top Match' : ($matchScore >= 80 ? 'Great Fit' : ($matchScore >= 70 ? 'Good Fit' : 'Explore'));
            $benefitAmount = $scholarship->benefit_snippet_1 ? (int) preg_replace('/[^0-9]/', '', $scholarship->benefit_snippet_1) : 0;
        @endphp
        <div class="match-card {{ $isTop ? 'top' : '' }}">
          <div class="mc-top"><span class="mc-cat">{{ $scholarship->provider_name ?? 'Scholarship' }}</span><span class="mc-fit {{ $isTop ? 'top' : ($matchScore >= 80 ? 'great' : ($matchScore >= 70 ? 'good' : 'explore')) }}">{{ $fitLabel }}</span></div>
          <div class="mc-name">{{ $scholarship->name }}</div>
          <div class="mc-org">{{ $scholarship->tagline ?? '' }}</div>
          <div class="mc-amt">₱{{ number_format($benefitAmount) }} <span class="mc-amt-unit">/ year</span></div>
          @if($matchScore)
          <div class="mc-score-lbl">Match Score</div>
          <div class="mc-bar-row"><div class="mc-bar"><div class="mc-fill {{ $isTop ? 'gold' : '' }}" style="width:{{ $matchScore }}%"></div></div><span class="mc-pct">{{ round($matchScore) }}%</span></div>
          @endif
          <div class="mc-btn-row"><button class="btn-apply-full">Apply Now</button></div>
        </div>
        @empty
        <div class="match-card">
          <div class="mc-top"><span class="mc-cat">No matches</span></div>
          <div class="mc-name">Complete your profile</div>
          <div class="mc-org">Add your GPA and income details to get personalized scholarship recommendations.</div>
          <div class="mc-btn-row"><button class="btn-view">Set Up Profile</button></div>
        </div>
        @endforelse

      </div>
    </div>

    <!-- BOTTOM GRID -->
    <div class="bottom-grid">

      <!-- LEFT: Active Applications -->
      <div>
        <div class="panel section">
          <div class="sec-hd" style="margin-bottom:10px;">
            <div class="sec-title">Active Applications</div>
            <a class="see-all" href="#">View all →</a>
          </div>

          @forelse($activeApplications as $application)
          @php
              $scholarship = $application->scholarship;
              $initials = strtoupper(substr($scholarship->name ?? 'S', 0, 2));
              $stage = $application->stage;
              $status = $application->status;
              $stages = ['submitted' => 1, 'doc_review' => 2, 'scoring' => 3, 'interview' => 4, 'decided' => 5];
              $currentStage = $stages[$stage] ?? 1;
          @endphp
          <div class="app-item">
            <div class="app-avatar" style="background:#F0FAFA;">{{ $initials }}</div>
            <div class="app-info">
              <div class="app-name">{{ $scholarship->name ?? 'Scholarship' }}</div>
              <div class="app-org">{{ $scholarship->provider_name ?? '' }}</div>
              <div class="app-progress">
                @for($i = 1; $i <= 5; $i++)
                <div class="ap-dot {{ $i < $currentStage ? 'done' : ($i == $currentStage ? 'current' : 'empty') }}"></div>
                @endfor
              </div>
            </div>
            <div class="app-status">
              @if($status === 'approved')
              <span class="app-status-tag approved">Approved! 🎉</span>
              @elseif($status === 'under_review')
              <span class="app-status-tag review">Under Review</span>
              @elseif($status === 'rejected')
              <span class="app-status-tag docs">Rejected</span>
              @else
              <span class="app-status-tag review">Pending</span>
              @endif
            </div>
          </div>
          @empty
          <div class="app-item">
            <div class="app-info">
              <div class="app-name">No active applications</div>
              <div class="app-org">Browse scholarships to start your application journey.</div>
            </div>
          </div>
          @endforelse
        </div>

        <!-- Quick Actions -->
        <div class="section qa-section">
            <div class="sec-hd">
            <div class="sec-title">Quick Actions</div>
            </div>
            <div class="qa-row">
            <div class="qa-card">
              <div class="qa-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              </div>
              <div class="qa-label">Browse All</div>
              <div class="qa-hint">Find scholarships</div>
            </div>
            <div class="qa-card">
              <div class="qa-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              </div>
              <div class="qa-label">Upload Docs</div>
              <div class="qa-hint">Add to wallet</div>
            </div>
          </div>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:18px;">

        <!-- Deadlines -->
        <div class="panel">
          <div class="sec-hd" style="margin-bottom:10px;">
            <div class="sec-title">Upcoming Deadlines</div>
            <a class="see-all" href="#">Calendar →</a>
          </div>

          @forelse($upcomingDeadlines as $application)
          @php
              $scholarship = $application->scholarship;
              $deadline = $scholarship->deadline ?? null;
              $daysLeft = $deadline ? now()->diffInDays(\Carbon\Carbon::parse($deadline), false) : null;
              $isUrgent = $daysLeft !== null && $daysLeft <= 3;
              $isSoon = $daysLeft !== null && $daysLeft <= 7;
          @endphp
          <div class="deadline-item">
            <div class="dl-cal {{ $isUrgent ? 'urgent' : 'normal' }}">
              <div class="dl-day">{{ $deadline ? \Carbon\Carbon::parse($deadline)->format('d') : '--' }}</div>
              <div class="dl-month">{{ $deadline ? \Carbon\Carbon::parse($deadline)->format('M') : '' }}</div>
            </div>
            <div class="dl-info">
              <div class="dl-name">{{ $scholarship->name ?? 'Scholarship' }}</div>
              <div class="dl-left {{ $isUrgent ? 'urgent' : ($isSoon ? 'soon' : 'ok') }}">
                @if($daysLeft !== null)
                @if($daysLeft <= 3)
                ⚠ {{ $daysLeft }} days left — submit docs!
                @else
                {{ $daysLeft }} days left
                @endif
                @else
                Deadline not set
                @endif
              </div>
            </div>
          </div>
          @empty
          <div class="deadline-item">
            <div class="dl-info">
              <div class="dl-name">No upcoming deadlines</div>
              <div class="dl-left ok">All caught up!</div>
            </div>
          </div>
          @endforelse
        </div>

        <!-- Notifications -->
        <div class="panel">
          <div class="sec-hd" style="margin-bottom:10px;">
            <div class="sec-title">Notifications</div>
            <a class="see-all" href="#">View all →</a>
          </div>

          @forelse($notifications as $notif)
          @php
              $type = $notif->type ?? 'info';
              $dotClass = $type === 'success' ? 'amber' : ($type === 'error' ? 'red' : '');
          @endphp
          <div class="notif-item">
            <div class="notif-dot {{ $dotClass }}"></div>
            <div>
              <div class="notif-txt">{!! $notif->body !!}</div>
              <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
          </div>
          @empty
          <div class="notif-item">
            <div class="notif-dot"></div>
            <div>
              <div class="notif-txt">No new notifications</div>
              <div class="notif-time">You're all caught up!</div>
            </div>
          </div>
          @endforelse
        </div>

      </div>
    </div>

  </main>
</div>

<!-- Session Timeout Modal -->
<div id="session-timeout-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 400px; width: 90%; box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15); animation: slideUp 0.3s ease-out;">
        <div style="text-align: center; margin-bottom: 20px; font-size: 48px;">⏱️</div>
        <h2 style="font-size: 20px; font-weight: 700; color: #1a2e2c; margin-bottom: 12px; text-align: center;">Session Expiring Soon</h2>
        <p style="font-size: 14px; color: #4a6460; line-height: 1.6; margin-bottom: 24px; text-align: center;">You've been inactive for 13 minutes. Your session will expire in 2 minutes for your security. Please click "Stay Logged In" to continue.</p>
        <div style="display: flex; gap: 12px;">
            <button onclick="if (window.sessionTracker) window.sessionTracker.stayLoggedIn(); else location.reload();" style="flex: 1; padding: 12px 16px; background: linear-gradient(135deg, #0F4C5C, #1A6B7A); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Stay Logged In</button>
            <button onclick="if (window.sessionTracker) window.sessionTracker.logout(); else document.getElementById('auto-logout-form').submit();" style="flex: 1; padding: 12px 16px; background: #f5f5f5; color: #1a2e2c; border: 1px solid #e2e8e6; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Log Out</button>
        </div>
        <div style="text-align: center; margin-top: 16px; font-size: 12px; color: #8aaba6;">Automatically logging out in <span id="session-timeout-countdown">2:00</span></div>
    </div>
</div>

<!-- Auto Logout Form -->
<form id="auto-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    /**
     * Session Timeout Tracker
     */
    document.addEventListener('alpine:init', () => {
        Alpine.data('sessionTracker', () => ({
            idleSeconds: 0,
            warningLimit: 13 * 60,
            timeoutLimit: 15 * 60,
            interval: null,
            isWarningShown: false,

            init() {
                this.resetIdleTime();
                const events = ['mousemove', 'keydown', 'scroll', 'click', 'touchstart'];
                events.forEach(event => {
                    window.addEventListener(event, () => this.resetIdleTime(), true);
                });

                this.interval = setInterval(() => {
                    this.idleSeconds++;
                    if (this.idleSeconds === this.warningLimit && !this.isWarningShown) {
                        this.isWarningShown = true;
                        const modal = document.getElementById('session-timeout-modal');
                        if (modal) modal.style.display = 'flex';
                    }
                    if (this.idleSeconds >= this.timeoutLimit) {
                        clearInterval(this.interval);
                        document.getElementById('auto-logout-form').submit();
                    }
                }, 1000);
            },

            resetIdleTime() {
                this.idleSeconds = 0;
                this.isWarningShown = false;
                const modal = document.getElementById('session-timeout-modal');
                if (modal) modal.style.display = 'none';
            },

            logout() {
                document.getElementById('auto-logout-form').submit();
            },

            stayLoggedIn() {
                this.resetIdleTime();
                const modal = document.getElementById('session-timeout-modal');
                if (modal) modal.style.display = 'none';
            }
        }));
    });

    // Make tracker globally available
    window.sessionTracker = null;
    document.addEventListener('alpine:init', () => {
        const root = document.querySelector('[x-data]');
        if (root && root.__x && root.__x.$data) {
            window.sessionTracker = root.__x.$data;
        }
    });

    // Countdown timer
    setInterval(() => {
        const modal = document.getElementById('session-timeout-modal');
        if (modal && modal.style.display !== 'none') {
            const countdownEl = document.getElementById('session-timeout-countdown');
            const text = countdownEl.textContent;
            const [minutes, seconds] = text.split(':').map(Number);
            let totalSeconds = minutes * 60 + seconds - 1;
            if (totalSeconds < 0) totalSeconds = 0;
            const newMinutes = Math.floor(totalSeconds / 60);
            const newSeconds = totalSeconds % 60;
            countdownEl.textContent = `${newMinutes}:${newSeconds.toString().padStart(2, '0')}`;
        }
    }, 1000);
</script>

</body>
</html>

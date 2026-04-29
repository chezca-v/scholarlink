<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ScholarLink — Evaluator Pages</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --primary: #0F4C5C;
    --primary-hover: #1A6B7A;
    --primary-light: #2A8FA0;
    --accent: #E8A838;
    --accent-light: #F9D679;
    --accent-pale: #FDF4E3;
    --page-bg: #F0FAFA;
    --card-bg: #FFFFFF;
    --border: #DFF0EE;
    --border-mid: #C8E8E4;
    --ink: #0A3040;
    --slate: #4A7A80;
    --muted: #7AACAA;
    --sidebar-w: 220px;
    --nav-h: 60px;
    --red: #DC2626;
    --red-bg: #FEF2F2;
    --green: #059669;
    --green-bg: #ECFDF5;
    --yellow: #D97706;
    --yellow-bg: #FFFBEB;
    --eval-nav-bg: #F0FAFA;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'DM Sans', sans-serif; background: var(--page-bg); color: var(--ink); font-size: 14px; line-height: 1.5; }

  /* ─── SIDEBAR ──────────────────────────────────────── */
  .sidebar {
    width: var(--sidebar-w); background: white;
    border-right: 1.5px solid var(--border);
    position: fixed; top: 0; left: 0; bottom: 0;
    overflow-y: auto; display: flex; flex-direction: column; z-index: 100;
  }
  .sidebar-logo { padding: 20px 16px 16px; border-bottom: 1px solid var(--border); }
  .sidebar-logo .brand-row { display:flex; align-items:center; gap:10px; }
  .sidebar-logo .brand-logo { width:34px; height:34px; object-fit:contain; filter:drop-shadow(0 4px 10px rgba(15,76,92,.18)); }
  .sidebar-logo .wordmark { font-family: 'Fraunces', serif; font-weight: 900; font-size: 18px; color: var(--primary); letter-spacing: -.3px; }
  .sidebar-logo .wordmark span { color: var(--accent); }
  .sidebar-logo .role-badge { display: inline-block; margin-top: 6px; padding: 2px 8px; background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 20px; font-size: 10px; font-weight: 600; color: #1D4ED8; text-transform: uppercase; letter-spacing: .5px; }
  .sidebar-section { padding: 14px 16px 4px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); }
  .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 9px 16px; color: var(--slate); font-size: 13px; font-weight: 400; cursor: pointer; border-left: 3px solid transparent; transition: all .15s; text-decoration: none; }
  .sidebar-link:hover { background: var(--page-bg); color: var(--ink); }
  .sidebar-link.active { background: rgba(15,76,92,.06); color: var(--primary); border-left-color: var(--primary); font-weight: 600; }
  .sidebar-link .icon { width: 18px; text-align: center; font-size: 15px; flex-shrink: 0; }
  .sidebar-link .badge { margin-left: auto; background: var(--primary); color: white; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px; min-width: 18px; text-align: center; }
  .sidebar-link .badge.amber { background: var(--accent); color: var(--ink); }
  .sidebar-footer { margin-top: auto; padding: 14px 16px; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
  .sidebar-footer .avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(15,76,92,.1); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: var(--primary); flex-shrink: 0; }
  .sidebar-footer .name { font-size: 12px; color: var(--ink); font-weight: 500; }
  .sidebar-footer .sub { font-size: 10px; color: var(--muted); }

  /* ─── TOPNAV ───────────────────────────────────────── */
  .topnav {
    position: fixed; top: 0; left: var(--sidebar-w); right: 0; height: var(--nav-h);
    background: var(--eval-nav-bg); display: flex; align-items: center; padding: 0 24px; gap: 12px;
    z-index: 99; border-bottom: 1.5px solid var(--border);
  }
  .topnav .page-title { font-family: 'Fraunces', serif; font-weight: 700; font-size: 22px; color: var(--ink); flex: 1; }
  .role-pill { padding: 4px 12px; border-radius: 20px; background: #EFF6FF; border: 1.5px solid #BFDBFE; font-size: 11px; font-weight: 600; color: #1D4ED8; }
  .icon-btn { width: 36px; height: 36px; border-radius: 10px; background: white; border: 1.5px solid var(--border-mid); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--slate); font-size: 16px; transition: all .15s; position: relative; }
  .icon-btn:hover { border-color: var(--primary-light); color: var(--primary); }
  .icon-btn .notif-dot { position: absolute; top: 6px; right: 6px; width: 8px; height: 8px; background: var(--accent); border-radius: 50%; border: 1.5px solid var(--eval-nav-bg); }
  .topnav-avatar { width: 36px; height: 36px; border-radius: 50%; background: rgba(15,76,92,.1); border: 2px solid var(--border-mid); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: var(--primary); cursor: pointer; }

  /* ─── MAIN ─────────────────────────────────────────── */
  .main { margin-left: var(--sidebar-w); padding-top: var(--nav-h); min-height: 100vh; }
  .content { padding: 28px; }

  /* ─── CARDS ────────────────────────────────────────── */
  .card { background: white; border: 1.5px solid var(--border); border-radius: 20px; padding: 20px; }
  .card.compact { border-radius: 16px; padding: 16px; }
  .card:hover { border-color: var(--border-mid); }

  /* ─── STAT GRID ────────────────────────────────────── */
  .stat-grid { display: grid; gap: 16px; margin-bottom: 24px; }
  .stat-card { background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 18px 20px; transition: all .2s; }
  .stat-card:hover { border-color: var(--border-mid); box-shadow: 0 4px 16px rgba(15,76,92,.07); }
  .stat-card .label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); margin-bottom: 6px; }
  .stat-card .value { font-family: 'Fraunces', serif; font-size: 28px; font-weight: 700; color: var(--ink); line-height: 1; margin-bottom: 4px; }
  .stat-card .delta { font-size: 12px; font-weight: 500; }
  .delta.up { color: var(--green); } .delta.down { color: var(--red); } .delta.neutral { color: var(--slate); }

  /* ─── SECTION HEADER ───────────────────────────────── */
  .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
  .section-title { font-family: 'Fraunces', serif; font-size: 16px; font-weight: 700; color: var(--ink); }
  .section-title small { font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 400; color: var(--slate); margin-left: 6px; }

  /* ─── BADGES ───────────────────────────────────────── */
  .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
  .badge.green { background: var(--green-bg); color: var(--green); }
  .badge.red { background: var(--red-bg); color: var(--red); }
  .badge.yellow { background: var(--yellow-bg); color: var(--yellow); }
  .badge.teal { background: rgba(15,76,92,.08); color: var(--primary); }
  .badge.amber { background: var(--accent-pale); color: #92650a; }
  .badge.gray { background: #F3F4F6; color: #6B7280; }
  .badge.blue { background: #EFF6FF; color: #1D4ED8; }
  .badge.priority-high { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
  .badge.priority-med { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
  .badge.priority-low { background: #F0FDF4; color: #059669; border: 1px solid #A7F3D0; }

  /* ─── BUTTONS ──────────────────────────────────────── */
  .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: all .15s; text-decoration: none; }
  .btn-primary { background: linear-gradient(135deg, #0F4C5C, #1A6B7A); color: white; box-shadow: 0 2px 8px rgba(15,76,92,.25); }
  .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(15,76,92,.3); }
  .btn-amber { background: linear-gradient(135deg, #E8A838, #F9C745); color: #5a3a00; box-shadow: 0 2px 8px rgba(232,168,56,.25); }
  .btn-green { background: linear-gradient(135deg, #059669, #10B981); color: white; box-shadow: 0 2px 8px rgba(5,150,105,.25); }
  .btn-green:hover { transform: translateY(-1px); }
  .btn-outline { background: white; border: 1.5px solid var(--border-mid); color: var(--slate); }
  .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
  .btn-ghost { background: transparent; color: var(--slate); padding: 6px 10px; }
  .btn-ghost:hover { background: var(--page-bg); color: var(--ink); }
  .btn-danger { background: var(--red-bg); color: var(--red); border: 1.5px solid #FECACA; }
  .btn-danger:hover { background: #FECACA; }
  .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 8px; }
  .btn-lg { padding: 12px 28px; font-size: 15px; border-radius: 12px; }

  /* ─── FORM ─────────────────────────────────────────── */
  .form-group { margin-bottom: 16px; }
  .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
  .form-label .req { color: var(--red); }
  .form-input, .form-select, .form-textarea { width: 100%; padding: 9px 12px; border: 1.5px solid var(--border-mid); border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 13px; color: var(--ink); background: white; outline: none; transition: border-color .15s; }
  .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(42,143,160,.12); }
  .form-textarea { resize: vertical; min-height: 100px; }

  /* ─── TABLE ────────────────────────────────────────── */
  .table-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; }
  thead th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); border-bottom: 1.5px solid var(--border); white-space: nowrap; cursor: pointer; user-select: none; }
  thead th:hover { color: var(--primary); }
  thead th.sorted { color: var(--primary); }
  thead th.sorted::after { content: ' ↓'; }
  tbody td { padding: 12px 14px; border-bottom: 1px solid var(--border); font-size: 13px; color: var(--ink); }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover td { background: var(--page-bg); }

  /* ─── PAGINATION ───────────────────────────────────── */
  .pagination { display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid var(--border); margin-top: 4px; }
  .pagination .info { font-size: 12px; color: var(--slate); }
  .page-btns { display: flex; gap: 4px; }
  .page-btn { width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid var(--border-mid); background: white; font-size: 12px; font-weight: 500; color: var(--slate); cursor: pointer; display: flex; align-items: center; justify-content: center; }
  .page-btn.active { background: var(--primary); border-color: var(--primary); color: white; }
  .page-btn:hover:not(.active) { border-color: var(--primary); color: var(--primary); }

  /* ─── SEARCH ───────────────────────────────────────── */
  .search-wrap { position: relative; }
  .search-wrap .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; }
  .search-wrap .form-input { padding-left: 34px; }

  /* ─── BREADCRUMB ────────────────────────────────────── */
  .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--muted); margin-bottom: 20px; }
  .breadcrumb .sep { color: var(--border-mid); }
  .breadcrumb .current { color: var(--ink); font-weight: 500; }

  /* ─── GRID ─────────────────────────────────────────── */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

  /* ─── PROGRESS BAR ─────────────────────────────────── */
  .prog-bar-wrap { height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
  .prog-bar { height: 100%; border-radius: 4px; }
  .prog-bar.teal { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
  .prog-bar.amber { background: linear-gradient(90deg, var(--accent), var(--accent-light)); }
  .prog-bar.red { background: linear-gradient(90deg, #DC2626, #F87171); }
  .prog-bar.green { background: linear-gradient(90deg, #059669, #34D399); }

  /* ─── SCHOLARSHIP QUEUE CARD ────────────────────────── */
  .queue-card {
    background: white; border: 1.5px solid var(--border); border-radius: 16px; padding: 18px;
    display: flex; flex-direction: column; gap: 12px; transition: all .2s;
  }
  .queue-card:hover { border-color: var(--border-mid); box-shadow: 0 4px 16px rgba(15,76,92,.07); transform: translateY(-1px); }
  .queue-card .org-tag { font-size: 11px; color: var(--slate); font-weight: 500; }

  /* ─── BLIND PROFILE CARD ────────────────────────────── */
  .blind-card {
    background: linear-gradient(135deg, #0F4C5C, #1A6B7A);
    border-radius: 16px; padding: 20px; color: white;
    display: flex; align-items: center; gap: 16px;
  }
  .blind-avatar {
    width: 56px; height: 56px; border-radius: 50%;
    background: rgba(255,255,255,.15); border: 2px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
  }
  .blind-card .label-row { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: rgba(255,255,255,.5); margin-bottom: 4px; }
  .blind-card .field-val { font-size: 14px; font-weight: 600; color: rgba(255,255,255,.9); }
  .blind-card .masked { display: inline-block; background: rgba(255,255,255,.15); border-radius: 4px; padding: 1px 8px; font-style: italic; color: rgba(255,255,255,.4); font-size: 13px; }
  .blind-fields { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; flex: 1; }

  /* ─── DOC VIEWER ────────────────────────────────────── */
  .doc-item {
    display: flex; align-items: center; gap: 12px; padding: 12px;
    border: 1.5px solid var(--border); border-radius: 12px; margin-bottom: 8px;
    transition: all .15s; cursor: pointer;
  }
  .doc-item:hover { border-color: var(--border-mid); background: var(--page-bg); }
  .doc-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
  .doc-name { font-size: 13px; font-weight: 500; color: var(--ink); }
  .doc-meta { font-size: 11px; color: var(--slate); }
  .doc-verify { margin-left: auto; display: flex; gap: 6px; align-items: center; }

  /* ─── SCORE SLIDER ──────────────────────────────────── */
  .score-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); }
  .score-row:last-child { border-bottom: none; }
  .score-label { width: 130px; font-size: 13px; font-weight: 500; color: var(--ink); flex-shrink: 0; }
  .score-label small { display: block; font-size: 11px; font-weight: 400; color: var(--slate); }
  .score-slider-wrap { flex: 1; }
  input[type=range] { width: 100%; -webkit-appearance: none; height: 6px; border-radius: 4px; background: var(--border); outline: none; }
  input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; width: 18px; height: 18px; border-radius: 50%; background: var(--primary); cursor: pointer; border: 2px solid white; box-shadow: 0 2px 6px rgba(15,76,92,.3); }
  .score-val { width: 40px; text-align: right; font-size: 15px; font-weight: 700; color: var(--primary); flex-shrink: 0; }

  /* ─── OVERALL SCORE ──────────────────────────────────── */
  .overall-score-ring {
    width: 90px; height: 90px; border-radius: 50%;
    background: conic-gradient(var(--primary) 0% 78%, var(--border) 78% 100%);
    display: flex; align-items: center; justify-content: center;
    position: relative; flex-shrink: 0;
  }
  .overall-score-ring::after {
    content: '';
    position: absolute;
    inset: 10px;
    border-radius: 50%;
    background: white;
  }
  .overall-score-ring .ring-val {
    position: relative; z-index: 1;
    font-family: 'Fraunces', serif;
    font-size: 22px; font-weight: 900; color: var(--primary);
  }

  /* ─── DECISION BUTTONS ──────────────────────────────── */
  .decision-bar {
    display: flex; gap: 12px; align-items: center;
    background: white; border: 1.5px solid var(--border);
    border-radius: 16px; padding: 16px 20px;
    position: sticky; bottom: 20px;
    box-shadow: 0 8px 32px rgba(15,76,92,.12);
  }
  .decision-bar .help { flex: 1; font-size: 12px; color: var(--slate); }
  .decision-bar .help strong { color: var(--ink); display: block; margin-bottom: 2px; }

  /* ─── REJECT ALTERNATIVES ───────────────────────────── */
  .alt-option { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 10px; margin-bottom: 8px; cursor: pointer; transition: all .15s; }
  .alt-option:hover { border-color: var(--primary-light); background: var(--page-bg); }
  .alt-option input[type=checkbox] { accent-color: var(--primary); width: 15px; height: 15px; }
  .alt-option .alt-name { font-size: 13px; font-weight: 500; color: var(--ink); }
  .alt-option .alt-match { font-size: 11px; color: var(--slate); }

  /* ─── MODAL ─────────────────────────────────────────── */
  .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.35); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 1000; }
  .modal-overlay.open { display: flex; }
  .modal { background: white; border-radius: 20px; padding: 28px; width: 520px; max-width: 95vw; box-shadow: 0 24px 60px rgba(0,0,0,.2); }
  .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .modal-title { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: var(--ink); }
  .modal-close { background: none; border: none; font-size: 20px; color: var(--muted); cursor: pointer; }

  /* ─── DOC PREVIEW MODAL ─────────────────────────────── */
  .doc-preview-area {
    height: 340px;
    background: var(--page-bg);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 12px;
    color: var(--muted);
  }
  .doc-preview-area .big-icon { font-size: 48px; }
  .doc-preview-area .doc-preview-name { font-size: 14px; font-weight: 600; color: var(--slate); }
</style>
@stack('styles')
</head>
<body>

<div class="layout">
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="brand-row">
        <img src="{{ asset('logo-light.png.png') }}" alt="ScholarLink logo" class="brand-logo">
        <div class="wordmark">Scholar<span>Link</span></div>
      </div>
      <div class="role-badge">📋 Evaluator</div>
    </div>
    <div class="sidebar-section">Workspace</div>
    <a href="{{ route('evaluator.dashboard') ?? '#' }}" class="sidebar-link {{ request()->routeIs('evaluator.dashboard') ? 'active' : '' }}">
      <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('evaluator.queue') }}" class="sidebar-link {{ request()->routeIs('evaluator.queue') || request()->routeIs('evaluator.review.*') ? 'active' : '' }}">
      <span class="icon">📥</span> Review Queue
      <span class="badge amber">{{ \App\Models\Application::where('status', 'pending')->count() ?? 14 }}</span>
    </a>
    <a href="{{ route('evaluator.completed') }}" class="sidebar-link {{ request()->routeIs('evaluator.completed') ? 'active' : '' }}">
      <span class="icon">✅</span> Completed Reviews
    </a>
    
    <div class="sidebar-section">Account</div>
    <a href="{{ route('evaluator.notifications') }}" class="sidebar-link {{ request()->routeIs('evaluator.notifications') ? 'active' : '' }}">
      <span class="icon">🔔</span> Notifications 
      @php $unreadNotifs = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
      @if($unreadNotifs > 0)
        <span class="badge">{{ $unreadNotifs }}</span>
      @endif
    </a>
    <a href="{{ route('evaluator.profile') }}" class="sidebar-link {{ request()->routeIs('evaluator.profile') ? 'active' : '' }}">
      <span class="icon">👤</span> My Profile
    </a>
    
    <div class="sidebar-footer" style="margin-top: auto; padding: 14px 16px; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 10px;">
      <div class="avatar" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(15,76,92,.1); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: var(--primary); flex-shrink: 0;">
        {{ strtoupper(substr(auth()->user()->first_name ?? 'E', 0, 1) . substr(auth()->user()->last_name ?? 'C', 0, 1)) }}
      </div>
      <div>
        <div class="name" style="font-size: 12px; color: var(--ink); font-weight: 500;">
          {{ auth()->user()->first_name ?? 'Eva' }} {{ auth()->user()->last_name ?? 'Cordero' }}
        </div>
        <div class="sub" style="font-size: 10px; color: var(--muted); display:flex; align-items:center; gap:4px;">
          Evaluator
          <button type="button" style="background:none; border:none; color:var(--red); font-size:10px; cursor:pointer; font-family:inherit; padding:0; text-decoration:none; margin-left:4px;" title="Log Out" onclick="document.getElementById('logoutModal').classList.add('open')">
            Log Out
          </button>
        </div>
      </div>
    </div>
  </aside>

  <div class="main">
    <nav class="topnav">
      <div class="page-title">
        @yield('page_title', 'Evaluator Pages')
      </div>
      <div style="display:flex;align-items:center;gap:10px">
        @yield('topnav_actions')
        <span class="role-pill">📋 Evaluator</span>
        <a href="{{ route('evaluator.notifications') }}" class="icon-btn" style="text-decoration: none;">
          🔔
          @php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
          @if($unreadCount > 0)
            <span class="notif-dot"></span>
          @endif
        </a>
        <a href="{{ route('evaluator.profile') }}" class="topnav-avatar" style="text-decoration: none;">
          {{ strtoupper(substr(auth()->user()->first_name ?? 'E', 0, 1) . substr(auth()->user()->last_name ?? 'C', 0, 1)) }}
        </a>
      </div>
    </nav>

    <div class="content">
      @yield('content')
    </div>
  </div>
</div>

@stack('modals')

<div class="modal-overlay" id="logoutModal">
  <div class="modal" style="width: 400px; text-align: center; padding: 32px 28px 24px;">
    <div style="font-size:42px; margin-bottom:14px;">👋</div>
    <h2 class="modal-title" style="margin-bottom:8px; font-size:22px; color:var(--primary);">Log out of ScholarLink?</h2>
    <p style="font-size:13px; color:var(--slate); margin-bottom:24px;">
        You'll need to sign in again to access your evaluator dashboard.
    </p>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-outline" style="flex:1; justify-content:center; background:#F4F6FA; border:1.5px solid #E2E8F0; color:#1C1C2E;" onclick="document.getElementById('logoutModal').classList.remove('open')">Cancel</button>
        <form method="POST" action="{{ route('logout') }}" style="flex:1; display:flex; margin:0;">
            @csrf
            <button type="submit" class="btn btn-danger" style="flex:1; justify-content:center; background:linear-gradient(135deg,#e53e3e,#c53030); color:white; border:none;">Yes, Log Out</button>
        </form>
    </div>
  </div>
</div>

<x-chatbot-widget />
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ScholarLink — Browse Scholarship Opportunities</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Fraunces:opsz,wght@9..144,700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --teal:#0F4C5C;
  --teal-hover:#0c3f4d;
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
}
body{font-family:'DM Sans',sans-serif;background:#F0FAFA;color:var(--ink);min-height:100vh;-webkit-font-smoothing:antialiased;}

.navbar{
  background:#FFFF;height:56px;
  display:flex;align-items:center;padding:0 22px;gap:14px;
  position:sticky;top:0;z-index:200;
  box-shadow:0 1px 4px rgba(0,0,0,0.18);
}
.nav-logo{display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0;}
.logo-box{
  width:32px;height:32px;
  background: #0F4C5C;
  color:#fff;
  font-size:18px;
  box-shadow:0 4px 12px rgba(0,0,0,0.12);
  border:1.5px solid rgba(255,255,255,0.25);
  border-radius:8px;
  display:flex;align-items:center;justify-content:center;
}
.logo-text{font-family:'Fraunces', serif;font-size:16px;font-weight:700;color:#0F4C5C;letter-spacing:-0.2px;}
.nav-search{flex:1;max-width:440px;margin:0 auto;position:relative;}
.nav-search input{
  width:100%;height:34px;
  background:var(--light-green);border:1px solid rgba(15, 76, 92, 0.10);
  border-radius:30px;padding:0 54px 0 34px;
  font-family:'DM Sans',sans-serif;font-size:13px;color:var(--teal);outline:none;
}
.nav-search input::placeholder{color:rgba(15, 76, 92, 0.48);}
.nav-search input:focus{background:rgba(15, 76, 92, 0);}
.si{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#0c3f4d;pointer-events:none;display:flex;}
.skb{position:absolute;right:8px;top:50%;transform:translateY(-50%);display:flex;gap:2px;pointer-events:none;}
.kb{background:rgba(255,255,255,0.14);border:1px solid rgba(15, 76, 92, 0.5);border-radius:3px;color:#0c3f4d;font-size:10px;font-weight:600;padding:0 4px;line-height:16px;}
.nav-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
.nav-ibtn{
  width: 35px;height:35px;
  border-radius:10px;
  background:var(--light-green);
  border:2px solid rgba(15, 76, 92, 0.12);
  display:flex;align-items:center;justify-content:center;
  cursor:pointer;position:relative;transition:all 0.2s ease;
}
.nav-ibtn:hover{background:rgba(15, 76, 92, 0.12);border-color:rgba(15, 76, 92, 0.25);}
.nbadge{position:absolute;top:5px;right:5px;width:8px;height:8px;border-radius:50%;background: #F9D679;border:1.5px solid var(--teal);}
.nav-av{width:34px;height:34px;border-radius:50%;background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid rgba(255,255,255,0.35);}

.layout{display:flex;align-items:stretch;}

.sidebar{
  width:256px;flex-shrink:0;
  background:#FFFF;
  padding:22px 18px 16px 22px;
  display:flex;flex-direction:column;gap:22px;
  position:sticky;top:56px;
  height:calc(100vh - 56px);
  overflow-y:auto;
}
.layout {
  display: flex;
  align-items: stretch;   /* <-- IMPORTANT */
}
.sidebar::-webkit-scrollbar{width:3px;}
.sidebar::-webkit-scrollbar-thumb{background:var(--mist);}

.sb-hd{display:flex;justify-content:space-between;align-items:center;}
.sb-title{font-size:16px;font-weight:700;color:var(--ink);}
.btn-clr{font-size:13px;color:var(--slate);background:none;border:none;cursor:pointer;}
.btn-clr:hover{color:var(--teal);}

.fg{display:flex;flex-direction:column;gap:10px;}
.fgl{font-size:10px;font-weight:700;letter-spacing:0.9px;text-transform:uppercase;color:var(--slate);}

.cbr{display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;}
.cbr input[type=checkbox]{width:15px;height:15px;accent-color:var(--teal);cursor:pointer;flex-shrink:0;}
.cbl{font-size:13.5px;color:var(--ink);flex:1;}
.cbc{font-size:12.5px;color:var(--slate);}

.sw{display:flex;flex-direction:column;gap:8px;}
.swr{display:flex;justify-content:space-between;font-size:12px;color:var(--slate);}
.swc{font-size:12px;font-weight:600;color:var(--ink);}
input[type=range]{width:100%;height:4px;-webkit-appearance:none;appearance:none;background:var(--mist);border-radius:4px;cursor:pointer;outline:none;}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:var(--teal);border:2.5px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,0.18);cursor:pointer;}

.pg{display:flex;flex-wrap:wrap;gap:7px;}
.pill{font-family:'DM Sans',sans-serif;font-size:12.5px;font-weight:500;padding:5px 13px;border-radius:20px;border:1.5px solid var(--mist);background:#fff;color:var(--ink);cursor:pointer;transition:all .12s;}
.pill.active{background:var(--teal);border-color:var(--teal);color:#fff;font-weight:600;}
.pill:not(.active):hover{border-color:var(--slate);}

.sb-user{margin-top:auto;display:flex;align-items:center;gap:10px;padding:12px 14px;background: var(--light-green); border: 2px solid rgba(15, 76, 92, 0.20);border-radius: 20px;}
.sb-av{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg, #0F4C5C, #1A6B7A);color: #F9D679;font-size:12.5px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-name{font-size:13px;font-weight:600;color:var(--ink);}
.sb-sub{font-size:11.5px;color:var(--slate);}

/* MAIN */
.main{flex:1;padding:24px 28px 32px;min-width:0;}
.eyebrow{font-size:11px;font-weight:600;letter-spacing:1.2px;text-transform:uppercase;color:var(--amber);margin-bottom:2px;}
.ptitle{font-family:'Fraunces',serif;font-size:32px;font-weight:700;color:var(--ink);letter-spacing:-0.5px;margin-bottom:18px;line-height:1.2;}

/* Active filter chips */
.chips-bar{display:flex;flex-wrap:wrap;align-items:center;gap:7px;margin-bottom:16px;}
.chips-lbl{font-size:13px;color:var(--ink);}
.chip{display:inline-flex;align-items:center;gap:4px;background:#fff;border:1.5px solid var(--mist);border-radius:20px;padding:3px 10px 3px 11px;font-size:12.5px;font-weight:500;color:var(--ink);cursor:pointer;}
.chip:hover{border-color:var(--slate);}
.cx{font-size:12px;color:var(--slate);line-height:1;}
.btn-clrchips{font-size:12.5px;color:var(--slate);background:none;border:none;cursor:pointer;padding:2px 4px;}
.btn-clrchips:hover{color:var(--teal);}

/* Results bar */
.rbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;}
.rtxt{font-size:13.5px;color:var(--slate);}
.rtxt strong{color:var(--ink);font-weight:600;}
.rright{display:flex;align-items:center;gap:8px;}
.sort-sel{font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:var(--ink);background:#fff;border:1.5px solid var(--mist);border-radius:8px;padding:6px 28px 6px 11px;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%238A95A3' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 8px center;background-color:#fff;}
.vtgl{display:flex;gap:3px;}
.vbtn{width:32px;height:32px;display:flex;align-items:center;justify-content:center;border:1.5px solid var(--mist);border-radius:7px;background:#fff;cursor:pointer;color:var(--slate);transition:all .12s;}
.vbtn.active{background:var(--teal);border-color:var(--teal);color:#fff;}

/* Cards */
.cgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:28px;}
.cgrid.lv{grid-template-columns:1fr;}

.card{background:#fff;border:1.5px solid var(--mist);border-radius:16px;padding:18px 18px 16px;display:flex;flex-direction:column;transition:box-shadow .15s;}
.card:hover{box-shadow:0 2px 14px rgba(0,0,0,0.07);}
.card.saved{border-color:var(--amber);border-width:2px;}

.card-skel{background:#fff;border:1.5px solid var(--mist);border-radius:16px;padding:18px 18px 16px;display:flex;flex-direction:column;gap:10px;}
.sk{background:var(--mist);border-radius:5px;animation:pulse 1.6s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:.45;}50%{opacity:.9;}}

.ctop{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;}
.corg{font-size:10.5px;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:var(--slate);}
.cbdg{display:flex;gap:5px;align-items:center;flex-wrap:wrap;justify-content:flex-end;}

.bdg{display:inline-flex;align-items:center;padding:2px 8px;font-size:10.5px;font-weight:700;line-height:1.6;white-space:nowrap;}
.bdg-new{background:var(--teal);color:#fff;border-radius:20px;}
.bdg-open{background:var(--green-bg);color:var(--green-text);border-radius:20px;}
.bdg-open::before{content:'';display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--green-text);margin-right:4px;}
.bdg-closing{background:var(--warn-bg);color:var(--warn-text);border-radius:5px;}
.bdg-coming{background:var(--violet-bg);color:var(--violet-text);border-radius:20px;}
.bdg-coming::before{content:'';display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--violet-text);margin-right:4px;}

.ctitle{font-size:15.5px;font-weight:700;color:var(--ink);line-height:1.35;margin-bottom:5px;letter-spacing:-0.2px;}
.cmeta{display:flex;align-items:center;gap:4px;font-size:11.5px;color:var(--slate);margin-bottom:10px;}
.ctags{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:14px;}
.tag{font-size:11.5px;font-weight:400;color:var(--ink);background:var(--cloud);border:1px solid var(--mist);padding:3px 10px;border-radius:5px;}
.cdiv{height:1px;background:var(--mist);margin-bottom:12px;}
.mlbl{font-size:11.5px;color:var(--slate);margin-bottom:5px;}
.mrow{display:flex;align-items:center;gap:9px;margin-bottom:13px;}
.btrack{flex:1;height:6px;background:var(--cloud);border-radius:20px;overflow:hidden;}
.bfill{height:100%;border-radius:20px;background: linear-gradient(90deg, #0F4C5C, #E8A838);}
.mpct{font-size:14px;font-weight:700;color:var(--amber);min-width:36px;text-align:right;}

.cact{display:flex;gap:7px;margin-top:auto;}
.btn-apply{flex:1;background: linear-gradient(160deg, #0F4C5C, #2A8FA0);color:var(--amber-light);font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;border:none;border-radius:8px;padding:9px 0;cursor:pointer;transition:background .12s;text-decoration:none;display:flex;align-items:center;justify-content:center;}
.btn-apply:hover{background: linear-gradient(135deg, #0F4C5C, #1A6B7A);}
.btn-bm{width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:var(--cloud);border:1.5px solid var(--mist);border-radius:8px;cursor:pointer;color:var(--slate);transition:all .12s;flex-shrink:0;}
.btn-bm:hover{border-color:var(--amber);color:var(--amber);}
.btn-bm.saved{background:#fff8e1;border-color:var(--amber);color:var(--amber);}

.no-res{grid-column:1/-1;text-align:center;padding:60px 24px;color:var(--slate);}
.no-res strong{display:block;font-size:15px;color:var(--ink);margin-bottom:6px;}

/* Pagination */
.pagi{display:flex;justify-content:center;align-items:center;gap:5px;padding-bottom:24px;}
.pbtn{min-width:34px;height:34px;display:flex;align-items:center;justify-content:center;border-radius:8px;border:1.5px solid var(--mist);background:#fff;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;color:var(--ink);cursor:pointer;transition:all .12s;padding:0 6px;text-decoration:none;}
.pbtn:hover{border-color:var(--teal);color:var(--teal);}
.pbtn.active{background:var(--teal);border-color:var(--teal);color:#fff;font-weight:700;}
.pbtn.disabled{opacity:.3;pointer-events:none;}
.psep{color:var(--slate);font-size:13px;padding:0 2px;}

/* List view overrides */
.cgrid.lv .card { flex-direction: row; align-items: stretch; padding: 14px 18px; gap: 0; }
.cgrid.lv .card .card-body { flex: 1; min-width: 0; display: flex; flex-direction: column; }
.cgrid.lv .card .cact { margin-top: 0; flex-shrink: 0; flex-direction: column; justify-content: flex-end; gap: 6px; width: 140px; padding-left: 16px; border-left: 1.5px solid var(--mist); }
.cgrid.lv .card .cact .btn-bm { width: 100%; border-radius: 8px; }
.cgrid.lv .card .cdiv { display: none; }
.cgrid.lv .card .mrow { margin-bottom: 0; }
.cgrid.lv .card .ctags { flex-wrap: nowrap; overflow: hidden; }

/* Toast */
.toast{
  position:fixed;right:20px;bottom:20px;z-index:500;
  background:#0e2f39;color:#fff;border:1px solid rgba(255,255,255,0.2);
  border-radius:10px;padding:10px 12px;font-size:13px;
  box-shadow:0 8px 24px rgba(0,0,0,0.22);
  opacity:0;transform:translateY(8px);pointer-events:none;
  transition:opacity .2s ease,transform .2s ease;
}
.toast.show{opacity:1;transform:translateY(0);}

@media (max-width: 1100px){
  .sidebar{width:228px;padding:18px 14px 14px;}
  .main{padding:20px 18px 28px;}
  .cgrid{grid-template-columns:repeat(2,1fr);}
}
@media (max-width: 820px){
  .sidebar{width:220px;position:sticky;top:56px;height:calc(100vh - 56px);}
  .main{padding:16px;}
  .cgrid{grid-template-columns:1fr;}
}
</style>
</head>
<body>

{{--═══════════════════════════════════════════════════ NAVBAR ══════════════ --}}
<nav class="navbar">
  <a class="nav-logo" href="{{ route('landing') }}">
    <div class="logo-box">🎓</div>
    <span class="logo-text">ScholarLink</span>
  </a>

  {{--Search form — submits GET to same page --}}
  <form class="nav-search" method="GET" action="{{ route('scholarships.index') }}" id="filter-form">
    <span class="si">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
    </span>
    <input type="text" name="q" id="nav-search"
           value="{{ $filters['q'] ?? '' }}"
           placeholder="Search scholarships, organizations…"
           autocomplete="off">
  </form>

  <div class="nav-right">
    <button class="nav-ibtn" type="button" title="Notifications">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
      <span class="nbadge"></span>
    </button>
    <button class="nav-ibtn" type="button" title="Settings">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06-.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
      </svg>
    </button>

    @auth
     {{--Show initials from the logged-in user's name --}}
      <div class="nav-av" title="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
      </div>
    @else
      <a href="{{ route('login') }}" class="pbtn" style="text-decoration:none;padding:0 14px;">Log in</a>
    @endauth
  </div>
</nav>

{{-- ═══════════════════════════════════════════════════ LAYOUT ═════════════ --}}
<div class="layout">

 {{--───────────────────────────────────── SIDEBAR / FILTERS ───────────────--}}
  <aside class="sidebar">
    <div class="sb-hd">
      <span class="sb-title">Filters</span>
      <a href="{{ route('scholarships.index') }}" class="btn-clr">Clear all</a>
    </div>

    {{-- Status --}}
    <div class="fg">
      <div class="fgl">Status</div>
      @php
        $activeStatuses = (array) ($filters['status'] ?? ['open', 'closing_soon', 'coming_soon']);
      @endphp
      <label class="cbr">
        <input type="checkbox" name="status[]" value="open"
               {{ in_array('open', $activeStatuses) ? 'checked' : '' }}
               onchange="submitFilters()">
        <span class="cbl">Open</span>
      </label>
      <label class="cbr">
        <input type="checkbox" name="status[]" value="closing_soon"
               {{ in_array('closing_soon', $activeStatuses) ? 'checked' : '' }}
               onchange="submitFilters()">
        <span class="cbl">Closing Soon</span>
      </label>
      <label class="cbr">
        <input type="checkbox" name="status[]" value="coming_soon"
               {{ in_array('coming_soon', $activeStatuses) ? 'checked' : '' }}
               onchange="submitFilters()">
        <span class="cbl">Coming Soon</span>
      </label>
    </div>

    {{-- Category (tags) --}}
    <div class="fg">
      <div class="fgl">Category</div>
      @php
        $activeCategories = (array) ($filters['category'] ?? []);
        $categoryOptions = [
          'STEM'             => 'STEM',
          'Merit-Based'      => 'Merit-Based',
          'Need-Based'       => 'Need-Based',
          'Arts & Humanities'=> 'Arts & Humanities',
          'Medicine & Health'=> 'Medicine & Health',
        ];
      @endphp
      @foreach($categoryOptions as $value => $label)
        <label class="cbr">
          <input type="checkbox" name="category[]" value="{{ $value }}"
                 {{ in_array($value, $activeCategories) ? 'checked' : '' }}
                 onchange="submitFilters()">
          <span class="cbl">{{ $label }}</span>
        </label>
      @endforeach
    </div>

    {{-- GPA / QPI slider --}}
    <div class="fg">
      <div class="fgl">Minimum GPA / QPI</div>
      <div class="sw">
        @php $gpaVal = $filters['gpa'] ?? 1.75; @endphp
        <div class="swr">
          <span>1.00</span>
          <span class="swc" id="gpa-d">≥ {{ number_format($gpaVal, 2) }}</span>
          <span>5.00</span>
        </div>
        <input type="range" id="gpa-s" name="gpa" min="1" max="5" step="0.25"
               value="{{ $gpaVal }}"
               oninput="document.getElementById('gpa-d').textContent='≥ '+parseFloat(this.value).toFixed(2);"
               onchange="submitFilters()">
      </div>
    </div>

    {{-- Income bracket --}}
    <div class="fg">
      <div class="fgl">Income Bracket</div>
      @php

        $activeIncomes = (array) ($filters['income'] ?? ['Below ₱100K/yr', '₱100K–₱250K']);
        $incomeOptions = ['Below ₱100K/yr', '₱100K–₱250K', '₱250K–₱500K', 'Open / Any'];
      @endphp
      @foreach($incomeOptions as $incVal)
        <label class="cbr">
          <input type="checkbox" name="income[]" value="{{ $incVal }}"
                 {{ in_array($incVal, $activeIncomes) ? 'checked' : '' }}
                 onchange="submitFilters()">
          <span class="cbl">{{ $incVal }}</span>
        </label>
      @endforeach
    </div>

    {{-- Deadline pill group --}}
    <div class="fg">
      <div class="fgl">Deadline</div>
      @php
        $activeDl = $filters['deadline'] ?? 'This month';
        $deadlineOptions = ['This week', 'This month', 'Next 3 months', 'Any time'];
      @endphp
      <div class="pg">
        @foreach($deadlineOptions as $dlOpt)
          <button type="button"
                  class="pill {{ $activeDl === $dlOpt ? 'active' : '' }}"
                  onclick="setPill('deadline', '{{ $dlOpt }}')">
            {{ $dlOpt }}
          </button>
        @endforeach
        {{-- Hidden input carries the selected value --}}
        <input type="hidden" name="deadline" id="deadline-val" value="{{ $activeDl }}">
      </div>
    </div>

    {{-- Match score slider (only shown to authenticated users) --}}
    @auth
    <div class="fg">
      <div class="fgl">Min. Match Score</div>
      <div class="sw">
        @php $matchVal = $filters['match'] ?? 60; @endphp
        <div class="swr">
          <span>0%</span>
          <span class="swc" id="match-d">≥ {{ $matchVal }}%</span>
          <span>100%</span>
        </div>
        <input type="range" id="match-s" name="match" min="0" max="100" step="5"
               value="{{ $matchVal }}"
               oninput="document.getElementById('match-d').textContent='≥ '+this.value+'%';"
               onchange="submitFilters()">
      </div>
    </div>
    @endauth

    {{-- Sort (hidden on sidebar, controlled by top bar) --}}
    <input type="hidden" name="sort" id="sort-val" value="{{ $filters['sort'] ?? 'match' }}">

    {{-- User profile card (bottom of sidebar) --}}
    @auth
    <div class="sb-user">
      <div class="sb-av">
        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
      </div>
      <div>
        <div class="sb-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
        @if(Auth::user()->applicantProfile)
          <div class="sb-sub">
            Applicant · GPA {{ number_format(Auth::user()->applicantProfile->gwa, 2) }}
          </div>
        @else
          <div class="sb-sub">Applicant</div>
        @endif
      </div>
    </div>
    @endauth

  </aside>

  {{-- ───────────────────────────────────────────────── MAIN CONTENT ──────── --}}
  <main class="main">
    <div class="eyebrow">BROWSE</div>
    <h1 class="ptitle">Scholarship Opportunities</h1>

    {{-- Active filter chips --}}
    @php
      $chips = [];
      if (!empty($filters['status'])) {
          foreach ((array) $filters['status'] as $s) {
              $chips[] = ['label' => ucwords(str_replace('_', ' ', $s)), 'remove' => 'status'];
          }
      }
      if (!empty($filters['category'])) {
          foreach ((array) $filters['category'] as $c) {
              $chips[] = ['label' => $c, 'remove' => 'category'];
          }
      }
      if (!empty($filters['income'])) {
          foreach ((array) $filters['income'] as $i) {
              $chips[] = ['label' => $i, 'remove' => 'income'];
          }
      }
      if (!empty($filters['q'])) {
          $chips[] = ['label' => '"' . $filters['q'] . '"', 'remove' => 'q'];
      }
    @endphp

    @if(count($chips))
    <div class="chips-bar">
      <span class="chips-lbl">Active:</span>
      @foreach($chips as $chip)
        <span class="chip">{{ $chip['label'] }} <span class="cx">×</span></span>
      @endforeach
      <a href="{{ route('scholarships.index') }}" class="btn-clrchips">Clear all</a>
    </div>
    @endif

    {{-- Results bar --}}
    <div class="rbar">
      <div class="rtxt" id="rtxt">
        <strong>{{ $scholarships->total() }}</strong>
        scholarship{{ $scholarships->total() !== 1 ? 's' : '' }} match your filters
      </div>
      <div class="rright">
        <select class="sort-sel" id="sort-sel" onchange="setSort(this.value)">
          <option value="match"    {{ ($filters['sort'] ?? 'match') === 'match'    ? 'selected' : '' }}>Best Match</option>
          <option value="deadline" {{ ($filters['sort'] ?? '') === 'deadline' ? 'selected' : '' }}>Deadline (Soonest)</option>
          <option value="slots"    {{ ($filters['sort'] ?? '') === 'slots'    ? 'selected' : '' }}>Most Slots</option>
          <option value="alpha"    {{ ($filters['sort'] ?? '') === 'alpha'    ? 'selected' : '' }}>A – Z</option>
        </select>
        <div class="vtgl">
          <button class="vbtn active" id="btn-grid" onclick="setView('grid')" title="Grid">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
              <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
              <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
          </button>
          <button class="vbtn" id="btn-list" onclick="setView('list')" title="List">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    {{--─────────────────── SCHOLARSHIP CARDS GRID ──────────────────────── --}}
    <div class="cgrid" id="cgrid">

      @forelse($scholarships as $scholarship)
        @php
        /* ai_match_score lives on the applications table (per applicant).
            If the user is logged in and has applied, pull it from the
            eager-loaded relationship. Otherwise show no score.
       */
          $userApplication = auth()->check()
              ? $scholarship->applications->first()
              : null;
          $matchScore = $userApplication?->ai_match_score ?? null;

          /*
            Determine badge type from scholarships.status (ENUM).
            Values from DB: open, closing_soon, coming_soon, closed
          */
          $statusLabel = match($scholarship->status) {
              'open'         => 'Open',
              'closing_soon' => 'Closing Soon',
              'coming_soon'  => 'Coming Soon',
              'closed'       => 'Closed',
              default        => ucfirst($scholarship->status),
          };

          /*
            "New" badge: posted within the last 14 days
          */
          $isNew = $scholarship->posted_at &&
                   \Carbon\Carbon::parse($scholarship->posted_at)->diffInDays(now()) <= 14;


          $tags = $scholarship->tags ?? [];
          if (is_string($tags)) {
              $tags = json_decode($tags, true) ?? [];
          }
        @endphp

        <div class="card {{ $userApplication && $userApplication->status === 'approved' ? 'saved' : '' }}"
             data-id="{{ $scholarship->id }}">
             <div class="card-body">

          {{-- Card top: org name + badges --}}
          <div class="ctop">
            <span class="corg">{{ $scholarship->provider_name }}</span>
            <div class="cbdg">
              @if($isNew)
                <span class="bdg bdg-new">+ New</span>
              @endif
              @if($scholarship->status === 'open')
                <span class="bdg bdg-open">Open</span>
              @elseif($scholarship->status === 'closing_soon')
                <span class="bdg bdg-closing">Closing Soon</span>
              @elseif($scholarship->status === 'coming_soon')
                <span class="bdg bdg-coming">Coming Soon</span>
              @elseif($scholarship->status === 'closed')
                <span class="bdg" style="background:var(--mist);color:var(--slate);border-radius:20px;">Closed</span>
              @endif
            </div>
          </div>

          {{-- Scholarship name --}}
          <div class="ctitle">{{ $scholarship->name }}</div>

          {{-- Deadline + location meta --}}
          <div class="cmeta">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>
              Deadline:
              {{ $scholarship->deadline
                  ? \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y')
                  : 'Rolling / Open' }}
            </span>
          </div>

          {{-- Tags: gpa_requirement, income_bracket, slots + category tags --}}
          <div class="ctags">
            @if($scholarship->gpa_requirement)
              <span class="tag">GPA {{ $scholarship->gpa_requirement }}+</span>
            @endif
            @if($scholarship->income_bracket)
              <span class="tag">{{ $scholarship->income_bracket }}</span>
            @endif
            <span class="tag">{{ number_format($scholarship->slots) }} slots</span>
            @foreach($tags as $tag)
              <span class="tag">{{ $tag }}</span>
            @endforeach
          </div>

          {{-- Benefit snippets (short highlights from DB) --}}
          @if($scholarship->benefit_snippet_1 || $scholarship->benefit_snippet_2)
            <div style="margin-bottom:14px;">
              @if($scholarship->benefit_snippet_1)
                <div style="font-size:11.5px;color:var(--teal);margin-bottom:3px;">
                  ✓ {{ $scholarship->benefit_snippet_1 }}
                </div>
              @endif
              @if($scholarship->benefit_snippet_2)
                <div style="font-size:11.5px;color:var(--teal);">
                  ✓ {{ $scholarship->benefit_snippet_2 }}
                </div>
              @endif
            </div>
          @endif

          <div class="cdiv"></div>

          {{-- Match score (only shown to logged-in users with an AI score) --}}
          @auth
            @if($matchScore !== null)
              <div class="mlbl">Your Match Score</div>
              <div class="mrow">
                <div class="btrack">
                  <div class="bfill" style="width:{{ $matchScore }}%"></div>
                </div>
                <span class="mpct">{{ number_format($matchScore, 0) }}%</span>
              </div>
            @else
              <div class="mlbl" style="margin-bottom:13px;opacity:.6;">Match score not yet computed</div>
            @endif
          @endauth

          {{-- Actions --}}
          </div> 
          <div class="cact">
            <a href="{{ route('scholarships.show', $scholarship->id) }}"
               class="btn-apply">
              View & Apply
            </a>
            {{-- Save  / save button (wired to a POST route) --}}
            <form method="POST" action="{{ route('scholarships.save', $scholarship->id) }}" style="display:contents;">              @csrf
              <button type="submit" class="btn-bm" title="Save scholarship">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                </svg>
              </button>
            </form>
          </div>

        </div>{{-- /card --}}

      @empty
        <div class="no-res">
          <strong>No scholarships found</strong>
          <span>Try adjusting your filters or
            <a href="{{ route('scholarships.index') }}" style="color:var(--teal);">reset all</a>.
          </span>
        </div>
      @endforelse

    </div>{{-- /cgrid --}}

    {{-- ───────────────────────────────── PAGINATION ──────────────────────── --}}
    @if($scholarships->hasPages())
    <div class="pagi">
      {{-- Previous --}}
      @if($scholarships->onFirstPage())
        <span class="pbtn disabled">‹</span>
      @else
        <a class="pbtn" href="{{ $scholarships->previousPageUrl() }}">‹</a>
      @endif

      {{-- Page numbers --}}
      @foreach($scholarships->getUrlRange(1, $scholarships->lastPage()) as $page => $url)
        @if($page == $scholarships->currentPage())
          <span class="pbtn active">{{ $page }}</span>
        @elseif(abs($page - $scholarships->currentPage()) <= 2 || $page == 1 || $page == $scholarships->lastPage())
          <a class="pbtn" href="{{ $url }}">{{ $page }}</a>
        @elseif(abs($page - $scholarships->currentPage()) == 3)
          <span class="psep">…</span>
        @endif
      @endforeach

      {{-- Next --}}
      @if($scholarships->hasMorePages())
        <a class="pbtn" href="{{ $scholarships->nextPageUrl() }}">›</a>
      @else
        <span class="pbtn disabled">›</span>
      @endif
    </div>
    @endif

  </main>
</div>{{-- /layout --}}

<div class="toast" id="toast"></div>

<script>
// ── View toggle (grid vs list) ─────────────────────────────────────────────
function setView(mode) {
  const grid = document.getElementById('cgrid');
  const btnGrid = document.getElementById('btn-grid');
  const btnList = document.getElementById('btn-list');
  if (mode === 'list') {
    grid.classList.add('lv');
    btnList.classList.add('active');
    btnGrid.classList.remove('active');
  } else {
    grid.classList.remove('lv');
    btnGrid.classList.add('active');
    btnList.classList.remove('active');
  }
}

// ── Sort select — updates hidden input and submits ─────────────────────────
function setSort(value) {
  document.getElementById('sort-val').value = value;
  submitFilters();
}

// ── Deadline pill toggle ───────────────────────────────────────────────────
function setPill(group, value) {
  document.querySelectorAll(`.pill[data-g="${group}"]`).forEach(p => p.classList.remove('active'));
  event.currentTarget.classList.add('active');
  document.getElementById('deadline-val').value = value;
  submitFilters();
}

// ── Submit the filter form (debounced for range inputs) ────────────────────
let submitTimer;
function submitFilters(delay = 400) {
  clearTimeout(submitTimer);
  submitTimer = setTimeout(() => {
    document.getElementById('filter-form').submit();
  }, delay);
}

// ── Search input: debounce to avoid submitting on every keystroke ──────────
document.getElementById('nav-search').addEventListener('input', () => submitFilters(500));

// ── ⌘K / Ctrl+K focuses the search bar ────────────────────────────────────
document.addEventListener('keydown', e => {
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    document.getElementById('nav-search').focus();
  }
});

// ── Simple toast helper ────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 1700);
}
</script>
</body>
</html>

{{--
=====================================================================
 resources/views/applicant/documents/wallet.blade.php
 Document Wallet — ScholarLink
=====================================================================
 CONTROLLER (DocumentWalletController.php):

    public function index()
    {
        $user = auth()->user();

        // Fetch user's documents keyed by document_type
        // withCount('applications') → gives $doc->applications_count
        $documents = $user->documents()
            ->withCount('applications')
            ->get()
            ->keyBy('document_type');

        // Total active applications the user is involved in
        $activeApplicationsCount = $user->applications()
            ->whereIn('status', ['pending', 'under_review', 'approved'])
            ->count();

        return view('applicant.documents.wallet', compact(
            'documents',
            'activeApplicationsCount'
        ));
    }

 DB FIELDS USED (documents table):
   id, user_id, document_type, file_url,
   status (pending/verified/rejected),
   expiry_date, updated_at, applications_count (via withCount)

 DB FIELDS USED (users table):
   first_name, last_name (for navbar avatar initials)
=====================================================================
--}}

@extends('layouts.app')

@section('title', 'Document Wallet — ScholarLink')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
{{--
  FONT CORRECTION:
  · Fraunces (Black 900)  → page title "Document Wallet", stat numbers, logo
  · DM Sans (400–700)     → all body text, labels, buttons, badges
--}}
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Fraunces:opsz,ital,wght@9..144,0,700;9..144,0,900&display=swap" rel="stylesheet">

<style>
/* ── RESET ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ──────────────────────────────────────────
   COLOR TOKENS  (from ScholarLink brand guide)
   Primary    Deep Teal      #0F4C5C
   Accent     Warm Amber     #C9A84C
   Accent Lt  Soft Gold      #F9D679
   BG         Light Green    #F0FAFA  (page background)
   Surface    Cloud Gray     #F4F6FA  (card bg tint)
   Border     Mist Gray      #E2E8F0
   Text       Ink            #1C1C2E
   Text 2nd   Slate Gray     #8A95A3
   Status OK  Pale Green     #C2FAD7 / #2D6A4F
   Status Warn Amber         #FEF9C3 / #E8A838
   Status Err Crimson        #FFD5D5 / #DC2626
   ────────────────────────────────────────── */
:root {
  --teal:         #0F4C5C;
  --teal-mid:     #1A6B7A;
  --amber:        #C9A84C;
  --amber-light:  #F9D679;
  --amber-warn:   #E8A838;
  --cloud:        #F4F6FA;
  --mist:         #E2E8F0;
  --mist2:        #DFF0EE;
  --slate:        #8A95A3;
  --ink:          #1C1C2E;
  --light-green:  #F0FAFA;
  --white:        #FFFFFF;
  --green-bg:     #C2FAD7;
  --green-text:   #2D6A4F;
  --red-bg:       #FFD5D5;
  --red-text:     #DC2626;
  --warn-bg:      #FEF9C3;
  --warn-text:    #E8A838;
  --grey-badge:   #ECF1F2;
  --bismark:      #5A7A86;
  --card-shadow:  2px 8px 16px rgba(15, 76, 92, 0.07);
}

/* ── BASE ── */
body {
  /* FONT FIX: DM Sans as base font for all body text */
  font-family: 'DM Sans', sans-serif;
  background: var(--light-green);
  color: var(--ink);
  min-height: 100vh;
  -webkit-font-smoothing: antialiased;
}

/* ════════════════ NAVBAR ════════════════ */
.navbar {
  background: var(--white);
  height: 56px;
  display: flex;
  align-items: center;
  padding: 0 28px;
  gap: 16px;
  position: sticky;
  top: 0;
  z-index: 200;
  border-bottom: 1px solid var(--mist2);
  box-shadow: 0 2px 8px rgba(15, 76, 92, 0.08);
}

.nav-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  flex-shrink: 0;
}
.logo-box {
  width: 30px; height: 30px;
  background: linear-gradient(143deg, var(--teal) 0%, var(--teal-mid) 100%);
  color: #fff; font-size: 15px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 10px rgba(15, 76, 92, 0.2);
}
/* FONT FIX: Logo uses Fraunces serif */
.logo-text {
  font-family: 'Fraunces', serif;
  font-size: 16px;
  font-weight: 700;
  color: var(--teal);
  letter-spacing: -0.2px;
}

.nav-search {
  flex: 1; max-width: 440px; margin: 0 auto;
  display: flex; align-items: center; gap: 8px;
  background: var(--light-green);
  border: 1px solid var(--mist2);
  border-radius: 999px;
  padding: 7px 16px;
  cursor: text;
}
.nav-search .search-txt { flex: 1; font-size: 13px; color: rgba(15,76,92,0.5); }
.nav-search .kbd {
  background: rgba(255,255,255,0.6);
  border: 1px solid rgba(15,76,92,0.2);
  border-radius: 4px; color: var(--teal);
  font-size: 10px; font-weight: 700;
  padding: 1px 5px; line-height: 16px;
}

.nav-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }
.nav-ibtn {
  width: 36px; height: 36px; border-radius: 8px;
  background: var(--light-green);
  border: 1px solid var(--mist2);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; position: relative; transition: all .15s;
  color: var(--bismark); text-decoration: none;
}
.nav-ibtn:hover { background: rgba(15,76,92,0.08); border-color: rgba(15,76,92,0.2); }
.notif-dot {
  position: absolute; top: 6px; right: 6px;
  width: 8px; height: 8px; border-radius: 50%;
  /* COLOR FIX: amber-light dot with teal border per brand guide */
  background: var(--amber-light);
  border: 1.5px solid var(--teal);
}
.nav-av {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(143deg, var(--teal) 0%, var(--teal-mid) 100%);
  /* COLOR FIX: amber-light text on teal avatar per brand guide */
  color: var(--amber-light);
  font-size: 12px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; border: 2px solid rgba(255,255,255,0.35);
  text-decoration: none;
}

/* ════════════════ PAGE WRAPPER ════════════════ */
.page { max-width: 1280px; margin: 0 auto; padding: 32px 36px 48px; }

/* ── PAGE HEADER ── */
/* COLOR FIX: eyebrow uses --bismark (secondary text color) */
.eyebrow {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 1.4px;
  text-transform: uppercase;
  color: var(--bismark);
  margin-bottom: 4px;
}
/* FONT FIX: "Document Wallet" title — Fraunces Black 900, Deep Teal color */
.page-title {
  font-family: 'Fraunces', serif;
  font-size: 36px;
  font-weight: 900;
  color: var(--teal);      /* Deep Teal #0F4C5C — matches reference image */
  letter-spacing: -0.5px;
  line-height: 1.1;
  margin-bottom: 6px;
}
.page-sub {
  font-size: 13.5px;
  color: var(--bismark);
  margin-bottom: 28px;
}

/* ════════════════ STAT CARDS ════════════════ */
.stats-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 32px;
}
.stat-card {
  background: var(--white);
  border-radius: 20px;
  padding: 16px 20px 18px;
  box-shadow: var(--card-shadow);
  display: flex; flex-direction: column; gap: 4px;
  transition: transform .15s, box-shadow .15s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,76,92,0.11); }

.stat-label {
  font-size: 10px; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase;
  color: var(--bismark);
}
/* FONT FIX: stat numbers use Fraunces Black like the reference image */
.stat-num {
  font-family: 'Fraunces', serif;
  font-size: 38px; font-weight: 900;
  color: var(--teal);       /* default: teal */
  line-height: 1; margin: 4px 0 2px;
}
/* COLOR FIX: match brand guide status colors */
.stat-num.warn   { color: var(--amber-warn); }   /* #E8A838 — Expiring Soon */
.stat-num.danger { color: var(--red-text);   }   /* #DC2626 — Expired */
.stat-num.active { color: var(--teal-mid);   }   /* #1A6B7A — Used In */
.stat-sub { font-size: 12px; color: var(--bismark); }

/* ════════════════ SECTION HEADER ════════════════ */
.section-header {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 18px;
}
.section-title { font-size: 15px; font-weight: 700; color: var(--ink); }
.btn-upload-all {
  display: flex; align-items: center; gap: 7px;
  /* COLOR FIX: teal-to-teal-mid gradient, amber-light text per brand guide */
  background: linear-gradient(160deg, var(--teal), var(--teal-mid));
  color: var(--amber-light);
  font-family: 'DM Sans', sans-serif;
  font-size: 13px; font-weight: 600;
  border: none; border-radius: 10px; padding: 8px 18px;
  cursor: pointer; transition: opacity .15s; text-decoration: none;
}
.btn-upload-all:hover { opacity: .88; }

/* ════════════════ DOCUMENT GRID ════════════════ */
.doc-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

/* ── DOCUMENT CARD ── */
.doc-card {
  background: var(--white);
  border-radius: 20px;
  padding: 20px 20px 18px;
  box-shadow: var(--card-shadow);
  border: 2px solid transparent;
  display: flex; flex-direction: column;
  transition: transform .15s, box-shadow .15s, border-color .15s;
  position: relative; overflow: hidden;
}
.doc-card:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(15,76,92,0.1); }
/* COLOR FIX: border colors use exact brand status colors */
.doc-card.is-expired  { border-color: var(--red-text);   }  /* #DC2626 */
.doc-card.is-expiring { border-color: var(--amber-warn); }  /* #E8A838 */

/* ── CARD TOP ROW ── */
.card-top { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
.doc-icon {
  width: 48px; height: 48px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
/* COLOR FIX: icon backgrounds use correct opacity tints */
.doc-icon.teal  { background: linear-gradient(143deg, rgba(15,76,92,0.12) 0%, rgba(26,107,122,0.08) 100%); }
.doc-icon.red   { background: rgba(220, 38, 38, 0.10); }
.doc-icon.amber { background: rgba(232, 168, 56, 0.10); }
.doc-icon svg { width: 22px; height: 22px; }
.doc-icon.teal  svg { color: var(--teal);     }
.doc-icon.red   svg { color: var(--red-text); }
.doc-icon.amber svg { color: var(--amber-warn); }

.card-meta { flex: 1; min-width: 0; }
/* COLOR FIX: doc-name is teal, not ink */
.doc-name { font-size: 14px; font-weight: 700; color: var(--teal); line-height: 1.3; margin-bottom: 6px; }

/* ── BADGES ── */
.badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 4px 11px; border-radius: 999px;
  font-size: 11px; font-weight: 700; line-height: 1; white-space: nowrap;
}
/* COLOR FIX: all badge colors match brand guide status palette */
.badge.uploaded    { background: var(--green-bg);  color: var(--green-text); }
.badge.uploaded::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--green-text); }
.badge.expired     { background: var(--red-bg);    color: var(--red-text);   }
.badge.expired::before  { content: '⚠ '; }
.badge.expiring    { background: var(--warn-bg);   color: var(--warn-text);  }
.badge.expiring::before { content: '⚠ '; }
.badge.pending     { background: rgba(139,92,246,0.12); color: #6d28d9; }
.badge.pending::before  { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #6d28d9; }
.badge.not-uploaded { background: var(--grey-badge); color: var(--bismark); }

/* ── FILE INFO ROW ── */
.file-row {
  display: flex; align-items: center; gap: 10px;
  background: var(--light-green);
  border: 1px solid var(--mist2);
  border-radius: 10px;
  padding: 10px 14px;
  margin-bottom: 12px;
}
.file-row svg { color: var(--bismark); flex-shrink: 0; width: 16px; height: 16px; }
.file-info { flex: 1; min-width: 0; }
/* COLOR FIX: filename is teal */
.file-name { font-size: 12.5px; font-weight: 600; color: var(--teal); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.file-meta { font-size: 11px; color: var(--bismark); }

.used-in { font-size: 11.5px; color: var(--bismark); margin-bottom: 14px; }

/* ── ACTION BUTTONS ── */
.card-actions { display: flex; gap: 8px; margin-top: auto; }
.btn-preview, .btn-replace {
  flex: 1; display: flex; align-items: center; justify-content: center; gap: 7px;
  height: 38px; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all .15s; text-decoration: none;
  /* COLOR FIX: outline style — white bg, teal border and text */
  background: var(--white);
  border: 1.5px solid var(--teal);
  color: var(--teal);
}
.btn-preview:hover,
.btn-replace:hover { background: rgba(15, 76, 92, 0.05); }
.btn-preview svg, .btn-replace svg { width: 16px; height: 16px; }

/* ── DROP AREA (not uploaded) ── */
.drop-area {
  border: 2px dashed rgba(200, 232, 228, 0.9);
  border-radius: 14px;
  background: var(--light-green);
  padding: 22px 16px;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 6px;
  cursor: pointer;
  transition: border-color .2s, background .2s;
  flex: 1; text-decoration: none;
}
.drop-area:hover { border-color: var(--teal); background: rgba(15, 76, 92, 0.03); }
.drop-icon  { font-size: 22px; margin-bottom: 2px; }
/* COLOR FIX: drop-title is teal */
.drop-title { font-size: 13px; font-weight: 600; color: var(--teal); }
.drop-hint  { font-size: 11px; color: var(--bismark); }
.drop-hint span { color: var(--teal); font-weight: 600; text-decoration: underline; }

/* ════════════════ TOAST ════════════════ */
.toast {
  position: fixed; right: 20px; bottom: 22px; z-index: 500;
  background: #0e2f39; color: #fff;
  border-radius: 10px; padding: 10px 16px; font-size: 13px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.22);
  opacity: 0; transform: translateY(8px); pointer-events: none;
  transition: opacity .2s, transform .2s;
}
.toast.show { opacity: 1; transform: translateY(0); }

/* ════════════════ ANIMATIONS ════════════════ */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}
.stat-card { animation: fadeUp .4s ease both; }
.stat-card:nth-child(1) { animation-delay: .05s; }
.stat-card:nth-child(2) { animation-delay: .10s; }
.stat-card:nth-child(3) { animation-delay: .15s; }
.stat-card:nth-child(4) { animation-delay: .20s; }
.doc-card  { animation: fadeUp .45s ease both; }

/* ════════════════ RESPONSIVE ════════════════ */
@media (max-width: 1100px) {
  .doc-grid   { grid-template-columns: repeat(2, 1fr); }
  .stats-row  { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 680px) {
  .doc-grid   { grid-template-columns: 1fr; }
  .stats-row  { grid-template-columns: repeat(2, 1fr); }
  .page       { padding: 20px 16px 40px; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════
     PHP LOGIC — derives all display states from
     the $documents collection passed by controller.
     No values are hardcoded below this block.
     ══════════════════════════════════════════════ --}}
@php
use Carbon\Carbon;

/**
 * Predefined document type slots for the wallet.
 * 'key' must match the document_type values in the DB.
 */
$documentTypes = [
    ['label' => 'PSA Birth Certificate',         'key' => 'PSA Birth Certificate',               'icon' => 'file'],
    ['label' => 'Certificate of Enrollment',     'key' => 'Certificate of Enrollment',           'icon' => 'file'],
    ['label' => 'Income Tax Return (ITR)',        'key' => 'Income Tax Return (ITR)',             'icon' => 'file'],
    ['label' => 'Certificate of Indigency',      'key' => 'Barangay Certificate of Indigency',   'icon' => 'file'],
    ['label' => 'Good Moral Certificate',        'key' => 'Certificate of Good Moral Character', 'icon' => 'file'],
    ['label' => 'Transcript of Records / TOR',   'key' => 'Latest Report Card / TOR',            'icon' => 'file'],
    ['label' => '2x2 ID Photo',                  'key' => '2x2 ID Photo',                        'icon' => 'image'],
    ['label' => "Voter's ID / National ID",      'key' => "Voter's ID / National ID",            'icon' => 'id'],
    ['label' => 'Medical Certificate',           'key' => 'Medical Certificate',                 'icon' => 'health'],
    ['label' => 'DSWD Certificate',              'key' => 'DSWD Certificate',                    'icon' => 'file'],
    ['label' => 'Certificate of Class Ranking',  'key' => 'Certificate of Class Ranking',        'icon' => 'chart'],
    ['label' => 'Additional Documents',          'key' => 'Additional Documents',                'icon' => 'plus'],
];

$today       = Carbon::today();
$totalTypes  = count($documentTypes);

// ── STATS (all from $documents — no hardcoded numbers) ──
// Uploaded: document record exists AND has a file_url
$uploadedCount = $documents->filter(fn($d) => !empty($d->file_url))->count();

// Expiring Soon: has expiry_date, not yet expired, within 30 days
$expiringSoonCount = $documents->filter(function ($d) use ($today) {
    if (!$d->expiry_date) return false;
    $exp = Carbon::parse($d->expiry_date);
    return $exp->gt($today) && $exp->diffInDays($today) <= 30;
})->count();

// Expired: expiry_date is in the past
$expiredCount = $documents->filter(function ($d) use ($today) {
    if (!$d->expiry_date) return false;
    return Carbon::parse($d->expiry_date)->lt($today);
})->count();

// $activeApplicationsCount is passed from the controller directly
@endphp

{{-- ══ NAVBAR ══ --}}
<nav class="navbar">
    <a class="nav-logo" href="{{ route('home') }}">
        <div class="logo-box">🎓</div>
        <span class="logo-text">ScholarLink</span>
    </a>

    <div class="nav-search">
        <span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </span>
        <span class="search-txt">Search scholarships...</span>
        <span class="kbd">⌘K</span>
    </div>

    <div class="nav-right">
        <a href="{{ route('notifications.index') }}" class="nav-ibtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            {{-- Dynamic: only show dot if user has unread notifications --}}
            @if(auth()->user()->unreadNotifications->isNotEmpty())
                <span class="notif-dot"></span>
            @endif
        </a>

        <a href="{{ route('profile.edit') }}" class="nav-ibtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2"/><polyline points="8 21 12 17 16 21"/>
            </svg>
        </a>

        {{-- Dynamic: initials from users.first_name + users.last_name --}}
        <a href="{{ route('profile.show') }}" class="nav-av"
           title="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}">
            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
        </a>
    </div>
</nav>

{{-- ══ MAIN PAGE ══ --}}
<div class="page">

    {{-- PAGE HEADER
         FIX: eyebrow "DOCUMENTS" label was missing from the original HTML.
         It is visible in the reference design image above the title. --}}
    <div class="eyebrow">Documents</div>
    <h1 class="page-title">Document Wallet</h1>
    <p class="page-sub">Upload once, reuse across all your scholarship applications.</p>

    {{-- ══ STAT CARDS ══ --}}
    <div class="stats-row">

        <div class="stat-card">
            <div class="stat-label">Uploaded</div>
            {{-- Dynamic: count from documents where file_url is not null --}}
            <div class="stat-num">{{ $uploadedCount }}</div>
            <div class="stat-sub">of {{ $totalTypes }} document types</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Expiring Soon</div>
            {{-- Dynamic: count where expiry_date is within 30 days --}}
            <div class="stat-num warn">{{ $expiringSoonCount }}</div>
            <div class="stat-sub">within 30 days</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Expired</div>
            {{-- Dynamic: count where expiry_date < today --}}
            <div class="stat-num danger">{{ $expiredCount }}</div>
            <div class="stat-sub">needs replacement</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Used In</div>
            {{-- Dynamic: $activeApplicationsCount from controller --}}
            <div class="stat-num active">{{ $activeApplicationsCount }}</div>
            <div class="stat-sub">active applications</div>
        </div>

    </div>{{-- /.stats-row --}}

    {{-- ══ SECTION HEADER ══ --}}
    <div class="section-header">
        <span class="section-title">My Documents</span>
        <a href="{{ route('applicant.documents.upload') }}" class="btn-upload-all">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="16 16 12 12 8 16"/>
                <line x1="12" y1="12" x2="12" y2="21"/>
                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
            </svg>
            Upload Documents
        </a>
    </div>

    {{-- ══ DOCUMENT GRID ══ --}}
    <div class="doc-grid">

        @foreach($documentTypes as $index => $type)
        @php
            /**
             * Look up the DB record for this document type.
             * $documents is keyed by document_type (set in controller).
             */
            $doc     = $documents->get($type['key']);
            $hasFile = $doc && !empty($doc->file_url);

            // Expiry state
            $expiry     = ($doc && $doc->expiry_date) ? Carbon::parse($doc->expiry_date) : null;
            $isExpired  = $expiry && $expiry->lt($today);
            $isExpiring = $expiry && !$isExpired && $expiry->diffInDays($today) <= 30;

            // Card border class
            $cardClass = $isExpired ? 'is-expired' : ($isExpiring ? 'is-expiring' : '');

            // Icon color class
            $iconColor = $isExpired ? 'red' : ($isExpiring ? 'amber' : 'teal');

            // Badge class + text
            if (!$hasFile) {
                $badgeClass = 'not-uploaded';
                $badgeText  = 'Not Uploaded';
            } elseif ($isExpired) {
                $badgeClass = 'expired';
                $badgeText  = 'Expired ' . $expiry->format('M j, Y');
            } elseif ($isExpiring) {
                $badgeClass = 'expiring';
                $badgeText  = 'Expires ' . $expiry->format('M j, Y');
            } elseif ($doc->status === 'pending') {
                $badgeClass = 'pending';
                $badgeText  = 'Pending Verification';
            } else {
                $badgeClass = 'uploaded';
                $badgeText  = 'Uploaded';
            }

            // Filename display (from documents.file_url)
            $fileName = $hasFile ? basename($doc->file_url) : null;

            // How many applications use this document (from withCount in controller)
            $usedCount = $doc ? ($doc->applications_count ?? 0) : 0;

            // Stagger animation
            $delay = number_format(0.08 + ($index * 0.04), 2);
        @endphp

        <div class="doc-card {{ $cardClass }}" style="animation-delay: {{ $delay }}s">

            {{-- Card header --}}
            <div class="card-top">
                <div class="doc-icon {{ $iconColor }}">
                    @switch($type['icon'])
                        @case('image')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            @break
                        @case('id')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <line x1="2" y1="10" x2="22" y2="10"/>
                            </svg>
                            @break
                        @case('health')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                            @break
                        @case('chart')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18 20 18 10"/>
                                <polyline points="12 20 12 4"/>
                                <polyline points="6 20 6 14"/>
                            </svg>
                            @break
                        @case('plus')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="16"/>
                                <line x1="8" y1="12" x2="16" y2="12"/>
                            </svg>
                            @break
                        @default {{-- file --}}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                    @endswitch
                </div>

                <div class="card-meta">
                    {{-- document_type label — NOT hardcoded, comes from $documentTypes array --}}
                    <div class="doc-name">{{ $type['label'] }}</div>
                    <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                </div>
            </div>{{-- /.card-top --}}

            @if($hasFile)
                {{-- ── UPLOADED STATE ── --}}

                {{-- File row: filename from documents.file_url, date from documents.updated_at --}}
                <div class="file-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    </svg>
                    <div class="file-info">
                        {{-- Dynamic: basename of documents.file_url --}}
                        <div class="file-name" title="{{ $fileName }}">{{ $fileName }}</div>
                        {{-- Dynamic: documents.updated_at formatted as relative time --}}
                        <div class="file-meta">Uploaded {{ $doc->updated_at->diffForHumans() }}</div>
                    </div>
                </div>

                {{-- Dynamic: applications_count from withCount('applications') in controller --}}
                <div class="used-in">
                    @if($usedCount > 0)
                        Used in {{ $usedCount }} {{ Str::plural('application', $usedCount) }}
                    @else
                        Not yet used in any application
                    @endif
                </div>

                <div class="card-actions">
                    {{-- Preview: passes documents.id to the preview route --}}
                    <a href="{{ route('applicant.documents.preview', $doc->id) }}"
                       class="btn-preview" target="_blank">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Preview
                    </a>
                    {{-- Replace: passes document_type to the replace route --}}
                    <a href="{{ route('applicant.documents.replace', ['documentType' => urlencode($type['key'])]) }}"
                       class="btn-replace">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 16 12 12 8 16"/>
                            <line x1="12" y1="12" x2="12" y2="21"/>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                        </svg>
                        Replace
                    </a>
                </div>

            @else
                {{-- ── NOT UPLOADED STATE: drop area ── --}}
                <a href="{{ route('applicant.documents.upload', ['documentType' => urlencode($type['key'])]) }}"
                   class="drop-area">
                    <div class="drop-icon">📎</div>
                    <div class="drop-title">Drop your document here</div>
                    <div class="drop-hint">PDF, JPG, PNG up to 5MB · <span>Browse Files</span></div>
                </a>
            @endif

        </div>{{-- /.doc-card --}}
        @endforeach

    </div>{{-- /.doc-grid --}}
</div>{{-- /.page --}}

<div class="toast" id="toast"></div>

@push('scripts')
<script>
let toastTimer;
function showToast(msg) {
    const t = document.getElementById('toast');
    clearTimeout(toastTimer);
    t.textContent = msg;
    t.classList.add('show');
    toastTimer = setTimeout(() => t.classList.remove('show'), 1800);
}

// Drag-and-drop highlight
document.querySelectorAll('.drop-area').forEach(el => {
    el.addEventListener('dragover', e => {
        e.preventDefault();
        el.style.borderColor = 'var(--teal)';
        el.style.background  = 'rgba(15,76,92,0.04)';
    });
    el.addEventListener('dragleave', () => {
        el.style.borderColor = '';
        el.style.background  = '';
    });
    el.addEventListener('drop', e => {
        e.preventDefault();
        el.style.borderColor = '';
        el.style.background  = '';
        const files = e.dataTransfer.files;
        if (files.length) {
            showToast(`"${files[0].name}" — redirecting to upload...`);
            setTimeout(() => { window.location = el.getAttribute('href'); }, 900);
        }
    });
});
</script>
@endpush

@endsection
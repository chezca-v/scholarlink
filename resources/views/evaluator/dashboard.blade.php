{{--
|--------------------------------------------------------------------------
| scholar-evaluator-dashboard.blade.php
|--------------------------------------------------------------------------
| ScholarLink — Evaluator Application Review Dashboard
|
| Sections:
|   01 — Top Navigation Bar
|   02 — Applicant Profile Card (left column)
|   03 — Evaluation Criteria + Score (left column)
|   04 — Submitted Documents (left column)
|   05 — Ranking Panel (right column)
|   06 — Applicants Leaderboard (right column)
|   07 — Flags & Notes (right column)
|   08 — Evaluator Notes (right column)
|   09 — Action Buttons (right column)
|   10 — Scholarship Slots (right column)
|
| Usage:
|   @include('components.scholar-evaluator-dashboard', [
|       'applicant'   => $applicant,
|       'criteria'    => $criteria,
|       'documents'   => $documents,
|       'ranking'     => $ranking,
|       'leaderboard' => $leaderboard,
|       'flags'       => $flags,
|       'notes'       => $evaluatorNotes,
|       'slots'       => $slots,
|   ])
--}}

@php
    /* ── Prop defaults (replace with real model data) ── */

    $applicant = $applicant ?? [
        'name'          => 'Karl Joseph Esteban',
        'initials'      => 'KE',
        'app_id'        => 'SL-2025-08841',
        'submitted_at'  => 'April 01, 2025',
        'track'         => 'STEM Track',
        'status'        => 'Under Review',      // Under Review | Approved | Rejected
        'school'        => 'Pamantasan ng Lungsod ng Maynila',
        'program'       => 'BS Computer Engineering',
        'year_level'    => '2nd Year',
        'region'        => 'NCR',
        'gwa'           => '97.4',
        'scholarship'   => 'DOST-SEI Merit',
        'total_score'   => 85.0,
        'completeness'  => 80,
    ];

    $criteria = $criteria ?? [
        ['label' => 'Academic Excellence',        'score' => 97],
        ['label' => 'Financial Need',             'score' => 71],
        ['label' => 'Leadership & Civic Involvement', 'score' => 95],
        ['label' => 'Recommendation Letters',     'score' => 89],
        ['label' => 'Special Circumstances',      'score' => 94],
    ];

    $documents = $documents ?? [
        ['name' => 'Certificate of Registration',  'type' => 'PDF', 'size' => '214 KB', 'status' => 'verified'],
        ['name' => 'SHS Report Card (G11–G12)',    'type' => 'PDF', 'size' => '31 KB',  'status' => 'verified'],
        ['name' => 'ITR / Certificate of Indigency','type' => 'PDF', 'size' => '532 KB', 'status' => 'verified'],
        ['name' => 'Recommendation Letter (2/3)',  'type' => 'PDF', 'size' => '88 KB',  'status' => 'pending', 'pending_count' => 1],
        ['name' => 'Personal Essay',               'type' => 'DOCX','size' => '47 KB',  'status' => 'verified'],
    ];

    $ranking = $ranking ?? [
        'rank'            => 97,
        'total_applicants'=> 348,
        'region_rank'     => 3,
        'region'          => 'NCR region',
    ];

    $leaderboard = $leaderboard ?? [
        ['name' => 'Princess Mae Sanchez', 'score' => 90.2, 'rank' => 9,  'current' => false],
        ['name' => 'Elena Vale Lanuza',    'score' => 88.6, 'rank' => 10, 'current' => false],
        ['name' => 'Franchezca Banayad',   'score' => 86.5, 'rank' => 11, 'current' => false],
        ['name' => 'Karl Joseph Esteban',  'score' => 85.0, 'rank' => 12, 'current' => true ],
        ['name' => 'Ysabelle Frigillana',  'score' => 82.3, 'rank' => 13, 'current' => false],
    ];

    $flags = $flags ?? [
        '1 recommendation letter still outstanding. Decision may proceed if evaluator waives requirement.',
    ];

    $notes = $notes ?? 'Strong academic record. Essay reflects genuine passion for research. Financial need is well-documented. Missing 3rd recco — professor confirmed en route.';

    $slots = $slots ?? [
        'scholarship'  => 'DOST-SEI Merit · NCR',
        'filled'       => 18,
        'total'        => 25,
        'remaining'    => 7,
        'closes_at'    => 'Apr 15, 2025',
    ];

    /* ── Evaluator info ── */
    $evaluator = $evaluator ?? ['initials' => 'EP', 'role' => 'Evaluator'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ScholarLink — Evaluator Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>

/* ── CSS Variables ──────────────────────────────────────────────────── */
:root {
    --sl-teal:          #1a6b63;
    --sl-teal-dark:     #0d3d3a;
    --sl-teal-mid:      #2a8a80;
    --sl-teal-light:    #e8f4f3;
    --sl-teal-xlight:   #f2fafa;
    --sl-amber:         #f5a623;
    --sl-amber-light:   #fef6e4;
    --sl-amber-border:  #f5cc80;
    --sl-green:         #27a96c;
    --sl-green-light:   #e6f7ef;
    --sl-orange:        #f07a30;
    --sl-orange-light:  #fff0e6;
    --sl-red:           #e05050;
    --sl-white:         #ffffff;
    --sl-bg:            #f4f6f5;
    --sl-surface:       #ffffff;
    --sl-border:        #e2e8e6;
    --sl-text-dark:     #1a2e2c;
    --sl-text-mid:      #4a6460;
    --sl-text-light:    #8aaba6;
    --sl-bar-bg:        #dde8e6;
    --sl-bar-fill:      #1a6b63;
    --sl-shadow-sm:     0 1px 4px rgba(0,0,0,.06);
    --sl-shadow-md:     0 4px 16px rgba(0,0,0,.09);
    --sl-radius-sm:     6px;
    --sl-radius-md:     10px;
    --sl-radius-lg:     14px;
    --sl-radius-pill:   9999px;
    --sl-font:          'DM Sans', sans-serif;
    --sl-font-display:  'DM Serif Display', serif;
    --sl-transition:    .22s cubic-bezier(.4,0,.2,1);
}

/* ── Reset ──────────────────────────────────────────────────────────── */
.sl-root *, .sl-root *::before, .sl-root *::after {
    box-sizing: border-box; margin: 0; padding: 0;
}
.sl-root {
    font-family: var(--sl-font);
    background:  var(--sl-bg);
    color:       var(--sl-text-dark);
    min-height:  100vh;
    font-size:   14px;
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
}

/* ════════════════════════════════════════════════════════════════════════
   01 — NAV BAR
════════════════════════════════════════════════════════════════════════ */
.sl-nav {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         0 28px;
    height:          56px;
    background:      var(--sl-white);
    border-bottom:   1px solid var(--sl-border);
    position:        sticky;
    top:             0;
    z-index:         100;
    box-shadow:      var(--sl-shadow-sm);
}
.sl-nav__brand {
    display:     flex;
    align-items: center;
    gap:         9px;
    font-weight: 700;
    font-size:   17px;
    color:       var(--sl-teal-dark);
    letter-spacing: -.3px;
}
.sl-nav__logo {
    width:           32px;
    height:          32px;
    border-radius:   var(--sl-radius-sm);
    background:      var(--sl-teal);
    display:         flex;
    align-items:     center;
    justify-content: center;
    color:           var(--sl-white);
    font-size:       16px;
}
.sl-nav__right { display: flex; align-items: center; gap: 14px; }
.sl-nav__role-badge {
    padding:       5px 14px;
    border-radius: var(--sl-radius-pill);
    background:    var(--sl-teal-light);
    color:         var(--sl-teal);
    font-size:     13px;
    font-weight:   600;
    border:        1px solid #c0dbd8;
}
.sl-nav__avatar {
    width:           36px;
    height:          36px;
    border-radius:   var(--sl-radius-pill);
    background:      var(--sl-teal);
    color:           var(--sl-white);
    font-weight:     700;
    font-size:       14px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    cursor:          pointer;
    transition:      transform var(--sl-transition), box-shadow var(--sl-transition);
}
.sl-nav__avatar:hover { transform: scale(1.07); box-shadow: 0 2px 8px rgba(0,0,0,.15); }

/* ════════════════════════════════════════════════════════════════════════
   USER DROPDOWN MENU
════════════════════════════════════════════════════════════════════════ */
.sl-dropdown {
    position: relative;
}
.sl-dropdown__menu {
    position:       absolute;
    top:            100%;
    right:          0;
    margin-top:     8px;
    background:     var(--sl-surface);
    border:         1px solid var(--sl-border);
    border-radius:  var(--sl-radius-md);
    box-shadow:     var(--sl-shadow-md);
    min-width:      180px;
    opacity:        0;
    pointer-events: none;
    transform:      translateY(-8px);
    transition:     opacity .2s ease, transform .2s ease;
    z-index:        1000;
}
.sl-dropdown__menu.is-open {
    opacity:        1;
    pointer-events: auto;
    transform:      translateY(0);
}
.sl-dropdown__item {
    display:       block;
    width:         100%;
    padding:       12px 16px;
    text-align:    left;
    border:        none;
    background:    transparent;
    color:         var(--sl-text-dark);
    font-family:   var(--sl-font);
    font-size:     14px;
    cursor:        pointer;
    transition:    background .15s ease;
}
.sl-dropdown__item:hover {
    background:    var(--sl-teal-xlight);
    color:         var(--sl-teal-dark);
}
.sl-dropdown__item:first-child {
    border-radius:  var(--sl-radius-md) var(--sl-radius-md) 0 0;
}
.sl-dropdown__item:last-child {
    border-radius:  0 0 var(--sl-radius-md) var(--sl-radius-md);
}
.sl-dropdown__divider {
    height:        1px;
    background:    var(--sl-border);
    margin:        4px 0;
}
.sl-dropdown__logout {
    color: var(--sl-red);
}
.sl-dropdown__logout:hover {
    background: #fde7e7;
    color: var(--sl-red);
}

/* ════════════════════════════════════════════════════════════════════════
   LAYOUT — two-column grid
════════════════════════════════════════════════════════════════════════ */
.sl-layout {
    display:               grid;
    grid-template-columns: 1fr 1fr;
    gap:                   20px;
    max-width:             1040px;
    margin:                0 auto;
    padding:               24px 20px 40px;
}
@media (max-width: 760px) {
    .sl-layout { grid-template-columns: 1fr; }
}

/* Shared card */
.sl-card {
    background:    var(--sl-surface);
    border-radius: var(--sl-radius-lg);
    border:        1px solid var(--sl-border);
    box-shadow:    var(--sl-shadow-sm);
    padding:       20px;
    animation:     sl-fadein .35s ease both;
}
@keyframes sl-fadein {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0);   }
}

/* Staggered column animations */
.sl-col-left  > * { animation-delay: calc(var(--i,0) * .07s); }
.sl-col-right > * { animation-delay: calc(.1s + var(--i,0) * .07s); }

/* Section heading */
.sl-section-label {
    font-size:      10.5px;
    font-weight:    700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color:          var(--sl-text-light);
    margin-bottom:  14px;
}

/* ════════════════════════════════════════════════════════════════════════
   02 — APPLICANT PROFILE CARD
════════════════════════════════════════════════════════════════════════ */
.sl-profile__header {
    display:     flex;
    align-items: flex-start;
    gap:         14px;
    margin-bottom: 16px;
}
.sl-profile__avatar {
    width:           48px;
    height:          48px;
    border-radius:   var(--sl-radius-md);
    background:      var(--sl-teal);
    color:           var(--sl-white);
    font-weight:     700;
    font-size:       18px;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}
.sl-profile__name {
    font-size:   17px;
    font-weight: 700;
    color:       var(--sl-text-dark);
    margin-bottom: 3px;
}
.sl-profile__meta {
    font-size: 12px;
    color:     var(--sl-text-light);
    display:   flex;
    flex-wrap: wrap;
    gap:       4px 10px;
    margin-bottom: 7px;
}
.sl-profile__meta span::before { content: '·'; margin-right: 10px; }
.sl-profile__meta span:first-child::before { content: none; }

/* Status badge */
.sl-badge {
    display:       inline-block;
    padding:       3px 10px;
    border-radius: var(--sl-radius-pill);
    font-size:     11.5px;
    font-weight:   600;
}
.sl-badge--review  { background: var(--sl-amber-light); color: var(--sl-amber); border: 1px solid var(--sl-amber-border); }
.sl-badge--approved{ background: var(--sl-green-light);  color: var(--sl-green);  border: 1px solid #a8e6c8; }
.sl-badge--rejected{ background: #fdeaea;                color: var(--sl-red);    border: 1px solid #f5bcbc; }

/* Info grid */
.sl-info-grid {
    display:               grid;
    grid-template-columns: 1fr 1fr;
    gap:                   12px 16px;
    border-top:            1px solid var(--sl-border);
    padding-top:           16px;
}
.sl-info-item__label {
    font-size:     11px;
    color:         var(--sl-text-light);
    margin-bottom: 3px;
    font-weight:   500;
}
.sl-info-item__value {
    font-size:   14px;
    font-weight: 600;
    color:       var(--sl-text-dark);
}

/* ════════════════════════════════════════════════════════════════════════
   03 — EVALUATION CRITERIA
════════════════════════════════════════════════════════════════════════ */
.sl-criteria { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
.sl-criterion { }
.sl-criterion__header {
    display:         flex;
    justify-content: space-between;
    align-items:     center;
    margin-bottom:   5px;
}
.sl-criterion__label { font-size: 13px; font-weight: 500; color: var(--sl-text-dark); }
.sl-criterion__score { font-size: 13px; font-weight: 700; color: var(--sl-teal); }
.sl-criterion__bar {
    height:        6px;
    border-radius: var(--sl-radius-pill);
    background:    var(--sl-bar-bg);
    overflow:      hidden;
}
.sl-criterion__fill {
    height:        100%;
    border-radius: var(--sl-radius-pill);
    background:    linear-gradient(90deg, var(--sl-teal-mid), var(--sl-teal));
    transform-origin: left;
    animation:     sl-bar-grow .7s cubic-bezier(.4,0,.2,1) both;
    animation-delay: calc(.1s + var(--bar-i, 0) * .08s);
}
@keyframes sl-bar-grow {
    from { transform: scaleX(0); }
    to   { transform: scaleX(1); }
}

/* Score summary */
.sl-score-summary {
    display:       flex;
    align-items:   center;
    justify-content:space-between;
    background:    var(--sl-teal-xlight);
    border:        1px solid #c8dedd;
    border-radius: var(--sl-radius-md);
    padding:       16px 18px;
}
.sl-score-summary__total {
    font-family:  var(--sl-font-display);
    font-size:    38px;
    color:        var(--sl-teal-dark);
    line-height:  1;
}
.sl-score-summary__sub {
    font-size:  11px;
    color:      var(--sl-text-light);
    margin-top: 3px;
}

/* Completeness ring */
.sl-ring {
    position:   relative;
    width:      60px;
    height:     60px;
    flex-shrink:0;
}
.sl-ring svg { transform: rotate(-90deg); }
.sl-ring__track { fill: none; stroke: var(--sl-bar-bg); stroke-width: 5; }
.sl-ring__fill  {
    fill:             none;
    stroke:           var(--sl-teal);
    stroke-width:     5;
    stroke-linecap:   round;
    stroke-dasharray: 157; /* 2π×25 */
    stroke-dashoffset: calc(157 - (157 * {{ $applicant['completeness'] }} / 100));
    transition:       stroke-dashoffset 1s cubic-bezier(.4,0,.2,1);
}
.sl-ring__label {
    position:   absolute;
    inset:      0;
    display:    flex;
    flex-direction: column;
    align-items:center;
    justify-content:center;
    text-align: center;
}
.sl-ring__pct   { font-size: 13px; font-weight: 700; color: var(--sl-teal-dark); }
.sl-ring__sub   { font-size: 9px;  color: var(--sl-text-light); }

/* ════════════════════════════════════════════════════════════════════════
   04 — SUBMITTED DOCUMENTS
════════════════════════════════════════════════════════════════════════ */
.sl-docs { display: flex; flex-direction: column; gap: 8px; }
.sl-doc {
    display:       flex;
    align-items:   center;
    gap:           12px;
    padding:       11px 14px;
    border-radius: var(--sl-radius-md);
    border:        1px solid var(--sl-border);
    background:    var(--sl-white);
    transition:    box-shadow var(--sl-transition), border-color var(--sl-transition);
    cursor:        default;
}
.sl-doc:hover { box-shadow: var(--sl-shadow-md); border-color: #c8dedd; }
.sl-doc__icon {
    width:           34px;
    height:          34px;
    border-radius:   var(--sl-radius-sm);
    background:      var(--sl-teal-light);
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}
.sl-doc__icon svg { width: 16px; height: 16px; color: var(--sl-teal); fill: currentColor; }
.sl-doc__info { flex: 1; min-width: 0; }
.sl-doc__name { font-size: 13px; font-weight: 600; color: var(--sl-text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sl-doc__meta { font-size: 11px; color: var(--sl-text-light); }

/* Doc status badges */
.sl-doc-badge {
    display:       inline-flex;
    align-items:   center;
    gap:           4px;
    padding:       4px 10px;
    border-radius: var(--sl-radius-pill);
    font-size:     11.5px;
    font-weight:   600;
    white-space:   nowrap;
    flex-shrink:   0;
}
.sl-doc-badge--verified { background: var(--sl-green-light); color: var(--sl-green); border: 1px solid #a8e6c8; }
.sl-doc-badge--pending  { background: var(--sl-amber-light); color: var(--sl-amber); border: 1px solid var(--sl-amber-border); }

/* ════════════════════════════════════════════════════════════════════════
   05 — RANKING PANEL
════════════════════════════════════════════════════════════════════════ */
.sl-ranking-grid {
    display:               grid;
    grid-template-columns: 1fr 1fr;
    gap:                   10px;
    margin-bottom:         0;
}
.sl-rank-box {
    border-radius: var(--sl-radius-md);
    padding:       18px 16px;
    text-align:    center;
}
.sl-rank-box--primary {
    background:  var(--sl-amber-light);
    border:      1px solid var(--sl-amber-border);
}
.sl-rank-box--secondary {
    background:  var(--sl-teal-light);
    border:      1px solid #c0dbd8;
}
.sl-rank-box__number {
    font-family:  var(--sl-font-display);
    font-size:    42px;
    line-height:  1;
    margin-bottom:4px;
}
.sl-rank-box--primary   .sl-rank-box__number { color: var(--sl-amber); }
.sl-rank-box--secondary .sl-rank-box__number { color: var(--sl-teal); }
.sl-rank-box__sub { font-size: 12px; color: var(--sl-text-mid); }

/* ════════════════════════════════════════════════════════════════════════
   06 — LEADERBOARD
════════════════════════════════════════════════════════════════════════ */
.sl-leaderboard { display: flex; flex-direction: column; gap: 0; }
.sl-lb-row {
    display:         flex;
    align-items:     center;
    justify-content: space-between;
    padding:         9px 12px;
    border-radius:   var(--sl-radius-sm);
    transition:      background var(--sl-transition);
}
.sl-lb-row:hover  { background: var(--sl-teal-xlight); }
.sl-lb-row.is-current {
    background: var(--sl-teal);
    border-radius: var(--sl-radius-md);
}
.sl-lb-row.is-current .sl-lb-name,
.sl-lb-row.is-current .sl-lb-score,
.sl-lb-row.is-current .sl-lb-rank { color: var(--sl-white) !important; }
.sl-lb-name   { font-size: 13.5px; font-weight: 500; color: var(--sl-text-dark); flex: 1; }
.sl-lb-score  { font-size: 13.5px; font-weight: 700; color: var(--sl-teal);  margin-right: 8px; }
.sl-lb-rank   { font-size: 11px;   color: var(--sl-text-light); min-width: 28px; text-align: right; }

/* ════════════════════════════════════════════════════════════════════════
   07 — FLAGS & NOTES
════════════════════════════════════════════════════════════════════════ */
.sl-flags { display: flex; flex-direction: column; gap: 8px; }
.sl-flag {
    display:       flex;
    align-items:   flex-start;
    gap:           10px;
    padding:       13px 14px;
    background:    var(--sl-amber-light);
    border:        1px solid var(--sl-amber-border);
    border-radius: var(--sl-radius-md);
}
.sl-flag__icon {
    font-size:   18px;
    flex-shrink: 0;
    margin-top:  1px;
}
.sl-flag__text { font-size: 13px; color: #7a5500; line-height: 1.5; }

/* ════════════════════════════════════════════════════════════════════════
   08 — EVALUATOR NOTES
════════════════════════════════════════════════════════════════════════ */
.sl-notes-box {
    background:    var(--sl-teal-light);
    border:        1px solid #c0dbd8;
    border-radius: var(--sl-radius-md);
    padding:       14px 16px;
    font-size:     13.5px;
    color:         var(--sl-teal-dark);
    line-height:   1.6;
}

/* ════════════════════════════════════════════════════════════════════════
   09 — ACTION BUTTONS
════════════════════════════════════════════════════════════════════════ */
.sl-actions { display: flex; flex-direction: column; gap: 8px; }
.sl-btn {
    width:          100%;
    padding:        13px;
    border-radius:  var(--sl-radius-md);
    border:         none;
    font-family:    var(--sl-font);
    font-size:      14px;
    font-weight:    600;
    cursor:         pointer;
    transition:     background var(--sl-transition), transform .15s ease,
                    box-shadow var(--sl-transition);
    text-align:     center;
}
.sl-btn:hover   { transform: translateY(-1px); box-shadow: var(--sl-shadow-md); }
.sl-btn:active  { transform: translateY(0); }
.sl-btn--approve {
    background: var(--sl-teal);
    color:      var(--sl-white);
}
.sl-btn--approve:hover { background: var(--sl-teal-mid); }
.sl-btn--flag {
    background: var(--sl-white);
    color:      var(--sl-text-dark);
    border:     1.5px solid var(--sl-border);
}
.sl-btn--flag:hover { border-color: var(--sl-amber); color: var(--sl-amber); }
.sl-btn--reject {
    background: var(--sl-white);
    color:      var(--sl-text-dark);
    border:     1.5px solid var(--sl-border);
}
.sl-btn--reject:hover { border-color: var(--sl-red); color: var(--sl-red); }

/* ════════════════════════════════════════════════════════════════════════
   10 — SCHOLARSHIP SLOTS
════════════════════════════════════════════════════════════════════════ */
.sl-slots__header {
    display:         flex;
    justify-content: space-between;
    align-items:     baseline;
    margin-bottom:   8px;
}
.sl-slots__name  { font-size: 12.5px; font-weight: 600; color: var(--sl-text-dark); }
.sl-slots__count { font-size: 13px; font-weight: 700; color: var(--sl-teal); }
.sl-slots__bar {
    height:        8px;
    background:    var(--sl-bar-bg);
    border-radius: var(--sl-radius-pill);
    overflow:      hidden;
    margin-bottom: 7px;
}
.sl-slots__fill {
    height:        100%;
    border-radius: var(--sl-radius-pill);
    background:    linear-gradient(90deg, var(--sl-teal-mid), var(--sl-teal));
    animation:     sl-bar-grow .9s cubic-bezier(.4,0,.2,1) .2s both;
}
.sl-slots__footer { font-size: 11.5px; color: var(--sl-text-light); }

/* ── Divider ── */
.sl-divider { border: none; border-top: 1px solid var(--sl-border); margin: 0; }

/* ── Spacing utility ── */
.sl-mt { margin-top: 16px; }
</style>
</head>
<body>


{{-- ════════════════════════════════════════════════════════════════════════
     HTML
════════════════════════════════════════════════════════════════════════ --}}
<div class="sl-root">

    {{-- 01 — NAV BAR --}}
    <nav class="sl-nav">
        <div class="sl-nav__brand">
            <div class="sl-nav__logo">🎓</div>
            ScholarLink
        </div>
        <div class="sl-nav__right">
            <span class="sl-nav__role-badge">{{ $evaluator['role'] }}</span>
            <div class="sl-dropdown">
                <div class="sl-nav__avatar" id="userMenuBtn" title="Open menu" style="cursor: pointer;">
                    {{ $evaluator['initials'] }}
                </div>
                <div class="sl-dropdown__menu" id="userMenu">
                    <button class="sl-dropdown__item" onclick="window.location.href='{{ route('profile.setup') }}'">
                        Profile Settings
                    </button>
                    <div class="sl-dropdown__divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="sl-dropdown__item sl-dropdown__logout">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN LAYOUT --}}
    <div class="sl-layout">

        {{-- ══════════════════════════════════════════════════
             LEFT COLUMN
        ══════════════════════════════════════════════════ --}}
        <div class="sl-col-left" style="display:flex;flex-direction:column;gap:16px;">

            {{-- 02 — APPLICANT PROFILE --}}
            <div class="sl-card" style="--i:0">
                <div class="sl-profile__header">
                    <div class="sl-profile__avatar">{{ $applicant['initials'] }}</div>
                    <div>
                        <div class="sl-profile__name">{{ $applicant['name'] }}</div>
                        <div class="sl-profile__meta">
                            <span>App ID: {{ $applicant['app_id'] }}</span>
                            <span>Submitted {{ $applicant['submitted_at'] }}</span>
                            <span>{{ $applicant['track'] }}</span>
                        </div>
                        @php
                            $badgeClass = match($applicant['status']) {
                                'Approved' => 'sl-badge--approved',
                                'Rejected' => 'sl-badge--rejected',
                                default    => 'sl-badge--review',
                            };
                        @endphp
                        <span class="sl-badge {{ $badgeClass }}">{{ $applicant['status'] }}</span>
                    </div>
                </div>

                <div class="sl-info-grid">
                    <div class="sl-info-item">
                        <div class="sl-info-item__label">School</div>
                        <div class="sl-info-item__value">{{ $applicant['school'] }}</div>
                    </div>
                    <div class="sl-info-item">
                        <div class="sl-info-item__label">Program</div>
                        <div class="sl-info-item__value">{{ $applicant['program'] }}</div>
                    </div>
                    <div class="sl-info-item">
                        <div class="sl-info-item__label">Year Level</div>
                        <div class="sl-info-item__value">{{ $applicant['year_level'] }}</div>
                    </div>
                    <div class="sl-info-item">
                        <div class="sl-info-item__label">Region</div>
                        <div class="sl-info-item__value">{{ $applicant['region'] }}</div>
                    </div>
                    <div class="sl-info-item">
                        <div class="sl-info-item__label">GWA (SHS)</div>
                        <div class="sl-info-item__value">{{ $applicant['gwa'] }}</div>
                    </div>
                    <div class="sl-info-item">
                        <div class="sl-info-item__label">Scholarship</div>
                        <div class="sl-info-item__value">{{ $applicant['scholarship'] }}</div>
                    </div>
                </div>
            </div>{{-- /profile card --}}

            {{-- 03 — EVALUATION CRITERIA --}}
            <div class="sl-card" style="--i:1">
                <div class="sl-section-label">Evaluation Criteria</div>

                <div class="sl-criteria">
                    @foreach ($criteria as $i => $crit)
                        <div class="sl-criterion">
                            <div class="sl-criterion__header">
                                <span class="sl-criterion__label">{{ $crit['label'] }}</span>
                                <span class="sl-criterion__score">{{ $crit['score'] }}</span>
                            </div>
                            <div class="sl-criterion__bar">
                                <div class="sl-criterion__fill"
                                     style="width:{{ $crit['score'] }}%; --bar-i:{{ $i }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Score summary --}}
                <div class="sl-score-summary">
                    <div>
                        <div class="sl-score-summary__total">{{ number_format($applicant['total_score'], 1) }}</div>
                        <div class="sl-score-summary__sub">out of 100<br>Weighted Total Score</div>
                    </div>
                    {{-- Completeness ring --}}
                    <div class="sl-ring">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <circle class="sl-ring__track" cx="30" cy="30" r="25"/>
                            <circle class="sl-ring__fill"  cx="30" cy="30" r="25"/>
                        </svg>
                        <div class="sl-ring__label">
                            <span class="sl-ring__pct">{{ $applicant['completeness'] }}%</span>
                            <span class="sl-ring__sub">Complete</span>
                        </div>
                    </div>
                </div>
            </div>{{-- /criteria --}}

            {{-- 04 — SUBMITTED DOCUMENTS --}}
            <div class="sl-card" style="--i:2">
                <div class="sl-section-label">Submitted Documents</div>
                <div class="sl-docs">
                    @foreach ($documents as $doc)
                        <div class="sl-doc">
                            {{-- File icon --}}
                            <div class="sl-doc__icon">
                                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13 2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7l-4-5zm-1 1l3 4h-3V3zM5 4h6v4h4v8H5V4z"/>
                                </svg>
                            </div>
                            <div class="sl-doc__info">
                                <div class="sl-doc__name">{{ $doc['name'] }}</div>
                                <div class="sl-doc__meta">{{ $doc['type'] }} · {{ $doc['size'] }}</div>
                            </div>
                            @if ($doc['status'] === 'verified')
                                <span class="sl-doc-badge sl-doc-badge--verified">✓ Verified</span>
                            @else
                                <span class="sl-doc-badge sl-doc-badge--pending">
                                    Pending {{ !empty($doc['pending_count']) ? $doc['pending_count'] : '' }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>{{-- /documents --}}

        </div>{{-- /col-left --}}


        {{-- ══════════════════════════════════════════════════
             RIGHT COLUMN
        ══════════════════════════════════════════════════ --}}
        <div class="sl-col-right" style="display:flex;flex-direction:column;gap:16px;">

            {{-- 05 — RANKING --}}
            <div class="sl-card" style="--i:0">
                <div class="sl-section-label">Ranking</div>
                <div class="sl-ranking-grid">
                    <div class="sl-rank-box sl-rank-box--primary">
                        <div class="sl-rank-box__number">{{ $ranking['rank'] }}</div>
                        <div class="sl-rank-box__sub">of {{ number_format($ranking['total_applicants']) }} applicants</div>
                    </div>
                    <div class="sl-rank-box sl-rank-box--secondary">
                        <div class="sl-rank-box__number">{{ $ranking['region_rank'] }}</div>
                        <div class="sl-rank-box__sub">in {{ $ranking['region'] }}</div>
                    </div>
                </div>
            </div>

            {{-- 06 — APPLICANTS LEADERBOARD --}}
            <div class="sl-card" style="--i:1">
                <div class="sl-section-label">Applicants</div>
                <div class="sl-leaderboard">
                    @foreach ($leaderboard as $lb)
                        <div class="sl-lb-row {{ $lb['current'] ? 'is-current' : '' }}">
                            <span class="sl-lb-name">{{ $lb['name'] }}</span>
                            <span class="sl-lb-score">{{ $lb['score'] }}</span>
                            <span class="sl-lb-rank">#{{ $lb['rank'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 07 — FLAGS & NOTES --}}
            @if (!empty($flags))
            <div class="sl-card" style="--i:2">
                <div class="sl-section-label">Flags &amp; Notes</div>
                <div class="sl-flags">
                    @foreach ($flags as $flag)
                        <div class="sl-flag">
                            <span class="sl-flag__icon">🚩</span>
                            <p class="sl-flag__text">{{ $flag }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 08 — EVALUATOR NOTES --}}
            @if (!empty($notes))
            <div class="sl-card" style="--i:3">
                <div class="sl-section-label">Evaluator Notes</div>
                <div class="sl-notes-box">{{ $notes }}</div>
            </div>
            @endif

            {{-- 09 — ACTION BUTTONS --}}
            <div class="sl-card" style="--i:4">
                <div class="sl-actions">
                    <button class="sl-btn sl-btn--approve"
                            onclick="ScholarDash.approve()">
                        Approve Application
                    </button>
                    <button class="sl-btn sl-btn--flag"
                            onclick="ScholarDash.flagReview()">
                        Flag for Special Review
                    </button>
                    <button class="sl-btn sl-btn--reject"
                            onclick="ScholarDash.reject()">
                        Reject Application
                    </button>
                </div>
            </div>

            {{-- 10 — SCHOLARSHIP SLOTS --}}
            <div class="sl-card" style="--i:5">
                <div class="sl-section-label">Scholarship Slots</div>
                <div class="sl-slots__header">
                    <span class="sl-slots__name">{{ $slots['scholarship'] }}</span>
                    <span class="sl-slots__count">
                        {{ $slots['filled'] }} / {{ $slots['total'] }} slots filled
                    </span>
                </div>
                <div class="sl-slots__bar">
                    <div class="sl-slots__fill"
                         style="width:{{ round($slots['filled'] / $slots['total'] * 100) }}%">
                    </div>
                </div>
                <div class="sl-slots__footer">
                    {{ $slots['remaining'] }} slots remaining · Closes {{ $slots['closes_at'] }}
                </div>
            </div>

        </div>{{-- /col-right --}}

    </div>{{-- /layout --}}
</div>{{-- /sl-root --}}


{{-- ════════════════════════════════════════════════════════════════════════
     JAVASCRIPT — ScholarDash namespace
════════════════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /**
     * ScholarDash — action handlers for the evaluator dashboard.
     *
     * Replace the console.log stubs with real fetch() / Axios calls
     * to your Laravel API routes, e.g.:
     *
     *   axios.post('/api/applications/{{ $applicant['app_id'] }}/approve')
     *        .then(r => { ... })
     *        .catch(e => { ... });
     */
    window.ScholarDash = {

        /**
         * Approve the application.
         * POST /api/applications/{id}/approve
         */
        approve() {
            if (!confirm('Approve this application? This action will be logged.')) return;
            console.log('[ScholarDash] Approve → {{ $applicant['app_id'] }}');
            // TODO: axios.post('/api/applications/{{ $applicant['app_id'] }}/approve')
            this._toast('Application approved!', 'success');
        },

        /**
         * Flag for special review.
         * POST /api/applications/{id}/flag
         */
        flagReview() {
            const reason = prompt('Briefly describe the reason for flagging (optional):');
            if (reason === null) return; // cancelled
            console.log('[ScholarDash] Flag → {{ $applicant['app_id'] }}', reason);
            // TODO: axios.post('/api/applications/{{ $applicant['app_id'] }}/flag', { reason })
            this._toast('Application flagged for special review.', 'warning');
        },

        /**
         * Reject the application.
         * POST /api/applications/{id}/reject
         */
        reject() {
            if (!confirm('Reject this application? This cannot be undone without admin override.')) return;
            console.log('[ScholarDash] Reject → {{ $applicant['app_id'] }}');
            // TODO: axios.post('/api/applications/{{ $applicant['app_id'] }}/reject')
            this._toast('Application rejected.', 'error');
        },

        /**
         * Simple toast notification.
         * @param {string} message
         * @param {'success'|'warning'|'error'} type
         */
        _toast(message, type = 'success') {
            const colors = {
                success: '#27a96c',
                warning: '#f5a623',
                error:   '#e05050',
            };
            const t = document.createElement('div');
            Object.assign(t.style, {
                position:     'fixed',
                bottom:       '24px',
                right:        '24px',
                padding:      '12px 20px',
                borderRadius: '10px',
                background:   colors[type] || colors.success,
                color:        '#fff',
                fontFamily:   'DM Sans, sans-serif',
                fontSize:     '14px',
                fontWeight:   '600',
                boxShadow:    '0 4px 18px rgba(0,0,0,.18)',
                zIndex:       '9999',
                opacity:      '0',
                transform:    'translateY(10px)',
                transition:   'opacity .25s ease, transform .25s ease',
            });
            t.textContent = message;
            document.body.appendChild(t);

            // Animate in
            requestAnimationFrame(() => requestAnimationFrame(() => {
                t.style.opacity   = '1';
                t.style.transform = 'translateY(0)';
            }));

            // Fade out after 3 s
            setTimeout(() => {
                t.style.opacity   = '0';
                t.style.transform = 'translateY(10px)';
                setTimeout(() => t.remove(), 300);
            }, 3000);
        },
    };
})();

/**
 * User dropdown menu toggle
 */
(function() {
    const btn = document.getElementById('userMenuBtn');
    const menu = document.getElementById('userMenu');

    if (btn && menu) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('is-open');
        });

        document.addEventListener('click', () => {
            menu.classList.remove('is-open');
        });

        menu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
})();

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

<!-- Session Timeout Modal -->
<div id="session-timeout-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 32px; max-width: 400px; width: 90%; box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15); animation: slideUp 0.3s ease-out;">
        <div style="text-align: center; margin-bottom: 20px; font-size: 48px;">⏱️</div>
        <h2 style="font-size: 20px; font-weight: 700; color: #1a2e2c; margin-bottom: 12px; text-align: center;">Session Expiring Soon</h2>
        <p style="font-size: 14px; color: #4a6460; line-height: 1.6; margin-bottom: 24px; text-align: center;">You've been inactive for 13 minutes. Your session will expire in 2 minutes for your security. Please click "Stay Logged In" to continue.</p>
        <div style="display: flex; gap: 12px;">
            <button onclick="if (window.sessionTracker) window.sessionTracker.stayLoggedIn(); else location.reload();" style="flex: 1; padding: 12px 16px; background: linear-gradient(135deg, #1a6b63, #2a8a80); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Stay Logged In</button>
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


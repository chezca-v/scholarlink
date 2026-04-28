@extends('layouts.applicant')
@section('title', 'ScholarLink - Saved Scholarships')

@push('styles')
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
.sb-nav-item.active{background:#FDF8EC;color:var(--teal);font-weight:700;border-left-color:var(--teal);}
.sb-badge{margin-left:auto;background:#E8A838;color:#0F4C5C;font-size:10px;font-weight:700;border-radius:20px;padding:1px 7px;min-width:20px;text-align:center;}
.sb-badge-transparent{margin-left:auto;color:#E8A838;font-size:11px;font-weight:700;}
.sb-spacer{flex:1;}
.sb-user{display:flex;align-items:center;gap:10px;padding:12px 16px;margin:0 10px 4px;background:var(--light-green);border:2px solid rgba(15,76,92,0.2);border-radius:14px;}
.sb-av{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);color:#F9D679;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-name{font-size:12.5px;font-weight:600;color:var(--ink);}
.sb-sub{font-size:11px;color:var(--slate);}

/* ── MAIN CONTENT (SAVED) ── */
.main{flex:1;padding:24px 28px 40px;min-width:0;overflow-y:auto;display:flex;flex-direction:column;}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 24px;
}

.page-title-area {
    display: flex;
    flex-direction: column;
}

.page-eyebrow {
    font-size: 11px;
    font-weight: 700;
    color: #E8A838;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
}

.page-title {
    font-family: 'Fraunces', serif;
    font-size: 28px;
    font-weight: 900;
    color: var(--teal);
    line-height: 1.1;
}

.save-count {
    font-size: 13px;
    font-weight: 500;
    color: var(--slate);
    margin-bottom: 4px;
}

/* Grid Layout */
.scholarship-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    gap: 20px;
}

/* Card */
.s-card {
    background: #fff;
    border: 1px solid var(--mist);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 4px 12px rgba(15, 76, 92, 0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}

.s-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15, 76, 92, 0.06);
}

.c-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.c-provider {
    font-size: 10px;
    font-weight: 700;
    color: var(--slate);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.c-badges {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid transparent;
}

.status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-badge.open {
    background: #e0f8e9;
    color: #15803d;
    border-color: #bbf7d0;
}
.status-badge.open::before {
    background: #15803d;
}

.status-badge.closing {
    background: #FEF6DF;
    color: #a16207;
    border-color: #fef08a;
}
.status-badge.closing::before {
    background: #d97706;
}

.btn-delete {
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--mist);
    background: #fff;
    border-radius: 6px;
    color: var(--slate);
    cursor: pointer;
    transition: all 0.2s;
}

.btn-delete:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: #fef2f2;
}

.c-title {
    font-family: 'Fraunces', serif;
    font-size: 18px;
    font-weight: 800;
    color: var(--teal);
    line-height: 1.3;
    margin-bottom: 8px;
}

.c-meta {
    font-size: 12px;
    color: var(--slate);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
}

.c-tags {
    display: flex;
    gap: 6px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.c-tag {
    background: var(--light-green);
    color: var(--teal-light);
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}

.c-match-section {
    margin-top: auto;
}

.match-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 6px;
}

.match-label {
    font-size: 11px;
    color: var(--slate);
    font-weight: 500;
}

.match-pct {
    font-family: 'Fraunces', serif;
    font-size: 15px;
    font-weight: 800;
    color: #E8A838;
}

.match-bar-bg {
    height: 3px;
    background: var(--mist);
    border-radius: 2px;
    margin-bottom: 16px;
    overflow: hidden;
}

.match-bar-fill {
    height: 100%;
    background: #E8A838;
    border-radius: 2px;
}

.btn-apply {
    display: block;
    width: 100%;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
}

.btn-apply.primary {
    background: var(--teal);
    color: #fff;
    border: none;
}

.btn-apply.primary:hover {
    background: var(--teal-hover);
}

.btn-apply.warning {
    background: #F9D679;
    color: var(--teal);
    border: none;
}

.btn-apply.warning:hover {
    background: #f5c853;
}

.s-card.browse-more {
    background: transparent;
    border: 1.5px dashed rgba(15, 76, 92, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px 20px;
}

.s-card.browse-more:hover {
    background: rgba(240, 250, 250, 0.5);
    border-color: rgba(15, 76, 92, 0.4);
    box-shadow: none;
}

.bm-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid var(--mist);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    color: var(--teal);
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.bm-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--teal);
    margin-bottom: 4px;
}

.bm-sub {
    font-size: 12px;
    color: var(--slate);
    margin-bottom: 16px;
}

.btn-outline {
    display: inline-block;
    background: #fff;
    border: 1px solid var(--teal);
    color: var(--teal);
    font-size: 12px;
    font-weight: 700;
    padding: 8px 20px;
    border-radius: 20px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-outline:hover {
    background: var(--light-green);
}
</style>
@endpush

@section('content')
<div class="main-inner">
    <div class="header-row">
        <div class="page-title-area">
            <span class="page-eyebrow">BOOKMARKS</span>
            <h1 class="page-title">Saved Scholarships</h1>
        </div>
        <div class="save-count">
            {{ isset($savedScholarships) ? $savedScholarships->count() : 0 }} scholarships saved
        </div>
    </div>

    <div class="scholarship-grid">
        @if(isset($savedScholarships))
            @foreach($savedScholarships as $saved)
                @php
                    $scholarship = $saved->scholarship;
                    // Mock match percentage (e.g. from 70 to 98)
                    $matchPct = 70 + ($scholarship->id % 29); 
                    
                    // Determine deadline proximity
                    $deadlineDate = \Carbon\Carbon::parse($scholarship->deadline);
                    $isClosingSoon = $deadlineDate->isFuture() && $deadlineDate->diffInDays(now()) < 14;
                    $isOpen = $scholarship->status === 'open';
                @endphp
                <div class="s-card">
                    <div class="c-header">
                        <div class="c-provider">{{ Str::limit($scholarship->provider_name, 25) }}</div>
                        <div class="c-badges">
                            @if($isClosingSoon && $isOpen)
                                <span class="status-badge closing">Closing</span>
                            @elseif($isOpen)
                                <span class="status-badge open">Open</span>
                            @else
                                <span class="status-badge" style="background:#f1f5f9;color:#64748b;border-color:#e2e8f0;">Closed</span>
                            @endif
                            
                            <form action="{{ route('scholarships.unsave', $scholarship->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Remove">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="c-title">{{ $scholarship->name }}</div>
                    
                    <div class="c-meta">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $deadlineDate->format('M j, Y') }} &middot; {{ $scholarship->slots }} slots
                    </div>

                    <div class="c-tags">
                        @if($scholarship->gpa_requirement)
                            <span class="c-tag">GPA {{ number_format($scholarship->gpa_requirement, 2) }}+</span>
                        @endif
                        @if($scholarship->tags)
                            @foreach(explode(',', $scholarship->tags) as $tag)
                                <span class="c-tag">{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            <span class="c-tag">STEM</span>
                        @endif
                    </div>

                    <div class="c-match-section">
                        <div class="match-row">
                            <span class="match-label">Match</span>
                            <span class="match-pct">{{ $matchPct }}%</span>
                        </div>
                        <div class="match-bar-bg">
                            <div class="match-bar-fill" style="width: {{ $matchPct }}%;"></div>
                        </div>

                        @if($isClosingSoon && $isOpen)
                            <a href="{{ route('scholarships.show', $scholarship->id) }}" class="btn-apply warning">Apply &mdash; Closing Soon!</a>
                        @elseif($isOpen)
                            <a href="{{ route('scholarships.show', $scholarship->id) }}" class="btn-apply primary">Apply Now</a>
                        @else
                            <a href="{{ route('scholarships.show', $scholarship->id) }}" class="btn-apply" style="background:#f1f5f9;color:#64748b;">View Details</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Browse More Card -->
        <div class="s-card browse-more">
            <div>
                <div class="bm-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <div class="bm-title">Browse more</div>
                <div class="bm-sub">Discover 120+ scholarships</div>
                <a href="{{ route('scholarships.index') }}" class="btn-outline">Browse All</a>
            </div>
        </div>
    </div>
  </div>
@endsection


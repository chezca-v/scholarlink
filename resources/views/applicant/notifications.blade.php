@extends('layouts.applicant')
@section('title', 'ScholarLink - Notifications')

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
.logo-box{width:32px;height:32px;object-fit:contain;filter:drop-shadow(0 4px 10px rgba(15,76,92,0.18));}
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
    padding: 18px 20px;
    border-bottom: 1px solid var(--mist);
    border-radius: 10px;
    margin-bottom: 4px;
    position: relative;
    transition: background 0.15s;
}

.n-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.n-item.unread {
    background: #f0fafa;
    border-left: 4px solid var(--teal);
    padding-left: 16px;
}

.n-item.read {
    background: #fff;
    border-left: 4px solid transparent;
    padding-left: 16px;
    opacity: 0.85;
}

.n-item:hover {
    background: #e8f5f5;
    opacity: 1;
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
@endpush

@section('content')
<div class="main-inner">
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

        <div class="filters-row" id="notif-filters">
            <button type="button" class="filter-pill active" data-filter="all">All ({{ $counts['all'] }})</button>
            <button type="button" class="filter-pill" data-filter="applications">Applications ({{ $counts['apps'] }})</button>
            <button type="button" class="filter-pill" data-filter="documents">Documents ({{ $counts['docs'] }})</button>
            <button type="button" class="filter-pill" data-filter="matches">Matches ({{ $counts['matches'] }})</button>
        </div>

        <div class="notif-list" x-data="{ 
            open: false, 
            activeNotif: {title: '', body: '', time: ''},
            showNotif(title, body, time) {
                this.activeNotif = {title, body, time};
                this.open = true;
            }
        }">
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

                @php
                    // Determine context URL for this notification
                    $contextUrl = null;
                    if ($notif->related_id) {
                        if (str_contains($titleLower, 'application') || str_contains($titleLower, 'approved') || str_contains($titleLower, 'rejected') || str_contains($titleLower, 'revision') || str_contains($titleLower, 'review')) {
                            $contextUrl = route('applications.track', $notif->related_id);
                        } elseif (str_contains($titleLower, 'document') || str_contains($titleLower, 'expiring')) {
                            $contextUrl = route('applicant.documents.index');
                        } elseif (str_contains($titleLower, 'match') || str_contains($titleLower, 'congratulations')) {
                            $contextUrl = route('scholarships.index');
                        }
                    }
                    $markReadUrl = route('notifications.markRead', $notif->id);
                @endphp
                <div class="n-item {{ $isUnread ? 'unread' : 'read' }}" data-filter-type="{{ str_contains($titleLower, 'application') || str_contains($titleLower, 'approved') || str_contains($titleLower, 'rejected') ? 'applications' : (str_contains($titleLower, 'document') ? 'documents' : (str_contains($titleLower, 'match') || str_contains($titleLower, 'congratulations') ? 'matches' : 'other')) }}">
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
                    <div class="n-action" style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                        @if($contextUrl)
                            {{-- Mark read + navigate in one click --}}
                            <form action="{{ $markReadUrl }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ $contextUrl }}">
                                <button type="submit" class="btn-action {{ $btnClass }}">{{ $actionText }}</button>
                            </form>
                        @else
                            <button onclick="markAndShow('{{ $markReadUrl }}', '{{ addslashes($notif->title) }}', '{{ addslashes($notif->body) }}', '{{ $notif->created_at->diffForHumans() }}')" class="btn-action {{ $btnClass }}">{{ $actionText }}</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="n-item" style="justify-content: center; padding: 40px 0; color: var(--slate);">
                    No notifications available.
                </div>
            @endforelse

            {{-- Notification Modal --}}
            <div x-show="open" 
                 class="modal-backdrop" 
                 style="display: none; position: fixed; inset: 0; background: rgba(15, 76, 92, 0.4); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;"
                 x-transition.opacity>
                <div @click.away="open = false" 
                     class="modal-content" 
                     style="background: #fff; border-radius: 20px; width: 90%; max-width: 480px; padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid var(--mist);"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <h2 x-text="activeNotif.title" style="font-family: 'Fraunces', serif; font-size: 24px; font-weight: 700; color: var(--teal); line-height: 1.2;"></h2>
                        <button @click="open = false" style="background: var(--cloud); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--slate);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <p x-text="activeNotif.body" style="font-size: 15px; color: var(--ink); line-height: 1.6; margin-bottom: 24px;"></p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span x-text="activeNotif.time" style="font-size: 12px; color: var(--slate); font-weight: 500;"></span>
                        <button @click="open = false" class="btn-action primary" style="width: auto; padding: 10px 24px;">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>



  <script>
      // Open modal and optionally mark read (for notifications without a context URL)
      function markAndShow(markReadUrl, title, body, time) {
          // Fire mark-read in background
          fetch(markReadUrl, {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
          }).catch(() => {}); // best-effort

          // Show the Alpine modal
          const notifList = document.querySelector('[x-data]');
          if (notifList && notifList.__x) {
              notifList.__x.$data.showNotif(title, body, time);
          } else {
              // Fallback: dispatch a custom event picked up by Alpine
              document.dispatchEvent(new CustomEvent('open-notif-modal', { detail: { title, body, time } }));
          }
      }

      document.addEventListener('DOMContentLoaded', function() {
          const filters = document.querySelectorAll('#notif-filters .filter-pill');
          const items = document.querySelectorAll('.n-item[data-filter-type]');

          filters.forEach(filter => {
              filter.addEventListener('click', function() {
                  filters.forEach(f => f.classList.remove('active'));
                  this.classList.add('active');

                  const type = this.dataset.filter;

                  items.forEach(item => {
                      if (type === 'all') {
                          item.style.display = 'flex';
                          return;
                      }
                      item.style.display = (item.dataset.filterType === type) ? 'flex' : 'none';
                  });
              });
          });
      });
  </script>
@endsection

@extends('layouts.superadmin')

@section('page_title', 'System Logs')
@section('topnav_title', 'System Logs')
@section('topnav_subtitle', '/superadmin/logs')

@section('topnav_actions')
  <a href="{{ route('superadmin.logs.export') }}"
     class="btn btn-outline btn-sm"
     style="border-color: rgba(255,255,255,.3); color: rgba(255,255,255,.8); background: rgba(255,255,255,.08);">
    ⬇ Export CSV
  </a>
@endsection

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Home</span><span class="sep">/</span><span class="current">System Logs</span>
  </div>

  <!-- FILTER BAR -->
  <div class="card" style="padding: 14px 20px; margin-bottom: 16px;">
    <form method="GET" action="{{ route('superadmin.logs.index') }}" id="logFilterForm">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div class="search-wrap" style="flex: 1; min-width: 200px;">
          <span class="search-icon">🔍</span>
          <input class="form-input" name="search" placeholder="Search by user, action, IP…"
                 value="{{ request('search') }}" style="padding-left: 36px;">
        </div>
        <select class="form-select" name="action" style="width: auto;">
          <option value="">All Actions</option>
          @foreach($actions as $act)
            <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
          @endforeach
        </select>
        <select class="form-select" name="user_role" style="width: auto;">
          <option value="">All Users</option>
          @foreach($roles as $role)
            @if($role)
              <option value="{{ $role }}" {{ request('user_role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
            @endif
          @endforeach
        </select>
        <div style="display: flex; align-items: center; gap: 6px;">
          <input class="form-input" type="date" name="date_from" style="width: auto;"
                 value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
          <span style="color: var(--muted);">→</span>
          <input class="form-input" type="date" name="date_to" style="width: auto;"
                 value="{{ request('date_to', now()->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
        <a href="{{ route('superadmin.logs.index') }}" class="btn btn-outline btn-sm">Reset</a>
      </div>

      <!-- QUICK FILTER PILLS -->
      <div style="margin-top: 10px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
        <span style="font-size: 11px; color: var(--slate);">Quick filter:</span>
        <a href="{{ route('superadmin.logs.index') }}"
           class="badge {{ !request('quick') ? 'teal' : 'gray' }}"
           style="cursor: pointer; text-decoration: none;">All</a>
        <a href="{{ route('superadmin.logs.index', ['quick' => 'login']) }}"
           class="badge {{ request('quick') === 'login' ? 'teal' : 'gray' }}"
           style="cursor: pointer; text-decoration: none;">Login/Logout</a>
        <a href="{{ route('superadmin.logs.index', ['quick' => 'errors']) }}"
           class="badge {{ request('quick') === 'errors' ? 'teal' : 'gray' }}"
           style="cursor: pointer; text-decoration: none;">Errors</a>
        <a href="{{ route('superadmin.logs.index', ['quick' => 'data_changes']) }}"
           class="badge {{ request('quick') === 'data_changes' ? 'teal' : 'gray' }}"
           style="cursor: pointer; text-decoration: none;">Data Changes</a>
        <a href="{{ route('superadmin.logs.index', ['quick' => 'fraud']) }}"
           class="badge red"
           style="cursor: pointer; text-decoration: none;">Fraud Flags</a>
      </div>
    </form>
  </div>

  <!-- LOG TABLE -->
  <div class="card" style="padding: 0;">
    <div style="padding: 16px 20px; border-bottom: 1.5px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
      <div class="section-title">
        Activity Log
        <small>Showing {{ $logs->total() }} entries</small>
      </div>
      <div style="display: flex; gap: 8px;">
        <span class="badge green">✔ {{ $successCount }} success</span>
        <span class="badge red">✖ {{ $errorCount }} error</span>
        <span class="badge yellow">⚠ {{ $warnCount }} warn</span>
      </div>
    </div>

    <div style="padding: 0 20px;">
      @forelse($logs as $log)
      <div class="log-entry">
        <div class="log-icon" style="background: {{ $log->icon_bg ?? '#E8F8F0' }};">
          {{ $log->icon ?? '✅' }}
        </div>
        <div style="flex: 1;">
          <div class="log-action">{{ $log->action_label }}</div>
          <div class="log-meta">
            Action:
            <span class="badge {{ $log->badge_color ?? 'teal' }}"
                  style="padding: 1px 6px; font-size: 10px;">
              {{ $log->action_type }}
            </span>
            @if($log->ip_address)
              · IP: {{ $log->ip_address }}
            @endif
            @if($log->extra_meta)
              · {{ $log->extra_meta }}
            @endif
          </div>
        </div>
        <div class="log-time">
          {{ $log->created_at->diffForHumans() }}<br>
          <span style="font-size: 10px; color: #ccc;">
            {{ $log->created_at->format('M d, g:i A') }}
          </span>
        </div>
      </div>
      @empty
      <div style="text-align: center; padding: 40px; color: var(--muted);">
        No log entries found matching your filters.
      </div>
      @endforelse
    </div>

    <div class="pagination">
      <span class="info">
        Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} entries
      </span>
      <div class="page-btns">
        @if($logs->currentPage() > 1)
          <button class="page-btn" onclick="window.location='{{ $logs->previousPageUrl() }}'">‹</button>
        @endif
        @foreach($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
          <button class="page-btn {{ $logs->currentPage() === $page ? 'active' : '' }}"
                  onclick="window.location='{{ $url }}'">
            {{ $page }}
          </button>
        @endforeach
        @if($logs->hasMorePages())
          <button class="page-btn" onclick="window.location='{{ $logs->nextPageUrl() }}'">›</button>
        @endif
      </div>
    </div>
  </div>
@endsection

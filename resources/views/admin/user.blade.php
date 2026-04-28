@extends('layouts.admin')

@section('page_title', 'User Management')
@section('topnav_title', 'User Management')
@section('topnav_subtitle', '/admin/users · ' . (auth()->user()->organization->name ?? 'CHED NCR'))

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Admin</span><span class="sep">/</span><span class="current">User Management</span>
  </div>

  <!-- STATS -->
  <div class="grid-4" style="margin-bottom: 20px;">
    <div class="stat-card">
      <div class="label">Total Users</div>
      <div class="value">{{ number_format($totalUsers) }}</div>
      <div class="delta neutral">In {{ auth()->user()->organization->name ?? 'your org' }}</div>
    </div>
    <div class="stat-card">
      <div class="label">Applicants</div>
      <div class="value">{{ number_format($totalApplicants) }}</div>
      <div class="delta up">▲ {{ $newThisWeek }} this week</div>
    </div>
    <div class="stat-card">
      <div class="label">Evaluators</div>
      <div class="value">{{ $totalEvaluators }}</div>
      <div class="delta neutral">{{ $activeEvaluators }} active now</div>
    </div>
    <div class="stat-card">
      <div class="label">Deactivated</div>
      <div class="value">{{ $totalDeactivated }}</div>
      <div class="delta down">Can restore</div>
    </div>
  </div>

  <!-- ROLE FILTER TABS + CREATE BUTTON -->
  <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap;">
    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
      <a href="{{ route('admin.users.index') }}"
         class="badge {{ !request('role') ? 'teal' : 'gray' }}"
         style="cursor: pointer; padding: 6px 14px; font-size: 12px; text-decoration: none;">
        All ({{ number_format($totalUsers) }})
      </a>
      <a href="{{ route('admin.users.index', ['role' => 'applicant']) }}"
         class="badge {{ request('role') === 'applicant' ? 'teal' : 'gray' }}"
         style="cursor: pointer; padding: 6px 14px; font-size: 12px; text-decoration: none;">
        Applicants ({{ number_format($totalApplicants) }})
      </a>
      <a href="{{ route('admin.users.index', ['role' => 'evaluator']) }}"
         class="badge {{ request('role') === 'evaluator' ? 'teal' : 'gray' }}"
         style="cursor: pointer; padding: 6px 14px; font-size: 12px; text-decoration: none;">
        Evaluators ({{ $totalEvaluators }})
      </a>
      <a href="{{ route('admin.users.index', ['status' => 'deactivated']) }}"
         class="badge {{ request('status') === 'deactivated' ? 'teal' : 'gray' }}"
         style="cursor: pointer; padding: 6px 14px; font-size: 12px; text-decoration: none;">
        Deactivated ({{ $totalDeactivated }})
      </a>
    </div>
    <button class="btn btn-primary"
            onclick="document.getElementById('createEvalModal').classList.add('open')">
      + Create Evaluator
    </button>
  </div>

  <!-- FILTER BAR -->
  <div class="card" style="padding: 12px 16px; margin-bottom: 12px;">
    <form method="GET" action="{{ route('admin.users.index') }}">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div class="search-wrap" style="flex: 1; min-width: 200px;">
          <span class="si">🔍</span>
          <input class="form-input" name="search" placeholder="Search by name, email, ID…"
                 value="{{ request('search') }}">
        </div>
        <select class="form-select" name="role" style="width: auto;">
          <option value="">All Roles</option>
          <option value="applicant" {{ request('role') === 'applicant' ? 'selected' : '' }}>Applicant</option>
          <option value="evaluator" {{ request('role') === 'evaluator' ? 'selected' : '' }}>Evaluator</option>
        </select>
        <select class="form-select" name="status" style="width: auto;">
          <option value="">All Status</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="deactivated" {{ request('status') === 'deactivated' ? 'selected' : '' }}>Deactivated</option>
        </select>
        <select class="form-select" name="scholarship_id" style="width: auto;">
          <option value="">All Scholarships</option>
          @foreach($scholarships as $scholarship)
            <option value="{{ $scholarship->id }}"
                    {{ request('scholarship_id') == $scholarship->id ? 'selected' : '' }}>
              {{ $scholarship->name }}
            </option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      </div>
    </form>
  </div>

  <!-- USER TABLE -->
  <div class="card" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th><input type="checkbox"></th>
            <th class="sortable sorted">User ↓</th>
            <th>Email</th>
            <th>Role</th>
            <th>Scholarship / Assignment</th>
            <th>Last Active</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr style="{{ $user->status === 'deactivated' ? 'opacity:.6' : '' }}">
            <td><input type="checkbox"></td>
            <td>
              <div style="display: flex; align-items: center; gap: 10px;">
                <div class="user-row-avatar"
                     style="background:{{ $user->status === 'deactivated' ? '#F3F4F6' : ($user->role === 'evaluator' ? 'rgba(15,76,92,.1)' : 'var(--accent-pale)') }};
                            color:{{ $user->status === 'deactivated' ? '#9CA3AF' : ($user->role === 'evaluator' ? 'var(--primary)' : '#92650a') }};">
                  {{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}
                </div>
                <div>
                  <div style="font-weight:600;color:{{ $user->status === 'deactivated' ? 'var(--muted)' : 'inherit' }};">
                    {{ $user->full_name }}
                  </div>
                  <div style="font-size:11px;color:{{ $user->status === 'deactivated' ? 'var(--muted)' : 'var(--slate)' }};">
                    #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                  </div>
                </div>
              </div>
            </td>
            <td style="font-size:12px;color:{{ $user->status === 'deactivated' ? 'var(--muted)' : 'var(--slate)' }};">
              {{ $user->email }}
            </td>
            <td>
              @if($user->status === 'deactivated')
                <span class="badge gray">🎓 {{ ucfirst($user->role) }}</span>
              @elseif($user->role === 'evaluator')
                <span class="badge blue">📋 Evaluator</span>
              @else
                <span class="badge amber">🎓 Applicant</span>
              @endif
            </td>
            <td>
              @if($user->status === 'deactivated')
                <span style="font-size:12px;color:var(--muted);">Account deactivated</span>
              @elseif($user->role === 'evaluator' && $user->assignedScholarship)
                <div style="font-size:12px;">{{ $user->assignedScholarship->name }}</div>
                <div style="font-size:11px;color:var(--slate);">
                  {{ $user->reviews_done }} reviews done · {{ $user->pending_reviews }} pending
                </div>
              @elseif($user->activeApplication)
                <div style="font-size:12px;">
                  {{ $user->activeApplication->scholarship->name }}
                  @switch($user->activeApplication->stage)
                    @case('under_review') <span class="badge yellow" style="font-size:10px;padding:1px 6px;">Under Review</span> @break
                    @case('approved') <span class="badge green" style="font-size:10px;padding:1px 6px;">Approved</span> @break
                    @case('rejected') <span class="badge red" style="font-size:10px;padding:1px 6px;">Rejected</span> @break
                    @default <span class="badge gray" style="font-size:10px;padding:1px 6px;">{{ ucfirst($user->activeApplication->stage) }}</span>
                  @endswitch
                </div>
                <div style="font-size:11px;color:var(--slate);">1 active application</div>
              @else
                <div style="font-size:12px;">None <span style="font-size:11px;color:var(--slate);">— no active app</span></div>
              @endif
            </td>
            <td style="font-size:12px;color:{{ $user->status === 'deactivated' ? 'var(--muted)' : 'var(--slate)' }};">
              {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}
            </td>
            <td>
              @if($user->status === 'active')
                <span class="badge green">Active</span>
              @else
                <span class="badge red">Deactivated</span>
              @endif
            </td>
            <td>
              <div style="display: flex; gap: 6px;">
                @if($user->status === 'deactivated')
                  <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline btn-sm">↩ Restore</button>
                  </form>
                @else
                  <a href="{{ route('admin.users.show', $user->id) }}"
                     class="btn btn-ghost btn-sm">👁 Profile</a>
                  <div class="action-menu">
                    <button class="btn btn-outline btn-sm">⋮</button>
                    <div class="action-menu-dropdown">
                      <a href="{{ route('admin.users.edit', $user->id) }}" class="action-menu-item">✏️ Edit</a>
                      @if($user->role === 'evaluator')
                        <div class="action-menu-item">🔄 Reassign</div>
                      @else
                        <a href="{{ route('admin.applications.index', ['user_id' => $user->id]) }}"
                           class="action-menu-item">📋 View Apps</a>
                      @endif
                      <div class="action-menu-item">📧 Send Email</div>
                      <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}"
                            onsubmit="return confirm('Deactivate {{ $user->full_name }}?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="action-menu-item danger" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
                          🚫 Deactivate
                        </button>
                      </form>
                    </div>
                  </div>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align:center;padding:40px;color:var(--muted);">
              No users found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">
      <span class="info">
        Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ number_format($users->total()) }} users
      </span>
      <div class="page-btns">
        @for($i = 1; $i <= min($users->lastPage(), 5); $i++)
          <button class="page-btn {{ $users->currentPage() === $i ? 'active' : '' }}"
                  onclick="window.location='{{ $users->url($i) }}'">{{ $i }}</button>
        @endfor
        @if($users->lastPage() > 5)
          <button class="page-btn">…</button>
          <button class="page-btn">{{ $users->lastPage() }}</button>
        @endif
        @if($users->hasMorePages())
          <button class="page-btn" onclick="window.location='{{ $users->nextPageUrl() }}'">›</button>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <!-- Create Evaluator Modal -->
  <div id="createEvalModal" class="modal-overlay"
       onclick="if(event.target===this) this.classList.remove('open')">
    <div class="modal">
      <div class="modal-title">
        + Create Evaluator Account
        <button class="modal-close"
                onclick="document.getElementById('createEvalModal').classList.remove('open')">✕</button>
      </div>
      <form method="POST" action="{{ route('admin.users.store-evaluator') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-group">
            <label class="form-label">First Name <span class="req">*</span></label>
            <input class="form-input" name="first_name" placeholder="Lena" required>
          </div>
          <div class="form-group">
            <label class="form-label">Last Name <span class="req">*</span></label>
            <input class="form-input" name="last_name" placeholder="Pascual" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address <span class="req">*</span></label>
          <input class="form-input" type="email" name="email"
                 placeholder="l.pascual@ched.gov.ph" required>
        </div>
        <div class="form-group">
          <label class="form-label">Assign to Scholarship(s) <span class="req">*</span></label>
          <select class="form-select" name="scholarship_id" required>
            @foreach($scholarships as $scholarship)
              <option value="{{ $scholarship->id }}">{{ $scholarship->name }}</option>
            @endforeach
          </select>
        </div>
        <div style="background:var(--accent-pale);border:1px solid rgba(232,168,56,.25);border-radius:10px;padding:10px 12px;font-size:12px;color:#92650a;margin-bottom:4px;">
          📧 New evaluator will receive a welcome email with login instructions.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline"
                  onclick="document.getElementById('createEvalModal').classList.remove('open')">Cancel</button>
          <button type="submit" class="btn btn-primary">Create &amp; Send Invite</button>
        </div>
      </form>
    </div>
  </div>
@endsection

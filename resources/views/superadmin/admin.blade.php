@extends('layouts.superadmin')

@section('page_title', 'Admin Accounts')
@section('topnav_title', 'Admin Accounts')
@section('topnav_subtitle', '/superadmin/admins')

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Home</span><span class="sep">/</span><span class="current">Admin Accounts</span>
  </div>

  <!-- STATS -->
  <div class="stat-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
    <div class="stat-card">
      <div class="label">Total Admins</div>
      <div class="value">{{ $totalAdmins }}</div>
      <div class="delta neutral">Across {{ $totalOrgs }} orgs</div>
    </div>
    <div class="stat-card">
      <div class="label">Active</div>
      <div class="value">{{ $activeAdmins }}</div>
      <div class="delta up">▲ {{ $newAdminsToday }} added today</div>
    </div>
    <div class="stat-card">
      <div class="label">Deactivated</div>
      <div class="value">{{ $deactivatedAdmins }}</div>
      <div class="delta neutral">Can be restored</div>
    </div>
    <div class="stat-card">
      <div class="label">Unassigned Orgs</div>
      <div class="value">{{ $unassignedOrgs }}</div>
      <div class="delta down">Need admin</div>
    </div>
  </div>

  <!-- FILTER + CREATE -->
  <div class="card" style="padding: 14px 20px; margin-bottom: 16px;">
    <form method="GET" action="{{ route('superadmin.admins') }}">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div class="search-wrap" style="flex: 1; min-width: 200px;">
          <span class="search-icon">🔍</span>
          <input class="form-input" name="search" placeholder="Search admin accounts…"
                 value="{{ request('search') }}" style="padding-left: 36px;">
        </div>
        <select class="form-select" name="status" style="width: auto;">
          <option value="">All Status</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="deactivated" {{ request('status') === 'deactivated' ? 'selected' : '' }}>Deactivated</option>
        </select>
        <select class="form-select" name="organization_id" style="width: auto;">
          <option value="">All Orgs</option>
          @foreach($organizations as $org)
            <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
              {{ $org->name }}
            </option>
          @endforeach
        </select>
        <button type="button" class="btn btn-primary"
                onclick="document.getElementById('createAdminModal').classList.add('open')">
          + Create Admin
        </button>
      </div>
    </form>
  </div>

  <!-- ADMIN TABLE -->
  <div class="card" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Admin</th>
            <th>Email</th>
            <th>Organization</th>
            <th>Last Active</th>
            <th>Managed Scholars</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($admins as $admin)
          <tr>
            <td>
              <div style="display: flex; align-items: center; gap: 10px;">
                <div class="org-avatar" style="background: {{ $admin->status === 'deactivated' ? '#F3F4F6' : 'rgba(15,76,92,.1)' }}; width: 36px; height: 36px; font-size: 13px; border-radius: 50%; font-weight: 700; color: {{ $admin->status === 'deactivated' ? '#9CA3AF' : 'var(--primary)' }};">
                  {{ strtoupper(substr($admin->first_name, 0, 1) . substr($admin->last_name, 0, 1)) }}
                </div>
                <div>
                  <div style="font-weight: 600; {{ $admin->status === 'deactivated' ? 'color: var(--muted);' : '' }}">
                    {{ $admin->full_name }}
                  </div>
                  <div style="font-size: 11px; color: {{ $admin->status === 'deactivated' ? 'var(--muted)' : 'var(--slate)' }};">
                    Admin · #ADM-{{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}
                  </div>
                </div>
              </div>
            </td>
            <td style="font-size: 12px; color: {{ $admin->status === 'deactivated' ? 'var(--muted)' : 'var(--slate)' }};">
              {{ $admin->email }}
            </td>
            <td>
              @if($admin->organization)
                <span class="badge {{ $admin->status === 'deactivated' ? 'gray' : 'teal' }}">
                  {{ $admin->organization->name }}
                </span>
              @else
                <span style="color: var(--muted); font-size: 12px; font-style: italic;">Unassigned</span>
              @endif
            </td>
            <td style="font-size: 12px; color: {{ $admin->status === 'deactivated' ? 'var(--muted)' : 'var(--slate)' }};">
              {{ $admin->last_active_at ? $admin->last_active_at->diffForHumans() : 'Never' }}
            </td>
            <td style="font-weight: 600; {{ $admin->status === 'deactivated' ? 'color: var(--muted);' : '' }}">
              {{ number_format($admin->managed_scholars_count ?? 0) }}
            </td>
            <td>
              @if($admin->status === 'active')
                <span class="badge green">Active</span>
              @elseif($admin->status === 'deactivated')
                <span class="badge red">Deactivated</span>
              @else
                <span class="badge yellow">{{ ucfirst($admin->status) }}</span>
              @endif
            </td>
            <td>
              <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                @if($admin->status === 'active')
                  <a href="#"
                     class="btn btn-ghost btn-sm">✏️</a>
                  <a href="#"
                     class="btn btn-ghost btn-sm">✏️</a>
                  <form method="POST" action="{{ route('superadmin.admins.deactivate', $admin->id) }}"
                        style="display: inline;"
                        onsubmit="return confirm('Deactivate {{ $admin->full_name }}?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                  </form>
                @else
                  <form method="POST" action="#"
                        style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline btn-sm">↩ Restore</button>
                  </form>

                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: var(--muted);">
              No admin accounts found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">
      <span class="info">Showing {{ $admins->firstItem() }}–{{ $admins->lastItem() }} of {{ $admins->total() }} admins</span>
      <div class="page-btns">
        @for($i = 1; $i <= min($admins->lastPage(), 5); $i++)
          <button class="page-btn {{ $admins->currentPage() === $i ? 'active' : '' }}"
                  onclick="window.location='{{ $admins->url($i) }}'">
            {{ $i }}
          </button>
        @endfor
        @if($admins->hasMorePages())
          <button class="page-btn" onclick="window.location='{{ $admins->nextPageUrl() }}'">›</button>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <!-- Create Admin Modal -->
  <div id="createAdminModal" class="modal-overlay"
       onclick="if(event.target===this) this.classList.remove('open')">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-title">Create Admin Account</div>
        <button class="modal-close"
                onclick="document.getElementById('createAdminModal').classList.remove('open')">✕</button>
      </div>
      <form method="POST" action="{{ route('superadmin.admins.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
          <div class="form-group">
            <label class="form-label">First Name <span class="req">*</span></label>
            <input class="form-input" name="first_name" placeholder="Juan" required>
          </div>
          <div class="form-group">
            <label class="form-label">Last Name <span class="req">*</span></label>
            <input class="form-input" name="last_name" placeholder="dela Cruz" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email Address <span class="req">*</span></label>
          <input class="form-input" type="email" name="email"
                 placeholder="juan@organization.gov.ph" required>
        </div>
        <div class="form-group">
          <label class="form-label">Assign to Scholarship Name <span class="req">*</span></label>
          <select class="form-select" name="organization_id" required>
            <option value="">— Select organization —</option>
            @foreach($organizations as $org)
              <option value="{{ $org->id }}">
                {{ $org->name }} ({{ $org->admin ? 'Has admin' : 'No admin' }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Temporary Password</label>
          <input class="form-input" type="password" name="password"
                 placeholder="Auto-generated if blank">
        </div>
        <div style="background: var(--accent-pale); border-radius: 10px; padding: 10px 12px; font-size: 12px; color: #92650a; border: 1px solid rgba(232,168,56,.2); margin-bottom: 4px;">
          📧 Admin will receive a welcome email with login instructions and a password setup link.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline"
                  onclick="document.getElementById('createAdminModal').classList.remove('open')">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">Create Account &amp; Send Invite</button>
        </div>
      </form>
    </div>
  </div>


@endsection

@push('scripts')

@endpush

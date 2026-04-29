@extends('layouts.superadmin')

@section('page_title', 'Organizations')
@section('topnav_title', 'Organizations')
@section('topnav_subtitle', '/superadmin/organizations')

@section('content')
  {{-- BREADCRUMB --}}
  <div class="breadcrumb">
    <span>Home</span><span class="sep">/</span><span class="current">Organizations</span>
  </div>

  {{-- SUCCESS / ERROR ALERTS --}}
  @if(session('success'))
    <div style="background:var(--green-light);border:1px solid #A7F3D0;color:var(--green);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;">
      ✅ {{ session('success') }}
    </div>
  @endif
  @if($errors->any())
    <div style="background:var(--red-light);border:1px solid #FECACA;color:var(--red);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;">
      @foreach($errors->all() as $err) <div>⚠️ {{ $err }}</div> @endforeach
    </div>
  @endif

  {{-- STATS --}}
  <div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px;">
    <div class="stat-card">
      <div class="label">Total Organizations</div>
      <div class="value">{{ $totalOrgs }}</div>
      <div class="delta up">▲ {{ $newOrgsThisMonth }} this month</div>
    </div>
    <div class="stat-card">
      <div class="label">Active</div>
      <div class="value">{{ $activeOrgs }}</div>
      <div class="delta neutral">Organizations online</div>
    </div>
    <div class="stat-card">
      <div class="label">Inactive</div>
      <div class="value">{{ $inactiveOrgs }}</div>
      <div class="delta down">Awaiting activation</div>
    </div>
    <div class="stat-card">
      <div class="label">Pending Approval</div>
      <div class="value">{{ $pendingOrgs }}</div>
      <div class="delta neutral">Needs review</div>
    </div>
  </div>

  {{-- FILTER + ACTIONS --}}
  <div class="card" style="padding:14px 20px;margin-bottom:16px;">
    <form method="GET" action="{{ route('superadmin.organizations') }}">
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <div class="search-wrap" style="flex:1;min-width:200px;">
          <span class="search-icon">🔍</span>
          <input class="form-input" name="search" placeholder="Search organizations…"
                 value="{{ request('search') }}" style="padding-left:36px;">
        </div>
        <select class="form-select" name="status" style="width:auto;">
          <option value="">All Status</option>
          <option value="active"   {{ request('status')==='active'   ? 'selected':'' }}>Active</option>
          <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}>Inactive</option>
        </select>
        <select class="form-select" name="type" style="width:auto;">
          <option value="">All Types</option>
          @foreach($orgTypes as $type)
            <option value="{{ $type }}" {{ request('type')===$type ? 'selected':'' }}>{{ $type }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-outline">Filter</button>
        <button type="button" class="btn btn-primary"
                onclick="document.getElementById('createOrgModal').classList.add('open')">
          + Add Organization
        </button>
      </div>
    </form>
  </div>

  {{-- ORGANIZATIONS TABLE --}}
  <div class="card" style="padding:0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Organization</th>
            <th>Type</th>
            <th>Assigned Admin</th>
            <th>Active Scholarships</th>
            <th>Applicants</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($organizations as $org)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div class="org-avatar" style="background:{{ $org->avatar_bg ?? '#E8F8F0' }};font-size:16px;">
                  {{ $org->emoji ?? '🏛️' }}
                </div>
                <div>
                  <div style="font-weight:600;">{{ $org->name }}</div>
                  <div style="font-size:11px;color:var(--slate);">{{ $org->email }}</div>
                </div>
              </div>
            </td>
            <td><span class="badge gray">{{ $org->type ?? '—' }}</span></td>
            <td>
              @if($org->admin)
                <div style="font-weight:600;font-size:13px;">{{ $org->admin->full_name }}</div>
                <div style="font-size:11px;color:var(--slate);">{{ $org->admin->email }}</div>
              @else
                <span style="color:var(--muted);font-size:12px;font-style:italic;">Unassigned</span>
              @endif
            </td>
            <td><span style="font-weight:600;">{{ $org->active_scholarships_count ?? 0 }}</span></td>
            <td>{{ number_format($org->applicants_count ?? 0) }}</td>
            <td>
              @if($org->is_active)
                <span class="badge green">Active</span>
              @else
                <span class="badge red">Inactive</span>
              @endif
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <form method="POST" action="{{ route('superadmin.organizations.destroy', $org->id) }}"
                      onsubmit="return confirm('Delete this organization? This cannot be undone.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:var(--muted);">
              No organizations found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($organizations->total() > 0)
    <div class="pagination">
      <span class="info">Showing {{ $organizations->firstItem() }}–{{ $organizations->lastItem() }} of {{ $organizations->total() }} organizations</span>
      <div class="page-btns">
        @for($i = 1; $i <= $organizations->lastPage(); $i++)
          <button class="page-btn {{ $organizations->currentPage() === $i ? 'active' : '' }}"
                  onclick="window.location='{{ $organizations->url($i) }}'">{{ $i }}</button>
        @endfor
        @if($organizations->hasMorePages())
          <button class="page-btn" onclick="window.location='{{ $organizations->nextPageUrl() }}'">›</button>
        @endif
      </div>
    </div>
    @endif
  </div>

  {{-- TOP PERFORMING ORGS --}}
  @if($orgStats->count() > 0)
  <div style="margin-top:20px;">
    <div class="section-title" style="margin-bottom:12px;">Top Organizations by Applications</div>
    <div class="grid-3">
      @foreach($orgStats as $stat)
      <div class="org-card">
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="org-avatar" style="background:{{ $stat->avatar_bg ?? '#E8F8F0' }};font-size:16px;">
            {{ $stat->emoji ?? '🏛️' }}
          </div>
          <div>
            <div style="font-weight:600;font-size:13px;">{{ $stat->name }}</div>
            <div class="org-meta">{{ $stat->type }}</div>
          </div>
        </div>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
            <span style="color:var(--slate);">Approval rate</span>
            <span style="font-weight:600;">{{ $stat->approval_rate }}%</span>
          </div>
          <div class="prog-bar-wrap">
            <div class="prog-bar" style="width:{{ $stat->approval_rate }}%;"></div>
          </div>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <span class="badge green">{{ number_format($stat->applicants_count) }} applicants</span>
          <span class="badge teal">{{ $stat->scholarships_count }} scholarships</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif
@endsection

@section('modals')
{{-- ═══ UNIFIED CREATE ORGANIZATION MODAL ═══ --}}
<div id="createOrgModal" class="modal-overlay" onclick="if(event.target===this) this.classList.remove('open')">
  <div class="modal" style="max-width:580px;">
    <div class="modal-header">
      <div class="modal-title">🏛️ Add New Organization</div>
      <button class="modal-close" onclick="document.getElementById('createOrgModal').classList.remove('open')">✕</button>
    </div>

    <form method="POST" action="{{ route('superadmin.organizations.store') }}">
      @csrf

      {{-- ORGANIZATION DETAILS --}}
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--slate);margin-bottom:10px;padding-bottom:6px;border-bottom:1.5px solid var(--border);">
        Organization Details
      </div>

      <div class="form-group">
        <label class="form-label">Organization Name <span class="req">*</span></label>
        <input class="form-input" name="name" placeholder="e.g. Petron Foundation" required>
      </div>
      <div class="grid-2" style="gap:12px;">
        <div class="form-group">
          <label class="form-label">Type <span class="req">*</span></label>
          <select class="form-select" name="type" required>
            @foreach($orgTypes as $type)
              <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Status</label>
          <select class="form-select" name="is_active">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-input" type="email" name="email" placeholder="org@example.com">
      </div>
      <div class="form-group">
        <label class="form-label">Website</label>
        <input class="form-input" type="url" name="website" placeholder="https://example.com">
      </div>
      <div class="form-group" style="margin-bottom:20px;">
        <label class="form-label">Address</label>
        <input class="form-input" name="address" placeholder="123 Main St, City, Province">
      </div>

      {{-- ADMIN ASSIGNMENT --}}
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--slate);margin-bottom:10px;padding-bottom:6px;border-bottom:1.5px solid var(--border);">
        Admin Assignment
      </div>

      {{-- Toggle --}}
      <div style="display:flex;gap:8px;margin-bottom:16px;">
        <button type="button" id="tab-existing" class="btn btn-primary btn-sm" onclick="switchAdminTab('existing')">Assign Existing Admin</button>
        <button type="button" id="tab-new" class="btn btn-outline btn-sm" onclick="switchAdminTab('new')">Create New Admin</button>
      </div>

      {{-- Existing Admin Panel --}}
      <div id="panel-existing">
        <div class="form-group">
          <label class="form-label">Select Admin</label>
          <select class="form-select" name="existing_admin_id">
            <option value="">— No assignment yet —</option>
            @foreach($unassignedAdmins ?? [] as $admin)
              <option value="{{ $admin->id }}">{{ $admin->full_name }} ({{ $admin->email }})</option>
            @endforeach
          </select>
          <p style="font-size:11px;color:var(--slate);margin-top:4px;">Only unassigned admins are listed. You can assign one later.</p>
        </div>
      </div>

      {{-- New Admin Panel --}}
      <div id="panel-new" style="display:none;">
        <div style="background:var(--primary-pale);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:14px;">
          <div class="grid-2" style="gap:12px;">
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">First Name <span class="req">*</span></label>
              <input class="form-input" name="new_admin_first_name" placeholder="Juan">
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Last Name <span class="req">*</span></label>
              <input class="form-input" name="new_admin_last_name" placeholder="Dela Cruz">
            </div>
          </div>
          <div class="form-group" style="margin-top:12px;margin-bottom:0;">
            <label class="form-label">Email <span class="req">*</span></label>
            <input class="form-input" type="email" name="new_admin_email" placeholder="admin@org.com">
          </div>
          <div class="form-group" style="margin-top:12px;margin-bottom:0;">
            <label class="form-label">Temporary Password <span class="req">*</span></label>
            <input class="form-input" type="password" name="new_admin_password" placeholder="Min. 8 characters">
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline"
                onclick="document.getElementById('createOrgModal').classList.remove('open')">
          Cancel
        </button>
        <button type="submit" class="btn btn-primary">Create Organization</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function switchAdminTab(tab) {
    const existingPanel = document.getElementById('panel-existing');
    const newPanel      = document.getElementById('panel-new');
    const tabExisting   = document.getElementById('tab-existing');
    const tabNew        = document.getElementById('tab-new');

    if (tab === 'existing') {
      existingPanel.style.display = 'block';
      newPanel.style.display      = 'none';
      tabExisting.className = 'btn btn-primary btn-sm';
      tabNew.className      = 'btn btn-outline btn-sm';
    } else {
      existingPanel.style.display = 'none';
      newPanel.style.display      = 'block';
      tabExisting.className = 'btn btn-outline btn-sm';
      tabNew.className      = 'btn btn-primary btn-sm';
    }
  }

  // Re-open modal on validation error
  @if($errors->any())
    document.getElementById('createOrgModal').classList.add('open');
    @if(old('new_admin_first_name') || old('new_admin_email'))
      switchAdminTab('new');
    @endif
  @endif
</script>
@endpush

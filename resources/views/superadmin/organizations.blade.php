@extends('layouts.superadmin')

@section('page_title', 'Scholarship Management')
@section('topnav_title', 'Scholarship Management')
@section('topnav_subtitle', '/superadmin/organizations')

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Home</span><span class="sep">/</span><span class="current">Scholarships</span>
  </div>

  <!-- STATS -->
  <div class="stat-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom: 20px;">
    <div class="stat-card">
      <div class="label">Total Scholarships</div>
      <div class="value">{{ $totalOrgs }}</div>
      <div class="delta up">▲ {{ $newOrgsThisMonth }} this month</div>
    </div>
    <div class="stat-card">
      <div class="label">Active Applicants</div>
      <div class="value">{{ $activeOrgs }}</div>
      <div class="delta neutral">{{ $inactiveOrgs }} inactive</div>
    </div>
    <div class="stat-card">
      <div class="label">Pending Approval</div>
      <div class="value">{{ $pendingOrgs }}</div>
      <div class="delta down">Needs review</div>
    </div>
  </div>

  <!-- FILTER + ACTIONS -->
  <div class="card" style="padding: 14px 20px; margin-bottom: 16px;">
    <form method="GET" action="{{ route('superadmin.organizations') }}">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div class="search-wrap" style="flex: 1; min-width: 200px;">
          <span class="search-icon">🔍</span>
          <input class="form-input" name="search" placeholder="Search scholarships…"
                 value="{{ request('search') }}" style="padding-left: 36px;">
        </div>
        <select class="form-select" name="status" style="width: auto;">
          <option value="">All Status</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
        <select class="form-select" name="type" style="width: auto;">
          <option value="">All Types</option>
          @foreach($orgTypes as $type)
            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
          @endforeach
        </select>
        <button type="button" class="btn btn-primary"
                onclick="document.getElementById('createOrgModal').classList.add('open')">
          + Add Scholarship
        </button>
      </div>
    </form>
  </div>

  <!-- ORG TABLE -->
  <div class="card" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Scholarship Provider</th>
            <th>Type</th>
            <th>Assigned Admin</th>
            <th>Scholarships</th>
            <th>Applicants</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($organizations as $org)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="org-avatar" style="background: #E8F8F0; font-size: 16px;">🎓</div>
                        <div>
                            <div style="font-weight: 600;">{{ $org->name }}</div>
                            <div style="font-size: 11px; color: var(--slate);">{{ $org->provider_name }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge gray">Scholarship</span>
                </td>
                <td>
                    <span style="color: var(--muted); font-size: 12px; font-style: italic;">System Admin</span>
                </td>
                <td>
                    <span style="font-weight: 600;">{{ $org->slots ?? 0 }}</span>
                    <span style="color: var(--slate); font-size: 12px;"> slots</span>
                </td>
                <td>{{ number_format($org->applications_count ?? 0) }}</td>
                <td>
                    @if ($org->status === 'open')
                        <span class="badge green">Open</span>
                    @elseif ($org->status === 'draft')
                        <span class="badge yellow">Draft</span>
                    @else
                        <span class="badge red">{{ ucfirst($org->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 6px;">
                        <a href="#" class="btn btn-ghost btn-sm">View Details</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--muted);">
                    No scholarships found.
                </td>
            </tr>
            @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">
      <span class="info">Showing {{ $organizations->firstItem() }}–{{ $organizations->lastItem() }} of {{ $organizations->total() }} scholarships</span>
      <div class="page-btns">
        @for($i = 1; $i <= $organizations->lastPage(); $i++)
          <button class="page-btn {{ $organizations->currentPage() === $i ? 'active' : '' }}"
                  onclick="window.location='{{ $organizations->url($i) }}'">
            {{ $i }}
          </button>
        @endfor
        @if($organizations->hasMorePages())
          <button class="page-btn" onclick="window.location='{{ $organizations->nextPageUrl() }}'">›</button>
        @endif
      </div>
    </div>
  </div>

  <!-- PER-ORG STATS OVERVIEW -->
  <div style="margin-top: 20px;">
    <div class="section-title" style="margin-bottom: 12px;">Top Performing Scholarships</div>
    <div class="grid-3">
        @foreach($orgStats as $stat)
        <div class="org-card">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="org-avatar" style="background: {{ $stat->avatar_bg ?? '#E8F8F0' }}; font-size: 16px;">
                    {{ $stat->emoji ?? '🎓' }}
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 13px;">{{ $stat->name }}</div>
                    <div class="org-meta">{{ $stat->provider }}</div>
                </div>
            </div>
            
            <div style="margin-top: 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                    <span style="color: var(--slate);">Approval rate</span>
                    <span style="font-weight: 600;">{{ $stat->approval_rate }}%</span>
                </div>
                <div class="prog-bar-wrap">
                    <div class="prog-bar" style="width: {{ $stat->approval_rate }}%;"></div>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
                <div style="display: flex; gap: 8px;">
                    <span class="badge green">{{ number_format($stat->applicants_count) }} applicants</span>
                    <span class="badge teal">{{ ucfirst($stat->status) }}</span>
                </div>
                <div style="font-size: 11px; color: var(--slate); font-weight: 600;">
                    {{ $stat->slots_available }} slots
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('modals')
  <!-- Create Org Modal -->
  <div id="createOrgModal" class="modal-overlay"
       onclick="if(event.target===this) this.classList.remove('open')">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-title">Add New Scholarship</div>
        <button class="modal-close"
                onclick="document.getElementById('createOrgModal').classList.remove('open')">✕</button>
      </div>
      <form method="POST" action="{{ route('superadmin.organizations.store') }}">
        @csrf
        <div class="form-group">
          <label class="form-label">Scholarship Provider Name <span class="req">*</span></label>
          <input class="form-input" name="name" placeholder="e.g. Petron Foundation" required>
        </div>
        <div class="form-group">
          <label class="form-label">Type <span class="req">*</span></label>
          <select class="form-select" name="type" required>
            @foreach($orgTypes as $type)
              <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-textarea" name="description"
                    placeholder="Brief description of the scholarship…"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Initial Status</label>
          <select class="form-select" name="status">
            <option value="active">Active</option>
            <option value="pending">Pending</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline"
                  onclick="document.getElementById('createOrgModal').classList.remove('open')">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">Create Scholarship</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Assign Admin Modal -->
  <div id="assignAdminModal" class="modal-overlay"
       onclick="if(event.target===this) this.classList.remove('open')">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-title">Assign Admin to </div>
        <button class="modal-close"
                onclick="document.getElementById('assignAdminModal').classList.remove('open')">✕</button>
      </div>
      <form method="POST" action="#" id="assignAdminForm">
        @csrf
        <input type="hidden" name="organization_id" id="assignOrgId">
        <div style="background: var(--page-bg); border-radius: 12px; padding: 12px; margin-bottom: 16px; border: 1px solid var(--border);">
          <div style="font-size: 11px; font-weight: 600; color: var(--slate); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Assigning to</div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="font-size: 20px;">🏗️</div>
            <div>
              <div style="font-weight: 600; font-size: 14px;" id="assignOrgName">—</div>
              <div style="font-size: 12px; color: var(--slate);">Select an admin below</div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Select Admin <span class="req">*</span></label>
          <select class="form-select" name="admin_id">
            <option value="">— Choose an admin —</option>
            @foreach($unassignedAdmins ?? [] as $admin)
              <option value="{{ $admin->id }}">{{ $admin->full_name }} (Unassigned)</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Or create a new admin</label>
          <input class="form-input" name="new_admin_email" placeholder="New admin email address…">
        </div>
        <div style="background: var(--yellow-bg); border-radius: 10px; padding: 10px 12px; font-size: 12px; color: var(--yellow); border: 1px solid rgba(217,119,6,.2); margin-bottom: 4px;">
          ⚠️ If transferring an existing admin, their previous org will become unassigned.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline"
                  onclick="document.getElementById('assignAdminModal').classList.remove('open')">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">Confirm Assignment</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function openAssignModal(orgId, orgName) {
    document.getElementById('assignOrgId').value = orgId;
    document.getElementById('assignOrgName').textContent = orgName;
    document.getElementById('assignAdminModal').classList.add('open');
  }

  function reactivateOrg(orgId) {
    if (confirm('Reactivate this organization?')) {
      fetch('/superadmin/organizations/' + orgId + '/reactivate', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      }).then(() => location.reload());
    }
  }
</script>
@endpush

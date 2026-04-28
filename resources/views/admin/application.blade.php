@extends('layouts.admin')

@section('page_title', 'Scholarship Applications')
@section('topnav_title', 'Scholarship Applications')
@section('topnav_subtitle', '/admin/scholarships/' . $scholarship->id . '/applications · ' . $scholarship->name)

@section('topnav_actions')
  <a href="{{ route('admin.applications.export', $scholarship->id) }}"
     class="btn btn-outline btn-sm"
     style="border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.8);background:rgba(255,255,255,.08)">
    ⬇ Export CSV
  </a>
@endsection

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Scholarships</span><span class="sep">/</span>
    <a href="{{ route('admin.scholarships.index') }}" style="color:var(--slate);">{{ $scholarship->name }}</a>
    <span class="sep">/</span>
    <span class="current">Applications</span>
  </div>

  <!-- STAT ROW -->
  <div class="grid-4" style="margin-bottom: 20px;">
    <div class="stat-card">
      <div class="label">Total Applications</div>
      <div class="value">{{ $totalApplications }}</div>
      <div class="delta neutral">Across all stages</div>
    </div>
    <div class="stat-card">
      <div class="label">Under Review</div>
      <div class="value">{{ $underReview }}</div>
      <div class="delta down">{{ $unassigned }} unassigned</div>
    </div>
    <div class="stat-card">
      <div class="label">Approved</div>
      <div class="value">{{ $approved }}</div>
      <div class="delta up" style="color:var(--green);">{{ $approvalRate }}% approval rate</div>
    </div>
    <div class="stat-card">
      <div class="label">Slots Remaining</div>
      <div class="value">{{ $slotsRemaining }}</div>
      <div class="delta neutral">of {{ $scholarship->total_slots }} total</div>
    </div>
  </div>

  <!-- STAGE TABS -->
  <div class="stage-tabs" style="margin-bottom: 16px;">
    @foreach($stages as $stage)
    <a href="{{ route('admin.applications.index', ['scholarship_id' => $scholarship->id, 'stage' => $stage['key']]) }}"
       class="stage-tab {{ request('stage', 'all') === $stage['key'] ? 'active' : '' }}">
      <span class="tab-count">{{ $stage['count'] }}</span>
      {{ $stage['label'] }}
    </a>
    @endforeach
  </div>

  <!-- FILTER + ACTIONS -->
  <div class="card" style="padding: 14px 20px; margin-bottom: 12px;">
    <form method="GET" action="{{ route('admin.applications.index') }}">
      <input type="hidden" name="scholarship_id" value="{{ $scholarship->id }}">
      <input type="hidden" name="stage" value="{{ request('stage', 'all') }}">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <div class="search-wrap" style="flex: 1; min-width: 200px;">
          <span class="si">🔍</span>
          <input class="form-input" name="search" placeholder="Search by App ID, applicant code…"
                 value="{{ request('search') }}">
        </div>
        <select class="form-select" name="evaluator_id" style="width: auto;">
          <option value="">All Evaluators</option>
          @foreach($evaluators as $evaluator)
            <option value="{{ $evaluator->id }}" {{ request('evaluator_id') == $evaluator->id ? 'selected' : '' }}>
              {{ $evaluator->full_name }}
            </option>
          @endforeach
          <option value="unassigned" {{ request('evaluator_id') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
        </select>
        <select class="form-select" name="sort" style="width: auto;">
          <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Sort: Newest</option>
          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Sort: Oldest</option>
          <option value="score_desc" {{ request('sort') === 'score_desc' ? 'selected' : '' }}>Sort: Score ↓</option>
          <option value="ai_match_desc" {{ request('sort') === 'ai_match_desc' ? 'selected' : '' }}>Sort: AI Match ↓</option>
        </select>
        <button type="button" class="btn btn-primary"
                onclick="document.getElementById('assignModal').classList.add('open')">
          👤 Assign Evaluator
        </button>
      </div>
    </form>
  </div>

  <!-- BULK ACTION BAR -->
  <div class="bulk-bar" id="bulkBar">
    <span>Bulk Actions:</span>
    <span class="count" id="selectedCount">0 selected</span>
    <div class="bulk-sep"></div>
    <button class="btn btn-sm"
            style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25)"
            onclick="document.getElementById('assignModal').classList.add('open')">
      👤 Assign Evaluator
    </button>
    <button class="btn btn-sm"
            style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25)"
            onclick="exportSelected()">
      ⬇ Export Selected
    </button>
    <button class="btn btn-sm"
            style="background:rgba(220,38,38,.3);color:#FCA5A5;border:1px solid rgba(220,38,38,.4)"
            onclick="rejectSelected()">
      ✖ Reject Selected
    </button>
    <div style="margin-left:auto;cursor:pointer;color:rgba(255,255,255,.6);font-size:18px"
         onclick="document.getElementById('bulkBar').classList.remove('visible')">✕</div>
  </div>

  <!-- APPLICATIONS TABLE -->
  <div class="card" style="padding: 0;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAll" onchange="toggleAllRows(this)"></th>
            <th class="sortable sorted">App ID ↓</th>
            <th>Applicant Code</th>
            <th>Submitted</th>
            <th>AI Match</th>
            <th>Score</th>
            <th>Stage</th>
            <th>Assigned Evaluator</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($applications as $application)
          <tr id="row-{{ $application->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $application->id }}" onchange="rowCheck()"></td>
            <td>
              <span style="font-weight: 700; color: var(--primary);">#A-{{ $application->id }}</span>
            </td>
            <td>
              <div style="font-size: 12px; font-weight: 500;">{{ $application->applicant_code }}</div>
              <div style="font-size: 11px; color: var(--slate);">
                GPA: {{ $application->gpa }} · ₱{{ number_format($application->annual_income) }}/yr
              </div>
            </td>
            <td style="font-size: 12px; color: var(--slate);">
              {{ $application->submitted_at->format('M d · g:i A') }}
            </td>
            <td>
              @php
                $match = $application->ai_match_score ?? 0;
                $matchColor = $match >= 80 ? 'var(--green)' : ($match >= 60 ? 'var(--primary)' : 'var(--yellow)');
              @endphp
              <div style="display: flex; align-items: center; gap: 6px;">
                <div style="width:44px;height:5px;background:var(--border);border-radius:3px;overflow:hidden">
                  <div style="width:{{ $match }}%;height:100%;background:{{ $matchColor }};border-radius:3px;"></div>
                </div>
                <span style="font-size:12px;font-weight:700;color:{{ $matchColor }};">{{ $match }}%</span>
              </div>
            </td>
            <td style="font-weight:700;font-family:'Fraunces',serif;
              color:{{ $application->score >= 80 ? 'var(--green)' : ($application->score < 50 ? 'var(--red)' : 'var(--primary)') }};">
              {{ number_format($application->score, 1) }}
            </td>
            <td>
              @switch($application->stage)
                @case('submitted')  <span class="badge yellow">⏳ Submitted</span> @break
                @case('under_review') <span class="badge blue">📋 Under Review</span> @break
                @case('info_requested') <span class="badge amber">📩 Info Requested</span> @break
                @case('approved') <span class="badge green">✅ Approved</span> @break
                @case('rejected') <span class="badge red">❌ Rejected</span> @break
                @default <span class="badge gray">{{ ucfirst($application->stage) }}</span>
              @endswitch
            </td>
            <td>
              @if($application->evaluator)
                <div style="display: flex; align-items: center; gap: 6px;">
                  <div class="user-row-avatar"
                       style="background:rgba(15,76,92,.1);color:var(--primary);width:24px;height:24px;font-size:10px;">
                    {{ strtoupper(substr($application->evaluator->first_name,0,1).substr($application->evaluator->last_name,0,1)) }}
                  </div>
                  <span style="font-size:12px;">{{ $application->evaluator->full_name }}</span>
                </div>
              @else
                <span style="color:var(--red);font-size:12px;font-style:italic;">⚠ Unassigned</span>
              @endif
            </td>
            <td>
              <div style="display: flex; gap: 6px;">
                @if(!$application->evaluator)
                  <button class="btn btn-amber btn-sm"
                          onclick="openAssignModal([{{ $application->id }}])">👤 Assign</button>
                @else
                  <a href="{{ route('admin.applications.show', $application->id) }}"
                     class="btn btn-ghost btn-sm">👁 View</a>
                @endif
                <div class="action-menu">
                  <button class="btn btn-outline btn-sm">⋮</button>
                  <div class="action-menu-dropdown">
                    @if($application->evaluator)
                      <div class="action-menu-item"
                           onclick="openAssignModal([{{ $application->id }}])">👤 Reassign</div>
                    @endif
                    <a href="{{ route('admin.applications.show', $application->id) }}"
                       class="action-menu-item">📋 View Detail</a>
                    <div class="action-menu-item"
                         onclick="emailApplicant({{ $application->id }})">📧 Email Applicant</div>
                    @if($application->stage === 'approved')
                      <div class="action-menu-item danger"
                           onclick="reverseDecision({{ $application->id }}, 'approval')">↩ Reverse Approval</div>
                    @elseif($application->stage === 'rejected')
                      <div class="action-menu-item"
                           onclick="reverseDecision({{ $application->id }}, 'rejection')">↩ Reverse Rejection</div>
                    @else
                      <div class="action-menu-item danger"
                           onclick="rejectApplication({{ $application->id }})">✖ Reject</div>
                    @endif
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" style="text-align:center;padding:40px;color:var(--muted);">
              No applications found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">
      <span class="info">
        Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }} applications
      </span>
      <div class="page-btns">
        @for($i = 1; $i <= min($applications->lastPage(), 9); $i++)
          <button class="page-btn {{ $applications->currentPage() === $i ? 'active' : '' }}"
                  onclick="window.location='{{ $applications->url($i) }}'">{{ $i }}</button>
        @endfor
        @if($applications->hasMorePages())
          <button class="page-btn" onclick="window.location='{{ $applications->nextPageUrl() }}'">›</button>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <!-- Assign Evaluator Modal -->
  <div id="assignModal" class="modal-overlay"
       onclick="if(event.target===this) this.classList.remove('open')">
    <div class="modal">
      <div class="modal-title">
        👤 Assign Evaluator
        <button class="modal-close"
                onclick="document.getElementById('assignModal').classList.remove('open')">✕</button>
      </div>
      <div style="background:var(--page-bg);border:1.5px solid var(--border);border-radius:12px;padding:12px;margin-bottom:16px;">
        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;font-weight:600;margin-bottom:4px;">Assigning</div>
        <div style="font-size:13px;font-weight:600;" id="assignContext">
          Selected applications · {{ $scholarship->name }}
        </div>
      </div>
      <form method="POST" action="{{ route('admin.applications.assign-evaluator') }}" id="assignForm">
        @csrf
        <input type="hidden" name="application_ids" id="assignAppIds">
        <input type="hidden" name="scholarship_id" value="{{ $scholarship->id }}">
        <div class="form-group">
          <label class="form-label">Select Evaluator <span class="req">*</span></label>
          <select class="form-select" name="evaluator_id" required>
            <option value="">— Choose evaluator —</option>
            @foreach($evaluators as $evaluator)
              <option value="{{ $evaluator->id }}">
                {{ $evaluator->full_name }}
                ({{ $evaluator->reviews_done }} done · {{ $evaluator->pending_reviews }} pending)
                {{ $evaluator->is_recommended ? '← Recommended' : '' }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Priority Level</label>
          <select class="form-select" name="priority">
            <option value="high">🔴 High — Review ASAP</option>
            <option value="medium">🟡 Medium</option>
            <option value="low">🟢 Low</option>
          </select>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">
            Note to Evaluator
            <span style="font-weight:400;color:var(--muted);">(optional)</span>
          </label>
          <textarea class="form-textarea" name="note" rows="2"
                    placeholder="e.g. Priority review — scholarship deadline on Apr 27…"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline"
                  onclick="document.getElementById('assignModal').classList.remove('open')">Cancel</button>
          <button type="submit" class="btn btn-primary">Assign Evaluator</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  let selectedIds = [];

  function toggleAllRows(checkbox) {
    document.querySelectorAll('.row-check').forEach(cb => {
      cb.checked = checkbox.checked;
      cb.closest('tr').classList.toggle('selected', checkbox.checked);
    });
    updateBulkBar();
  }

  function rowCheck() { updateBulkBar(); }

  function updateBulkBar() {
    const checked = [...document.querySelectorAll('.row-check:checked')];
    selectedIds = checked.map(cb => cb.value);
    const bar = document.getElementById('bulkBar');
    document.getElementById('selectedCount').textContent = selectedIds.length + ' selected';
    if (selectedIds.length > 0) bar.classList.add('visible');
    else bar.classList.remove('visible');
  }

  function openAssignModal(ids) {
    selectedIds = ids || selectedIds;
    document.getElementById('assignAppIds').value = selectedIds.join(',');
    document.getElementById('assignContext').textContent =
      selectedIds.length + ' application(s) · {{ $scholarship->name }}';
    document.getElementById('assignModal').classList.add('open');
  }

  function rejectSelected() {
    if (!selectedIds.length || !confirm('Reject ' + selectedIds.length + ' application(s)?')) return;
    fetch('{{ route("admin.applications.bulk-reject") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ ids: selectedIds })
    }).then(() => location.reload());
  }

  function exportSelected() {
    window.location = '{{ route("admin.applications.export", $scholarship->id) }}?ids=' + selectedIds.join(',');
  }

  function rejectApplication(id) {
    if (!confirm('Reject this application?')) return;
    fetch('{{ route("admin.applications.reject", ":id") }}'.replace(':id', id), {
      method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(() => location.reload());
  }

  function reverseDecision(id, type) {
    if (!confirm('Reverse ' + type + ' for this application?')) return;
    fetch('{{ route("admin.applications.reverse", ":id") }}'.replace(':id', id), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ type })
    }).then(() => location.reload());
  }

  function emailApplicant(id) {
    // TODO: open email composer modal
    alert('Email composer coming soon.');
  }
</script>
@endpush

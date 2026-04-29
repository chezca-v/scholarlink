@extends('admin.layouts.admin')

@section('title', 'Applications — ' . $scholarship->name)

@section('content')
<div class="page-header">
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('admin.scholarships.index') }}" style="color:var(--slate);font-size:13px;display:flex;align-items:center;gap:4px;">
            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Scholarships
        </a>
        <span style="color:var(--muted);">/</span>
        <span style="font-size:13px;color:var(--ink);">{{ Str::limit($scholarship->name, 35) }}</span>
        <span style="color:var(--muted);">/</span>
        <span style="font-size:13px;color:var(--ink);">Applications</span>
    </div>
    <div class="flex items-start justify-between mt-1">
        <div>
            <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">{{ $scholarship->name }}</h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="status-badge status-{{ $scholarship->status }}">{{ ucfirst($scholarship->status) }}</span>
                <span style="font-size:13px;color:var(--slate);">{{ $applications->total() ?? 0 }} total applications</span>
                <span style="font-size:13px;color:var(--slate);">•</span>
                <span style="font-size:13px;color:var(--slate);">{{ $scholarship->filled_slots ?? 0 }}/{{ $scholarship->total_slots }} slots filled</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.scholarships.edit', $scholarship->id) }}" class="btn-ghost flex items-center gap-2" style="font-size:13px;">
                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Scholarship
            </a>
            <a href="{{ route('admin.scholarships.applications.export', $scholarship->id) }}" class="btn-secondary flex items-center gap-2" style="font-size:13px;">
                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- Stage summary --}}
    <div class="grid grid-cols-5 gap-3 mt-5">
        @php
            $stages = [
                ['key' => 'submitted', 'label' => 'Submitted', 'color' => '#6B7280'],
                ['key' => 'review', 'label' => 'Under Review', 'color' => '#2563EB'],
                ['key' => 'approved', 'label' => 'Approved', 'color' => '#16A34A'],
                ['key' => 'rejected', 'label' => 'Rejected', 'color' => '#DC2626'],
                ['key' => 'waitlisted', 'label' => 'Waitlisted', 'color' => '#B45309'],
            ];
        @endphp
        @foreach($stages as $stage)
        <button onclick="updateFilter('status', '{{ $stage['key'] }}')"
            style="padding:14px;border-radius:14px;border:1.5px solid {{ request('status') === $stage['key'] ? $stage['color'] : 'var(--border)' }};background:{{ request('status') === $stage['key'] ? 'rgba('.implode(',', sscanf($stage['color'], '#%02x%02x%02x')).',0.08)' : 'white' }};cursor:pointer;transition:all 0.2s;text-align:left;">
            <p style="font-size:11px;font-weight:600;color:{{ $stage['color'] }};text-transform:uppercase;letter-spacing:0.06em;">{{ $stage['label'] }}</p>
            <p style="font-size:22px;font-weight:700;color:var(--ink);margin-top:4px;">{{ $stageCounts[$stage['key']] ?? 0 }}</p>
        </button>
        @endforeach
    </div>
</div>

<div class="page-content">
    <div class="card overflow-hidden" x-data="bulkActions()">
        {{-- Filter + bulk toolbar --}}
        <div class="flex items-center gap-3 p-4 border-b" style="border-color:var(--border);">
            {{-- Stage tabs --}}
            <div class="flex items-center gap-1 p-1" style="background:var(--page-bg);border-radius:10px;">
                <button class="tab-btn {{ !request('status') ? 'active' : '' }}" onclick="updateFilter('status', '')">All</button>
                <button class="tab-btn {{ request('status') === 'submitted' ? 'active' : '' }}" onclick="updateFilter('status', 'submitted')">Submitted</button>
                <button class="tab-btn {{ request('status') === 'review' ? 'active' : '' }}" onclick="updateFilter('status', 'review')">Under Review</button>
                <button class="tab-btn {{ request('status') === 'approved' ? 'active' : '' }}" onclick="updateFilter('status', 'approved')">Approved</button>
                <button class="tab-btn {{ request('status') === 'rejected' ? 'active' : '' }}" onclick="updateFilter('status', 'rejected')">Rejected</button>
            </div>

            <div class="ml-auto flex items-center gap-2">
                {{-- Bulk actions (shown when selected) --}}
                <div x-show="selected.length > 0" class="flex items-center gap-2" style="display:none;">
                    <span style="font-size:13px;font-weight:600;color:var(--primary);" x-text="selected.length + ' selected'"></span>
                    <button @click="assignEvaluator()" class="btn-secondary" style="font-size:12px;padding:7px 12px;">
                        <svg style="width:14px;height:14px;display:inline;margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/><line x1="20" y1="8" x2="20" y2="14"/></svg>
                        Assign Evaluator
                    </button>
                    <button @click="bulkApprove()" class="btn-ghost" style="font-size:12px;padding:7px 12px;color:#16A34A;border-color:#BBF7D0;">Bulk Approve</button>
                    <button @click="bulkReject()" class="btn-danger" style="font-size:12px;padding:7px 12px;">Bulk Reject</button>
                </div>

                {{-- Sort --}}
                <select class="input-field" style="width:auto;min-width:150px;font-size:13px;" onchange="updateFilter('sort', this.value)">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="score_high" {{ request('sort') === 'score_high' ? 'selected' : '' }}>Score: High → Low</option>
                    <option value="score_low" {{ request('sort') === 'score_low' ? 'selected' : '' }}>Score: Low → High</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="table-header">
                    <tr>
                        <th class="px-5 py-3 text-left" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;width:40px;">
                            <input type="checkbox" @change="toggleAll($event)" style="accent-color:var(--primary);width:15px;height:15px;cursor:pointer;">
                        </th>
                        <th class="px-4 py-3 text-left" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Applicant</th>
                        <th class="px-4 py-3 text-left" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Stage</th>
                        <th class="px-4 py-3 text-left" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">AI Score</th>
                        <th class="px-4 py-3 text-left" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Evaluator</th>
                        <th class="px-4 py-3 text-left" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Submitted</th>
                        <th class="px-4 py-3 text-right" style="font-size:11px;font-weight:700;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications ?? [] as $application)
                    <tr class="table-row">
                        <td class="px-5 py-4">
                            <input type="checkbox" :checked="selected.includes({{ $application->id }})"
                                @change="toggle({{ $application->id }})"
                                style="accent-color:var(--primary);width:15px;height:15px;cursor:pointer;">
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div style="width:34px;height:34px;border-radius:99px;background:rgba(15,76,92,0.1);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--primary);flex-shrink:0;">
                                    {{ strtoupper(substr($application->applicant->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p style="font-size:14px;font-weight:600;color:var(--ink);">{{ $application->applicant->name ?? 'Unknown' }}</p>
                                    <p style="font-size:12px;color:var(--slate);">{{ $application->applicant->course ?? '—' }} · {{ $application->applicant->year_level ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if($application->ai_score !== null)
                            <div class="flex items-center gap-2">
                                <div class="progress-bar-track" style="width:60px;">
                                    <div class="progress-bar-fill" style="width:{{ $application->ai_score }}%;"></div>
                                </div>
                                <span style="font-size:13px;font-weight:700;color:var(--primary);">{{ $application->ai_score }}%</span>
                            </div>
                            @else
                            <span style="font-size:13px;color:var(--muted);">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($application->evaluator)
                                <div class="flex items-center gap-2">
                                    <div style="width:24px;height:24px;border-radius:99px;background:rgba(15,76,92,0.1);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--primary);">
                                        {{ strtoupper(substr($application->evaluator->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:13px;color:var(--ink);">{{ Str::limit($application->evaluator->name, 18) }}</span>
                                </div>
                            @else
                                <button @click="assignSingle({{ $application->id }})"
                                    style="font-size:12px;color:var(--primary);font-weight:600;text-decoration:underline;text-underline-offset:2px;background:none;border:none;cursor:pointer;">
                                    + Assign
                                </button>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <p style="font-size:13px;color:var(--ink);">{{ $application->created_at->format('M d, Y') }}</p>
                            <p style="font-size:11px;color:var(--slate);">{{ $application->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="btn-ghost" style="padding:7px 12px;font-size:12px;">View</a>
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="btn-ghost" style="padding:7px 10px;">
                                        <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="dropdown-menu">
                                        <button class="dropdown-item w-full text-left" @click="assignSingle({{ $application->id }}); open = false">
                                            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                            Assign Evaluator
                                        </button>
                                        @if($application->status === 'submitted' || $application->status === 'review')
                                        <form method="POST" action="{{ route('admin.applications.approve', $application->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item w-full text-left" style="color:#16A34A;">
                                                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                                Approve
                                            </button>
                                        </form>
                                        <button class="dropdown-item w-full text-left danger" onclick="rejectApplication({{ $application->id }})">
                                            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Reject
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <p style="font-size:16px;font-weight:600;color:var(--ink);margin-bottom:6px;">No applications yet</p>
                                <p style="font-size:14px;color:var(--slate);">Applications will appear here once students apply.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($applications) && $applications->hasPages())
        <div class="flex items-center justify-between px-5 py-4 border-t" style="border-color:var(--border);">
            <p style="font-size:13px;color:var(--slate);">
                Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }}
            </p>
            <div class="flex items-center gap-1.5">
                @if(!$applications->onFirstPage())
                    <a href="{{ $applications->previousPageUrl() }}" class="pagination-btn">
                        <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                    </a>
                @endif
                @foreach($applications->getUrlRange(max(1, $applications->currentPage()-2), min($applications->lastPage(), $applications->currentPage()+2)) as $page => $url)
                    <a href="{{ $url }}" class="pagination-btn {{ $page === $applications->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($applications->hasMorePages())
                    <a href="{{ $applications->nextPageUrl() }}" class="pagination-btn">
                        <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Assign Evaluator Modal --}}
<div id="assignModal" style="display:none;position:fixed;inset:0;z-index:50;align-items:center;justify-content:center;background:rgba(10,48,64,0.4);">
    <div class="card p-6" style="width:460px;">
        <h3 class="font-display font-bold text-lg mb-1">Assign Evaluator</h3>
        <p style="font-size:14px;color:var(--slate);margin-bottom:20px;" id="assignModalSubtitle">Select an evaluator to review this application.</p>
        <form method="POST" id="assignForm">
            @csrf @method('PATCH')
            <input type="hidden" name="application_ids" id="assignApplicationIds">
            <div class="space-y-2 mb-5" style="max-height:280px;overflow-y:auto;">
                @foreach($evaluators ?? [] as $evaluator)
                <label style="display:flex;align-items:center;gap-3;padding:12px 14px;border-radius:12px;border:1.5px solid var(--border);cursor:pointer;transition:all 0.2s;"
                    onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
                    <input type="radio" name="evaluator_id" value="{{ $evaluator->id }}" style="accent-color:var(--primary);">
                    <div style="width:36px;height:36px;border-radius:99px;background:rgba(15,76,92,0.1);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--primary);font-size:13px;flex-shrink:0;">
                        {{ strtoupper(substr($evaluator->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p style="font-size:14px;font-weight:600;color:var(--ink);">{{ $evaluator->name }}</p>
                        <p style="font-size:12px;color:var(--slate);">{{ $evaluator->pending_reviews ?? 0 }} pending reviews</p>
                    </div>
                </label>
                @endforeach
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary flex-1">Assign</button>
                <button type="button" onclick="document.getElementById('assignModal').style.display='none'" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateFilter(key, value) {
    const url = new URL(window.location.href);
    if (value) url.searchParams.set(key, value);
    else url.searchParams.delete(key);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

function bulkActions() {
    return {
        selected: [],
        toggle(id) {
            const idx = this.selected.indexOf(id);
            if (idx === -1) this.selected.push(id);
            else this.selected.splice(idx, 1);
        },
        toggleAll(e) {
            if (e.target.checked) {
                this.selected = [{{ $applications->pluck('id')->join(',') }}];
            } else {
                this.selected = [];
            }
        },
        assignEvaluator() {
            document.getElementById('assignApplicationIds').value = this.selected.join(',');
            document.getElementById('assignModalSubtitle').textContent = `Assign evaluator to ${this.selected.length} selected application(s).`;
            document.getElementById('assignForm').action = '{{ route('admin.applications.bulk-assign') }}';
            document.getElementById('assignModal').style.display = 'flex';
        },
        assignSingle(id) {
            document.getElementById('assignApplicationIds').value = id;
            document.getElementById('assignModalSubtitle').textContent = 'Select an evaluator to review this application.';
            document.getElementById('assignForm').action = '{{ route('admin.applications.assign', '__ID__') }}'.replace('__ID__', id);
            document.getElementById('assignModal').style.display = 'flex';
        },
        bulkApprove() {
            if (!confirm(`Approve ${this.selected.length} application(s)?`)) return;
            this.submitBulk('{{ route('admin.applications.bulk-approve') }}');
        },
        bulkReject() {
            if (!confirm(`Reject ${this.selected.length} application(s)?`)) return;
            this.submitBulk('{{ route('admin.applications.bulk-reject') }}');
        },
        submitBulk(url) {
            const form = document.createElement('form');
            form.method = 'POST'; form.action = url;
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input'); method.type = 'hidden'; method.name = '_method'; method.value = 'PATCH';
            const ids = document.createElement('input'); ids.type = 'hidden'; ids.name = 'application_ids'; ids.value = this.selected.join(',');
            form.append(csrf, method, ids);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

function rejectApplication(id) {
    window.location.href = '{{ route('admin.applications.reject-form', '__ID__') }}'.replace('__ID__', id);
}
</script>
@endpush
@endsection
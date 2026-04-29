@extends('admin.layouts.admin')

@section('title', 'Edit Scholarship')

@section('content')
<div class="page-header">
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('admin.scholarships.index') }}" style="color:var(--slate);font-size:13px;display:flex;align-items:center;gap:4px;">
            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Scholarships
        </a>
        <span style="color:var(--muted);">/</span>
        <a href="{{ route('admin.scholarships.show', $scholarship->id) }}" style="color:var(--slate);font-size:13px;">{{ Str::limit($scholarship->name, 30) }}</a>
        <span style="color:var(--muted);">/</span>
        <span style="font-size:13px;color:var(--ink);">Edit</span>
    </div>
    <div class="flex items-start justify-between">
        <div>
            <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">Edit Scholarship</h1>
            <p style="color:var(--slate);font-size:14px;margin-top:4px;">Last updated {{ $scholarship->updated_at->diffForHumans() }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.scholarships.show', $scholarship->id) }}" class="btn-ghost flex items-center gap-2">
                <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Preview
            </a>
            <span class="status-badge status-{{ $scholarship->status }}">{{ ucfirst($scholarship->status) }}</span>
        </div>
    </div>

    {{-- Quick stats --}}
    <div class="flex items-center gap-6 mt-4 py-4 border-t border-b" style="border-color:var(--border);">
        <div>
            <p style="font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Applications</p>
            <p style="font-size:20px;font-weight:700;color:var(--ink);">{{ $scholarship->applications_count ?? 0 }}</p>
        </div>
        <div>
            <p style="font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Slots Filled</p>
            <p style="font-size:20px;font-weight:700;color:var(--ink);">{{ $scholarship->filled_slots ?? 0 }}/{{ $scholarship->total_slots }}</p>
        </div>
        <div>
            <p style="font-size:11px;font-weight:600;color:var(--slate);text-transform:uppercase;letter-spacing:0.06em;">Deadline</p>
            <p style="font-size:20px;font-weight:700;color:var(--ink);">{{ \Carbon\Carbon::parse($scholarship->deadline_date)->format('M d, Y') }}</p>
        </div>
        {{-- Extend Deadline button --}}
        <div class="ml-auto">
            <button type="button" onclick="document.getElementById('extendModal').style.display='flex'" class="btn-secondary flex items-center gap-2">
                <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Extend Deadline
            </button>
        </div>
    </div>
</div>

<div class="page-content">
    <form method="POST" action="{{ route('admin.scholarships.update', $scholarship->id) }}" x-data="weightSliders()">
        @csrf @method('PUT')

        <div class="grid grid-cols-3 gap-6">
            {{-- LEFT: Main details --}}
            <div class="col-span-2 space-y-5">
                {{-- Basic Info --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-base mb-5" style="color:var(--ink);">Basic Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="input-label" for="name">Scholarship Name <span style="color:#DC2626;">*</span></label>
                            <input type="text" id="name" name="name" class="input-field"
                                value="{{ old('name', $scholarship->name) }}" required>
                            @error('name') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="input-label" for="description">Description <span style="color:#DC2626;">*</span></label>
                            <textarea id="description" name="description" class="input-field" rows="4" required>{{ old('description', $scholarship->description) }}</textarea>
                            @error('description') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="input-label" for="organization_id">Organization <span style="color:#DC2626;">*</span></label>
                                <select id="organization_id" name="organization_id" class="input-field" required>
                                    @foreach($organizations ?? [] as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id', $scholarship->organization_id) == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="input-label" for="total_slots">Total Slots <span style="color:#DC2626;">*</span></label>
                                <input type="number" id="total_slots" name="total_slots" class="input-field"
                                    min="{{ $scholarship->filled_slots ?? 1 }}"
                                    value="{{ old('total_slots', $scholarship->total_slots) }}" required>
                                @if(($scholarship->filled_slots ?? 0) > 0)
                                <p class="input-hint">⚠ Cannot set below {{ $scholarship->filled_slots }} (current filled slots)</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="input-label" for="location">Location / Coverage Area</label>
                            <input type="text" id="location" name="location" class="input-field"
                                value="{{ old('location', $scholarship->location) }}">
                        </div>
                    </div>
                </div>

                {{-- Eligibility --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-base mb-5" style="color:var(--ink);">Eligibility Requirements</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="input-label" for="min_gpa">Minimum GPA <span style="color:#DC2626;">*</span></label>
                                <input type="number" id="min_gpa" name="min_gpa" class="input-field"
                                    placeholder="e.g. 85" min="0" max="100" step="0.01"
                                    value="{{ old('min_gpa', $scholarship->min_gpa) }}" required>
                                <p class="input-hint">On a 100-point scale</p>
                            </div>
                            <div>
                                <label class="input-label" for="max_family_income">Max Family Income (₱/mo)</label>
                                <input type="number" id="max_family_income" name="max_family_income" class="input-field"
                                    value="{{ old('max_family_income', $scholarship->max_family_income) }}">
                                <p class="input-hint">Leave blank if no income limit</p>
                            </div>
                        </div>
                        <div>
                            <label class="input-label">Eligible Courses</label>
                            <div x-data="tagInput('courses', {{ json_encode(old('courses', $scholarship->courses ?? [])) }})">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(tag, i) in tags" :key="i">
                                        <span class="tag-chip">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="remove(i)">×</button>
                                            <input type="hidden" name="courses[]" :value="tag">
                                        </span>
                                    </template>
                                </div>
                                <input type="text" class="input-field" placeholder="Type a course and press Enter"
                                    @keydown.enter.prevent="add($event.target.value); $event.target.value = ''"
                                    @keydown.comma.prevent="add($event.target.value); $event.target.value = ''">
                                <p class="input-hint">Press Enter or comma to add. Leave blank for all courses.</p>
                            </div>
                        </div>
                        <div>
                            <label class="input-label">Year Level Eligibility</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'] as $year)
                                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:7px 14px;border-radius:99px;border:1.5px solid var(--border-mid);font-size:13px;font-weight:500;">
                                    <input type="checkbox" name="year_levels[]" value="{{ $year }}"
                                        style="accent-color:var(--primary);"
                                        {{ in_array($year, old('year_levels', $scholarship->year_levels ?? [])) ? 'checked' : '' }}>
                                    {{ $year }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Scoring Weights --}}
                <div class="card p-6">
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <h2 class="font-display font-bold text-base" style="color:var(--ink);">Scoring Weights</h2>
                            <p style="font-size:13px;color:var(--slate);margin-top:3px;">Must sum to exactly 100%</p>
                        </div>
                        <div style="padding:6px 14px;border-radius:10px;font-size:13px;font-weight:700;"
                            :style="gpaWeight + incomeWeight === 100 ? 'background:#DCFCE7;color:#16A34A;' : 'background:#FEE2E2;color:#DC2626;'">
                            <span x-text="gpaWeight + incomeWeight"></span>/100
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="input-label" style="margin-bottom:0;">GPA / Academic Grade</label>
                                <span style="font-size:14px;font-weight:700;color:var(--primary);" x-text="gpaWeight + '%'"></span>
                            </div>
                            <input type="range" min="0" max="100" step="5"
                                x-model.number="gpaWeight" @input="syncWeights('gpa')"
                                class="w-full" style="accent-color:var(--primary);height:6px;cursor:pointer;">
                            <input type="hidden" name="gpa_weight" :value="gpaWeight">
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="input-label" style="margin-bottom:0;">Family Income</label>
                                <span style="font-size:14px;font-weight:700;color:var(--accent);" x-text="incomeWeight + '%'"></span>
                            </div>
                            <input type="range" min="0" max="100" step="5"
                                x-model.number="incomeWeight" @input="syncWeights('income')"
                                class="w-full" style="accent-color:var(--accent);height:6px;cursor:pointer;">
                            <input type="hidden" name="income_weight" :value="incomeWeight">
                        </div>
                        <div>
                            <p style="font-size:12px;color:var(--slate);margin-bottom:8px;">Weight Distribution Preview</p>
                            <div style="display:flex;border-radius:10px;overflow:hidden;height:28px;">
                                <div :style="`width:${gpaWeight}%;background:var(--primary);display:flex;align-items:center;justify-content:center;`" class="transition-all duration-300">
                                    <span style="font-size:11px;font-weight:700;color:white;" x-show="gpaWeight >= 15" x-text="'GPA ' + gpaWeight + '%'"></span>
                                </div>
                                <div :style="`width:${incomeWeight}%;background:var(--accent);display:flex;align-items:center;justify-content:center;`" class="transition-all duration-300">
                                    <span style="font-size:11px;font-weight:700;color:var(--primary);" x-show="incomeWeight >= 15" x-text="'Income ' + incomeWeight + '%'"></span>
                                </div>
                            </div>
                            <p x-show="gpaWeight + incomeWeight !== 100" style="font-size:12px;color:#DC2626;margin-top:6px;">⚠ Weights must sum to exactly 100%</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Settings --}}
            <div class="space-y-5">
                <div class="card p-5">
                    <h2 class="font-display font-bold text-base mb-4" style="color:var(--ink);">Publish Settings</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="input-label" for="status">Status</label>
                            <select id="status" name="status" class="input-field">
                                <option value="draft" {{ old('status', $scholarship->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $scholarship->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ old('status', $scholarship->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div>
                            <label class="input-label" for="open_date">Application Opens</label>
                            <input type="date" id="open_date" name="open_date" class="input-field"
                                value="{{ old('open_date', optional($scholarship->open_date)->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="input-label" for="deadline_date">Application Deadline <span style="color:#DC2626;">*</span></label>
                            <input type="date" id="deadline_date" name="deadline_date" class="input-field"
                                value="{{ old('deadline_date', optional($scholarship->deadline_date)->format('Y-m-d') ?? \Carbon\Carbon::parse($scholarship->deadline_date)->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h2 class="font-display font-bold text-base mb-4" style="color:var(--ink);">Screening Settings</h2>
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">Blind Screening</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Hide applicant identity from evaluators</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" name="blind_screening" value="1" {{ $scholarship->blind_screening ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                        <hr style="border-color:var(--border);">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">Conflict Detection</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Warn applicants about conflicts</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" name="conflict_detection" value="1" {{ $scholarship->conflict_detection ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                        <hr style="border-color:var(--border);">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">SMS Notifications</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Via ESP32 + SIM800L hardware</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" name="sms_enabled" value="1" {{ $scholarship->sms_enabled ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card p-5" x-data="tagInput('tags', {{ json_encode($scholarship->tags ?? []) }})">
                    <h2 class="font-display font-bold text-base mb-4" style="color:var(--ink);">Tags</h2>
                    <div class="flex flex-wrap gap-2 mb-2 min-h-8">
                        <template x-for="(tag, i) in tags" :key="i">
                            <span class="tag-chip">
                                <span x-text="tag"></span>
                                <button type="button" @click="remove(i)">×</button>
                                <input type="hidden" name="tags[]" :value="tag">
                            </span>
                        </template>
                        <span x-show="tags.length === 0" style="font-size:13px;color:var(--muted);">No tags yet</span>
                    </div>
                    <input type="text" class="input-field" placeholder="Add tag and press Enter…"
                        @keydown.enter.prevent="add($event.target.value); $event.target.value = ''"
                        @keydown.comma.prevent="add($event.target.value); $event.target.value = ''">
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2">
                        <svg style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.scholarships.show', $scholarship->id) }}" class="btn-ghost w-full text-center">Cancel</a>
                </div>

                {{-- Danger zone --}}
                <div style="border:1.5px solid #FCA5A5;border-radius:16px;padding:16px;">
                    <p style="font-size:13px;font-weight:700;color:#DC2626;margin-bottom:10px;">Danger Zone</p>
                    @if($scholarship->status !== 'closed')
                    <form method="POST" action="{{ route('admin.scholarships.toggle', $scholarship->id) }}" class="mb-2">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-danger w-full text-sm">Close Scholarship</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.scholarships.destroy', $scholarship->id) }}"
                        onsubmit="return confirm('Permanently delete this scholarship and all applications? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" style="width:100%;background:transparent;border:1.5px solid #FCA5A5;color:#DC2626;padding:8px 16px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">
                            Delete Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Extend Deadline Modal --}}
<div id="extendModal" style="display:none;position:fixed;inset:0;z-index:50;align-items:center;justify-content:center;background:rgba(10,48,64,0.4);">
    <div class="card p-6" style="width:420px;">
        <h3 class="font-display font-bold text-lg mb-1">Extend Deadline</h3>
        <p style="font-size:14px;color:var(--slate);margin-bottom:20px;">
            Current: <strong>{{ \Carbon\Carbon::parse($scholarship->deadline_date)->format('M d, Y') }}</strong>
        </p>
        <form method="POST" action="{{ route('admin.scholarships.extend', $scholarship->id) }}">
            @csrf @method('PATCH')
            <label class="input-label">New Deadline</label>
            <input type="date" name="deadline_date" class="input-field" required
                min="{{ now()->addDay()->format('Y-m-d') }}">
            <div class="flex gap-3 mt-5">
                <button type="submit" class="btn-primary flex-1">Extend Deadline</button>
                <button type="button" onclick="document.getElementById('extendModal').style.display='none'" class="btn-ghost flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function weightSliders() {
    return {
        gpaWeight: {{ old('gpa_weight', $scholarship->gpa_weight ?? 60) }},
        incomeWeight: {{ old('income_weight', $scholarship->income_weight ?? 40) }},
        syncWeights(changed) {
            if (changed === 'gpa') this.incomeWeight = 100 - this.gpaWeight;
            else this.gpaWeight = 100 - this.incomeWeight;
        }
    }
}

function tagInput(fieldName, initial = []) {
    return {
        tags: initial,
        add(value) {
            const v = value.trim().replace(/,$/, '');
            if (v && !this.tags.includes(v)) this.tags.push(v);
        },
        remove(i) { this.tags.splice(i, 1); }
    }
}
</script>
@endpush
@endsection
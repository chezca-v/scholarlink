@extends('admin.layouts.admin')

@section('title', 'Create Scholarship')

@section('content')
<div class="page-header">
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('admin.scholarships.index') }}" style="color:var(--slate);font-size:13px;display:flex;align-items:center;gap:4px;">
            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Scholarships
        </a>
        <span style="color:var(--muted);">/</span>
        <span style="font-size:13px;color:var(--ink);">Create New</span>
    </div>
    <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">Create Scholarship</h1>
    <p style="color:var(--slate);font-size:14px;margin-top:4px;">Fill in the details below to publish a new scholarship listing.</p>
</div>

<div class="page-content">
    <form method="POST" action="{{ route('admin.scholarships.store') }}" x-data="scholarshipForm()" @submit.prevent="submitForm">
        @csrf

        <div class="grid grid-cols-3 gap-6">
            {{-- LEFT COLUMN: Main details --}}
            <div class="col-span-2 space-y-5">
                {{-- Basic Info Card --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-base mb-5" style="color:var(--ink);">Basic Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="input-label" for="name">Scholarship Name <span style="color:#DC2626;">*</span></label>
                            <input type="text" id="name" name="name" class="input-field" placeholder="e.g. PLM Academic Excellence Scholarship" value="{{ old('name') }}" required>
                            @error('name') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="input-label" for="description">Description <span style="color:#DC2626;">*</span></label>
                            <textarea id="description" name="description" class="input-field" rows="4" placeholder="Describe what this scholarship covers, who it's for, and any special requirements…" required>{{ old('description') }}</textarea>
                            @error('description') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="input-label" for="organization_id">Organization <span style="color:#DC2626;">*</span></label>
                                <select id="organization_id" name="organization_id" class="input-field" required>
                                    <option value="">Select organization…</option>
                                    @foreach($organizations ?? [] as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                @error('organization_id') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="input-label" for="total_slots">Total Slots <span style="color:#DC2626;">*</span></label>
                                <input type="number" id="total_slots" name="total_slots" class="input-field" placeholder="e.g. 50" min="1" value="{{ old('total_slots') }}" required>
                                @error('total_slots') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="input-label" for="location">Location / Coverage Area</label>
                            <input type="text" id="location" name="location" class="input-field" placeholder="e.g. Manila, NCR or Nationwide" value="{{ old('location') }}">
                        </div>
                    </div>
                </div>

                {{-- Eligibility Card --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-base mb-5" style="color:var(--ink);">Eligibility Requirements</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="input-label" for="min_gpa">Minimum GPA <span style="color:#DC2626;">*</span></label>
                                <input type="number" id="min_gpa" name="min_gpa" class="input-field" placeholder="e.g. 85" min="0" max="100" step="0.01" value="{{ old('min_gpa') }}" required>
                                <p class="input-hint">On a 100-point scale</p>
                                @error('min_gpa') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="input-label" for="max_family_income">Max Family Income (₱/mo)</label>
                                <input type="number" id="max_family_income" name="max_family_income" class="input-field" placeholder="e.g. 25000" min="0" value="{{ old('max_family_income') }}">
                                <p class="input-hint">Leave blank if no income limit</p>
                            </div>
                        </div>
                        <div>
                            <label class="input-label">Eligible Courses</label>
                            <div x-data="tagInput('courses', {{ json_encode(old('courses', [])) }})">
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <template x-for="(tag, i) in tags" :key="i">
                                        <span class="tag-chip">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="remove(i)" style="line-height:1;">×</button>
                                            <input type="hidden" :name="`courses[]`" :value="tag">
                                        </span>
                                    </template>
                                </div>
                                <input type="text" class="input-field" placeholder="Type a course and press Enter (e.g. BS Computer Engineering)"
                                    @keydown.enter.prevent="add($event.target.value); $event.target.value = ''"
                                    @keydown.comma.prevent="add($event.target.value); $event.target.value = ''">
                                <p class="input-hint">Press Enter or comma to add. Leave blank for all courses.</p>
                            </div>
                        </div>
                        <div>
                            <label class="input-label">Year Level Eligibility</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'] as $year)
                                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:7px 14px;border-radius:99px;border:1.5px solid var(--border-mid);font-size:13px;font-weight:500;transition:all 0.2s;"
                                    :class="yearLevels.includes('{{ $year }}') ? 'border-primary bg-teal-50 text-primary' : 'text-slate'"
                                    x-data>
                                    <input type="checkbox" name="year_levels[]" value="{{ $year }}"
                                        style="accent-color:var(--primary);"
                                        {{ in_array($year, old('year_levels', [])) ? 'checked' : '' }}>
                                    {{ $year }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Scoring Weights Card --}}
                <div class="card p-6" x-data="weightSliders()">
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <h2 class="font-display font-bold text-base" style="color:var(--ink);">Scoring Weights</h2>
                            <p style="font-size:13px;color:var(--slate);margin-top:3px;">Set how GPA and income factor into the application score.</p>
                        </div>
                        <div style="padding:6px 14px;border-radius:10px;font-size:13px;font-weight:700;transition:all 0.2s;"
                            :style="gpaWeight + incomeWeight === 100 ? 'background:#DCFCE7;color:#16A34A;' : 'background:#FEE2E2;color:#DC2626;'">
                            <span x-text="gpaWeight + incomeWeight"></span>/100
                        </div>
                    </div>

                    <div class="space-y-5">
                        {{-- GPA Weight --}}
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="input-label" style="margin-bottom:0;">GPA / Academic Grade</label>
                                <span style="font-size:14px;font-weight:700;color:var(--primary);" x-text="gpaWeight + '%'"></span>
                            </div>
                            <input type="range" name="gpa_weight" min="0" max="100" step="5"
                                x-model.number="gpaWeight"
                                @input="syncWeights('gpa')"
                                class="w-full" style="accent-color:var(--primary);height:6px;cursor:pointer;">
                            <input type="hidden" name="gpa_weight" :value="gpaWeight">
                        </div>

                        {{-- Income Weight --}}
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="input-label" style="margin-bottom:0;">Family Income</label>
                                <span style="font-size:14px;font-weight:700;color:var(--accent);" x-text="incomeWeight + '%'"></span>
                            </div>
                            <input type="range" name="income_weight" min="0" max="100" step="5"
                                x-model.number="incomeWeight"
                                @input="syncWeights('income')"
                                class="w-full" style="accent-color:var(--accent);height:6px;cursor:pointer;">
                            <input type="hidden" name="income_weight" :value="incomeWeight">
                        </div>

                        {{-- Weight preview bar --}}
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

            {{-- RIGHT COLUMN: Settings --}}
            <div class="space-y-5">
                {{-- Publish settings --}}
                <div class="card p-5">
                    <h2 class="font-display font-bold text-base mb-4" style="color:var(--ink);">Publish Settings</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="input-label" for="status">Status</label>
                            <select id="status" name="status" class="input-field">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active (Published)</option>
                            </select>
                        </div>
                        <div>
                            <label class="input-label" for="open_date">Application Opens</label>
                            <input type="date" id="open_date" name="open_date" class="input-field" value="{{ old('open_date') }}">
                        </div>
                        <div>
                            <label class="input-label" for="deadline_date">Application Deadline <span style="color:#DC2626;">*</span></label>
                            <input type="date" id="deadline_date" name="deadline_date" class="input-field" value="{{ old('deadline_date') }}" required>
                            @error('deadline_date') <p style="font-size:12px;color:#DC2626;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Screening settings --}}
                <div class="card p-5">
                    <h2 class="font-display font-bold text-base mb-4" style="color:var(--ink);">Screening Settings</h2>
                    <div class="space-y-4">
                        {{-- Blind Screening Toggle --}}
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">Blind Screening</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Hide applicant names and schools from evaluators</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" name="blind_screening" value="1" {{ old('blind_screening') ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                        <hr style="border-color:var(--border);">
                        {{-- Conflict detection --}}
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">Conflict Detection</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Warn applicants about conflicting scholarships</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" name="conflict_detection" value="1" {{ old('conflict_detection', '1') ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                        <hr style="border-color:var(--border);">
                        {{-- SMS Notifications --}}
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">SMS Notifications</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Send SMS via hardware gateway on status changes</p>
                            </div>
                            <label class="toggle">
                                <input type="checkbox" name="sms_enabled" value="1" {{ old('sms_enabled', '1') ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Tags --}}
                <div class="card p-5" x-data="tagInput('tags', {{ json_encode(old('tags', [])) }})">
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
                    <p class="input-hint">e.g. merit-based, STEM, need-based</p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3">
                    <button type="submit" name="action" value="publish" class="btn-amber w-full flex items-center justify-center gap-2">
                        <svg style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Publish Scholarship
                    </button>
                    <button type="submit" name="action" value="draft" class="btn-ghost w-full">Save as Draft</button>
                    <a href="{{ route('admin.scholarships.index') }}" class="btn-ghost w-full text-center" style="color:#DC2626;border-color:#FECACA;">Discard</a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function scholarshipForm() {
    return {
        submitForm(e) {
            const form = e.target;
            const submitter = e.submitter;
            if (submitter) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'action';
                input.value = submitter.value;
                form.appendChild(input);
            }
            form.submit();
        }
    }
}

function weightSliders() {
    return {
        gpaWeight: {{ old('gpa_weight', 60) }},
        incomeWeight: {{ old('income_weight', 40) }},
        syncWeights(changed) {
            if (changed === 'gpa') {
                this.incomeWeight = 100 - this.gpaWeight;
            } else {
                this.gpaWeight = 100 - this.incomeWeight;
            }
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
@extends('admin.layouts.admin')

@section('title', 'Edit Scholarship')

@section('content')
<div class="page-header mb-6">
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
</div>

<div class="page-content" x-data="scholarshipForm()">
    <form method="POST" action="{{ route('admin.scholarships.update', $scholarship->id) }}">
        @csrf
        @method('PUT')

        {{-- Hidden fields to store dynamic arrays as strings --}}
        <input type="hidden" name="eligibility" :value="eligibility.join('\n')">
        <input type="hidden" name="benefits" :value="benefits.join('\n')">
        <input type="hidden" name="requirements" :value="requirements.join('\n')">

        <div class="grid grid-cols-3 gap-6">
            {{-- LEFT COLUMN: Main details --}}
            <div class="col-span-2 space-y-6">
                
                {{-- Basic Info Card --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-lg mb-5 border-b border-[var(--border)] pb-3" style="color:var(--ink);">Basic Information</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="input-label" for="name">Scholarship Name <span style="color:#DC2626;">*</span></label>
                                <input type="text" id="name" name="name" class="input-field" placeholder="e.g. Academic Excellence Scholarship" value="{{ old('name', $scholarship->name) }}" required>
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="input-label" for="provider_name">Provider Name <span style="color:#DC2626;">*</span></label>
                                <input type="text" id="provider_name" name="provider_name" class="input-field" placeholder="e.g. ScholarLink Foundation" value="{{ old('provider_name', $scholarship->provider_name) }}" required>
                                @error('provider_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="input-label" for="tagline">Tagline</label>
                            <input type="text" id="tagline" name="tagline" class="input-field" placeholder="A short, catchy summary..." value="{{ old('tagline', $scholarship->tagline) }}">
                        </div>

                        <div>
                            <label class="input-label" for="description">Full Description <span style="color:#DC2626;">*</span></label>
                            <textarea id="description" name="description" class="input-field" rows="4" placeholder="Describe the overarching goal of this scholarship..." required>{{ old('description', $scholarship->description) }}</textarea>
                            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="input-label" for="slots">Total Slots <span style="color:#DC2626;">*</span></label>
                                <input type="number" id="slots" name="slots" class="input-field" placeholder="e.g. 50" min="1" value="{{ old('slots', $scholarship->slots) }}" required>
                                @error('slots') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="input-label" for="contact_email">Contact Email</label>
                                <input type="email" id="contact_email" name="contact_email" class="input-field" placeholder="info@example.com" value="{{ old('contact_email', $scholarship->contact_email) }}">
                            </div>
                            <div>
                                <label class="input-label" for="website">Website URL</label>
                                <input type="url" id="website" name="website" class="input-field" placeholder="https://..." value="{{ old('website', $scholarship->website) }}">
                            </div>
                        </div>
                        
                        <div>
                            <label class="input-label" for="address">Address</label>
                            <input type="text" id="address" name="address" class="input-field" placeholder="e.g. Manila, NCR or Nationwide" value="{{ old('address', $scholarship->address) }}">
                        </div>
                    </div>
                </div>

                {{-- Requirements & Details Card (Dynamic Lists) --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-lg mb-5 border-b border-[var(--border)] pb-3" style="color:var(--ink);">Details & Requirements</h2>
                    <div class="space-y-6">
                        
                        {{-- Eligibility --}}
                        <div>
                            <label class="input-label flex items-center justify-between">
                                <span>Eligibility Criteria <span style="color:#DC2626;">*</span></span>
                            </label>
                            <div class="space-y-2">
                                <template x-for="(item, index) in eligibility" :key="'elig'+index">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <input type="text" class="input-field" x-model="eligibility[index]" placeholder="e.g. Must be a graduating SHS student" required>
                                        </div>
                                        <button type="button" @click="eligibility.splice(index, 1)" x-show="eligibility.length > 1" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="eligibility.push('')" class="mt-2 text-sm font-semibold text-[var(--primary)] hover:underline flex items-center gap-1">
                                + Add another criteria
                            </button>
                        </div>

                        {{-- Benefits --}}
                        <div>
                            <label class="input-label flex items-center justify-between">
                                <span>Benefits Overview <span style="color:#DC2626;">*</span></span>
                            </label>
                            <div class="space-y-2">
                                <template x-for="(item, index) in benefits" :key="'ben'+index">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <input type="text" class="input-field" x-model="benefits[index]" placeholder="e.g. Full Tuition Coverage" required>
                                        </div>
                                        <button type="button" @click="benefits.splice(index, 1)" x-show="benefits.length > 1" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="benefits.push('')" class="mt-2 text-sm font-semibold text-[var(--primary)] hover:underline flex items-center gap-1">
                                + Add another benefit
                            </button>
                        </div>

                        {{-- Requirements --}}
                        <div>
                            <label class="input-label flex items-center justify-between">
                                <span>Document Requirements <span style="color:#DC2626;">*</span></span>
                            </label>
                            <div class="space-y-2">
                                <template x-for="(item, index) in requirements" :key="'req'+index">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <input type="text" class="input-field" x-model="requirements[index]" placeholder="e.g. Certificate of Good Moral" required>
                                        </div>
                                        <button type="button" @click="requirements.splice(index, 1)" x-show="requirements.length > 1" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="requirements.push('')" class="mt-2 text-sm font-semibold text-[var(--primary)] hover:underline flex items-center gap-1">
                                + Add another document
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Target Demographics Card --}}
                <div class="card p-6">
                    <h2 class="font-display font-bold text-lg mb-5 border-b border-[var(--border)] pb-3" style="color:var(--ink);">Target Demographics</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <label class="input-label !mb-0" for="gpa_requirement">Minimum GPA <span style="color:#DC2626;">*</span></label>
                                    <select x-model="gpaScale" class="text-xs border-0 bg-gray-50 text-gray-600 rounded py-1 pl-2 pr-6 cursor-pointer focus:ring-0">
                                        <option value="college">College (1.0 - 5.0)</option>
                                        <option value="shs">SHS (100 Point)</option>
                                    </select>
                                </div>
                                <input type="number" id="gpa_requirement" name="gpa_requirement" class="input-field" 
                                    :placeholder="gpaScale === 'college' ? 'e.g. 1.5' : 'e.g. 85'" 
                                    :min="gpaScale === 'college' ? 0 : 70" 
                                    :max="gpaScale === 'college' ? 5 : 100" 
                                    :step="gpaScale === 'college' ? '0.01' : '1'" 
                                    value="{{ old('gpa_requirement', $scholarship->gpa_requirement) }}" required>
                                <p class="input-hint" x-text="gpaScale === 'college' ? 'Enter the equivalent (e.g. 1.5, 2.0)' : 'Enter grade out of 100'"></p>
                                @error('gpa_requirement') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="input-label" for="income_bracket">Family Income Bracket</label>
                                <select id="income_bracket" name="income_bracket" class="input-field">
                                    <option value="">Any Income</option>
                                    <option value="Below ₱10,000" {{ old('income_bracket', $scholarship->income_bracket) == 'Below ₱10,000' ? 'selected' : '' }}>Below ₱10,000</option>
                                    <option value="₱10,001 - ₱20,000" {{ old('income_bracket', $scholarship->income_bracket) == '₱10,001 - ₱20,000' ? 'selected' : '' }}>₱10,001 - ₱20,000</option>
                                    <option value="₱20,001 - ₱40,000" {{ old('income_bracket', $scholarship->income_bracket) == '₱20,001 - ₱40,000' ? 'selected' : '' }}>₱20,001 - ₱40,000</option>
                                    <option value="₱40,001 - ₱80,000" {{ old('income_bracket', $scholarship->income_bracket) == '₱40,001 - ₱80,000' ? 'selected' : '' }}>₱40,001 - ₱80,000</option>
                                    <option value="Above ₱80,000" {{ old('income_bracket', $scholarship->income_bracket) == 'Above ₱80,000' ? 'selected' : '' }}>Above ₱80,000</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="input-label mb-2">Eligible Courses</label>
                            <div class="p-4 rounded-xl border border-[var(--border)] bg-gray-50 flex items-center justify-between">
                                <div class="flex-1">
                                    <div x-show="selectedTags.length > 0 || customTags.length > 0" class="flex flex-wrap gap-2">
                                        <template x-for="(tag, i) in selectedTags" :key="'s'+i">
                                            <span class="tag-chip shadow-sm"><span x-text="tag"></span> <input type="hidden" name="tags[]" :value="tag"></span>
                                        </template>
                                        <template x-for="(tag, i) in customTags" :key="'c'+i">
                                            <span class="tag-chip shadow-sm bg-blue-50 text-blue-700"><span x-text="tag"></span> <input type="hidden" name="tags[]" :value="tag"></span>
                                        </template>
                                    </div>
                                    <p x-show="selectedTags.length === 0 && customTags.length === 0" class="text-sm text-gray-500">All courses eligible by default.</p>
                                </div>
                                <button type="button" @click="courseModalOpen = true" class="btn-secondary !py-1.5 ml-4 shrink-0">
                                    Select Courses
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Scoring Weights Card --}}
                <div class="card p-6">
                    <div class="flex items-start justify-between mb-5 border-b border-[var(--border)] pb-3">
                        <div>
                            <h2 class="font-display font-bold text-lg" style="color:var(--ink);">Scoring Weights</h2>
                            <p style="font-size:13px;color:var(--slate);margin-top:3px;">Set how GPA and income factor into the AI Match score.</p>
                        </div>
                        <div style="padding:6px 14px;border-radius:10px;font-size:13px;font-weight:700;transition:all 0.2s;"
                            :style="gpaWeight + incomeWeight === 100 ? 'background:#DCFCE7;color:#16A34A;' : 'background:#FEE2E2;color:#DC2626;'">
                            <span x-text="gpaWeight + incomeWeight"></span>/100
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- GPA Weight --}}
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="input-label" style="margin-bottom:0;">GPA / Academic Grade Weight</label>
                                <span style="font-size:14px;font-weight:700;color:var(--primary);" x-text="gpaWeight + '%'"></span>
                            </div>
                            <input type="range" name="weight_gpa" min="0" max="100" step="5"
                                x-model.number="gpaWeight"
                                @input="syncWeights('gpa')"
                                class="w-full" style="accent-color:var(--primary);height:6px;cursor:pointer;">
                        </div>

                        {{-- Income Weight --}}
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="input-label" style="margin-bottom:0;">Family Income Weight</label>
                                <span style="font-size:14px;font-weight:700;color:var(--accent);" x-text="incomeWeight + '%'"></span>
                            </div>
                            <input type="range" name="weight_income" min="0" max="100" step="5"
                                x-model.number="incomeWeight"
                                @input="syncWeights('income')"
                                class="w-full" style="accent-color:var(--accent);height:6px;cursor:pointer;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Settings --}}
            <div class="space-y-6">
                {{-- Publish settings --}}
                <div class="card p-5">
                    <h2 class="font-display font-bold text-lg mb-4 border-b border-[var(--border)] pb-2" style="color:var(--ink);">Publishing</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="input-label" for="status">Status</label>
                            <select id="status" name="status" class="input-field">
                                <option value="draft" {{ old('status', $scholarship->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="open" {{ old('status', $scholarship->status) === 'open' ? 'selected' : '' }}>Open (Published)</option>
                                <option value="closed" {{ old('status', $scholarship->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div>
                            <label class="input-label" for="open_date">Application Opens <span style="color:#DC2626;">*</span></label>
                            <input type="date" id="open_date" name="open_date" class="input-field" value="{{ old('open_date', optional($scholarship->open_date)->format('Y-m-d')) }}" required>
                            @error('open_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="input-label" for="deadline">Application Deadline <span style="color:#DC2626;">*</span></label>
                            <input type="date" id="deadline" name="deadline" class="input-field" value="{{ old('deadline', optional($scholarship->deadline)->format('Y-m-d')) }}" required>
                            @error('deadline') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Screening settings --}}
                <div class="card p-5">
                    <h2 class="font-display font-bold text-lg mb-4 border-b border-[var(--border)] pb-2" style="color:var(--ink);">Screening Toggles</h2>
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">Blind Screening</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Hide applicant names from evaluators</p>
                            </div>
                            <label class="toggle mt-1">
                                <input type="hidden" name="blind_screening" value="0">
                                <input type="checkbox" name="blind_screening" value="1" {{ old('blind_screening', $scholarship->blind_screening) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb shadow-sm"></span>
                            </label>
                        </div>
                        <hr style="border-color:var(--border);">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--ink);">AI Recommendations</p>
                                <p style="font-size:12px;color:var(--slate);margin-top:2px;">Recommend to suitable applicants</p>
                            </div>
                            <label class="toggle mt-1">
                                <input type="hidden" name="ai_match_enabled" value="0">
                                <input type="checkbox" name="ai_match_enabled" value="1" {{ old('ai_match_enabled', $scholarship->ai_match_enabled) ? 'checked' : '' }}>
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb shadow-sm"></span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3 sticky top-20">
                    <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2 py-3 text-sm shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.scholarships.index') }}" class="btn-ghost w-full text-center py-2.5 hover:bg-red-50 hover:text-red-600 transition-colors" style="color:#DC2626;border-color:#FECACA;">Cancel</a>
                </div>
            </div>
        </div>

        {{-- COURSES MODAL --}}
        <div x-show="courseModalOpen" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div @click.outside="courseModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[85vh] flex flex-col overflow-hidden transform transition-all" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-[var(--border)] flex justify-between items-center bg-gray-50">
                    <h3 class="font-display font-bold text-lg text-[var(--ink)]">Select Eligible Courses</h3>
                    <button type="button" @click="courseModalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto flex-1">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="font-bold text-[var(--ink)] text-sm mb-3 pb-2 border-b">Engineering & Technology</p>
                            <div class="space-y-2">
                                <template x-for="course in standardCourses.eng" :key="course">
                                    <label class="flex items-center gap-3 cursor-pointer text-sm text-[var(--slate)] hover:text-[var(--primary)] transition-colors">
                                        <input type="checkbox" :value="course" x-model="selectedTags" class="w-4 h-4 rounded text-[var(--primary)] border-gray-300 focus:ring-[var(--primary)]">
                                        <span x-text="course"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                        <div>
                            <p class="font-bold text-[var(--ink)] text-sm mb-3 pb-2 border-b">Business & Sciences</p>
                            <div class="space-y-2">
                                <template x-for="course in standardCourses.biz" :key="course">
                                    <label class="flex items-center gap-3 cursor-pointer text-sm text-[var(--slate)] hover:text-[var(--primary)] transition-colors">
                                        <input type="checkbox" :value="course" x-model="selectedTags" class="w-4 h-4 rounded text-[var(--primary)] border-gray-300 focus:ring-[var(--primary)]">
                                        <span x-text="course"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl border border-[var(--border)]">
                        <p class="font-bold text-[var(--ink)] text-sm mb-2">Other Courses / Custom Tags</p>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(tag, i) in customTags" :key="i">
                                <span class="tag-chip shadow-sm bg-blue-50 border-blue-200 text-blue-700">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="customTags.splice(i, 1)" class="hover:text-red-500 ml-1" style="line-height:1;">×</button>
                                </span>
                            </template>
                        </div>
                        <input type="text" class="input-field !bg-white focus:ring-2 focus:ring-[var(--primary)]/20" placeholder="Type a course and press Enter..."
                            @keydown.enter.prevent="addCustomTag($event.target.value); $event.target.value = ''"
                            @keydown.comma.prevent="addCustomTag($event.target.value); $event.target.value = ''">
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[var(--border)] flex justify-end gap-3 bg-gray-50">
                    <button type="button" @click="courseModalOpen = false" class="btn-primary px-8">Done</button>
                </div>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
function scholarshipForm() {
    return {
        // Dynamic Fields setup
        eligibility: {!! json_encode(old('eligibility', $scholarship->eligibility) ? explode("\n", old('eligibility', $scholarship->eligibility)) : ['']) !!},
        benefits: {!! json_encode(old('benefits', $scholarship->benefits) ? explode("\n", old('benefits', $scholarship->benefits)) : ['']) !!},
        requirements: {!! json_encode(old('requirements', $scholarship->requirements) ? explode("\n", old('requirements', $scholarship->requirements)) : ['']) !!},
        
        // GPA Scale
        gpaScale: {{ (old('gpa_requirement', $scholarship->gpa_requirement) > 5) ? "'shs'" : "'college'" }},

        // Scoring Weights
        gpaWeight: {{ old('weight_gpa', $scholarship->weight_gpa ?? 60) }},
        incomeWeight: {{ old('weight_income', $scholarship->weight_income ?? 40) }},
        syncWeights(changed) {
            if (changed === 'gpa') {
                this.incomeWeight = 100 - this.gpaWeight;
            } else {
                this.gpaWeight = 100 - this.incomeWeight;
            }
        },

        // Courses Modal setup
        courseModalOpen: false,
        standardCourses: {
            eng: ['BS Civil Engineering', 'BS Computer Engineering', 'BS Information Technology', 'BS Computer Science'],
            biz: ['BS Accountancy', 'BS Business Administration', 'BS Nursing', 'BS Biology', 'BA Psychology']
        },
        selectedTags: {!! json_encode(array_values(array_filter(old('tags', $scholarship->tags ?? []), function($tag) {
            $standards = ['BS Civil Engineering', 'BS Computer Engineering', 'BS Information Technology', 'BS Computer Science', 'BS Accountancy', 'BS Business Administration', 'BS Nursing', 'BS Biology', 'BA Psychology'];
            return in_array($tag, $standards);
        }))) !!},
        customTags: {!! json_encode(array_values(array_filter(old('tags', $scholarship->tags ?? []), function($tag) {
            $standards = ['BS Civil Engineering', 'BS Computer Engineering', 'BS Information Technology', 'BS Computer Science', 'BS Accountancy', 'BS Business Administration', 'BS Nursing', 'BS Biology', 'BA Psychology'];
            return !in_array($tag, $standards);
        }))) !!},
        
        addCustomTag(value) {
            const v = value.trim().replace(/,$/, '');
            const allStandards = [...this.standardCourses.eng, ...this.standardCourses.biz];
            if (v && !this.customTags.includes(v) && !this.selectedTags.includes(v) && !allStandards.includes(v)) {
                this.customTags.push(v);
            }
        }
    }
}
</script>
@endpush
@endsection
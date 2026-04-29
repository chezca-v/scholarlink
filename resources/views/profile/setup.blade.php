@extends('layouts.applicant')

@section('title', 'ScholarLink — Profile Setup')

@push('styles')
<style>
/* ── UI TOKENS & SETUP CARD ── */
.setup-container {
    max-width: 800px;
    margin: 0 auto;
    padding-bottom: 40px;
}
.setup-header {
    text-align: center;
    margin-bottom: 32px;
}
.setup-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 800;
    color: var(--teal-950, #0f3a44);
    margin-bottom: 8px;
}
.setup-subtitle {
    font-size: 14px;
    color: var(--slate, #64748b);
}

/* ── PROGRESS BAR ── */
.wizard-progress {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 36px;
    position: relative;
}
.wizard-progress::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--mist, #e2e8f0);
    z-index: 0;
    transform: translateY(-50%);
    border-radius: 3px;
}
.wizard-step {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    width: 80px;
}
.step-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid var(--mist, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    color: var(--slate, #64748b);
    transition: all 0.3s ease;
}
.step-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--slate, #64748b);
    text-align: center;
    transition: all 0.3s ease;
}

/* Active & Completed States */
.wizard-step.active .step-circle {
    border-color: var(--teal-600, #1e7585);
    background: var(--teal-600, #1e7585);
    color: #fff;
    box-shadow: 0 0 0 4px rgba(30,117,133,0.15);
}
.wizard-step.active .step-label {
    color: var(--teal-950, #0f3a44);
}
.wizard-step.completed .step-circle {
    border-color: var(--gold, #c9a227);
    background: var(--gold, #c9a227);
    color: #fff;
}
.wizard-step.completed .step-label {
    color: var(--gold, #c9a227);
}

/* ── FORM CARD ── */
.setup-card {
    background: #fff;
    border: 1.5px solid var(--mist, #e2e8f0);
    border-radius: 16px;
    padding: 32px 40px;
    box-shadow: 0 4px 24px rgba(15,76,92,0.04);
}
.step-heading {
    font-size: 20px;
    font-weight: 700;
    color: var(--teal-950, #0f3a44);
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--cloud, #f1f5f9);
}

/* ── INPUTS ── */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-grid.full {
    grid-template-columns: 1fr;
}
.field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.field-group.col-span-2 {
    grid-column: span 2;
}
.field-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink, #1e293b);
}
.field-req {
    color: #ef4444;
    margin-left: 2px;
}
.form-input, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--mist, #e2e8f0);
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--ink, #1e293b);
    transition: all 0.2s;
    background: #fff;
}
.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--teal-500, #22889a);
    box-shadow: 0 0 0 3px rgba(34,136,154,0.1);
}
.form-input::placeholder {
    color: #94a3b8;
}

/* ── BUTTONS ── */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--cloud, #f1f5f9);
}
.btn-back {
    font-size: 14px;
    font-weight: 600;
    color: var(--slate, #64748b);
    text-decoration: none;
    transition: color 0.2s;
}
.btn-back:hover {
    color: var(--teal-900, #17535f);
}
.btn-next {
    background: linear-gradient(135deg, var(--teal-900, #17535f), var(--teal-700, #1b6878));
    color: var(--gold, #c9a227);
    border: none;
    padding: 11px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(23,83,95,0.15);
}
.btn-next:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(23,83,95,0.25);
}

.error-msg {
    font-size: 12px;
    color: #ef4444;
    margin-top: 4px;
}

/* ── SUCCESS VIEW ── */
.success-view {
    text-align: center;
    padding: 40px 20px;
}
.success-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--light-green, #eaf7f7);
    color: var(--teal-600, #1e7585);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}
.success-title {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
    color: var(--teal-950, #0f3a44);
    margin-bottom: 12px;
}
.success-sub {
    font-size: 15px;
    color: var(--slate, #64748b);
    max-width: 400px;
    margin: 0 auto 32px;
    line-height: 1.5;
}
</style>
@endpush

@section('content')
<div class="setup-container">
    
    <div class="setup-header">
        <h1 class="setup-title">Profile Setup</h1>
        <p class="setup-subtitle">Complete your profile to get personalized scholarship recommendations.</p>
    </div>

    <!-- Progress Indicator -->
    <div class="wizard-progress">
        @php
            $steps = [
                1 => 'Personal',
                2 => 'Academic',
                3 => 'Financial',
                4 => 'Finish'
            ];
        @endphp
        
        @foreach($steps as $step => $label)
            <div class="wizard-step {{ $currentStep === $step ? 'active' : '' }} {{ $currentStep > $step ? 'completed' : '' }}">
                <div class="step-circle">
                    @if($currentStep > $step)
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    @else
                        {{ $step }}
                    @endif
                </div>
                <div class="step-label">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <!-- Global Errors -->
    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 13px;">
            <ul style="list-style-type: disc; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="setup-card">
        @if ($currentStep === 1)
            <h2 class="step-heading">Personal Information</h2>
            <form method="POST" action="{{ route('profile.setup.step1') }}">
                @csrf
                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label" for="date_of_birth">Date of Birth <span class="field-req">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-input" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" required>
                        @error('date_of_birth') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="field-group">
                        <label class="field-label" for="sex">Sex <span class="field-req">*</span></label>
                        <select id="sex" name="sex" class="form-select" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male" @selected(old('sex', $profile->sex) === 'Male')>Male</option>
                            <option value="Female" @selected(old('sex', $profile->sex) === 'Female')>Female</option>
                        </select>
                        @error('sex') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group col-span-2">
                        <label class="field-label" for="home_address">Home Address (Street, Barangay) <span class="field-req">*</span></label>
                        <input type="text" id="home_address" name="home_address" class="form-input" value="{{ old('home_address', $profile->home_address) }}" placeholder="e.g. 123 Main St, Brgy. San Jose" required>
                        @error('home_address') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="city">City / Municipality <span class="field-req">*</span></label>
                        <input type="text" id="city" name="city" class="form-input" value="{{ old('city', $profile->city) }}" placeholder="e.g. Quezon City" required>
                        @error('city') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="province">Province <span class="field-req">*</span></label>
                        <input type="text" id="province" name="province" class="form-input" value="{{ old('province', $profile->province) }}" placeholder="e.g. Metro Manila" required>
                        @error('province') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="zip_code">ZIP Code <span class="field-req">*</span></label>
                        <input type="text" id="zip_code" name="zip_code" class="form-input" value="{{ old('zip_code', $profile->zip_code) }}" placeholder="e.g. 1100" required>
                        @error('zip_code') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="mobile_number">Mobile Number <span class="field-req">*</span></label>
                        <input type="text" id="mobile_number" name="mobile_number" class="form-input" value="{{ old('mobile_number', $profile->mobile_number) }}" placeholder="e.g. 09123456789" required>
                        @error('mobile_number') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions" style="justify-content: flex-end;">
                    <button type="submit" class="btn-next">Save & Continue</button>
                </div>
            </form>

        @elseif ($currentStep === 2)
            <h2 class="step-heading">Academic Information</h2>
            <form method="POST" action="{{ route('profile.setup.step2') }}">
                @csrf
                <div class="form-grid">
                    <div class="field-group col-span-2">
                        <label class="field-label" for="university_name">University / School Name <span class="field-req">*</span></label>
                        <input type="text" id="university_name" name="university_name" class="form-input" value="{{ old('university_name', $profile->university_name) }}" placeholder="e.g. University of the Philippines" required>
                        @error('university_name') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group col-span-2">
                        <label class="field-label" for="university_address">University Address <span class="field-req">*</span></label>
                        <input type="text" id="university_address" name="university_address" class="form-input" value="{{ old('university_address', $profile->university_address) }}" placeholder="e.g. Diliman, Quezon City" required>
                        @error('university_address') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group col-span-2">
                        <label class="field-label" for="university_email">Institutional Email (Optional)</label>
                        <input type="email" id="university_email" name="university_email" class="form-input" value="{{ old('university_email', $profile->university_email) }}" placeholder="e.g. juan@up.edu.ph">
                        @error('university_email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="course_program">Course / Program <span class="field-req">*</span></label>
                        <input type="text" id="course_program" name="course_program" class="form-input" value="{{ old('course_program', $profile->course_program) }}" placeholder="e.g. BS Computer Science" required>
                        @error('course_program') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="student_number">Student Number <span class="field-req">*</span></label>
                        <input type="text" id="student_number" name="student_number" class="form-input" value="{{ old('student_number', $profile->student_number) }}" placeholder="e.g. 2021-12345" required>
                        @error('student_number') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="year_level">Year Level <span class="field-req">*</span></label>
                        <select id="year_level" name="year_level" class="form-select" required>
                            <option value="" disabled selected>Select level</option>
                            <option value="1st Year" @selected(old('year_level', $profile->year_level) === '1st Year')>1st Year</option>
                            <option value="2nd Year" @selected(old('year_level', $profile->year_level) === '2nd Year')>2nd Year</option>
                            <option value="3rd Year" @selected(old('year_level', $profile->year_level) === '3rd Year')>3rd Year</option>
                            <option value="4th Year" @selected(old('year_level', $profile->year_level) === '4th Year')>4th Year</option>
                            <option value="5th Year" @selected(old('year_level', $profile->year_level) === '5th Year')>5th Year</option>
                        </select>
                        @error('year_level') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="semester">Current Semester <span class="field-req">*</span></label>
                        <select id="semester" name="semester" class="form-select" required>
                            <option value="" disabled selected>Select semester</option>
                            <option value="1st Semester" @selected(old('semester', $profile->semester) === '1st Semester')>1st Semester</option>
                            <option value="2nd Semester" @selected(old('semester', $profile->semester) === '2nd Semester')>2nd Semester</option>
                            <option value="Midyear/Summer" @selected(old('semester', $profile->semester) === 'Midyear/Summer')>Midyear/Summer</option>
                        </select>
                        @error('semester') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="academic_year">Academic Year <span class="field-req">*</span></label>
                        <input type="text" id="academic_year" name="academic_year" class="form-input" value="{{ old('academic_year', $profile->academic_year) }}" placeholder="e.g. 2024-2025" required>
                        @error('academic_year') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="gwa">GWA / GPA (Optional)</label>
                        <input type="number" id="gwa" name="gwa" step="0.01" min="1" max="100" class="form-input" value="{{ old('gwa', $profile->gwa) }}" placeholder="e.g. 1.25 or 95">
                        @error('gwa') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('profile.setup', ['step' => 1]) }}" class="btn-back">← Back</a>
                    <button type="submit" class="btn-next">Save & Continue</button>
                </div>
            </form>

        @elseif ($currentStep === 3)
            <h2 class="step-heading">Financial Information</h2>
            <form method="POST" action="{{ route('profile.setup.step3') }}">
                @csrf
                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label" for="monthly_household_income">Monthly Household Income (₱) <span class="field-req">*</span></label>
                        <input type="number" id="monthly_household_income" name="monthly_household_income" min="0" step="0.01" class="form-input" value="{{ old('monthly_household_income', $profile->monthly_household_income) }}" placeholder="e.g. 15000" required>
                        @error('monthly_household_income') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="num_dependents">Number of Dependents <span class="field-req">*</span></label>
                        <input type="number" id="num_dependents" name="num_dependents" min="0" class="form-input" value="{{ old('num_dependents', $profile->num_dependents) }}" placeholder="e.g. 3" required>
                        @error('num_dependents') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="is_breadwinner">Breadwinner Status <span class="field-req">*</span></label>
                        <select id="is_breadwinner" name="is_breadwinner" class="form-select" required>
                            <option value="" disabled selected>Select status</option>
                            <option value="Yes" @selected(old('is_breadwinner', $profile->is_breadwinner) === 'Yes')>Yes</option>
                            <option value="No" @selected(old('is_breadwinner', $profile->is_breadwinner) === 'No')>No</option>
                            <option value="Partial Contributor" @selected(old('is_breadwinner', $profile->is_breadwinner) === 'Partial Contributor')>Partial Contributor</option>
                        </select>
                        @error('is_breadwinner') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="is_4ps">4Ps Beneficiary? <span class="field-req">*</span></label>
                        <select id="is_4ps" name="is_4ps" class="form-select" required>
                            <option value="" disabled selected>Select</option>
                            <option value="1" @selected((string) old('is_4ps', (int) $profile->is_4ps) === '1')>Yes</option>
                            <option value="0" @selected((string) old('is_4ps', (int) $profile->is_4ps) === '0')>No</option>
                        </select>
                        @error('is_4ps') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="father_employment_status">Father's Employment Status</label>
                        <select id="father_employment_status" name="father_employment_status" class="form-select">
                            <option value="" selected>Select status</option>
                            <option value="Employed" @selected(old('father_employment_status', $profile->father_employment_status) === 'Employed')>Employed</option>
                            <option value="Unemployed" @selected(old('father_employment_status', $profile->father_employment_status) === 'Unemployed')>Unemployed</option>
                            <option value="Self-Employed" @selected(old('father_employment_status', $profile->father_employment_status) === 'Self-Employed')>Self-Employed</option>
                            <option value="Deceased" @selected(old('father_employment_status', $profile->father_employment_status) === 'Deceased')>Deceased</option>
                        </select>
                        @error('father_employment_status') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="mother_employment_status">Mother's Employment Status</label>
                        <select id="mother_employment_status" name="mother_employment_status" class="form-select">
                            <option value="" selected>Select status</option>
                            <option value="Employed" @selected(old('mother_employment_status', $profile->mother_employment_status) === 'Employed')>Employed</option>
                            <option value="Unemployed" @selected(old('mother_employment_status', $profile->mother_employment_status) === 'Unemployed')>Unemployed</option>
                            <option value="Self-Employed" @selected(old('mother_employment_status', $profile->mother_employment_status) === 'Self-Employed')>Self-Employed</option>
                            <option value="Deceased" @selected(old('mother_employment_status', $profile->mother_employment_status) === 'Deceased')>Deceased</option>
                        </select>
                        @error('mother_employment_status') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('profile.setup', ['step' => 2]) }}" class="btn-back">← Back</a>
                    <button type="submit" class="btn-next">Save & Continue</button>
                </div>
            </form>

        @else
            <div class="success-view">
                <div class="success-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h2 class="success-title">You're All Set!</h2>
                <p class="success-sub">Your profile is now complete. Our AI engine is ready to match you with the best scholarship opportunities based on your information.</p>
                
                <form method="POST" action="{{ route('profile.setup.submit') }}">
                    @csrf
                    <div class="form-actions" style="justify-content: center; border-top: none; margin-top: 0; padding-top: 0;">
                        <a href="{{ route('profile.setup', ['step' => 3]) }}" class="btn-back" style="margin-right: 20px;">← Back</a>
                        <button type="submit" class="btn-next">Go to Dashboard</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection


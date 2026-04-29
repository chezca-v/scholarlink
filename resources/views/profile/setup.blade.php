<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ScholarLink – Profile Setup</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal:#0F4C5C;--teal-mid:#1a6878;--teal-light:#e8f4f7;--teal-xlight:#f2f8fa;
  --gold:#C9A84C;--gold-light:#F9D679;
  --ink:#1C1C2E;--slate:#8A95A3;--cloud:#F4F6FA;--mist:#E2E8F0;
  --red:#EF4444;--green:#1a9653;--white:#fff;--r:10px;--r-lg:14px;
}
body{
  font-family:"DM Sans",sans-serif;
  background:linear-gradient(135deg,#0F4C5C 0%,#1A6B7A 100%);
  min-height:100vh;display:flex;flex-direction:column;align-items:center;
  justify-content:flex-start;padding:40px 16px 60px;color:var(--ink);
}
.wizard{width:100%;max-width:960px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.30)}
/* topbar */
.topbar{background:var(--teal-xlight);border-bottom:1px solid var(--mist);padding:14px 48px;display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;align-items:center;gap:10px}
.logo-icon{width:36px;height:36px;background:var(--teal);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-name{font-family:"Fraunces",serif;font-size:19px;font-weight:800;color:var(--teal);letter-spacing:-.01em}
.topbar-right{display:flex;align-items:center;gap:18px}
.save-indicator{font-size:12.5px;color:#3a7a6a;display:flex;align-items:center;gap:6px}
.save-dot{width:7px;height:7px;background:#22c55e;border-radius:50%;display:inline-block;flex-shrink:0}
.btn-exit{font-family:"DM Sans",sans-serif;font-size:12.5px;font-weight:600;color:var(--ink);background:var(--white);border:1.5px solid var(--mist);border-radius:var(--r);padding:7px 16px;cursor:pointer;display:flex;align-items:center;gap:5px;transition:border-color .15s,color .15s;text-decoration:none}
.btn-exit:hover{border-color:var(--teal);color:var(--teal)}
/* stepper */
.stepper-wrap{background:var(--teal-xlight);border-bottom:1px solid var(--mist);padding:22px 48px 0}
.stepper{display:flex;align-items:flex-start;max-width:680px;margin:0 auto}
.step-item{display:flex;flex-direction:column;align-items:center;gap:8px;flex:1;position:relative}
.step-item:not(:last-child)::after{content:'';position:absolute;top:18px;left:calc(50% + 24px);right:calc(-50% + 24px);height:2px;background:#2f5d66;transition:background .3s}
.step-item.done::after{background:var(--teal)}
.step-circle{width:36px;height:36px;border-radius:50%;border:2px solid #2f5d66;background:transparent;color:#2f5d66;font-family:"Fraunces",serif;font-size:17px;font-weight:700;display:grid;place-items:center;position:relative;z-index:1;transition:all .2s}
.step-item.active .step-circle{background:#0F3F4A;border:none;color:#fff;box-shadow:0 8px 20px rgba(0,0,0,.22)}
.step-item.done .step-circle{background:transparent;border-color:var(--teal);color:var(--teal)}
.step-item.done .step-circle::after{content:'✓';font-size:13px;font-family:"DM Sans",sans-serif;font-weight:700}
.step-item.done .step-number{display:none}
.step-label{font-family:"Fraunces",serif;font-size:12.5px;font-weight:700;color:#2f5d66;text-align:center;line-height:1.3;padding-bottom:18px}
.step-item.active .step-label{color:var(--teal)}
.step-item.done .step-label{color:var(--teal-mid)}
/* panels */
.panel{display:none;padding:32px 48px 28px;animation:fadeIn .2s ease}
.panel.active{display:block}
@keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
/* fields */
.field-row{display:grid;gap:20px;margin-bottom:20px}
.cols-1{grid-template-columns:1fr}.cols-2{grid-template-columns:1fr 1fr}.cols-3{grid-template-columns:1fr 1fr 1fr}
.field-group{display:flex;flex-direction:column;gap:5px}
.field-label{font-size:12.5px;font-weight:600;color:var(--ink)}
.req{color:var(--red)}
.field-hint{font-size:11px;color:var(--slate);margin-top:1px}
.field-error{font-size:11.5px;color:var(--red);margin-top:2px}
.form-input{width:100%;background:var(--cloud);border:1.5px solid var(--mist);border-radius:var(--r);padding:10px 13px;font-family:"DM Sans",sans-serif;font-size:13.5px;color:var(--ink);outline:none;transition:border-color .2s,box-shadow .2s}
.form-input::placeholder{color:var(--slate)}
.form-input:focus{border-color:var(--teal);background:#fff;box-shadow:0 0 0 3px rgba(15,76,92,.08)}
.form-input.is-invalid{border-color:var(--red)}
/* select */
.select-wrap{position:relative}
.select-wrap select{width:100%;appearance:none;background:var(--cloud);border:1.5px solid var(--mist);border-radius:var(--r);padding:10px 38px 10px 13px;font-family:"DM Sans",sans-serif;font-size:13.5px;color:var(--ink);cursor:pointer;outline:none;transition:border-color .2s,box-shadow .2s}
.select-wrap select:focus{border-color:var(--teal);background:#fff;box-shadow:0 0 0 3px rgba(15,76,92,.08)}
.select-wrap::after{content:'';position:absolute;right:13px;top:50%;transform:translateY(-50%);width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid var(--slate);pointer-events:none}
/* radio */
.radio-group{display:flex;flex-direction:column;gap:9px;padding-top:3px}
.radio-option{display:flex;align-items:center;gap:9px;font-size:13.5px;color:var(--ink);cursor:pointer}
.radio-option input[type="radio"]{width:17px;height:17px;accent-color:var(--teal);cursor:pointer;flex-shrink:0}
/* nav */
.footer-nav{margin-top:28px;padding-top:18px;border-top:1px solid var(--mist);display:flex;justify-content:space-between;align-items:center}
.step-counter{font-size:12px;color:var(--slate)}
.btn-back{padding:9px 22px;border-radius:var(--r);border:1.5px solid var(--mist);background:var(--white);font-family:"DM Sans",sans-serif;font-size:12.5px;font-weight:600;color:#4a5a68;cursor:pointer;transition:all .15s}
.btn-back:hover{border-color:var(--teal);color:var(--teal)}
.btn-next{padding:9px 24px;border-radius:var(--r);border:none;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);font-family:"DM Sans",sans-serif;font-size:12.5px;font-weight:600;color:#F9D679;cursor:pointer;box-shadow:0 6px 14px rgba(0,0,0,.18);transition:transform .15s,box-shadow .15s;display:flex;align-items:center;gap:6px}
.btn-next:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(0,0,0,.22)}
.btn-submit{padding:9px 28px;border-radius:var(--r);border:none;background:linear-gradient(135deg,#1a9653,#15784a);font-family:"DM Sans",sans-serif;font-size:12.5px;font-weight:600;color:#fff;cursor:pointer;box-shadow:0 6px 14px rgba(0,0,0,.18);transition:transform .15s,box-shadow .15s;display:flex;align-items:center;gap:6px}
.btn-submit:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(0,0,0,.22)}
/* review */
.review-title{font-family:"Fraunces",serif;font-size:26px;font-weight:800;color:var(--teal);text-align:center;margin-bottom:5px}
.review-sub{font-size:13px;color:var(--slate);text-align:center;margin-bottom:26px}
.review-section{margin-bottom:22px;border:1px solid var(--mist);border-radius:var(--r-lg);overflow:hidden}
.review-header{background:var(--teal-xlight);border-bottom:1px solid var(--mist);padding:10px 16px;display:flex;justify-content:space-between;align-items:center}
.review-section-title{font-size:13px;font-weight:700;color:var(--teal)}
.btn-edit{font-size:12px;font-weight:600;color:var(--teal-mid);background:none;border:none;cursor:pointer;text-decoration:underline}
.review-row{display:flex;justify-content:space-between;align-items:center;padding:9px 16px;border-bottom:1px solid #EEF5F8;font-size:13px}
.review-row:last-child{border-bottom:none}
.review-key{color:#4a7a88;font-weight:400}
.review-val{color:var(--ink);font-weight:600;text-align:right;max-width:60%}
.review-val.empty{color:var(--slate);font-weight:400;font-style:italic}
/* success */
.success-panel{display:none;padding:52px 48px 44px;text-align:center}
.success-panel.active{display:block;animation:fadeIn .4s ease}
.success-check{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);display:grid;place-items:center;margin:0 auto 20px;box-shadow:0 8px 24px rgba(15,76,92,.25)}
.success-check svg{width:28px;height:28px}
.success-title{font-family:"Fraunces",serif;font-size:26px;font-weight:800;color:var(--ink);margin-bottom:10px}
.success-note{font-size:13px;color:#5a6a78;max-width:380px;margin:0 auto 28px;line-height:1.65}
.success-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:28px}
.btn-secondary{padding:11px 24px;border-radius:var(--r-lg);border:1.5px solid var(--mist);background:var(--white);font-family:"DM Sans",sans-serif;font-size:13px;font-weight:600;color:var(--ink);cursor:pointer;transition:all .15s;min-width:160px;text-decoration:none;display:inline-block;text-align:center}
.btn-secondary:hover{border-color:var(--teal);color:var(--teal)}
</style>
</head>
<body>

{{--
  setup.blade.php — Profile Setup Wizard (4 steps, single file)
  ─────────────────────────────────────────────────────────────
  Controller : ProfileController@setup  (GET)
  Route      : profile.setup            (GET  /profile/setup)
               profile.update           (PATCH /profile/update)

  Variables expected:
    $user    → Auth user (App\Models\User)
    $profile → App\Models\ApplicantProfile  (firstOrNew(['user_id'=>$user->id]))

  Form values: old('field', $profile->field) — survives validation
  errors AND pre-fills on revisit.
--}}

<form id="setup-form" method="POST" action="{{ route('profile.update') }}">
@csrf
@method('PATCH')

<div class="wizard" id="wizard">

  {{-- ── TOPBAR ── --}}
  <div class="topbar">
    <div class="logo">
      <div class="logo-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 3C8 3 6 6 6 9C6 13 9 15 12 18C15 15 18 13 18 9C18 6 16 3 12 3Z" fill="#C9A84C"/>
          <circle cx="12" cy="9" r="2.5" fill="white" opacity="0.85"/>
        </svg>
      </div>
      <span class="logo-name">ScholarLink</span>
    </div>
    <div class="topbar-right">
      @if($profile->exists)
        <span class="save-indicator"><span class="save-dot"></span> All Changes Saved</span>
      @endif
      <a href="{{ route('dashboard') }}" class="btn-exit">✕ Exit Setup</a>
    </div>
  </div>

  {{-- ── STEPPER ── --}}
  <div class="stepper-wrap">
    <div class="stepper">
      <div class="step-item active" id="sn1">
        <div class="step-circle"><span class="step-number">1</span></div>
        <div class="step-label">Personal<br>Info</div>
      </div>
      <div class="step-item" id="sn2">
        <div class="step-circle"><span class="step-number">2</span></div>
        <div class="step-label">Academic<br>Info</div>
      </div>
      <div class="step-item" id="sn3">
        <div class="step-circle"><span class="step-number">3</span></div>
        <div class="step-label">Financial<br>Background</div>
      </div>
      <div class="step-item" id="sn4">
        <div class="step-circle"><span class="step-number">4</span></div>
        <div class="step-label">Review &amp;<br>Submit</div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════
       STEP 1 — PERSONAL INFO
       applicant_profiles + users
  ════════════════════════════════════════════════ --}}
  <div class="panel active" id="panel-1">

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="first_name">First Name <span class="req">*</span></label>
        {{-- applicant_profiles.first_name --}}
        <input class="form-input @error('first_name') is-invalid @enderror"
               type="text" id="first_name" name="first_name"
               value="{{ old('first_name', $profile->first_name) }}"
               placeholder="e.g. Juan">
        <span class="field-hint">As it appears on your school ID</span>
        @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="last_name">Last Name <span class="req">*</span></label>
        {{-- applicant_profiles.last_name --}}
        <input class="form-input @error('last_name') is-invalid @enderror"
               type="text" id="last_name" name="last_name"
               value="{{ old('last_name', $profile->last_name) }}"
               placeholder="e.g. Dela Cruz">
        <span class="field-hint">As it appears on your school ID</span>
        @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="date_of_birth">Date of Birth <span class="req">*</span></label>
        {{-- applicant_profiles.date_of_birth --}}
        <input class="form-input @error('date_of_birth') is-invalid @enderror"
               type="date" id="date_of_birth" name="date_of_birth"
               value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}">
        @error('date_of_birth')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label">Sex <span class="req">*</span></label>
        {{-- applicant_profiles.sex — ENUM --}}
        <div class="radio-group">
          @foreach(['Male'=>'Male','Female'=>'Female','Non-Binary'=>'Non-Binary','Prefer not to say'=>'Prefer not to say'] as $val=>$label)
            <label class="radio-option">
              <input type="radio" name="sex" value="{{ $val }}"
                     {{ old('sex', $profile->sex) === $val ? 'checked' : '' }}>
              {{ $label }}
            </label>
          @endforeach
        </div>
        @error('sex')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-1">
      <div class="field-group">
        <label class="field-label" for="home_address">Home Address <span class="req">*</span></label>
        {{-- applicant_profiles.home_address --}}
        <input class="form-input @error('home_address') is-invalid @enderror"
               type="text" id="home_address" name="home_address"
               value="{{ old('home_address', $profile->home_address) }}"
               placeholder="Blk 1, Lot 2, Street Barangay">
        @error('home_address')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-3">
      <div class="field-group">
        <label class="field-label" for="city">City <span class="req">*</span></label>
        {{-- applicant_profiles.city --}}
        <input class="form-input @error('city') is-invalid @enderror"
               type="text" id="city" name="city"
               value="{{ old('city', $profile->city) }}"
               placeholder="City Name">
        @error('city')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="province">Province <span class="req">*</span></label>
        {{-- applicant_profiles.province --}}
        <input class="form-input @error('province') is-invalid @enderror"
               type="text" id="province" name="province"
               value="{{ old('province', $profile->province) }}"
               placeholder="Province">
        @error('province')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="zip_code">Zip Code <span class="req">*</span></label>
        {{-- applicant_profiles.zip_code --}}
        <input class="form-input @error('zip_code') is-invalid @enderror"
               type="text" id="zip_code" name="zip_code"
               value="{{ old('zip_code', $profile->zip_code) }}"
               placeholder="Zip Code">
        @error('zip_code')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="mobile_number">Mobile Number <span class="req">*</span></label>
        {{-- applicant_profiles.mobile_number --}}
        <input class="form-input @error('mobile_number') is-invalid @enderror"
               type="tel" id="mobile_number" name="mobile_number"
               value="{{ old('mobile_number', $profile->mobile_number) }}"
               placeholder="09XX-XXX-XXXX">
        @error('mobile_number')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="email">Email Address <span class="req">*</span></label>
        {{-- users.email --}}
        <input class="form-input @error('email') is-invalid @enderror"
               type="email" id="email" name="email"
               value="{{ old('email', $user->email) }}"
               placeholder="juandelacruz@email.com">
        <span class="field-hint">Make sure that it is active</span>
        @error('email')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="footer-nav">
      <span class="step-counter">Step 1 of 4</span>
      <button type="button" class="btn-next" onclick="goTo(2)">Next &rarr;</button>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════
       STEP 2 — ACADEMIC INFO
       applicant_profiles
  ════════════════════════════════════════════════ --}}
  <div class="panel" id="panel-2">

    <div class="field-row cols-1">
      <div class="field-group">
        <label class="field-label" for="school_name">University / School / Institution <span class="req">*</span></label>
        {{-- applicant_profiles.school_name --}}
        <input class="form-input @error('school_name') is-invalid @enderror"
               type="text" id="school_name" name="school_name"
               value="{{ old('school_name', $profile->school_name) }}"
               placeholder="Pamantasan ng Lungsod ng Maynila">
        @error('school_name')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="school_address">School Address <span class="req">*</span></label>
        {{-- applicant_profiles.school_address --}}
        <input class="form-input @error('school_address') is-invalid @enderror"
               type="text" id="school_address" name="school_address"
               value="{{ old('school_address', $profile->school_address) }}"
               placeholder="Intramuros, Manila">
        @error('school_address')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="school_email">University / School Email <span class="req">*</span></label>
        {{-- applicant_profiles.school_email --}}
        <input class="form-input @error('school_email') is-invalid @enderror"
               type="email" id="school_email" name="school_email"
               value="{{ old('school_email', $profile->school_email) }}"
               placeholder="PLM@email.com">
        @error('school_email')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="course_program">Course / Program <span class="req">*</span></label>
        {{-- applicant_profiles.course_program --}}
        <input class="form-input @error('course_program') is-invalid @enderror"
               type="text" id="course_program" name="course_program"
               value="{{ old('course_program', $profile->course_program) }}"
               placeholder="Bachelor of Science in Computer Engineering">
        @error('course_program')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="student_number">Student No. <span class="req">*</span></label>
        {{-- applicant_profiles.student_number --}}
        <input class="form-input @error('student_number') is-invalid @enderror"
               type="text" id="student_number" name="student_number"
               value="{{ old('student_number', $profile->student_number) }}"
               placeholder="LRN / Student No.">
        <span class="field-hint">N/A if not available</span>
        @error('student_number')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-3">
      <div class="field-group">
        <label class="field-label" for="year_level">Year Level <span class="req">*</span></label>
        {{-- applicant_profiles.year_level --}}
        <div class="select-wrap">
          <select id="year_level" name="year_level" class="@error('year_level') is-invalid @enderror">
            <option value="" disabled {{ old('year_level', $profile->year_level) ? '' : 'selected' }}>Select</option>
            @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $opt)
              <option value="{{ $opt }}" {{ old('year_level', $profile->year_level) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>
        @error('year_level')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="semester">Semester <span class="req">*</span></label>
        {{-- applicant_profiles.semester --}}
        <div class="select-wrap">
          <select id="semester" name="semester" class="@error('semester') is-invalid @enderror">
            <option value="" disabled {{ old('semester', $profile->semester) ? '' : 'selected' }}>Select</option>
            @foreach(['1st Semester','2nd Semester','Summer'] as $opt)
              <option value="{{ $opt }}" {{ old('semester', $profile->semester) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>
        @error('semester')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label" for="academic_year">Academic Year <span class="req">*</span></label>
        {{-- applicant_profiles.academic_year --}}
        <input class="form-input @error('academic_year') is-invalid @enderror"
               type="text" id="academic_year" name="academic_year"
               value="{{ old('academic_year', $profile->academic_year) }}"
               placeholder="e.g. 2025-2026">
        @error('academic_year')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="gwa">General Weighted Average (GWA) <span class="req">*</span></label>
        {{-- applicant_profiles.gwa -- DECIMAL --}}
        <input class="form-input @error('gwa') is-invalid @enderror"
               type="number" id="gwa" name="gwa"
               step="0.01" min="1.00" max="5.00"
               value="{{ old('gwa', $profile->gwa) }}"
               placeholder="e.g. 1.50">
        <span class="field-hint">1.00 (highest) – 5.00 (lowest)</span>
        @error('gwa')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="footer-nav">
      <button type="button" class="btn-back" onclick="goTo(1)">&larr; Back</button>
      <div style="display:flex;align-items:center;gap:14px">
        <span class="step-counter">Step 2 of 4</span>
        <button type="button" class="btn-next" onclick="goTo(3)">Next &rarr;</button>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════
       STEP 3 — FINANCIAL BACKGROUND
       applicant_profiles
  ════════════════════════════════════════════════ --}}
  <div class="panel" id="panel-3">

    <div class="field-row cols-1">
      <div class="field-group">
        <label class="field-label" for="income_bracket">Monthly Household Income <span class="req">*</span></label>
        {{-- applicant_profiles.income_bracket --}}
        <div class="select-wrap">
          <select id="income_bracket" name="income_bracket" class="@error('income_bracket') is-invalid @enderror">
            <option value="" disabled {{ old('income_bracket', $profile->income_bracket) ? '' : 'selected' }}>Select income bracket</option>
            @php
              $incomeBrackets = [
                'below_10957'  => 'Below PHP 10,957 – Poor household',
                '10957_21914'  => 'PHP 10,957 – PHP 21,914 – Low income',
                '21914_43828'  => 'PHP 21,914 – PHP 43,828 – Lower middle income',
                '43828_76669'  => 'PHP 43,828 – PHP 76,669 – Middle income',
                '76669_131484' => 'PHP 76,669 – PHP 131,484 – Upper middle income',
                'above_131484' => 'Above PHP 131,484 – High income',
              ];
            @endphp
            @foreach($incomeBrackets as $val => $label)
              <option value="{{ $val }}" {{ old('income_bracket', $profile->income_bracket) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <span class="field-hint">Based on PSA (Philippine Statistics Authority) Income Classification</span>
        @error('income_bracket')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-2">
      <div class="field-group">
        <label class="field-label" for="num_dependents">No. of Dependents <span class="req">*</span></label>
        {{-- applicant_profiles.num_dependents -- INT --}}
        <input class="form-input @error('num_dependents') is-invalid @enderror"
               type="number" id="num_dependents" name="num_dependents"
               min="1"
               value="{{ old('num_dependents', $profile->num_dependents) }}"
               placeholder="e.g. 4">
        <span class="field-hint">Include yourself, parents, and siblings</span>
        @error('num_dependents')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="field-group">
        <label class="field-label">Are you the breadwinner? <span class="req">*</span></label>
        {{-- applicant_profiles.is_breadwinner -- ENUM: Yes / No / Partial Contributor --}}
        <div class="radio-group">
          @foreach(['Yes'=>'Yes, Primary Earner','Partial Contributor'=>'Partial Contributor','No'=>'No'] as $val=>$label)
            <label class="radio-option">
              <input type="radio" name="is_breadwinner" value="{{ $val }}"
                     {{ old('is_breadwinner', $profile->is_breadwinner) === $val ? 'checked' : '' }}>
              {{ $label }}
            </label>
          @endforeach
        </div>
        @error('is_breadwinner')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-1">
      <div class="field-group">
        <label class="field-label" for="parent_employment_status">Parent / Guardian Employment Status <span class="req">*</span></label>
        {{-- applicant_profiles.parent_employment_status --}}
        <div class="select-wrap">
          <select id="parent_employment_status" name="parent_employment_status" class="@error('parent_employment_status') is-invalid @enderror">
            <option value="" disabled {{ old('parent_employment_status', $profile->parent_employment_status) ? '' : 'selected' }}>Select</option>
            @php
              $employmentOptions = [
                'both_employed'    => 'Both parents employed',
                'one_employed'     => 'One parent employed',
                'self_employed'    => 'Self-employed / Negosyante',
                'ofw'              => 'Overseas Filipino Worker (OFW)',
                'unemployed'       => 'Unemployed / No Income',
                'solo_parent'      => 'Solo parent household',
                'parents_deceased' => 'Parent/s deceased',
              ];
            @endphp
            @foreach($employmentOptions as $val => $label)
              <option value="{{ $val }}" {{ old('parent_employment_status', $profile->parent_employment_status) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        @error('parent_employment_status')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="field-row cols-1">
      <div class="field-group">
        <label class="field-label" for="is_4ps">Is your Family a 4Ps Beneficiary? <span class="req">*</span></label>
        {{-- applicant_profiles.is_4ps --}}
        <div class="select-wrap">
          <select id="is_4ps" name="is_4ps" class="@error('is_4ps') is-invalid @enderror">
            <option value="" disabled {{ old('is_4ps', $profile->is_4ps) !== null ? '' : 'selected' }}>Select...</option>
            @php
              $fourPsOptions = [
                'active'            => 'Yes – Active beneficiary',
                'inactive'          => 'Yes – Inactive/Exited',
                'no'                => 'No',
                'prefer_not_to_say' => 'Prefer not to say',
              ];
            @endphp
            @foreach($fourPsOptions as $val => $label)
              <option value="{{ $val }}" {{ old('is_4ps', $profile->is_4ps) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        @error('is_4ps')<span class="field-error">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="footer-nav">
      <button type="button" class="btn-back" onclick="goTo(2)">&larr; Back</button>
      <div style="display:flex;align-items:center;gap:14px">
        <span class="step-counter">Step 3 of 4</span>
        <button type="button" class="btn-next" onclick="goTo(4)">Next &rarr;</button>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════
       STEP 4 — REVIEW & SUBMIT
       Reads values from DOM via JS — no DB call needed.
       Final PATCH to profile.update sends everything.
  ════════════════════════════════════════════════ --}}
  <div class="panel" id="panel-4">

    <div class="review-title">Review &amp; Submit</div>
    <div class="review-sub">Double-check everything before finalizing your ScholarLink Profile</div>

    <div class="review-section">
      <div class="review-header">
        <span class="review-section-title">Personal Information</span>
        <button type="button" class="btn-edit" onclick="goTo(1)">Edit</button>
      </div>
      <div class="review-row"><span class="review-key">Full Name</span><span class="review-val" id="r-name">—</span></div>
      <div class="review-row"><span class="review-key">Date of Birth</span><span class="review-val" id="r-dob">—</span></div>
      <div class="review-row"><span class="review-key">Sex</span><span class="review-val" id="r-sex">—</span></div>
      <div class="review-row"><span class="review-key">Address</span><span class="review-val" id="r-address">—</span></div>
      <div class="review-row"><span class="review-key">Mobile</span><span class="review-val" id="r-mobile">—</span></div>
      <div class="review-row"><span class="review-key">Email</span><span class="review-val" id="r-email">—</span></div>
    </div>

    <div class="review-section">
      <div class="review-header">
        <span class="review-section-title">Academic Information</span>
        <button type="button" class="btn-edit" onclick="goTo(2)">Edit</button>
      </div>
      <div class="review-row"><span class="review-key">School</span><span class="review-val" id="r-school">—</span></div>
      <div class="review-row"><span class="review-key">Program</span><span class="review-val" id="r-course">—</span></div>
      <div class="review-row"><span class="review-key">Student No.</span><span class="review-val" id="r-studno">—</span></div>
      <div class="review-row"><span class="review-key">Year &amp; Semester</span><span class="review-val" id="r-yearsem">—</span></div>
      <div class="review-row"><span class="review-key">Academic Year</span><span class="review-val" id="r-acadyr">—</span></div>
      <div class="review-row"><span class="review-key">GWA</span><span class="review-val" id="r-gwa">—</span></div>
    </div>

    <div class="review-section">
      <div class="review-header">
        <span class="review-section-title">Financial Background</span>
        <button type="button" class="btn-edit" onclick="goTo(3)">Edit</button>
      </div>
      <div class="review-row"><span class="review-key">Monthly Income</span><span class="review-val" id="r-income">—</span></div>
      <div class="review-row"><span class="review-key">No. of Dependents</span><span class="review-val" id="r-dependents">—</span></div>
      <div class="review-row"><span class="review-key">Breadwinner</span><span class="review-val" id="r-breadwinner">—</span></div>
      <div class="review-row"><span class="review-key">Parent Employment</span><span class="review-val" id="r-employment">—</span></div>
      <div class="review-row"><span class="review-key">4Ps Beneficiary</span><span class="review-val" id="r-4ps">—</span></div>
    </div>

    <div class="footer-nav">
      <button type="button" class="btn-back" onclick="goTo(3)">&larr; Back</button>
      <div style="display:flex;align-items:center;gap:14px">
        <span class="step-counter">Step 4 of 4</span>
        <button type="submit" class="btn-submit">Submit Profile ✓</button>
      </div>
    </div>
  </div>

  {{-- ── SUCCESS PANEL — shown after redirect with session flash ── --}}
  @if(session('profile_saved'))
  <div class="success-panel active" id="panel-success">
    <div class="success-check">
      <svg viewBox="0 0 28 28" fill="none">
        <path d="M6 14.5l5.5 5.5 10.5-11" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <div class="success-title">Profile Saved!</div>
    <div class="success-note">
      Your ScholarLink profile is now complete. You can start applying to scholarships that match your qualifications.
    </div>
    <div class="success-actions">
      <a href="{{ route('scholarships.index') }}" class="btn-secondary">Browse Scholarships</a>
      <a href="{{ route('dashboard') }}" class="btn-secondary">Go to Dashboard</a>
    </div>
  </div>
  @endif

</div>{{-- /.wizard --}}
</form>

<script>
/* ─────────────────────────────────────────────────────────────────
   Label maps — mirror the @php arrays in the Blade above.
   Used by populateReview() to resolve stored keys to readable text.
   ──────────────────────────────────────────────────────────────── */
var INCOME = {
  'below_10957':  'Below PHP 10,957 \u2013 Poor household',
  '10957_21914':  'PHP 10,957 \u2013 PHP 21,914 \u2013 Low income',
  '21914_43828':  'PHP 21,914 \u2013 PHP 43,828 \u2013 Lower middle income',
  '43828_76669':  'PHP 43,828 \u2013 PHP 76,669 \u2013 Middle income',
  '76669_131484': 'PHP 76,669 \u2013 PHP 131,484 \u2013 Upper middle income',
  'above_131484': 'Above PHP 131,484 \u2013 High income'
};
var EMP = {
  'both_employed':'Both parents employed','one_employed':'One parent employed',
  'self_employed':'Self-employed / Negosyante','ofw':'Overseas Filipino Worker (OFW)',
  'unemployed':'Unemployed / No Income','solo_parent':'Solo parent household',
  'parents_deceased':'Parent/s deceased'
};
var FOURPS = {
  'active':'Yes \u2013 Active beneficiary','inactive':'Yes \u2013 Inactive/Exited',
  'no':'No','prefer_not_to_say':'Prefer not to say'
};
var BW = {
  'Yes':'Yes, Primary Earner','Partial Contributor':'Partial Contributor','No':'No'
};

function gv(id){var e=document.getElementById(id);return e?e.value.trim():'';}
function gr(n){var e=document.querySelector('input[name="'+n+'"]:checked');return e?e.value:'';}
function gk(id){var e=document.getElementById(id);return(e&&e.selectedIndex>0)?e.value:'';}
function gt(id){var e=document.getElementById(id);return(e&&e.selectedIndex>0)?e.options[e.selectedIndex].text:'';}
function set(id,val){var e=document.getElementById(id);if(!e)return;e.textContent=val||'\u2014';e.className='review-val'+(val?'':' empty');}

function populateReview(){
  set('r-name',[gv('first_name'),gv('last_name')].filter(Boolean).join(' '));
  set('r-dob',gv('date_of_birth'));
  set('r-sex',gr('sex'));
  set('r-address',[gv('home_address'),gv('city'),gv('province'),gv('zip_code')].filter(Boolean).join(', '));
  set('r-mobile',gv('mobile_number'));
  set('r-email',gv('email'));
  set('r-school',gv('school_name'));
  set('r-course',gv('course_program'));
  set('r-studno',gv('student_number'));
  set('r-yearsem',(gt('year_level')&&gt('semester'))?gt('year_level')+' \u2013 '+gt('semester'):'');
  set('r-acadyr',gv('academic_year'));
  set('r-gwa',gv('gwa'));
  set('r-income',INCOME[gk('income_bracket')]||'');
  set('r-dependents',gv('num_dependents'));
  set('r-breadwinner',BW[gr('is_breadwinner')]||'');
  set('r-employment',EMP[gk('parent_employment_status')]||'');
  set('r-4ps',FOURPS[gk('is_4ps')]||'');
}

function goTo(step){
  [1,2,3,4].forEach(function(i){
    document.getElementById('panel-'+i).classList.toggle('active',i===step);
    var n=document.getElementById('sn'+i);
    n.classList.remove('active','done');
    if(i===step)n.classList.add('active');
    if(i<step)n.classList.add('done');
  });
  if(step===4)populateReview();
  window.scrollTo({top:0,behavior:'smooth'});
}

/* Hide wizard steps if success flash is present */
document.addEventListener('DOMContentLoaded',function(){
  if(document.getElementById('panel-success')){
    [1,2,3,4].forEach(function(i){
      var p=document.getElementById('panel-'+i);
      if(p)p.classList.remove('active');
    });
  }
});
</script>

</body>
</html>
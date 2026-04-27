<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ScholarLink – Apply</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal:#0F4C5C;
  --teal-mid:#1a6878;
  --teal-light:#e8f4f7;
  --teal-xlight:#f2f8fa;
  --gold:#C9A84C;
  --gold-light:#F9D679;
  --gold-bg:#fdf7e3;
  --green:#1a9653;
  --green-bg:#e8f8ed;
  --amber:#EAB308;
  --amber-bg:#fefce8;
  --red:#EF4444;
  --violet:#8B5CF6;
  --violet-bg:#f5f3ff;
  --ink:#1C1C2E;
  --slate:#8A95A3;
  --cloud:#F4F6FA;
  --mist:#E2E8F0;
  --white:#fff;
  --r:10px;
  --r-lg:14px;
}
body{
  font-family:"DM Sans",sans-serif;
  background:linear-gradient(135deg,#0F4C5C 0%,#1A6B7A 100%);
  min-height:100vh;
  display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
  padding:40px 16px 60px;
  color:var(--ink);
}

/* ── Wizard card ── */
.wizard{width:100%;max-width:900px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.30)}
.wizard.is-success .stepper-wrap {display: none;}

/* ── Stepper header ── */
.stepper-wrap{background:#F0FAFA;border-bottom:1px solid var(--mist);padding:24px 48px 0}
.stepper{display:flex;align-items:flex-start;gap:0;max-width:640px;margin:0 auto}
.step-item{display:flex;flex-direction:column;align-items:center;gap:8px;flex:1;position:relative}
.step-item:not(:last-child)::after{content:'';position:absolute;top:19px;left:calc(50% + 26px);right:calc(-50% + 26px);height:2px;background:#2f5d66;transition:background .3s}
.step-item.done::after{background:var(--teal)}
.step-circle{width:38px;height:38px;border-radius:50%;border:2px solid #2f5d66;background:transparent;color:#2f5d66;font-family:"fraunces",sans-serif;font-size:18px;font-weight:600;display:grid;place-items:center;position:relative;z-index:1;transition:all .2s}
.step-item.active .step-circle{background:#0F3F4A;border: none;color:#fff;box-shadow:0 10px 22px rgba(0,0,0,.22)}
.step-item.done .step-circle{background:transparent;border-color:var(--teal);color:var(--teal)}
.step-item.done .step-circle::after{content:'✓';font-size:14px;font-family:"DM Sans",sans-serif;font-weight:700}
.step-item.done .step-number{display:none}
.step-label{font-family:'fraunces', sans-serif;font-size:13px;font-weight:700;color:#2f5d66;text-align:center;line-height:1.3;padding-bottom:18px}
.step-item.active .step-label{color:var(--teal)}
.step-item.done .step-label{color:var(--teal-mid)}

/* ── Alert banners ── */
.alerts-bar{padding:14px 48px 0;display:flex;flex-direction:column;gap:6px;padding-bottom:0}
.alerts-bar.hidden{display:none}
.alert{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:5px 14px;font-size:12.5px;font-weight:600;width:fit-content}
.alert-blue{background:#dbeeff;color:#1a4b7a}
.alert-gold{background:#fff8e1;color:#9a6b00}
.alert-dot{width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0}
.alert-star{font-size:11px}

/* Loading state */
.loading-state{padding:48px;text-align:center;color:var(--slate);font-size:14px}
.loading-spinner{width:32px;height:32px;border:3px solid var(--mist);border-top-color:var(--teal);border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 12px}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── Panel body ── */
.panel{display:none;padding:28px 48px 28px}
.panel.active{display:block}
.panel-hint{font-size:12.5px;color:var(--slate);margin-bottom:20px;line-height:1.5}
.panel-hint.top-hint {margin: 2px 4px 0;}
.sec-label{font-family:'dm sans', sans-serif;font-size:18px;font-weight:700;letter-spacing:.01em;color:var(--teal);margin-bottom:10px}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:28px}

/* ── Requirements list ── */
.req-list{border:1px solid #8A95A3;border-radius:14px;background:#FFFF;overflow:hidden;box-shadow:0 4px 8px rgba(0,0,0,0.04);}
.req-item{display:flex;align-items:flex-start;gap:11px;padding:10px 14px;font-size:13px;color:var(--ink);border-bottom:1px solid var(--mist);line-height:1.4}
.req-item:last-child{border-bottom:none}
.req-check{width:16px;height:16px;border-radius:50%;flex-shrink:0;margin-top:1px;display:grid;place-items:center;font-size:9px;font-weight:700}
.req-check.ok{background:var(--teal);color:#fff}
.req-check.warn{background:transparent;border:1.5px solid var(--mist)}
.req-sub{display:block;font-size:11px;color:var(--slate);margin-top:2px}

/* ── Eligibility table ── */
.elig-table{border:1px solid #8A95A3;border-radius:14px;background:#FFFF;overflow:hidden;box-shadow:0 4px 8px rgba(0,0,0,0.04);}
.elig-row{display:flex;align-items:center;justify-content:space-between;padding:9px 14px;font-size:12.5px;color:var(--ink);border-bottom:1px solid var(--mist);gap:8px}
.elig-row:last-child{border-bottom:none}
.elig-key{color:#4a5a68}
.badge{font-size:10.5px;font-weight:600;border-radius:999px;padding:3px 10px;white-space:nowrap;display:inline-flex;align-items:center;gap:4px}
.b-green{background:var(--green-bg);color:var(--green)}
.b-amber{background:var(--amber-bg);color:#92700a}
.b-teal{background:var(--teal-light);color:var(--teal)}
.b-gray{background:#ecf1f3;color:#6a8490}
.b-blue{background:#dbeeff;color:#1a4b7a}

/* ── Step 2 document grid ── */
.doc-cols{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:22px}
.doc-group{display:flex;flex-direction:column;gap:8px}
.doc-group-title{font-family:'dm sans', sans-serif;font-size:18px;font-weight:700;letter-spacing:.01em;color:var(--teal);margin-bottom:1px}
.doc-subcard{border:1.5px solid #8A95A3;border-radius:14px;padding:11px 13px;background:#fff;margin-bottom:8px;box-shadow:0 2px 6px rgba(0,0,0,0.04);}
.doc-card-label{font-size:12px;font-weight:600;color:var(--ink);margin-bottom:9px;line-height:1.4;display:flex;align-items:flex-start;justify-content:space-between;gap:6px}
.doc-card-label small{font-weight:400;color:var(--slate);font-size:11px}
.doc-file{display:flex;align-items:center;gap:9px;padding:7px 9px;border-radius:7px;background:var(--cloud);margin-bottom:7px;cursor:pointer}
.file-icon{width:28px;height:28px;border-radius:5px;background:var(--teal-light);color:var(--teal);font-size:9px;font-weight:700;display:grid;place-items:center;flex-shrink:0}
.file-icon.img{background:#fff0d6;color:#a07010}
.file-meta{flex:1;min-width:0}
.file-name{font-size:11.5px;font-weight:600;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.file-size{font-size:10px;color:var(--green)}
.file-size.muted{color:var(--slate)}
.radio{width:16px;height:16px;border-radius:50%;border:2px solid var(--mist);flex-shrink:0;display:grid;place-items:center;cursor:pointer}
.radio.sel{border-color:var(--teal)}
.radio.sel::after{content:'';width:8px;height:8px;border-radius:50%;background:var(--teal)}
.upload-trigger{width:100%;border:1.5px dashed var(--mist);border-radius:7px;background:transparent;padding:7px 10px;display:flex;align-items:center;gap:9px;cursor:pointer;transition:border-color .15s}
.upload-trigger:hover{border-color:var(--teal)}
.upload-icon-box{width:26px;height:26px;border-radius:5px;border:1.5px dashed #b0ccd6;display:grid;place-items:center;font-size:13px;color:#b0ccd6;flex-shrink:0}
.upload-text{font-size:11px;color:var(--slate);text-align:left;line-height:1.4}
.upload-text strong{color:var(--ink);font-weight:600;font-size:11.5px}
.doc-endorsement{margin-top:0}
.doc-endorsement .doc-subcard{max-width:300px}

/* ── Step 3: Confirm ── */
.confirm-block{margin-bottom:20px}
.confirm-heading{font-family:'dm sans', sans-serif;font-size:18px;font-weight:700;letter-spacing:.01em;color:var(--teal);margin-bottom:1px}
.confirm-table{border:1px solid var(--mist);border-radius:var(--r-lg);overflow:hidden}
.confirm-row{display:flex;justify-content:space-between;align-items:center;padding:8px 14px;font-size:12.5px;border-bottom:1px solid var(--mist)}
.confirm-row:last-child{border-bottom:none}
.ck{color:#4a5a68}
.cv{font-weight:500;color:var(--ink)}
.cv.ok{color:var(--green)}
.cv.pending{color:#92700a}
.cv.none{color:var(--slate);font-weight:400}
.certify-box{display:flex;align-items:flex-start;gap:11px;margin-top:18px;padding:13px 15px;background:var(--cloud);border-radius:var(--r);border:1px solid var(--mist);font-size:12.5px;color:var(--ink);line-height:1.6;cursor:pointer;transition:border-color .15s,background .15s}
.certify-box:has(input:checked){border-color:var(--teal);background:#f0fafa}
.certify-box.error{border-color:#EF4444;background:#fff5f5}
.certify-box input[type=checkbox]{width:15px;height:15px;margin-top:3px;flex-shrink:0;accent-color:var(--teal);cursor:pointer}

/* ── Footer nav ── */
.footer-nav{margin-top:24px;padding-top:16px;border-top:1px solid var(--mist);display:flex;justify-content:space-between;align-items:center}
.btn-back{padding:8px 20px;border-radius:var(--r);border:1.5px solid var(--mist);background:var(--white);font-size:12.5px;font-weight:600;color:#4a5a68;cursor:pointer;transition:all .15s;font-family:"DM Sans",sans-serif}
.btn-back:hover{border-color:var(--teal);color:var(--teal)}
.btn-next,.btn-submit{padding:8px 22px;border-radius:var(--r);border:none;background:linear-gradient(135deg, #0F4C5C, #1A6B7A);font-size:12.5px;font-weight:600;color: #F9D679;cursor:pointer;transition:transform 0.15s ease, box-shadow 0.15s ease; box-shadow: 0 6px 14px rgba(0,0,0,0.2);will-change: transform;font-family:"DM Sans",sans-serif;display:flex;align-items:center;gap:6px}
.btn-next:hover,.btn-submit:hover{background:var(--teal-mid);}
.step-counter{font-size:12px;color:var(--slate)}

/* ── Success panel ── */
.success-panel{display:none;padding:48px 48px 40px;text-align:center;background:#fff}
.success-panel.active{display:block;animation:fadeUp .4s ease both}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.success-check{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg, #0F4C5C, #1A6B7A);display:grid;place-items:center;margin:0 auto 20px;box-shadow:0 8px 24px rgba(15,76,92,.25)}
.success-check svg{width:28px;height:28px;}
.success-title{font-family:"Fraunces",serif;font-size:26px;font-weight:800;color:var(--ink);margin-bottom:10px}
.success-note{font-size:13px;color:#5a6a78;max-width:380px;margin:0 auto 28px;line-height:1.65}
.success-note strong{color:var(--ink)}
/* Application ID badge */
.appid-label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--slate);margin-bottom:8px}
.appid-badge{display:inline-block;background:var(--gold-light);color:#5c3d00;font-family:"Fraunces",serif;font-size:20px;font-weight:800;padding:9px 28px;border-radius:999px;letter-spacing:.04em;margin-bottom:10px;border:2px solid var(--gold)}
.review-pill{display:inline-flex;align-items:center;gap:6px;background:var(--white);border:1px solid var(--mist);border-radius:999px;padding:5px 14px;font-size:11.5px;color:var(--slate);margin-bottom:32px}
/* Progress steps */
.progress-steps{text-align:left;max-width:320px;margin:0 auto 32px;display:flex;flex-direction:column;gap:0}
.progress-step{display:flex;align-items:center;gap:12px;padding:9px 0;position:relative}
.progress-step:not(:last-child)::after{content:'';position:absolute;left:15px;top:36px;width:2px;height:calc(100% - 10px);background:var(--mist)}
.ps-circle{width:30px;height:30px;border-radius:50%;border:2px solid var(--mist);background:var(--white);color:var(--slate);font-size:12px;font-weight:700;display:grid;place-items:center;flex-shrink:0;position:relative;z-index:1;font-family:"Fraunces",serif}
.ps-circle.done{background:var(--teal);border-color:var(--teal);color:#fff}
.ps-label{font-size:13px;font-weight:500;color:var(--slate)}
.ps-label.done{color:var(--ink);font-weight:600}
/* Divider */
.success-divider{border:none;border-top:1px solid var(--mist);margin:0 0 24px}
/* Action buttons */
.success-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-secondary{padding:11px 22px;border-radius:var(--r-lg);border:1.5px solid var(--mist);background:var(--white);font-size:13px;font-weight:600;color:var(--ink);cursor:pointer;transition:all .15s;font-family:"DM Sans",sans-serif;min-width:160px}
.btn-secondary:hover{border-color:var(--teal);color:var(--teal)}
.btn-primary-outline{padding:11px 22px;border-radius:var(--r-lg);border:1.5px solid var(--mist);background:var(--white);font-size:13px;font-weight:600;color:var(--ink);cursor:pointer;transition:all .15s;font-family:"DM Sans",sans-serif;min-width:160px}
.btn-primary-outline:hover{border-color:var(--teal);color:var(--teal)}
</style>
</head>
<body>

<div class="wizard" id="wizard">

  <!-- Stepper -->
  <div class="stepper-wrap">
    <div class="stepper" id="stepper">
      <div class="step-item active" id="sn1">
        <div class="step-circle"><span class="step-number">1</span></div>
        <div class="step-label">Review<br>Requirements</div>
      </div>
      <div class="step-item" id="sn2">
        <div class="step-circle"><span class="step-number">2</span></div>
        <div class="step-label">Select<br>Documents</div>
      </div>
      <div class="step-item" id="sn3">
        <div class="step-circle"><span class="step-number">3</span></div>
        <div class="step-label">Confirm &amp;<br>Submit</div>
      </div>
    </div>
  </div>

  <!-- Alert bar -->
  <div class="alerts-bar" id="alerts-bar">
    <p class="panel-hint top-hint">Check that you meet all eligibility criteria before proceeding with your scholarship application.</p>
    <div class="alert alert-blue" id="alert-scholarship">
      <span class="alert-star">★</span> <span id="alert-scholarship-name">Loading…</span>
    </div>
    <div class="alert alert-gold" id="alert-eligibility" style="display:none">
      <span class="alert-dot"></span> <span id="alert-eligibility-text"></span>
    </div>
  </div>

  <!-- Loading -->
  <div id="loading-wrap" style="display:block">
    <div class="loading-state">
      <div class="loading-spinner"></div>
      Loading application data…
    </div>
  </div>

  <!-- ── STEP 1 ── -->
  <div class="panel" id="panel-1">
    <div class="two-col">
      <div>
        <div class="sec-label">Requirements checklist</div>
        <div class="req-list" id="req-list"><!-- populated by JS --></div>
      </div>
      <div>
        <div class="sec-label">Eligibility check results</div>
        <div class="elig-table" id="elig-table"><!-- populated by JS --></div>
      </div>
    </div>
    <div class="footer-nav">
      <span class="step-counter">Step 1 of 3</span>
      <button class="btn-next" onclick="goTo(2)">Next </button>
    </div>
  </div>

  <!-- ── STEP 2 ── -->
  <div class="panel" id="panel-2">
    <p class="panel-hint">Pick from your saved documents or upload new files. All items are required unless marked optional.</p>
    <div class="doc-cols" id="doc-cols"><!-- populated by JS --></div>
    <div class="doc-endorsement" id="doc-endorsement"><!-- populated by JS --></div>
    <div class="footer-nav">
      <button class="btn-back" onclick="goTo(1)">Back</button>
      <div style="display:flex;align-items:center;gap:14px">
        <span class="step-counter">Step 2 of 3</span>
        <button class="btn-next" onclick="goTo(3)">Next </button>
      </div>
    </div>
  </div>

  <!-- ── STEP 3 ── -->
  <div class="panel" id="panel-3">
    <p class="panel-hint">Review your application carefully. You will not be able to make changes after submission.</p>
    <div id="confirm-content"><!-- populated by JS --></div>
    <label class="certify-box" id="certify-label">
      <input type="checkbox" id="certify" onchange="document.getElementById('certify-label').classList.remove('error')"/>
      I certify that all information and documents submitted are true, correct, and complete. I understand that any misrepresentation may result in disqualification and legal action under applicable laws.
    </label>
    <div class="footer-nav">
      <button class="btn-back" onclick="goTo(2)">Back</button>
      <div style="display:flex;align-items:center;gap:14px">
        <span class="step-counter">Step 3 of 3</span>
        <button class="btn-submit" onclick="handleSubmit()">Submit Application</button>
      </div>
    </div>
  </div>

  <!-- ── SUCCESS ── -->
  <div class="success-panel" id="panel-success">
    <div class="success-check">
      <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 14.5l5.5 5.5 10.5-11" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <div class="success-title">Application Submitted!</div>
    <div class="success-note" id="success-note">
      Your application for <strong id="success-scholarship-name">—</strong> has been received. You'll get updates via email and SMS.
    </div>

    <div class="appid-label">Application ID</div>
    <div class="appid-badge" id="ref-code">—</div><br>
    <div class="review-pill">
      <span class="review-pill-dot"></span>
      Estimated review: 7–14 days
    </div>

    <div class="progress-steps">
      <div class="progress-step">
        <div class="ps-circle done">✓</div>
        <span class="ps-label done">Application submitted</span>
      </div>
      <div class="progress-step">
        <div class="ps-circle">2</div>
        <span class="ps-label">Document verification</span>
      </div>
      <div class="progress-step">
        <div class="ps-circle">3</div>
        <span class="ps-label">Evaluation and scoring</span>
      </div>
      <div class="progress-step">
        <div class="ps-circle">4</div>
        <span class="ps-label">Final decision</span>
      </div>
    </div>

    <hr class="success-divider"/>
    <div class="success-actions">
      <button class="btn-secondary" onclick="alert('Browse scholarships')">Browse more scholarships</button>
      <button class="btn-primary-outline" onclick="alert('Track application: ' + applicationState.reference_code)">Track my application</button>
    </div>
  </div>

</div>

<script>
/* ─────────────────────────────────────────────
   MOCK DATABASE — exact field names per docs
   ───────────────────────────────────────────── */

// Table: users (applicant)
const currentUser = {
  id: 1,
  first_name: "Maria",
  last_name: "Santos",
  email: "maria.santos@gmail.com",
  role: "applicant",
  is_active: 1,
  email_verified_at: "2026-01-01 00:00:00"
};

// Table: applicant_profiles
const applicantProfile = {
  id: 1,
  user_id: 1,
  avatar_url: null,
  date_of_birth: "2005-06-12",
  sex: "Female",
  home_address: "123 Mabini St.",
  city: "Baras",
  province: "Rizal",
  zip_code: "1970",
  mobile_number: "9171234567",
  sms_opted_in: 0,
  phone_verified_at: null,
  university_name: "University of Rizal System",
  university_address: "Morong, Rizal",
  university_email: "maria.santos@urs.edu.ph",
  course_program: "BS Information Technology",
  student_number: "2026-12345",
  year_level: "2nd Year",
  semester: "1st",
  academic_year: "2026-2027",
  gwa: 1.50,
  gwa_scale: "college",
  monthly_household_income: 12000.00,
  num_dependents: 4,
  is_breadwinner: "Partial Contributor",
  is_4ps: 1,
  father_employment_status: "Tricycle Driver",
  mother_employment_status: "Unemployed",
  profile_completed_at: null
};

// Table: scholarships (the one being applied to)
const scholarship = {
  id: 16,
  name: "DOST-SEI Merit Award 2025",
  provider_name: "DOST – Science Education Institute",
  created_by: null,
  tagline: "Advancing Filipino science and innovation through merit.",
  description: "The DOST-SEI Merit Award supports academically excellent Filipino students pursuing STEM degrees in state universities and colleges.",
  gpa_requirement: 1.75,
  income_bracket: "≤ ₱400,000/yr",
  slots: 100,
  eligibility: "Filipino citizen, enrolled in a CHED-accredited STEM program, GWA ≤ 1.75, annual family income ≤ ₱400,000, no concurrent scholarship, no pending disciplinary case.",
  benefits: "Full tuition coverage, monthly stipend of ₱5,000, book allowance of ₱10,000/yr, thesis grant up to ₱15,000.",
  requirements: "Official Transcript of Records, Certificate of Enrollment, Valid Government ID, Income Tax Return or BIR Form 2316, Endorsement letter from school registrar.",
  open_date: "2025-12-01",
  deadline: "2026-03-31",
  status: "open",
  blind_screening: 0,
  weight_gpa: 60,
  weight_income: 40,
  tags: null,
  ai_match_enabled: 1,
  gcal_event_id: null,
  contact_email: "scholarships@dostsei.gov.ph",
  website: "https://www.sei.dost.gov.ph",
  address: "Science Heritage Building, DOST Compound, Bicutan, Taguig",
  benefit_snippet_1: "Full tuition coverage",
  benefit_snippet_2: "₱5,000/mo stipend + ₱10,000 book allowance",
  org_logo: "storage/app/logos/dost_sei.png",
  posted_at: "2025-11-15"
};

// Table: documents (applicant's saved document vault)
const savedDocuments = [
  {
    id: 1,
    user_id: 1,
    document_type: "Latest Report Card / TOR",
    file_url: "storage/app/documents/user_1/transcript_of_records.pdf",
    status: "verified",
    expiry_date: "2027-01-01",
    expiry_notified_at: null,
    verified_by: 11
  },
  {
    id: 2,
    user_id: 1,
    document_type: "Certificate of Enrollment",
    file_url: "storage/app/documents/user_1/coe_ay2425_1st.pdf",
    status: "verified",
    expiry_date: "2027-01-01",
    expiry_notified_at: null,
    verified_by: 11
  },
  {
    id: 3,
    user_id: 1,
    document_type: "PSA Birth Certificate",
    file_url: "storage/app/documents/user_1/psa_birth_certificate.pdf",
    status: "verified",
    expiry_date: null,
    expiry_notified_at: null,
    verified_by: 11
  },
  {
    id: 4,
    user_id: 1,
    document_type: "Barangay Certificate of Indigency",
    file_url: "storage/app/documents/user_1/barangay_cert.pdf",
    status: "pending",
    expiry_date: "2027-01-01",
    expiry_notified_at: null,
    verified_by: null
  },
  {
    id: 5,
    user_id: 1,
    document_type: "Certificate of Good Moral Character",
    file_url: "storage/app/documents/user_1/good_moral.pdf",
    status: "verified",
    expiry_date: "2027-01-01",
    expiry_notified_at: null,
    verified_by: 11
  }
];

// Application session state — will become a row in `applications`
const applicationState = {
  reference_code: null,
  applicant_id: currentUser.id,
  scholarship_id: scholarship.id,
  status: "pending",
  stage: "submitted",
  ai_match_score: null,
  conflict_flag: 0,
  submitted_at: null,
  decided_at: null,
  selectedDocs: {}
};

/* ─────────────────────────────────────────────
   ELIGIBILITY LOGIC
   ───────────────────────────────────────────── */

function computeEligibility() {
  const annualIncome = applicantProfile.monthly_household_income * 12;
  const incomeLimit = 400000;

  return {
    gpa: {
      label: "Academic standing",
      pass: applicantProfile.gwa <= scholarship.gpa_requirement,
      badge: applicantProfile.gwa <= scholarship.gpa_requirement ? "Qualified" : "Insufficient",
      badgeClass: applicantProfile.gwa <= scholarship.gpa_requirement ? "b-green" : "b-red"
    },
    citizenship: {
      label: "Citizenship",
      pass: true,
      badge: "Verified",
      badgeClass: "b-green"
    },
    enrollment: {
      label: "Enrollment status",
      pass: true,
      badge: "Active",
      badgeClass: "b-teal"
    },
    income: {
      label: "Family income bracket",
      pass: null,
      badge: "Pending",
      badgeClass: "b-amber"
    },
    concurrent: {
      label: "Concurrent scholarship",
      pass: true,
      badge: "None found",
      badgeClass: "b-gray"
    },
    course: {
      label: "Course eligibility",
      pass: true,
      badge: "Priority course",
      badgeClass: "b-blue"
    },
    disciplinary: {
      label: "Disciplinary record",
      pass: true,
      badge: "Clear",
      badgeClass: "b-gray"
    }
  };
}

function getRequirementsChecklist() {
  const elig = computeEligibility();
  return [
    {
      ok: elig.income.pass !== false,
      warn: elig.income.pass === null,
      label: `Annual family income`,
      sub: `Limit: ${scholarship.income_bracket} — income not yet verified`
    },
    {
      ok: elig.gpa.pass,
      warn: false,
      label: `GPA of ${scholarship.gpa_requirement} or better`,
      sub: `Your GWA: ${applicantProfile.gwa.toFixed(2)} — ${elig.gpa.pass ? 'qualifies' : 'does not qualify'}`
    },
    {
      ok: elig.concurrent.pass,
      warn: false,
      label: `No active scholarship grant`,
      sub: null
    },
    {
      ok: elig.enrollment.pass,
      warn: false,
      label: `Currently enrolled (college level)`,
      sub: null
    },
    {
      ok: false,
      warn: true,
      label: `Endorsement letter from school`,
      sub: `Must be uploaded in Step 2`
    }
  ];
}

/* ─────────────────────────────────────────────
   DOCUMENT REQUIREMENT GROUPS
   ───────────────────────────────────────────── */

function getDocumentGroups() {
  return [
    {
      groupTitle: "Academic records",
      slots: [
        {
          label: "Official transcript of records",
          document_type: "Latest Report Card / TOR",
          optional: false
        },
        {
          label: "Certificate of enrollment",
          document_type: "Certificate of Enrollment",
          optional: false
        }
      ]
    },
    {
      groupTitle: "Financial documents",
      slots: [
        {
          label: "Income tax return (ITR) or BIR Form 2316",
          document_type: "Income Tax Return",
          optional: false
        },
        {
          label: "Barangay certificate of indigency",
          smallNote: "if no ITR",
          document_type: "Barangay Certificate of Indigency",
          optional: true
        }
      ]
    },
    {
      groupTitle: "Identity & family",
      slots: [
        {
          label: "PSA Birth Certificate",
          document_type: "PSA Birth Certificate",
          optional: false
        }
      ]
    }
  ];
}

const endorsementSlot = {
  groupTitle: "School endorsement",
  label: "Endorsement letter from school registrar",
  document_type: "Endorsement Letter",
  optional: false
};

/* ─────────────────────────────────────────────
   UI RENDER FUNCTIONS
   ───────────────────────────────────────────── */

function getFileName(file_url) {
  if (!file_url) return "Unknown file";
  return file_url.split("/").pop();
}

function getFileExt(file_url) {
  const name = getFileName(file_url);
  const parts = name.split(".");
  return parts.length > 1 ? parts.pop().toUpperCase() : "FILE";
}

function renderStep1() {
  const checklist = getRequirementsChecklist();
  const elig = computeEligibility();

  const reqHtml = checklist.map(item => {
    const checkClass = item.ok && !item.warn ? "ok" : (item.warn ? "warn" : "");
    const checkMark = item.ok && !item.warn ? "✓" : "";
    return `
      <div class="req-item">
        <div class="req-check ${checkClass}">${checkMark}</div>
        <div>
          ${item.label}
          ${item.sub ? `<span class="req-sub">${item.sub}</span>` : ""}
        </div>
      </div>`;
  }).join("");
  document.getElementById("req-list").innerHTML = reqHtml;

  const eligHtml = Object.values(elig).map(e => `
    <div class="elig-row">
      <span class="elig-key">${e.label}</span>
      <span class="badge ${e.badgeClass}">${e.badge}</span>
    </div>`).join("");
  document.getElementById("elig-table").innerHTML = eligHtml;

  document.getElementById("alert-scholarship-name").textContent =
    `Scholarship: ${scholarship.name}`;

  const pendingCount = Object.values(elig).filter(e => e.pass === null).length;
  if (pendingCount > 0) {
    document.getElementById("alert-eligibility").style.display = "inline-flex";
    document.getElementById("alert-eligibility-text").textContent =
      `${pendingCount} eligibility item${pendingCount > 1 ? "s" : ""} need${pendingCount === 1 ? "s" : ""} attention — you may still proceed.`;
  }
}

function renderStep2() {
  const groups = getDocumentGroups();
  const colsHtml = groups.map(group => {
    const slotsHtml = group.slots.map(slot => {
      const matches = savedDocuments.filter(d => d.document_type === slot.document_type);
      const filesHtml = matches.map(doc => {
        const isSelected = applicationState.selectedDocs[slot.document_type] === doc.id;
        const ext = getFileExt(doc.file_url);
        const isImg = ["PNG","JPG","JPEG"].includes(ext);
        const statusColor = doc.status === "verified" ? "var(--green)" :
                            doc.status === "pending" ? "#92700a" : "var(--red)";
        return `
          <div class="doc-file" onclick="selectDoc('${slot.document_type}', ${doc.id}, this)" data-doc-id="${doc.id}">
            <div class="file-icon ${isImg ? 'img' : ''}">${ext.substring(0,3)}</div>
            <div class="file-meta">
              <div class="file-name">${getFileName(doc.file_url)}</div>
              <div class="file-size" style="color:${statusColor}">${doc.status === 'verified' ? 'Verified · ' : 'Pending · '}${(Math.random()*2+0.8).toFixed(1)} MB</div>
            </div>
            <div class="radio ${isSelected ? 'sel' : ''}" id="radio-${doc.id}"></div>
          </div>`;
      }).join("");

      const labelNote = slot.smallNote ? `<small>(${slot.smallNote})</small>` : "";
      const optTag = slot.optional ? `<small style="color:var(--slate);font-weight:400">Optional</small>` : "";
      return `
        <div class="doc-subcard">
          <div class="doc-card-label">${slot.label} ${labelNote} ${optTag}</div>
          ${filesHtml}
          <button class="upload-trigger" onclick="triggerUpload('${slot.document_type}')">
            <div class="upload-icon-box">↑</div>
            <div class="upload-text"><strong>Upload new file</strong><br>PDF, JPG, PNG up to 5MB</div>
          </button>
        </div>`;
    }).join("");

    return `<div class="doc-group">
      <div class="doc-group-title">${group.groupTitle}</div>
      ${slotsHtml}
    </div>`;
  }).join("");

  document.getElementById("doc-cols").innerHTML = colsHtml;

  document.getElementById("doc-endorsement").innerHTML = `
    <div class="doc-group-title">${endorsementSlot.groupTitle}</div>
    <div class="doc-subcard">
      <div class="doc-card-label">${endorsementSlot.label}</div>
      <button class="upload-trigger" onclick="triggerUpload('${endorsementSlot.document_type}')">
        <div class="upload-icon-box">↑</div>
        <div class="upload-text"><strong>Upload new file</strong><br>PDF, JPG, PNG up to 5MB</div>
      </button>
    </div>`;

  groups.forEach(group => {
    group.slots.forEach(slot => {
      const matches = savedDocuments.filter(d => d.document_type === slot.document_type && d.status === "verified");
      if (matches.length > 0 && !applicationState.selectedDocs[slot.document_type]) {
        applicationState.selectedDocs[slot.document_type] = matches[0].id;
        const radio = document.getElementById("radio-" + matches[0].id);
        if (radio) radio.classList.add("sel");
      }
    });
  });
}

function selectDoc(document_type, docId, rowEl) {
  applicationState.selectedDocs[document_type] = docId;
  const subcard = rowEl.closest(".doc-subcard");
  subcard.querySelectorAll(".radio").forEach(r => r.classList.remove("sel"));
  rowEl.querySelector(".radio").classList.add("sel");
}

function triggerUpload(document_type) {
  alert(`Upload dialog for: ${document_type}\n(In production, this opens a file picker and saves to storage/app/documents/user_${currentUser.id}/)`);
}

function renderStep3() {
  const elig = computeEligibility();

  const docRows = getDocumentGroups().flatMap(g => g.slots).map(slot => {
    const docId = applicationState.selectedDocs[slot.document_type];
    const doc = docId ? savedDocuments.find(d => d.id === docId) : null;
    const displayVal = doc ? getFileName(doc.file_url) : "Not selected";
    const valClass = doc ? "" : "none";
    return `<div class="confirm-row"><span class="ck">${slot.label}</span><span class="cv ${valClass}">${displayVal}</span></div>`;
  }).join("");

  const endorsementUploaded = applicationState.selectedDocs[endorsementSlot.document_type];
  const endorsementRow = `<div class="confirm-row"><span class="ck">Endorsement letter</span><span class="cv ${endorsementUploaded ? '' : 'pending'}">${endorsementUploaded ? "Uploaded" : "Pending upload"}</span></div>`;

  const annualIncome = (applicantProfile.monthly_household_income * 12).toLocaleString("en-PH", {minimumFractionDigits: 0});

  document.getElementById("confirm-content").innerHTML = `
    <div class="confirm-block">
      <div class="confirm-heading">Scholarship details</div>
      <div class="confirm-table">
        <div class="confirm-row"><span class="ck">Scholarship</span><span class="cv">${scholarship.name}</span></div>
        <div class="confirm-row"><span class="ck">Provider</span><span class="cv">${scholarship.provider_name}</span></div>
        <div class="confirm-row"><span class="ck">Applicant</span><span class="cv">${currentUser.first_name} ${currentUser.last_name}</span></div>
        <div class="confirm-row"><span class="ck">Course &amp; Year</span><span class="cv">${applicantProfile.course_program}, ${applicantProfile.year_level}</span></div>
        <div class="confirm-row"><span class="ck">School</span><span class="cv">${applicantProfile.university_name}</span></div>
        <div class="confirm-row"><span class="ck">Academic year</span><span class="cv">${applicantProfile.academic_year} — ${applicantProfile.semester} Semester</span></div>
        <div class="confirm-row"><span class="ck">Student number</span><span class="cv">${applicantProfile.student_number}</span></div>
        <div class="confirm-row"><span class="ck">GWA (${applicantProfile.gwa_scale} scale)</span><span class="cv ok">${applicantProfile.gwa.toFixed(2)} — qualifies</span></div>
        <div class="confirm-row"><span class="ck">Monthly household income</span><span class="cv">₱${applicantProfile.monthly_household_income.toLocaleString()} (₱${annualIncome}/yr)</span></div>
        <div class="confirm-row"><span class="ck">Household dependents</span><span class="cv">${applicantProfile.num_dependents}</span></div>
        <div class="confirm-row"><span class="ck">Breadwinner status</span><span class="cv">${applicantProfile.is_breadwinner}</span></div>
        <div class="confirm-row"><span class="ck">4Ps beneficiary</span><span class="cv">${applicantProfile.is_4ps ? 'Yes' : 'No'}</span></div>
      </div>
    </div>

    <div class="confirm-block">
      <div class="confirm-heading">Eligibility results</div>
      <div class="confirm-table">
        ${Object.values(elig).map(e => {
          const cls = e.pass === true ? "ok" : e.pass === null ? "pending" : "none";
          return `<div class="confirm-row"><span class="ck">${e.label}</span><span class="cv ${cls}">${e.badge}</span></div>`;
        }).join("")}
      </div>
    </div>

    <div class="confirm-block">
      <div class="confirm-heading">Documents submitted</div>
      <div class="confirm-table">
        ${docRows}
        ${endorsementRow}
      </div>
    </div>`;
}

/* ─────────────────────────────────────────────
   WIZARD NAVIGATION
   ───────────────────────────────────────────── */

function goTo(step) {
  [1, 2, 3].forEach(i => {
    document.getElementById("panel-" + i).classList.toggle("active", i === step);
    const n = document.getElementById("sn" + i);
    n.classList.remove("active", "done");
    if (i === step) n.classList.add("active");
    if (i < step) n.classList.add("done");
  });

  // Show alerts bar only on step 1
  document.getElementById("alerts-bar").classList.toggle("hidden", step !== 1);

  if (step === 2) renderStep2();
  if (step === 3) renderStep3();
  window.scrollTo({ top: 0, behavior: "smooth" });
}

/* ─────────────────────────────────────────────
   SUBMIT
   ───────────────────────────────────────────── */

function generateReferenceCode() {
  const initials = (currentUser.first_name[0] + currentUser.last_name[0]).toUpperCase();
  const year = new Date().getFullYear();
  const queue = String(Math.floor(Math.random() * 89999) + 10001).padStart(5, "0");
  return `${initials}-${year}-${queue}`;
}

function handleSubmit() {

  const cb = document.getElementById("certify");
  const lbl = document.getElementById("certify-label");
  if (!cb.checked) {
    lbl.classList.add("error");
    lbl.scrollIntoView({ behavior: "smooth", block: "center" });
    return;
  }

  applicationState.reference_code = generateReferenceCode();
  applicationState.status = "pending";
  applicationState.stage = "submitted";
  applicationState.submitted_at = new Date().toISOString().split("T")[0];

  console.log("=== applications row (INSERT) ===", {
    reference_code: applicationState.reference_code,
    applicant_id: applicationState.applicant_id,
    scholarship_id: applicationState.scholarship_id,
    status: applicationState.status,
    stage: applicationState.stage,
    ai_match_score: null,
    conflict_flag: applicationState.conflict_flag,
    submitted_at: applicationState.submitted_at,
    decided_at: null
  });

  [1,2,3].forEach(i => {
    document.getElementById("panel-" + i).classList.remove("active");
    const n = document.getElementById("sn" + i);
    n.classList.remove("active");
    n.classList.add("done");
  });

  // Hide alerts bar on success screen too
  document.getElementById("alerts-bar").classList.add("hidden");

  document.getElementById("wizard").classList.add("is-success");
  // Populate success panel from DB-driven data
  document.getElementById("ref-code").textContent = applicationState.reference_code;
  document.getElementById("success-scholarship-name").textContent = scholarship.name;
  document.getElementById("panel-success").classList.add("active");
  window.scrollTo({ top: 0, behavior: "smooth" });
}

/* ─────────────────────────────────────────────
   INIT
   ───────────────────────────────────────────── */

function init() {
  setTimeout(() => {
    document.getElementById("loading-wrap").style.display = "none";
    renderStep1();
    document.getElementById("panel-1").classList.add("active");
  }, 700);
}

init();
</script>
</body>
</html>

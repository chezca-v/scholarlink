@extends('layouts.applicant')

@section('title', 'ScholarLink - Apply')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet"/>
<style>
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
.wizard-container{
  background:linear-gradient(135deg,#0F4C5C 0%,#1A6B7A 100%);
  display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
  padding:40px 16px 60px;
  border-radius: 18px;
  min-height: calc(100vh - 120px);
}
.wizard{width:100%;max-width:900px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.30)}
.wizard.is-success .stepper-wrap{display:none}
.stepper-wrap{background:#F0FAFA;border-bottom:1px solid var(--mist);padding:24px 48px 0}
.stepper{display:flex;align-items:flex-start;gap:0;max-width:640px;margin:0 auto}
.step-item{display:flex;flex-direction:column;align-items:center;gap:8px;flex:1;position:relative}
.step-item:not(:last-child)::after{content:'';position:absolute;top:19px;left:calc(50% + 26px);right:calc(-50% + 26px);height:2px;background:#2f5d66;transition:background .3s}
.step-item.done::after{background:var(--teal)}
.step-circle{width:38px;height:38px;border-radius:50%;border:2px solid #2f5d66;background:transparent;color:#2f5d66;font-family:"fraunces",sans-serif;font-size:18px;font-weight:600;display:grid;place-items:center;position:relative;z-index:1;transition:all .2s}
.step-item.active .step-circle{background:#0F3F4A;border:none;color:#fff;box-shadow:0 10px 22px rgba(0,0,0,.22)}
.step-item.done .step-circle{background:transparent;border-color:var(--teal);color:var(--teal)}
.step-item.done .step-circle::after{content:'✓';font-size:14px;font-family:"DM Sans",sans-serif;font-weight:700}
.step-item.done .step-number{display:none}
.step-label{font-family:'fraunces',sans-serif;font-size:13px;font-weight:700;color:#2f5d66;text-align:center;line-height:1.3;padding-bottom:18px}
.step-item.active .step-label{color:var(--teal)}
.step-item.done .step-label{color:var(--teal-mid)}
.alerts-bar{padding:14px 48px 0;display:flex;flex-direction:column;gap:6px;padding-bottom:0}
.alerts-bar.hidden{display:none}
.alert{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:5px 14px;font-size:12.5px;font-weight:600;width:fit-content}
.alert-blue{background:#dbeeff;color:#1a4b7a}
.alert-gold{background:#fff8e1;color:#9a6b00}
.alert-dot{width:7px;height:7px;border-radius:50%;background:currentColor;flex-shrink:0}
.alert-star{font-size:11px}
.panel{display:none;padding:28px 48px 28px}
.panel.active{display:block}
.panel-hint{font-size:12.5px;color:var(--slate);margin-bottom:20px;line-height:1.5}
.panel-hint.top-hint{margin:2px 4px 0}
.sec-label{font-family:'dm sans',sans-serif;font-size:18px;font-weight:700;letter-spacing:.01em;color:var(--teal);margin-bottom:10px}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:28px}
.req-list{border:1px solid #8A95A3;border-radius:14px;background:#FFFF;overflow:hidden;box-shadow:0 4px 8px rgba(0,0,0,0.04)}
.req-item{display:flex;align-items:flex-start;gap:11px;padding:10px 14px;font-size:13px;color:var(--ink);border-bottom:1px solid var(--mist);line-height:1.4}
.req-item:last-child{border-bottom:none}
.req-check{width:16px;height:16px;border-radius:50%;flex-shrink:0;margin-top:1px;display:grid;place-items:center;font-size:9px;font-weight:700}
.req-check.ok{background:var(--teal);color:#fff}
.req-check.warn{background:transparent;border:1.5px solid var(--mist)}
.req-sub{display:block;font-size:11px;color:var(--slate);margin-top:2px}
.elig-table{border:1px solid #8A95A3;border-radius:14px;background:#FFFF;overflow:hidden;box-shadow:0 4px 8px rgba(0,0,0,0.04)}
.elig-row{display:flex;align-items:center;justify-content:space-between;padding:9px 14px;font-size:12.5px;color:var(--ink);border-bottom:1px solid var(--mist);gap:8px}
.elig-row:last-child{border-bottom:none}
.elig-key{color:#4a5a68}
.badge{font-size:10.5px;font-weight:600;border-radius:999px;padding:3px 10px;white-space:nowrap;display:inline-flex;align-items:center;gap:4px}
.b-green{background:var(--green-bg);color:var(--green)}
.b-amber{background:var(--amber-bg);color:#92700a}
.b-teal{background:var(--teal-light);color:var(--teal)}
.b-gray{background:#ecf1f3;color:#6a8490}
.b-blue{background:#dbeeff;color:#1a4b7a}
.b-red{background:#fee2e2;color:#b91c1c}
.doc-cols{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:22px}
.doc-group{display:flex;flex-direction:column;gap:8px}
.doc-group-title{font-family:'dm sans',sans-serif;font-size:18px;font-weight:700;letter-spacing:.01em;color:var(--teal);margin-bottom:1px}
.doc-subcard{border:1.5px solid #8A95A3;border-radius:14px;padding:11px 13px;background:#fff;margin-bottom:8px;box-shadow:0 2px 6px rgba(0,0,0,0.04)}
.doc-card-label{font-size:12px;font-weight:600;color:var(--ink);margin-bottom:9px;line-height:1.4;display:flex;align-items:flex-start;justify-content:space-between;gap:6px}
.doc-card-label small{font-weight:400;color:var(--slate);font-size:11px}
.doc-file{display:flex;align-items:center;gap:9px;padding:7px 9px;border-radius:7px;background:var(--cloud);margin-bottom:7px;cursor:pointer}
.file-icon{width:28px;height:28px;border-radius:5px;background:var(--teal-light);color:var(--teal);font-size:9px;font-weight:700;display:grid;place-items:center;flex-shrink:0}
.file-icon.img{background:#fff0d6;color:#a07010}
.file-meta{flex:1;min-width:0}
.file-name{font-size:11.5px;font-weight:600;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.file-size{font-size:10px;color:var(--green)}
.file-size.muted{color:var(--slate)}
.radio{width:16px;height:16px;border-radius:50%;border:2px solid var(--mist);flex-shrink:0;display:grid;place-items:center;cursor:pointer;transition:border-color .15s}
.radio.sel{border-color:var(--teal)}
.radio.sel::after{content:'';width:8px;height:8px;border-radius:50%;background:var(--teal)}
.upload-trigger{width:100%;border:1.5px dashed var(--mist);border-radius:7px;background:transparent;padding:7px 10px;display:flex;align-items:center;gap:9px;cursor:pointer;transition:border-color .15s;position:relative;overflow:hidden}
.upload-trigger:hover{border-color:var(--teal)}
.upload-icon-box{width:26px;height:26px;border-radius:5px;border:1.5px dashed #b0ccd6;display:grid;place-items:center;font-size:13px;color:#b0ccd6;flex-shrink:0}
.upload-text{font-size:11px;color:var(--slate);text-align:left;line-height:1.4}
.upload-text strong{color:var(--ink);font-weight:600;font-size:11.5px}
.doc-endorsement{margin-top:0}
.doc-endorsement .doc-subcard{max-width:300px}
.real-file-input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.confirm-block{margin-bottom:20px}
.confirm-heading{font-family:'dm sans',sans-serif;font-size:18px;font-weight:700;letter-spacing:.01em;color:var(--teal);margin-bottom:1px}
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
.footer-nav{margin-top:24px;padding-top:16px;border-top:1px solid var(--mist);display:flex;justify-content:space-between;align-items:center}
.btn-back{padding:8px 20px;border-radius:var(--r);border:1.5px solid var(--mist);background:var(--white);font-size:12.5px;font-weight:600;color:#4a5a68;cursor:pointer;transition:all .15s;font-family:"DM Sans",sans-serif}
.btn-back:hover{border-color:var(--teal);color:var(--teal)}
.btn-next,.btn-submit{padding:8px 22px;border-radius:var(--r);border:none;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);font-size:12.5px;font-weight:600;color:#F9D679;cursor:pointer;transition:transform 0.15s ease,box-shadow 0.15s ease;box-shadow:0 6px 14px rgba(0,0,0,0.2);will-change:transform;font-family:"DM Sans",sans-serif;display:flex;align-items:center;gap:6px}
.btn-next:hover,.btn-submit:hover{background:var(--teal-mid)}
.step-counter{font-size:12px;color:var(--slate)}
.success-panel{display:none;padding:48px 48px 40px;text-align:center;background:#fff}
.success-panel.active{display:block;animation:fadeUp .4s ease both}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.success-check{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#0F4C5C,#1A6B7A);display:grid;place-items:center;margin:0 auto 20px;box-shadow:0 8px 24px rgba(15,76,92,.25)}
.success-check svg{width:28px;height:28px}
.success-title{font-family:"Fraunces",serif;font-size:26px;font-weight:800;color:var(--ink);margin-bottom:10px}
.success-note{font-size:13px;color:#5a6a78;max-width:380px;margin:0 auto 28px;line-height:1.65}
.success-note strong{color:var(--ink)}
.appid-label{font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--slate);margin-bottom:8px}
.appid-badge{display:inline-block;background:var(--gold-light);color:#5c3d00;font-family:"Fraunces",serif;font-size:20px;font-weight:800;padding:9px 28px;border-radius:999px;letter-spacing:.04em;margin-bottom:10px;border:2px solid var(--gold)}
.review-pill{display:inline-flex;align-items:center;gap:6px;background:var(--white);border:1px solid var(--mist);border-radius:999px;padding:5px 14px;font-size:11.5px;color:var(--slate);margin-bottom:32px}
.progress-steps{text-align:left;max-width:320px;margin:0 auto 32px;display:flex;flex-direction:column;gap:0}
.progress-step{display:flex;align-items:center;gap:12px;padding:9px 0;position:relative}
.progress-step:not(:last-child)::after{content:'';position:absolute;left:15px;top:36px;width:2px;height:calc(100% - 10px);background:var(--mist)}
.ps-circle{width:30px;height:30px;border-radius:50%;border:2px solid var(--mist);background:var(--white);color:var(--slate);font-size:12px;font-weight:700;display:grid;place-items:center;flex-shrink:0;position:relative;z-index:1;font-family:"Fraunces",serif}
.ps-circle.done{background:var(--teal);border-color:var(--teal);color:#fff}
.ps-label{font-size:13px;font-weight:500;color:var(--slate)}
.ps-label.done{color:var(--ink);font-weight:600}
.success-divider{border:none;border-top:1px solid var(--mist);margin:0 0 24px}
.success-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-secondary{padding:11px 22px;border-radius:var(--r-lg);border:1.5px solid var(--mist);background:var(--white);font-size:13px;font-weight:600;color:var(--ink);cursor:pointer;transition:all .15s;font-family:"DM Sans",sans-serif;min-width:160px}
.btn-secondary:hover{border-color:var(--teal);color:var(--teal)}
.btn-primary-outline{padding:11px 22px;border-radius:var(--r-lg);border:1.5px solid var(--mist);background:var(--white);font-size:13px;font-weight:600;color:var(--ink);cursor:pointer;transition:all .15s;font-family:"DM Sans",sans-serif;min-width:160px}
.btn-primary-outline:hover{border-color:var(--teal);color:var(--teal)}
</style>
@endpush

@section('content')
<div class="wizard-container">
    <form
      id="apply-form"
      method="POST"
      action="{{ route('applications.store', $scholarship->id) }}"
      enctype="multipart/form-data"
      style="width: 100%; max-width: 900px;"
    >
    @csrf
    <input type="hidden" name="scholarship_id" value="{{ $scholarship->id }}">

    <div class="wizard" id="wizard">

      {{-- ── Stepper ── --}}
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

      {{-- ── Alert bar — Step 1 only ── --}}
      <div class="alerts-bar" id="alerts-bar">
        <p class="panel-hint top-hint">Check that you meet all eligibility criteria before proceeding with your scholarship application.</p>
        <div class="alert alert-blue">
          <span class="alert-star">★</span>
          <span>Scholarship: {{ $scholarship->name }}</span>
        </div>
        @php
          $pendingCount = collect($eligibility)->filter(fn($e) => $e['pass'] === null)->count();
        @endphp
        @if($pendingCount > 0)
        <div class="alert alert-gold">
          <span class="alert-dot"></span>
          <span>
            {{ $pendingCount }} eligibility item{{ $pendingCount > 1 ? 's' : '' }}
            need{{ $pendingCount === 1 ? 's' : '' }} attention — you may still proceed.
          </span>
        </div>
        @endif
      </div>

      <div class="panel active" id="panel-1">
        <div class="two-col">

          <div>
            <div class="sec-label">Requirements checklist</div>
            <div class="req-list">

              {{-- GPA --}}
              @php $gpa = $eligibility['gpa']; @endphp
              <div class="req-item">
                <div class="req-check {{ $gpa['pass'] ? 'ok' : 'warn' }}">{{ $gpa['pass'] ? '✓' : '' }}</div>
                <div>
                  GPA of {{ $scholarship->gpa_requirement }} or better
                  <span class="req-sub">
                    Your GWA: {{ number_format((float) $profile->gwa, 2) }} —
                    {{ $gpa['pass'] ? 'qualifies' : 'does not qualify' }}
                  </span>
                </div>
              </div>

              {{-- Income --}}
              @php $inc = $eligibility['income']; @endphp
              <div class="req-item">
                <div class="req-check {{ $inc['pass'] === true ? 'ok' : 'warn' }}">{{ $inc['pass'] === true ? '✓' : '' }}</div>
                <div>
                  Annual family income
                  <span class="req-sub">Limit: {{ $scholarship->income_bracket }} — income not yet verified</span>
                </div>
              </div>

              {{-- No concurrent scholarship --}}
              @php $conc = $eligibility['concurrent']; @endphp
              <div class="req-item">
                <div class="req-check {{ $conc['pass'] ? 'ok' : 'warn' }}">{{ $conc['pass'] ? '✓' : '' }}</div>
                <div>No active scholarship grant</div>
              </div>

              {{-- Enrollment --}}
              @php $enr = $eligibility['enrollment']; @endphp
              <div class="req-item">
                <div class="req-check {{ $enr['pass'] ? 'ok' : 'warn' }}">{{ $enr['pass'] ? '✓' : '' }}</div>
                <div>Currently enrolled (college level)</div>
              </div>

              {{-- Endorsement letter — always shows as pending until Step 2 --}}
              <div class="req-item">
                <div class="req-check warn"></div>
                <div>
                  Endorsement letter from school
                  <span class="req-sub">Must be uploaded in Step 2</span>
                </div>
              </div>

            </div>
          </div>

          {{-- Eligibility results — loops over $eligibility from controller --}}
          <div>
            <div class="sec-label">Eligibility check results</div>
            <div class="elig-table">
              @foreach($eligibility as $item)
              <div class="elig-row">
                <span class="elig-key">{{ $item['label'] }}</span>
                <span class="badge {{ $item['badgeClass'] }}">{{ $item['badge'] }}</span>
              </div>
              @endforeach
            </div>
          </div>

        </div>
        <div class="footer-nav">
          <span class="step-counter">Step 1 of 3</span>
          <button type="button" class="btn-next" onclick="goTo(2)">Next </button>
        </div>
      </div>

      <div class="panel" id="panel-2">
        <p class="panel-hint">Pick from your saved documents or upload new files. All items are required unless marked optional.</p>

        <div class="doc-cols">
          @foreach($documentGroups as $group)
          <div class="doc-group">
            <div class="doc-group-title">{{ $group['groupTitle'] }}</div>

            @foreach($group['slots'] as $slot)
            @php
              $matches     = $savedDocuments->where('document_type', $slot['document_type']);
              $slug        = Str::slug($slot['document_type']);
              $preSelected = $matches->firstWhere('status', 'verified');
            @endphp

            <div class="doc-subcard">
              <div class="doc-card-label">
                {{ $slot['label'] }}
                @if(!empty($slot['smallNote']))<small>({{ $slot['smallNote'] }})</small>@endif
                @if($slot['optional'])<small style="color:var(--slate);font-weight:400">Optional</small>@endif
              </div>

              {{-- Saved documents from the vault --}}
              @foreach($matches as $doc)
              @php
                $ext   = strtoupper(pathinfo($doc->file_url, PATHINFO_EXTENSION));
                $isImg = in_array($ext, ['PNG','JPG','JPEG']);
                $isPre = $preSelected && $preSelected->id === $doc->id;
                $statusColor = match($doc->status) {
                  'verified' => 'var(--green)',
                  'pending'  => '#92700a',
                  default    => 'var(--red)',
                };
              @endphp
              <div
                class="doc-file"
                onclick="selectDoc('{{ $slug }}', {{ $doc->id }}, '{{ basename($doc->file_url) }}', this)"
                data-doc-id="{{ $doc->id }}"
              >
                <div class="file-icon {{ $isImg ? 'img' : '' }}">{{ substr($ext,0,3) }}</div>
                <div class="file-meta">
                  <div class="file-name">{{ basename($doc->file_url) }}</div>
                  <div class="file-size" style="color:{{ $statusColor }}">{{ ucfirst($doc->status) }}</div>
                </div>
                <div class="radio {{ $isPre ? 'sel' : '' }}" id="radio-{{ $doc->id }}"></div>
                {{-- Real radio — carries doc ID in POST --}}
                <input
                  type="radio"
                  name="documents[{{ $slug }}]"
                  value="{{ $doc->id }}"
                  style="display:none"
                  id="doc-radio-{{ $doc->id }}"
                  {{ $isPre ? 'checked' : '' }}
                >
              </div>
              @endforeach

              {{-- Upload new file — real file input overlaid on the button --}}
              <div class="upload-trigger" id="upload-trigger-{{ $slug }}">
                <div class="upload-icon-box">↑</div>
                <div class="upload-text" id="upload-text-{{ $slug }}">
                  <strong>Upload new file</strong><br>PDF, JPG, PNG up to 5MB
                </div>
                <input
                  type="file"
                  name="uploads[{{ $slug }}]"
                  accept=".pdf,.jpg,.jpeg,.png"
                  class="real-file-input"
                  data-slug="{{ $slug }}"
                  onchange="handleUpload(this)"
                >
              </div>
            </div>
            @endforeach

          </div>
          @endforeach
        </div>

        {{-- Endorsement letter — separate row below the 3-col grid --}}
        @php $endorseSlug = Str::slug($endorsementSlot['document_type']); @endphp
        <div class="doc-endorsement">
          <div class="doc-group-title">{{ $endorsementSlot['groupTitle'] }}</div>
          <div class="doc-subcard">
            <div class="doc-card-label">{{ $endorsementSlot['label'] }}</div>
            <div class="upload-trigger" id="upload-trigger-{{ $endorseSlug }}">
              <div class="upload-icon-box">↑</div>
              <div class="upload-text" id="upload-text-{{ $endorseSlug }}">
                <strong>Upload new file</strong><br>PDF, JPG, PNG up to 5MB
              </div>
              <input
                type="file"
                name="uploads[{{ $endorseSlug }}]"
                accept=".pdf,.jpg,.jpeg,.png"
                class="real-file-input"
                data-slug="{{ $endorseSlug }}"
                onchange="handleUpload(this)"
              >
            </div>
          </div>
        </div>

        <div class="footer-nav">
          <button type="button" class="btn-back" onclick="goTo(1)">Back</button>
          <div style="display:flex;align-items:center;gap:14px">
            <span class="step-counter">Step 2 of 3</span>
            <button type="button" class="btn-next" onclick="goTo(3)">Next </button>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-3">
        <p class="panel-hint">Review your application carefully. You will not be able to make changes after submission.</p>

        {{-- Scholarship & applicant details --}}
        <div class="confirm-block">
          <div class="confirm-heading">Scholarship details</div>
          <div class="confirm-table">
            <div class="confirm-row"><span class="ck">Scholarship</span><span class="cv">{{ $scholarship->name }}</span></div>
            <div class="confirm-row"><span class="ck">Provider</span><span class="cv">{{ $scholarship->provider_name }}</span></div>
            <div class="confirm-row"><span class="ck">Applicant</span><span class="cv">{{ $applicant->first_name }} {{ $applicant->last_name }}</span></div>
            <div class="confirm-row"><span class="ck">Course &amp; Year</span><span class="cv">{{ $profile->course_program }}, {{ $profile->year_level }}</span></div>
            <div class="confirm-row"><span class="ck">School</span><span class="cv">{{ $profile->university_name }}</span></div>
            <div class="confirm-row"><span class="ck">Academic year</span><span class="cv">{{ $profile->academic_year }} — {{ $profile->semester }} Semester</span></div>
            <div class="confirm-row"><span class="ck">Student number</span><span class="cv">{{ $profile->student_number }}</span></div>
            <div class="confirm-row">
              <span class="ck">GWA ({{ $profile->gwa_scale }} scale)</span>
              <span class="cv ok">{{ number_format((float) $profile->gwa, 2) }} — qualifies</span>
            </div>
            <div class="confirm-row">
              <span class="ck">Monthly household income</span>
              <span class="cv">
                ₱{{ number_format((float) $profile->monthly_household_income) }}
                (₱{{ number_format(((float) $profile->monthly_household_income) * 12) }}/yr)
              </span>
            </div>
            <div class="confirm-row"><span class="ck">Household dependents</span><span class="cv">{{ $profile->num_dependents }}</span></div>
            <div class="confirm-row"><span class="ck">Breadwinner status</span><span class="cv">{{ $profile->is_breadwinner }}</span></div>
            <div class="confirm-row">
              <span class="ck">4Ps beneficiary</span>
              <span class="cv">{{ $profile->is_4ps ? 'Yes' : 'No' }}</span>
            </div>
          </div>
        </div>

        {{-- Eligibility results --}}
        <div class="confirm-block">
          <div class="confirm-heading">Eligibility results</div>
          <div class="confirm-table">
            @foreach($eligibility as $item)
            @php $cls = $item['pass'] === true ? 'ok' : ($item['pass'] === null ? 'pending' : 'none'); @endphp
            <div class="confirm-row">
              <span class="ck">{{ $item['label'] }}</span>
              <span class="cv {{ $cls }}">{{ $item['badge'] }}</span>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Documents submitted — rows start with Blade-seeded values,
             then JS updates them as the user changes selection in Step 2 --}}
        <div class="confirm-block">
          <div class="confirm-heading">Documents submitted</div>
          <div class="confirm-table">
            @foreach($documentGroups as $group)
              @foreach($group['slots'] as $slot)
              @php
                $slug     = Str::slug($slot['document_type']);
                $preMatch = $savedDocuments->where('document_type', $slot['document_type'])->firstWhere('status', 'verified');
              @endphp
              <div class="confirm-row">
                <span class="ck">{{ $slot['label'] }}</span>
                <span class="cv {{ $preMatch ? '' : 'none' }}" id="confirm-val-{{ $slug }}">
                  {{ $preMatch ? basename($preMatch->file_url) : 'Not selected' }}
                </span>
              </div>
              @endforeach
            @endforeach

            {{-- Endorsement letter row --}}
            <div class="confirm-row">
              <span class="ck">{{ $endorsementSlot['label'] }}</span>
              <span class="cv pending" id="confirm-val-{{ $endorseSlug }}">Pending upload</span>
            </div>
          </div>
        </div>

        {{-- Certify checkbox — must be checked before POST is allowed --}}
        <label class="certify-box" id="certify-label">
          <input type="checkbox" name="certified" id="certify" value="1"
                 onchange="this.closest('.certify-box').classList.remove('error')"/>
          I certify that all information and documents submitted are true, correct, and complete.
          I understand that any misrepresentation may result in disqualification and legal action under applicable laws.
        </label>

        <div class="footer-nav">
          <button type="button" class="btn-back" onclick="goTo(2)">Back</button>
          <div style="display:flex;align-items:center;gap:14px">
            <span class="step-counter">Step 3 of 3</span>
            <button type="submit" class="btn-submit" onclick="return guardSubmit()">Submit Application</button>
          </div>
        </div>
      </div>

      @if(session('application_submitted'))
      @php $flash = session('application_submitted'); @endphp
      <div class="success-panel active" id="panel-success">
        <div class="success-check">
          <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 14.5l5.5 5.5 10.5-11" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="success-title">Application Submitted!</div>
        <div class="success-note">
          Your application for <strong>{{ $flash['scholarship_name'] }}</strong> has been received. You'll get updates via email and SMS.
        </div>
        <div class="appid-label">Application ID</div>
        <div class="appid-badge">{{ $flash['reference_code'] }}</div><br>
        <div class="review-pill">
          <span></span>
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
          <button type="button" class="btn-secondary"
            onclick="window.location='{{ route('scholarships.index') }}'">
            Browse more scholarships
          </button>
          <button type="button" class="btn-primary-outline"
            onclick="window.location='{{ route('applications.show', $flash['application_id']) }}'">
            Track my application
          </button>
        </div>
      </div>
      @endif

    </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
/* ─────────────────────────────────────────────────────────────────
   PAGE INIT
   If success panel is present (session flash after POST),
   hide the form steps and stepper.
   ───────────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
  if (document.getElementById('panel-success')) {
    document.getElementById('wizard').classList.add('is-success');
    [1,2,3].forEach(i => {
      const p = document.getElementById('panel-' + i);
      if (p) p.classList.remove('active');
    });
    document.getElementById('alerts-bar').classList.add('hidden');
  }
});

function selectDoc(slug, docId, fileName, rowEl) {
  // Update visual radio rings inside this subcard only
  rowEl.closest('.doc-subcard').querySelectorAll('.radio').forEach(r => r.classList.remove('sel'));
  rowEl.querySelector('.radio').classList.add('sel');
  // Check the real hidden radio input
  const radio = document.getElementById('doc-radio-' + docId);
  if (radio) radio.checked = true;
  // Sync confirm summary row
  updateConfirm(slug, fileName);
}

function handleUpload(input) {
  if (!input.files || !input.files[0]) return;
  const slug     = input.dataset.slug;
  const fileName = input.files[0].name;
  // Update upload button label
  const textEl = document.getElementById('upload-text-' + slug);
  if (textEl) {
    textEl.innerHTML = '<strong>' + fileName + '</strong><br>'
      + '<span style="color:var(--green)">Ready to upload</span>';
  }
  // Deselect any saved-doc radio for this slot
  input.closest('.doc-subcard')?.querySelectorAll('.radio')
    .forEach(r => r.classList.remove('sel'));
  input.closest('.doc-subcard')?.querySelectorAll('input[type="radio"]')
    .forEach(r => r.checked = false);
  // Sync confirm summary row
  updateConfirm(slug, fileName);
}

function updateConfirm(slug, fileName) {
  const el = document.getElementById('confirm-val-' + slug);
  if (!el) return;
  el.textContent = fileName || 'Not selected';
  el.className   = 'cv ' + (fileName ? '' : 'none');
}

function goTo(step) {
  [1, 2, 3].forEach(i => {
    document.getElementById('panel-' + i).classList.toggle('active', i === step);
    const n = document.getElementById('sn' + i);
    n.classList.remove('active', 'done');
    if (i === step) n.classList.add('active');
    if (i < step)  n.classList.add('done');
  });
  document.getElementById('alerts-bar').classList.toggle('hidden', step !== 1);
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function guardSubmit() {
  const cb  = document.getElementById('certify');
  const lbl = document.getElementById('certify-label');
  if (!cb.checked) {
    lbl.classList.add('error');
    lbl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return false;
  }
  return true;
}
</script>
@endpush

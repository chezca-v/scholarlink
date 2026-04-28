@extends('layouts.admin')

@section('page_title', 'Settings')
@section('topnav_title', 'Settings')
@section('topnav_subtitle', '/admin/settings · ' . (auth()->user()->organization->name ?? 'CHED NCR'))

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Admin</span><span class="sep">/</span><span class="current">Settings</span>
  </div>

  <div class="grid-2" style="gap: 24px; align-items: start;">

    <!-- LEFT COLUMN -->
    <div style="display: flex; flex-direction: column; gap: 16px;">

      <!-- ORG PROFILE -->
      <div class="card">
        <div class="section-header">
          <div class="section-title">🏛️ Organization Profile</div>
          <button type="submit" form="orgProfileForm" class="btn btn-primary btn-sm">Save Changes</button>
        </div>

        <div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--page-bg);border-radius:12px;border:1.5px solid var(--border);margin-bottom:16px;">
          <div style="width:56px;height:56px;border-radius:14px;background:rgba(15,76,92,.1);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
            {{ $organization->emoji ?? '🏛️' }}
          </div>
          <div style="flex: 1;">
            <div style="font-weight:700;font-size:15px;">{{ $organization->name }}</div>
            <div style="font-size:12px;color:var(--slate);">{{ $organization->description }}</div>
          </div>
          <button type="button" class="btn btn-outline btn-sm">Change Logo</button>
        </div>

        <form method="POST" action="{{ route('admin.settings.update-profile') }}" id="orgProfileForm">
          @csrf @method('PATCH')
          <div class="form-group">
            <label class="form-label">Organization Name</label>
            <input class="form-input" name="name" value="{{ old('name', $organization->name) }}">
          </div>
          <div class="form-group">
            <label class="form-label">Full Name / Description</label>
            <input class="form-input" name="description" value="{{ old('description', $organization->description) }}">
          </div>
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">Contact Email</label>
              <input class="form-input" type="email" name="contact_email"
                     value="{{ old('contact_email', $organization->contact_email) }}">
            </div>
            <div class="form-group">
              <label class="form-label">Website</label>
              <input class="form-input" name="website"
                     value="{{ old('website', $organization->website) }}">
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Address</label>
            <input class="form-input" name="address"
                   value="{{ old('address', $organization->address) }}">
          </div>
        </form>
      </div>

      <!-- BLIND SCREENING DEFAULTS -->
      <div class="card">
        <div class="section-header" style="margin-bottom: 4px;">
          <div class="section-title">🔒 Blind Screening Defaults</div>
          <button type="submit" form="blindScreeningForm" class="btn btn-outline btn-sm">Save</button>
        </div>
        <p style="font-size:12px;color:var(--slate);margin-bottom:14px;">
          Controls what evaluators can see by default across all scholarships in this org.
        </p>
        <form method="POST" action="{{ route('admin.settings.update-blind-screening') }}"
              id="blindScreeningForm">
          @csrf @method('PATCH')
          @foreach($blindScreeningOptions as $key => $option)
          <div class="toggle-row">
            <div>
              <div class="toggle-label">{{ $option['label'] }}</div>
              <div class="toggle-desc">{{ $option['description'] }}</div>
            </div>
            <label class="toggle">
              <input type="checkbox" name="blind_screening[{{ $key }}]"
                     {{ $option['enabled'] ? 'checked' : '' }}
                     onchange="autoSaveBlindScreening('{{ $key }}', this.checked)">
              <span class="toggle-track"></span>
            </label>
          </div>
          @endforeach
        </form>
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div style="display: flex; flex-direction: column; gap: 16px;">

      <!-- NOTIFICATION TEMPLATES -->
      <div class="card">
        <div class="section-header">
          <div class="section-title">📨 Notification Templates</div>
          <button type="submit" form="notifTemplateForm" class="btn btn-primary btn-sm">Save All</button>
        </div>
        <p style="font-size:12px;color:var(--slate);margin-bottom:14px;">
          Variables:
          <code style="background:var(--page-bg);padding:1px 5px;border-radius:4px;font-size:11px;">&#123;&#123;name&#125;&#125;</code>
          <code style="background:var(--page-bg);padding:1px 5px;border-radius:4px;font-size:11px;">&#123;&#123;scholarship&#125;&#125;</code>
          <code style="background:var(--page-bg);padding:1px 5px;border-radius:4px;font-size:11px;">&#123;&#123;status&#125;&#125;</code>
          <code style="background:var(--page-bg);padding:1px 5px;border-radius:4px;font-size:11px;">&#123;&#123;deadline&#125;&#125;</code>
        </p>

        <!-- Template type tabs -->
        <div style="display: flex; gap: 4px; margin-bottom: 14px; flex-wrap: wrap;">
          @foreach($notificationTemplates as $key => $template)
          <button class="badge {{ $loop->first ? 'teal' : 'gray' }}"
                  style="padding:4px 12px;cursor:pointer;font-size:11px;border:none;"
                  onclick="switchTemplate('{{ $key }}', this)">
            {{ $template['tab_label'] }}
          </button>
          @endforeach
        </div>

        <form method="POST" action="{{ route('admin.settings.update-templates') }}"
              id="notifTemplateForm">
          @csrf @method('PATCH')
          @foreach($notificationTemplates as $key => $template)
          <div class="template-panel" id="template-{{ $key }}"
               style="{{ !$loop->first ? 'display:none;' : '' }}">
            <div class="form-group">
              <label class="form-label">Email Subject</label>
              <input class="form-input" name="templates[{{ $key }}][subject]"
                     value="{{ old("templates.$key.subject", $template['subject']) }}">
            </div>
            <div class="form-group">
              <label class="form-label">Email Body</label>
              <textarea class="form-textarea" name="templates[{{ $key }}][email_body]"
                        rows="4">{{ old("templates.$key.email_body", $template['email_body']) }}</textarea>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
              <label class="form-label">
                SMS Body
                <span style="font-weight:400;color:var(--muted);">(max 160 chars)</span>
              </label>
              <textarea class="form-textarea" name="templates[{{ $key }}][sms_body]"
                        rows="2" style="min-height:60px;"
                        maxlength="160"
                        oninput="updateSmsCount(this)">{{ old("templates.$key.sms_body", $template['sms_body']) }}</textarea>
              <div style="text-align:right;font-size:11px;color:var(--muted);margin-top:4px;"
                   id="sms-count-{{ $key }}">
                {{ strlen($template['sms_body']) }} / 160
              </div>
            </div>
          </div>
          @endforeach
        </form>
      </div>

      <!-- SCORING WEIGHT DEFAULTS -->
      <div class="card">
        <div class="section-header">
          <div class="section-title">⚖️ Scoring Weight Defaults</div>
          <button type="button" class="btn btn-outline btn-sm" onclick="resetWeights()">
            Reset to Default
          </button>
        </div>
        <p style="font-size:12px;color:var(--slate);margin-bottom:4px;">
          Set default weights for this org's scholarships. Weights must total 100%.
        </p>

        <!-- Sum indicator -->
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:var(--green-bg);border-radius:10px;margin-bottom:14px;border:1px solid #A7F3D0;"
             id="weightSumRow">
          <span style="font-size:12px;font-weight:600;color:var(--green);" id="weightSumLabel">✓ Total weight</span>
          <span style="font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--green);"
                id="weightSum">100%</span>
        </div>

        <form method="POST" action="{{ route('admin.settings.update-weights') }}" id="weightsForm">
          @csrf @method('PATCH')
          @foreach($scoringWeights as $key => $weight)
          <div class="weight-row">
            <div class="weight-label">
              {{ $weight['label'] }}
              <small>{{ $weight['description'] }}</small>
            </div>
            <input type="range" min="0" max="100"
                   value="{{ old("weights.$key", $weight['value']) }}"
                   name="weights[{{ $key }}]"
                   id="w-{{ $key }}"
                   oninput="updateWeights()">
            <div class="weight-val" id="wv-{{ $key }}">{{ $weight['value'] }}%</div>
          </div>
          @endforeach

          <button type="submit" class="btn btn-primary btn-lg"
                  style="width:100%;margin-top:14px;justify-content:center;">
            💾 Save Weight Defaults
          </button>
        </form>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
<script>
  const weightKeys = @json(array_keys($scoringWeights));
  const defaultWeights = @json(array_column($scoringWeights, 'value', null));

  function updateWeights() {
    let total = 0;
    weightKeys.forEach(key => {
      const val = parseInt(document.getElementById('w-' + key).value);
      document.getElementById('wv-' + key).textContent = val + '%';
      total += val;
    });
    const row = document.getElementById('weightSumRow');
    const sumEl = document.getElementById('weightSum');
    const labelEl = document.getElementById('weightSumLabel');
    sumEl.textContent = total + '%';
    if (total === 100) {
      row.style.background = 'var(--green-bg)'; row.style.borderColor = '#A7F3D0';
      sumEl.style.color = 'var(--green)'; labelEl.style.color = 'var(--green)';
      labelEl.textContent = '✓ Total weight';
    } else {
      row.style.background = 'var(--red-bg)'; row.style.borderColor = '#FECACA';
      sumEl.style.color = 'var(--red)'; labelEl.style.color = 'var(--red)';
      labelEl.textContent = '⚠ Must equal 100%';
    }
  }

  function resetWeights() {
    if (!confirm('Reset scoring weights to system defaults?')) return;
    weightKeys.forEach((key, i) => {
      document.getElementById('w-' + key).value = Object.values(defaultWeights)[i] || 33;
    });
    updateWeights();
  }

  function switchTemplate(key, btn) {
    document.querySelectorAll('.template-panel').forEach(p => p.style.display = 'none');
    document.getElementById('template-' + key).style.display = 'block';
    document.querySelectorAll('[onclick^="switchTemplate"]').forEach(b => {
      b.className = 'badge gray';
      b.style.cssText = 'padding:4px 12px;cursor:pointer;font-size:11px;border:none;';
    });
    btn.className = 'badge teal';
    btn.style.cssText = 'padding:4px 12px;cursor:pointer;font-size:11px;border:none;';
  }

  function updateSmsCount(textarea) {
    const key = textarea.name.match(/templates\[(\w+)\]/)?.[1];
    if (key) {
      document.getElementById('sms-count-' + key).textContent = textarea.value.length + ' / 160';
    }
  }

  function autoSaveBlindScreening(key, enabled) {
    fetch('{{ route("admin.settings.toggle-blind-screening") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ key, enabled })
    });
  }
</script>
@endpush

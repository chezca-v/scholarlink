@extends('layouts.superadmin')

@section('page_title', 'System Settings')
@section('topnav_title', 'System Settings')
@section('topnav_subtitle', '/superadmin/settings')

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Home</span><span class="sep">/</span><span class="current">Settings</span>
  </div>

  <!-- SETTINGS TABS -->
  <div style="display: flex; gap: 6px; margin-bottom: 20px; flex-wrap: wrap;">
    <button class="badge teal" style="padding: 6px 16px; cursor: pointer; font-size: 12px; border: none;"
            onclick="showTab('feature-flags', this)">Feature Flags</button>
    <button class="badge gray" style="padding: 6px 16px; cursor: pointer; font-size: 12px; border: none;"
            onclick="showTab('notif-templates', this)">Notification Templates</button>
    <button class="badge gray" style="padding: 6px 16px; cursor: pointer; font-size: 12px; border: none;"
            onclick="showTab('permissions', this)">Permissions Matrix</button>
    <button class="badge gray" style="padding: 6px 16px; cursor: pointer; font-size: 12px; border: none;"
            onclick="showTab('integrations', this)">Integrations</button>
  </div>

  <div class="grid-2" style="align-items: start; gap: 20px;">

    <!-- LEFT COLUMN -->
    <div>
      <!-- FEATURE FLAGS -->
      <div class="card" id="tab-feature-flags" style="margin-bottom: 16px;">
        <div class="section-header" style="margin-bottom: 4px;">
          <div class="section-title">🏁 Feature Flags</div>
          <form method="POST" action="{{ route('superadmin.settings.update') }}" id="featureFlagsForm">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline btn-sm">Save Changes</button>
          </form>
        </div>
        <p style="font-size: 12px; color: var(--slate); margin-bottom: 16px;">
          Enable or disable platform features system-wide. Changes apply immediately.
        </p>

        @foreach($featureFlags as $key => $flag)
        <div class="toggle-row">
          <div class="toggle-info">
            <div class="toggle-label">{{ $flag['label'] }}</div>
            <div class="toggle-desc">{{ $flag['description'] }}</div>
          </div>
          <label class="toggle">
            <input type="checkbox" name="flags[{{ $key }}]"
                   form="featureFlagsForm"
                   {{ $flag['enabled'] ? 'checked' : '' }}
                   onchange="autoSaveFlag('{{ $key }}', this.checked)">
            <span class="toggle-track"></span>
          </label>
        </div>
        @endforeach
      </div>

      <!-- INTEGRATION STATUS -->
      <div class="card compact" id="tab-integrations">
        <div class="section-title" style="margin-bottom: 12px;">🔌 Integration Status</div>

        @foreach($integrations as $integration)
        <div class="toggle-row" style="padding: 8px 0;">
          <div class="toggle-info">
            <div class="toggle-label">{{ $integration['name'] }}</div>
            <div class="toggle-desc">{{ $integration['description'] }}</div>
          </div>
          @if($integration['status'] === 'connected')
            <span class="badge green">Connected</span>
          @elseif($integration['status'] === 'online')
            <span class="badge green">Online</span>
          @elseif($integration['status'] === 'active')
            <span class="badge green">Active</span>
          @elseif($integration['status'] === 'error')
            <span class="badge red">Error</span>
          @else
            <span class="badge gray">{{ ucfirst($integration['status']) }}</span>
          @endif
        </div>
        @endforeach
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div>
      <!-- NOTIFICATION TEMPLATES -->
      <div class="card" id="tab-notif-templates" style="margin-bottom: 16px;">
        <div class="section-header">
          <div class="section-title">📨 Notification Templates</div>
          <button type="submit" form="notifTemplatesForm" class="btn btn-primary btn-sm">Save All</button>
        </div>
        <p style="font-size: 12px; color: var(--slate); margin-bottom: 16px;">
          Use
          <code style="background: var(--page-bg); padding: 1px 5px; border-radius: 4px; font-size: 11px;">&#123;&#123;name&#125;&#125;</code>,
          <code style="background: var(--page-bg); padding: 1px 5px; border-radius: 4px; font-size: 11px;">&#123;&#123;scholarship&#125;&#125;</code>,
          <code style="background: var(--page-bg); padding: 1px 5px; border-radius: 4px; font-size: 11px;">&#123;&#123;status&#125;&#125;</code>
          variables.
        </p>

        <form method="POST" action="{{ route('superadmin.settings.update') }}"
              id="notifTemplatesForm">
          @csrf
          @method('PATCH')

          @foreach($notificationTemplates as $key => $template)
          <div class="form-group">
            <label class="form-label">{{ $template['label'] }}</label>
            <textarea class="form-textarea"
                      name="templates[{{ $key }}]"
                      rows="{{ $template['rows'] ?? 3 }}">{{ old("templates.$key", $template['content']) }}</textarea>
          </div>
          @endforeach

          <div style="display: flex; gap: 8px; margin-top: 4px;">
            <button type="submit" class="btn btn-primary">💾 Save Templates</button>
            <button type="button" class="btn btn-outline"
                    onclick="resetTemplates()">Reset to Default</button>
          </div>
        </form>
      </div>

      <!-- PERMISSIONS MATRIX -->
      <div class="card" id="tab-permissions">
        <div class="section-header" style="margin-bottom: 12px;">
          <div class="section-title">🔐 RBAC Permissions Matrix</div>
          <form method="POST" action="{{ route('superadmin.settings.update') }}"
                id="permissionsForm">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
          </form>
        </div>
        <p style="font-size: 12px; color: var(--slate); margin-bottom: 16px;">
          Control what each role can do across the platform. Click to toggle permissions.
        </p>

        @foreach($permissionsMatrix as $role => $roleData)
        <div class="perm-row">
          <div class="perm-role-label">{{ $roleData['icon'] }} {{ $roleData['label'] }}</div>
          <div class="perm-actions">
            @foreach($roleData['permissions'] as $permKey => $perm)
              @if($role === 'superadmin')
                <span class="perm-action-tag on">{{ $perm['label'] }}</span>
              @else
                <span class="perm-action-tag {{ $perm['enabled'] ? 'on' : 'off' }}"
                      onclick="togglePerm(this, '{{ $role }}', '{{ $permKey }}')"
                      data-role="{{ $role }}"
                      data-perm="{{ $permKey }}">
                  {{ $perm['label'] }}
                </span>
              @endif
            @endforeach
          </div>
        </div>
        @endforeach

        <div style="margin-top: 16px; padding: 12px; background: var(--accent-pale); border-radius: 10px; border: 1px solid rgba(232,168,56,.3);">
          <div style="font-size: 12px; font-weight: 600; color: #92650a; margin-bottom: 4px;">⚠️ Caution</div>
          <div style="font-size: 11px; color: #92650a;">
            Changes to permissions take effect immediately for all active sessions.
            Superadmin permissions cannot be restricted here.
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@push('styles')
<style>
  .tab-section { display: block; }
  .tab-section.hidden { display: none; }
</style>
@endpush

@push('scripts')
<script>
  function showTab(tab, btn) {
    // Update button styles
    document.querySelectorAll('[onclick^="showTab"]').forEach(b => {
      b.className = 'badge gray';
      b.style.cssText = 'padding: 6px 16px; cursor: pointer; font-size: 12px; border: none;';
    });
    btn.className = 'badge teal';
    btn.style.cssText = 'padding: 6px 16px; cursor: pointer; font-size: 12px; border: none;';
  }

  function togglePerm(el, role, perm) {
    const isOn = el.classList.contains('on');
    el.classList.toggle('on', !isOn);
    el.classList.toggle('off', isOn);

    // Send AJAX update
    fetch('{{ route("superadmin.settings.update") }}', {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ role, permission: perm, enabled: !isOn })
    });
  }

  function autoSaveFlag(key, enabled) {
    fetch('{{ route("superadmin.settings.update") }}', {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ flag: key, enabled })
    });
  }

  function resetTemplates() {
    if (confirm('Reset all notification templates to default? This cannot be undone.')) {
      fetch('{{ route("superadmin.settings.update") }}', {
        method: 'PATCH',
        headers: { 
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
        },
        body: JSON.stringify({ action: 'reset_templates' })
      }).then(() => location.reload());
    }
  }
</script>
@endpush

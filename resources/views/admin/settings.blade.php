@extends('admin.layouts.admin')

@section('page_title', $pageTitle)
@section('topnav_title', $topnavTitle)
@section('topnav_subtitle', $topnavSubtitle)

@section('content')
<div class="page-header mb-6">
    <div class="flex items-center gap-3 mb-1">
        @foreach($breadcrumbs as $crumb)
            @if(isset($crumb['url']))
                <a href="{{ $crumb['url'] }}" style="color:var(--slate);font-size:13px;display:flex;align-items:center;gap:4px;">{{ $crumb['label'] }}</a>
                <span style="color:var(--muted);">/</span>
            @else
                <span style="font-size:13px;color:var(--ink);">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    </div>
    <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">{{ $pageTitle }}</h1>
    <p style="color:var(--slate);font-size:14px;margin-top:4px;">Configure system parameters, organization details, and notifications.</p>
</div>

<div class="page-content" x-data="{ activeTab: 'org' }">
    <div class="grid grid-cols-4 gap-6">
        
        {{-- SIDEBAR NAV --}}
        <div class="col-span-1">
            <div class="card p-3 flex flex-col gap-1">
                <button @click="activeTab = 'org'" :class="activeTab === 'org' ? 'bg-[rgba(15,76,92,0.06)] text-[var(--primary)] font-semibold' : 'text-[var(--slate)] hover:bg-gray-50'" class="text-left px-4 py-3 rounded-xl text-sm transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Organization Profile
                </button>
                <button @click="activeTab = 'screening'" :class="activeTab === 'screening' ? 'bg-[rgba(15,76,92,0.06)] text-[var(--primary)] font-semibold' : 'text-[var(--slate)] hover:bg-gray-50'" class="text-left px-4 py-3 rounded-xl text-sm transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Screening & Weights
                </button>
                <button @click="activeTab = 'notifs'" :class="activeTab === 'notifs' ? 'bg-[rgba(15,76,92,0.06)] text-[var(--primary)] font-semibold' : 'text-[var(--slate)] hover:bg-gray-50'" class="text-left px-4 py-3 rounded-xl text-sm transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    Notifications
                </button>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="col-span-3 space-y-6">
            
            {{-- ORG PROFILE --}}
            <div x-show="activeTab === 'org'" class="card p-6" style="display:none;" x-transition>
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[var(--border)]">
                    <div>
                        <h2 class="font-display font-bold text-lg" style="color:var(--ink);">{{ $labels['org_profile'] }}</h2>
                        <p class="text-sm text-[var(--slate)] mt-1">Manage your public organization details</p>
                    </div>
                    <button type="submit" form="orgProfileForm" class="btn-primary">
                        {{ $labels['save_changes'] }}
                    </button>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl mb-6" style="background:var(--page-bg); border: 1px solid var(--border);">
                    <div class="w-12 h-12 rounded-lg bg-white shadow flex items-center justify-center text-2xl border border-[var(--border)]">
                        {{ $organization->emoji }}
                    </div>
                    <div>
                        <p class="font-bold text-[var(--ink)]">{{ $organization->name }}</p>
                        <p class="text-sm text-[var(--slate)]">{{ Str::limit($organization->description, 60) }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route($routes['update_profile']) }}" id="orgProfileForm" class="space-y-4">
                    @csrf @method('PATCH')
                    @foreach($orgFields as $field)
                    <div>
                        <label class="input-label">{{ $field['label'] }}</label>
                        @if(($field['type'] ?? 'text') === 'textarea')
                        <textarea class="input-field" name="{{ $field['name'] }}" rows="3">{{ old($field['name'], $organization->{$field['name']}) }}</textarea>
                        @else
                        <input class="input-field" name="{{ $field['name'] }}" type="{{ $field['type'] ?? 'text' }}" value="{{ old($field['name'], $organization->{$field['name']}) }}">
                        @endif
                    </div>
                    @endforeach
                </form>
            </div>

            {{-- SCREENING & WEIGHTS --}}
            <div x-show="activeTab === 'screening'" style="display:none;" x-transition class="space-y-6">
                
                {{-- BLIND SCREENING --}}
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[var(--border)]">
                        <div>
                            <h2 class="font-display font-bold text-lg" style="color:var(--ink);">{{ $labels['blind_screening'] }}</h2>
                            <p class="text-sm text-[var(--slate)] mt-1">Configure anonymity settings</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route($routes['blind_screening']) }}" id="blindScreeningForm" class="space-y-4">
                        @csrf @method('PATCH')
                        @foreach($blindScreeningOptions as $key => $option)
                        <div class="flex items-start justify-between gap-4 p-4 rounded-xl border border-[var(--border)] hover:bg-gray-50 transition-colors">
                            <div>
                                <p class="font-bold text-[var(--ink)] text-sm">{{ $option['label'] }}</p>
                                <p class="text-xs text-[var(--slate)] mt-1">{{ $option['description'] }}</p>
                            </div>
                            <label class="toggle mt-1">
                                <input type="checkbox" name="blind_screening[{{ $key }}]" {{ $option['enabled'] ? 'checked' : '' }} onchange="autoSaveBlind('{{ $key }}', this.checked)">
                                <span class="toggle-track"></span>
                                <span class="toggle-thumb"></span>
                            </label>
                        </div>
                        @endforeach
                    </form>
                </div>

                {{-- WEIGHTS --}}
                <div class="card p-6" x-data="weightSlidersSettings()">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-[var(--border)]">
                        <div>
                            <h2 class="font-display font-bold text-lg" style="color:var(--ink);">{{ $labels['weights'] }}</h2>
                            <p class="text-sm text-[var(--slate)] mt-1">Default scoring formula</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn-ghost" @click="resetWeights">Reset</button>
                            <button type="submit" form="weightsForm" class="btn-primary">{{ $labels['save_weights'] }}</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-4 p-3 rounded-lg" :class="totalWeight === 100 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                        <span class="text-sm font-semibold" :class="totalWeight === 100 ? 'text-green-700' : 'text-red-700'">Total Weight</span>
                        <span class="font-bold" :class="totalWeight === 100 ? 'text-green-700' : 'text-red-700'" x-text="totalWeight + '%'"></span>
                    </div>

                    <form method="POST" action="{{ route($routes['weights']) }}" id="weightsForm" class="space-y-6">
                        @csrf @method('PATCH')
                        @foreach($scoringWeights as $key => $w)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="text-sm font-bold text-[var(--ink)]">{{ $w['label'] }}</p>
                                    <p class="text-xs text-[var(--slate)]">{{ $w['description'] }}</p>
                                </div>
                                <span class="text-sm font-bold" style="color:var(--primary);" x-text="weights['{{ $key }}'] + '%'"></span>
                            </div>
                            <input type="range" name="weights[{{ $key }}]" min="0" max="100" step="5"
                                x-model.number="weights['{{ $key }}']"
                                class="w-full" style="accent-color:var(--primary);height:6px;cursor:pointer;">
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>

            {{-- NOTIFICATIONS --}}
            <div x-show="activeTab === 'notifs'" class="card p-6" style="display:none;" x-transition x-data="{ tplTab: '{{ array_key_first($notificationTemplates) }}' }">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-[var(--border)]">
                    <div>
                        <h2 class="font-display font-bold text-lg" style="color:var(--ink);">{{ $labels['notifications'] }}</h2>
                        <p class="text-sm text-[var(--slate)] mt-1">Configure automated messages</p>
                    </div>
                    <button type="submit" form="notifForm" class="btn-primary">
                        {{ $labels['save_all'] }}
                    </button>
                </div>

                <div class="flex gap-2 mb-6 border-b border-[var(--border)] pb-1">
                    @foreach($notificationTemplates as $key => $tpl)
                    <button @click="tplTab = '{{ $key }}'" :class="tplTab === '{{ $key }}' ? 'border-[var(--primary)] text-[var(--primary)] font-semibold' : 'border-transparent text-[var(--slate)] hover:text-[var(--ink)]'" class="px-4 py-2 text-sm border-b-2 transition-colors">
                        {{ $tpl['tab_label'] }}
                    </button>
                    @endforeach
                </div>

                <form method="POST" action="{{ route($routes['templates']) }}" id="notifForm">
                    @csrf @method('PATCH')
                    @foreach($notificationTemplates as $key => $tpl)
                    <div x-show="tplTab === '{{ $key }}'" class="space-y-4">
                        <div>
                            <label class="input-label">Email Subject</label>
                            <input class="input-field" name="templates[{{ $key }}][subject]" value="{{ $tpl['subject'] }}">
                        </div>
                        <div>
                            <label class="input-label">Email Body</label>
                            <textarea class="input-field" rows="5" name="templates[{{ $key }}][email_body]">{{ $tpl['email_body'] }}</textarea>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="input-label !mb-0">SMS Body</label>
                                <span class="text-xs text-[var(--slate)]" :id="'sms-count-' + '{{ $key }}'">{{ strlen($tpl['sms_body']) }}/160</span>
                            </div>
                            <textarea class="input-field" maxlength="160" rows="3" name="templates[{{ $key }}][sms_body]" @input="document.getElementById('sms-count-{{ $key }}').innerText = $event.target.value.length + '/160'">{{ $tpl['sms_body'] }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </form>
            </div>
            
        </div>
    </div>
</div>

@push('scripts')
<script>
function autoSaveBlind(key,val){
  fetch('{{ route($routes["toggle_blind"]) }}',{
    method:'POST',
    headers:{
      'Content-Type':'application/json',
      'X-CSRF-TOKEN':'{{ csrf_token() }}'
    },
    body:JSON.stringify({key,val})
  });
}

document.addEventListener('alpine:init', () => {
    Alpine.data('weightSlidersSettings', () => ({
        weights: {
            @foreach($scoringWeights as $key => $w)
            '{{ $key }}': {{ $w['value'] }},
            @endforeach
        },
        defaultWeights: @json($defaultWeights),
        get totalWeight() {
            return Object.values(this.weights).reduce((a, b) => a + Number(b), 0);
        },
        resetWeights() {
            this.weights = { ...this.defaultWeights };
        }
    }));
});
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('page_title', $pageTitle)
@section('topnav_title', $topnavTitle)
@section('topnav_subtitle', $topnavSubtitle)

@section('content')
<div class="breadcrumb">
  @foreach($breadcrumbs as $crumb)
    @if(isset($crumb['url']))
      <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
    @else
      <span class="current">{{ $crumb['label'] }}</span>
    @endif
    @if(!$loop->last)<span class="sep">/</span>@endif
  @endforeach
</div>

<div class="grid-2" style="gap:24px;align-items:start;">

  {{-- LEFT --}}
  <div style="display:flex;flex-direction:column;gap:16px;">

    {{-- ORGANIZATION --}}
    <div class="card">
      <div class="section-header">
        <div class="section-title">{{ $labels['org_profile'] }}</div>
        <button type="submit" form="orgProfileForm" class="btn btn-primary btn-sm">
          {{ $labels['save_changes'] }}
        </button>
      </div>

      <div class="org-preview">
        <div class="org-emoji">{{ $organization->emoji }}</div>
        <div>
          <div class="org-name">{{ $organization->name }}</div>
          <div class="org-desc">{{ $organization->description }}</div>
        </div>
      </div>

      <form method="POST" action="{{ route($routes['update_profile']) }}" id="orgProfileForm">
        @csrf @method('PATCH')

        @foreach($orgFields as $field)
        <div class="form-group">
          <label class="form-label">{{ $field['label'] }}</label>
          <input
            class="form-input"
            name="{{ $field['name'] }}"
            type="{{ $field['type'] ?? 'text' }}"
            value="{{ old($field['name'], $organization->{$field['name']}) }}"
          >
        </div>
        @endforeach
      </form>
    </div>

    {{-- BLIND SCREENING --}}
    <div class="card">
      <div class="section-header">
        <div class="section-title">{{ $labels['blind_screening'] }}</div>
        <button type="submit" form="blindScreeningForm" class="btn btn-outline btn-sm">
          {{ $labels['save'] }}
        </button>
      </div>

      <form method="POST" action="{{ route($routes['blind_screening']) }}" id="blindScreeningForm">
        @csrf @method('PATCH')

        @foreach($blindScreeningOptions as $key => $option)
        <div class="toggle-row">
          <div>
            <div class="toggle-label">{{ $option['label'] }}</div>
            <div class="toggle-desc">{{ $option['description'] }}</div>
          </div>

          <label class="toggle">
            <input type="checkbox"
                   name="blind_screening[{{ $key }}]"
                   {{ $option['enabled'] ? 'checked' : '' }}
                   onchange="autoSaveBlind('{{ $key }}', this.checked)">
            <span class="toggle-track"></span>
          </label>
        </div>
        @endforeach
      </form>
    </div>
  </div>

  {{-- RIGHT --}}
  <div style="display:flex;flex-direction:column;gap:16px;">

    {{-- NOTIFICATIONS --}}
    <div class="card">
      <div class="section-header">
        <div class="section-title">{{ $labels['notifications'] }}</div>
        <button type="submit" form="notifForm" class="btn btn-primary btn-sm">
          {{ $labels['save_all'] }}
        </button>
      </div>

      <div class="template-tabs">
        @foreach($notificationTemplates as $key => $tpl)
        <button onclick="switchTemplate('{{ $key }}', this)"
                class="tab-btn {{ $loop->first ? 'active' : '' }}">
          {{ $tpl['tab_label'] }}
        </button>
        @endforeach
      </div>

      <form method="POST" action="{{ route($routes['templates']) }}" id="notifForm">
        @csrf @method('PATCH')

        @foreach($notificationTemplates as $key => $tpl)
        <div class="template-panel" id="tpl-{{ $key }}"
             style="{{ !$loop->first ? 'display:none' : '' }}">

          <input class="form-input"
                 name="templates[{{ $key }}][subject]"
                 value="{{ $tpl['subject'] }}">

          <textarea class="form-textarea"
                    name="templates[{{ $key }}][email_body]">{{ $tpl['email_body'] }}</textarea>

          <textarea class="form-textarea"
                    maxlength="160"
                    name="templates[{{ $key }}][sms_body]"
                    oninput="countSMS(this)">
            {{ $tpl['sms_body'] }}
          </textarea>

          <div id="sms-{{ $key }}">{{ strlen($tpl['sms_body']) }}</div>

        </div>
        @endforeach
      </form>
    </div>

    {{-- WEIGHTS --}}
    <div class="card">
      <div class="section-header">
        <div class="section-title">{{ $labels['weights'] }}</div>
        <button type="button" class="btn btn-outline btn-sm" onclick="resetWeights()">
          {{ $labels['reset'] }}
        </button>
      </div>

      <div id="weightSumRow">
        <span id="weightLabel"></span>
        <span id="weightSum"></span>
      </div>

      <form method="POST" action="{{ route($routes['weights']) }}" id="weightsForm">
        @csrf @method('PATCH')

        @foreach($scoringWeights as $key => $w)
        <div class="weight-row">
          <div>
            {{ $w['label'] }}
            <small>{{ $w['description'] }}</small>
          </div>

          <input type="range"
                 name="weights[{{ $key }}]"
                 value="{{ $w['value'] }}"
                 id="w-{{ $key }}"
                 oninput="updateWeights()">

          <span id="val-{{ $key }}">{{ $w['value'] }}%</span>
        </div>
        @endforeach

        <button type="submit" class="btn btn-primary w-full">
          {{ $labels['save_weights'] }}
        </button>
      </form>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
const weightKeys = @json(array_keys($scoringWeights));
const defaultWeights = @json($defaultWeights);

function updateWeights(){
  let total = 0;
  weightKeys.forEach(k=>{
    let v = +document.getElementById('w-'+k).value;
    document.getElementById('val-'+k).innerText = v+'%';
    total += v;
  });
  document.getElementById('weightSum').innerText = total+'%';
}

function resetWeights(){
  weightKeys.forEach((k,i)=>{
    document.getElementById('w-'+k).value = defaultWeights[k];
  });
  updateWeights();
}

function switchTemplate(key,btn){
  document.querySelectorAll('.template-panel').forEach(p=>p.style.display='none');
  document.getElementById('tpl-'+key).style.display='block';
}

function countSMS(el){
  let key = el.name.match(/\[(.*?)\]/)[1];
  document.getElementById('sms-'+key).innerText = el.value.length;
}

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
</script>
@endpush

 @extends('layouts.admin')

@php
$ui      = $ui ?? config('ui.admin.applications');
$routes  = $routes ?? config('routes.admin.applications');
$config  = $config ?? config('applications');

$currentStage = request('stage', 'all');
@endphp

@section('page_title', $ui['page_title'])
@section('topnav_title', $ui['topnav_title'])
@section('topnav_subtitle', str_replace(
[':id', ':name'],
[$scholarship->id, $scholarship->name],
$ui['topnav_subtitle']
))

@section('topnav_actions') <a href="{{ route($routes['export'], $scholarship->id) }}"
  class="btn btn-outline btn-sm">
{{ $ui['export_text'] }} </a>
@endsection

@section('content')

<div class="breadcrumb">
  @foreach($ui['breadcrumb'] as $crumb)
    @if(isset($crumb['route']))
      <a href="{{ route($crumb['route'], $scholarship->id ?? null) }}">
        {{ $crumb['label'] }}
      </a>
    @else
      <span>{{ str_replace(':name', $scholarship->name, $crumb['label']) }}</span>
    @endif
  @endforeach
</div>

<div class="grid-4">
  @foreach($ui['stats'] as $key => $stat)
    <div class="stat-card">
      <div class="label">{{ $stat['label'] }}</div>
      <div class="value">{{ $$key }}</div>
      <div class="delta">
        {{ str_replace(
            [':value', ':total'],
            [$$stat['meta'] ?? '', $scholarship->total_slots],
            $stat['meta_text']
        ) }}
      </div>
    </div>
  @endforeach
</div>

<div class="stage-tabs">
  @foreach($stages as $stage)
    <a href="{{ route($routes['index'], [
        'scholarship_id' => $scholarship->id,
        'stage' => $stage['key']
    ]) }}"
       class="stage-tab {{ $currentStage === $stage['key'] ? 'active' : '' }}">
      <span>{{ $stage['count'] }}</span>
      {{ $stage['label'] }}
    </a>
  @endforeach
</div>

<div class="card">
  <form method="GET" action="{{ route($routes['index']) }}">
    <input type="hidden" name="scholarship_id" value="{{ $scholarship->id }}">

```
<input name="search"
       placeholder="{{ $ui['search_placeholder'] }}"
       value="{{ request('search') }}">

<select name="evaluator_id">
  <option value="">{{ $ui['filters']['all_evaluators'] }}</option>
  @foreach($evaluators as $e)
    <option value="{{ $e->id }}">{{ $e->full_name }}</option>
  @endforeach
</select>

<select name="sort">
  @foreach($ui['sort_options'] as $key => $label)
    <option value="{{ $key }}">{{ $label }}</option>
  @endforeach
</select>

<button type="button"
        onclick="openAssignModal()">
  {{ $ui['assign_button'] }}
</button>
```

  </form>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        @foreach($ui['table_headers'] as $th)
          <th>{{ $th }}</th>
        @endforeach
      </tr>
    </thead>

```
<tbody>
  @forelse($applications as $app)
  <tr>
    <td><input type="checkbox" value="{{ $app->id }}"></td>

    <td>{{ str_replace(':id', $app->id, $ui['app_id_format']) }}</td>

    <td>
      {{ $app->applicant_code }}
      <small>
        {{ str_replace(
            [':gpa', ':income'],
            [$app->gpa, number_format($app->annual_income)],
            $ui['applicant_meta']
        ) }}
      </small>
    </td>

    <td>{{ $app->submitted_at->format($config['date_format']) }}</td>

    <td>{{ $app->ai_match_score }}</td>

    <td>{{ number_format($app->score, 1) }}</td>

    <td>
      {{ $config['stages'][$app->stage]['label'] ?? $app->stage }}
    </td>

    <td>
      {{ optional($app->evaluator)->full_name ?? $ui['unassigned'] }}
    </td>

    <td>
      <a href="{{ route($routes['show'], $app->id) }}">
        {{ $ui['view'] }}
      </a>
    </td>
  </tr>
  @empty
  <tr>
    <td colspan="9">{{ $ui['empty'] }}</td>
  </tr>
  @endforelse
</tbody>
```

  </table>

  <div class="pagination">
    {{ str_replace(
        [':from', ':to', ':total'],
        [$applications->firstItem(), $applications->lastItem(), $applications->total()],
        $ui['pagination']
    ) }}
  </div>
</div>

@include('admin.applications.partials.assign-evaluator-modal')

@endsection

@push('scripts')

<script>
const routes = @json($routes);
const messages = @json($ui['messages']);

function confirmAction(type, id=null) {
  if (!confirm(messages[type])) return;

  fetch(routes[type].replace(':id', id), {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  }).then(() => location.reload());
}

function openAssignModal() {
  const modal = document.getElementById('assign-evaluator-modal');
  if (!modal) return;
  if (modal.__x && modal.__x.$data) {
    modal.__x.$data.showAssignModal = true;
    return;
  }
  modal.style.display = 'flex';
}
</script>

@endpush

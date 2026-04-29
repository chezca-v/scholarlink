@extends('admin.layouts.admin')

@php
$ui     = $ui ?? config('ui.admin.calendar');
$routes = $routes ?? config('routes.admin.calendar');
$config = $config ?? config('calendar');
@endphp

@section('page_title', $ui['page_title'])
@section('topnav_title', $ui['topnav_title'])
@section('topnav_subtitle', str_replace(
[':month'],
[$currentMonth->format($config['month_format'])],
$ui['topnav_subtitle']
))

@section('topnav_actions')
@foreach($ui['actions'] as $action)
@if($action['type'] === 'button') <button class="btn btn-sm">
{{ $action['label'] }} </button>
@else <a href="{{ route($routes[$action['route']]) }}"
      class="btn btn-outline btn-sm">
{{ $action['label'] }} </a>
@endif
@endforeach
@endsection

@section('content')

<div class="breadcrumb">
  @foreach($ui['breadcrumb'] as $crumb)
    <span>{{ $crumb }}</span>
  @endforeach
</div>

<div class="calendar-header">

  <div>
    <a href="{{ route($routes['index'], ['month'=>$prevMonth->format('Y-m')]) }}">
      {{ $ui['prev'] }}
    </a>

```
<span>{{ $currentMonth->format($config['month_format']) }}</span>

<a href="{{ route($routes['index'], ['month'=>$nextMonth->format('Y-m')]) }}">
  {{ $ui['next'] }}
</a>

<a href="{{ route($routes['index']) }}">
  {{ $ui['today'] }}
</a>
```

  </div>

  <div>
    @foreach($scholarshipLegend as $item)
      <span>
        <span style="background:{{ $item['bg'] }}"></span>
        {{ $item['label'] }}
      </span>
    @endforeach
  </div>

</div>

<div class="card">
  <div class="cal-grid">

```
{{-- DAYS HEADER --}}
@foreach($config['week_days'] as $day)
  <div>{{ $day }}</div>
@endforeach

{{-- DAYS --}}
@foreach($calendarDays as $day)
  <div>
    <div>{{ $day['date']->day }}</div>

    @foreach($day['deadlines'] as $deadline)
      <span onclick="openEditModal({{ $deadline['id'] }})">
        {{ $deadline['label'] }}
      </span>
    @endforeach

  </div>
@endforeach
```

  </div>
</div>

{{-- UPCOMING --}}

<div>
  <div>
    {{ str_replace(':days', $config['upcoming_range'], $ui['upcoming_title']) }}
  </div>

  <div class="card">
    @forelse($upcomingDeadlines as $deadline)

```
  <div>
    <div>
      {{ $deadline['date']->format($config['day_format']) }}
    </div>

    <div>
      {{ $deadline['scholarship_name'] }}
      {{ $deadline['type_label'] }}
    </div>

    <div>{{ $deadline['meta'] }}</div>

    <span>
      {{ str_replace(':days', $deadline['days_away'], $ui['deadline_badge']) }}
    </span>

    <button onclick="openEditModal({{ $deadline['id'] }})">
      {{ $ui['edit'] }}
    </button>

  </div>

@empty
  <div>{{ $ui['empty'] }}</div>
@endforelse
```

  </div>
</div>

@endsection

@section('modals')

<div id="editModal">
  <form method="POST"
        action="{{ route($routes['update'], ':id') }}"
        id="editDeadlineForm">

```
@csrf
@method('PATCH')

<div id="editDeadlineScholarship"></div>
<div id="editDeadlineCurrent"></div>

<select name="type">
  @foreach($config['deadline_types'] as $key => $label)
    <option value="{{ $key }}">{{ $label }}</option>
  @endforeach
</select>

<input type="date" name="deadline_date" id="editDeadlineDate">

<input type="time" name="deadline_time">

<textarea name="reason"
          placeholder="{{ $ui['reason_placeholder'] }}"></textarea>

<div>{{ $ui['warning'] }}</div>

<button type="submit">{{ $ui['save'] }}</button>
```

  </form>
</div>

@endsection

@push('scripts')

<script>
const routes = @json($routes);
const deadlines = @json($deadlinesJson);

function openEditModal(id){
  const d = deadlines[id];
  if(!d) return;

  const form = document.getElementById('editDeadlineForm');
  form.action = routes.update.replace(':id', id);

  document.getElementById('editDeadlineScholarship').textContent = d.scholarship_name;
  document.getElementById('editDeadlineCurrent').textContent = d.type_label + ' ' + d.formatted_date;
  document.getElementById('editDeadlineDate').value = d.date;
}
</script>

@endpush

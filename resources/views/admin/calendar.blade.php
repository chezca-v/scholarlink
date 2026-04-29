@extends('admin.layouts.admin')

@php
$ui     = $ui ?? config('ui.admin.calendar');
$routes = $routes ?? config('routes.admin.calendar');
$config = $config ?? config('calendar');

// Generate blank days for offset
$firstDayOfMonth = $currentMonth->copy()->startOfMonth();
$daysInMonth = $currentMonth->daysInMonth;
$offsetDays = $firstDayOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
@endphp

@section('page_title', $ui['page_title'])
@section('topnav_title', $ui['topnav_title'])
@section('topnav_subtitle', str_replace([':month'], [$currentMonth->format($config['month_format'])], $ui['topnav_subtitle']))

@section('content')

<div class="page-header mb-6 flex justify-between items-end">
    <div>
        <div class="flex items-center gap-3 mb-1">
            @foreach($ui['breadcrumb'] as $crumb)
                <span style="color:var(--slate);font-size:13px;">{{ $crumb }}</span>
                @if(!$loop->last)<span style="color:var(--muted);">/</span>@endif
            @endforeach
        </div>
        <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">{{ $ui['page_title'] }}</h1>
    </div>
    
    <div class="flex items-center gap-4">
        {{-- Legend --}}
        <div class="flex items-center gap-4 mr-4">
            @foreach($scholarshipLegend as $item)
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full" style="background:{{ $item['bg'] }}"></div>
                <span class="text-xs font-semibold text-[var(--slate)]">{{ $item['label'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Month Controls --}}
        <div class="flex items-center gap-1 bg-white border border-[var(--border)] rounded-xl p-1 shadow-sm">
            <a href="{{ route($routes['index'], ['month'=>$prevMonth->format('Y-m')]) }}" class="p-2 hover:bg-gray-50 rounded-lg text-[var(--slate)] transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <a href="{{ route($routes['index']) }}" class="px-3 py-1 text-sm font-bold text-[var(--ink)] hover:text-[var(--primary)] transition-colors">
                {{ $currentMonth->format($config['month_format']) }}
            </a>
            <a href="{{ route($routes['index'], ['month'=>$nextMonth->format('Y-m')]) }}" class="p-2 hover:bg-gray-50 rounded-lg text-[var(--slate)] transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
        <a href="{{ route($routes['index']) }}" class="btn-secondary">{{ $ui['today'] }}</a>
    </div>
</div>

<div class="page-content">
    <div class="grid grid-cols-4 gap-6">
        
        {{-- MAIN CALENDAR --}}
        <div class="col-span-3">
            <div class="card overflow-hidden border border-[var(--border)] bg-white">
                {{-- DAYS HEADER --}}
                <div class="grid grid-cols-7 border-b border-[var(--border)] bg-gray-50">
                    @foreach($config['week_days'] as $day)
                    <div class="py-3 text-center text-xs font-bold text-[var(--slate)] uppercase tracking-wider">{{ $day }}</div>
                    @endforeach
                </div>

                {{-- DAYS GRID --}}
                <div class="grid grid-cols-7 auto-rows-[120px] bg-[var(--border)] gap-[1px]">
                    
                    {{-- Empty offset cells --}}
                    @for($i = 0; $i < $offsetDays; $i++)
                    <div class="bg-gray-50/50 p-2"></div>
                    @endfor

                    {{-- Actual days --}}
                    @foreach($calendarDays as $day)
                    @php
                        $isToday = $day['date']->isToday();
                    @endphp
                    <div class="bg-white p-2 flex flex-col group hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-1">
                            <span class="w-7 h-7 flex items-center justify-center rounded-full text-sm font-semibold {{ $isToday ? 'bg-[var(--primary)] text-white' : 'text-[var(--ink)]' }}">
                                {{ $day['date']->day }}
                            </span>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto space-y-1 mt-1 no-scrollbar">
                            @foreach($day['deadlines'] ?? [] as $deadline)
                            <button onclick="openEditModal({{ $deadline['id'] }})" class="w-full text-left px-2 py-1 text-xs font-semibold rounded text-white truncate transition-transform hover:scale-[1.02]" style="background: {{ $deadline['type'] === 'urgent' ? '#ea8c55' : '#1a8fa0' }};" title="{{ $deadline['label'] }}">
                                {{ $deadline['label'] }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                    {{-- Trailing empty cells to fill the last row --}}
                    @php
                        $totalCells = $offsetDays + count($calendarDays);
                        $trailingCells = (7 - ($totalCells % 7)) % 7;
                    @endphp
                    @for($i = 0; $i < $trailingCells; $i++)
                    <div class="bg-gray-50/50 p-2"></div>
                    @endfor

                </div>
            </div>
        </div>

        {{-- UPCOMING DEADLINES SIDEBAR --}}
        <div class="col-span-1">
            <h3 class="font-display font-bold text-lg text-[var(--ink)] mb-4">
                {{ str_replace(':days', $config['upcoming_range'], $ui['upcoming_title']) }}
            </h3>

            <div class="space-y-3">
                @forelse($upcomingDeadlines as $deadline)
                <div class="card p-4 hover:border-[var(--primary)] transition-colors group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-[var(--slate)]">{{ $deadline['date']->format($config['day_format']) }}</span>
                        <span class="text-xs font-bold text-[var(--primary)] bg-[rgba(15,76,92,0.1)] px-2 py-0.5 rounded-full">
                            {{ str_replace(':days', $deadline['days_away'], $ui['deadline_badge']) }}
                        </span>
                    </div>
                    <h4 class="font-bold text-[var(--ink)] text-sm leading-snug">{{ $deadline['scholarship_name'] }}</h4>
                    <p class="text-xs text-[var(--slate)] mt-1">{{ $deadline['type_label'] }} • {{ $deadline['meta'] }}</p>
                    
                    <button onclick="openEditModal({{ $deadline['id'] }})" class="mt-3 text-xs font-bold text-[var(--primary)] opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        {{ $ui['edit'] }}
                    </button>
                </div>
                @empty
                <div class="card p-6 text-center">
                    <p class="text-[var(--slate)] text-sm">{{ $ui['empty'] }}</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('modals')

<div id="editModal" x-data="{ show: false }" @open-edit-modal.window="show = true" x-show="show" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
  <div @click.outside="show = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
      
      <div class="px-6 py-4 border-b border-[var(--border)] flex justify-between items-center bg-gray-50">
          <h3 class="font-display font-bold text-lg text-[var(--ink)]">Edit Deadline</h3>
          <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
      </div>

      <form method="POST" action="" id="editDeadlineForm" class="p-6 space-y-4">
        @csrf
        @method('PATCH')

        <div class="p-3 bg-blue-50 text-blue-900 rounded-lg border border-blue-100 mb-4">
            <p id="editDeadlineScholarship" class="font-bold text-sm"></p>
            <p id="editDeadlineCurrent" class="text-xs mt-1 opacity-80"></p>
        </div>

        <div>
            <label class="input-label">Deadline Type</label>
            <select name="type" class="input-field">
            @foreach($config['deadline_types'] as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="input-label">Date</label>
                <input type="date" name="deadline_date" id="editDeadlineDate" class="input-field">
            </div>
            <div>
                <label class="input-label">Time (Optional)</label>
                <input type="time" name="deadline_time" class="input-field">
            </div>
        </div>

        <div>
            <label class="input-label">Reason for change</label>
            <textarea name="reason" rows="2" class="input-field" placeholder="{{ $ui['reason_placeholder'] }}"></textarea>
            <p class="text-xs text-red-500 mt-2">{{ $ui['warning'] }}</p>
        </div>

        <div class="pt-4 flex justify-end gap-2">
            <button type="button" @click="show = false" class="btn-ghost">Cancel</button>
            <button type="submit" class="btn-primary">{{ $ui['save'] }}</button>
        </div>
      </form>
  </div>
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
  
  window.dispatchEvent(new CustomEvent('open-edit-modal'));
}
</script>

@endpush

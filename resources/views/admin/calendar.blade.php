@extends('layouts.admin')

@section('page_title', 'Deadline Calendar')
@section('topnav_title', 'Deadline Calendar')
@section('topnav_subtitle', '/admin/calendar · ' . $currentMonth->format('F Y'))

@section('topnav_actions')
  <button class="btn btn-sm"
          style="background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25)">
    📅 Month
  </button>
  <a href="{{ route('admin.calendar.create') }}"
     class="btn btn-outline btn-sm"
     style="border-color:rgba(255,255,255,.3);color:rgba(255,255,255,.8);background:rgba(255,255,255,.08);">
    + Add Deadline
  </a>
@endsection

@section('content')
  <!-- BREADCRUMB -->
  <div class="breadcrumb">
    <span>Admin</span><span class="sep">/</span><span class="current">Deadline Calendar</span>
  </div>

  <!-- CALENDAR HEADER NAV -->
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <a href="{{ route('admin.calendar.index', ['month' => $prevMonth->format('Y-m')]) }}"
         class="btn btn-outline btn-sm">‹ Prev</a>
      <div style="font-family:'Fraunces',serif;font-size:20px;font-weight:700;color:var(--ink);">
        {{ $currentMonth->format('F Y') }}
      </div>
      <a href="{{ route('admin.calendar.index', ['month' => $nextMonth->format('Y-m')]) }}"
         class="btn btn-outline btn-sm">Next ›</a>
      <a href="{{ route('admin.calendar.index') }}"
         class="btn btn-ghost btn-sm" style="color:var(--primary);">Today</a>
    </div>
    <!-- LEGEND -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
      @foreach($scholarshipLegend as $key => $item)
      <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--slate);">
        <span style="width:10px;height:10px;border-radius:2px;background:{{ $item['bg'] }};display:inline-block;"></span>
        {{ $item['label'] }}
      </span>
      @endforeach
    </div>
  </div>

  <!-- MONTH VIEW -->
  <div class="card" style="padding: 0; overflow: hidden;">
    <div class="cal-grid">
      <!-- HEADER ROW -->
      @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
        <div class="cal-header-cell">{{ $day }}</div>
      @endforeach

      <!-- CALENDAR DAYS -->
      @foreach($calendarDays as $day)
      <div class="cal-day
                  {{ $day['is_today'] ? 'today' : '' }}
                  {{ $day['is_other_month'] ? 'other-month' : '' }}">
        <div class="day-num">{{ $day['date']->day }}</div>
        @foreach($day['deadlines'] as $deadline)
          <span class="deadline-chip {{ $deadline['chip_class'] }}"
                onclick="openEditModal({{ $deadline['id'] }})">
            {{ $deadline['label'] }}
          </span>
        @endforeach
      </div>
      @endforeach
    </div>
  </div>

  <!-- UPCOMING DEADLINES LIST -->
  <div style="margin-top: 20px;">
    <div class="section-title" style="margin-bottom: 12px;">
      📌 Upcoming Deadlines <small>next 14 days</small>
    </div>
    <div class="card compact">
      <div style="display: flex; flex-direction: column; gap: 0;">
        @forelse($upcomingDeadlines as $deadline)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border);">
          <div style="width:48px;text-align:center;flex-shrink:0;">
            <div style="font-family:'Fraunces',serif;font-size:20px;font-weight:700;
                        color:{{ $deadline['days_away'] === 0 ? 'var(--red)' : ($deadline['days_away'] <= 3 ? 'var(--yellow)' : 'var(--ink)') }};">
              {{ $deadline['date']->format('d') }}
            </div>
            <div style="font-size:10px;color:var(--muted);text-transform:uppercase;">
              {{ $deadline['date']->format('M') }}
            </div>
          </div>
          <div style="flex: 1;">
            <div style="font-size:13px;font-weight:600;">{{ $deadline['scholarship_name'] }} — {{ $deadline['type_label'] }}</div>
            <div style="font-size:11px;color:var(--slate);">{{ $deadline['meta'] }}</div>
          </div>
          @if($deadline['days_away'] === 0)
            <span class="badge red">Today</span>
          @elseif($deadline['days_away'] <= 2)
            <span class="badge yellow">{{ $deadline['days_away'] }} day{{ $deadline['days_away'] > 1 ? 's' : '' }}</span>
          @elseif($deadline['days_away'] <= 5)
            <span class="badge amber">{{ $deadline['days_away'] }} days</span>
          @else
            <span class="badge teal">{{ $deadline['days_away'] }} days</span>
          @endif
          <button class="btn btn-outline btn-sm"
                  onclick="openEditModal({{ $deadline['id'] }})">✏️ Edit</button>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:var(--muted);">
          No upcoming deadlines in the next 14 days.
        </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection

@section('modals')
  <!-- Edit Deadline Modal -->
  <div id="editModal" class="modal-overlay"
       onclick="if(event.target===this) this.classList.remove('open')">
    <div class="modal">
      <div class="modal-title">
        ✏️ Edit Deadline
        <button class="modal-close"
                onclick="document.getElementById('editModal').classList.remove('open')">✕</button>
      </div>
      <form method="POST" action="{{ route('admin.calendar.update', ':id') }}" id="editDeadlineForm">
        @csrf @method('PATCH')
        <div style="background:var(--page-bg);border:1.5px solid var(--border);border-radius:12px;padding:12px;margin-bottom:16px;">
          <div style="font-size:13px;font-weight:600;" id="editDeadlineScholarship">—</div>
          <div style="font-size:12px;color:var(--slate);" id="editDeadlineCurrent">—</div>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label class="form-label">Deadline Type</label>
            <select class="form-select" name="type">
              <option value="application_close">Application Close</option>
              <option value="evaluation_deadline">Evaluation Deadline</option>
              <option value="results_announcement">Results Announcement</option>
              <option value="document_submission">Document Submission</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">New Deadline Date <span class="req">*</span></label>
            <input class="form-input" type="date" name="deadline_date" id="editDeadlineDate" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Time</label>
          <input class="form-input" type="time" name="deadline_time" value="23:59">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">
            Reason for Extension
            <span style="font-weight:400;color:var(--muted);">(optional)</span>
          </label>
          <textarea class="form-textarea" name="reason" rows="2"
                    placeholder="e.g. Due to public holiday, extending by 5 days…"></textarea>
        </div>
        <div style="background:var(--yellow-bg);border:1px solid rgba(217,119,6,.2);border-radius:10px;padding:10px 12px;font-size:12px;color:var(--yellow);margin-top:12px;">
          ⚠️ Changing the deadline will notify all affected applicants via email and SMS.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline"
                  onclick="document.getElementById('editModal').classList.remove('open')">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Deadline</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  const deadlines = @json($deadlinesJson);

  function openEditModal(id) {
    const d = deadlines[id];
    if (!d) return;

    const form = document.getElementById('editDeadlineForm');
    form.action = form.action.replace(':id', id);

    document.getElementById('editDeadlineScholarship').textContent = d.scholarship_name;
    document.getElementById('editDeadlineCurrent').textContent =
      d.type_label + ' · Currently: ' + d.formatted_date;
    document.getElementById('editDeadlineDate').value = d.date;

    document.getElementById('editModal').classList.add('open');
  }
</script>
@endpush

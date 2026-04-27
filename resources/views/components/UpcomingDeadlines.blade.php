@props(['deadlines', 'now'])

<div class="alerts-section" style="margin-top: 20px;">
    <div class="section-header">
        <h3>Upcoming Deadlines</h3>
        <a href="{{ route('admin.calendar') ?? '#' }}" class="subtitle" style="text-decoration: none; color: inherit;">Calendar →</a>
    </div>

    <div>
        @forelse($deadlines as $deadlineScholarship)
            @php
                $daysLeft = max(0, $now->diffInDays($deadlineScholarship->deadline, false));
                $deadlineClass = $daysLeft <= 3 ? 'urgent' : ($daysLeft <= 10 ? 'warning' : ($daysLeft <= 20 ? 'safe' : 'info'));
                $deadlineColor = $daysLeft <= 3 ? '#ef5350' : ($daysLeft <= 10 ? '#ea8c55' : ($daysLeft <= 20 ? '#10b981' : '#1a8fa0'));
            @endphp
            <div class="deadline-item">
                <div class="deadline-color" style="background: {{ $deadlineColor }};"></div>
                <div class="deadline-content">
                    <div class="deadline-name">{{ $deadlineScholarship->name }}</div>
                    <div class="deadline-date">{{ $deadlineScholarship->deadline->format('M j') }} · {{ $deadlineScholarship->applications_count }}/{{ $deadlineScholarship->slots }} slots filled</div>
                </div>
                <div class="deadline-days {{ $deadlineClass }}">{{ $daysLeft }}d</div>
            </div>
        @empty
            <div class="deadline-item">
                <div class="deadline-color" style="background: #1a8fa0;"></div>
                <div class="deadline-content">
                    <div class="deadline-name">No upcoming deadlines</div>
                    <div class="deadline-date">Create or open a scholarship to see timelines here.</div>
                </div>
                <div class="deadline-days info">--</div>
            </div>
        @endforelse
    </div>
</div>

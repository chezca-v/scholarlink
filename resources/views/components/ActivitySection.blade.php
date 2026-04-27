@props(['activities'])

<div class="activity-section">
    <div class="section-header">
        <h3>Recent Activity</h3>
        <a href="{{ route('admin.activity.index') ?? '#' }}" class="subtitle" style="text-decoration: none; color: inherit;">View all →</a>
    </div>

    <div class="activity-list">
        @forelse($activities as $activity)
            <div class="activity-item">
                <div class="activity-icon" style="background: #f3f4f6; color: #0f4c5c;">📌</div>
                <div class="activity-content">
                    <div class="activity-text">
                        <strong>{{ trim((optional($activity->user)->first_name ?? '').' '.(optional($activity->user)->last_name ?? '')) ?: 'System' }}</strong> {{ $activity->action }}
                    </div>
                    <div class="activity-meta">
                        <span class="activity-badge">{{ ucfirst(str_replace('_', ' ', $activity->target_type ?? 'system')) }}</span>
                        <span>{{ $activity->target_type ? 'Target ID: '.$activity->target_id : 'System activity' }}</span>
                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="activity-item">
                <div class="activity-icon" style="background: #f3f4f6; color: #0f4c5c;">ℹ️</div>
                <div class="activity-content">
                    <div class="activity-text"><strong>No recent activity yet.</strong> Activity logs will appear here as users interact with the system.</div>
                </div>
            </div>
        @endforelse
    </div>
</div>

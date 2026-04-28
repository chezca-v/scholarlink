@props(['stats'])

<div class="stat-grid">
    @foreach($stats as $stat)
        <div class="stat-card">
            <div style="display:flex; justify-content:space-between;">
                <span class="icon">{{ $stat['icon'] }}</span>
                <span class="stat-badge" @if($stat['badge_color'] ?? false) style="color: {{ $stat['badge_color'] }}" @endif>
                    {{ $stat['badge_text'] }}
                </span>
            </div>
            <h1>{{ $stat['value'] }}</h1>
            <p style="font-size:12px; font-weight:600; color:var(--teal-mid)">{{ $stat['label'] }}</p>
            <p class="stat-card-foot">{{ $stat['footer'] }}</p>
        </div>
    @endforeach
</div>

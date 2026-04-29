@props(['alerts'])

<div class="box-container">
    <div class="box-header">
        <h3 class="box-title">⚠ Bottleneck Alerts</h3>
        <span style="font-size:10px; color:var(--muted)">Auto-refreshes every 5 min</span>
    </div>
    
    @foreach($alerts as $alert)
        <div class="alert-row alert-{{ $alert['type'] }}">
            <div style="font-size:18px;">{{ $alert['icon'] }}</div>
            <div>
                <b style="font-size:13px;">{{ $alert['title'] }}</b>
                <p style="font-size:12px; opacity:0.8;">{{ $alert['description'] }}</p>
                <a href="{{ $alert['link'] ?? '#' }}" style="color:inherit; font-weight:700; font-size:11px; margin-top:6px; display:block;">{{ $alert['link_text'] ?? 'View' }} →</a>
            </div>
        </div>
    @endforeach
</div>

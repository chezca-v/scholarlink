@props(['actions'])

<div class="box-container">
    <h3 class="box-title" style="margin-bottom:12px;">Quick Actions</h3>
    <div class="action-grid">
        @foreach($actions as $action)
            <a href="{{ $action['link'] }}" class="action-card">
                <div style="font-size:18px; margin-bottom:5px;">{{ $action['icon'] }}</div>
                <b style="font-size:11px;">{{ $action['label'] }}</b>
            </a>
        @endforeach
    </div>
</div>

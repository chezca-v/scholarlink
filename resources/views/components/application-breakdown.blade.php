@props(['statusCounts', 'totalApplications', 'breakdownItems'])

<div class="alerts-section">
    <div class="section-header">
        <h3>Application Breakdown</h3>
        <span class="subtitle">{{ $totalApplications }} total</span>
    </div>

    <div>
        @foreach($breakdownItems as $item)
            <div class="breakdown-item">
                <div class="breakdown-color" style="background: {{ $item['color'] }};"></div>
                <div class="breakdown-label">{{ $item['label'] }}</div>
                <div class="breakdown-number">{{ $item['count'] }}</div>
                <div class="breakdown-percent">{{ $item['percentage'] }}%</div>
            </div>
        @endforeach

        <div class="stacked-bar">
            @foreach($breakdownItems as $item)
                <div class="stacked-bar-segment" style="background: {{ $item['color'] }}; width: {{ $item['percentage'] }}%;"></div>
            @endforeach
        </div>
        <div style="font-size: 12px; color: #3da9b8; margin-top: 8px;">Stacked distribution across all scholarships</div>
    </div>
</div>

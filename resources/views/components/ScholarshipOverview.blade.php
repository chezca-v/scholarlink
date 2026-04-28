@props(['scholarships'])

<div class="box-container">
    <div class="box-header">
        <h3 class="box-title">Scholarship Overview</h3>
        <a href="{{ route('admin.scholarships.index') ?? '#' }}" style="font-size:12px; color:var(--teal-mid); font-weight:700;">Manage all →</a>
    </div>
    <table style="width: 100%;">
        <thead>
        <tr>
            <th>Scholarship</th>
            <th>Status</th>
            <th>Apps</th>
            <th>Capacity</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($scholarships as $scholarship)
            @php
                $appsCount = $scholarship->applications_count ?? 0;
                $slots = max(1, (int) $scholarship->slots);
                $fill = min(100, (int) round(($appsCount / $slots) * 100));
                $statusColor = match($scholarship->status) {
                    'open' => 'var(--green-success)',
                    'closed' => 'var(--red-alert)',
                    'coming_soon' => 'var(--orange-warning)',
                    default => 'var(--muted)',
                };
            @endphp
            <tr>
                <td><b>{{ $scholarship->name }}</b><br><small style="color:var(--muted)">{{ $scholarship->provider_name }}</small></td>
                <td><span style="color:{{ $statusColor }}; font-weight:700;">● {{ ucfirst(str_replace('_', ' ', $scholarship->status)) }}</span></td>
                <td>{{ $appsCount }} / {{ $scholarship->slots }}</td>
                <td><div class="prog-bar-container"><div class="prog-bar-fill" style="width:{{ $fill }}%"></div></div></td>
                <td style="text-align:right;"><a href="{{ route('admin.scholarships.show', $scholarship->id) }}" style="color:var(--teal-mid); font-weight:700;">View →</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center; color:var(--muted);">No scholarships found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

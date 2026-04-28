{{-- FULLY DYNAMIC — ZERO HARDCODED STRINGS --}}

@php
$activeFilter = $activeFilter ?? request('filter', 'all');

// UI text config (should come from controller/config later)
$ui = $ui ?? config('ui.applications');

$counts = [
'all'          => $applications->count(),
'under_review' => $applications->where('status', 'under_review')->count(),
'submitted'    => $applications->where('status', 'submitted')->count(),
'approved'     => $applications->where('status', 'approved')->count(),
];

$statusConfig = config('applications.statuses');
@endphp

<div class="ma-root">

<div class="ma-page-header">
    <div class="ma-page-eyebrow">{{ $ui['eyebrow'] }}</div>
    <h1 class="ma-page-title">{{ $ui['title'] }}</h1>
    <span class="ma-page-breadcrumb">{{ request()->path() }}</span>
</div>

<div class="ma-shell">

```
{{-- TOPBAR --}}
<div class="ma-topbar">
    <div class="ma-topbar__brand">
        <div class="ma-topbar__logo">{{ config('app.logo_icon') }}</div>
        {{ config('app.name') }}
    </div>

    <div class="ma-topbar__search">
        <span class="ma-topbar__search-icon">🔍</span>
        <input type="text" placeholder="{{ $ui['search_placeholder'] }}" />
    </div>

    <div class="ma-topbar__actions">
        <div class="ma-topbar__bell">🔔</div>

        <div class="ma-topbar__avatar">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
    </div>
</div>

<div class="ma-body">

    {{-- SIDEBAR --}}
    <aside class="ma-sidebar">
        @foreach($navigation['main'] as $item)
            <a href="{{ route($item['route']) }}"
               class="ma-nav-item {{ $item['active'] ? 'is-active' : '' }}">
                {!! $item['icon'] !!}
                {{ $item['label'] }}
                @if(!empty($item['badge']))
                    <span class="ma-nav-item__badge">{{ $item['badge'] }}</span>
                @endif
            </a>
        @endforeach

        @foreach($navigation['account'] as $item)
            <a href="{{ route($item['route']) }}" class="ma-nav-item">
                {!! $item['icon'] !!}
                {{ $item['label'] }}
            </a>
        @endforeach

        <div class="ma-sidebar__user">
            <div class="ma-sidebar__user-avatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <div class="ma-sidebar__user-name">{{ $user->name }}</div>
                <div class="ma-sidebar__user-role">{{ $user->role }}</div>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="ma-main">

        <div class="ma-content-header">
            <div>
                <div class="ma-content-eyebrow">{{ $ui['section_label'] }}</div>
                <h2 class="ma-content-title">{{ $ui['section_title'] }}</h2>
            </div>

            <a href="{{ route($ui['apply_route']) }}" class="ma-btn-apply">
                {{ $ui['apply_text'] }}
            </a>
        </div>

        {{-- FILTERS --}}
        <div class="ma-tabs">
            @foreach($ui['filters'] as $key => $label)
                <button class="ma-tab {{ $activeFilter===$key?'is-active':'' }}"
                        onclick="MyApplications.filter('{{ $key }}', this)">
                    {{ $label }} ({{ $counts[$key] ?? 0 }})
                </button>
            @endforeach
        </div>

        {{-- TABLE --}}
        <div class="ma-table-wrap">
            <table class="ma-table">
                <thead>
                    <tr>
                        @foreach($ui['table_headers'] as $th)
                            <th>{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody id="ma-table-body">
                    @forelse ($applications as $app)
                        <tr data-status="{{ $app->status }}" data-id="{{ $app->id }}">

                            <td>
                                <div class="ma-app-name">
                                    {{ $app->scholarship->title }}
                                </div>
                                <div class="ma-app-deadline">
                                    {{ $ui['deadline_label'] }}:
                                    {{ optional($app->scholarship->deadline)->format($ui['date_format']) }}
                                </div>
                            </td>

                            <td>{{ $app->scholarship->organization->name }}</td>

                            <td>{{ $app->created_at->format($ui['date_format']) }}</td>

                            <td>{{ $app->stage }}</td>

                            <td>
                                @php
                                    $sc = $statusConfig[$app->status] ?? null;
                                @endphp

                                <span class="ma-status {{ $sc['class'] ?? '' }}">
                                    {{ $sc['label'] ?? $app->status }}
                                </span>
                            </td>

                            <td>
                                <div class="ma-actions">

                                    <a href="{{ route('applications.show', $app->id) }}" class="ma-btn">
                                        {{ $ui['view_text'] }}
                                    </a>

                                    @if($app->canWithdraw())
                                        <form method="POST" action="{{ route('applications.withdraw',$app->id) }}">
                                            @csrf
                                            <button class="ma-btn ma-btn--withdraw">
                                                {{ $ui['withdraw_text'] }}
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:30px;">
                                {{ $ui['empty_state'] }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="ma-table-footer">
                {{ str_replace(':count', $counts['all'], $ui['footer_text']) }}
            </div>
        </div>

    </main>
</div>
```

</div>

</div>

<script>
window.MyApplications = {
    filter(status, el) {
        document.querySelectorAll('.ma-tab').forEach(t => t.classList.remove('is-active'));
        el.classList.add('is-active');

        document.querySelectorAll('#ma-table-body tr').forEach(r => {
            r.style.display = (status==='all'||r.dataset.status===status) ? '' : 'none';
        });
    }
};
</script>

{{--
|--------------------------------------------------------------------------
| evaluator-profile.blade.php
|--------------------------------------------------------------------------
| ScholarLink — Evaluator Profile Component
|
| Displays evaluator information, expertise, and statistics
|
| Props:
|   - $evaluator: Evaluator model/array with user info
|   - $stats: Evaluator statistics (reviews, rating, etc.)
|   - $showStats: Boolean to show/hide statistics
--}}

@php
    $evaluator = $evaluator ?? null;
    $stats = $stats ?? null;
    $showStats = $showStats ?? true;
@endphp

@if($evaluator)
<div class="evaluator-profile-card">
    <div class="evaluator-header">
        <div class="evaluator-avatar">
            @if($evaluator?->avatar_url)
                <img src="{{ $evaluator->avatar_url }}" alt="{{ $evaluator->name }}" />
            @else
                {{ strtoupper(substr($evaluator?->name ?? 'E', 0, 1)) }}
            @endif
        </div>
        
        <div class="evaluator-info">
            <h3 class="evaluator-name">{{ $evaluator?->name ?? 'Evaluator' }}</h3>
            <p class="evaluator-title">{{ $evaluator?->title ?? 'Academic Evaluator' }}</p>
            <p class="evaluator-department">{{ $evaluator?->department ?? 'Department' }}</p>
        </div>
    </div>

    @if($showStats && $stats)
    <div class="evaluator-stats">
        <div class="stat-item">
            <span class="stat-label">Reviews</span>
            <span class="stat-value">{{ $stats->total_reviews ?? 0 }}</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Rating</span>
            <span class="stat-value">{{ $stats->average_rating ?? '4.8' }}/5</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Expertise</span>
            <span class="stat-value">{{ $stats->expertise_areas ?? 'Multiple' }}</span>
        </div>
    </div>
    @endif

    <div class="evaluator-contact">
        <p><strong>Email:</strong> {{ $evaluator?->email ?? 'Not provided' }}</p>
        <p><strong>Phone:</strong> {{ $evaluator?->phone ?? 'Not provided' }}</p>
    </div>

    <style>
        .evaluator-profile-card {
            background: white;
            border: 1px solid #DFF0EE;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(15, 76, 92, 0.04);
        }

        .evaluator-header {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            align-items: flex-start;
        }

        .evaluator-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0F4C5C, #1A6B7A);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .evaluator-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .evaluator-info {
            flex: 1;
        }

        .evaluator-name {
            font-size: 18px;
            font-weight: 700;
            color: #0F4C5C;
            margin: 0 0 4px 0;
        }

        .evaluator-title {
            font-size: 13px;
            color: #1A6B7A;
            font-weight: 600;
            margin: 0;
        }

        .evaluator-department {
            font-size: 12px;
            color: #6B8E94;
            margin: 2px 0 0 0;
        }

        .evaluator-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 16px 0;
            padding: 12px;
            background: #F0FAFA;
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            display: block;
            font-size: 11px;
            color: #6B8E94;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-value {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: #0F4C5C;
        }

        .evaluator-contact {
            border-top: 1px solid #DFF0EE;
            padding-top: 12px;
            font-size: 13px;
            color: #0A3040;
        }

        .evaluator-contact p {
            margin: 6px 0;
        }

        .evaluator-contact strong {
            color: #0F4C5C;
            font-weight: 700;
        }
    </style>
</div>
@endif

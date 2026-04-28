@extends('layouts.applicant')

@section('title', 'ScholarLink - My Applications')

@push('styles')
<style>
    :root {
        --deep-teal: #0F4C5C;
        --teal-mid: #1A6B7A;
        --teal-soft-bg: #EAF4F4;
        --teal-ink: #184A59;
        --muted: #7BA5AE;
        --card-bg: #fff;
        --border: #DDEDEE;
        --success-bg: #BFECC9;
        --success-text: #0B5F2A;
        --warn-bg: #F9DF9A;
        --warn-text: #9B6400;
        --danger-bg: #FFD0D0;
        --danger-text: #C42020;
        --pending-bg: #AFC3CA;
        --pending-text: #184A59;
        --white: #fff;
    }

    .page-wrap {
        max-width: 1320px;
        margin: 0 auto;
        /* padding handled by main container in layout */
        padding-bottom: 40px;
    }

    .brand-title {
        font-family: 'Fraunces', serif;
        font-size: 30px;
        font-weight: 900;
        color: var(--deep-teal);
        line-height: 1.1;
    }

    .subtitle {
        margin-top: 4px;
        font-size: 24px;
        color: var(--teal-ink);
    }

    .stats-grid {
        margin-top: 20px;
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
    }

    .stat-card {
        background: var(--white);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 2px 8px 4px rgba(15, 76, 92, 0.05);
        border: 1px solid transparent;
        cursor: pointer;
        transition: .2s ease;
        text-align: left;
    }
    .stat-card:hover { transform: translateY(-2px); border-color: #CDE3E5; }
    .stat-card.active { border-color: #176879; box-shadow: 0 0 0 2px rgba(23,104,121,.1); }

    .stat-card p:first-child { font-size: 12px; margin: 0; }
    .stat-value {
        font-family: 'Fraunces', serif;
        font-size: 36px;
        font-weight: 700;
        margin-top: 6px;
        line-height: 1;
        color: #123A49;
    }

    .stat-note {
        margin-top: 8px;
        font-size: 12px;
        color: #2B8CA1;
        margin-bottom: 0;
    }

    .table-wrap {
        margin-top: 26px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 76, 92, 0.08);
    }

    .filter-state {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 10px 16px;
        font-size: 12px;
        color: #4D7E87;
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    thead tr { background: #176879; color: #EAF4F4; }
    th {
        text-align: left;
        font-size: 18px;
        font-weight: 700;
        padding: 18px 20px;
    }

    td {
        padding: 14px 16px;
        font-size: 16px;
        vertical-align: top;
        border-bottom: 1px solid #EDF5F5;
    }

    .sch-title { font-size: 24px; font-weight: 700; line-height: 1.1; color: #176879; }
    .org { font-size: 20px; color: var(--muted); }
    .status-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        white-space: nowrap;
    }

    .submitted { border: 1px solid #176879; color: #176879; background: transparent; }
    .approved { background: var(--success-bg); color: var(--success-text); }
    .under-review { background: var(--warn-bg); color: var(--warn-text); }
    .rejected { background: var(--danger-bg); color: var(--danger-text); }
    .pending { background: var(--pending-bg); color: var(--pending-text); }
    .shortlisted { background: var(--success-bg); color: var(--success-text); }

    @media (max-width: 1280px) {
        .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .brand-title { font-size: 22px; }
        .subtitle { font-size: 22px; }
        .sch-title { font-size: 24px; }
        .org { font-size: 18px; }
    }

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        th, td { font-size: 13px; }
        .sch-title { font-size: 18px; }
        .org { font-size: 13px; }
    }

    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .page-wrap { padding: 16px; }
        .brand-title { font-size: 19px; }
        .subtitle { font-size: 18px; }
        .table-wrap { overflow-x: auto; }
        table { min-width: 980px; }
    }
</style>
@endpush

@section('content')
<div class="page-wrap">
    <section>
        <h1 class="brand-title">My Applications</h1>
        <p class="subtitle">Track and manage all your scholarship application in one place</p>
    </section>

    <section class="stats-grid">
        <button class="stat-card active" data-filter="all" type="button"><p>TOTAL APPLIED</p><div class="stat-value">{{ $stats['totalApplied'] }}</div><p class="stat-note">scholarship</p></button>
        <button class="stat-card" data-filter="under-review" type="button"><p>UNDER REVIEW</p><div class="stat-value">{{ $stats['underReview'] }}</div><p class="stat-note">in progress</p></button>
        <button class="stat-card" data-filter="approved" type="button"><p>APPROVED</p><div class="stat-value">{{ $stats['approved'] }}</div><p class="stat-note">awarded</p></button>
        <button class="stat-card" data-filter="rejected" type="button"><p>REJECTED</p><div class="stat-value">{{ $stats['rejected'] }}</div><p class="stat-note">not selected</p></button>
        <button class="stat-card" data-filter="shortlisted" type="button"><p>SHORTLISTED</p><div class="stat-value">{{ $stats['shortlisted'] }}</div><p class="stat-note">qualified</p></button>
        <button class="stat-card" data-filter="action-needed" type="button"><p>ACTION NEEDED</p><div class="stat-value">{{ $stats['actionNeeded'] }}</div><p class="stat-note">missing requirements</p></button>
    </section>

    <section class="table-wrap">
        <div class="filter-state">Showing: <span id="filterLabel">All Applications</span></div>

        <table>
            <thead>
            <tr>
                <th>SCHOLARSHIP & ORGANIZATION</th>
                <th>REFERENCE NO.</th>
                <th>DATE APPLIED</th>
                <th>REMARKS</th>
                <th>STATUS</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($applications as $application)
                @php
                    $mappedStatus = $statusMap[$application->status] ?? $statusMap['submitted'];
                    if ($application->status === 'under_review' && $application->stage === 'scoring') {
                        $mappedStatus = ['filter' => 'shortlisted', 'class' => 'shortlisted', 'label' => 'Shortlisted'];
                    }
                @endphp
                <tr data-status="{{ $mappedStatus['filter'] }}">
                    <td>
                        <div class="sch-title">{{ $application->scholarship?->name ?? 'Scholarship name unavailable' }}</div>
                        <div class="org">{{ $application->scholarship?->provider_name ?? 'Organization unavailable' }}</div>
                    </td>
                    <td>{{ $application->reference_code ?? '—' }}</td>
                    <td>{{ optional($application->submitted_at ?? $application->created_at)?->format('F d, Y') ?? '—' }}</td>
                    <td>{{ $remarksByStage[$application->stage] ?? 'No current stage available' }}</td>
                    <td><span class="status-pill {{ $mappedStatus['class'] }}">{{ $mappedStatus['label'] }}</span></td>
                </tr>
            @empty
                <tr data-status="submitted">
                    <td><div class="sch-title">No applications yet</div><div class="org">Your submitted applications will appear here</div></td>
                    <td>—</td>
                    <td>—</td>
                    <td>No records found</td>
                    <td>—</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.stat-card[data-filter]');
        const rows = document.querySelectorAll('tbody tr[data-status]');
        const filterLabel = document.getElementById('filterLabel');
        const labels = {
            all: 'All Applications',
            'under-review': 'Under Review',
            approved: 'Approved',
            rejected: 'Rejected',
            shortlisted: 'Shortlisted',
            'action-needed': 'Action Needed',
            submitted: 'Submitted'
        };

        cards.forEach((card) => {
            card.addEventListener('click', () => {
                const filter = card.dataset.filter;
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                filterLabel.textContent = labels[filter] ?? 'All Applications';

                rows.forEach((row) => {
                    row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
                });
            });
        });
    });
</script>
@endpush

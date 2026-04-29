@extends('admin.layouts.admin')

@section('title', 'Pending Reviews')

@section('content')
<div class="page-header flex justify-between items-center mb-6">
    <div>
        <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">Pending Reviews</h1>
        <p style="color:var(--slate);font-size:14px;margin-top:4px;">Applications awaiting evaluator assignment and review.</p>
    </div>
</div>

<div class="page-content">
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="table-header">
                    <tr>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Applicant Name</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Scholarship</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Submitted On</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $app)
                    <tr class="table-row">
                        <td class="px-5 py-4">
                            <div class="font-semibold" style="color:var(--ink);">{{ $app->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $app->user->email ?? '' }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-semibold text-sm">{{ $app->scholarship->name ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="status-badge status-{{ str_replace('_', '-', $app->status) }}">
                                {{ str_replace('_', ' ', Str::title($app->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-600">
                            {{ $app->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="#" class="btn-primary !px-3 !py-1.5 text-xs">Assign Evaluator</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                            No pending reviews.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

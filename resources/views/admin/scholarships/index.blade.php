@extends('admin.layouts.admin')

@section('title', 'Scholarships Management')

@section('content')
<div class="page-header flex justify-between items-center mb-6">
    <div>
        <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">Manage Scholarships</h1>
        <p style="color:var(--slate);font-size:14px;margin-top:4px;">View, edit, and manage all scholarship listings.</p>
    </div>
    <a href="{{ route('admin.scholarships.create') }}" class="btn-primary flex items-center gap-2">
        <svg style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        Create New
    </a>
</div>

<div class="page-content">
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="table-header">
                    <tr>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Scholarship Name</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Applications</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($scholarships as $scholarship)
                    <tr class="table-row">
                        <td class="px-5 py-4">
                            <div class="font-semibold" style="color:var(--ink);">{{ $scholarship->name }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ Str::limit($scholarship->description, 50) }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="status-badge status-{{ $scholarship->status }}">
                                {{ ucfirst($scholarship->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-semibold">{{ $scholarship->applications_count ?? 0 }}</span> apps
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-600">
                            {{ $scholarship->deadline_date ? \Carbon\Carbon::parse($scholarship->deadline_date)->format('M d, Y') : 'No deadline' }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.scholarships.show', $scholarship->id) }}" class="btn-ghost !px-3 !py-1.5 text-xs">View</a>
                                <a href="{{ route('admin.scholarships.edit', $scholarship->id) }}" class="btn-ghost !px-3 !py-1.5 text-xs">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                            No scholarships found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($scholarships->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $scholarships->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

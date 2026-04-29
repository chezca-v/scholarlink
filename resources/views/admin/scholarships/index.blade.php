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

<div class="page-content" x-data="{ deleteModal: false, deleteId: null, deleteName: '' }">
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
                                {{ ucfirst(str_replace('_', ' ', $scholarship->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-semibold">{{ $scholarship->applications_count ?? 0 }}</span> apps
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-600">
                            {{ $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('M d, Y') : 'No deadline' }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.scholarships.show', $scholarship->id) }}" class="btn-ghost !px-3 !py-1.5 text-xs">View</a>
                                <a href="{{ route('admin.scholarships.edit', $scholarship->id) }}" class="btn-ghost !px-3 !py-1.5 text-xs">Edit</a>
                                <button type="button"
                                    @click="deleteModal = true; deleteId = {{ $scholarship->id }}; deleteName = '{{ addslashes($scholarship->name) }}'"
                                    class="btn-danger !px-3 !py-1.5 text-xs">
                                    Delete
                                </button>
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

    {{-- Delete Confirmation Modal --}}
    <div x-show="deleteModal" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="deleteModal = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- Modal Header --}}
            <div class="p-6 pb-4">
                <div class="flex items-start gap-4">
                    <div style="width:44px;height:44px;background:#FEE2E2;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:22px;height:22px;color:#DC2626;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/>
                            <path d="M9 6V4h6v2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-display font-bold text-lg" style="color:var(--ink);">Delete Scholarship</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Are you sure you want to delete
                            <span class="font-semibold" style="color:var(--ink);" x-text="'\"' + deleteName + '\"'"></span>?
                            This action <strong>cannot be undone</strong> and will remove all associated applications.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" @click="deleteModal = false" class="btn-ghost">Cancel</button>
                <form :action="'/admin/scholarships/' + deleteId" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger font-semibold">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

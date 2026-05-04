@extends('admin.layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="page-header mb-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span style="font-size:13px;color:var(--slate);">Admin</span>
                <span style="color:var(--muted);">/</span>
                <span style="font-size:13px;color:var(--ink);">Users</span>
            </div>
            <h1 class="font-display font-bold text-2xl" style="color:var(--ink);">User Management</h1>
            <p style="color:var(--slate);font-size:14px;margin-top:4px;">Manage platform access, roles, and user accounts.</p>
        </div>

    </div>
</div>

<div class="page-content">
    {{-- Search and Filters --}}
    <div class="card p-4 mb-4 flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Search by name or email..." class="input-field !pl-9 !py-2 w-full">
        </div>
        <div class="flex items-center gap-3">
            <select class="input-field !py-2 !pr-8 min-w-[140px]">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="student">Student</option>
                <option value="provider">Provider</option>
            </select>
            <button class="btn-secondary !py-2 !px-3">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            </button>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-8">
                            <input type="checkbox" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)]">
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="table-row hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)]">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-sm"
                                    style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                                    {{ strtoupper(substr($user->name ?? ($user->first_name ?? 'U'), 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-sm" style="color:var(--ink);">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Joined {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Unknown' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-700">{{ $user->email }}</div>
                            @if(!empty($user->phone))
                                <div class="text-xs text-slate-500 mt-0.5">{{ $user->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                style="{{ $user->role === 'admin' ? 'background:#F3E8FF;color:#7E22CE;' : ($user->role === 'provider' ? 'background:#E0F2FE;color:#0369A1;' : 'background:#F1F5F9;color:#475569;') }}">
                                {{ ucfirst($user->role ?? 'Student') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="status-badge status-active">Active</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-1.5 text-gray-400 hover:text-[var(--primary)] transition-colors rounded-lg hover:bg-blue-50">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <button class="p-1.5 text-gray-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <p class="font-medium text-gray-600">No users found</p>
                                <p class="text-sm mt-1">There are no user accounts in the system.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
            <span class="text-sm text-gray-500">Showing {{ count($users) }} users</span>
            <div class="flex gap-1">
                <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-400 cursor-not-allowed">Previous</button>
                <button class="px-3 py-1 text-sm border border-gray-200 rounded text-gray-600 hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection

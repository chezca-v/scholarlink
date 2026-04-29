<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Organization;
use App\Models\User;
use App\Models\ActivityLog;

class SuperadminController extends Controller
{
    // -------------------------------------------------------------------------
    // DASHBOARD
    // -------------------------------------------------------------------------

    /**
     * GET /superadmin/dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_organizations' => Organization::count(),
            'total_admins'        => User::where('role', 'admin')->count(),
            'total_logs'          => ActivityLog::count(),
            'inactive_orgs'       => Organization::where('is_active', false)->count(),
        ];

        $recentLogs = ActivityLog::with('user')
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentLogs'));
    }

    // -------------------------------------------------------------------------
    // ORGANIZATIONS — Full CRUD
    // -------------------------------------------------------------------------

    /**
     * List all organizations.
     * GET /superadmin/organizations
     */
    public function organizations()
    {
        $organizations = Organization::withCount('users')
            ->latest()
            ->paginate(15);

        return view('superadmin.organizations', compact('organizations'));
    }

    /**
     * Create a new organization.
     * POST /superadmin/organizations
     */
    public function storeOrganization(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:organizations,email'],
            'website'   => ['nullable', 'url', 'max:255'],
            'address'   => ['nullable', 'string'],
            'logo_url'  => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $org = Organization::create($validated);

        ActivityLog::record(
            action: 'organization_created',
            targetType: 'Organization',
            targetId: $org->id,
        );

        return redirect()
            ->route('superadmin.organizations')
            ->with('success', "Organization \"{$org->name}\" created successfully.");
    }

    /**
     * Update an existing organization.
     * PUT /superadmin/organizations/{id}
     */
    public function updateOrganization(Request $request, $id)
    {
        $org = Organization::findOrFail($id);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', "unique:organizations,email,{$id}"],
            'website'   => ['nullable', 'url', 'max:255'],
            'address'   => ['nullable', 'string'],
            'logo_url'  => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $org->update($validated);

        ActivityLog::record(
            action: 'organization_updated',
            targetType: 'Organization',
            targetId: $org->id,
        );

        return redirect()
            ->route('superadmin.organizations')
            ->with('success', "Organization \"{$org->name}\" updated.");
    }

    /**
     * Soft-delete an organization.
     * DELETE /superadmin/organizations/{id}
     */
    public function destroyOrganization($id)
    {
        $org = Organization::findOrFail($id);
        $org->delete();

        ActivityLog::record(
            action: 'organization_deleted',
            targetType: 'Organization',
            targetId: $org->id,
        );

        return redirect()
            ->route('superadmin.organizations')
            ->with('success', "Organization \"{$org->name}\" deleted.");
    }

    /**
     * Toggle an organization's active status.
     * POST /superadmin/organizations/{id}/toggle
     */
    public function toggleOrganization($id)
    {
        $org = Organization::findOrFail($id);
        $org->update(['is_active' => !$org->is_active]);

        $status = $org->is_active ? 'activated' : 'deactivated';

        ActivityLog::record(
            action: "organization_{$status}",
            targetType: 'Organization',
            targetId: $org->id,
        );

        return redirect()
            ->route('superadmin.organizations')
            ->with('success', "Organization \"{$org->name}\" {$status}.");
    }

    // -------------------------------------------------------------------------
    // ADMIN ROLE MANAGEMENT
    // -------------------------------------------------------------------------

    /**
     * List all admin users.
     * GET /superadmin/admins
     */
    public function admins()
    {
        $admins = User::with('organization')
            ->where('role', 'admin')
            ->latest()
            ->paginate(15);

        $organizations = Organization::where('is_active', true)->get(['id', 'name']);

        return view('superadmin.admins', compact('admins', 'organizations'));
    }

    /**
     * Create a new admin user.
     * POST /superadmin/admins
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
        ]);

        $admin = User::create([
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'],
            'email'           => $validated['email'],
            'password'        => Hash::make($validated['password']),
            'role'            => 'admin',
            'organization_id' => $validated['organization_id'] ?? null,
            'is_active'       => true,
        ]);

        ActivityLog::record(
            action: 'admin_created',
            targetType: 'User',
            targetId: $admin->id,
        );

        return redirect()
            ->route('superadmin.admins')
            ->with('success', "Admin \"{$admin->first_name} {$admin->last_name}\" created.");
    }

    /**
     * Update an admin user's details or role.
     * PUT /superadmin/admins/{id}
     */
    public function updateAdmin(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', "unique:users,email,{$id}"],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'is_active'       => ['boolean'],
        ]);

        $admin->update($validated);

        ActivityLog::record(
            action: 'admin_updated',
            targetType: 'User',
            targetId: $admin->id,
        );

        return redirect()
            ->route('superadmin.admins')
            ->with('success', "Admin \"{$admin->first_name} {$admin->last_name}\" updated.");
    }

    /**
     * Soft-delete (remove) an admin user.
     * DELETE /superadmin/admins/{id}
     */
    public function destroyAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        ActivityLog::record(
            action: 'admin_deleted',
            targetType: 'User',
            targetId: $admin->id,
        );

        return redirect()
            ->route('superadmin.admins')
            ->with('success', "Admin removed successfully.");
    }

    // -------------------------------------------------------------------------
    // ACTIVITY LOGS
    // -------------------------------------------------------------------------

    /**
     * View all system activity logs with filtering.
     * GET /superadmin/logs
     */
    public function logs(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action keyword
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Filter by target type (e.g. Organization, User, Application)
        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25)->withQueryString();

        // For the filter dropdown
        $users       = User::select('id', 'first_name', 'last_name', 'role')->get();
        $targetTypes = ActivityLog::select('target_type')
            ->whereNotNull('target_type')
            ->distinct()
            ->pluck('target_type');

        return view('superadmin.logs', compact('logs', 'users', 'targetTypes'));
    }

    // -------------------------------------------------------------------------
    // SETTINGS
    // -------------------------------------------------------------------------

    /**
     * GET /superadmin/settings
     */
    public function settings()
    {
        return view('superadmin.settings');
    }
}

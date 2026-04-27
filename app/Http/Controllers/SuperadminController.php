<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Organization;
use App\Models\User;
use App\Models\Application;
use App\Models\Scholarship;
use App\Models\ActivityLog;
use Carbon\Carbon;

class SuperadminController extends Controller
{
    // ─────────────────────────────────────────────
    // Dashboard
    // ─────────────────────────────────────────────

    public function dashboard()
    {
        $now = Carbon::now();

        // Nationwide stats
        $totalOrganizations = Organization::query()->where('is_active', true)->count();
        $totalAdmins        = User::query()->where('role', 'admin')->where('is_active', true)->count();
        $totalApplicants    = User::query()->where('role', 'applicant')->count();
        $totalScholarships  = Scholarship::query()->count();
        $totalApplications  = Application::query()->count();
        $totalApproved      = Application::query()->where('status', 'approved')->count();
        $approvalRate       = $totalApplications > 0
            ? round(($totalApproved / $totalApplications) * 100, 1)
            : 0;

        // Org performance comparison
        $orgPerformance = Organization::query()
            ->where('is_active', true)
            ->with(['users' => function ($query) {
                $query->where('role', 'admin');
            }])
            ->get()
            ->map(function ($org) {
                $adminIds = $org->users->pluck('id');
                return [
                    'org'          => $org,
                    'admin_count'  => $adminIds->count(),
                ];
            });

        // Fraud / conflict alert feed
        $fraudAlerts = Application::query()
            ->where('conflict_flag', true)
            ->with(['applicant', 'scholarship'])
            ->latest('submitted_at')
            ->take(10)
            ->get();

        // System health indicators
        $pendingApplications  = Application::query()
            ->whereIn('status', ['pending', 'under_review'])
            ->count();
        $unassignedApplications = Application::query()
            ->whereIn('status', ['pending', 'under_review'])
            ->doesntHave('evaluations')
            ->where('created_at', '<=', $now->copy()->subDays(4))
            ->count();
        $inactiveAdmins = User::query()
            ->where('role', 'admin')
            ->where('is_active', false)
            ->count();

        // Recent activity logs
        $recentLogs = ActivityLog::query()
            ->with('user')
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('superadmin.dashboard', [
            'now'                    => $now,
            'totalOrganizations'     => $totalOrganizations,
            'totalAdmins'            => $totalAdmins,
            'totalApplicants'        => $totalApplicants,
            'totalScholarships'      => $totalScholarships,
            'totalApplications'      => $totalApplications,
            'totalApproved'          => $totalApproved,
            'approvalRate'           => $approvalRate,
            'orgPerformance'         => $orgPerformance,
            'fraudAlerts'            => $fraudAlerts,
            'pendingApplications'    => $pendingApplications,
            'unassignedApplications' => $unassignedApplications,
            'inactiveAdmins'         => $inactiveAdmins,
            'recentLogs'             => $recentLogs,
        ]);
    }

    // ─────────────────────────────────────────────
    // Organizations
    // ─────────────────────────────────────────────

    public function organizations()
    {
        $organizations = Organization::query()
            ->withCount(['users' => function ($query) {
                $query->where('role', 'admin');
            }])
            ->latest()
            ->paginate(15);

        return view('superadmin.organizations', [
            'organizations' => $organizations,
        ]);
    }

    public function storeOrganization(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:organizations,email'],
            'website'   => ['nullable', 'url', 'max:255'],
            'address'   => ['nullable', 'string', 'max:500'],
            'logo_url'  => ['nullable', 'string', 'max:255'],
        ]);

        Organization::query()->create([
            'name'      => $request->name,
            'email'     => $request->email,
            'website'   => $request->website,
            'address'   => $request->address,
            'logo_url'  => $request->logo_url,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.organizations')
            ->with('success', 'Organization created successfully.');
    }

    public function updateOrganization(Request $request, $id)
    {
        $organization = Organization::query()->findOrFail($id);

        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:organizations,email,' . $id],
            'website'   => ['nullable', 'url', 'max:255'],
            'address'   => ['nullable', 'string', 'max:500'],
            'logo_url'  => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $organization->update($request->only([
            'name', 'email', 'website', 'address', 'logo_url', 'is_active'
        ]));

        return redirect()->route('superadmin.organizations')
            ->with('success', 'Organization updated successfully.');
    }

    public function destroyOrganization($id)
    {
        $organization = Organization::query()->findOrFail($id);

        // Soft delete — admins under this org stay but org is deactivated
        $organization->delete();

        return redirect()->route('superadmin.organizations')
            ->with('success', 'Organization removed successfully.');
    }

    // ─────────────────────────────────────────────
    // Admin Accounts
    // ─────────────────────────────────────────────

    public function admins()
    {
        $admins = User::query()
            ->where('role', 'admin')
            ->with('organization')
            ->latest()
            ->paginate(15);

        $organizations = Organization::query()
            ->where('is_active', true)
            ->get(['id', 'name']);

        return view('superadmin.admins', [
            'admins'        => $admins,
            'organizations' => $organizations,
        ]);
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
            'organization_id' => ['required', 'exists:organizations,id'],
        ]);

        User::query()->create([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'admin',
            'organization_id'   => $request->organization_id,
            'is_active'         => true,
            'email_verified_at' => Carbon::now(),
        ]);

        return redirect()->route('superadmin.admins')
            ->with('success', 'Admin account created successfully.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::query()->where('role', 'admin')->findOrFail($id);

        $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'unique:users,email,' . $id],
            'organization_id' => ['required', 'exists:organizations,id'],
        ]);

        $admin->update($request->only([
            'first_name', 'last_name', 'email', 'organization_id'
        ]));

        return redirect()->route('superadmin.admins')
            ->with('success', 'Admin account updated successfully.');
    }

    public function deactivateAdmin($id)
    {
        $admin = User::query()->where('role', 'admin')->findOrFail($id);
        $admin->update(['is_active' => false]);

        return redirect()->route('superadmin.admins')
            ->with('success', 'Admin account deactivated.');
    }

    public function reassignAdmin(Request $request, $id)
    {
        $admin = User::query()->where('role', 'admin')->findOrFail($id);

        $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
        ]);

        $admin->update(['organization_id' => $request->organization_id]);

        return redirect()->route('superadmin.admins')
            ->with('success', 'Admin reassigned successfully.');
    }

    // ─────────────────────────────────────────────
    // Logs
    // ─────────────────────────────────────────────

    public function logs(Request $request)
    {
        $query = ActivityLog::query()->with('user');

        // Filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest('created_at')->paginate(20);

        $users = User::query()
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'role']);

        $actions = ActivityLog::query()
            ->distinct()
            ->pluck('action');

        return view('superadmin.logs', [
            'logs'    => $logs,
            'users'   => $users,
            'actions' => $actions,
        ]);
    }

    // ─────────────────────────────────────────────
    // Settings
    // ─────────────────────────────────────────────

    public function settings()
    {
        return view('superadmin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Feature flags and RBAC permissions matrix
        // Will expand once feature flag system is defined
        return redirect()->route('superadmin.settings')
            ->with('success', 'Settings updated successfully.');
    }
}

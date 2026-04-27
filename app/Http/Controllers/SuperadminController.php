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
use App\Models\Application;
use App\Models\Scholarship;
use Illuminate\Support\Facades\DB;

class SuperadminController extends Controller
{
    // ─────────────────────────────────────────────
    // Dashboard
    // ─────────────────────────────────────────────

    public function dashboard()
    {
        // 1. Stats
        $orgCount = Organization::count();
        $applicantCount = User::where('role', 'applicant')->where('is_active', true)->count();
        $applicationCount = Application::count();
        $fraudAlertCount = ActivityLog::where('action', 'like', '%fraud%')->orWhere('action', 'like', '%alert%')->count();

        $stats = [
            ['icon' => '🏛️', 'icon_bg' => '#E8F8F0',          'label' => 'Organizations',      'value' => number_format($orgCount),    'delta' => 'Total registered',   'delta_class' => 'neutral'],
            ['icon' => '🎓', 'icon_bg' => '#FDF4E3',          'label' => 'Active Applicants',  'value' => number_format($applicantCount), 'delta' => 'Verified users', 'delta_class' => 'neutral'],
            ['icon' => '📋', 'icon_bg' => 'rgba(15,76,92,.08)','label' => 'Total Applications', 'value' => number_format($applicationCount),'delta' => 'Across all orgs',   'delta_class' => 'neutral'],
            ['icon' => '⚠️', 'icon_bg' => '#FEF2F2',          'label' => 'Fraud Alerts',       'value' => number_format($fraudAlertCount),     'delta' => 'System detected',    'delta_class' => $fraudAlertCount > 0 ? 'down' : 'neutral'],
        ];

        // 2. Org Performance (Top 6 by applications count)
        $organizations = Organization::with('users')->get();
        $orgPerformanceRaw = [];
        
        foreach ($organizations as $org) {
            $userIds = $org->users->pluck('id');
            $scholarshipIds = Scholarship::whereIn('created_by', $userIds)->pluck('id');
            
            $appsQuery = Application::whereIn('scholarship_id', $scholarshipIds);
            $count = $appsQuery->count();
            $approvedCount = (clone $appsQuery)->where('status', 'approved')->count();
            $pct = $count > 0 ? round(($approvedCount / $count) * 100) : 0;
            
            if ($count > 0) {
                $orgPerformanceRaw[] = [
                    'name' => $org->name,
                    'pct' => $pct,
                    'count' => $count,
                    'color' => 'linear-gradient(90deg,#0F4C5C,#2A8FA0)'
                ];
            }
        }
        
        usort($orgPerformanceRaw, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        $orgPerformance = array_map(function($item) {
            $item['count'] = number_format($item['count']);
            return $item;
        }, array_slice($orgPerformanceRaw, 0, 6));

        // 3. Fraud Alerts
        $recentLogs = ActivityLog::where('action', 'like', '%fraud%')->orWhere('action', 'like', '%alert%')->latest()->take(4)->get();
        $fraudAlerts = [];
        foreach($recentLogs as $log) {
            $fraudAlerts[] = [
                'dot' => 'red',
                'title' => $log->action,
                'meta' => ($log->user ? $log->user->first_name . ' · ' : '') . $log->created_at->diffForHumans(),
            ];
        }
        
        if (empty($fraudAlerts)) {
            $fraudAlerts[] = [
                'dot' => 'green',
                'title' => 'No active alerts',
                'meta' => 'System is running smoothly',
            ];
        }

        // 4. System Health
        $systemHealth = [
            ['label' => 'API Uptime',  'value' => '99.9%', 'status' => 'ok',   'status_text' => '● Operational'],
            ['label' => 'SMS Gateway', 'value' => 'Online', 'status' => 'ok',   'status_text' => '● ESP32 Active'],
            ['label' => 'DB Storage',  'value' => 'Healthy', 'status' => 'ok', 'status_text' => '● Good'],
            ['label' => 'AI Matching', 'value' => 'Active', 'status' => 'ok',   'status_text' => '● Gemini API'],
        ];

        // 5. Chart Months
        $chartMonths = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $year = $date->format('Y');
            $monthNum = $date->format('m');
            
            $appsCount = Application::whereYear('created_at', $year)->whereMonth('created_at', $monthNum)->count();
            $chartMonths[] = [
                'month' => $date->format('M'),
                'raw_count' => $appsCount,
            ];
        }

        $maxApps = max(array_column($chartMonths, 'raw_count'));
        $maxApps = $maxApps > 0 ? $maxApps : 1;

        foreach ($chartMonths as &$monthData) {
            $monthData['pct'] = round(($monthData['raw_count'] / $maxApps) * 100);
            $monthData['accent'] = $monthData['month'] === now()->format('M');
        }

        return view('superadmin.dashboard', compact(
            'stats',
            'orgPerformance',
            'fraudAlerts',
            'fraudAlertCount',
            'systemHealth',
            'chartMonths'
        ));
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

    public function admins(Request $request)
    {
        // 1. Stats
        $totalAdmins = User::where('role', 'admin')->count();
        $totalOrgs = Organization::count();
        $activeAdmins = User::where('role', 'admin')->where('is_active', true)->count();
        $newAdminsToday = User::where('role', 'admin')->whereDate('created_at', Carbon::today())->count();
        $deactivatedAdmins = User::where('role', 'admin')->where('is_active', false)->count();
        $unassignedOrgs = Organization::whereDoesntHave('users', function ($query) {
            $query->where('role', 'admin');
        })->count();

        // 2. Admin Query & Filters
        $query = User::query()
            ->where('role', 'admin')
            ->with('organization');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'deactivated') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        $admins = $query->latest()->paginate(15)->withQueryString();

        // Post-process items
        $admins->getCollection()->transform(function ($admin) {
            $admin->full_name = $admin->first_name . ' ' . $admin->last_name;
            $admin->status = $admin->is_active ? 'active' : 'deactivated';
            $admin->managed_scholars_count = Application::where('status', 'approved')
                ->whereHas('scholarship', function ($q) use ($admin) {
                    $q->where('created_by', $admin->id);
                })->count();
            return $admin;
        });

        // 3. Organizations (for filters and creation modal)
        $organizations = Organization::query()
            ->withCount(['users as admin_count' => function ($query) {
                $query->where('role', 'admin');
            }])
            ->where('is_active', true)
            ->get()
            ->map(function ($org) {
                $org->admin = $org->admin_count > 0;
                return $org;
            });

        return view('superadmin.admin', compact(
            'totalAdmins',
            'totalOrgs',
            'activeAdmins',
            'newAdminsToday',
            'deactivatedAdmins',
            'unassignedOrgs',
            'admins',
            'organizations'
        ));
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

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Action Dropdown Filter
        if ($request->filled('action')) {
            $action = $request->action;
            $query->where('action', 'like', "%{$action}%");
        }

        // 3. User Role Filter
        if ($request->filled('user_role')) {
            $role = $request->user_role;
            $query->whereHas('user', function($q) use ($role) {
                $q->where('role', $role);
            });
        }

        // 4. Date Filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 5. Quick Filters
        if ($request->filled('quick')) {
            $quick = $request->quick;
            if ($quick === 'login') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%login%')
                      ->orWhere('action', 'like', '%logout%');
                });
            } elseif ($quick === 'errors') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%error%')
                      ->orWhere('action', 'like', '%failed%');
                });
            } elseif ($quick === 'data_changes') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%create%')
                      ->orWhere('action', 'like', '%update%')
                      ->orWhere('action', 'like', '%delete%');
                });
            } elseif ($quick === 'fraud') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%fraud%')
                      ->orWhere('action', 'like', '%alert%');
                });
            }
        }

        // 6. Counts Calculation (on filtered query)
        $successCount = (clone $query)->where(function($q) {
            $q->where('action', 'not like', '%error%')
              ->where('action', 'not like', '%failed%')
              ->where('action', 'not like', '%fraud%')
              ->where('action', 'not like', '%alert%');
        })->count();

        $errorCount = (clone $query)->where(function($q) {
            $q->where('action', 'like', '%error%')
              ->orWhere('action', 'like', '%failed%');
        })->count();

        $warnCount = (clone $query)->where(function($q) {
            $q->where('action', 'like', '%fraud%')
              ->orWhere('action', 'like', '%alert%');
        })->count();

        // 7. Paginate and Format
        $logs = $query->latest('created_at')->paginate(20)->withQueryString();

        $logs->getCollection()->transform(function ($log) {
            $actionLower = strtolower($log->action);
            
            // Defaults
            $log->icon = '📋';
            $log->badge_color = 'teal';
            $log->icon_bg = 'rgba(15,76,92,.08)';
            $log->action_type = 'system';
            
            if (str_contains($actionLower, 'login') || str_contains($actionLower, 'logout')) {
                $log->icon = '🔑';
                $log->badge_color = 'gray';
                $log->icon_bg = '#F3F4F6';
                $log->action_type = 'auth';
            } elseif (str_contains($actionLower, 'error') || str_contains($actionLower, 'fail')) {
                $log->icon = '✖️';
                $log->badge_color = 'red';
                $log->icon_bg = '#FEF2F2';
                $log->action_type = 'error';
            } elseif (str_contains($actionLower, 'fraud') || str_contains($actionLower, 'alert')) {
                $log->icon = '⚠️';
                $log->badge_color = 'red';
                $log->icon_bg = '#FEF2F2';
                $log->action_type = 'security';
            } elseif (str_contains($actionLower, 'create') || str_contains($actionLower, 'add')) {
                $log->icon = '➕';
                $log->badge_color = 'green';
                $log->icon_bg = '#E8F8F0';
                $log->action_type = 'create';
            } elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit')) {
                $log->icon = '✏️';
                $log->badge_color = 'yellow';
                $log->icon_bg = '#FEF3C7';
                $log->action_type = 'update';
            } elseif (str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove')) {
                $log->icon = '🗑️';
                $log->badge_color = 'red';
                $log->icon_bg = '#FEF2F2';
                $log->action_type = 'delete';
            } elseif (str_contains($actionLower, 'export') || str_contains($actionLower, 'download')) {
                $log->icon = '⬇️';
                $log->badge_color = 'teal';
                $log->icon_bg = 'rgba(15,76,92,.08)';
                $log->action_type = 'export';
            }

            // Action Label
            if ($log->user) {
                $log->action_label = $log->user->first_name . ' ' . $log->user->last_name . ' ' . strtolower($log->action);
            } else {
                $log->action_label = ucfirst($log->action);
            }

            // Extra Meta
            $meta = [];
            if ($log->target_type) {
                $meta[] = "Target: " . class_basename($log->target_type) . ($log->target_id ? " (#{$log->target_id})" : "");
            }
            if ($log->user && $log->user->role) {
                $meta[] = "Role: " . ucfirst($log->user->role);
            }
            $log->extra_meta = empty($meta) ? null : implode(' · ', $meta);

            return $log;
        });

        // 8. Dynamic Options for View Filters
        $actions = ActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $roles = User::query()
            ->select('role')
            ->distinct()
            ->orderBy('role')
            ->pluck('role');

        return view('superadmin.logs', compact(
            'logs',
            'successCount',
            'errorCount',
            'warnCount',
            'actions',
            'roles'
        ));
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

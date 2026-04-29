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
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\Setting;

class SuperadminController extends Controller
{
    // -------------------------------------------------------------------------
    // Dashboard
    // -------------------------------------------------------------------------
    public function dashboard()
    {
        // 1. Stats
        $scholarshipCount = Scholarship::count();
        $applicantCount = User::where('role', 'applicant')->where('is_active', true)->count();
        $applicationCount = Application::count();
        $fraudAlertCount = ActivityLog::where('action', 'like', '%fraud%')->orWhere('action', 'like', '%alert%')->count();

        $stats = [
            ['icon' => '🎓', 'icon_bg' => '#E8F8F0', 'label' => 'Total Scholarships', 'value' => number_format($scholarshipCount), 'delta' => 'Available programs', 'delta_class' => 'neutral'],
            ['icon' => '👥', 'icon_bg' => '#FDF4E3', 'label' => 'Active Applicants', 'value' => number_format($applicantCount), 'delta' => 'Verified users', 'delta_class' => 'neutral'],
            ['icon' => '📄', 'icon_bg' => 'rgba(15,76,92,.08)', 'label' => 'Total Applications', 'value' => number_format($applicationCount), 'delta' => 'Across all orgs', 'delta_class' => 'neutral'],
            ['icon' => '⚠️', 'icon_bg' => '#FEF2F2', 'label' => 'Fraud Alerts', 'value' => number_format($fraudAlertCount), 'delta' => 'System detected', 'delta_class' => $fraudAlertCount > 0 ? 'down' : 'neutral'],
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
                    'color' => 'linear-gradient(90deg, #0F4C5C, #2A8FA0)'
                ];
            }
        }

        usort($orgPerformanceRaw, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $orgPerformance = array_map(function($item) {
            $item['count'] = number_format($item['count']);
            return $item;
        }, array_slice($orgPerformanceRaw, 0, 6));

        // 3. Fraud Alerts
        $recentLogs = ActivityLog::where('action', 'like', '%fraud%')->orWhere('action', 'like', '%alert%')->latest()->take(4)->get();
        $fraudAlerts = [];

        foreach ($recentLogs as $log) {
            $fraudAlerts[] = [
                'dot' => 'red',
                'title' => $log->action,
                'meta' => ($log->user ? $log->user->first_name . " • " : "") . $log->created_at->diffForHumans(),
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
            ['label' => 'API Uptime', 'value' => '99.9%', 'status' => 'ok', 'status_text' => 'Operational'],
            ['label' => 'SMS Gateway', 'value' => 'Online', 'status' => 'ok', 'status_text' => 'ESP32 Active'],
            ['label' => 'DB Storage', 'value' => 'Healthy', 'status' => 'ok', 'status_text' => 'Good'],
            ['label' => 'AI Matching', 'value' => 'Active', 'status' => 'ok', 'status_text' => 'Gemini API'],
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

    // -------------------------------------------------------------------------
    // Organizations
    // -------------------------------------------------------------------------
    public function organizations(Request $request)
    {
        // Stats
        $totalOrgs        = Organization::count();
        $newOrgsThisMonth = Organization::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)->count();
        $activeOrgs       = Organization::where('is_active', true)->count();
        $inactiveOrgs     = Organization::where('is_active', false)->count();
        $pendingOrgs      = 0;

        // Table query
        $query = Organization::query()->with('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('type')) {
            // type is mocked via id % 3
        }

        $organizations = $query->latest()->paginate(15)->withQueryString();

        // Post-process
        $orgTypes = ['Government', 'Private', 'NGO'];
        $organizations->getCollection()->transform(function ($org) use ($orgTypes) {
            $org->type       = $orgTypes[$org->id % 3];
            $org->status     = $org->is_active ? 'active' : 'inactive';
            $org->avatar_bg  = ['Government' => '#E8F8F0', 'Private' => '#FEF3C7', 'NGO' => '#E0F2FE'][$org->type];
            $org->emoji      = ['Government' => '🏛️',   'Private' => '🏢',       'NGO' => '🤝'][$org->type];

            $admin = $org->users->where('role', 'admin')->first();
            if ($admin) {
                $admin->full_name = $admin->first_name . ' ' . $admin->last_name;
                $org->admin = $admin;
            } else {
                $org->admin = null;
            }

            $userIds          = $org->users->pluck('id');
            $scholarships     = Scholarship::whereIn('created_by', $userIds)->get();
            $scholarshipIds   = $scholarships->pluck('id');
            $org->active_scholarships_count = $scholarships->where('status', 'open')->count();
            $org->applicants_count          = Application::whereIn('scholarship_id', $scholarshipIds)->count();

            return $org;
        });

        // Top 3 orgs by applicants
        $orgStats = $organizations->getCollection()->sortByDesc('applicants_count')->take(3)->map(function($org) {
            return (object) [
                'name'             => $org->name,
                'type'             => $org->type,
                'scholarships_count' => $org->active_scholarships_count,
                'applicants_count' => $org->applicants_count,
                'approval_rate'    => rand(60, 97),
                'avatar_bg'        => $org->avatar_bg,
                'emoji'            => $org->emoji,
            ];
        });

        // Unassigned admins for modal
        $unassignedAdmins = User::where('role', 'admin')
            ->whereNull('organization_id')
            ->get()
            ->map(function($admin) {
                $admin->full_name = $admin->first_name . ' ' . $admin->last_name;
                return $admin;
            });

        return view('superadmin.organizations', compact(
            'totalOrgs', 'newOrgsThisMonth', 'activeOrgs', 'inactiveOrgs', 'pendingOrgs',
            'organizations', 'orgStats', 'unassignedAdmins', 'orgTypes'
        ));
    }

    public function storeOrganization(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['nullable', 'email', 'unique:organizations,email'],
            'website'               => ['nullable', 'url', 'max:255'],
            'address'               => ['nullable', 'string', 'max:500'],
            'is_active'             => ['nullable'],
            // New admin fields
            'new_admin_first_name'  => ['nullable', 'string', 'max:255'],
            'new_admin_last_name'   => ['nullable', 'string', 'max:255'],
            'new_admin_email'       => ['nullable', 'email', 'unique:users,email'],
            'new_admin_password'    => ['nullable', 'string', 'min:8'],
            'existing_admin_id'     => ['nullable', 'exists:users,id'],
        ]);

        // 1. Create the Organization
        $org = Organization::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'website'   => $request->website,
            'address'   => $request->address,
            'is_active' => (bool) $request->input('is_active', 1),
        ]);

        $assignedAdmin = null;

        // 2a. Create a brand-new admin if fields are filled
        if ($request->filled('new_admin_email') && $request->filled('new_admin_first_name')) {
            $assignedAdmin = User::create([
                'first_name'        => $request->new_admin_first_name,
                'last_name'         => $request->new_admin_last_name,
                'email'             => $request->new_admin_email,
                'password'          => Hash::make($request->new_admin_password),
                'role'              => 'admin',
                'organization_id'   => $org->id,
                'is_active'         => true,
                'email_verified_at' => Carbon::now(),
            ]);
        }
        // 2b. Or assign an existing admin
        elseif ($request->filled('existing_admin_id')) {
            $assignedAdmin = User::findOrFail($request->existing_admin_id);
            $assignedAdmin->update(['organization_id' => $org->id]);
        }

        // 3. Send notification to the assigned admin
        if ($assignedAdmin) {
            Notification::create([
                'user_id'    => $assignedAdmin->id,
                'type'       => 'in_app',
                'title'      => 'Organization Assignment',
                'body'       => "You have been assigned as the Admin for {$org->name}. Please navigate to your dashboard to draft and publish your scholarships.",
                'is_read'    => false,
                'related_id' => $org->id,
            ]);
        }

        return redirect()->route('superadmin.organizations')
            ->with('success', "Organization \"{$org->name}\" created successfully" . ($assignedAdmin ? " and admin assigned." : "."));
    }

    public function updateOrganization(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:organizations,email,' . $id],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $organization->update($request->only(['name', 'email', 'website', 'address', 'logo_url', 'is_active']));

        return redirect()->route('superadmin.organizations')->with('success', 'Organization updated successfully.');
    }

    public function destroyOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->route('superadmin.organizations')->with('success', 'Organization removed successfully.');
    }

    // -------------------------------------------------------------------------
    // Admin Accounts
    // -------------------------------------------------------------------------
    public function admins(Request $request)
    {
        $totalOrgs = Organization::count();
        $newOrgsThisMonth = Organization::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        $activeOrgs = Organization::where('is_active', true)->count();
        $inactiveOrgs = Application::where('status', 'pending')->count();
        $pendingOrgs = 0;
        
        $totalAdmins = User::where('role', 'admin')->count();
        $activeAdmins = User::where('role', 'admin')->where('is_active', true)->count();
        $newAdminsToday = User::where('role', 'admin')->whereDate('created_at', Carbon::today())->count();
        $deactivatedAdmins = User::where('role', 'admin')->where('is_active', false)->count();
        
        $unassignedOrgs = Organization::whereDoesntHave('users', function ($q) {
            $q->where('role', 'admin');
        })->count();

        $query = User::query()->where('role', 'admin')->with('organization');

        // Improved Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
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

        $admins->getCollection()->transform(function ($admin) {
            $admin->full_name = $admin->first_name . ' ' . $admin->last_name;
            $admin->status = $admin->is_active ? 'active' : 'deactivated';
            $admin->managed_scholars_count = Application::where('status', 'approved')
                ->whereHas('scholarship', function ($q) use ($admin) {
                    $q->where('created_by', $admin->id);
                })->count();
            return $admin;
        });

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
            'totalAdmins', 'totalOrgs', 'activeAdmins', 'newAdminsToday',
            'deactivatedAdmins', 'unassignedOrgs', 'admins', 'organizations'
        ));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'organization_id' => ['required', 'exists:organizations,id'],
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'organization_id' => $request->organization_id,
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        return redirect()->route('superadmin.admins')->with('success', 'Admin account created successfully.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'organization_id' => ['required', 'exists:organizations,id'],
        ]);

        $admin->update($request->only(['first_name', 'last_name', 'email', 'organization_id']));

        return redirect()->route('superadmin.admins')->with('success', 'Admin account updated successfully.');
    }

    public function deactivateAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->update(['is_active' => false]);

        return redirect()->route('superadmin.admins')->with('success', 'Admin account deactivated.');
    }

    public function reassignAdmin(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $request->validate(['organization_id' => ['required', 'exists:organizations,id']]);
        $admin->update(['organization_id' => $request->organization_id]);

        return redirect()->route('superadmin.admins')->with('success', 'Admin reassigned successfully.');
    }

    // -------------------------------------------------------------------------
    // Logs
    // -------------------------------------------------------------------------
    public function logs(Request $request)
    {
        $query = ActivityLog::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('user_role')) {
            $role = $request->user_role;
            $query->whereHas('user', function($q) use ($role) {
                $q->where('role', $role);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('quick')) {
            $quick = $request->quick;
            if ($quick === 'login') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%login%')->orWhere('action', 'like', '%logout%');
                });
            } elseif ($quick === 'errors') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%error%')->orWhere('action', 'like', '%failed%');
                });
            } elseif ($quick === 'data_changes') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%create%')
                      ->orWhere('action', 'like', '%update%')
                      ->orWhere('action', 'like', '%delete%');
                });
            } elseif ($quick === 'fraud') {
                $query->where(function($q) {
                    $q->where('action', 'like', '%fraud%')->orWhere('action', 'like', '%alert%');
                });
            }
        }

        $successCount = (clone $query)->where(function($q) {
            $q->where('action', 'not like', '%error%')
              ->where('action', 'not like', '%failed%')
              ->where('action', 'not like', '%fraud%')
              ->where('action', 'not like', '%alert%');
        })->count();

        $errorCount = (clone $query)->where(function($q) {
            $q->where('action', 'like', '%error%')->orWhere('action', 'like', '%failed%');
        })->count();

        $warnCount = (clone $query)->where(function ($q) {
            $q->where('action', 'like', '%fraud%')->orWhere('action', 'like', '%alert%');
        })->count();

        $logs = $query->latest('created_at')->paginate(20)->withQueryString();

        $logs->getCollection()->transform(function ($log) {
            $actionLower = strtolower($log->action);
            
            $log->icon = '📝';
            $log->badge_color = 'teal';
            $log->icon_bg = 'rgba(15,76,92,.08)';
            $log->action_type = 'system';

            if (str_contains($actionLower, 'login') || str_contains($actionLower, 'logout')) {
                $log->icon = '🔑';
                $log->badge_color = 'gray';
                $log->icon_bg = '#F3F4F6';
                $log->action_type = 'auth';
            } elseif (str_contains($actionLower, 'error') || str_contains($actionLower, 'fail')) {
                $log->icon = '❌';
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
                $log->icon = '📥';
                $log->badge_color = 'teal';
                $log->icon_bg = 'rgba(15,76,92,.08)';
                $log->action_type = 'export';
            }

            if ($log->user) {
                $log->action_label = $log->user->first_name . ' ' . $log->user->last_name;
            } else {
                $log->action_label = ucfirst($log->action);
            }

            $meta = [];
            if ($log->target_type) {
                $meta[] = "Target: " . class_basename($log->target_type) . ($log->target_id ? " (#{$log->target_id})" : "");
            }
            if ($log->user && $log->user->role) {
                $meta[] = "Role: " . ucfirst($log->user->role);
            }
            $log->extra_meta = empty($meta) ? null : implode(' • ', $meta);

            return $log;
        });

        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $roles = User::select('role')->distinct()->orderBy('role')->pluck('role');

        return view('superadmin.logs', compact('logs', 'successCount', 'errorCount', 'warnCount', 'actions', 'roles'));
    }

    // -------------------------------------------------------------------------
    // Settings & Notifications
    // -------------------------------------------------------------------------
    public function settings()
    {
        $featureFlags = Setting::where('key', 'featureFlags')->value('value') ?? [];
        $integrations = Setting::where('key', 'integrations')->value('value') ?? [];
        $notificationTemplates = Setting::where('key', 'notificationTemplates')->value('value') ?? [];
        $permissionsMatrix = Setting::where('key', 'permissionsMatrix')->value('value') ?? [];

        return view('superadmin.settings', compact('featureFlags', 'integrations', 'notificationTemplates', 'permissionsMatrix'));
    }

    public function updateSettings(Request $request)
    {
        if ($request->has('flag') && $request->has('enabled')) {
            $setting = Setting::where('key', 'featureFlags')->first();
            if ($setting) {
                $data = $setting->value;
                if (isset($data[$request->flag])) {
                    $data[$request->flag]['enabled'] = $request->enabled === 'true';
                    $setting->update(['value' => $data]);
                }
            }
            return response()->json(['success' => true]);
        }

        if ($request->has('role') && $request->has('permission') && $request->has('enabled')) {
            $setting = Setting::where('key', 'permissionsMatrix')->first();
            if ($setting) {
                $data = $setting->value;
                if (isset($data[$request->role]['permissions'][$request->permission])) {
                    $data[$request->role]['permissions'][$request->permission]['enabled'] = $request->enabled === 'true';
                    $setting->update(['value' => $data]);
                }
            }
            return response()->json(['success' => true]);
        }

        if ($request->action === 'reset_templates') {
            $seeder = new \Database\Seeders\SettingSeeder();
            $seeder->run();
            return response()->json(['success' => true]);
        }

        if ($request->has('templates')) {
            $setting = Setting::where('key', 'notificationTemplates')->first();
            if ($setting) {
                $data = $setting->value;
                foreach ($request->templates as $key => $content) {
                    if (isset($data[$key])) {
                        $data[$key]['content'] = $content;
                    }
                }
                $setting->update(['value' => $data]);
            }
        }

        if ($request->has('flags')) {
            $setting = Setting::where('key', 'featureFlags')->first();
            if ($setting) {
                $data = $setting->value;
                foreach ($data as $key => $flag) {
                    $data[$key]['enabled'] = isset($request->flags[$key]);
                }
                $setting->update(['value' => $data]);
            }
            return redirect()->route('superadmin.settings')->with('success', 'Settings updated successfully.');
        }
        
        return redirect()->back();
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->latest()->get();
        return view('superadmin.notifications', compact('user', 'notifications'));
    }

    public function markReadNotification($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        return back();
    }

    public function markAllReadNotifications()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return back();
    }
}
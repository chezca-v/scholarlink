<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\Application;
use App\Models\ActivityLog;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $previousMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $previousMonthEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();
        
        $adminId = auth()->id();

        $openScholarships = Scholarship::query()->where('created_by', $adminId)->where('status', 'open')->count();
        $draftScholarships = Scholarship::query()->where('created_by', $adminId)->where('status', 'draft')->count();
        $newScholarships = Scholarship::query()->where('created_by', $adminId)->where('created_at', '>=', $now->copy()->subDays(7))->count();
        $closingSoonScholarships = Scholarship::query()
            ->where('created_by', $adminId)
            ->where('status', 'open')
            ->whereDate('deadline', '>=', $now->toDateString())
            ->whereDate('deadline', '<=', $now->copy()->addDays(7)->toDateString())
            ->count();

        $applicationsQuery = function() use ($adminId) {
            return Application::whereHas('scholarship', function($q) use ($adminId) {
                $q->where('created_by', $adminId);
            });
        };

        $pendingReviews = $applicationsQuery()->whereIn('status', ['pending', 'under_review'])->count();
        $pendingToday = $applicationsQuery()
            ->whereIn('status', ['pending', 'under_review'])
            ->whereDate('created_at', $now->toDateString())
            ->count();
        $oldestPendingApplication = $applicationsQuery()
            ->whereIn('status', ['pending', 'under_review'])
            ->oldest('created_at')
            ->first();
        $oldestPendingDays = $oldestPendingApplication
            ? (int) $oldestPendingApplication->created_at->diffInDays($now)
            : 0;

        $totalApplications = $applicationsQuery()->count();
        $currentMonthApplications = $applicationsQuery()->whereBetween('created_at', [$startOfMonth, $now])->count();
        $previousMonthApplications = $applicationsQuery()->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $applicationsGrowth = $previousMonthApplications > 0
            ? round((($currentMonthApplications - $previousMonthApplications) / $previousMonthApplications) * 100)
            : ($currentMonthApplications > 0 ? 100 : 0);

        $approvedAwarded = $applicationsQuery()->where('status', 'approved')->count();
        $approvalRate = $totalApplications > 0 ? round(($approvedAwarded / $totalApplications) * 100, 1) : 0;

        $unassignedApplications = $applicationsQuery()
            ->whereIn('status', ['pending', 'under_review'])
            ->where('created_at', '<=', $now->copy()->subDays(4))
            ->doesntHave('evaluations')
            ->count();
        $incompleteDocsApplications = $applicationsQuery()
            ->whereIn('status', ['pending', 'under_review'])
            ->doesntHave('applicationDocuments')
            ->count();
        $awaitingApprovalScholarships = Scholarship::query()->where('created_by', $adminId)->where('status', 'draft')->count();

        $statusCounts = [
            'pending' => $applicationsQuery()->where('status', 'pending')->count(),
            'under_review' => $applicationsQuery()->where('status', 'under_review')->count(),
            'revision' => $applicationsQuery()->where('status', 'revision')->count(),
            'approved' => $applicationsQuery()->where('status', 'approved')->count(),
            'rejected' => $applicationsQuery()->where('status', 'rejected')->count(),
        ];

        $recentActivity = ActivityLog::query()
            ->whereHas('scholarship', function($q) use ($adminId) {
                $q->where('created_by', $adminId);
            })
            ->with('user')
            ->latest('created_at')
            ->take(6)
            ->get();

        $scholarshipOverview = Scholarship::query()
            ->where('created_by', $adminId)
            ->withCount('applications')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $upcomingDeadlines = Scholarship::query()
            ->where('created_by', $adminId)
            ->whereNotNull('deadline')
            ->whereDate('deadline', '>=', $now->toDateString())
            ->orderBy('deadline')
            ->withCount('applications')
            ->take(4)
            ->get();

        $unreadNotifications = auth()->user()
            ? auth()->user()->notifications()->where('is_read', false)->count()
            : 0;

        // Build stat cards data
        $stats = [
            [
                'icon' => '📖',
                'badge_text' => '↑ ' . $newScholarships . ' new',
                'badge_color' => 'var(--green-success)',
                'value' => $openScholarships,
                'label' => 'Open Scholarships',
                'footer' => $closingSoonScholarships . ' closing soon · ' . $draftScholarships . ' drafting',
            ],
            [
                'icon' => '📝',
                'badge_text' => '↑ ' . $pendingToday . ' today',
                'badge_color' => 'var(--red-alert)',
                'value' => $pendingReviews,
                'label' => 'Pending Reviews',
                'footer' => 'Oldest: ' . $oldestPendingDays . ' days ago',
            ],
            [
                'icon' => '📈',
                'badge_text' => ($applicationsGrowth >= 0 ? '↑' : '↓') . ' ' . abs($applicationsGrowth) . '%',
                'badge_color' => 'var(--green-success)',
                'value' => $totalApplications,
                'label' => 'Total Applications',
                'footer' => 'This academic year',
            ],
            [
                'icon' => '💰',
                'badge_text' => ($applicationsGrowth >= 0 ? '↑' : '↓') . ' ' . abs($applicationsGrowth) . '%',
                'badge_color' => 'var(--green-success)',
                'value' => $approvedAwarded,
                'label' => 'Approved / Awarded',
                'footer' => $approvalRate . '% approval rate',
            ],
        ];

        // Build alerts data
        $alerts = [
            [
                'type' => 'red',
                'icon' => '🚨',
                'title' => $unassignedApplications . ' Applications Unassigned for 4+ Days',
                'description' => 'Applications with no evaluations assigned yet. Risk of missing SLA.',
                'link' => route('admin.applications'),
                'link_text' => 'Assign Evaluators',
            ],
            [
                'type' => 'orange',
                'icon' => '⏳',
                'title' => 'Upcoming Deadlines — ' . $incompleteDocsApplications . ' Applicants with Incomplete Docs',
                'description' => 'Applicants still missing at least one required document.',
                'link' => route('admin.applications'),
                'link_text' => 'View Applicants',
            ],
            [
                'type' => 'blue',
                'icon' => '📢',
                'title' => $awaitingApprovalScholarships . ' Scholarships Awaiting Approval',
                'description' => 'Draft scholarships are ready for review and publication.',
                'link' => route('admin.scholarships.index'),
                'link_text' => 'Review Drafts',
            ],
        ];

        // Build quick actions data
        $quickActions = [
            [
                'icon' => '➕',
                'label' => 'New Scholarship',
                'link' => route('admin.scholarships.create') ?? '#',
            ],
            [
                'icon' => '👥',
                'label' => 'Assign',
                'link' => route('admin.applications') ?? '#',
            ],
            [
                'icon' => '⚙️',
                'label' => 'Settings',
                'link' => route('admin.settings'),
            ],
            [
                'icon' => '📊',
                'label' => 'Analytics',
                'link' => route('admin.analytics') ?? '#',
            ],
        ];

        // Build breakdown items data
        $totalForBreakdown = max(1, $totalApplications);
        $breakdownItems = [
            [
                'color' => '#ea8c55',
                'label' => 'Pending Review',
                'count' => $statusCounts['pending'],
                'percentage' => round(($statusCounts['pending'] / $totalForBreakdown) * 100),
            ],
            [
                'color' => '#1a8fa0',
                'label' => 'Under Evaluation',
                'count' => $statusCounts['under_review'],
                'percentage' => round(($statusCounts['under_review'] / $totalForBreakdown) * 100),
            ],
            [
                'color' => '#8b5cf6',
                'label' => 'Awaiting Docs',
                'count' => $statusCounts['revision'],
                'percentage' => round(($statusCounts['revision'] / $totalForBreakdown) * 100),
            ],
            [
                'color' => '#10b981',
                'label' => 'Approved',
                'count' => $statusCounts['approved'],
                'percentage' => round(($statusCounts['approved'] / $totalForBreakdown) * 100),
            ],
            [
                'color' => '#ef5350',
                'label' => 'Rejected',
                'count' => $statusCounts['rejected'],
                'percentage' => round(($statusCounts['rejected'] / $totalForBreakdown) * 100),
            ],
        ];

        return view('admin.dashboard', [
            'now' => $now,
            'openScholarships' => $openScholarships,
            'draftScholarships' => $draftScholarships,
            'newScholarships' => $newScholarships,
            'closingSoonScholarships' => $closingSoonScholarships,
            'pendingReviews' => $pendingReviews,
            'pendingToday' => $pendingToday,
            'oldestPendingDays' => $oldestPendingDays,
            'totalApplications' => $totalApplications,
            'applicationsGrowth' => $applicationsGrowth,
            'approvedAwarded' => $approvedAwarded,
            'approvalRate' => $approvalRate,
            'unassignedApplications' => $unassignedApplications,
            'incompleteDocsApplications' => $incompleteDocsApplications,
            'awaitingApprovalScholarships' => $awaitingApprovalScholarships,
            'statusCounts' => $statusCounts,
            'recentActivity' => $recentActivity,
            'scholarshipOverview' => $scholarshipOverview,
            'upcomingDeadlines' => $upcomingDeadlines,
            'unreadNotifications' => $unreadNotifications,
            'stats' => $stats,
            'alerts' => $alerts,
            'quickActions' => $quickActions,
            'breakdownItems' => $breakdownItems,
        ]);
    }

    public function users()
    {
        $adminId = auth()->id();
        $scholarships = Scholarship::where('created_by', $adminId)->get();
        $scholarshipIds = $scholarships->pluck('id');

        $evaluatorIds = \Illuminate\Support\Facades\DB::table('evaluator_assignments')
                            ->whereIn('scholarship_id', $scholarshipIds)
                            ->pluck('evaluator_id');

        $users = User::whereIn('id', $evaluatorIds)->where('role', 'evaluator')->latest()->get();
        return view('admin.user', compact('users', 'scholarships'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'scholarship_id' => 'required|exists:scholarships,id',
        ]);

        // Verify the admin owns this scholarship
        $scholarship = Scholarship::where('id', $request->scholarship_id)
            ->where('created_by', auth()->id())
            ->firstOrFail();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make('password123'), // default password
            'role' => 'evaluator',
        ]);

        \Illuminate\Support\Facades\DB::table('evaluator_assignments')->insert([
            'evaluator_id' => $user->id,
            'scholarship_id' => $scholarship->id,
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Evaluator created and assigned successfully. Default password is: password123');
    }

    public function deactivateUser($id)
    {
        // Logic to update user status to inactive
    }


    public function analytics()
    {
        $adminId = auth()->id();
        
        $applicationsQuery = function() use ($adminId) {
            return Application::whereHas('scholarship', function($q) use ($adminId) {
                $q->where('created_by', $adminId);
            });
        };

        $stats = [
            'total_applications' => $applicationsQuery()->count(),
            'apps_change' => '+0%',
            'approval_rate' => $applicationsQuery()->count() > 0 ? round(($applicationsQuery()->where('status', 'approved')->count() / $applicationsQuery()->count()) * 100) : 0,
            'approval_change' => '+0%',
            'avg_review_days' => 0,
            'review_change' => '0',
            'active_scholarships' => Scholarship::where('created_by', $adminId)->where('status', 'open')->count(),
            'active_change' => '+0',
        ];

        $funnel = [
            'viewed' => $stats['total_applications'] * 3,
            'started' => $stats['total_applications'] * 2,
            'submitted' => $stats['total_applications'],
            'under_review' => $applicationsQuery()->where('status', 'under_review')->count(),
            'approved' => $applicationsQuery()->where('status', 'approved')->count(),
        ];

        return view('admin.analytics', compact('stats', 'funnel'));
    }

    public function calendar(\Illuminate\Http\Request $request)
    {
        $monthQuery = $request->get('month');
        if ($monthQuery) {
            $currentMonth = \Carbon\Carbon::parse($monthQuery . '-01');
        } else {
            $currentMonth = \Carbon\Carbon::now();
        }
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        $scholarships = \App\Models\Scholarship::whereNotNull('deadline')
                        ->where('created_by', auth()->id())
                        ->where('status', '!=', 'draft')
                        ->get();

        $calendarDays = [];
        $upcomingDeadlines = [];
        
        $daysInMonth = $currentMonth->daysInMonth;
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDate = $currentMonth->copy()->day($i);
            $dayDeadlines = [];
            
            foreach ($scholarships as $scholarship) {
                if ($scholarship->deadline && $scholarship->deadline->isSameDay($currentDate)) {
                    $daysUntil = \Carbon\Carbon::now()->startOfDay()->diffInDays($scholarship->deadline, false);
                    $type = $daysUntil <= 7 && $daysUntil >= 0 ? 'urgent' : 'standard';
                    
                    $dayDeadlines[] = [
                        'id' => $scholarship->id,
                        'label' => $scholarship->name,
                        'type' => $type,
                        'days_away' => $daysUntil
                    ];
                    
                    if ($daysUntil >= 0 && $daysUntil <= 30) {
                        $upcomingDeadlines[] = [
                            'id' => $scholarship->id,
                            'scholarship_name' => $scholarship->name,
                            'date' => $scholarship->deadline,
                            'days_away' => $daysUntil,
                            'type' => $type,
                            'type_label' => ucfirst($type) . ' Deadline',
                            'meta' => 'Applications close at 11:59 PM'
                        ];
                    }
                }
            }
            
            $calendarDays[] = [
                'date' => $currentDate->copy(),
                'deadlines' => $dayDeadlines
            ];
        }
        
        usort($upcomingDeadlines, function($a, $b) {
            return $a['days_away'] <=> $b['days_away'];
        });

        $upcomingDeadlines = array_map("unserialize", array_unique(array_map("serialize", $upcomingDeadlines)));

        $scholarshipLegend = [
            ['bg' => '#1a8fa0', 'label' => 'Standard Deadline'],
            ['bg' => '#ea8c55', 'label' => 'Urgent Deadline (≤7 days)'],
        ];

        $deadlinesJson = [];
        
        $ui = [
            'page_title' => 'Calendar',
            'topnav_title' => 'Calendar',
            'topnav_subtitle' => 'Month of :month',
            'actions' => [],
            'breadcrumb' => ['Admin', 'Calendar'],
            'prev' => 'Prev',
            'next' => 'Next',
            'today' => 'Today',
            'upcoming_title' => 'Upcoming Deadlines',
            'deadline_badge' => ':days days away',
            'edit' => 'Edit',
            'empty' => 'No upcoming deadlines.',
            'reason_placeholder' => 'Reason for editing...',
            'warning' => 'Note: altering deadlines affects applicants.',
            'save' => 'Save Changes'
        ];
        $routes = [
            'index' => 'admin.calendar',
            'update' => 'admin.calendar'
        ];
        $config = [
            'month_format' => 'F Y',
            'day_format' => 'M d, Y',
            'week_days' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'upcoming_range' => 30,
            'deadline_types' => ['standard' => 'Standard Deadline', 'urgent' => 'Urgent Deadline']
        ];

        return view('admin.calendar', compact(
            'currentMonth', 'prevMonth', 'nextMonth',
            'scholarshipLegend', 'calendarDays',
            'upcomingDeadlines', 'deadlinesJson',
            'ui', 'routes', 'config'
        ));
    }

    public function exportAnalytics()
    {
        return back()->with('success', 'Analytics exported successfully.');
    }

    public function applications()
    {
        $adminId = auth()->id();
        $applications = Application::with('applicant', 'scholarship')
            ->whereHas('scholarship', function($q) use ($adminId) {
                $q->where('created_by', $adminId);
            })
            ->latest()->paginate(15);
        return view('admin.applications', compact('applications'));
    }

    public function reviews()
    {
        $adminId = auth()->id();
        $reviews = Application::with('applicant', 'scholarship')
            ->whereHas('scholarship', function($q) use ($adminId) {
                $q->where('created_by', $adminId);
            })
            ->whereIn('status', ['pending', 'under_review'])->latest()->paginate(15);
        return view('admin.reviews', compact('reviews'));
    }

    public function settings()
    {
        return view('admin.settings', [
            'pageTitle' => 'Admin Settings',
            'topnavTitle' => 'Settings',
            'topnavSubtitle' => 'Manage platform configuration',
            'breadcrumbs' => [
                ['label' => 'Admin', 'url' => route('admin.dashboard')],
                ['label' => 'Settings']
            ],
            'organization' => (object)[
                'name' => 'ScholarLink',
                'description' => 'Scholarship matching platform',
                'emoji' => '🎓',
                'email' => 'contact@scholarlink.com',
                'phone' => '123-456-7890',
                'website' => 'scholarlink.com',
                'address' => '123 Scholar Way',
            ],
            'labels' => [
                'org_profile' => 'Organization Profile',
                'save_changes' => 'Save Changes',
                'blind_screening' => 'Blind Screening',
                'save' => 'Save',
                'notifications' => 'Notification Templates',
                'save_all' => 'Save All',
                'weights' => 'Scoring Weights',
                'reset' => 'Reset to Default',
                'save_weights' => 'Save Weights'
            ],
            'routes' => [
                'update_profile' => 'admin.settings.update',
                'blind_screening' => 'admin.settings.update',
                'templates' => 'admin.settings.update',
                'weights' => 'admin.settings.update',
                'toggle_blind' => 'admin.settings.update',
            ],
            'orgFields' => [
                ['name' => 'name', 'label' => 'Organization Name'],
                ['name' => 'description', 'label' => 'Description'],
            ],
            'blindScreeningOptions' => [
                'hide_names' => ['label' => 'Hide Applicant Names', 'description' => 'Evaluators will not see the names of applicants.', 'enabled' => true],
                'hide_photos' => ['label' => 'Hide Applicant Photos', 'description' => 'Evaluators will not see the photos of applicants.', 'enabled' => true],
            ],
            'notificationTemplates' => [
                'application_received' => ['tab_label' => 'Application Received', 'subject' => 'Application Received', 'email_body' => 'Your application was received.', 'sms_body' => 'Your application was received.'],
            ],
            'scoringWeights' => [
                'academic' => ['label' => 'Academic Score', 'description' => 'Weight for academic performance.', 'value' => 40],
                'extracurricular' => ['label' => 'Extracurriculars', 'description' => 'Weight for extracurricular activities.', 'value' => 30],
                'essay' => ['label' => 'Essay', 'description' => 'Weight for the essay.', 'value' => 30],
            ],
            'defaultWeights' => [
                'academic' => 40,
                'extracurricular' => 30,
                'essay' => 30,
            ],
        ]);
    }

    public function updateSettings(\Illuminate\Http\Request $request)
    {
        return back()->with('success', 'Settings updated successfully.');
    }

    public function showApplication($id)
    {
        return back()->with('success', 'Application ' . $id . ' viewed.');
    }

    public function bulkAssign(\Illuminate\Http\Request $request)
    {
        return back()->with('success', 'Evaluator assigned to selected applications.');
    }

    public function assign(\Illuminate\Http\Request $request, $id)
    {
        return back()->with('success', 'Evaluator assigned to application.');
    }

    public function bulkApprove(\Illuminate\Http\Request $request)
    {
        return back()->with('success', 'Selected applications approved.');
    }

    public function bulkReject(\Illuminate\Http\Request $request)
    {
        return back()->with('success', 'Selected applications rejected.');
    }

    public function approveApplication($id)
    {
        return back()->with('success', 'Application approved.');
    }

    public function rejectForm($id)
    {
        return back()->with('success', 'Application rejected.');
    }
}

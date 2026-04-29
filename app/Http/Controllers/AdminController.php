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

        $openScholarships = Scholarship::query()->where('status', 'open')->count();
        $draftScholarships = Scholarship::query()->where('status', 'draft')->count();
        $newScholarships = Scholarship::query()->where('created_at', '>=', $now->copy()->subDays(7))->count();
        $closingSoonScholarships = Scholarship::query()
            ->where('status', 'open')
            ->whereDate('deadline', '>=', $now->toDateString())
            ->whereDate('deadline', '<=', $now->copy()->addDays(7)->toDateString())
            ->count();

        $pendingReviews = Application::query()->whereIn('status', ['pending', 'under_review'])->count();
        $pendingToday = Application::query()
            ->whereIn('status', ['pending', 'under_review'])
            ->whereDate('created_at', $now->toDateString())
            ->count();
        $oldestPendingApplication = Application::query()
            ->whereIn('status', ['pending', 'under_review'])
            ->oldest('created_at')
            ->first();
        $oldestPendingDays = $oldestPendingApplication
            ? (int) $oldestPendingApplication->created_at->diffInDays($now)
            : 0;

        $totalApplications = Application::query()->count();
        $currentMonthApplications = Application::query()->whereBetween('created_at', [$startOfMonth, $now])->count();
        $previousMonthApplications = Application::query()->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $applicationsGrowth = $previousMonthApplications > 0
            ? round((($currentMonthApplications - $previousMonthApplications) / $previousMonthApplications) * 100)
            : ($currentMonthApplications > 0 ? 100 : 0);

        $approvedAwarded = Application::query()->where('status', 'approved')->count();
        $approvalRate = $totalApplications > 0 ? round(($approvedAwarded / $totalApplications) * 100, 1) : 0;

        $unassignedApplications = Application::query()
            ->whereIn('status', ['pending', 'under_review'])
            ->where('created_at', '<=', $now->copy()->subDays(4))
            ->doesntHave('evaluations')
            ->count();
        $incompleteDocsApplications = Application::query()
            ->whereIn('status', ['pending', 'under_review'])
            ->doesntHave('applicationDocuments')
            ->count();
        $awaitingApprovalScholarships = Scholarship::query()->where('status', 'draft')->count();

        $statusCounts = [
            'pending' => Application::query()->where('status', 'pending')->count(),
            'under_review' => Application::query()->where('status', 'under_review')->count(),
            'revision' => Application::query()->where('status', 'revision')->count(),
            'approved' => Application::query()->where('status', 'approved')->count(),
            'rejected' => Application::query()->where('status', 'rejected')->count(),
        ];

        $recentActivity = ActivityLog::query()
            ->with('user')
            ->latest('created_at')
            ->take(6)
            ->get();

        $scholarshipOverview = Scholarship::query()
            ->withCount('applications')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $upcomingDeadlines = Scholarship::query()
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
        $users = User::where('role', '!=', 'applicant')->latest()->get();
        return view('admin.user', compact('users'));
    }

    public function createUser(Request $request)
    {
        // Logic to create a new user with specific roles
    }

    public function deactivateUser($id)
    {
        // Logic to update user status to inactive
    }


    public function analytics()
    {
        $stats = [
            'total_applications' => Application::count() ?? 1420,
            'apps_change' => '+12%',
            'approval_rate' => 24,
            'approval_change' => '+2.1%',
            'avg_review_days' => 4.5,
            'review_change' => '-1.2d',
            'active_scholarships' => Scholarship::where('status', 'open')->count() ?? 12,
            'active_change' => '+3',
        ];

        $funnel = [
            'viewed' => 5400,
            'started' => 3200,
            'submitted' => 1420,
            'under_review' => 950,
            'approved' => 340,
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
        $applications = Application::with('applicant', 'scholarship')->latest()->paginate(15);
        return view('admin.applications', compact('applications'));
    }

    public function reviews()
    {
        $reviews = Application::with('applicant', 'scholarship')->whereIn('status', ['pending', 'under_review'])->latest()->paginate(15);
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
        $request->validate([
            'application_ids' => 'required|string',
            'evaluator_id' => 'required|exists:users,id',
        ]);

        $ids = explode(',', $request->application_ids);
        
        foreach ($ids as $id) {
            $application = Application::find($id);
            if ($application) {
                \App\Models\Evaluation::firstOrCreate([
                    'application_id' => $application->id,
                    'evaluator_id' => $request->evaluator_id,
                ], [
                    'gpa_score' => 0,
                    'income_score' => 0,
                ]);
                
                if ($application->status === 'pending') {
                    $application->update(['status' => 'under_review']);
                }
            }
        }

        return back()->with('success', 'Evaluator assigned to selected applications.');
    }

    public function assign(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'evaluator_id' => 'required|exists:users,id',
        ]);

        $application = Application::findOrFail($id);
        
        \App\Models\Evaluation::firstOrCreate([
            'application_id' => $application->id,
            'evaluator_id' => $request->evaluator_id,
        ], [
            'gpa_score' => 0,
            'income_score' => 0,
        ]);
        
        if ($application->status === 'pending') {
            $application->update(['status' => 'under_review']);
        }

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

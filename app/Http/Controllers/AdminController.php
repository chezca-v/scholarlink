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
                'link' => route('admin.applications.unassigned') ?? '#',
                'link_text' => 'Assign Evaluators',
            ],
            [
                'type' => 'orange',
                'icon' => '⏳',
                'title' => 'Upcoming Deadlines — ' . $incompleteDocsApplications . ' Applicants with Incomplete Docs',
                'description' => 'Applicants still missing at least one required document.',
                'link' => route('admin.applications.incomplete_docs') ?? '#',
                'link_text' => 'View Applicants',
            ],
            [
                'type' => 'blue',
                'icon' => '📢',
                'title' => $awaitingApprovalScholarships . ' Scholarships Awaiting Approval',
                'description' => 'Draft scholarships are ready for review and publication.',
                'link' => route('admin.scholarships.drafts') ?? '#',
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
                'link' => route('admin.applications.pending') ?? '#',
            ],
            [
                'icon' => '⚙️',
                'label' => 'Weight Config',
                'link' => route('admin.settings') ?? '#',
            ],
            [
                'icon' => '📊',
                'label' => 'Analytics',
                'link' => route('admin.reports.index') ?? '#',
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
        return view('admin.users');
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
        return view('admin.analytics');
    }

    public function calendar()
    {
        return view('admin.calendar');
    }
}

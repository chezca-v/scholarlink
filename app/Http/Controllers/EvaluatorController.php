<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EvaluatorAssignment;
use App\Models\Evaluation;

class EvaluatorController extends Controller
{
    public function dashboard()
    {
        $evaluator = auth()->user();

        // Assigned scholarships with queue counts
        $assignments = EvaluatorAssignment::query()
            ->where('evaluator_id', $evaluator->id)
            ->with('scholarship')
            ->get();

        $assignedScholarshipIds = $assignments->pluck('scholarship_id');

        // Pending queue count per scholarship
        $queueCounts = Application::query()
            ->whereIn('scholarship_id', $assignedScholarshipIds)
            ->whereIn('status', ['pending', 'under_review'])
            ->selectRaw('scholarship_id, COUNT(*) as total')
            ->groupBy('scholarship_id')
            ->pluck('total', 'scholarship_id');

        // Workload bar — total assigned vs completed
        $totalAssigned = Application::query()
            ->whereIn('scholarship_id', $assignedScholarshipIds)
            ->count();

        $totalCompleted = Evaluation::query()
            ->where('evaluator_id', $evaluator->id)
            ->whereNotNull('decision')
            ->count();

        // Recent completions
        $recentCompletions = Evaluation::query()
            ->where('evaluator_id', $evaluator->id)
            ->whereNotNull('decision')
            ->with('application.scholarship')
            ->latest('evaluated_at')
            ->take(5)
            ->get();

        return view('evaluator.dashboard', [
            'assignments'      => $assignments,
            'queueCounts'      => $queueCounts,
            'totalAssigned'    => $totalAssigned,
            'totalCompleted'   => $totalCompleted,
            'recentCompletions' => $recentCompletions,
        ]);
    }

    public function queue()
    {
        $evaluator = auth()->user();

        $assignedScholarshipIds = EvaluatorAssignment::query()
            ->where('evaluator_id', $evaluator->id)
            ->pluck('scholarship_id');

        $applications = Application::query()
            ->whereIn('scholarship_id', $assignedScholarshipIds)
            ->whereIn('status', ['pending', 'under_review'])
            ->with(['scholarship', 'applicant.applicantProfile'])
            ->oldest('submitted_at')
            ->paginate(15);

        return view('evaluator.queue', [
            'applications' => $applications,
        ]);
    }
}

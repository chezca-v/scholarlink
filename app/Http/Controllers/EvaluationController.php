<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\EvaluationSuggestion;
use App\Models\Scholarship;
use Carbon\Carbon;

class EvaluationController extends Controller
{
    public function show($id)
    {
        $evaluator = auth()->user();

        $application = Application::query()
            ->with([
                'scholarship',
                'applicant.applicantProfile',
                'applicationDocuments.document',
            ])
            ->findOrFail($id);

        // Check evaluator is assigned to this scholarship
        $isAssigned = $evaluator->evaluatorAssignments()
            ->where('scholarship_id', $application->scholarship_id)
            ->exists();

        abort_if(!$isAssigned, 403, 'You are not assigned to this scholarship.');

        // Get or initialize evaluation
        $evaluation = Evaluation::query()
            ->where('application_id', $id)
            ->where('evaluator_id', $evaluator->id)
            ->first();

        // Blind screening — mask applicant info if enabled
        $blindScreening = $application->scholarship->blind_screening;

        // Alternative scholarships for suggestion
        $alternatives = Scholarship::query()
            ->where('status', 'open')
            ->where('id', '!=', $application->scholarship_id)
            ->get(['id', 'name']);

        return view('evaluator.review', [
            'application'    => $application,
            'evaluation'     => $evaluation,
            'blindScreening' => $blindScreening,
            'alternatives'   => $alternatives,
        ]);
    }

    public function store(Request $request, $id)
    {
        $evaluator = auth()->user();

        $application = Application::query()->findOrFail($id);

        $request->validate([
            'gpa_score'    => ['required', 'numeric', 'min:0', 'max:100'],
            'income_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes'        => ['nullable', 'string', 'max:1000'],
            'decision'     => ['required', 'in:approved,rejected,revision_requested'],
        ]);

        $scholarship = $application->scholarship;

        // Compute final score using scholarship weights
        $evaluation = Evaluation::query()->firstOrNew([
            'application_id' => $id,
            'evaluator_id'   => $evaluator->id,
        ]);

        $evaluation->gpa_score    = $request->gpa_score;
        $evaluation->income_score = $request->income_score;
        $evaluation->notes        = $request->notes;
        $evaluation->decision     = $request->decision;
        $evaluation->final_score  = $evaluation->computeFinalScore(
            $scholarship->weight_gpa,
            $scholarship->weight_income
        );
        $evaluation->evaluated_at = Carbon::now();
        $evaluation->save();

        // Update application status and stage
        $application->status     = match($request->decision) {
            'approved'            => 'approved',
            'rejected'            => 'rejected',
            'revision_requested'  => 'revision',
        };
        $application->stage      = $request->decision === 'revision_requested' ? 'doc_review' : 'decided';
        $application->decided_at = $request->decision !== 'revision_requested' ? Carbon::now() : null;
        $application->save();

        if ($request->decision === 'rejected') {
            return redirect()->route('evaluator.rejection', ['id' => $id]);
        }

        return redirect()->route('evaluator.queue')
            ->with('success', 'Evaluation submitted successfully.');
    }

    public function reject($id)
    {
        $application = Application::query()
            ->with('scholarship')
            ->findOrFail($id);

        $evaluation = Evaluation::query()
            ->where('application_id', $id)
            ->where('evaluator_id', auth()->id())
            ->firstOrFail();

        $alternatives = Scholarship::query()
            ->where('status', 'open')
            ->where('id', '!=', $application->scholarship_id)
            ->get(['id', 'name']);

        return view('evaluator.rejection', [
            'application'  => $application,
            'evaluation'   => $evaluation,
            'alternatives' => $alternatives,
        ]);
    }

    public function submitRejection(Request $request, $id)
    {
        $request->validate([
            'rejection_reason'   => ['required', 'in:gpa,income_bracket,docs,mismatch,other'],
            'notes'              => ['nullable', 'string', 'max:1000'],
            'alternative_ids'    => ['nullable', 'array'],
            'alternative_ids.*'  => ['exists:scholarships,id'],
        ]);

        $evaluation = Evaluation::query()
            ->where('application_id', $id)
            ->where('evaluator_id', auth()->id())
            ->firstOrFail();

        $evaluation->rejection_reason = $request->rejection_reason;
        if ($request->notes) {
            $evaluation->notes = $request->notes;
        }
        $evaluation->save();

        // Save alternative scholarship suggestions
        if ($request->filled('alternative_ids')) {
            foreach ($request->alternative_ids as $scholarshipId) {
                EvaluationSuggestion::query()->firstOrCreate([
                    'evaluation_id' => $evaluation->id,
                    'scholarship_id' => $scholarshipId,
                ]);
            }
        }

        return redirect()->route('evaluator.queue')
            ->with('success', 'Rejection submitted successfully.');
    }

    public function completed()
    {
        $evaluator = auth()->user();

        $evaluations = Evaluation::query()
            ->where('evaluator_id', $evaluator->id)
            ->whereNotNull('decision')
            ->with('application.scholarship')
            ->latest('evaluated_at')
            ->paginate(15);

        return view('evaluator.completed', [
            'evaluations' => $evaluations,
        ]);
    }
}
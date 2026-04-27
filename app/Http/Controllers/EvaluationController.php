<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return view('evaluator.index');
    }

    public function show($id)
    {
        $application = Application::with(['applicant.applicantProfile', 'scholarship'])
            ->findOrFail($id);

        $applicant = $application->applicant;
        $profile = $applicant->applicantProfile;

        if ($application->scholarship->blind_screening) {
            $applicant = $this->maskApplicantForBlindReview($applicant, $profile);
        }

        return view('evaluator.show', [
            'application' => $application,
            'applicant' => $applicant,
            'profile' => $profile,
            'blindScreening' => $application->scholarship->blind_screening,
        ]);
    }

    public function store(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'gpa_score' => 'required|numeric|min:0|max:100',
            'income_score' => 'required|numeric|min:0|max:100',
            'final_score' => 'required|numeric|min:0|max:100',
            'decision' => 'required|in:approved,rejected,revision_requested',
            'notes' => 'nullable|string|max:2000',
        ]);

        Evaluation::create([
            'application_id' => $application->id,
            'evaluator_id' => auth()->id(),
            'gpa_score' => $request->gpa_score,
            'income_score' => $request->income_score,
            'final_score' => $request->final_score,
            'decision' => $request->decision,
            'notes' => $request->notes,
            'evaluated_at' => now(),
        ]);

        $application->update([
            'status' => $request->decision === 'approved' ? 'approved' : ($request->decision === 'rejected' ? 'rejected' : 'revision'),
            'stage' => $request->decision === 'revision_requested' ? 'revision' : 'decided',
            'decided_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Evaluation saved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        Evaluation::create([
            'application_id' => $application->id,
            'evaluator_id' => auth()->id(),
            'final_score' => 0,
            'decision' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'notes' => $request->rejection_reason,
            'evaluated_at' => now(),
        ]);

        $application->update([
            'status' => 'rejected',
            'stage' => 'decided',
            'decided_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }

    public function completed()
    {
        return view('evaluator.completed');
    }

    private function maskApplicantForBlindReview($applicant, $profile)
    {
        $applicant->name = 'Anonymous Applicant';

        if (isset($applicant->first_name)) {
            $applicant->first_name = 'Anonymous';
            $applicant->last_name = 'Applicant';
        }

        if ($profile) {
            $profile->university_name = 'Hidden';
            $profile->course_program = 'Hidden';
            $profile->year_level = null;
            $profile->province = 'Philippines';
            $profile->avatar_url = null;
        }

        if (property_exists($applicant, 'avatar_url')) {
            $applicant->avatar_url = null;
        }

        return $applicant;
    }
}

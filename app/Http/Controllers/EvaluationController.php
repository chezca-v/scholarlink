<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Evaluation;
use Illuminate\Http\Request;
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
        if ($blindScreening) {
            $application->applicant = $this->maskApplicantForBlindReview($application->applicant, $application->applicant->applicantProfile);
        }

        // Precompute scores for the view
        $gpaScore = $evaluation ? $evaluation->gpa_score : 0;
        $incomeScore = $evaluation ? $evaluation->income_score : 0;
        
        if (!$evaluation || ($gpaScore == 0 && $incomeScore == 0)) {
            $profile = $application->applicant->applicantProfile;
            $gpaScore = $this->calculateAutomatedGpaScore($profile->gwa, $application->scholarship->gpa_requirement);
            $incomeScore = $this->calculateAutomatedIncomeScore($profile->monthly_household_income, $application->scholarship->income_bracket);
        }

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
            'precomputedGpa' => $gpaScore,
            'precomputedIncome' => $incomeScore,
        ]);
    }

    public function store(Request $request, $id)
    {
        $evaluator = auth()->user();

        $application = Application::query()->findOrFail($id);

        // Check evaluator is assigned to this scholarship
        $isAssigned = $evaluator->evaluatorAssignments()
            ->where('scholarship_id', $application->scholarship_id)
            ->exists();

        abort_if(!$isAssigned, 403, 'You are not assigned to this scholarship.');

        $request->validate([
            'notes'        => ['nullable', 'string', 'max:1000'],
            'decision'     => ['required', 'in:approved,rejected,revision_requested,save_draft'],
            'documents'    => ['nullable', 'array'],
            'documents.*.status' => ['in:pending,approved,rejected,revision_requested'],
            'documents.*.notes' => ['nullable', 'string', 'max:500'],
        ]);

        $scholarship = $application->scholarship;
        $profile = $application->applicant->applicantProfile;

        // Process document verification
        $allDocsApproved = true;
        if ($request->has('documents')) {
            foreach ($request->documents as $docId => $docData) {
                $appDoc = \App\Models\ApplicationDocument::where('application_id', $id)
                    ->where('id', $docId)->first();
                if ($appDoc) {
                    $appDoc->status = $docData['status'];
                    $appDoc->evaluator_notes = $docData['notes'] ?? null;
                    $appDoc->save();
                    
                    if ($appDoc->status !== 'approved') {
                        $allDocsApproved = false;
                    }
                }
            }
        } else {
            // If there are no documents submitted, we might still proceed, but if there are, and they weren't submitted in form, it's false.
            if ($application->applicationDocuments()->count() > 0) {
                $allDocsApproved = false;
            }
        }

        // Compute automated scores
        $gpaScore = $this->calculateAutomatedGpaScore($profile->gwa, $scholarship->gpa_requirement);
        $incomeScore = $this->calculateAutomatedIncomeScore($profile->monthly_household_income, $scholarship->income_bracket);

        $evaluation = Evaluation::query()->firstOrNew([
            'application_id' => $id,
            'evaluator_id'   => $evaluator->id,
        ]);

        // Only attribute score if all documents are verified/approved
        $evaluation->gpa_score    = $allDocsApproved ? $gpaScore : 0;
        $evaluation->income_score = $allDocsApproved ? $incomeScore : 0;
        $evaluation->notes        = $request->notes;
        $evaluation->decision     = ($request->decision === 'save_draft') ? null : $request->decision;
        $evaluation->final_score  = $evaluation->computeFinalScore(
            $scholarship->weight_gpa,
            $scholarship->weight_income
        );
        $evaluation->evaluated_at = Carbon::now();
        $evaluation->save();

        // Validate approval prerequisites AFTER saving draft score
        if ($request->decision === 'approved') {
            if (!$allDocsApproved) {
                return redirect()->back()->withErrors(['decision' => 'All documents must be approved before marking the application as Approved.'])->withInput();
            }
            if ($evaluation->final_score < 65) {
                return redirect()->back()->withErrors(['decision' => 'Application final score must be at least 65 to be Approved.'])->withInput();
            }
        }

        if ($request->decision === 'save_draft') {
            return redirect()->back()->with('success', 'Progress saved successfully.');
        }

        // Update application status and stage
        $application->status     = match($request->decision) {
            'approved'            => 'approved',
            'rejected'            => 'rejected',
            'revision_requested'  => 'revision',
        };
        $application->stage      = $request->decision === 'revision_requested' ? 'doc_review' : 'decided';
        $application->decided_at = $request->decision !== 'revision_requested' ? Carbon::now() : null;
        
        if ($request->decision === 'approved') {
            $application->offer_status = 'pending';
            $application->offer_expires_at = Carbon::now()->addDays(7);
        }
        
        $application->save();

        if ($request->decision === 'rejected') {
            return redirect()->route('evaluator.rejection', ['id' => $id]);
        }

        $msg = $request->decision === 'revision_requested' 
            ? 'Information request sent to applicant.' 
            : 'Evaluation submitted successfully.';

        return redirect()->route('evaluator.queue')->with('success', $msg);
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
    private function maskApplicantForBlindReview($applicant, $profile)
    {
        $applicant->name = 'Anonymous Applicant';

        if (isset($applicant->first_name)) {
            $applicant->first_name = 'Anonymous';
            $applicant->last_name = 'Applicant';
        }
        
        $applicant->email = 'hidden@scholarlink.ph';
        $applicant->phone = '09XX-XXX-XXXX';

        if ($profile) {
            $profile->address = 'Hidden (Blind Mode)';
            $profile->phone = 'Hidden';
            $profile->birth_date = null;
            $profile->gender = 'Hidden';
            $profile->university_name = 'Hidden';
            $profile->course_program = 'Hidden';
            $profile->year_level = null;
            $profile->province = 'Philippines';
            $profile->avatar_url = null;
            $profile->fb_link = null;
            $profile->linkedin_link = null;
        }

        if (property_exists($applicant, 'avatar_url')) {
            $applicant->avatar_url = null;
        }

        return $applicant;
    }

    private function calculateAutomatedGpaScore($profileGpa, $requirement): int
    {
        if (blank($requirement) || blank($profileGpa)) {
            return blank($profileGpa) ? 50 : 100;
        }

        $profileGpa = floatval($profileGpa);
        $requirement = floatval($requirement);

        if ($profileGpa <= $requirement) {
            return 100;
        }

        $diff = max(0, $profileGpa - $requirement);
        return max(0, 100 - (int) round($diff * 30));
    }

    private function calculateAutomatedIncomeScore($monthlyIncome, $bracket): int
    {
        if (blank($bracket)) {
            return 100;
        }

        if (is_null($monthlyIncome)) {
            return 50;
        }

        $annualIncome = floatval($monthlyIncome) * 12;

        if (preg_match_all('/\d+[\d,]*/', (string) $bracket, $matches)) {
            $numbers = array_map(fn($v) => (int) str_replace(',', '', $v), $matches[0]);
            if (!empty($numbers)) {
                $threshold = max($numbers);
                return $annualIncome <= $threshold ? 100 : 20;
            }
        }
        return 50;
    }
}

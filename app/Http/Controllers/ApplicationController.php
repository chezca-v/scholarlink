<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::query()
            ->with('scholarship:id,name,provider_name')
            ->where('applicant_id', auth()->id())
            ->latest('submitted_at')
            ->latest('created_at')
            ->get();

        $statusMap = [
            'under_review' => ['filter' => 'under-review', 'class' => 'under-review', 'label' => 'Under Review'],
            'approved' => ['filter' => 'approved', 'class' => 'approved', 'label' => 'Approved'],
            'rejected' => ['filter' => 'rejected', 'class' => 'rejected', 'label' => 'Rejected'],
            'revision' => ['filter' => 'action-needed', 'class' => 'pending', 'label' => 'Action Needed'],
            'pending' => ['filter' => 'submitted', 'class' => 'submitted', 'label' => 'Submitted'],
            'submitted' => ['filter' => 'submitted', 'class' => 'submitted', 'label' => 'Submitted'],
        ];

        $remarksByStage = [
            'submitted' => 'Queued for Screening',
            'doc_review' => 'Document Review',
            'scoring' => 'Blind Evaluation',
            'decided' => 'Decision Released',
        ];

        $stats = [
            'totalApplied' => $applications->count(),
            'underReview' => $applications->where('status', 'under_review')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'shortlisted' => $applications
                ->where('status', 'under_review')
                ->where('stage', 'scoring')
                ->count(),
            'actionNeeded' => $applications->where('status', 'revision')->count(),
        ];

        return view('applicant.applications.index', [
            'applications' => $applications,
            'stats' => $stats,
            'statusMap' => $statusMap,
            'remarksByStage' => $remarksByStage,
        ]);
    }


    public function create()
    {
        return view('applicant.applications.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            // Profile fields (optional updates)
            'date_of_birth' => 'nullable|date|before:today',
            'sex' => 'nullable|in:male,female,other',
            'home_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'mobile_number' => 'nullable|string|max:15',
            'university_name' => 'nullable|string|max:255',
            'university_email' => 'nullable|email|max:255',
            'course_program' => 'nullable|string|max:255',
            'student_number' => 'nullable|string|max:50',
            'year_level' => 'nullable|integer|min:1|max:5',
            'semester' => 'nullable|in:1st,2nd,summer',
            'academic_year' => 'nullable|string|max:20',
            'gwa' => 'nullable|numeric|min:0|max:5',
            'gwa_scale' => 'nullable|numeric|min:1|max:5',
            'monthly_household_income' => 'nullable|numeric|min:0',
            'num_dependents' => 'nullable|integer|min:0',
            'is_breadwinner' => 'nullable|boolean',
            'is_4ps' => 'nullable|boolean',
            'father_employment_status' => 'nullable|string|max:100',
            'mother_employment_status' => 'nullable|string|max:100',
        ]);

        // Update or create applicant profile
        ApplicantProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only([
                'date_of_birth', 'sex', 'home_address', 'city', 'province', 'zip_code',
                'mobile_number', 'university_name', 'university_email', 'course_program',
                'student_number', 'year_level', 'semester', 'academic_year', 'gwa',
                'gwa_scale', 'monthly_household_income', 'num_dependents', 'is_breadwinner',
                'is_4ps', 'father_employment_status', 'mother_employment_status',
                'profile_completed_at' => now(), // Mark as completed
            ])
        );

        // Create the application
        $application = Application::create([
            'reference_code' => 'APP-' . Str::upper(Str::random(8)), // Generate unique code
            'applicant_id' => auth()->id(),
            'scholarship_id' => $request->scholarship_id,
            'status' => 'pending', // Default status
            'stage' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('applications.show', $application->id)->with('success', 'Application submitted successfully.');
    }


    public function show($id)
    {
        return view('applicant.applications.show', compact('id'));
    }


    public function track($id)
    {
        return view('applicant.applications.track', compact('id'));
    }
}

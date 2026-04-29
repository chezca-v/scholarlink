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

        return view('applicant.track', [
            'applications' => $applications,
            'stats' => $stats,
            'statusMap' => $statusMap,
            'remarksByStage' => $remarksByStage,
        ]);
    }


    public function create($id)
    {
        $scholarship = \App\Models\Scholarship::findOrFail($id);
        $applicant = auth()->user();
        $profile = $applicant->applicantProfile ?? new ApplicantProfile();

        $savedDocuments = \App\Models\Document::where('user_id', $applicant->id)->get();

        // Check GPA eligibility
        $gpaPass = null;
        if ($profile->gwa) {
            if ($scholarship->gpa_requirement) {
                $gpaPass = $profile->gwa <= $scholarship->gpa_requirement; // Assuming lower is better in PH system
            } else {
                $gpaPass = true;
            }
        }

        // Check Income eligibility
        $incomePass = null;
        if ($profile->monthly_household_income !== null) {
            if ($scholarship->income_bracket) {
                preg_match_all('/\d+/', str_replace(',', '', $scholarship->income_bracket), $matches);
                if (!empty($matches[0])) {
                    $limit = (float) $matches[0][0];
                    $annualIncome = $profile->monthly_household_income * 12;
                    $incomePass = $annualIncome <= $limit;
                } else {
                    $incomePass = true;
                }
            } else {
                $incomePass = true;
            }
        }

        // Check concurrent scholarship
        $hasActiveScholarship = Application::where('applicant_id', $applicant->id)
                                ->where('status', 'approved')
                                ->exists();
        $concurrentPass = !$hasActiveScholarship;

        // Check enrollment
        $enrollmentPass = null;
        if ($profile->university_name || $profile->course_program || $profile->year_level) {
            $enrollmentPass = true;
        }

        $eligibility = [
            'gpa' => [
                'label' => 'GPA Requirement',
                'pass' => $gpaPass,
                'badge' => $gpaPass ? 'Passed' : ($gpaPass === false ? 'Failed' : 'Pending'),
                'badgeClass' => $gpaPass ? 'b-green' : ($gpaPass === false ? 'b-red' : 'b-amber'),
            ],
            'income' => [
                'label' => 'Income Bracket',
                'pass' => $incomePass,
                'badge' => $incomePass ? 'Passed' : ($incomePass === false ? 'Failed' : 'Pending'),
                'badgeClass' => $incomePass ? 'b-green' : ($incomePass === false ? 'b-red' : 'b-amber'),
            ],
            'concurrent' => [
                'label' => 'No Concurrent Scholarship',
                'pass' => $concurrentPass,
                'badge' => $concurrentPass ? 'Passed' : 'Failed',
                'badgeClass' => $concurrentPass ? 'b-green' : 'b-red',
            ],
            'enrollment' => [
                'label' => 'Currently Enrolled',
                'pass' => $enrollmentPass,
                'badge' => $enrollmentPass ? 'Passed' : 'Failed',
                'badgeClass' => $enrollmentPass ? 'b-green' : 'b-red',
            ]
        ];

        $documentGroups = [
            [
                'groupTitle' => 'Identity & Academic',
                'slots' => [
                    [
                        'document_type' => 'Proof of Enrollment / Acceptance Letter',
                        'label' => 'Proof of Enrollment',
                        'smallNote' => 'Certificate of Registration',
                        'optional' => false,
                    ],
                    [
                        'document_type' => 'Latest Report Card / TOR',
                        'label' => 'Latest Report Card',
                        'smallNote' => 'Previous semester',
                        'optional' => false,
                    ],
                    [
                        'document_type' => '2x2 ID Photo',
                        'label' => '2x2 ID Photo',
                        'smallNote' => 'Passport-style photo',
                        'optional' => false,
                    ],
                ]
            ],
            [
                'groupTitle' => 'Financial & Legal Documents',
                'slots' => [
                    [
                        'document_type' => 'Income Tax Return / Certificate of Non-Filing',
                        'label' => 'Income Tax Return',
                        'smallNote' => 'Or Certificate of Non-Filing',
                        'optional' => false,
                    ],
                    [
                        'document_type' => 'Barangay Certificate of Indigency',
                        'label' => 'Barangay Certificate of Indigency',
                        'smallNote' => null,
                        'optional' => false,
                    ],
                    [
                        'document_type' => 'Certificate of Good Moral Character',
                        'label' => 'Certificate of Good Moral Character',
                        'smallNote' => 'From school/organization',
                        'optional' => true,
                    ]
                ]
            ],
            [
                'groupTitle' => 'Supporting Documents',
                'slots' => [
                    [
                        'document_type' => 'Affidavit of Financial Need',
                        'label' => 'Affidavit of Financial Need',
                        'smallNote' => null,
                        'optional' => true,
                    ],
                    [
                        'document_type' => 'Letter of Recommendation',
                        'label' => 'Letter of Recommendation',
                        'smallNote' => 'From teacher/mentor',
                        'optional' => true,
                    ],
                    [
                        'document_type' => 'PSA Birth Certificate',
                        'label' => 'PSA Birth Certificate',
                        'smallNote' => 'Original or certified copy',
                        'optional' => true,
                    ]
                ]
            ]
        ];

        $endorsementSlot = [
            'groupTitle' => 'Endorsement',
            'document_type' => 'Letter of Recommendation',
            'label' => 'Endorsement Letter',
            'smallNote' => 'From Dean or Guidance Counselor',
            'optional' => false,
        ];

        return view('applicant.applications.create', compact(
            'scholarship', 'applicant', 'profile', 'eligibility', 'documentGroups', 'savedDocuments', 'endorsementSlot'
        ));
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
            'documents' => 'nullable|array',
            'uploads' => 'nullable|array',
            'uploads.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documents = $request->input('documents', []);
        $uploads = $request->file('uploads', []);

        $requiredSlugs = [
            \Illuminate\Support\Str::slug('Proof of Enrollment'),
            \Illuminate\Support\Str::slug('Report Card / Transcript'),
            \Illuminate\Support\Str::slug('Valid ID'),
            \Illuminate\Support\Str::slug('ITR / Tax Exemption'),
            \Illuminate\Support\Str::slug('Barangay Indigency'),
            \Illuminate\Support\Str::slug('Endorsement Letter'),
        ];

        foreach ($requiredSlugs as $slug) {
            if (empty($documents[$slug]) && empty($uploads[$slug])) {
                return back()->withErrors(['error' => 'Missing required document: ' . ucwords(str_replace('-', ' ', $slug))])->withInput();
            }
        }

        // Update or create applicant profile
        $profileData = $request->only([
            'date_of_birth', 'sex', 'home_address', 'city', 'province', 'zip_code',
            'mobile_number', 'university_name', 'university_email', 'course_program',
            'student_number', 'year_level', 'semester', 'academic_year', 'gwa',
            'gwa_scale', 'monthly_household_income', 'num_dependents', 'is_breadwinner',
            'is_4ps', 'father_employment_status', 'mother_employment_status'
        ]);
        $profileData['profile_completed_at'] = now(); // Mark as completed

        ApplicantProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $profileData
        );

        // Create the application
        $application = Application::create([
            'reference_code' => 'APP-' . Str::upper(Str::random(8)), // Generate unique code
            'applicant_id' => auth()->id(),
            'scholarship_id' => $request->scholarship_id,
            'status' => 'submitted', // Changed from 'pending' to 'submitted'
            'stage' => 'submitted',
            'submitted_at' => now(),
        ]);

        $docTypes = [
            \Illuminate\Support\Str::slug('Proof of Enrollment') => 'Proof of Enrollment / Acceptance Letter',
            \Illuminate\Support\Str::slug('Report Card / Transcript') => 'Latest Report Card / TOR',
            \Illuminate\Support\Str::slug('Valid ID') => 'Other',
            \Illuminate\Support\Str::slug('ITR / Tax Exemption') => 'Income Tax Return / Certificate of Non-Filing',
            \Illuminate\Support\Str::slug('Barangay Indigency') => 'Barangay Certificate of Indigency',
            \Illuminate\Support\Str::slug('Utility Bill') => 'Other',
            \Illuminate\Support\Str::slug('Endorsement Letter') => 'Letter of Recommendation',
        ];

        foreach ($uploads as $slug => $file) {
            if ($file) {
                $filePath = $file->store('documents/user_' . auth()->id(), 'public');
                $docTypeStr = $docTypes[$slug] ?? 'Other';

                $doc = \App\Models\Document::create([
                    'user_id' => auth()->id(),
                    'document_type' => $docTypeStr,
                    'file_url' => $filePath,
                    'status' => 'pending',
                ]);

                \App\Models\ApplicationDocument::create([
                    'application_id' => $application->id,
                    'document_id' => $doc->id,
                    'submitted_at' => now(),
                ]);
            }
        }

        foreach ($documents as $slug => $docId) {
            if ($docId) {
                \App\Models\ApplicationDocument::create([
                    'application_id' => $application->id,
                    'document_id' => $docId,
                    'submitted_at' => now(),
                ]);
            }
        }

        $scholarship = \App\Models\Scholarship::find($application->scholarship_id);

        return back()->with('application_submitted', [
            'application_id' => $application->id,
            'reference_code' => $application->reference_code,
            'scholarship_name' => $scholarship->name ?? 'Scholarship',
        ]);
    }


    public function show($id)
    {
        $application = Application::with(['scholarship', 'applicationDocuments.document'])
            ->where('applicant_id', auth()->id())
            ->findOrFail($id);

        return view('applicant.applications.show', compact('application', 'id'));
    }


    public function track($id)
    {
        // Alias: redirect to show view
        return $this->show($id);
    }
}

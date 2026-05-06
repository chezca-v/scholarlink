<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Application;
use App\Models\ApplicantProfile;
use App\Models\Notification;
use App\Models\Scholarship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $profile = $user->applicantProfile;

        $applicationBaseQuery = Application::query()
            ->with('scholarship')
            ->where('applicant_id', $user->id);

        $recentApplications = (clone $applicationBaseQuery)
            ->orderByDesc('submitted_at')
            ->limit(5)
            ->get();

        $recommendedScholarships = (clone $applicationBaseQuery)
            ->whereNotNull('ai_match_score')
            ->orderByDesc('ai_match_score')
            ->limit(5)
            ->get()
            ->map(function (Application $application) {
                $application->setAttribute('match_score', (float) $application->ai_match_score);
                return $application;
            });

        if ($recommendedScholarships->isEmpty()) {
            $recommendedScholarships = Scholarship::query()
                ->where('status', 'open')
                ->where('ai_match_enabled', true)
                ->orderBy('deadline')
                ->limit(5)
                ->get()
                ->map(function (Scholarship $scholarship) {
                    $scholarship->setAttribute('match_score', null);
                    return $scholarship;
                });
        }

        $upcomingDeadlines = (clone $applicationBaseQuery)
            ->whereHas('scholarship', function ($query) {
                $query->whereDate('deadline', '>=', now()->toDateString());
            })
            ->get()
            ->filter(fn (Application $application) => $application->scholarship?->deadline)
            ->sortBy(fn (Application $application) => $application->scholarship->deadline)
            ->take(4)
            ->values();

        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $unreadNotifications = Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $pendingOffers = Application::query()
            ->with('scholarship')
            ->where('applicant_id', $user->id)
            ->where('status', 'approved')
            ->where('offer_status', 'pending')
            ->get();

        $stats = [
            'active_applications' => (clone $applicationBaseQuery)
                ->whereIn('status', ['pending', 'under_review', 'revision'])
                ->count(),
            'ai_matched' => (clone $applicationBaseQuery)
                ->whereNotNull('ai_match_score')
                ->count(),
            'awarded' => (clone $applicationBaseQuery)
                ->where('status', 'approved')
                ->count(),
            'saved' => $user->savedScholarships()->count(),
        ];

        $profileFields = collect([
            $profile?->date_of_birth,
            $profile?->sex,
            $profile?->home_address,
            $profile?->city,
            $profile?->province,
            $profile?->zip_code,
            $profile?->mobile_number,
            $profile?->university_name,
            $profile?->course_program,
            $profile?->student_number,
            $profile?->year_level,
            $profile?->semester,
            $profile?->academic_year,
            $profile?->gwa,
            $profile?->monthly_household_income,
            $profile?->num_dependents,
            $profile?->is_breadwinner,
            $profile?->is_4ps,
            $profile?->father_employment_status,
            $profile?->mother_employment_status,
        ]);

        $profileCompleteness = $profileFields->isEmpty()
            ? 0
            : (int) round(($profileFields->filter(fn ($value) => !is_null($value) && $value !== '')->count() / $profileFields->count()) * 100);

        return view('applicant.dashboard', compact(
            'user',
            'profile',
            'stats',
            'recentApplications',
            'recommendedScholarships',
            'upcomingDeadlines',
            'notifications',
            'profileCompleteness',
            'unreadNotifications',
            'pendingOffers'
        ));
    }

    public function setup(Request $request): View    {
        $profile = ApplicantProfile::firstOrNew(['user_id' => $request->user()->id]);
        $currentStep = max(1, min(4, (int) $request->integer('step', 1)));

        return view('profile.setup', [
            'profile' => $profile,
            'currentStep' => $currentStep,
        ]);
    }

    public function setupStep1(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date_of_birth' => ['required', 'date'],
            'sex' => ['required', 'in:Male,Female'],
            'home_address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:10'],
            'mobile_number' => ['required', 'string', 'max:20'],
        ]);

        ApplicantProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            array_merge([
                'university_name' => '',
                'university_address' => '',
                'course_program' => '',
                'student_number' => '',
                'year_level' => '',
                'semester' => '',
                'academic_year' => '',
            ], $validated)             );

        return redirect()->route('profile.setup', ['step' => 2]);
    }

    public function setupStep2(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'university_name' => ['required', 'string', 'max:255'],
            'university_address' => ['required', 'string'],
            'university_email' => ['nullable', 'email', 'max:255'],
            'course_program' => ['required', 'string', 'max:255'],
            'student_number' => ['required', 'string', 'max:50'],
            'year_level' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'string', 'max:20'],
            'academic_year' => ['required', 'string', 'max:20'],
            'gwa' => ['nullable', 'numeric', 'min:1', 'max:100'],
        ]);

        ApplicantProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return redirect()->route('profile.setup', ['step' => 3]);
    }

    public function setupStep3(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'monthly_household_income' => ['required', 'numeric', 'min:0'],
            'num_dependents' => ['required', 'integer', 'min:0'],
            'is_breadwinner' => ['required', 'in:Yes,No,Partial Contributor'],
            'is_4ps' => ['required', 'boolean'],
            'father_employment_status' => ['nullable', 'string', 'max:100'],
            'mother_employment_status' => ['nullable', 'string', 'max:100'],
        ]);

        ApplicantProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return redirect()->route('profile.setup', ['step' => 4]);
    }

    public function setupSubmit(Request $request): RedirectResponse
    {
        ApplicantProfile::where('user_id', $request->user()->id)
            ->update(['profile_completed_at' => now()]);

        return redirect()->route('dashboard')
            ->with('status', 'Profile setup completed successfully.');
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

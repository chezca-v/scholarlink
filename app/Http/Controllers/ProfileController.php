<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Application;
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

        $activeApplications = (clone $applicationBaseQuery)
            ->whereIn('status', ['pending', 'under_review', 'revision'])
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

        return view('dashboard', compact(
            'user',
            'profile',
            'stats',
            'activeApplications',
            'recommendedScholarships',
            'upcomingDeadlines',
            'notifications',
            'profileCompleteness'
        ));    }

    public function setup()
    {
        return view('profile.setup');
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

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

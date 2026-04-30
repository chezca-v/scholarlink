<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\ApplicantProfile;
use Illuminate\Support\Str;

class SavedScholarshipController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $savedScholarships = $user->savedScholarships()
            ->with('scholarship')
            ->latest('saved_at')
            ->get();

        // Calculate real AI match scores for each saved scholarship
        $matchScores = [];
        $profile = $user->applicantProfile;

        if ($profile) {
            $matchScores = $this->calculateMatchScores($profile, $savedScholarships->pluck('scholarship')->all());
        }

        // We might also need notifications if the layout requires it
        $notifications = $user->notifications()->latest()->get();

        return view('applicant.saved', compact('user', 'savedScholarships', 'notifications', 'matchScores'));
    }

    /**
     * Calculate AI match scores for scholarships based on user profile.
     */
    private function calculateMatchScores(ApplicantProfile $profile, array $scholarships): array
    {
        $scores = [];
        $gwa = $profile->gwa;
        $course = $profile->course_program;
        $income = $profile->monthly_household_income;

        foreach ($scholarships as $scholarship) {
            $gpaScore = $this->scoreGpa($gwa, $scholarship->gpa_requirement);
            $courseScore = $this->scoreCourse($course, $scholarship->courses);
            $incomeScore = $this->scoreIncome($income, $scholarship->income_bracket);

            $score = (int) round(
                ($gpaScore * 0.55) +
                ($courseScore * 0.30) +
                ($incomeScore * 0.15)
            );
            $scores[$scholarship->id] = max(0, min(100, $score));
        }

        return $scores;
    }

private function scoreGpa($profileGpa, $requirement): int
    {
        // Philippine grading scale: 1.0 = best, 5.0 = fail
        // 1.00 = 100, 1.25 = 96, 1.50 = 92, 1.75 = 88, 2.0 = 84, etc.
        if (blank($requirement) || blank($profileGpa)) {
            return blank($profileGpa) ? 50 : 100;
        }

        $profileGpa = floatval($profileGpa);
        $requirement = floatval($requirement);

        // With 1.0 = 100 scale, lower is better
        // If profile GPA <= requirement (e.g., 1.0 <= 1.5), student meets requirement
        if ($profileGpa <= $requirement) {
            return 100;
        }

        // Calculate score based on how much lower the requirement is
        // e.g., profile: 1.5, req: 1.0 → diff = 0.5 → score = 100 - (0.5 * 30) = 85
        $diff = max(0, $profileGpa - $requirement);
        return max(0, 100 - (int) round($diff * 30));
    }

    private function scoreCourse($profileCourse, $scholarshipCourses): int
    {
        if (blank($scholarshipCourses)) {
            return 100;
        }

        $profileCourse = Str::lower(trim((string) $profileCourse));
        $courses = is_array($scholarshipCourses)
            ? $scholarshipCourses
            : explode(',', (string) $scholarshipCourses);
        $courses = array_filter(array_map(fn($c) => Str::lower(trim($c)), $courses));

        if (!$profileCourse || empty($courses)) {
            return 50;
        }

        foreach ($courses as $course) {
            if (!$course) continue;
            if (Str::contains($profileCourse, $course) || Str::contains($course, $profileCourse)) {
                return 100;
            }
            $profileWords = preg_split('/\s+/', $profileCourse);
            foreach ($profileWords as $word) {
                if ($word && Str::contains($course, $word)) {
                    return 90;
                }
            }
        }

        return 0;
    }

    private function scoreIncome($monthlyIncome, $bracket): int
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

        return 75;
    }

    public function store(Request $request, $id)
    {
        $user = auth()->user();

        \App\Models\SavedScholarship::firstOrCreate([
            'user_id' => $user->id,
            'scholarship_id' => $id,
        ], [
            'saved_at' => now(),
        ]);

        return redirect()->route('applicant.saved')->with('success', 'Scholarship saved successfully!');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        \App\Models\SavedScholarship::where('user_id', $user->id)
            ->where('scholarship_id', $id)
            ->delete();

        return back()->with('success', 'Scholarship removed from saved list.');
    }
}

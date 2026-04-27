<?php

namespace App\Http\Controllers;

use App\Models\ApplicantProfile;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class AIController extends Controller
{
    /**
     * AI match scoring API endpoint
     * Compares student profile against scholarship requirements.
     */
    public function match(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:users,id',
            'scholarship_id' => 'required|exists:scholarships,id',
        ]);

        $scholarship = Scholarship::findOrFail($request->scholarship_id);
        $profile = ApplicantProfile::where('user_id', $request->applicant_id)->first();

        if (! $profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Applicant profile not found.',
            ], 404);
        }

        $gpaScore = $this->scoreGpa($profile->gwa, $scholarship->gpa_requirement);
        $incomeScore = $this->scoreIncome($profile->monthly_household_income, $scholarship->income_bracket);
        $fitScore = $this->scoreFit($profile, $scholarship);

        $weightGpa = $this->normalizeWeight($scholarship->weight_gpa, 40);
        $weightIncome = $this->normalizeWeight($scholarship->weight_income, 30);
        $weightFit = max(100 - ($weightGpa + $weightIncome), 20);

        $matchScore = round((
            $gpaScore * $weightGpa +
            $incomeScore * $weightIncome +
            $fitScore * $weightFit
        ) / 100, 0);

        return response()->json([
            'status' => 'success',
            'match_score' => $matchScore,
            'analysis' => [
                'gpa' => "GPA score: {$gpaScore}%",
                'income' => "Income fit: {$incomeScore}%",
                'eligibility' => "Eligibility and course match: {$fitScore}%",
                'weights' => [
                    'gpa_weight' => $weightGpa,
                    'income_weight' => $weightIncome,
                    'fit_weight' => $weightFit,
                ],
                'message' => $this->buildAnalysisMessage($profile, $scholarship, $gpaScore, $incomeScore),
            ],
        ]);
    }

    private function scoreGpa(?float $gwa, ?float $requirement): int
    {
        if (! $requirement || ! $gwa) {
            return 100;
        }

        if ($gwa >= $requirement) {
            return 100;
        }

        return max(0, min(100, (int) round(($gwa / $requirement) * 100)));
    }

    private function scoreIncome(?float $income, ?string $bracket): int
    {
        if (! $bracket || ! $income) {
            return 100;
        }

        preg_match('/\d[\d,.]*/', $bracket, $matches);
        if (empty($matches)) {
            return 75;
        }

        $threshold = (float) str_replace(',', '', $matches[0]);
        if ($threshold <= 0) {
            return 75;
        }

        if ($income <= $threshold) {
            return 100;
        }

        $ratio = $threshold / $income;
        return max(0, min(100, (int) round($ratio * 100)));
    }

    private function scoreFit(ApplicantProfile $profile, Scholarship $scholarship): int
    {
        $courseMatch = $this->containsText($scholarship->eligibility, $profile->course_program) ? 100 : 80;
        $schoolMatch = $this->containsText($scholarship->eligibility, $profile->university_name) ? 100 : 75;
        $locationMatch = $this->containsText($scholarship->eligibility, $profile->province) ? 100 : 70;
        $completion = $profile->profile_completed_at ? 100 : 65;

        return (int) round(($courseMatch * 0.4) + ($schoolMatch * 0.2) + ($locationMatch * 0.2) + ($completion * 0.2));
    }

    private function containsText(?string $haystack, ?string $needle): bool
    {
        if (! $haystack || ! $needle) {
            return false;
        }

        return str_contains(strtolower($haystack), strtolower($needle));
    }

    private function normalizeWeight(?int $value, int $default): int
    {
        if (is_null($value)) {
            return $default;
        }

        return max(0, min(80, $value));
    }

    private function buildAnalysisMessage(ApplicantProfile $profile, Scholarship $scholarship, int $gpaScore, int $incomeScore): string
    {
        $parts = [];

        if ($scholarship->gpa_requirement) {
            $parts[] = $profile->gwa >= $scholarship->gpa_requirement
                ? 'GPA exceeds the required threshold.'
                : 'GPA is below the scholarship requirement.';
        } else {
            $parts[] = 'No GPA threshold is defined for this scholarship.';
        }

        if ($scholarship->income_bracket) {
            $parts[] = $incomeScore === 100
                ? 'Income falls within the target bracket.'
                : 'Income is higher than the target bracket, which may reduce eligibility.';
        }

        if ($this->containsText($scholarship->eligibility, $profile->course_program)) {
            $parts[] = 'Course program matches the scholarship eligibility description.';
        }

        if ($this->containsText($scholarship->eligibility, $profile->province)) {
            $parts[] = 'Location is aligned with the scholarship eligibility.';
        }

        return implode(' ', $parts);
    }
}

<?php

namespace App\Services;

use App\Models\ApplicantProfile;
use App\Models\Scholarship;
use Illuminate\Support\Str;

class ScholarshipScoringService
{
    public const WEIGHT_GPA = 0.55;

    public const WEIGHT_COURSE = 0.30;

    public const WEIGHT_INCOME = 0.15;

    public const UNKNOWN_INCOME_SCORE = 75;

    public const EVALUATION_UNKNOWN_INCOME_SCORE = 50;

    public const UNKNOWN_GPA_SCORE = 100;

    public const MISSING_PROFILE_GPA_SCORE = 50;

    public function calculateMatchScore(
        ApplicantProfile $profile,
        Scholarship $scholarship,
        int $missingProfileWithoutRequirementScore = self::UNKNOWN_GPA_SCORE
    ): int {
        $gpaScore = $this->scoreGpa(
            $profile->gwa,
            $scholarship->gpa_requirement,
            $missingProfileWithoutRequirementScore
        );
        $courseScore = $this->scoreCourse($profile->course_program, $scholarship->courses);
        $incomeScore = $this->scoreIncome(
            $profile->monthly_household_income,
            $scholarship->income_bracket
        );

        $score = (int) round(
            ($gpaScore * self::WEIGHT_GPA)
            + ($courseScore * self::WEIGHT_COURSE)
            + ($incomeScore * self::WEIGHT_INCOME)
        );

        return max(0, min(100, $score));
    }

    public function scoreGpa(
        mixed $profileGpa,
        mixed $requirement,
        int $missingProfileWithoutRequirementScore = self::UNKNOWN_GPA_SCORE
    ): int {
        if (blank($requirement) && blank($profileGpa)) {
            return $missingProfileWithoutRequirementScore;
        }

        if (blank($requirement)) {
            return 100;
        }

        if (blank($profileGpa)) {
            return 50;
        }

        $profileGpa = (float) $profileGpa;
        $requirement = (float) $requirement;

        if ($profileGpa <= $requirement) {
            return 100;
        }

        $difference = max(0, $profileGpa - $requirement);

        return max(0, 100 - (int) round($difference * 30));
    }

    public function scoreCourse(mixed $profileCourse, mixed $scholarshipCourses): int
    {
        if (blank($scholarshipCourses)) {
            return 100;
        }

        $profileCourse = Str::lower(trim((string) $profileCourse));
        $courses = is_array($scholarshipCourses)
            ? $scholarshipCourses
            : explode(',', (string) $scholarshipCourses);
        $courses = array_filter(
            array_map(
                fn (mixed $course): string => Str::lower(trim((string) $course)),
                $courses
            )
        );

        if (! $profileCourse || empty($courses)) {
            return 50;
        }

        foreach ($courses as $course) {
            if (
                Str::contains($profileCourse, $course)
                || Str::contains($course, $profileCourse)
            ) {
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

    public function scoreIncome(
        mixed $monthlyIncome,
        mixed $bracket,
        int $unknownBracketScore = self::UNKNOWN_INCOME_SCORE
    ): int {
        if (blank($bracket)) {
            return 100;
        }

        if (is_null($monthlyIncome)) {
            return 50;
        }

        $annualIncome = (float) $monthlyIncome * 12;

        if (preg_match_all('/\d+[\d,]*/', (string) $bracket, $matches)) {
            $numbers = array_map(
                fn (string $value): int => (int) str_replace(',', '', $value),
                $matches[0]
            );

            if (! empty($numbers)) {
                $threshold = max($numbers);

                return $annualIncome <= $threshold ? 100 : 20;
            }
        }

        return $unknownBracketScore;
    }
}

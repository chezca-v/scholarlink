<?php

namespace Tests\Unit;

use App\Models\ApplicantProfile;
use App\Models\Scholarship;
use App\Services\ScholarshipScoringService;
use PHPUnit\Framework\TestCase;

class ScholarshipScoringServiceTest extends TestCase
{
    private ScholarshipScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ScholarshipScoringService;
    }

    public function test_it_calculates_a_perfect_match_score(): void
    {
        $profile = new ApplicantProfile([
            'gwa' => 1.50,
            'course_program' => 'Computer Science',
            'monthly_household_income' => 20000,
        ]);

        $scholarship = new Scholarship([
            'gpa_requirement' => 2.00,
            'courses' => ['Computer Science'],
            'income_bracket' => 'Up to 300,000',
        ]);

        $this->assertSame(100, $this->service->scoreGpa(1.50, 2.00));
        $this->assertSame(
            100,
            $this->service->scoreCourse('Computer Science', ['Computer Science'])
        );
        $this->assertSame(100, $this->service->scoreIncome(20000, 'Up to 300,000'));
        $this->assertSame(100, $this->service->calculateMatchScore($profile, $scholarship));
    }

    public function test_it_preserves_the_baseline_partial_match_score(): void
    {
        $profile = new ApplicantProfile([
            'gwa' => 2.00,
            'course_program' => 'Computer Science',
            'monthly_household_income' => 20000,
        ]);

        $scholarship = new Scholarship([
            'gpa_requirement' => 1.50,
            'courses' => ['Computer Engineering'],
            'income_bracket' => 'Up to 300,000',
        ]);

        $this->assertSame(85, $this->service->scoreGpa(2.00, 1.50));
        $this->assertSame(
            90,
            $this->service->scoreCourse('Computer Science', ['Computer Engineering'])
        );
        $this->assertSame(89, $this->service->calculateMatchScore($profile, $scholarship));
    }

    public function test_it_preserves_unknown_income_bracket_scores(): void
    {
        $this->assertSame(
            ScholarshipScoringService::UNKNOWN_INCOME_SCORE,
            $this->service->scoreIncome(20000, 'Contact provider')
        );

        $this->assertSame(
            ScholarshipScoringService::EVALUATION_UNKNOWN_INCOME_SCORE,
            $this->service->scoreIncome(
                20000,
                'Contact provider',
                ScholarshipScoringService::EVALUATION_UNKNOWN_INCOME_SCORE
            )
        );
    }

    public function test_it_preserves_legacy_missing_gpa_behavior(): void
    {
        $this->assertSame(
            ScholarshipScoringService::UNKNOWN_GPA_SCORE,
            $this->service->scoreGpa(null, null)
        );

        $this->assertSame(
            ScholarshipScoringService::MISSING_PROFILE_GPA_SCORE,
            $this->service->scoreGpa(
                null,
                null,
                ScholarshipScoringService::MISSING_PROFILE_GPA_SCORE
            )
        );
    }
}

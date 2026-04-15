<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluation;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $evaluations = [
            // ── DECIDED EVALUATIONS (final_score + decision set) ──

            // Eval 1 — App 1 (Maria → Gabay Dunong) — approved
            [
                'application_id'   => 1,
                'evaluator_id'     => 11,
                'gpa_score'        => 95.00,
                'income_score'     => 90.00,
                'final_score'      => 92.50,
                'decision'         => 'approved',
                'rejection_reason' => null,
                'notes'            => 'Strong GPA, household income qualifies. Recommended for full benefit.',
                'evaluated_at'     => '2026-02-14 00:00:00',
            ],
            // Eval 2 — App 4 (Juan → Lingap Kabataan) — rejected
            [
                'application_id'   => 4,
                'evaluator_id'     => 14,
                'gpa_score'        => 72.00,
                'income_score'     => 68.00,
                'final_score'      => 70.00,
                'decision'         => 'rejected',
                'rejection_reason' => 'income_bracket',
                'notes'            => 'Household income slightly exceeds bracket. GPA acceptable.',
                'evaluated_at'     => '2026-02-09 00:00:00',
            ],
            // Eval 3 — App 7 (Angela → AccessED) — approved
            [
                'application_id'   => 7,
                'evaluator_id'     => 12,
                'gpa_score'        => 93.00,
                'income_score'     => 89.00,
                'final_score'      => 91.00,
                'decision'         => 'approved',
                'rejection_reason' => null,
                'notes'            => 'Excellent academic standing. Fully qualified under all criteria.',
                'evaluated_at'     => '2026-02-11 00:00:00',
            ],
            // Eval 4 — App 11 (Kevin → EmpowerED) — rejected
            [
                'application_id'   => 11,
                'evaluator_id'     => 16,
                'gpa_score'        => 68.00,
                'income_score'     => 62.00,
                'final_score'      => 65.00,
                'decision'         => 'rejected',
                'rejection_reason' => 'gpa',
                'notes'            => 'GWA does not meet required threshold. Suggested to reapply next cycle.',
                'evaluated_at'     => '2026-02-07 00:00:00',
            ],
            // Eval 5 — App 13 (Liz → Bukas Palad) — approved
            [
                'application_id'   => 13,
                'evaluator_id'     => 15,
                'gpa_score'        => 88.00,
                'income_score'     => 86.00,
                'final_score'      => 87.00,
                'decision'         => 'approved',
                'rejection_reason' => null,
                'notes'            => 'Qualified applicant. Financial need confirmed, GWA above requirement.',
                'evaluated_at'     => '2026-02-13 00:00:00',
            ],
            // Eval 6 — App 19 (Nicole → Lingap Kabataan) — approved
            [
                'application_id'   => 19,
                'evaluator_id'     => 14,
                'gpa_score'        => 78.00,
                'income_score'     => 74.00,
                'final_score'      => 76.00,
                'decision'         => 'approved',
                'rejection_reason' => null,
                'notes'            => 'Community residency verified. Meets all eligibility criteria.',
                'evaluated_at'     => '2026-02-10 00:00:00',
            ],
            // Eval 7 — App 23 (Joshua → Catalyst) — approved
            [
                'application_id'   => 23,
                'evaluator_id'     => 19,
                'gpa_score'        => 87.00,
                'income_score'     => 86.00,
                'final_score'      => 86.50,
                'decision'         => 'approved',
                'rejection_reason' => null,
                'notes'            => 'Leadership qualities evident. GWA and income bracket fully qualify.',
                'evaluated_at'     => '2026-02-15 00:00:00',
            ],
            // Eval 8 — App 25 (Fatima → Bukas Palad) — rejected
            [
                'application_id'   => 25,
                'evaluator_id'     => 15,
                'gpa_score'        => 71.00,
                'income_score'     => 67.00,
                'final_score'      => 69.00,
                'decision'         => 'rejected',
                'rejection_reason' => 'docs',
                'notes'            => 'Missing notarized affidavit of financial need. Incomplete submission.',
                'evaluated_at'     => '2026-02-08 00:00:00',
            ],
            // Eval 9 — App 28 (Alex → Gabay Dunong) — approved
            [
                'application_id'   => 28,
                'evaluator_id'     => 11,
                'gpa_score'        => 94.00,
                'income_score'     => 92.00,
                'final_score'      => 93.00,
                'decision'         => 'approved',
                'rejection_reason' => null,
                'notes'            => 'Top applicant for this cycle. Exceptional academic record.',
                'evaluated_at'     => '2026-02-12 00:00:00',
            ],

            // ── IN PROGRESS EVALUATIONS (final_score + decision null) ──

            // Eval 10 — App 2 (Maria → STEM Forward) — in progress
            [
                'application_id'   => 2,
                'evaluator_id'     => 17,
                'gpa_score'        => 89.00,
                'income_score'     => 87.00,
                'final_score'      => null,
                'decision'         => null,
                'rejection_reason' => null,
                'notes'            => 'Under scoring review. Awaiting final deliberation.',
                'evaluated_at'     => null,
            ],
            // Eval 11 — App 8 (Angela → Summit) — in progress
            [
                'application_id'   => 8,
                'evaluator_id'     => 13,
                'gpa_score'        => 90.00,
                'income_score'     => 89.00,
                'final_score'      => null,
                'decision'         => null,
                'rejection_reason' => null,
                'notes'            => 'Documents verified. Scoring in progress.',
                'evaluated_at'     => null,
            ],
            // Eval 12 — App 14 (Liz → LiftED) — in progress
            [
                'application_id'   => 14,
                'evaluator_id'     => 15,
                'gpa_score'        => 84.00,
                'income_score'     => 83.00,
                'final_score'      => null,
                'decision'         => null,
                'rejection_reason' => null,
                'notes'            => 'Financial documents confirmed. Final score pending.',
                'evaluated_at'     => null,
            ],
            // Eval 13 — App 17 (Mark → STEM Forward) — in progress
            [
                'application_id'   => 17,
                'evaluator_id'     => 17,
                'gpa_score'        => 83.00,
                'income_score'     => 81.00,
                'final_score'      => null,
                'decision'         => null,
                'rejection_reason' => null,
                'notes'            => 'STEM qualification verified. Awaiting final score.',
                'evaluated_at'     => null,
            ],
            // Eval 14 — App 22 (Joshua → ExcelEdge) — in progress
            [
                'application_id'   => 22,
                'evaluator_id'     => 19,
                'gpa_score'        => 85.00,
                'income_score'     => 83.00,
                'final_score'      => null,
                'decision'         => null,
                'rejection_reason' => null,
                'notes'            => 'Leadership assessment completed. Score pending.',
                'evaluated_at'     => null,
            ],
            // Eval 15 — App 29 (Alex → STEM Forward) — in progress
            [
                'application_id'   => 29,
                'evaluator_id'     => 17,
                'gpa_score'        => 91.00,
                'income_score'     => 89.00,
                'final_score'      => null,
                'decision'         => null,
                'rejection_reason' => null,
                'notes'            => 'Strong STEM profile. Scoring near completion.',
                'evaluated_at'     => null,
            ],
        ];

        foreach ($evaluations as $evaluation) {
            Evaluation::create($evaluation);
        }
    }
}

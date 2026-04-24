<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluatorAssignment;

class EvaluatorAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            // Luz Santos (11) → Gabay Dunong Scholarship (1)
            [
                'evaluator_id'  => 11,
                'scholarship_id' => 1,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Jose Reyes (12) → AccessED College Support Grant (2)
            [
                'evaluator_id'  => 12,
                'scholarship_id' => 2,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Ana Cruz (13) → Summit Scholars Grant (3)
            [
                'evaluator_id'  => 13,
                'scholarship_id' => 3,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Carlo Mendoza (14) → Lingap Kabataan Scholarship (4)
            [
                'evaluator_id'  => 14,
                'scholarship_id' => 4,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Liza Bautista (15) → Bukas Palad Scholarship (5)
            [
                'evaluator_id'  => 15,
                'scholarship_id' => 5,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Ramon Garcia (16) → EmpowerED Academic Scholarship (6)
            [
                'evaluator_id'  => 16,
                'scholarship_id' => 6,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Cynthia Torres (17) → STEM Forward Scholars Program (7)
            [
                'evaluator_id'  => 17,
                'scholarship_id' => 7,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Mark Villanueva (18) → Bright Horizons Academic Grant (8)
            [
                'evaluator_id'  => 18,
                'scholarship_id' => 8,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Patricia Flores (19) → ExcelEdge Merit Scholarship (9)
            [
                'evaluator_id'  => 19,
                'scholarship_id' => 9,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Dennis Aguilar (20) → Lingap Kabataan Scholarship (4)
            [
                'evaluator_id'  => 20,
                'scholarship_id' => 4,
                'assigned_by'   => null,
                'assigned_at'   => '2026-01-02 00:00:00',
            ],
            // Jose Reyes (12) → Bagong Alab Scholars Fund (21)
            [
                'evaluator_id'   => 12,
                'scholarship_id' => 21,
                'assigned_by'    => null,
                'assigned_at'    => '2026-03-01 00:00:00',
            ],
            // Ana Cruz (13) → Sindak ng Pag-asa Scholarship (22)
            [
                'evaluator_id'   => 13,
                'scholarship_id' => 22,
                'assigned_by'    => null,
                'assigned_at'    => '2026-03-01 00:00:00',
            ],
            // Liza Bautista (15) → Talino at Puso Grant (23)
            [
                'evaluator_id'   => 15,
                'scholarship_id' => 23,
                'assigned_by'    => null,
                'assigned_at'    => '2026-03-15 00:00:00',
            ],
            // Carlo Mendoza (14) → Lakbay Dunong Scholarship (24)
            [
                'evaluator_id'   => 14,
                'scholarship_id' => 24,
                'assigned_by'    => null,
                'assigned_at'    => '2026-03-15 00:00:00',
            ],
            // Patricia Flores (19) → Haligi ng Bayan Academic Award (25)
            [
                'evaluator_id'   => 19,
                'scholarship_id' => 25,
                'assigned_by'    => null,
                'assigned_at'    => '2026-04-01 00:00:00',
            ],
        ];

        foreach ($assignments as $assignment) {
            EvaluatorAssignment::create($assignment);
        }
    }
}

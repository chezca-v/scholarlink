<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Users first — applicants and evaluators
            UserSeeder::class,

            // 2. Applicant profiles — depends on users
            ApplicantProfileSeeder::class,

            // 3. Scholarships — depends on users (created_by)
            ScholarshipSeeder::class,

            // 4. Documents — depends on users
            DocumentSeeder::class,

            // 5. Applications — depends on users + scholarships
            ApplicationSeeder::class,

            // 6. Evaluations — depends on applications + users
            EvaluationSeeder::class,

            // 7. Notifications — depends on users
            NotificationSeeder::class,

            // 8. Activity logs — depends on users
            ActivityLogSeeder::class,

            // 9. Evaluator assignments — depends on users + scholarships
            EvaluatorAssignmentSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            // ── APPLICATION SUBMISSIONS ───────────────────────
            [
                'user_id'     => 1,
                'action'      => 'submitted_application',
                'target_type' => 'Application',
                'target_id'   => 1,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-10 00:00:00',
            ],
            [
                'user_id'     => 2,
                'action'      => 'submitted_application',
                'target_type' => 'Application',
                'target_id'   => 4,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-08 00:00:00',
            ],
            [
                'user_id'     => 3,
                'action'      => 'submitted_application',
                'target_type' => 'Application',
                'target_id'   => 7,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-09 00:00:00',
            ],
            [
                'user_id'     => 4,
                'action'      => 'submitted_application',
                'target_type' => 'Application',
                'target_id'   => 11,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-05 00:00:00',
            ],
            [
                'user_id'     => 5,
                'action'      => 'submitted_application',
                'target_type' => 'Application',
                'target_id'   => 13,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-07 00:00:00',
            ],

            // ── DOCUMENT UPLOADS ──────────────────────────────
            [
                'user_id'     => 6,
                'action'      => 'uploaded_document',
                'target_type' => 'Document',
                'target_id'   => 1,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-14 00:00:00',
            ],
            [
                'user_id'     => 7,
                'action'      => 'uploaded_document',
                'target_type' => 'Document',
                'target_id'   => 5,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-06 00:00:00',
            ],
            [
                'user_id'     => 8,
                'action'      => 'uploaded_document',
                'target_type' => 'Document',
                'target_id'   => 9,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-11 00:00:00',
            ],
            [
                'user_id'     => 9,
                'action'      => 'uploaded_document',
                'target_type' => 'Document',
                'target_id'   => 13,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-04 00:00:00',
            ],

            // ── SCHOLARSHIP VIEWS ─────────────────────────────
            [
                'user_id'     => 10,
                'action'      => 'viewed_scholarship',
                'target_type' => 'Scholarship',
                'target_id'   => 7,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-17 00:00:00',
            ],
            [
                'user_id'     => 1,
                'action'      => 'viewed_scholarship',
                'target_type' => 'Scholarship',
                'target_id'   => 2,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-12 00:00:00',
            ],

            // ── SAVED SCHOLARSHIPS ────────────────────────────
            [
                'user_id'     => 3,
                'action'      => 'saved_scholarship',
                'target_type' => 'Scholarship',
                'target_id'   => 3,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-15 00:00:00',
            ],
            [
                'user_id'     => 8,
                'action'      => 'saved_scholarship',
                'target_type' => 'Scholarship',
                'target_id'   => 14,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-16 00:00:00',
            ],

            // ── EVALUATIONS ───────────────────────────────────
            [
                'user_id'     => 11,
                'action'      => 'evaluated_application',
                'target_type' => 'Application',
                'target_id'   => 1,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-02-14 00:00:00',
            ],
            [
                'user_id'     => 12,
                'action'      => 'evaluated_application',
                'target_type' => 'Application',
                'target_id'   => 7,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-02-11 00:00:00',
            ],
            [
                'user_id'     => 13,
                'action'      => 'evaluated_application',
                'target_type' => 'Application',
                'target_id'   => 8,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-02-11 00:00:00',
            ],
            [
                'user_id'     => 14,
                'action'      => 'evaluated_application',
                'target_type' => 'Application',
                'target_id'   => 11,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-02-07 00:00:00',
            ],

            // ── PROFILE UPDATES ───────────────────────────────
            [
                'user_id'     => 1,
                'action'      => 'updated_profile',
                'target_type' => 'User',
                'target_id'   => 1,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-10 00:00:00',
            ],
            [
                'user_id'     => 5,
                'action'      => 'updated_profile',
                'target_type' => 'User',
                'target_id'   => 5,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-07 00:00:00',
            ],

            // ── LOGIN ─────────────────────────────────────────
            [
                'user_id'     => 9,
                'action'      => 'login',
                'target_type' => 'User',
                'target_id'   => 9,
                'ip_address'  => null,
                'user_agent'  => null,
                'created_at'  => '2026-01-21 00:00:00',
            ],
        ];

        foreach ($logs as $log) {
            ActivityLog::create($log);
        }
    }
}

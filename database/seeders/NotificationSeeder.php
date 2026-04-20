<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            // ── USER 1 — Maria Santos ─────────────────────────
            [
                'user_id'    => 1,
                'type'       => 'email',
                'title'      => 'Application Status Update',
                'body'       => 'Your application for Gabay Dunong Scholarship has been updated to: approved.',
                'is_read'    => 0,
                'related_id' => 1,
                'created_at' => '2026-02-15 00:00:00',
            ],
            [
                'user_id'    => 1,
                'type'       => 'sms',
                'title'      => 'ScholarLink Update',
                'body'       => 'Your GD-2026-00001 application status changed to approved. Log in to view details.',
                'is_read'    => 0,
                'related_id' => 1,
                'created_at' => '2026-02-15 00:00:00',
            ],
            [
                'user_id'    => 1,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your PSA Birth Certificate has been verified successfully.',
                'is_read'    => 1,
                'related_id' => 1,
                'created_at' => '2026-01-10 00:00:00',
            ],

            // ── USER 2 — Juan Dela Cruz ───────────────────────
            [
                'user_id'    => 2,
                'type'       => 'email',
                'title'      => 'Application Status Update',
                'body'       => 'Your application for Lingap Kabataan Scholarship has been updated to: rejected.',
                'is_read'    => 0,
                'related_id' => 4,
                'created_at' => '2026-02-10 00:00:00',
            ],
            [
                'user_id'    => 2,
                'type'       => 'in_app',
                'title'      => 'Application Rejected',
                'body'       => 'Your application LK-2026-00001 was not approved. See evaluator feedback for details.',
                'is_read'    => 0,
                'related_id' => 4,
                'created_at' => '2026-02-10 00:00:00',
            ],
            [
                'user_id'    => 2,
                'type'       => 'in_app',
                'title'      => 'Action Required',
                'body'       => 'Please resubmit your application for Unity Scholars Financial Aid Program. Reason: revision requested.',
                'is_read'    => 1,
                'related_id' => 5,
                'created_at' => '2026-01-20 00:00:00',
            ],

            // ── USER 3 — Angela Reyes ─────────────────────────
            [
                'user_id'    => 3,
                'type'       => 'email',
                'title'      => 'Congratulations!',
                'body'       => 'You have been approved for the AccessED College Support Grant. Please check your dashboard for next steps.',
                'is_read'    => 1,
                'related_id' => 7,
                'created_at' => '2026-02-12 00:00:00',
            ],
            [
                'user_id'    => 3,
                'type'       => 'sms',
                'title'      => 'ScholarLink Update',
                'body'       => 'Your AE-2026-00001 application status changed to approved. Log in to view details.',
                'is_read'    => 0,
                'related_id' => 7,
                'created_at' => '2026-02-12 00:00:00',
            ],
            [
                'user_id'    => 3,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your Latest Report Card / TOR has been verified successfully.',
                'is_read'    => 0,
                'related_id' => 11,
                'created_at' => '2026-01-09 00:00:00',
            ],

            // ── USER 4 — Kevin Cruz ───────────────────────────
            [
                'user_id'    => 4,
                'type'       => 'email',
                'title'      => 'Application Status Update',
                'body'       => 'Your application for EmpowerED Academic Scholarship has been updated to: rejected.',
                'is_read'    => 0,
                'related_id' => 11,
                'created_at' => '2026-02-08 00:00:00',
            ],
            [
                'user_id'    => 4,
                'type'       => 'in_app',
                'title'      => 'Application Rejected',
                'body'       => 'Your application EP-2026-00001 was not approved. See evaluator feedback for details.',
                'is_read'    => 0,
                'related_id' => 11,
                'created_at' => '2026-02-08 00:00:00',
            ],
            [
                'user_id'    => 4,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your PSA Birth Certificate has been verified successfully.',
                'is_read'    => 1,
                'related_id' => 13,
                'created_at' => '2026-01-05 00:00:00',
            ],

            // ── USER 5 — Liz Mendoza ──────────────────────────
            [
                'user_id'    => 5,
                'type'       => 'email',
                'title'      => 'Congratulations!',
                'body'       => 'You have been approved for the Bukas Palad Scholarship. Please check your dashboard for next steps.',
                'is_read'    => 1,
                'related_id' => 13,
                'created_at' => '2026-02-14 00:00:00',
            ],
            [
                'user_id'    => 5,
                'type'       => 'sms',
                'title'      => 'ScholarLink Update',
                'body'       => 'Your BP-2026-00001 application status changed to approved. Log in to view details.',
                'is_read'    => 0,
                'related_id' => 13,
                'created_at' => '2026-02-14 00:00:00',
            ],
            [
                'user_id'    => 5,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your Barangay Certificate of Indigency has been verified successfully.',
                'is_read'    => 0,
                'related_id' => 18,
                'created_at' => '2026-01-07 00:00:00',
            ],

            // ── USER 6 — Mark Villanueva ──────────────────────
            [
                'user_id'    => 6,
                'type'       => 'in_app',
                'title'      => 'Action Required',
                'body'       => 'Please resubmit your application for Bright Horizons Academic Grant. Reason: revision requested.',
                'is_read'    => 0,
                'related_id' => 16,
                'created_at' => '2026-01-14 00:00:00',
            ],
            [
                'user_id'    => 6,
                'type'       => 'email',
                'title'      => 'Application Status Update',
                'body'       => 'Your application for Bright Horizons Academic Grant has been updated to: revision.',
                'is_read'    => 0,
                'related_id' => 16,
                'created_at' => '2026-01-14 00:00:00',
            ],
            [
                'user_id'    => 6,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your PSA Birth Certificate has been verified successfully.',
                'is_read'    => 1,
                'related_id' => 21,
                'created_at' => '2026-01-12 00:00:00',
            ],

            // ── USER 7 — Nicole Garcia ────────────────────────
            [
                'user_id'    => 7,
                'type'       => 'email',
                'title'      => 'Congratulations!',
                'body'       => 'You have been approved for the Lingap Kabataan Scholarship. Please check your dashboard for next steps.',
                'is_read'    => 1,
                'related_id' => 19,
                'created_at' => '2026-02-11 00:00:00',
            ],
            [
                'user_id'    => 7,
                'type'       => 'sms',
                'title'      => 'ScholarLink Update',
                'body'       => 'Your LK-2026-00002 application status changed to approved. Log in to view details.',
                'is_read'    => 0,
                'related_id' => 19,
                'created_at' => '2026-02-11 00:00:00',
            ],
            [
                'user_id'    => 7,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your Barangay Certificate of Indigency has been verified successfully.',
                'is_read'    => 0,
                'related_id' => 26,
                'created_at' => '2026-01-06 00:00:00',
            ],

            // ── USER 8 — Joshua Lim ───────────────────────────
            [
                'user_id'    => 8,
                'type'       => 'email',
                'title'      => 'Congratulations!',
                'body'       => 'You have been approved for the Catalyst Scholars Program. Please check your dashboard for next steps.',
                'is_read'    => 1,
                'related_id' => 23,
                'created_at' => '2026-02-16 00:00:00',
            ],
            [
                'user_id'    => 8,
                'type'       => 'sms',
                'title'      => 'ScholarLink Update',
                'body'       => 'Your CS-2026-00001 application status changed to approved. Log in to view details.',
                'is_read'    => 0,
                'related_id' => 23,
                'created_at' => '2026-02-16 00:00:00',
            ],
            [
                'user_id'    => 8,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your Latest Report Card / TOR has been verified successfully.',
                'is_read'    => 0,
                'related_id' => 31,
                'created_at' => '2026-01-11 00:00:00',
            ],

            // ── USER 9 — Fatima Hassan ────────────────────────
            [
                'user_id'    => 9,
                'type'       => 'email',
                'title'      => 'Application Status Update',
                'body'       => 'Your application for Bukas Palad Scholarship has been updated to: rejected.',
                'is_read'    => 0,
                'related_id' => 25,
                'created_at' => '2026-02-09 00:00:00',
            ],
            [
                'user_id'    => 9,
                'type'       => 'in_app',
                'title'      => 'Application Rejected',
                'body'       => 'Your application BP-2026-00002 was not approved. See evaluator feedback for details.',
                'is_read'    => 0,
                'related_id' => 25,
                'created_at' => '2026-02-09 00:00:00',
            ],
            [
                'user_id'    => 9,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your PSA Birth Certificate has been verified successfully.',
                'is_read'    => 1,
                'related_id' => 33,
                'created_at' => '2026-01-04 00:00:00',
            ],

            // ── USER 10 — Alex Rivera ─────────────────────────
            [
                'user_id'    => 10,
                'type'       => 'email',
                'title'      => 'Congratulations!',
                'body'       => 'You have been approved for the Gabay Dunong Scholarship. Please check your dashboard for next steps.',
                'is_read'    => 1,
                'related_id' => 28,
                'created_at' => '2026-02-13 00:00:00',
            ],
            [
                'user_id'    => 10,
                'type'       => 'sms',
                'title'      => 'ScholarLink Update',
                'body'       => 'Your GD-2026-00002 application status changed to approved. Log in to view details.',
                'is_read'    => 0,
                'related_id' => 28,
                'created_at' => '2026-02-13 00:00:00',
            ],
            [
                'user_id'    => 10,
                'type'       => 'in_app',
                'title'      => 'Document Verified',
                'body'       => 'Your Barangay Certificate of Indigency has been verified successfully.',
                'is_read'    => 0,
                'related_id' => 38,
                'created_at' => '2026-01-03 00:00:00',
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}

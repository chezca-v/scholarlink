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
            // ── USER 21 — Sofia Aguilar ───────────────────────
            [
                'user_id'    => 21,
                'type'       => 'email',
                'title'      => 'Application Submitted',
                'body'       => 'Your application for Bagong Alab Scholars Fund has been successfully submitted.',
                'is_read'    => 0,
                'related_id' => 31,
                'created_at' => '2026-04-10 00:00:00',
            ],
            [
                'user_id'    => 21,
                'type'       => 'in_app',
                'title'      => 'Application Approved',
                'body'       => 'Congratulations! Your application for Bagong Alab Scholars Fund has been approved.',
                'is_read'    => 1,
                'related_id' => 31,
                'created_at' => '2026-05-20 00:00:00',
            ],
            [
                'user_id'    => 21,
                'type'       => 'sms',
                'title'      => 'Deadline Reminder',
                'body'       => 'The deadline for Sindak ng Pag-asa Scholarship is approaching. Submit your documents soon.',
                'is_read'    => 0,
                'related_id' => 32,
                'created_at' => '2026-05-10 00:00:00',
            ],

            // ── USER 22 — Miguel Torres ───────────────────────
            [
                'user_id'    => 22,
                'type'       => 'email',
                'title'      => 'Application Submitted',
                'body'       => 'Your application for Sindak ng Pag-asa Scholarship has been successfully submitted.',
                'is_read'    => 0,
                'related_id' => 34,
                'created_at' => '2026-04-08 00:00:00',
            ],
            [
                'user_id'    => 22,
                'type'       => 'in_app',
                'title'      => 'Application Rejected',
                'body'       => 'Your application for Sindak ng Pag-asa Scholarship was not approved. Please view feedback.',
                'is_read'    => 0,
                'related_id' => 34,
                'created_at' => '2026-05-18 00:00:00',
            ],
            [
                'user_id'    => 22,
                'type'       => 'sms',
                'title'      => 'Deadline Reminder',
                'body'       => 'The deadline for Haligi ng Bayan Academic Award is approaching. Submit your documents soon.',
                'is_read'    => 1,
                'related_id' => 36,
                'created_at' => '2026-05-12 00:00:00',
            ],

            // ── USER 23 — Hannah Pascual ──────────────────────
            [
                'user_id'    => 23,
                'type'       => 'email',
                'title'      => 'Application Submitted',
                'body'       => 'Your application for Talino at Puso Grant has been successfully submitted.',
                'is_read'    => 1,
                'related_id' => 37,
                'created_at' => '2026-04-09 00:00:00',
            ],
            [
                'user_id'    => 23,
                'type'       => 'in_app',
                'title'      => 'Application Approved',
                'body'       => 'Congratulations! Your application for Talino at Puso Grant has been approved.',
                'is_read'    => 0,
                'related_id' => 37,
                'created_at' => '2026-05-19 00:00:00',
            ],
            [
                'user_id'    => 23,
                'type'       => 'sms',
                'title'      => 'Document Status Update',
                'body'       => 'Your Barangay Certificate has been verified. Please check your document wallet.',
                'is_read'    => 0,
                'related_id' => 50,
                'created_at' => '2026-04-15 00:00:00',
            ],

            // ── USER 24 — Rainier Domingo ─────────────────────
            [
                'user_id'    => 24,
                'type'       => 'email',
                'title'      => 'Application Submitted',
                'body'       => 'Your application for Lakbay Dunong Scholarship has been successfully submitted.',
                'is_read'    => 0,
                'related_id' => 40,
                'created_at' => '2026-04-07 00:00:00',
            ],
            [
                'user_id'    => 24,
                'type'       => 'in_app',
                'title'      => 'Application Approved',
                'body'       => 'Congratulations! Your application for Lakbay Dunong Scholarship has been approved.',
                'is_read'    => 1,
                'related_id' => 40,
                'created_at' => '2026-05-17 00:00:00',
            ],
            [
                'user_id'    => 24,
                'type'       => 'sms',
                'title'      => 'Deadline Reminder',
                'body'       => 'The deadline for Sindak ng Pag-asa Scholarship is approaching. Complete your submission.',
                'is_read'    => 0,
                'related_id' => 41,
                'created_at' => '2026-05-11 00:00:00',
            ],

            // ── USER 25 — Camille Navarro ─────────────────────
            [
                'user_id'    => 25,
                'type'       => 'email',
                'title'      => 'Application Submitted',
                'body'       => 'Your application for Haligi ng Bayan Academic Award has been successfully submitted.',
                'is_read'    => 1,
                'related_id' => 43,
                'created_at' => '2026-04-06 00:00:00',
            ],
            [
                'user_id'    => 25,
                'type'       => 'in_app',
                'title'      => 'Application Approved',
                'body'       => 'Congratulations! Your application for Haligi ng Bayan Academic Award has been approved.',
                'is_read'    => 0,
                'related_id' => 43,
                'created_at' => '2026-05-16 00:00:00',
            ],
            [
                'user_id'    => 25,
                'type'       => 'sms',
                'title'      => 'Document Status Update',
                'body'       => 'Your SHS Form 138 has been verified. Your document wallet is now complete.',
                'is_read'    => 1,
                'related_id' => 59,
                'created_at' => '2026-04-12 00:00:00',
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'featureFlags'],
            ['value' => [
                'ai_matching' => [
                    'label' => 'AI Scholarship Matching',
                    'description' => 'Uses Gemini API to match applicants with scholarships.',
                    'enabled' => true,
                ],
                'sms_notifications' => [
                    'label' => 'SMS Notifications',
                    'description' => 'Send SMS updates via ESP32 gateway.',
                    'enabled' => true,
                ],
                'public_registration' => [
                    'label' => 'Public Applicant Registration',
                    'description' => 'Allow anyone to register as an applicant.',
                    'enabled' => true,
                ],
                'auto_approve_orgs' => [
                    'label' => 'Auto-Approve Organizations',
                    'description' => 'Skip manual review for new organizations.',
                    'enabled' => false,
                ],
            ]]
        );

        Setting::updateOrCreate(
            ['key' => 'integrations'],
            ['value' => [
                [
                    'name' => 'Gemini API',
                    'description' => 'AI engine for document parsing and matching.',
                    'status' => 'connected',
                ],
                [
                    'name' => 'ESP32 SMS Gateway',
                    'description' => 'Hardware interface for sending SMS alerts.',
                    'status' => 'online',
                ],
                [
                    'name' => 'Email SMTP',
                    'description' => 'Primary email delivery service.',
                    'status' => 'active',
                ],
                [
                    'name' => 'PhilSys ID Verify',
                    'description' => 'National ID validation endpoint.',
                    'status' => 'error',
                ],
            ]]
        );

        Setting::updateOrCreate(
            ['key' => 'notificationTemplates'],
            ['value' => [
                'welcome_email' => [
                    'label' => 'Welcome Email (Applicants)',
                    'rows' => 4,
                    'content' => "Hi {{name}},\n\nWelcome to ScholarLink! We're excited to help you find the right scholarships.\n\nBest,\nThe ScholarLink Team",
                ],
                'status_update' => [
                    'label' => 'Application Status Update',
                    'rows' => 3,
                    'content' => "Hello {{name}},\n\nYour application for {{scholarship}} is now {{status}}.",
                ],
                'admin_invite' => [
                    'label' => 'Admin Invitation',
                    'rows' => 3,
                    'content' => "Hi {{name}},\n\nYou've been invited to manage your organization. Click here to set up your account.",
                ],
            ]]
        );

        Setting::updateOrCreate(
            ['key' => 'permissionsMatrix'],
            ['value' => [
                'superadmin' => [
                    'icon' => '👑',
                    'label' => 'Superadmin',
                    'permissions' => [
                        'manage_users' => ['label' => 'Manage Users', 'enabled' => true],
                        'manage_orgs' => ['label' => 'Manage Orgs', 'enabled' => true],
                        'system_settings' => ['label' => 'System Settings', 'enabled' => true],
                        'view_logs' => ['label' => 'View Logs', 'enabled' => true],
                    ],
                ],
                'admin' => [
                    'icon' => '🏛️',
                    'label' => 'Org Admin',
                    'permissions' => [
                        'manage_users' => ['label' => 'Manage Users', 'enabled' => false],
                        'manage_orgs' => ['label' => 'Manage Orgs', 'enabled' => false],
                        'manage_scholarships' => ['label' => 'Manage Scholarships', 'enabled' => true],
                        'review_applications' => ['label' => 'Review Applications', 'enabled' => true],
                    ],
                ],
                'evaluator' => [
                    'icon' => '📋',
                    'label' => 'Evaluator',
                    'permissions' => [
                        'manage_scholarships' => ['label' => 'Manage Scholarships', 'enabled' => false],
                        'review_applications' => ['label' => 'Review Applications', 'enabled' => true],
                        'add_notes' => ['label' => 'Add Notes', 'enabled' => true],
                    ],
                ],
                'applicant' => [
                    'icon' => '🎓',
                    'label' => 'Applicant',
                    'permissions' => [
                        'apply_scholarships' => ['label' => 'Apply', 'enabled' => true],
                        'view_status' => ['label' => 'View Status', 'enabled' => true],
                    ],
                ],
            ]]
        );
    }
}

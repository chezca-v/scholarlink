<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── APPLICANTS (user IDs 1–10) ────────────────────────
        $applicants = [
            ['first_name' => 'Maria',  'last_name' => 'Santos',     'email' => 'maria.santos@gmail.com',   'password' => 'Maria@001'],
            ['first_name' => 'Juan',   'last_name' => 'Dela Cruz',  'email' => 'juan.delacruz@gmail.com',  'password' => 'Juan@002'],
            ['first_name' => 'Angela', 'last_name' => 'Reyes',      'email' => 'angela.reyes@gmail.com',   'password' => 'Angela@003'],
            ['first_name' => 'Kevin',  'last_name' => 'Cruz',       'email' => 'kevin.cruz@gmail.com',     'password' => 'Kevin@004'],
            ['first_name' => 'Liz',    'last_name' => 'Mendoza',    'email' => 'liza.mendoza@gmail.com',   'password' => 'Liz@005'],
            ['first_name' => 'Mark',   'last_name' => 'Villanueva', 'email' => 'mark.v@gmail.com',         'password' => 'Mark@006'],
            ['first_name' => 'Nicole', 'last_name' => 'Garcia',     'email' => 'nicole.g@gmail.com',       'password' => 'Nicole@007'],
            ['first_name' => 'Joshua', 'last_name' => 'Lim',        'email' => 'josh.lim@gmail.com',       'password' => 'Joshua@008'],
            ['first_name' => 'Fatima', 'last_name' => 'Hassan',     'email' => 'fatima.h@gmail.com',       'password' => 'Fatima@009'],
            ['first_name' => 'Alex',   'last_name' => 'Rivera',     'email' => 'alex.rivera@gmail.com',    'password' => 'Alex@010'],
            ['first_name' => 'Sofia',   'last_name' => 'Aguilar', 'email' => 'sofia.aguilar@gmail.com',   'password' => 'Sofia@021'],
            ['first_name' => 'Miguel',  'last_name' => 'Torres',  'email' => 'miguel.torres@gmail.com',   'password' => 'Miguel@022'],
            ['first_name' => 'Hannah',  'last_name' => 'Pascual', 'email' => 'hannah.pascual@gmail.com',  'password' => 'Hannah@023'],
            ['first_name' => 'Rainier', 'last_name' => 'Domingo', 'email' => 'rainier.domingo@gmail.com', 'password' => 'Rainier@024'],
            ['first_name' => 'Camille', 'last_name' => 'Navarro', 'email' => 'camille.navarro@gmail.com', 'password' => 'Camille@025'],
        ];

        foreach ($applicants as $applicant) {
            User::create([
                'first_name'        => $applicant['first_name'],
                'last_name'         => $applicant['last_name'],
                'email'             => $applicant['email'],
                'password'          => Hash::make($applicant['password']),
                'role'              => 'applicant',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ]);
        }

        // ── EVALUATORS (user IDs 11–20) ───────────────────────
        $evaluators = [
            ['first_name' => 'Luz',     'last_name' => 'Santos',     'email' => 'evaluator.santos@scholarlink.ph',      'password' => 'Luz@011'],
            ['first_name' => 'Jose',    'last_name' => 'Reyes',      'email' => 'evaluator.reyes@scholarlink.ph',       'password' => 'Jose@012'],
            ['first_name' => 'Ana',     'last_name' => 'Cruz',       'email' => 'evaluator.cruz@scholarlink.ph',        'password' => 'Ana@013'],
            ['first_name' => 'Carlo',   'last_name' => 'Mendoza',    'email' => 'evaluator.mendoza@scholarlink.ph',     'password' => 'Carlo@014'],
            ['first_name' => 'Liza',    'last_name' => 'Bautista',   'email' => 'evaluator.bautista@scholarlink.ph',    'password' => 'Liza@015'],
            ['first_name' => 'Ramon',   'last_name' => 'Garcia',     'email' => 'evaluator.garcia@scholarlink.ph',      'password' => 'Ramon@016'],
            ['first_name' => 'Cynthia', 'last_name' => 'Torres',     'email' => 'evaluator.torres@scholarlink.ph',      'password' => 'Cynthia@017'],
            ['first_name' => 'Mark',    'last_name' => 'Villanueva', 'email' => 'evaluator.villanueva@scholarlink.ph',  'password' => 'Mark@018'],
            ['first_name' => 'Patricia','last_name' => 'Flores',     'email' => 'evaluator.flores@scholarlink.ph',      'password' => 'Patricia@019'],
            ['first_name' => 'Dennis',  'last_name' => 'Aguilar',    'email' => 'evaluator.aguilar@scholarlink.ph',     'password' => 'Dennis@020'],
        ];

        foreach ($evaluators as $evaluator) {
            User::create([
                'first_name'        => $evaluator['first_name'],
                'last_name'         => $evaluator['last_name'],
                'email'             => $evaluator['email'],
                'password'          => Hash::make($evaluator['password']),
                'role'              => 'evaluator',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ]);
        }

        // ── SUPERADMIN (user ID 26) ───────────────────────
        User::create([
            'first_name'        => 'Super',
            'last_name'         => 'Admin',
            'email'             => 'superadmin@scholarlink.ph',
            'password'          => Hash::make('Superadmin@000'),
            'role'              => 'superadmin',
            'organization_id'   => null,
            'is_active'         => 1,
            'email_verified_at' => '2026-01-01 00:00:00',
        ]);

        // ── ADMINS (user IDs 27–32) ───────────────────────
        $admins = [
            // ID 27 — manages scholarships 1, 2, 3
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Gabay',
                'email'             => 'admin.gabay@scholarlink.ph',
                'password'          => Hash::make('AdminGabay@027'),
                'role'              => 'admin',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ],
            // ID 28 — manages scholarships 4, 5, 6
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Community',
                'email'             => 'admin.community@scholarlink.ph',
                'password'          => Hash::make('AdminCommunity@028'),
                'role'              => 'admin',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ],
            // ID 29 — manages scholarships 7, 8, 9
            [
                'first_name'        => 'Admin',
                'last_name'         => 'STEM',
                'email'             => 'admin.stem@scholarlink.ph',
                'password'          => Hash::make('AdminSTEM@029'),
                'role'              => 'admin',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ],
            // ID 30 — manages scholarships 10, 11, 12, 13
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Government',
                'email'             => 'admin.government@scholarlink.ph',
                'password'          => Hash::make('AdminGovernment@030'),
                'role'              => 'admin',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ],
            // ID 31 — manages scholarships 14, 15, 16, 17, 18, 19, 20
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Corporate',
                'email'             => 'admin.corporate@scholarlink.ph',
                'password'          => Hash::make('AdminCorporate@031'),
                'role'              => 'admin',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ],
            // ID 32 — manages scholarships 21, 22, 23, 24, 25
            [
                'first_name'        => 'Admin',
                'last_name'         => 'SHS',
                'email'             => 'admin.shs@scholarlink.ph',
                'password'          => Hash::make('AdminSHS@032'),
                'role'              => 'admin',
                'organization_id'   => null,
                'is_active'         => 1,
                'email_verified_at' => '2026-01-01 00:00:00',
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
}

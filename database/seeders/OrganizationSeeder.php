<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use App\Models\Organization;
use App\Models\User;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $scholarships = Scholarship::all()->groupBy('provider_name');

        foreach ($scholarships as $providerName => $orgScholarships) {
            $firstScholarship = $orgScholarships->first();
            
            // Check if organization already exists to avoid duplicates
            $org = Organization::firstOrCreate(
                ['name' => $providerName],
                [
                    'email'     => $firstScholarship->contact_email ?? 'contact@' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $providerName)) . '.org',
                    'website'   => $firstScholarship->website,
                    'address'   => $firstScholarship->address,
                    'is_active' => 1,
                ]
            );

            // Assign the creator(admin) to this organization
            User::where('id', $firstScholarship->created_by)->update(['organization_id' => $org->id]);
        }
    }
}

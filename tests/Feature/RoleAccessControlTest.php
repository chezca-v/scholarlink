<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_protected_routes(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/evaluator/dashboard')->assertRedirect('/login');
        $this->get('/superadmin/dashboard')->assertRedirect('/login');
    }

    public function test_applicant_cannot_access_admin_or_evaluator_areas(): void
    {
        $applicant = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::factory()->create([
            'user_id' => $applicant->id,
            'profile_completed_at' => now(),
        ]);

        // CheckRole redirects a mismatched role to their own dashboard rather than aborting 403.
        $this->actingAs($applicant)->get('/admin/dashboard')->assertRedirect('/dashboard');
        $this->actingAs($applicant)->get('/evaluator/dashboard')->assertRedirect('/dashboard');
        $this->actingAs($applicant)->get('/superadmin/dashboard')->assertRedirect('/dashboard');
    }

    public function test_evaluator_cannot_access_admin_or_superadmin_areas(): void
    {
        $evaluator = User::factory()->create(['role' => 'evaluator']);

        $this->actingAs($evaluator)->get('/admin/dashboard')->assertRedirect('/evaluator/dashboard');
        $this->actingAs($evaluator)->get('/superadmin/dashboard')->assertRedirect('/evaluator/dashboard');
        $this->actingAs($evaluator)->get('/dashboard')->assertRedirect('/evaluator/dashboard');
    }

    public function test_admin_cannot_access_evaluator_or_superadmin_areas(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/evaluator/dashboard')->assertRedirect('/admin/dashboard');
        $this->actingAs($admin)->get('/superadmin/dashboard')->assertRedirect('/admin/dashboard');
        $this->actingAs($admin)->get('/dashboard')->assertRedirect('/admin/dashboard');
    }

    public function test_evaluator_cannot_review_application_for_unassigned_scholarship(): void
    {
        $evaluator = User::factory()->create(['role' => 'evaluator']);
        $admin = User::factory()->create(['role' => 'admin']);
        $applicant = User::factory()->create(['role' => 'applicant']);

        $scholarship = \App\Models\Scholarship::create([
            'name' => 'Unassigned Test Grant',
            'provider_name' => 'Test Org',
            'description' => 'desc',
            'gpa_requirement' => 3.00,
            'slots' => 5,
            'eligibility' => 'n/a',
            'benefits' => 'n/a',
            'requirements' => 'n/a',
            'open_date' => now(),
            'deadline' => now()->addDays(30),
            'status' => 'open',
            'created_by' => $admin->id,
            'posted_at' => now(),
        ]);

        $application = \App\Models\Application::create([
            'reference_code' => \App\Models\Application::generateReferenceCode($scholarship, now()->year),
            'applicant_id' => $applicant->id,
            'scholarship_id' => $scholarship->id,
            'status' => 'under_review',
            'stage' => 'scoring',
            'submitted_at' => now(),
        ]);

        // No EvaluatorAssignment exists linking this evaluator to this scholarship.
        $this->actingAs($evaluator)
            ->get("/evaluator/review/{$application->id}")
            ->assertForbidden();
    }
}

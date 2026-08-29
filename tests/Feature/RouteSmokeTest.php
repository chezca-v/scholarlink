<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function applicantWithCompletedProfile(): User
    {
        $user = User::factory()->create(['role' => 'applicant']);

        ApplicantProfile::factory()->create([
            'user_id' => $user->id,
            'profile_completed_at' => now(),
        ]);

        return $user;
    }

    public function test_public_routes_render_successfully(): void
    {
        $publicRoutes = [
            '/',
            '/scholarships',
            '/about',
            '/organizations',
            '/terms',
            '/privacy',
            '/data-privacy',
            '/login',
            '/register',
            '/forgot-password',
        ];

        foreach ($publicRoutes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200, "Failed asserting that public route {$url} loads.");
        }
    }

    public function test_applicant_routes_render_successfully_with_authenticated_user(): void
    {
        $applicant = $this->applicantWithCompletedProfile();

        $applicantRoutes = [
            '/dashboard',
            '/applicant/dashboard',
            '/profile',
            '/applicant/documents',
            '/applicant/applications',
            '/applicant/saved',
            '/notifications',
        ];

        foreach ($applicantRoutes as $url) {
            $response = $this->actingAs($applicant)->get($url);
            $response->assertStatus(200, "Failed asserting that applicant route {$url} loads.");
        }
    }

    public function test_admin_routes_render_successfully(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $adminRoutes = [
            '/admin/dashboard',
            '/admin/scholarships',
            '/admin/scholarships/create',
            '/admin/users',
            '/admin/analytics',
            '/admin/calendar',
            '/admin/applications',
            '/admin/reviews',
            '/admin/settings',
        ];

        foreach ($adminRoutes as $url) {
            $response = $this->actingAs($admin)->get($url);
            $response->assertStatus(200, "Failed asserting that admin route {$url} loads.");
        }
    }

    public function test_evaluator_routes_render_successfully(): void
    {
        $evaluator = User::factory()->create(['role' => 'evaluator']);

        $evaluatorRoutes = [
            '/evaluator/dashboard',
            '/evaluator/queue',
            '/evaluator/notifications',
            '/evaluator/profile',
            '/evaluator/completed',
        ];

        foreach ($evaluatorRoutes as $url) {
            $response = $this->actingAs($evaluator)->get($url);
            $response->assertStatus(200, "Failed asserting that evaluator route {$url} loads.");
        }
    }

    public function test_superadmin_routes_render_successfully(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $superadminRoutes = [
            '/superadmin/dashboard',
            '/superadmin/organizations',
            '/superadmin/admins',
            '/superadmin/logs',
            '/superadmin/settings',
            '/superadmin/notifications',
        ];

        foreach ($superadminRoutes as $url) {
            $response = $this->actingAs($superadmin)->get($url);
            $response->assertStatus(200, "Failed asserting that superadmin route {$url} loads.");
        }
    }
}

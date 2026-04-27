<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
    public function test_admin_users_are_redirected_to_the_admin_dashboard_after_login(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_evaluator_users_are_redirected_to_the_evaluator_dashboard_after_login(): void
    {
        $evaluator = User::factory()->create([
            'role' => 'evaluator',
        ]);

        $response = $this->post('/login', [
            'email' => $evaluator->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($evaluator);
        $response->assertRedirect(route('evaluator.dashboard', absolute: false));
    }

    public function test_superadmin_users_are_redirected_to_the_superadmin_dashboard_after_login(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $response = $this->post('/login', [
            'email' => $superadmin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($superadmin);
        $response->assertRedirect(route('superadmin.dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}

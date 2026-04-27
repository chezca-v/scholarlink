<?php

namespace Tests\Feature\Auth;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_applicant_users_can_register(): void    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Applicant',
            'email' => 'applicant@example.com',
            'password' => 'Password123!',
            'role' => 'applicant',
            'terms' => 'on',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    public function test_new_applicant_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'first_name' => 'Test',
            'last_name' => 'Applicant',
            'email' => 'applicant@example.com',
            'password' => 'Password123!',
            'role' => 'applicant',
            'terms' => 'on',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'applicant@example.com',
            'role' => 'applicant',
        ]);
    }

    public function test_new_admin_users_are_redirected_to_admin_dashboard_after_registering(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'Password123!',
            'role' => 'admin',
            'terms' => 'on',
        ]);

        $response->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertInstanceOf(User::class, auth()->user());
    }
}

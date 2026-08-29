<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
    }

    public function test_new_applicant_users_can_register(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Applicant',
            'email' => 'applicant@example.com',
            'password' => 'Password123!',
            'role' => 'applicant',
            'terms' => 'on',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('profile.setup', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'applicant@example.com',
            'role' => 'applicant',
        ]);
    }
}

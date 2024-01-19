<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_hit_register_page(): void
    {
        $response = $this->post('/api/register', []);

        // a 302 will be returned because we're just hitting the route without passing any data
        $response->assertStatus(302);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertCreated();
    }

    public function test_password_field_must_match(): void
    {
        $response = $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_name_and_email_fields_are_required(): void
    {
        $response = $this->post('/api/register', [
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }
}

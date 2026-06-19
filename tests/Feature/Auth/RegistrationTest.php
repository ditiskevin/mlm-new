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

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'parent_type' => 'ouder',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_users_can_register_with_gender_and_parenting_role(): void
    {
        $this->post('/register', [
            'name' => 'Papa Piet',
            'email' => 'piet@example.com',
            'parent_type' => 'ouder',
            'gender' => 'man',
            'parenting_role' => 'vader',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'piet@example.com',
            'gender' => 'man',
            'parenting_role' => 'vader',
        ]);
    }

    public function test_registration_rejects_invalid_gender(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'parent_type' => 'ouder',
            'gender' => 'onzin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('gender');
    }
}

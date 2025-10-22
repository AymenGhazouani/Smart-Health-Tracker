<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        
        // First user becomes admin, so check for admin dashboard redirect
        $user = auth()->user();
        if ($user->isAdmin()) {
            $response->assertRedirect(route('admin.dashboard'));
        } else {
            $response->assertRedirect(route('dashboard'));
        }
    }
}

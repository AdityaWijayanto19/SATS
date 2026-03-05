<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_a_new_user(): void
    {
        Event::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Aditya',
            'email' => 'aditya@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'aditya@test.com'
        ]);

        Event::assertDispatched(Registered::class);
    }

    public function test_registration_existing_credentials(){
        $user = User::factory()->create([
            'email' => 'existing@test.com'
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Aditya',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_with_empty_fields(){
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
    }

    public function test_registration_with_password_confirmation_mismatch(){
        $response = $this->postJson('/api/register', [
            'name' => 'Aditya',
            'email' => 'aditya@test.com',
            'password' => 'password123',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertStatus(422);
    }
}

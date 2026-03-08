<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    public function test_correct_with_data()
    {
        $user = User::factory()->create([
            'email' => 'aditya@example.com',
            'name' => 'Aditya',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        /**
         * @var \App\Models\User $user
         */
        $response = $this->actingAs($user, 'sanctum')->patchJson('/api/updateProfile', [
            'name' => 'Aditya Updated',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'aditya@example.com',
            'name'  => 'Aditya Updated',
        ]);
    }
}

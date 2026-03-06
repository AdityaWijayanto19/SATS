<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_update_can_update_data()
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
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/updateProfile', [
            'name' => 'Aditya Updated',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message', 'user']);
    }

    public function test_user_update_with_empty_fields()
    {
        $user = User::factory()->create([
            'email' => 'aditya@examples.com',
            'name' => 'Aditya',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/updateProfile', []);

        $response->assertStatus(401)->assertJsonStructure(['message', 'user']);
    }

    public function test_user_with_password_confirm_missmatch()
    {
        $user = User::factory()->create([
            'email' => 'aditya@examples.com',
            'name' => 'Aditya123',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user, 'sanctum')->postJson('/Api/UpdateProfile', [
            'name' => 'Aditya Updated',
            'password' => 'newpassword123',
        ]);

        $response->assertStatus(404)->assertJsonStructure(['message']);
    }
}

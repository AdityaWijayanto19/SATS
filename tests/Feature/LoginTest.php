<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

use function Symfony\Component\Clock\now;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_with_correct_datas()
    {
        $user = User::factory()->create([
            'email' => 'aditya123@test.com',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'aditya123@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message', 'token', 'user']);
    }

    public function test_login_with_not_verified(): void
    {
        $user = User::factory()->create([
            'email' => 'aditya123@test.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'aditya123@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403)->assertJsonStructure([
            'message',
        ]);
    }

    public function test_login_with_empty_fields()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422);
    }

    public function test_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'email' => 'incorrect@test.com',
            'password' => Hash::make('wrongpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'iincorrect@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)->assertJsonStructure([
            'message'
        ]);
    }
}

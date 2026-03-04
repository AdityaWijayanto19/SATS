<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'aditya@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'aditya@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'token',
            'user'
        ]);
    }
}

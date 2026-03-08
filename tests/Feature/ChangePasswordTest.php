<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;
    public function test_correct_with_data()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        /**
         * @var \App\Models\User $user
         */
        $response = $this->actingAs($user, 'sanctum')->patchJson('/api/changePassword', [
            'old_password' => 'password123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->dump();

        $response->assertStatus(200)->assertJsonStructure(['message']);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}

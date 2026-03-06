<?php

namespace App\Services;
use App\Models\User;

class ProfileService
{
        public function updateProfile(User $user, array $data)
        {
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
            ]);

            return $user;
        }
}

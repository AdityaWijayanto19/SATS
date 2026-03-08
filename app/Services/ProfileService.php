<?php

namespace App\Services;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\throwException;

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

        public function changePassword(User $user, array $data){

            if (!Hash::check($data['old_password'], $user->password)) {
                throw new Exception('Old Password uncorrect');
            }

            $user->update([
                'password' => Hash::make($data['new_password'])
            ]);

            return $user;
        }
}

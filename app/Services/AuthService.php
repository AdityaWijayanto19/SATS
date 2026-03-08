<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthService
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        return $user;
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            return 'not_verified';
        }

        /** @var \App\Models\User $user */
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function verify($userId, $hash)
    {
        $user = User::findOrFail($userId);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return false;
        }

        if ($user->hasVerifiedEmail()) {
            return true;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return true;
    }

    public function resendVerification($email)
    {
        $user = User::where('email', $email)->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return 'not_found';
        }

        // Jika email sudah diverifikasi, tidak perlu kirim lagi
        if ($user->hasVerifiedEmail()) {
            return 'already_verified';
        }

        // Kirim notifikasi email verifikasi
        $user->sendEmailVerificationNotification();

        return 'sent';
    }

    public function logout(Request $request) {

        $request->user()->currentAccessToken()->delete();

        return true;
    }
}

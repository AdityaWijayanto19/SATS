<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthService $authService)
    {
        $user = $authService->register($request->validated());

        event(new Registered($user));

        return response()->json([
            'message' => 'User registered successfully'
        ], 201);
    }

    public function login(LoginRequest $request, AuthService $authService)
    {
        $credentials = $request->only('email', 'password');

        $result = $authService->login($credentials);

        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $result[1],
            'user' => $result[0]
        ], 200);
    }
}

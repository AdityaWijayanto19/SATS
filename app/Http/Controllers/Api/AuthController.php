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
        $data = $authService->login($request->validated());

        return response()->json([
            'message' => 'Login Sukses',
            'token' => $data[1],
            'user' => $data[0],
        ], 200);
    }
}

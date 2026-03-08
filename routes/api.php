<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
    
Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['throttle:3,1']) // Maksimal 3 kali resend per menit
    ->name('verification.resend');

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/updateProfile', [ProfileController::class, 'updateProfile']);
    Route::patch('/changePassword', [ProfileController::class, 'changePassword']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

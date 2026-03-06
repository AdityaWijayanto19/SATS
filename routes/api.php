<?php

use App\Http\Controllers\Api\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['throttle:3,1']) // Maksimal 3 kali resend per menit
    ->name('verification.resend');

Route::patch('/updateProfile', [App\Http\Controllers\Api\ProfileController::class, 'updateProfile']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

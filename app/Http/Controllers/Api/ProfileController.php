<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Exception;

class ProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request, ProfileService $profileService)
    {
        $data = $profileService->updateProfile($request->user(), $request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $data
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request, ProfileService $profileService)
    {
        try {
            $profileService->changePassword($request->user(), $request->validated());
            return response()->json([
                'message' => 'Password successfully changed',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

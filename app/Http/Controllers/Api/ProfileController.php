<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;

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
}

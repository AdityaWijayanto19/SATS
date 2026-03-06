<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResendVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        $result = $this->authService->verify(
            $request->route('id'),
            $request->route('hash')
        );

        if (!$result) {
            return response()->json([
                'message' => 'Link Verifikasi sudah ekspired',
            ], 403);
        }

        return response()->json([
            'message' => 'Email berhasil terverifikasi'
        ], 200);
    }

    public function resend(ResendVerificationRequest $request): JsonResponse
    {
        $status = $this->authService->resendVerification($request->email);

        if ($status === 'already_verified') {
            return response()->json(['message' => 'Email sudah terverifikasi.'], 400);
        }

        return response()->json(['message' => 'Link verifikasi baru telah dikirim ke email kamu.'], 200);
    }
}

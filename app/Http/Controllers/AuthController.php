<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\VolunteerProfile;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json([
                'message' => 'The email or password is incorrect.'
            ], 401);
        }

        $user = $result['user'];

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'must_change_password' => $user->must_change_password,
            ]
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $success = $this->authService->changePassword($user, $request->validated());

        if (!$success) {
            return response()->json([
                'message' => 'The current password is incorrect.'
            ], 422);
        }

        return response()->json([
            'message' => 'Password changed successfully, and the account is now ready.',
        ]);
    }

    public function userProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $userInfoData = $this->authService->getUserProfile($user);

        return response()->json([
            'user' => $userInfoData
        ], 200);
    }
}

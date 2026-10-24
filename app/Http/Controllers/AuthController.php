<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'The email or password is incorrect.'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'access_token' => $token,
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();


        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully, and the account is now ready.',
        ]);
    }

    public function userProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->load('volunteerProfile');
        
        $userInfoData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'preferred_help' => $user->preferred_help ?? null,
            'role' => $user->role,
            'must_change_password' => $user->must_change_password ?? false,
        ];

        if ($user->role === 'volunteer') {
            $profile = $user->volunteerProfile;
            $totalHours = $profile ? (float) $profile->total_hours : 0.00;
            $percentage = $profile ? $profile->completion_percentage : 0.00;

            $userInfoData['volunteer_stats'] = [
                'total_hours' => $totalHours,
                'target_hours' => VolunteerProfile::TARGET_HOURS, // 35.00
                'completion_percentage' => $percentage,
                'remaining_hours' => max(0, VolunteerProfile::TARGET_HOURS - $totalHours),
            ];
        }

        return response()->json([
            'user' => $userInfoData
        ], 200);
    }
}

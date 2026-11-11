<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\VolunteerProfile;

class AuthService
{
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function changePassword(User $user, array $data): bool
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return false;
        }

        $user->password = Hash::make($data['new_password']);
        $user->must_change_password = false;
        $user->save();

        return true;
    }

    public function getUserProfile(User $user): array
{
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

    return $userInfoData;
}

}
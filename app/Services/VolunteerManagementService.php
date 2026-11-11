<?php

namespace App\Services;

use App\Models\User;
use App\Models\VolunteerProfile;

class VolunteerManagementService
{
    public function updateHours(int $volunteerId, array $data): User
    {
        $volunteer = User::with('volunteerProfile')->findOrFail($volunteerId);

        $profile = $volunteer->volunteerProfile()->firstOrCreate(['user_id' => $volunteer->id]);

        if ($data['action'] === 'add') {
            $profile->total_hours += $data['hours'];
        } else {
            $profile->total_hours = $data['hours'];
        }

        $profile->save();

        return $volunteer->load('volunteerProfile');
    }

    public function getAllVolunteers()
    {
        $volunteers = User::where('role', 'volunteer')
            ->with('volunteerProfile')
            ->get();

        return $volunteers->map(function ($volunteer) {
            $profile = $volunteer->volunteerProfile;
            $totalHours = $profile ? (float) $profile->total_hours : 0.00;
            $percentage = $profile ? $profile->completion_percentage : 0.00;

            return [
                'id' => $volunteer->id,
                'name' => $volunteer->name,
                'email' => $volunteer->email,
                'phone' => $volunteer->phone ?? null,
                'preferred_help' => $volunteer->preferred_help ?? null,
                'total_hours' => $totalHours,
                'target_hours' => VolunteerProfile::TARGET_HOURS,
                'completion_percentage' => $percentage,
                'is_target_achieved' => $totalHours >= VolunteerProfile::TARGET_HOURS,
            ];
        });
    }
}

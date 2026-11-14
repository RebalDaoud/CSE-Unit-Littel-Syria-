<?php

namespace App\Services;

use App\Models\User;
use App\Models\VolunteerProfile;
use App\Actions\AddHoursAction;
use App\Actions\SetHoursAction;
use Illuminate\Validation\ValidationException;

class VolunteerManagementService
{
    protected array $actions = [
        'add' => AddHoursAction::class,
        'set' => SetHoursAction::class,
    ];

    public function updateHours(int $volunteerId, array $data): User
    {
        $volunteer = User::with('volunteerProfile')->findOrFail($volunteerId);

        $profile = $volunteer->volunteerProfile()->firstOrCreate(['user_id' => $volunteer->id]);

        $actionType = $data['action'];

        if (!isset($this->actions[$actionType])) {
            throw ValidationException::withMessages(['action' => 'Invalid action type.']);
        }

        $action = app($this->actions[$actionType]);
        $action->execute($profile, (float) $data['hours']);

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

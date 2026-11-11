<?php

namespace App\Services;

use App\Models\ActivitySession;

class ActivityAssignmentService
{
    public function getSessionVolunteersForDropdown(int $sessionId)
    {
        $session = ActivitySession::with([
            'volunteers.volunteerProfile'
        ])->findOrFail($sessionId);

        $dropdownList = $session->volunteers->map(function ($volunteer) {
            return [
                'id' => $volunteer->id,
                'name' => $volunteer->name,
                'total_hours' => $volunteer->volunteerProfile ? (float) $volunteer->volunteerProfile->total_hours : 0.00,
                'completion_percentage' => $volunteer->volunteerProfile ? $volunteer->volunteerProfile->completion_percentage : 0.00,
            ];
        });

        return [
            'session' => $session,
            'dropdownList' => $dropdownList
        ];
    }

    public function assignVolunteersToSession(int $sessionId, array $volunteerIds)
    {
        $session = ActivitySession::with('supervisor', 'activity')->findOrFail($sessionId);

        $session->volunteers()->sync($volunteerIds);

        return $session->load('volunteers', 'supervisor');
    }

    public function updateSessionSupervisor(int $sessionId, int $supervisorId)
    {
        $session = ActivitySession::findOrFail($sessionId);
        $session->supervisor_id = $supervisorId;
        $session->save();

        return $session->load('supervisor', 'activity');
    }

    public function getUserSchedule($user)
    {
        return $user->assignedActivities()->get();
    }

    public function applyUserForSession($user, int $sessionId)
    {
        $session = ActivitySession::findOrFail($sessionId);

        $session->volunteers()->syncWithoutDetaching([
            $user->id => ['status' => 'pending']
        ]);

        return $session;
    }
}
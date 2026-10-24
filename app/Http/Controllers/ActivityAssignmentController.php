<?php

namespace App\Http\Controllers;

use App\Models\ActivitySession;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityAssignmentController extends Controller
{
    public function getVolunteersForSelection(Request $request, $sessionId)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $session = ActivitySession::with([
            'volunteers.volunteerProfile'
        ])->findOrFail($sessionId);

        if ($user->role !== 'admin' && $user->id != $session->supervisor_id) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to view the volunteers for this session.'
            ], 403);
        }
        
        $dropdownList = $session->volunteers->map(function ($volunteer) {
            return [
                'id' => $volunteer->id,
                'name' => $volunteer->name,
                'total_hours' => $volunteer->volunteerProfile ? (float) $volunteer->volunteerProfile->total_hours : 0.00,
                'completion_percentage' => $volunteer->volunteerProfile ? $volunteer->volunteerProfile->completion_percentage : 0.00,
            ];
        });

        return response()->json([
            'session_id' => $session->id,
            'applicants_count' => $dropdownList->count(),
            'volunteers' => $dropdownList
        ], 200);
    }

    public function assignVolunteers(Request $request, $sessionId)
    {
        $request->validate([
            'volunteer_ids' => 'required|array',
            'volunteer_ids.*' => 'exists:users,id',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $session = ActivitySession::with('supervisor', 'activity')->findOrFail($sessionId);

        if ($user->role !== 'admin' && $user->id != $session->supervisor_id) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to assign volunteers to this session.'
            ], 403);
        }

        $session->volunteers()->sync($request->volunteer_ids);

        return response()->json([
            'message' => 'Volunteers assigned to session successfully.',
            'session' => $session->load('volunteers', 'supervisor')
        ], 200);
    }


    public function updateSupervisor(Request $request, $sessionId)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();


        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Sorry, you are not authorized to update the supervisor for this session.'
            ], 403);
        }

        $session = ActivitySession::findOrFail($sessionId);
        $session->supervisor_id = $request->supervisor_id;
        $session->save();

        return response()->json([
            'message' => 'Supervisor updated for session successfully.',
            'session' => $session->load('supervisor', 'activity')
        ]);
    }

    public function mySchedule(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $assignedActivities = $user->assignedActivities()->get();

        return response()->json([
            'message' => 'Your schedule',
            'schedule' => $assignedActivities
        ], 200);
    }

    public function getAvailableSessions(Request $request)
    {
        $sessions = ActivitySession::with(['activity', 'supervisor'])
            ->get();

        return response()->json([
            'sessions' => $sessions
        ], 200);
    }

    public function applyForSession(Request $request, $sessionId)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->role !== 'volunteer') {
            return response()->json(
                ['message' => 'Sorry, you are not authorized to view this.'],
                403
            );
        }

        $session = ActivitySession::findOrFail($sessionId);

        $session->volunteers()->syncWithoutDetaching([
            $user->id => ['status' => 'pending']
        ]);

        return response()->json([
            'message' => 'Your request to join this session has been successfully registered and is awaiting supervisor approval.'
        ], 200);
    }
}

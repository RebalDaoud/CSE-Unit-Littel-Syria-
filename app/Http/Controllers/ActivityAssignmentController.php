<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AssignVolunteersRequest;
use App\Http\Requests\Auth\UpdateSupervisorRequest;
use App\Models\ActivitySession;
use App\Services\ActivityAssignmentService;
use Illuminate\Http\Request;

class ActivityAssignmentController extends Controller
{
    //Newly added constructor to inject the ActivityAssignmentService
    public function __construct(protected ActivityAssignmentService $assignmentService) {}

    public function getVolunteersForSelection(Request $request, int $sessionId)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $this->assignmentService->getSessionVolunteersForDropdown($sessionId);
        $session = $data['session'];
        $dropdownList = $data['dropdownList'];

        if ($user->cannot('manageVolunteers', $session)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to view the volunteers for this session.'
            ], 403);
        }

        return response()->json([
            'session_id' => $session->id,
            'applicants_count' => $dropdownList->count(),
            'volunteers' => $dropdownList
        ], 200);
    }

    public function assignVolunteers(AssignVolunteersRequest $request, int $sessionId)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $session = ActivitySession::findOrFail($sessionId);

        if ($user->cannot('manageVolunteers', $session)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to assign volunteers to this session.'
            ], 403);
        }

        $updatedSession = $this->assignmentService->assignVolunteersToSession(
            $sessionId,
            $request->validated('volunteer_ids')
        );

        return response()->json([
            'message' => 'Volunteers assigned to session successfully.',
            'session' => $updatedSession
        ], 200);
    }


    public function updateSupervisor(UpdateSupervisorRequest $request, int $sessionId)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $session = ActivitySession::findOrFail($sessionId);

        //We can write a policy check with out put a class "ActivitySessionPolicy" because of lavral auto discovery of policy classes based on model names. 
        if ($user->cannot('updateSupervisor', $session)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to update the supervisor for this session.'
            ], 403);
        }

        $updatedSession = $this->assignmentService->updateSessionSupervisor(
            $sessionId,
            $request->validated('supervisor_id')
        );

        return response()->json([
            'message' => 'Supervisor updated for session successfully.',
            'session' => $updatedSession
        ], 200);
    }

    public function mySchedule(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $assignedActivities = $this->assignmentService->getUserSchedule($user);

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

    public function applyForSession(Request $request, int $sessionId)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $session = ActivitySession::findOrFail($sessionId);

        if ($user->cannot('apply', $session)) {
            return response()->json(
                ['message' => 'Sorry, you are not authorized to view this.'],
                403
            );
        }

        $this->assignmentService->applyUserForSession($user, $sessionId);

        return response()->json([
            'message' => 'Your request to join this session has been successfully registered and is awaiting supervisor approval.'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateVolunteerHoursRequest;
use App\Policies\VolunteerPolicy;
use App\Services\VolunteerManagementService;
use Illuminate\Http\Request;

class VolunteerManagementController extends Controller
{
    public function __construct(protected VolunteerManagementService $volunteerService) {}

    public function updateHours(UpdateVolunteerHoursRequest $request, int $volunteerId)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        $policy = new VolunteerPolicy();
        if (!$policy->updateHours($currentUser)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to update volunteer hours.'
            ], 403);
        }

        $volunteer = $this->volunteerService->updateHours($volunteerId, $request->validated());

        return response()->json([
            'message' => 'Hours updated successfully.',
            'volunteer' => $volunteer
        ], 200);
    }

    public function getAll(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        $policy = new VolunteerPolicy();
        if (!$policy->viewAny($currentUser)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to view this information.'
            ], 403);
        }

        $analyticsReport = $this->volunteerService->getAllVolunteers();

        return response()->json([
            'total_volunteers_count' => $analyticsReport->count(),
            'volunteers' => $analyticsReport
        ], 200);
    }
}

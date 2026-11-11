<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreActivityRequest;
use App\Http\Requests\Auth\UpdateActivityRequest;
use App\Models\Activity;
use App\Services\ActivityService;

class ActivityController extends Controller
{
    public function __construct(protected ActivityService $activityService) {}

    public function store(StoreActivityRequest $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        if ($currentUser->cannot('create', Activity::class)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to create activities.'
            ], 403);
        }

        $activity = $this->activityService->createActivity($request->validated());

        return response()->json([
            'message' => 'Activity created successfully.',
            'activity' => $activity
        ], 201);
    }

    public function update(UpdateActivityRequest $request, int $id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        $activity = Activity::findOrFail($id);

        if ($currentUser->cannot('update', $activity)) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to update activities.'
            ], 403);
        }

        $updatedActivity = $this->activityService->updateActivity($activity, $request->validated());

        return response()->json([
            'message' => 'Activity updated successfully.',
            'activity' => $updatedActivity
        ], 200);
    }
}

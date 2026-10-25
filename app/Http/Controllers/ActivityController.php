<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sessions' => 'required|array|min:1',
            'sessions.*.day_of_week' => 'required|string',
            'sessions.*.time_slot' => 'required|string',
            'sessions.*.supervisor_id' => 'nullable|exists:users,id',
        ]);

        /** @var \App\Models\User $user */
        $currentUser = $request->user();

        if ($currentUser->role !== 'admin') {
            return response()->json([
                'message' => 'Sorry, you are not authorized to create activities.'
            ], 403);
        }

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->sessions as $sessionData) {
            $activity->sessions()->create($sessionData);
        }

        return response()->json([
            'message' => 'Activity created successfully.',
            'activity' => $activity->load('sessions.supervisor')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'sessions' => 'sometimes|array|min:1',
            'sessions.*.day_of_week' => 'required_with:sessions|string',
            'sessions.*.time_slot' => 'required_with:sessions|string',
            'sessions.*.supervisor_id' => 'nullable|exists:users,id',
        ]);

        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        if ($currentUser->role !== 'admin') {
            return response()->json([
                'message' => 'Sorry, you are not authorized to update activities.'
            ], 403);
        }

        $activity = Activity::findOrFail($id);

        $activity->update([
            'title' => $request->input('title', $activity->title),
            'description' => $request->input('description', $activity->description),
        ]);

        if ($request->has('sessions')) {
            $activity->sessions()->delete();

            foreach ($request->sessions as $sessionData) {
                $activity->sessions()->create($sessionData);
            }
        }

        return response()->json([
            'message' => 'Activity updated successfully.',
            'activity' => $activity->load('sessions.supervisor')
        ], 200);
    }
}

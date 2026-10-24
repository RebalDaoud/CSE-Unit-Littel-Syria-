<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Http\Request;

class VolunteerManagementController extends Controller
{
    public function updateHours(Request $request, $volunteerId)
    {
        $request->validate([
            'hours' => 'required|numeric|min:0|max:6',
            'action' => 'required|in:add,set',
        ]);

        /** @var \App\Models\User $user */
        $currentUser = $request->user();

        if ($currentUser->role !== 'admin') {
            return response()->json([
                'message' => 'Sorry, you are not authorized to update volunteer hours.'
            ], 403);
        }

        $volunteer = User::with('volunteerProfile')->findOrFail($volunteerId);

        $profile = $volunteer->volunteerProfile()->firstOrCreate(['user_id' => $volunteer->id]);

        if ($request->action === 'add') {
            $profile->total_hours += $request->hours;
        } else {
            $profile->total_hours = $request->hours;
        }

        $profile->save();

        return response()->json([
            'message' => 'Hours updated successfully.',
            'volunteer' => $volunteer->load('volunteerProfile')
        ], 200);
    }

    public function getAll(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        if ($currentUser->role !== 'admin') {
            return response()->json([
                'message' => 'Sorry, you are not authorized to view this information.'
            ], 403);
        }

        $volunteers = User::where('role', 'volunteer')
            ->with('volunteerProfile')
            ->get();

        $analyticsReport = $volunteers->map(function ($volunteer) {
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

        return response()->json([
            'total_volunteers_count' => $analyticsReport->count(),
            'volunteers' => $analyticsReport
        ], 200);
    }
}

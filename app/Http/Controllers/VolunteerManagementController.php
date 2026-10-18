<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}

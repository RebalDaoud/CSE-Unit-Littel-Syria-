<?php

namespace App\Http\Controllers;

use App\Models\ActivitySession;
use Illuminate\Http\Request;

class ActivityAssignmentController extends Controller
{
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

        // جلب الجلسات التي تم تعيين هذا المتطوع عليها عبر جدول activity_assignments
        // (ملاحظة: تأكد أن جدول activity_assignments يربط المتطوع بالجلسة أو بالنشاط بحسب هيكلتك)

        $assignedActivities = $user->assignedActivities()->get();

        return response()->json([
            'message' => 'Your schedule',
            'schedule' => $assignedActivities
        ], 200);
    }
}

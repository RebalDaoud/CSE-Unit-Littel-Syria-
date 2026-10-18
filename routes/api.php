<?php

use App\Http\Controllers\ActivityAssignmentController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\VolunteerManagementController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/user', [AuthController::class, 'userProfile']);

    Route::post('/sessions/{sessionId}/assign-volunteers', [ActivityAssignmentController::class, 'assignVolunteers']);
    Route::patch('/sessions/{sessionId}/supervisor', [ActivityAssignmentController::class, 'updateSupervisor']);

    Route::patch('/volunteers/{volunteerId}/hours', [VolunteerManagementController::class, 'updateHours']);

    Route::post('/activities', [ActivityController::class, 'store']);
    Route::get('/volunteer/schedule', [ActivityAssignmentController::class, 'mySchedule']);
});

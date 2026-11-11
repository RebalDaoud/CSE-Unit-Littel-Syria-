<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivitySession;

class ActivitySessionPolicy
{
    public function manageVolunteers(User $user, ActivitySession $session): bool
    {
        return $user->role === 'admin' || $user->id == $session->supervisor_id;
    }

    public function updateSupervisor(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function apply(User $user): bool
    {
        return $user->role === 'volunteer';
    }
}
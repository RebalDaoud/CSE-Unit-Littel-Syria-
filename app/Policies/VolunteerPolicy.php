<?php

namespace App\Policies;

use App\Models\User;

class VolunteerPolicy
{
    public function updateHours(User $user): bool
    {
        return $user->role === 'admin';
    }
    
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }
}
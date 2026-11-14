<?php

namespace App\Actions;

use App\Models\VolunteerProfile;

class AddHoursAction implements HourActionInterface
{
    public function execute(VolunteerProfile $profile, float $hours): void
    {
        $profile->total_hours += $hours;
    }
}
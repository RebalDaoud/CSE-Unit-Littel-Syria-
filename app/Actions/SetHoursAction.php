<?php

namespace App\Actions;

use App\Models\VolunteerProfile;

class SetHoursAction implements HourActionInterface
{
    public function execute(VolunteerProfile $profile, float $hours): void
    {
        $profile->total_hours = $hours;
    }
}
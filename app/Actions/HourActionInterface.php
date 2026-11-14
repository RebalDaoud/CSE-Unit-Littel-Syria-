<?php

namespace App\Actions;

use App\Models\VolunteerProfile;

interface HourActionInterface
{
    public function execute(VolunteerProfile $profile, float $hours): void;
}
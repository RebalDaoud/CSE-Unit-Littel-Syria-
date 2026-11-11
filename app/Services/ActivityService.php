<?php

namespace App\Services;

use App\Models\Activity;

class ActivityService
{
    public function createActivity(array $data)
    {
        $activity = Activity::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        foreach ($data['sessions'] as $sessionData) {
            $activity->sessions()->create($sessionData);
        }

        return $activity->load('sessions.supervisor');
    }

    public function updateActivity(Activity $activity, array $data)
    {
        $activity->update([
            'title' => $data['title'] ?? $activity->title,
            'description' => array_key_exists('description', $data) ? $data['description'] : $activity->description,
        ]);

        if (isset($data['sessions'])) {
            $activity->sessions()->delete();

            foreach ($data['sessions'] as $sessionData) {
                $activity->sessions()->create($sessionData);
            }
        }

        return $activity->load('sessions.supervisor');
    }
}
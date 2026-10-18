<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ActivitySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'day_of_week',
        'time_slot',
        'supervisor_id',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_assignments', 'activity_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_hours',
        'cv_path',
    ];

    public const TARGET_HOURS = 35.00;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCompletionPercentageAttribute(): float
    {
        if (self::TARGET_HOURS <= 0) {
            return 0.00;
        }

        $percentage = ($this->total_hours / self::TARGET_HOURS) * 100;

        return round($percentage, 2);
    }
}

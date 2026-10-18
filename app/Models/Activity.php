<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'vendor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ActivitySession::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ActivityAssignment::class);
    }

    public function volunteers()
    {
        return $this->belongsToMany(
            User::class,
            'activity_assignments',
            'activity_id',
            'user_id'
        )
            ->withPivot('status')
            ->withTimestamps();
    }
}

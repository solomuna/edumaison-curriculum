<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseAttempt extends Model
{
    protected $fillable = [
        'child_id', 'exercise_id', 'school_year_id',
        'score', 'max_score', 'duration_seconds',
        'status', 'verification_status', 'answers', 'evidence', 'attempted_at',
    ];

    protected $casts = [
        'answers'      => 'array',
        'evidence'     => 'array',
        'attempted_at' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}

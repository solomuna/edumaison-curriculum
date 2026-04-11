<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lesson_id', 'home_skill_id', 'title', 'instructions',
        'category', 'difficulty', 'estimated_minutes', 'content', 'is_active',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function homeSkill(): BelongsTo
    {
        return $this->belongsTo(HomeSkill::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExerciseAttempt::class);
    }

    public function pronunciationAttempts(): HasMany
    {
        return $this->hasMany(PronunciationAttempt::class);
    }

    public function schoolCompetencies(): BelongsToMany
    {
        return $this->belongsToMany(SchoolCompetency::class, 'exercise_school_competency');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'unit_id', 'name', 'slug', 'description', 'content',
        'order', 'estimated_minutes', 'type', 'is_active',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    public function audioModels(): HasMany
    {
        return $this->hasMany(AudioModel::class);
    }

    public function homeSkills(): BelongsToMany
    {
        return $this->belongsToMany(HomeSkill::class, 'lesson_home_skill');
    }
}

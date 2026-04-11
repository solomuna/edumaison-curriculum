<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'level_id', 'name', 'slug', 'color', 'icon', 'order', 'is_active',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function integratedThemes(): HasMany
    {
        return $this->hasMany(IntegratedTheme::class);
    }

    public function schoolCompetencies(): HasMany
    {
        return $this->hasMany(SchoolCompetency::class);
    }

    public function homeSkills(): HasMany
    {
        return $this->hasMany(HomeSkill::class);
    }

    public function schoolResults(): HasMany
    {
        return $this->hasMany(SchoolResult::class);
    }
}

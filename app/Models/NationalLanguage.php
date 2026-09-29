<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NationalLanguage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'autonym', 'aliases', 'source_notes', 'is_selectable', 'is_active',
    ];

    protected $casts = [
        'aliases' => 'array',
        'is_selectable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function households(): HasMany
    {
        return $this->hasMany(Household::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function learningPacks(): HasMany
    {
        return $this->hasMany(LearningPack::class);
    }
}

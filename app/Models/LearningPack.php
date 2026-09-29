<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningPack extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'type', 'visibility',
        'target_level_id', 'target_subject_id', 'metadata',
        'national_language_id', 'content_version', 'linguistic_review_status',
        'linguistic_reviewed_at', 'linguistic_review_notes',
        'is_active', 'is_published', 'published_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'linguistic_reviewed_at' => 'datetime',
    ];

    public function targetLevel(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'target_level_id');
    }

    public function targetSubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'target_subject_id');
    }

    public function nationalLanguage(): BelongsTo
    {
        return $this->belongsTo(NationalLanguage::class);
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'learning_pack_exercise')
            ->withPivot(['position', 'is_required'])
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function householdActivations(): HasMany
    {
        return $this->hasMany(HouseholdLearningPack::class);
    }

    public function childAssignments(): HasMany
    {
        return $this->hasMany(ChildLearningPack::class);
    }
}

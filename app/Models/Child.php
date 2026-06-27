<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'household_id', 'level_id', 'first_name', 'last_name',
        'birth_date', 'avatar', 'pin', 'is_active',
        // Lien EduMaison-Campus (cf. align_children_with_campus_contract).
        // campus_school_id = schools.id cote Campus
        // campus_student_id = matricule ou id eleve cote Campus
        'campus_school_id', 'campus_student_id', 'campus_last_sync_at',
    ];

    protected $hidden = ['pin'];

    protected $casts = [
        'birth_date'          => 'date',
        'is_active'           => 'boolean',
        'campus_school_id'    => 'integer',
        'campus_last_sync_at' => 'datetime',
    ];

    /** Cet enfant est-il relie a un eleve EduMaison-Campus ? */
    public function isLinkedToCampus(): bool
    {
        return $this->campus_school_id !== null && $this->campus_student_id !== null;
    }

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function exerciseAttempts(): HasMany
    {
        return $this->hasMany(ExerciseAttempt::class);
    }

    public function remediationPlans(): HasMany
    {
        return $this->hasMany(RemediationPlan::class);
    }

    public function schoolResults(): HasMany
    {
        return $this->hasMany(SchoolResult::class);
    }

    public function pronunciationAttempts(): HasMany
    {
        return $this->hasMany(PronunciationAttempt::class);
    }
}

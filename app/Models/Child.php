<?php

namespace App\Models;

use App\Jobs\SyncChildNameVoice;
use App\Services\ChildNameVoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'household_id', 'level_id', 'first_name', 'last_name',
        'national_language_id', 'national_language_other_name',
        'birth_date', 'avatar', 'pin', 'pin_hash', 'is_active',
        // Lien EduMaison-Campus (cf. align_children_with_campus_contract).
        // campus_school_id = schools.id cote Campus
        // campus_student_id = matricule ou id eleve cote Campus
        'campus_school_id', 'campus_student_id', 'campus_last_sync_at',
    ];

    protected $hidden = ['pin', 'pin_hash'];

    protected $casts = [
        'birth_date'          => 'date',
        'is_active'           => 'boolean',
        'campus_school_id'    => 'integer',
        'campus_last_sync_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Voix au prénom : générée à la création, regénérée si le prénom change
        // (le job ne fait rien sans l'accord du foyer), effacée avec l'enfant.
        static::created(function (Child $child) {
            if ($child->household?->child_name_voice_consent_at) SyncChildNameVoice::dispatch($child->id);
        });
        static::updated(function (Child $child) {
            if ($child->wasChanged('first_name') && $child->household?->child_name_voice_consent_at) SyncChildNameVoice::dispatch($child->id);
        });
        static::deleting(fn (Child $child) => app(ChildNameVoice::class)->purge($child));
    }

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

    public function nationalLanguage(): BelongsTo
    {
        return $this->belongsTo(NationalLanguage::class);
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

    public function learningPackAssignments(): HasMany
    {
        return $this->hasMany(ChildLearningPack::class);
    }
}

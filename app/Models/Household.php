<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Household extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'city', 'school', 'national_language_id', 'national_language_other_name',
        'locale', 'timezone', 'speaking_audio_consent_at',
        'speaking_audio_retention_days', 'access_pin_hash', 'is_active', 'child_name_voice_consent_at',
    ];

    protected $casts = [
        'speaking_audio_consent_at' => 'datetime',
        'child_name_voice_consent_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['access_pin_hash'];

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'is_active'])
            ->withTimestamps();
    }

    public function nationalLanguage(): BelongsTo
    {
        return $this->belongsTo(NationalLanguage::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function voiceClips(): HasMany
    {
        return $this->hasMany(FamilyVoiceClip::class);
    }

    public function learningPackActivations(): HasMany
    {
        return $this->hasMany(HouseholdLearningPack::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseholdLearningPack extends Model
{
    protected $table = 'household_learning_pack';

    protected $fillable = [
        'household_id', 'learning_pack_id', 'activated_by_user_id', 'is_active', 'settings',
    ];

    protected $casts = ['is_active' => 'boolean', 'settings' => 'array'];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function learningPack(): BelongsTo
    {
        return $this->belongsTo(LearningPack::class);
    }
}

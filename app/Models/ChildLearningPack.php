<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildLearningPack extends Model
{
    protected $table = 'child_learning_pack';

    protected $fillable = [
        'household_id', 'child_id', 'learning_pack_id', 'assigned_by_user_id',
        'status', 'source', 'settings', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function learningPack(): BelongsTo
    {
        return $this->belongsTo(LearningPack::class);
    }
}

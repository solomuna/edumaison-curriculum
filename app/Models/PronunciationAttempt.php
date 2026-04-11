<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PronunciationAttempt extends Model
{
    protected $fillable = [
        'child_id', 'exercise_id', 'target_text',
        'recorded_audio_path', 'overall_score', 'fluency_score',
        'prosody_score', 'rhythm_score', 'pronunciation_score',
        'feedback_json', 'attempted_at',
    ];

    protected $casts = [
        'feedback_json' => 'array',
        'attempted_at'  => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildVoiceClip extends Model
{
    protected $fillable = ['child_id', 'language', 'event', 'variant', 'file_path', 'source_hash'];

    protected $casts = ['variant' => 'integer'];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}

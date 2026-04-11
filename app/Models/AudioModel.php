<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lesson_id', 'title', 'file_path',
        'transcript', 'lang', 'duration_ms', 'is_active',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExerciseMediaAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'exercise_id', 'kind', 'file_path', 'alt_text', 'transcript',
        'language', 'metadata', 'sort_order', 'is_active',
    ];

    protected $casts = ['metadata' => 'array', 'is_active' => 'boolean'];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}

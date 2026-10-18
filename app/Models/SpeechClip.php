<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeechClip extends Model
{
    protected $fillable = ['hash', 'language', 'text', 'characters', 'model', 'file_path'];

    protected $casts = ['characters' => 'integer'];
}

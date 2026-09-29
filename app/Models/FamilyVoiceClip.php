<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyVoiceClip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'household_id', 'title', 'purpose', 'language', 'file_path',
        'transcript', 'consent_at', 'is_active',
    ];

    protected $casts = ['consent_at' => 'datetime', 'is_active' => 'boolean'];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }
}

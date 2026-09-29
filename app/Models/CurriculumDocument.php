<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'authority', 'education_subsystem', 'cycle', 'levels', 'language',
        'version_label', 'publication_date', 'source_url', 'storage_path', 'sha256',
        'verification_status', 'verified_at',
    ];

    protected $casts = [
        'levels' => 'array',
        'publication_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function competencies()
    {
        return $this->hasMany(SchoolCompetency::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolResult extends Model
{
    protected $fillable = [
        'child_id', 'school_year_id', 'subject_id',
        'school_competency_id', 'total_score', 'max_score',
        'average_score', 'appreciation', 'teacher_comment', 'source_type',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolCompetency(): BelongsTo
    {
        return $this->belongsTo(SchoolCompetency::class);
    }
}

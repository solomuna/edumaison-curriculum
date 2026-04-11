<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RemediationPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'child_id', 'home_skill_id', 'school_year_id',
        'notes', 'status', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'date',
        'completed_at' => 'date',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function homeSkill(): BelongsTo
    {
        return $this->belongsTo(HomeSkill::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RemediationPlanItem::class);
    }
}

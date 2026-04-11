<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RemediationPlanItem extends Model
{
    protected $fillable = [
        'remediation_plan_id', 'exercise_id',
        'order', 'status', 'scheduled_at', 'completed_at',
    ];

    protected $casts = [
        'scheduled_at'  => 'date',
        'completed_at'  => 'date',
    ];

    public function remediationPlan(): BelongsTo
    {
        return $this->belongsTo(RemediationPlan::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}

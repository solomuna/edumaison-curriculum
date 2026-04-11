<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'label', 'start_date', 'end_date', 'is_current',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }

    public function schoolResults(): HasMany
    {
        return $this->hasMany(SchoolResult::class);
    }

    public function exerciseAttempts(): HasMany
    {
        return $this->hasMany(ExerciseAttempt::class);
    }

    public function remediationPlans(): HasMany
    {
        return $this->hasMany(RemediationPlan::class);
    }
}

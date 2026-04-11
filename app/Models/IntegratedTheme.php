<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntegratedTheme extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject_id', 'name', 'slug', 'description', 'order', 'is_active',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}

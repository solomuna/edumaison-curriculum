<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    protected $fillable = [
        'household_id', 'name', 'type',
        'user_agent', 'ip_address', 'is_trusted', 'last_seen_at',
    ];

    protected $casts = [
        'is_trusted'   => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}

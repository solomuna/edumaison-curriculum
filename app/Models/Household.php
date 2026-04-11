<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Household extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'city', 'school', 'is_active',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}

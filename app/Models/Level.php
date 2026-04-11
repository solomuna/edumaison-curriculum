<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'order', 'cycle',
    ];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }
}

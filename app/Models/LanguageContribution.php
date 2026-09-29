<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageContribution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'household_id', 'household_national_language_id', 'created_by_user_id',
        'variant_name', 'kind', 'source_text', 'french_translation',
        'english_translation', 'usage_context', 'source_origin',
        'source_reference', 'rights_confirmed', 'status', 'submitted_at',
    ];

    protected $casts = [
        'rights_confirmed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}

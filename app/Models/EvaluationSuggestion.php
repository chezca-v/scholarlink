<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationSuggestion extends Model
{
    // Disable updated_at since suggestions only have created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'evaluation_id',
        'scholarship_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Belongs to an evaluation
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    // Belongs to the suggested scholarship
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluatorAssignment extends Model
{
    protected $fillable = [
        'evaluator_id',
        'scholarship_id',
        'assigned_by',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Belongs to the evaluator (user)
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Belongs to a scholarship
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    // Belongs to the admin who assigned (user)
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
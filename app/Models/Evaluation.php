<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'evaluator_id',
        'gpa_score',
        'income_score',
        'final_score',
        'decision',
        'rejection_reason',
        'notes',
        'evaluated_at',
    ];

    protected $casts = [
        'gpa_score'    => 'decimal:2',
        'income_score' => 'decimal:2',
        'final_score'  => 'decimal:2',
        'evaluated_at' => 'datetime',
    ];

    // Belongs to an application
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    // Belongs to an evaluator (user)
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // One evaluation has many suggestions
    public function suggestions(): HasMany
    {
        return $this->hasMany(EvaluationSuggestion::class);
    }

    // Helpers: check decision
    public function isApproved(): bool
    {
        return $this->decision === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->decision === 'rejected';
    }

    public function isRevisionRequested(): bool
    {
        return $this->decision === 'revision_requested';
    }

    public function isPending(): bool
    {
        return $this->decision === null;
    }

    // Helper: compute final score from weights
    public function computeFinalScore(int $weightGpa, int $weightIncome): float
    {
        return round(
            ($this->gpa_score * $weightGpa / 100) +
            ($this->income_score * $weightIncome / 100),
            2
        );
    }
}
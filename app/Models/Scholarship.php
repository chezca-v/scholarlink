<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'provider_name',
        'created_by',
        'name',
        'tagline',
        'description',
        'gpa_requirement',
        'income_bracket',
        'slots',
        'eligibility',
        'benefits',
        'requirements',
        'open_date',
        'deadline',
        'status',
        'blind_screening',
        'weight_gpa',
        'weight_income',
        'tags',
        'ai_match_enabled',
        'gcal_event_id',
        'contact_email',
        'website',
        'address',
        'benefit_snippet_1',
        'benefit_snippet_2',
        'org_logo',
        'posted_at',
    ];

    protected $casts = [
        'open_date'        => 'date',
        'deadline'         => 'date',
        'posted_at'        => 'datetime',
        'tags'             => 'array',
        'blind_screening'  => 'boolean',
        'ai_match_enabled' => 'boolean',
        'gpa_requirement'  => 'decimal:2',
    ];

    // Belongs to the admin who created it
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // One scholarship has many applications
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // One scholarship has many saved_scholarships
    public function savedByUsers(): HasMany
    {
        return $this->hasMany(SavedScholarship::class);
    }

    // One scholarship has many evaluator assignments
    public function evaluatorAssignments(): HasMany
    {
        return $this->hasMany(EvaluatorAssignment::class);
    }

    // One scholarship has many subscriptions
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ScholarshipSubscription::class);
    }

    // One scholarship can appear in many evaluation suggestions
    public function evaluationSuggestions(): HasMany
    {
        return $this->hasMany(EvaluationSuggestion::class);
    }

    // Helpers: check status
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isComingSoon(): bool
    {
        return $this->status === 'coming_soon';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // Helper: check if deadline has passed
    public function isExpired(): bool
    {
        if (!$this->deadline) return false;
        return $this->deadline->isPast();
    }

    // Helper: get remaining slots
    public function remainingSlots(): int
    {
        $approved = $this->applications()
                         ->where('status', 'approved')
                         ->count();
        return max(0, $this->slots - $approved);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'oauth_provider',
        'oauth_id',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'phone_verified_at'  => 'datetime',
        'is_active'          => 'boolean',
    ];

    // Belongs to an organization (admins only)
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // One user has one applicant profile
    public function applicantProfile(): HasOne
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    // One user has many documents
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // One user has many applications (as applicant)
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'applicant_id');
    }

    // One user has many evaluations (as evaluator)
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    // One user has many notifications
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // One user has many activity logs
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // One user has many saved scholarships
    public function savedScholarships(): HasMany
    {
        return $this->hasMany(SavedScholarship::class);
    }

    // One user has many evaluator assignments
    public function evaluatorAssignments(): HasMany
    {
        return $this->hasMany(EvaluatorAssignment::class, 'evaluator_id');
    }

    // Helper: check role
    public function isApplicant(): bool
    {
        return $this->role === 'applicant';
    }

    public function isEvaluator(): bool
    {
        return $this->role === 'evaluator';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }
}

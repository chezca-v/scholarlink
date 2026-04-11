<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'document_type',
        'file_url',
        'status',
        'expiry_date',
        'expiry_notified_at',
        'verified_by',
    ];

    protected $casts = [
        'expiry_date'        => 'date',
        'expiry_notified_at' => 'datetime',
    ];

    // Belongs to a user (document owner)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to a user (the one who verified it)
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // One document can be in many application_documents
    public function applicationDocuments(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    // Helper: check status
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // Helper: check if document is expiring soon (within 30 days)
    public function isExpiringSoon(): bool
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date->diffInDays(now()) <= 30;
    }
}
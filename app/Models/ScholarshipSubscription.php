<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipSubscription extends Model
{
    protected $fillable = [
        'scholarship_id',
        'email',
        'user_id',
        'subscribed_at',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
    ];

    // Belongs to a scholarship
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    // Belongs to a user (nullable — guest if null)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper: check if subscriber is a guest
    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    // Helper: check if subscriber is a registered user
    public function isRegistered(): bool
    {
        return $this->user_id !== null;
    }
}
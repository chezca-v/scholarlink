<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'is_read',
        'related_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helpers: check type
    public function isInApp(): bool
    {
        return $this->type === 'in_app';
    }

    public function isEmail(): bool
    {
        return $this->type === 'email';
    }

    public function isSms(): bool
    {
        return $this->type === 'sms';
    }

    // Helper: mark as read
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
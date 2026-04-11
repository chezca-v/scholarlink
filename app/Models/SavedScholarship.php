<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedScholarship extends Model
{
    protected $fillable = [
        'user_id',
        'scholarship_id',
        'saved_at',
    ];

    protected $casts = [
        'saved_at' => 'datetime',
    ];

    // Belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to a scholarship
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }
}
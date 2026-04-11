<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    // Disable updated_at since activity logs only have created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Belongs to a user (nullable — some logs may not have a user)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper: log a new activity anywhere in the app
    public static function record(
        string $action,
        string $targetType = null,
        int $targetId = null,
        int $userId = null
    ): void {
        static::create([
            'user_id'     => $userId ?? auth()->id(),
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
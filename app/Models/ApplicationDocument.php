<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id',
        'document_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    // Belongs to an application
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    // Belongs to a document
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
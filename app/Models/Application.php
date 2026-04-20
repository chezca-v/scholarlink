<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_code',
        'applicant_id',
        'scholarship_id',
        'status',
        'stage',
        'ai_match_score',
        'conflict_flag',
        'submitted_at',
        'decided_at',
    ];

    protected $casts = [
        'submitted_at'    => 'datetime',
        'decided_at'      => 'datetime',
        'ai_match_score'  => 'decimal:2',
        'conflict_flag'   => 'boolean',
    ];

    // Belongs to the applicant (user)
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    // Belongs to a scholarship
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    // One application has many evaluations
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    // One application has many application_documents
    public function applicationDocuments(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    // Helpers: check status
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isRevision(): bool
    {
        return $this->status === 'revision';
    }

    // Helpers: check stage
    public function isInDocReview(): bool
    {
        return $this->stage === 'doc_review';
    }

    public function isInScoring(): bool
    {
        return $this->stage === 'scoring';
    }

    public function isDecided(): bool
    {
        return $this->stage === 'decided';
    }

    // Helper: generate reference code
    public static function generateReferenceCode(Scholarship $scholarship, int $year): string
    {
        $skip = ['for', 'the', 'and', 'of', 'ng', 'mo', 'ko', 'at', 'a', 'an', 'in', 'sa'];
        $words = explode(' ', preg_replace('/[^a-zA-Z ]/', '', $scholarship->name));
        $initials = '';
        foreach ($words as $word) {
            if (!in_array(strtolower($word), $skip) && strlen($word) > 0) {
                $initials .= strtoupper($word[0]);
                if (strlen($initials) >= 2) break;
            }
        }

        $queue = static::where('scholarship_id', $scholarship->id)
                       ->whereYear('created_at', $year)
                       ->count() + 1;

        return $initials . '-' . $year . '-' . str_pad($queue, 5, '0', STR_PAD_LEFT);
    }
}
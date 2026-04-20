<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'avatar_url',
        'date_of_birth',
        'sex',
        'home_address',
        'city',
        'province',
        'zip_code',
        'mobile_number',
        'sms_opted_in',
        'phone_verified_at',
        'university_name',
        'university_address',
        'university_email',
        'course_program',
        'student_number',
        'year_level',
        'semester',
        'academic_year',
        'gwa',
        'monthly_household_income',
        'num_dependents',
        'is_breadwinner',
        'is_4ps',
        'father_employment_status',
        'mother_employment_status',
        'profile_completed_at',
    ];

    protected $casts = [
        'date_of_birth'            => 'date',
        'phone_verified_at'        => 'datetime',
        'profile_completed_at'     => 'datetime',
        'gwa'                      => 'decimal:2',
        'monthly_household_income' => 'decimal:2',
        'sms_opted_in'             => 'boolean',
        'is_4ps'                   => 'boolean',
    ];

    // Belongs to a user (1:1)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

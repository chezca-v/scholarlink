<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('avatar_url', 500)->nullable();
            $table->date('date_of_birth');
            $table->enum('sex', ['Male', 'Female']);
            $table->text('home_address');
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('zip_code', 10);
            $table->string('mobile_number', 20);
            $table->tinyInteger('sms_opted_in')->default(0);
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('university_name', 255);
            $table->text('university_address');
            $table->string('university_email', 255);
            $table->string('course_program', 255);
            $table->string('student_number', 50);
            $table->string('year_level', 20);
            $table->string('semester', 20);
            $table->string('academic_year', 20);
            $table->decimal('gwa', 4, 2);
            $table->decimal('monthly_household_income', 12, 2);
            $table->integer('num_dependents');
            $table->enum('is_breadwinner', ['Yes', 'No', 'Partial Contributor']);
            $table->tinyInteger('is_4ps')->default(0);
            $table->string('father_employment_status', 100)->nullable();
            $table->string('mother_employment_status', 100)->nullable();
            $table->timestamp('profile_completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};

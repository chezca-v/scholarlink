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
            $table->string('university_name', 255)->nullable();
            $table->text('university_address')->nullable();
            $table->string('university_email', 255)->nullable();
            $table->string('course_program', 255)->nullable();
            $table->string('student_number', 50)->nullable();
            $table->string('year_level', 20)->nullable();
            $table->string('semester', 20)->nullable();
            $table->string('academic_year', 20)->nullable();
            $table->decimal('gwa', 4, 2)->nullable();
            $table->decimal('monthly_household_income', 12, 2)->nullable();
            $table->integer('num_dependents')->nullable();
            $table->enum('is_breadwinner', ['Yes', 'No', 'Partial Contributor'])->nullable();
            $table->tinyInteger('is_4ps')->default(0)->nullable();
            $table->string('father_employment_status', 100)->nullable();
            $table->string('mother_employment_status', 100)->nullable();
            $table->timestamp('profile_completed_at')->nullable();
            $table->enum('gwa_scale', ['college', 'shs'])->default('college');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 20)->unique();
            $table->foreignId('applicant_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('scholarship_id')
                  ->constrained('scholarships')
                  ->cascadeOnDelete();
            $table->enum('status', [
                'pending',
                'under_review',
                'approved',
                'rejected',
                'revision'
            ])->default('pending');
            $table->enum('stage', [
                'submitted',
                'doc_review',
                'scoring',
                'decided'
            ])->default('submitted');
            $table->decimal('ai_match_score', 5, 2)->nullable();
            $table->tinyInteger('conflict_flag')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

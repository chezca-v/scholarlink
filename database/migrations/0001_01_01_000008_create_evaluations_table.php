<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                  ->constrained('applications')
                  ->cascadeOnDelete();
            $table->foreignId('evaluator_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->decimal('gpa_score', 5, 2);
            $table->decimal('income_score', 5, 2);
            $table->decimal('final_score', 5, 2)->nullable();
            $table->enum('decision', [
                'approved',
                'rejected',
                'revision_requested'
            ])->nullable();
            $table->string('rejection_reason', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

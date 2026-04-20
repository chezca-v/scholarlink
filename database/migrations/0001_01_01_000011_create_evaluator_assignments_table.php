<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluator_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluator_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('scholarship_id')
                  ->constrained('scholarships')
                  ->cascadeOnDelete();
            $table->foreignId('assigned_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['evaluator_id', 'scholarship_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluator_assignments');
    }
};

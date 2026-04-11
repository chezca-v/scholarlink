<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')
                  ->constrained('evaluations')
                  ->cascadeOnDelete();
            $table->foreignId('scholarship_id')
                  ->constrained('scholarships')
                  ->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            // Prevent duplicate suggestions per evaluation
            $table->unique(['evaluation_id', 'scholarship_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_suggestions');
    }
};

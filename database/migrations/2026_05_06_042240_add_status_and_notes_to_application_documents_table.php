<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('application_documents', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'revision_requested'])->default('pending')->after('submitted_at');
            $table->text('evaluator_notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_documents', function (Blueprint $table) {
            $table->dropColumn(['status', 'evaluator_notes']);
        });
    }
};

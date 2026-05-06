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
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('offer_status', ['pending', 'accepted', 'rejected'])->nullable()->after('decided_at');
            $table->timestamp('offer_expires_at')->nullable()->after('offer_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['offer_status', 'offer_expires_at']);
        });
    }
};

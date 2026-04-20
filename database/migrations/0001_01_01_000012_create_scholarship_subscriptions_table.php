<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')
                  ->constrained('scholarships')
                  ->cascadeOnDelete();
            $table->string('email', 255);
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamps();

            // Prevent duplicate subscriptions per scholarship
            $table->unique(['scholarship_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_subscriptions');
    }
};

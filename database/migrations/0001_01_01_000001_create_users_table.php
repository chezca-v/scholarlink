<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                  ->nullable()
                  ->constrained('organizations')
                  ->nullOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255)->nullable();
            $table->enum('role', ['superadmin', 'admin', 'evaluator', 'applicant'])
                  ->default('applicant');
            $table->string('oauth_provider', 50)->nullable();
            $table->string('oauth_id', 255)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

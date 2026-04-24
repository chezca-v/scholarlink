<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name', 255);
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('name', 255);
            $table->string('tagline', 255)->nullable();
            $table->text('description');
            $table->decimal('gpa_requirement', 4, 2);
            $table->string('income_bracket', 100)->nullable();
            $table->integer('slots');
            $table->text('eligibility');
            $table->text('benefits');
            $table->text('requirements');
            $table->date('open_date')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['draft', 'open', 'closed', 'coming_soon'])
                  ->default('draft');
            $table->tinyInteger('blind_screening')->default(0);
            $table->integer('weight_gpa')->default(50);
            $table->integer('weight_income')->default(50);
            $table->json('tags')->nullable();
            $table->tinyInteger('ai_match_enabled')->default(1);
            $table->string('gcal_event_id', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('benefit_snippet_1', 255)->nullable();
            $table->string('benefit_snippet_2', 255)->nullable();
            $table->string('org_logo', 500)->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};

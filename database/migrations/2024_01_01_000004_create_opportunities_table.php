<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('provider_profiles')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['scholarship', 'internship', 'training', 'job'])->index();
            $table->enum('status', ['active', 'closed', 'draft'])->default('draft')->index();
            $table->date('start_date')->nullable();
            $table->date('application_deadline');
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('duration')->nullable();
            $table->integer('positions_available')->default(1);
            $table->integer('applications_received')->default(0);
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('course_eligibility')->nullable();
            $table->string('tags')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('saves_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('application_deadline');
            // Removed duplicate indexes since enum already has ->index()
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
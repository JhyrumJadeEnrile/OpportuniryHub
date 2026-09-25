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
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->enum('status', ['submitted', 'under_review', 'shortlisted', 'rejected', 'accepted'])->default('submitted')->index();
            $table->text('cover_letter')->nullable();
            $table->string('resume')->nullable();
            $table->json('application_answers')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->unique(['student_id', 'opportunity_id']);
            // Remove this line: $table->index('status');  ← already indexed above
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
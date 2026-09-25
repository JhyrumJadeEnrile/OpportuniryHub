<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deadline_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->enum('reminder_type', ['1_day', '3_days', '1_week', '2_weeks'])->default('3_days');
            $table->timestamp('send_at');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'opportunity_id']);
            $table->index('is_sent');
            $table->index('send_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deadline_reminders');
    }
};

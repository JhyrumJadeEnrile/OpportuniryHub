<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained('opportunities')->onDelete('cascade');
            $table->timestamp('saved_at')->useCurrent();
            $table->timestamps();

            $table->unique(['student_id', 'opportunity_id']);
            $table->index('saved_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_opportunities');
    }
};

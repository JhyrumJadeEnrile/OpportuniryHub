<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('student_id')->nullable();
            $table->string('course')->nullable();
            $table->string('year_level')->nullable(); // 1st Year, 2nd Year, etc.
            $table->string('gpa')->nullable();
            $table->text('skills')->nullable();
            $table->text('interests')->nullable();
            $table->string('resume')->nullable();
            $table->string('university')->default('TUP-Taguig');
            $table->date('graduation_date')->nullable();
            $table->integer('saved_opportunities_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamps();

            $table->unique('user_id');
            $table->index('course');
            $table->index('year_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};

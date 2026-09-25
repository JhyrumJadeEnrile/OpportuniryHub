<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->text('company_description')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('industry')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('company_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Philippines');
            $table->integer('total_opportunities')->default(0);
            $table->integer('active_opportunities')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->index('company_name');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_profiles');
    }
};

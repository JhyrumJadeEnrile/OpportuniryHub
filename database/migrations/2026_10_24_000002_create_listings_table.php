<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 120);
            $table->text('description');
            $table->string('category', 30); // books, electronics, clothing, furniture, services, others
            $table->decimal('price', 10, 2)->nullable();
            $table->string('condition', 20)->nullable(); // new, like_new, used
            $table->string('image_path')->nullable();
            $table->string('status', 20)->default('available'); // available | sold
            $table->timestamps();

            $table->index(['status', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->date('deadline')->nullable()->after('attachment_name');
            $table->string('status', 20)->default('open')->after('deadline');
            $table->string('requirement_path')->nullable()->after('status');
            $table->string('requirement_name')->nullable()->after('requirement_path');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['deadline', 'status', 'requirement_path', 'requirement_name']);
        });
    }
};
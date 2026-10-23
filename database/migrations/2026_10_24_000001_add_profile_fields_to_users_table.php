<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_type', 20)->default('student')->after('email'); // student | fresh_grad | alumni
            $table->unsignedSmallInteger('batch_year')->nullable()->after('member_type');
            $table->string('program', 120)->nullable()->after('batch_year');
            $table->string('headline', 160)->nullable()->after('program');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['member_type', 'batch_year', 'program', 'headline']);
        });
    }
};

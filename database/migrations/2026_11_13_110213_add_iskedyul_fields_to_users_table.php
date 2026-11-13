<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id', 20)->unique()->nullable();
            $table->enum('user_type', ['Student', 'Faculty', 'Admin'])
                  ->default('Student');
            $table->string('program')->nullable();
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['user_id']);

            $table->dropColumn([
                'user_id',
                'user_type',
                'program',
                'year_level',
                'section',
            ]);
        });
    }
};
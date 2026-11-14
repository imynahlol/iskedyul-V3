<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('text');
            $table->text('description')->nullable();
        });
    }

    public function down(): void {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->dropColumn([
                'key',
                'value',
                'group',
                'type',
                'description',
            ]);
        });
    }
};
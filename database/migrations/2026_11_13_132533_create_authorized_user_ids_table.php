<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('authorized_user_ids', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->unique();
            $table->enum('user_type', ['Student', 'Faculty']);
            $table->boolean('is_active')->default(true);
            $table->foreignId('claimed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('authorized_user_ids');
    }
};
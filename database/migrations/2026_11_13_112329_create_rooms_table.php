<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('room_code');
            $table->string('room_name')->nullable();
            $table->text('description')->nullable();

            $table->enum('status', ['Active', 'Inactive'])
                  ->default('Active');

            $table->timestamps();

            $table->unique(['building_id', 'room_code']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};
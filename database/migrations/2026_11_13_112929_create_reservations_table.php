<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->string('reservation_number')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->text('purpose');

            $table->enum('status', ['Reserved', 'Cancelled'])
                ->default('Reserved');

            $table->decimal('duration_hours', 8, 2);

            $table->timestamps();

            $table->index([
                'room_id',
                'reservation_date',
                'status',
            ]);
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservations');
    }
};
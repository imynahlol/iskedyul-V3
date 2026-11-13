<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model {
    protected $fillable = [
        'reservation_number',
        'user_id',
        'building_id',
        'room_id',
        'reservation_date',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'duration_hours',
    ];

    protected function casts(): array {
        return [
            'reservation_date' => 'date',
            'duration_hours' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }

    public function room(): BelongsTo {
        return $this->belongsTo(Room::class);
    }

    public function logs(): HasMany {
        return $this->hasMany(ReservationLog::class);
    }
}
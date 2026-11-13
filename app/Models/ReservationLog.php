<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationLog extends Model {
    protected $fillable = [
        'reservation_id',
        'user_id',
        'action',
        'previous_values',
        'new_values',
    ];

    protected function casts(): array {
        return [
            'previous_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function reservation(): BelongsTo {
        return $this->belongsTo(Reservation::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
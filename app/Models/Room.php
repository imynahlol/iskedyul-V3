<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model {
    protected $fillable = [
        'building_id',
        'room_code',
        'room_name',
        'description',
        'status',
    ];

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }
}
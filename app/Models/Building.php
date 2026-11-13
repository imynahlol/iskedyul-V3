<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model {
    protected $fillable = [
        'building_name',
        'building_code',
        'description',
        'status',
    ];

    public function rooms(): HasMany {
        return $this->hasMany(Room::class);
    }

    public function reservations(): HasMany {
        return $this->hasMany(Reservation::class);
    }
}
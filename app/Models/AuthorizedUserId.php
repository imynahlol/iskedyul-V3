<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorizedUserId extends Model {
    protected $fillable = [
        'user_id',
        'user_type',
        'is_active',
        'claimed_by',
        'claimed_at',
    ];

    protected function casts(): array {
        return [
            'is_active' => 'boolean',
            'claimed_at' => 'datetime',
        ];
    }

    public function claimedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'claimed_by');
    }
}
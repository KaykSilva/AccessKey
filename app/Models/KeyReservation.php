<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyReservation extends Model
{
    protected $fillable = [
        'key_id',
        'person_id',
        'requested_by_user_id',
        'start_at',
        'end_at',
        'period',
        'reason',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(KeyPerson::class, 'person_id');
    }

    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }
}

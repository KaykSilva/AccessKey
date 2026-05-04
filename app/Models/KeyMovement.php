<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyMovement extends Model
{
    protected $fillable = [
        'key_id',
        'person_id',
        'taken_by_user_id',
        'delivered_by_user_id',
        'received_by_user_id',
        'taken_at',
        'returned_at',
        'take_notes',
        'return_notes',
        'status',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(KeyPerson::class, 'person_id');
    }

    public function takenByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'taken_by_user_id');
    }

    public function deliveredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by_user_id');
    }

    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }
}

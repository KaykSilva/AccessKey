<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeyPerson extends Model
{
    protected $fillable = [
        'name',
        'document',
        'email',
        'phone',
        'department',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(KeyMovement::class, 'person_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(KeyReservation::class, 'person_id');
    }
}

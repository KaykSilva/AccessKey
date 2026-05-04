<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Key extends Model
{
    protected $fillable = [
        'responsible_id',
        'name',
        'code',
        'description',
        'location',
        'status',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(KeyResponsible::class, 'responsible_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(KeyMovement::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(KeyReservation::class);
    }

    public function currentMovement()
    {
        return $this->hasOne(KeyMovement::class)
            ->where('status', 'borrowed')
            ->latestOfMany();
    }
}

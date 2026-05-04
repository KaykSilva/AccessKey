<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeyResponsible extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'department',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function keys(): HasMany
    {
        return $this->hasMany(Key::class, 'responsible_id');
    }
}

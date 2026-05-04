<?php

namespace App\Services\KeyPerson;

use App\Models\KeyPerson;

class KeyPersonListService
{
    public function execute(array $filters = [])
    {
        return KeyPerson::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            })
            ->when(isset($filters['active']), fn ($query) => $query->where('active', $filters['active']))
            ->latest()
            ->paginate(10);
    }
}

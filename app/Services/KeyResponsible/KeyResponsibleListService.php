<?php

namespace App\Services\KeyResponsible;

use App\Models\KeyResponsible;

class KeyResponsibleListService
{
    public function execute(array $filters = [])
    {
        return KeyResponsible::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            })
            ->when(isset($filters['active']), fn ($query) => $query->where('active', $filters['active']))
            ->latest()
            ->paginate(10);
    }
}

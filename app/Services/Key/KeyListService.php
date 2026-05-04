<?php

namespace App\Services\Key;

use App\Models\Key;

class KeyListService
{
    public function execute(array $filters = [])
    {
        return Key::query()
            ->with(['responsible', 'currentMovement.person'])
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when(isset($filters['active']), fn ($q) => $q->where('active', $filters['active']))
            ->latest()
            ->paginate(10);
    }
}

<?php

namespace App\Services\KeyReservation;

use App\Models\KeyReservation;

class KeyReservationListService
{
    public function execute(array $filters = [])
    {
        return KeyReservation::query()
            ->with(['key', 'person', 'requestedByUser'])
            ->when($filters['key_id'] ?? null, fn ($query, $keyId) => $query->where('key_id', $keyId))
            ->when($filters['person_id'] ?? null, fn ($query, $personId) => $query->where('person_id', $personId))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['start_at'] ?? null, fn ($query, $startAt) => $query->where('start_at', '>=', $startAt))
            ->when($filters['end_at'] ?? null, fn ($query, $endAt) => $query->where('end_at', '<=', $endAt))
            ->latest()
            ->paginate(10);
    }
}

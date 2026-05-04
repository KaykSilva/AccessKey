<?php

namespace App\Services\KeyReservation;

use App\Models\KeyReservation;
use Exception;

class KeyReservationCreateService
{
    public function execute(array $data): KeyReservation
    {
        $existsConflict = KeyReservation::query()
            ->where('key_id', $data['key_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                    ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_at', '<=', $data['start_at'])
                            ->where('end_at', '>=', $data['end_at']);
                    });
            })
            ->exists();

        if ($existsConflict) {
            throw new Exception('Já existe uma reserva para esta chave nesse período.');
        }

        return KeyReservation::create([
            ...$data,
            'requested_by_user_id' => auth()->id(),
            'status' => $data['status'] ?? 'pending',
        ])->load(['key', 'person', 'requestedByUser']);
    }
}

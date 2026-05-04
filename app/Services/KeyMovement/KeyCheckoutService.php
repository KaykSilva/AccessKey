<?php

namespace App\Services\KeyMovement;

use App\Models\Key;
use App\Models\KeyMovement;
use Exception;
use Illuminate\Support\Facades\DB;

class KeyCheckoutService
{
    public function execute(Key $key, array $data): KeyMovement
    {
        return DB::transaction(function () use ($key, $data) {
            $key = Key::query()
                ->where('id', $key->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($key->status !== 'available') {
                throw new Exception('Esta chave não está disponível para retirada.');
            }

            $movement = KeyMovement::create([
                'key_id' => $key->id,
                'person_id' => $data['person_id'],
                'taken_by_user_id' => auth()->id(),
                'taken_at' => now(),
                'take_notes' => $data['take_notes'] ?? null,
                'status' => 'borrowed',
            ]);

            $key->update([
                'status' => 'borrowed',
            ]);

            return $movement->load(['key', 'person', 'takenByUser']);
        });
    }
}

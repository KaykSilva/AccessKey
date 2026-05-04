<?php

namespace App\Services\KeyMovement;

use App\Models\Key;
use App\Models\KeyMovement;
use Exception;
use Illuminate\Support\Facades\DB;

class KeyReturnService
{
    public function execute(Key $key, array $data): KeyMovement
    {
        return DB::transaction(function () use ($key, $data) {
            $key = Key::query()
                ->where('id', $key->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($key->status !== 'borrowed') {
                throw new Exception('Esta chave não está marcada como retirada.');
            }

            $movement = KeyMovement::query()
                ->where('key_id', $key->id)
                ->where('status', 'borrowed')
                ->latest()
                ->lockForUpdate()
                ->first();

            if (!$movement) {
                throw new Exception('Nenhum movimento de retirada aberto foi encontrado para esta chave.');
            }

            $movement->update([
                'delivered_by_user_id' => $data['delivered_by_user_id'] ?? null,
                'received_by_user_id' => auth()->id(),
                'returned_at' => now(),
                'return_notes' => $data['return_notes'] ?? null,
                'status' => 'returned',
            ]);

            $key->update([
                'status' => 'available',
            ]);

            return $movement->fresh()->load([
                'key',
                'person',
                'takenByUser',
                'deliveredByUser',
                'receivedByUser',
            ]);
        });
    }
}

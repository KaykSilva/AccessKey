<?php

namespace App\Services\KeyReservation;

use App\Models\KeyReservation;
use Exception;

class KeyReservationDeleteService
{
    public function execute(KeyReservation $keyReservation): void
    {
        if ($keyReservation->status === 'approved') {
            throw new Exception('Não é possível excluir uma reserva aprovada. Cancele a reserva primeiro.');
        }

        $keyReservation->delete();
    }
}

<?php

namespace App\Http\Controllers\KeyReservation;

use App\Models\KeyReservation;
use App\Services\KeyReservation\KeyReservationDeleteService;
use Illuminate\Http\RedirectResponse;

class KeyReservationDestroyController
{
    public function __invoke(KeyReservation $keyReservation, KeyReservationDeleteService $service): RedirectResponse
    {
        $service->execute($keyReservation);

        return redirect()->back();
    }
}

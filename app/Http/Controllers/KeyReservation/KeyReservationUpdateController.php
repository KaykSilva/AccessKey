<?php

namespace App\Http\Controllers\KeyReservation;

use App\Http\Requests\KeyReservation\UpdateKeyReservationRequest;
use App\Models\KeyReservation;
use App\Services\KeyReservation\KeyReservationUpdateService;
use Illuminate\Http\RedirectResponse;

class KeyReservationUpdateController
{
    public function __invoke(UpdateKeyReservationRequest $request, KeyReservation $keyReservation, KeyReservationUpdateService $service): RedirectResponse
    {
        $service->execute($keyReservation, $request->validated());

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\KeyReservation;

use App\Http\Requests\KeyReservation\StoreKeyReservationRequest;
use App\Services\KeyReservation\KeyReservationCreateService;
use Illuminate\Http\RedirectResponse;

class KeyReservationStoreController
{
    public function __invoke(StoreKeyReservationRequest $request, KeyReservationCreateService $service): RedirectResponse
    {
        $service->execute($request->validated());

        return redirect()->back();
    }
}

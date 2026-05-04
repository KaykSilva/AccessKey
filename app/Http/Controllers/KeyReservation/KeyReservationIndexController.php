<?php

namespace App\Http\Controllers\KeyReservation;

use App\Models\Key;
use App\Models\KeyPerson;
use App\Services\KeyReservation\KeyReservationListService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeyReservationIndexController
{
    public function __invoke(Request $request, KeyReservationListService $service)
    {
        return Inertia::render('KeyReservations/Index', [
            'reservations' => $service->execute($request->all()),
            'filters' => $request->all(),
            'keys' => Key::orderBy('name')->get(),
            'people' => KeyPerson::where('active', true)->orderBy('name')->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\KeyMovement;

use App\Models\Key;
use App\Models\KeyMovement;
use App\Models\KeyPerson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeyMovementIndexController
{
    public function __invoke(Request $request)
    {
        $movements = KeyMovement::query()
            ->with([
                'key',
                'person',
                'takenByUser',
                'deliveredByUser',
                'receivedByUser',
            ])
            ->when($request->key_id, fn ($query, $keyId) => $query->where('key_id', $keyId))
            ->when($request->person_id, fn ($query, $personId) => $query->where('person_id', $personId))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('KeyMovements/Index', [
            'movements' => $movements,
            'filters' => $request->all(),
            'keys' => Key::orderBy('name')->get(),
            'people' => KeyPerson::orderBy('name')->get(),
        ]);
    }
}

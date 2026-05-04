<?php

namespace App\Http\Controllers\Key;

use App\Models\KeyPerson;
use App\Models\KeyResponsible;
use App\Services\Key\KeyListService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeyIndexController
{
    public function __invoke(Request $request, KeyListService $service)
    {
        $keys = $service->execute($request->all());

        return Inertia::render('Keys/Index', [
            'keys' => $keys,
            'filters' => $request->all(),
            'responsibles' => KeyResponsible::where('active', true)->orderBy('name')->get(),
            'people' => KeyPerson::where('active', true)->orderBy('name')->get(),
        ]);
    }
}

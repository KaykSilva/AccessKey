<?php

namespace App\Http\Controllers\Key;

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
        ]);
    }
}

<?php

namespace App\Http\Controllers\KeyPerson;

use App\Services\KeyPerson\KeyPersonListService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeyPersonIndexController
{
    public function __invoke(Request $request, KeyPersonListService $service)
    {
        return Inertia::render('KeyPeople/Index', [
            'people' => $service->execute($request->all()),
            'filters' => $request->all(),
        ]);
    }
}

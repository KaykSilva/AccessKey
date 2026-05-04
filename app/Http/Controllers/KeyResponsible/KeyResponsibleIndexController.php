<?php

namespace App\Http\Controllers\KeyResponsible;

use App\Services\KeyResponsible\KeyResponsibleListService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KeyResponsibleIndexController
{
    public function __invoke(Request $request, KeyResponsibleListService $service)
    {
        return Inertia::render('KeyResponsibles/Index', [
            'responsibles' => $service->execute($request->all()),
            'filters' => $request->all(),
        ]);
    }
}

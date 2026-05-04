<?php

namespace App\Http\Controllers\KeyResponsible;

use App\Models\KeyResponsible;
use App\Services\KeyResponsible\KeyResponsibleDeleteService;
use Illuminate\Http\RedirectResponse;

class KeyResponsibleDestroyController
{
    public function __invoke(KeyResponsible $keyResponsible, KeyResponsibleDeleteService $service): RedirectResponse
    {
        $service->execute($keyResponsible);

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\KeyResponsible;

use App\Http\Requests\KeyResponsible\StoreKeyResponsibleRequest;
use App\Services\KeyResponsible\KeyResponsibleCreateService;
use Illuminate\Http\RedirectResponse;

class KeyResponsibleStoreController
{
    public function __invoke(StoreKeyResponsibleRequest $request, KeyResponsibleCreateService $service): RedirectResponse
    {
        $service->execute($request->validated());

        return redirect()->back();
    }
}

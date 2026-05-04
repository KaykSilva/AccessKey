<?php

namespace App\Http\Controllers\KeyPerson;

use App\Http\Requests\KeyPerson\StoreKeyPersonRequest;
use App\Services\KeyPerson\KeyPersonCreateService;
use Illuminate\Http\RedirectResponse;

class KeyPersonStoreController
{
    public function __invoke(StoreKeyPersonRequest $request, KeyPersonCreateService $service): RedirectResponse
    {
        $service->execute($request->validated());

        return redirect()->back();
    }
}

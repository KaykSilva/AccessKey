<?php

namespace App\Http\Controllers\Key;

use App\Http\Requests\Key\StoreKeyRequest;
use App\Services\Key\KeyCreateService;
use Illuminate\Http\RedirectResponse;

class KeyStoreController
{
    public function __invoke(StoreKeyRequest $request, KeyCreateService $service): RedirectResponse
    {
        $service->execute($request->validated());

        return redirect()->route('keys.index');
    }
}

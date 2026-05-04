<?php

namespace App\Http\Controllers\KeyMovement;

use App\Http\Requests\KeyMovement\StoreKeyCheckoutRequest;
use App\Models\Key;
use App\Services\KeyMovement\KeyCheckoutService;
use Illuminate\Http\RedirectResponse;

class KeyCheckoutController
{
    public function __invoke(Key $key, StoreKeyCheckoutRequest $request, KeyCheckoutService $service): RedirectResponse
    {
        $service->execute($key, $request->validated());

        return redirect()->back();
    }
}

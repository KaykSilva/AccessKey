<?php

namespace App\Http\Controllers\KeyMovement;

use App\Http\Requests\KeyMovement\StoreKeyReturnRequest;
use App\Models\Key;
use App\Services\KeyMovement\KeyReturnService;
use Illuminate\Http\RedirectResponse;

class KeyReturnController
{
    public function __invoke(Key $key, StoreKeyReturnRequest $request, KeyReturnService $service): RedirectResponse
    {
        $service->execute($key, $request->validated());

        return redirect()->back();
    }
}

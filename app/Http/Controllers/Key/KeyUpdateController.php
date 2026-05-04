<?php

namespace App\Http\Controllers\Key;

use App\Http\Requests\Key\UpdateKeyRequest;
use App\Models\Key;
use App\Services\Key\KeyUpdateService;
use Illuminate\Http\RedirectResponse;

class KeyUpdateController
{
    public function __invoke(UpdateKeyRequest $request, Key $key, KeyUpdateService $service): RedirectResponse
    {
        $service->execute($key, $request->validated());

        return redirect()->route('keys.index');
    }
}

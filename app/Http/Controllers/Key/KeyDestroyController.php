<?php

namespace App\Http\Controllers\Key;

use App\Models\Key;
use App\Services\Key\KeyDeleteService;
use Illuminate\Http\RedirectResponse;

class KeyDestroyController
{
    public function __invoke(Key $key, KeyDeleteService $service): RedirectResponse
    {
        $service->execute($key);

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\KeyPerson;

use App\Models\KeyPerson;
use App\Services\KeyPerson\KeyPersonDeleteService;
use Illuminate\Http\RedirectResponse;

class KeyPersonDestroyController
{
    public function __invoke(KeyPerson $keyPerson, KeyPersonDeleteService $service): RedirectResponse
    {
        $service->execute($keyPerson);

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\KeyPerson;

use App\Http\Requests\KeyPerson\UpdateKeyPersonRequest;
use App\Models\KeyPerson;
use App\Services\KeyPerson\KeyPersonUpdateService;
use Illuminate\Http\RedirectResponse;

class KeyPersonUpdateController
{
    public function __invoke(UpdateKeyPersonRequest $request, KeyPerson $keyPerson, KeyPersonUpdateService $service): RedirectResponse
    {
        $service->execute($keyPerson, $request->validated());

        return redirect()->back();
    }
}

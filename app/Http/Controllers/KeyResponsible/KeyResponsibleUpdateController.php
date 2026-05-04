<?php

namespace App\Http\Controllers\KeyResponsible;

use App\Http\Requests\KeyResponsible\UpdateKeyResponsibleRequest;
use App\Models\KeyResponsible;
use App\Services\KeyResponsible\KeyResponsibleUpdateService;
use Illuminate\Http\RedirectResponse;

class KeyResponsibleUpdateController
{
    public function __invoke(
        UpdateKeyResponsibleRequest $request,
        KeyResponsible $keyResponsible,
        KeyResponsibleUpdateService $service
    ): RedirectResponse {
        $service->execute($keyResponsible, $request->validated());

        return redirect()->back();
    }
}

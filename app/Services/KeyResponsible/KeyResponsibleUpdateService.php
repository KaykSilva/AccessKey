<?php

namespace App\Services\KeyResponsible;

use App\Models\KeyResponsible;

class KeyResponsibleUpdateService
{
    public function execute(KeyResponsible $keyResponsible, array $data): KeyResponsible
    {
        $keyResponsible->update($data);

        return $keyResponsible->fresh();
    }
}

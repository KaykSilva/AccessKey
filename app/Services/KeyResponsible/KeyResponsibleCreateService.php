<?php

namespace App\Services\KeyResponsible;

use App\Models\KeyResponsible;

class KeyResponsibleCreateService
{
    public function execute(array $data): KeyResponsible
    {
        return KeyResponsible::create($data);
    }
}

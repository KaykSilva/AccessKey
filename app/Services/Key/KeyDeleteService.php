<?php

namespace App\Services\Key;

use App\Models\Key;
use Exception;

class KeyDeleteService
{
    public function execute(Key $key): void
    {
        if ($key->status === 'borrowed') {
            throw new Exception('Não é possível excluir uma chave em uso.');
        }

        $key->delete();
    }
}

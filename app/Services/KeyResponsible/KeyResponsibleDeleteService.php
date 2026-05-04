<?php

namespace App\Services\KeyResponsible;

use App\Models\KeyResponsible;
use Exception;

class KeyResponsibleDeleteService
{
    public function execute(KeyResponsible $keyResponsible): void
    {
        if ($keyResponsible->keys()->exists()) {
            throw new Exception('Não é possível excluir um responsável vinculado a uma ou mais chaves.');
        }

        $keyResponsible->delete();
    }
}

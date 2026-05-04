<?php

namespace App\Services\KeyPerson;

use App\Models\KeyPerson;
use Exception;

class KeyPersonDeleteService
{
    public function execute(KeyPerson $keyPerson): void
    {
        if ($keyPerson->movements()->exists()) {
            throw new Exception('Não é possível excluir uma pessoa que possui movimentações de chaves.');
        }

        if ($keyPerson->reservations()->exists()) {
            throw new Exception('Não é possível excluir uma pessoa que possui reservas de chaves.');
        }

        $keyPerson->delete();
    }
}

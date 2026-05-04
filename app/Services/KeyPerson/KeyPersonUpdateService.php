<?php

namespace App\Services\KeyPerson;

use App\Models\KeyPerson;

class KeyPersonUpdateService
{
    public function execute(KeyPerson $keyPerson, array $data): KeyPerson
    {
        $keyPerson->update($data);

        return $keyPerson->fresh();
    }
}

<?php

namespace App\Services\KeyPerson;

use App\Models\KeyPerson;

class KeyPersonCreateService
{
    public function execute(array $data): KeyPerson
    {
        return KeyPerson::create($data);
    }
}

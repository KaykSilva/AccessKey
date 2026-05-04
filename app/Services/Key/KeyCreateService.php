<?php

namespace App\Services\Key;

use App\Models\Key;

class KeyCreateService
{
    public function execute(array $data): Key
    {
        return Key::create($data);
    }
}

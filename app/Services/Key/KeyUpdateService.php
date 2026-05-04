<?php

namespace App\Services\Key;

use App\Models\Key;

class KeyUpdateService
{
    public function execute(Key $key, array $data): Key
    {
        $key->update($data);

        return $key->fresh();
    }
}

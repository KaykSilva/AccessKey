<?php

namespace App\Http\Requests\KeyMovement;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'return_notes' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests\KeyMovement;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'person_id' => ['required', 'exists:key_people,id'],
            'take_notes' => ['nullable', 'string'],
        ];
    }
}

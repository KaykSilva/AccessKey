<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'responsible_id' => ['nullable', 'exists:key_responsibles,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('keys', 'code')->ignore($this->route('key')),
            ],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,borrowed,maintenance,inactive'],
            'active' => ['boolean'],
        ];
    }
}

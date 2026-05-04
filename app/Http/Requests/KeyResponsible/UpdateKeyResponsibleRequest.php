<?php

namespace App\Http\Requests\KeyResponsible;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeyResponsibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }
}

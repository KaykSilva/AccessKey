<?php

namespace App\Http\Requests\KeyReservation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeyReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key_id' => ['required', 'exists:keys,id'],
            'person_id' => ['required', 'exists:key_people,id'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'period' => ['required', 'in:morning,afternoon,night,custom'],
            'reason' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,approved,rejected,cancelled,finished'],
        ];
    }
}

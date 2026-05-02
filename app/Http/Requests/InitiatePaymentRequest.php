<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'provider' => ['nullable', 'string', 'in:tmoney,moov'],
            'phone_number' => ['required', 'string', 'regex:/^\+?[0-9]{8,15}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'Le numero de telephone est obligatoire.',
            'phone_number.regex' => 'Le numero de telephone est invalide.',
            'provider.in' => 'Le provider doit etre tmoney ou moov.',
        ];
    }
}

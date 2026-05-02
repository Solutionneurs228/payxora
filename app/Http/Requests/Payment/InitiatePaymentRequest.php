<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider' => ['required', 'in:tmoney,moov'],
            'phone_number' => ['required', 'string', 'regex:/^\d{8,15}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => 'Le numero de telephone doit contenir entre 8 et 15 chiffres.',
        ];
    }
}

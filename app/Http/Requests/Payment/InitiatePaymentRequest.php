<?php

namespace App\Http\Requests\Payment;

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
            'transaction_id' => ['required', 'exists:transactions,id'],
            'provider' => ['required', 'string', 'in:tmoney,moov'],
            'phone_number' => ['required', 'string', 'regex:/^\+228[0-9]{8}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required' => 'La transaction est requise.',
            'transaction_id.exists' => 'Cette transaction n\'existe pas.',
            'provider.required' => 'Le provider de paiement est requis.',
            'provider.in' => 'Le provider doit être TMoney ou Moov.',
            'phone_number.required' => 'Le numéro de téléphone est requis.',
            'phone_number.regex' => 'Le numéro doit être au format +228XXXXXXXX.',
        ];
    }
}

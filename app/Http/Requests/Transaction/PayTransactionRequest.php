<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class PayTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', 'in:tmoney,moov,card'],
            'phone_number' => ['required', 'string', 'regex:/^\+228[0-9]{8}$/'],
            'accept_terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Le mode de paiement est obligatoire.',
            'payment_method.in' => 'Le mode de paiement selectionne n\'est pas valide.',
            'phone_number.required' => 'Le numero de telephone est obligatoire.',
            'phone_number.regex' => 'Veuillez entrer un numero togolais valide (+228XXXXXXXX).',
            'accept_terms.accepted' => 'Vous devez accepter les conditions de paiement.',
        ];
    }
}

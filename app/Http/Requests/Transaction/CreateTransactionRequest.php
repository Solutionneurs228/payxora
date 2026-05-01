<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSeller();
    }

    public function rules(): array
    {
        $minAmount = config('payxora.limits.min_amount', 1000);
        $maxAmount = config('payxora.limits.max_amount', 5000000);

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'amount' => ['required', 'numeric', 'min:' . $minAmount, 'max:' . $maxAmount],
            'currency' => ['nullable', 'string', 'size:3', 'in:XOF'],
            'delivery_address' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la transaction est requis.',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'amount.required' => 'Le montant est requis.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant minimum est de :min FCFA.',
            'amount.max' => 'Le montant maximum est de :max FCFA.',
            'currency.in' => 'La devise doit être XOF.',
        ];
    }
}

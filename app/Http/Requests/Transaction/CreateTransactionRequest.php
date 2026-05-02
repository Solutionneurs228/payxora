<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'min:3', 'max:255'],
            'product_description' => ['nullable', 'string', 'max:2000'],
            'amount' => ['required', 'numeric', 'min:' . config('payxora.min_transaction_amount', 1000), 'max:' . config('payxora.max_transaction_amount', 10000000)],
            'currency' => ['required', 'in:XOF'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'seller_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Le montant minimum est de ' . number_format(config('payxora.min_transaction_amount', 1000), 0, ',', ' ') . ' FCFA.',
            'amount.max' => 'Le montant maximum est de ' . number_format(config('payxora.max_transaction_amount', 10000000), 0, ',', ' ') . ' FCFA.',
        ];
    }
}

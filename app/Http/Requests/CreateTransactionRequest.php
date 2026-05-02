<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', 'min:3'],
            'product_description' => ['nullable', 'string', 'max:2000'],
            'amount' => ['required', 'numeric', 'min:100', 'max:10000000'],
            'currency' => ['nullable', 'string', 'in:XOF,USD,EUR'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
            'seller_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Le nom du produit est obligatoire.',
            'product_name.min' => 'Le nom du produit doit contenir au moins 3 caracteres.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.min' => 'Le montant minimum est de 100 XOF.',
            'amount.max' => 'Le montant maximum est de 10 000 000 XOF.',
            'currency.in' => 'La devise doit etre XOF, USD ou EUR.',
        ];
    }
}

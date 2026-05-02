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
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'description' => ['required', 'string', 'max:2000'],
            'amount' => ['required', 'numeric', 'min:100', 'max:10000000'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'delivery_conditions' => ['required', 'string', 'max:1000'],
            'delivery_deadline_days' => ['required', 'integer', 'min:1', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la transaction est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins 3 caracteres.',
            'description.required' => 'La description est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.min' => 'Le montant minimum est de 100 FCFA.',
            'amount.max' => 'Le montant maximum est de 10 000 000 FCFA.',
            'buyer_email.required' => 'L\'email de l\'acheteur est obligatoire.',
            'delivery_conditions.required' => 'Les conditions de livraison sont obligatoires.',
            'delivery_deadline_days.required' => 'Le delai de livraison est obligatoire.',
        ];
    }
}

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
            'provider' => ['required', 'in:tmoney,moov'],
            'phone_number' => ['required', 'string', 'regex:/^\d{8,15}$/'],
        ];
    }
}

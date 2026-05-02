<?php

namespace App\Http\Requests\Dispute;

use Illuminate\Foundation\Http\FormRequest;

class CreateDisputeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => ['required', 'exists:transactions,id'],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.min' => 'Le motif doit contenir au moins 10 caracteres.',
            'reason.max' => 'Le motif ne doit pas depasser 500 caracteres.',
        ];
    }
}

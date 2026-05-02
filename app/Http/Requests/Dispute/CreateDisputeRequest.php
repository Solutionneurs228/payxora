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
            'reason' => ['required', 'string', 'in:non_delivery,wrong_item,damaged,not_as_described,other'],
            'description' => ['required', 'string', 'max:3000', 'min:20'],
            'evidence.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required' => 'La transaction est obligatoire.',
            'transaction_id.exists' => 'Cette transaction n\'existe pas.',
            'reason.required' => 'Le motif du litige est obligatoire.',
            'reason.in' => 'Le motif selectionne n\'est pas valide.',
            'description.required' => 'La description du litige est obligatoire.',
            'description.min' => 'La description doit contenir au moins 20 caracteres.',
            'description.max' => 'La description ne doit pas depasser 3000 caracteres.',
        ];
    }
}

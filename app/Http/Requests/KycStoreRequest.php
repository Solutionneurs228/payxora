<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'nationality' => ['required', 'string', 'max:100'],
            'document_type' => ['required', 'string', 'max:100'],
            'document_number' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],

            'document_front' => ['required', 'image', 'max:4096'],
            'document_back' => ['nullable', 'image', 'max:4096'],
            'selfie' => ['required', 'image', 'max:4096'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\d{8,15}$/', 'unique:users,phone,' . $userId],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Le numero de telephone doit contenir entre 8 et 15 chiffres.',
        ];
    }
}

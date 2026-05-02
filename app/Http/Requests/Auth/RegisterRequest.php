<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone', 'regex:/^\+?[0-9\s\-]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()->min(8)->mixedCase()->numbers()],
            'terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom complet est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caracteres.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est deja utilisee.',
            'phone.required' => 'Le numero de telephone est obligatoire.',
            'phone.unique' => 'Ce numero de telephone est deja utilise.',
            'phone.regex' => 'Veuillez entrer un numero de telephone valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ];
    }
}

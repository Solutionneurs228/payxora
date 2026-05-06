<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'id_type' => ['required', 'string', 'in:cni,passport,driver_license,residence'],
            'id_number' => ['required', 'string', 'max:50', 'min:3'],
            'id_front' => ['required', 'file', 'image', 'max:5120'], // 5MB max
            'id_back' => ['nullable', 'file', 'image', 'max:5120'],
            'selfie' => ['required', 'file', 'image', 'max:5120'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_type.required' => 'Le type de piece d\'identite est obligatoire.',
            'id_type.in' => 'Le type de piece doit etre CNI, Passeport, Permis ou Carte de residence.',
            'id_number.required' => 'Le numero de la piece est obligatoire.',
            'id_front.required' => 'La photo recto de la piece est obligatoire.',
            'id_front.image' => 'Le fichier doit etre une image.',
            'id_front.max' => 'L\'image ne doit pas depasser 5 Mo.',
            'selfie.required' => 'Le selfie avec la piece est obligatoire.',
            'selfie.image' => 'Le fichier doit etre une image.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
        ];
    }
}

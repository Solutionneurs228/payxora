<?php

namespace App\Http\Requests\Kyc;

use Illuminate\Foundation\Http\FormRequest;

class SubmitKycRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_type' => ['required', 'string', 'in:passport,cni,driving_license'],
            'id_number' => ['required', 'string', 'max:50'],
            'id_front' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'id_back' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'selfie' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100', 'in:Togo'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_type.required' => 'Le type de piece d\'identite est obligatoire.',
            'id_type.in' => 'Le type de piece d\'identite selectionne n\'est pas valide.',
            'id_number.required' => 'Le numero de piece d\'identite est obligatoire.',
            'id_front.required' => 'La photo recto de la piece d\'identite est obligatoire.',
            'id_front.image' => 'Le fichier doit etre une image.',
            'id_front.max' => 'L\'image ne doit pas depasser 2 Mo.',
            'selfie.required' => 'La photo selfie est obligatoire.',
            'selfie.image' => 'Le fichier doit etre une image.',
            'address.required' => 'L\'adresse est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'country.required' => 'Le pays est obligatoire.',
        ];
    }
}

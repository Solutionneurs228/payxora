<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class KycRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_type' => ['required', 'string', 'in:cni,passport,residence'],
            'id_number' => ['required', 'string', 'max:50'],
            'id_photo' => ['required', File::image()->max(5 * 1024)], // 5MB max
            'selfie' => ['required', File::image()->max(5 * 1024)],
        ];
    }

    public function messages(): array
    {
        return [
            'id_type.required' => 'Le type de pièce d\'identité est requis.',
            'id_type.in' => 'Le type de pièce d\'identité doit être CNI, Passeport ou Carte de résidence.',
            'id_number.required' => 'Le numéro de pièce d\'identité est requis.',
            'id_photo.required' => 'La photo de la pièce d\'identité est requise.',
            'id_photo.image' => 'Le fichier doit être une image.',
            'id_photo.max' => 'L\'image ne doit pas dépasser 5 Mo.',
            'selfie.required' => 'La photo selfie est requise.',
            'selfie.image' => 'Le fichier doit être une image.',
            'selfie.max' => 'L\'image ne doit pas dépasser 5 Mo.',
        ];
    }
}

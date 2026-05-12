<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^(\+228)?([ -]?[0-9]{2}){4}$/',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],

            'role' => [
                'required',
                'in:buyer,seller',
            ],

            'terms' => [
                'required',
                'accepted',
            ],

        ], [
            'phone.regex' => 'Numéro invalide.',
        ]);

        // Nettoyage du numéro
        $phone = preg_replace('/[\s-]/', '', $validated['phone']);

        // Ajouter +228 si absent
        if (! str_starts_with($phone, '+228')) {
            $phone = '+228'.$phone;
        }

        // Vérification unicité après normalisation
        if (User::where('phone', $phone)->exists()) {

            return back()
                ->withErrors([
                    'phone' => 'Ce numéro existe déjà.',
                ])
                ->withInput();
        }

        // Création utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phone,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'kyc_status' => 'pending',
        ]);

        // Connexion automatique
        Auth::login($user);

        // Email bienvenue
        BrevoService::sendWelcomeEmail($user);

        return redirect()
            ->route('kyc.show')
            ->with(
                'success',
                'Bienvenue ! Complétez votre vérification pour continuer.'
            );
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Authentifie l'utilisateur.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('Ces identifiants ne correspondent pas a nos enregistrements.'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirection selon le statut KYC
        if (!$user->kycProfile || $user->kycProfile->status !== 'approved') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez completer votre verification d\'identite pour acceder a toutes les fonctionnalites.');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Affiche la page mot de passe oublie.
     */
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoie le lien de reinitialisation.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Logique d'envoi d'email via BrevoService - Phase 3

        return back()->with('status', 'Un lien de reinitialisation a ete envoye a votre adresse email.');
    }
}

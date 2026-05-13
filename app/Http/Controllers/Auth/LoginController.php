<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ]);

        $remember = $credentials['remember'] ?? false;

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // REDIRECTION ADMIN
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // REDIRECTION KYC NON VERIFIE
            if (!$user->isKycVerified()) {
                return redirect()->route('kyc.show')
                    ->with('warning', 'Veuillez completer votre verification KYC.');
            }

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Ces identifiants ne correspondent pas a nos enregistrements.',
        ]);
    }

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        \App\Services\BrevoService::sendPasswordReset($request->email);
        return back()->with('status', 'Un lien de reinitialisation vous a ete envoye.');
    }

    public function showReset($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        // Logique de reinitialisation ici
        return redirect()->route('login')->with('status', 'Mot de passe reinitialise.');
    }
}

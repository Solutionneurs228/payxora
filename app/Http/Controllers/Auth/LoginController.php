<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
     * Traite la connexion avec protection brute force.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Rate limiting par IP + email
        $key = "login:{$request->ip()}:{$request->email}";

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives. Reessayez dans {$seconds} secondes.",
            ]);
        }

        $user = User::where('email', $request->email)->first();

        // Verifier si compte verrouille
        if ($user && $user->isLocked()) {
            $minutes = $user->locked_until->diffInMinutes(now()->subSecond());
            throw ValidationException::withMessages([
                'email' => "Compte temporairement verrouille. Reessayez dans {$minutes} minutes.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($key, 300); // 5 minutes

            if ($user) {
                $user->recordFailedLogin();
            }

            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent pas a nos enregistrements.',
            ]);
        }

        RateLimiter::clear($key);

        $user = Auth::user();
        $user->recordSuccessfulLogin($request->ip());

        $request->session()->regenerate();

        // Mode dev : auto-verification email si pas verifie
        if (app()->environment('local', 'testing') && !$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Verifier si email verifie
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Veuillez verifier votre email pour acceder a toutes les fonctionnalites.');
        }

        // Redirection selon le statut KYC
        if (!$user->isKycVerified()) {
            return redirect()->route('kyc')
                ->with('info', 'Veuillez completer votre verification d\'identite pour acceder a toutes les fonctionnalites.');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Affiche la page mot de passe oublie.
     */
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoie le lien de reinitialisation via Brevo.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Aucun compte trouve avec cet email.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Mode dev : affiche le token a l'ecran
        if (app()->environment('local', 'testing')) {
            $token = Str::random(64);
            \DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                ['email' => $request->email, 'token' => Hash::make($token), 'created_at' => now()]
            );

            $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
            return back()->with('status', "Mode dev - Lien de reset : {$resetUrl}");
        }

        $token = Str::random(64);
        \DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['email' => $request->email, 'token' => Hash::make($token), 'created_at' => now()]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
        BrevoService::sendPasswordReset($user, $resetUrl);

        return back()->with('status', 'Un lien de reinitialisation a ete envoye a votre adresse email.');
    }

    /**
     * Affiche le formulaire de reinitialisation.
     */
    public function showReset(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Traite la reinitialisation du mot de passe.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Votre mot de passe a ete reinitialise.');
        }

        return back()->withErrors(['email' => 'Le lien de reinitialisation est invalide ou a expire.']);
    }
}

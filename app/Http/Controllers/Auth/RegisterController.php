<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KycProfile;
use App\Enums\UserRole;
use App\Enums\KycStatus;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{8,15}$/', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'role' => ['required', 'in:buyer,seller'],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est deja utilise.',
            'phone.required' => 'Le numero de telephone est obligatoire.',
            'phone.regex' => 'Le numero de telephone est invalide.',
            'phone.unique' => 'Ce numero est deja utilise.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caracteres.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role === 'seller' ? UserRole::SELLER : UserRole::BUYER,
            'is_active' => true,
        ]);

        // Creer un profil KYC vide
        KycProfile::create([
            'user_id' => $user->id,
            'status' => KycStatus::NOT_SUBMITTED,
        ]);

        event(new Registered($user));

        // Mode dev : auto-verification sans email
        if (app()->environment('local', 'testing')) {
            $user->markEmailAsVerified();
            Auth::login($user);

            return redirect()->route('kyc')
                ->with('success', 'Compte cree et email verifie automatiquement (mode dev). Veuillez completer votre KYC.');
        }

        // Mode production : envoi email via Brevo
        BrevoService::sendWelcomeEmail($user);
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Compte cree ! Verifiez votre email pour activer votre compte.');
    }

    /**
     * Affiche la page de verification email.
     */
    public function showVerify()
    {
        return view('auth.verify-email');
    }

    /**
     * Verifie l'email.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Lien de verification invalide.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Email verifie ! Vous pouvez maintenant utiliser PayXora.');
    }

    /**
     * Renvoie l'email de verification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // Mode dev : auto-verify
        if (app()->environment('local', 'testing')) {
            $request->user()->markEmailAsVerified();
            return redirect()->route('dashboard')
                ->with('success', 'Email verifie automatiquement (mode dev).');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Un nouveau lien de verification a ete envoye.');
    }
}

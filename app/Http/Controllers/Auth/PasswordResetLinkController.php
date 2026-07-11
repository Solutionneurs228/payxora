<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\BrevoService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Génère un token
            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );

            // Envoie via Brevo
            $link = url(route('password.reset', ['token' => $token, 'email' => $request->email], false));
            
            $sent = BrevoService::sendPasswordReset($request->email, $link);

            Log::info('Reset password email', [
                'email' => $request->email,
                'sent' => $sent,
            ]);

            return $sent
                ? back()->with('status', 'Un lien de reinitialisation a ete envoye a votre adresse email.')
                : back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Une erreur est survenue lors de l\'envoi. Veuillez reessayer.']);

        } catch (\Throwable $e) {
            Log::error('Erreur reset password', [
                'message' => $e->getMessage(),
            ]);
            return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Une erreur est survenue. Veuillez reessayer plus tard.']);
        }
    }
}
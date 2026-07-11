<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Log::info('Reset password store started', [
            'email' => $request->email,
            'token_length' => strlen($request->token),
        ]);

        // 1. Cherche le token dans la table
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            Log::warning('Reset password: no token found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Aucune demande de reinitialisation trouvee pour cet email.']);
        }

        Log::info('Reset password: token record found', [
            'email' => $request->email,
            'token_hash_prefix' => substr($record->token, 0, 20),
            'created_at' => $record->created_at,
        ]);

        // 2. Vérifie le token (comparaison avec Hash::check)
        if (!Hash::check($request->token, $record->token)) {
            Log::warning('Reset password: token hash mismatch', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Ce lien de reinitialisation est invalide.']);
        }

        // 3. Vérifie expiration (60 minutes)
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if (now()->diffInMinutes($createdAt) > 60) {
            Log::warning('Reset password: token expired', [
                'email' => $request->email,
                'created_at' => $record->created_at,
                'diff_minutes' => now()->diffInMinutes($createdAt),
            ]);
            return back()->withErrors(['email' => 'Ce lien a expire. Veuillez en demander un nouveau.']);
        }

        // 4. Met à jour le mot de passe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Log::error('Reset password: user not found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Utilisateur introuvable.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        Log::info('Reset password: password updated successfully', ['email' => $request->email]);

        // 5. Supprime le token utilisé
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Log::info('Reset password: token deleted', ['email' => $request->email]);

        return redirect()->route('login')->with('status', 'Votre mot de passe a ete reinitialise avec succes. Vous pouvez maintenant vous connecter.');
    }
}
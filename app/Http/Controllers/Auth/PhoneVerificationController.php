<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PhoneVerificationController extends Controller
{
    /**
     * Envoie un code OTP au telephone.
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{8,15}$/'],
        ]);

        $user = Auth::user();
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put("phone_verification:{$user->id}", [
            'code' => $code,
            'phone' => $request->phone,
            'attempts' => 0,
        ], now()->addMinutes(10));

        // TODO: Integrer Twilio ou service SMS local pour envoyer le code
        // Pour le dev, on retourne le code dans la reponse
        if (app()->environment('local', 'testing')) {
            return back()->with('success', "Code de verification (dev): {$code}");
        }

        return back()->with('success', 'Un code de verification a ete envoye a votre telephone.');
    }

    /**
     * Verifie le code OTP.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();
        $cached = Cache::get("phone_verification:{$user->id}");

        if (!$cached) {
            return back()->with('error', 'Le code a expire. Veuillez en demander un nouveau.');
        }

        if ($cached['attempts'] >= 3) {
            Cache::forget("phone_verification:{$user->id}");
            return back()->with('error', 'Trop de tentatives. Veuillez en demander un nouveau.');
        }

        if ($cached['code'] !== $request->code) {
            $cached['attempts']++;
            Cache::put("phone_verification:{$user->id}", $cached, now()->addMinutes(10));
            return back()->with('error', 'Code invalide. ' . (3 - $cached['attempts']) . ' tentatives restantes.');
        }

        $user->update(['phone' => $cached['phone']]);
        Cache::forget("phone_verification:{$user->id}");

        return redirect()->route('dashboard')
            ->with('success', 'Telephone verifie avec succes.');
    }
}

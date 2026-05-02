<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * Cree un nouvel utilisateur.
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => UserRole::USER->value,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('kyc.show')
            ->with('success', 'Bienvenue sur PayXora ! Veuillez completer votre verification d\'identite.');
    }
}

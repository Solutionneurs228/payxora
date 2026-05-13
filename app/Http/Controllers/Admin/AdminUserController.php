<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('kyc')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('kyc');
        return view('admin.users.show', compact('user'));
    }

    public function validateKyc(Request $request, User $user)
    {
        if ($user->kyc) {
            $user->kyc->update([
                'status' => 'verified',
                'verified_at' => now(),
                'admin_notes' => $request->input('admin_notes', 'KYC approuve par admin'),
            ]);
        }

        $user->update(['kyc_status' => 'verified']);

        return back()->with('success', 'KYC valide avec succes pour ' . $user->name);
    }

    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Si le compte est actif → on le suspend
        if ($user->is_active) {
            $user->update(['is_active' => false]);

            // Mettre a jour le KYC avec le motif
            if ($user->kyc) {
                $user->kyc->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->input('reason', 'Compte suspendu par l\'administrateur'),
                ]);
            }
            $user->update(['kyc_status' => 'rejected']);

            return back()->with('warning', 'Utilisateur ' . $user->name . ' suspendu.');
        }
        // Si le compte est deja suspendu → on le reactive
        else {
            $user->update(['is_active' => true]);

            // Remettre le KYC en pending (a reverifier)
            if ($user->kyc) {
                $user->kyc->update([
                    'status' => 'pending',
                    'admin_notes' => 'Compte reactive. KYC a reverifier.',
                ]);
            }
            $user->update(['kyc_status' => 'pending']);

            return back()->with('success', 'Utilisateur ' . $user->name . ' reactive.');
        }
    }
}

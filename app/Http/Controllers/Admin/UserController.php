<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KycStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('kycProfile')
            ->latest()
            ->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['kycProfile', 'transactionsAsSeller', 'transactionsAsBuyer', 'activityLogs']);

        return view('admin.users.show', compact('user'));
    }

    public function validateKyc(Request $request, User $user)
    {
        if (!$user->kycProfile) {
            return back()->with('error', 'Cet utilisateur n'a pas soumis de documents KYC.');
        }

        $user->kycProfile->update([
            'status' => KycStatus::APPROVED,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', 'KYC valide pour ' . $user->name);
    }

    public function suspend(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'is_active' => false,
        ]);

        // TODO: Envoyer notification de suspension

        return back()->with('success', 'Utilisateur suspendu.');
    }
}

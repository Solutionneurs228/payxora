<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['transactionsAsSeller', 'transactionsAsBuyer', 'disputesOpened']);
        return view('admin.users.show', compact('user'));
    }

    public function validateKyc(Request $request, User $user)
    {
        $user->update(['kyc_status' => 'verified']);

        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'kyc_approved',
            'title' => 'KYC approuve',
            'message' => 'Votre verification d\'identite a ete approuvee.',
            'link' => route('dashboard'),
        ]);

        \App\Services\BrevoService::sendKycApproved($user);

        return back()->with('success', 'KYC valide.');
    }

    public function suspend(Request $request, User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'active' : 'suspendu';
        return back()->with('success', "Utilisateur {$status}.");
    }
}

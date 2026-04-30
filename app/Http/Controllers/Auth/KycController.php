<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function show()
    {
        if (Auth::user()->isKycVerified()) {
            return redirect()->route('dashboard');
        }
        return view('auth.kyc');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'id_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'profile_photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        if ($request->hasFile('id_document')) {
            $validated['id_document'] = $request->file('id_document')
                ->store('kyc/documents', 'private');
        }

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')
                ->store('kyc/photos', 'public');
        }

        $user->update([...$validated, 'kyc_status' => 'pending']);

        \App\Models\Notification::create([
            'user_id' => 1,
            'type' => 'kyc_pending',
            'title' => 'Nouvelle verification KYC',
            'message' => "{$user->full_name} a soumis ses documents KYC.",
            'link' => route('admin.users.show', $user),
        ]);

        return redirect()->route('kyc.verification')
            ->with('success', 'Documents soumis. Verification en cours.');
    }

    public function verification()
    {
        return view('auth.kyc-verification');
    }
}

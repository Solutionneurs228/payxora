<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KycController extends Controller
{
    public function show(): View
    {
        $kyc = auth()->user()->kyc;
        return view('auth.kyc', compact('kyc'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'nationality' => 'required|string|max:100',
            'document_type' => 'required|string|max:50',
            'document_number' => 'required|string|max:100',
            'address' => 'nullable|string',
            'document_front' => 'required|image|max:5120', // 5MB max
            'document_back' => 'nullable|image|max:5120',
            'selfie' => 'required|image|max:5120',
        ]);

        $user = auth()->user();

        // Supprimer anciens fichiers si existants
        if ($user->kyc) {
            if ($user->kyc->document_front && \Storage::disk('public')->exists($user->kyc->document_front)) {
                \Storage::disk('public')->delete($user->kyc->document_front);
            }
            if ($user->kyc->document_back && \Storage::disk('public')->exists($user->kyc->document_back)) {
                \Storage::disk('public')->delete($user->kyc->document_back);
            }
            if ($user->kyc->selfie && \Storage::disk('public')->exists($user->kyc->selfie)) {
                \Storage::disk('public')->delete($user->kyc->selfie);
            }
        }

        // Upload des nouveaux fichiers sur disk PUBLIC (accessible via web)
        $documentFront = $request->file('document_front')->store('kyc/' . $user->id, 'public');
        $documentBack = $request->file('document_back')
            ? $request->file('document_back')->store('kyc/' . $user->id, 'public')
            : null;
        $selfie = $request->file('selfie')->store('kyc/' . $user->id, 'public');

        $kyc = Kyc::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->full_name,
                'birth_date' => $request->birth_date,
                'nationality' => $request->nationality,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'address' => $request->address,
                'document_front' => $documentFront,
                'document_back' => $documentBack,
                'selfie' => $selfie,
                'status' => 'pending',
                'submitted_at' => now(),
            ]
        );

        // Mettre à jour le kyc_status de l'utilisateur
        $user->update(['kyc_status' => 'pending']);

        return redirect()
            ->route('kyc.show')
            ->with('success', 'Documents KYC soumis avec succes. En attente de verification.');
    }

    public function verification(): View
    {
        return view('auth.kyc-verification');
    }

    public function document(string $type, int $id)
    {
        $kyc = Kyc::findOrFail($id);

        $field = match($type) {
            'front' => 'document_front',
            'back' => 'document_back',
            'selfie' => 'selfie',
            default => abort(404),
        };

        $path = $kyc->$field;

        if (!$path || !\Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return \Storage::disk('public')->response($path);
    }
}

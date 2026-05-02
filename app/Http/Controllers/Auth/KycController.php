<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KycProfile;
use App\Services\KycService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    protected KycService $kycService;
    protected NotificationService $notificationService;

    public function __construct(KycService $kycService, NotificationService $notificationService)
    {
        $this->kycService = $kycService;
        $this->notificationService = $notificationService;
    }

    /**
     * Affiche le formulaire KYC.
     */
    public function show()
    {
        $kyc = Auth::user()->kycProfile;

        // Si deja approuve, rediriger vers le dashboard
        if ($kyc && $kyc->status === 'approved') {
            return redirect()->route('dashboard')
                ->with('success', 'Votre compte est deja verifie.');
        }

        return view('auth.kyc', compact('kyc'));
    }

    /**
     * Soumet les informations KYC avec upload de fichiers.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Supprimer l'ancien KYC si rejete
        if ($user->kycProfile && $user->kycProfile->status === 'rejected') {
            $this->deleteKycFiles($user->kycProfile);
            $user->kycProfile->delete();
        }

        // Stocker les fichiers
        $idFrontPath = $request->file('id_front')->store('kyc/' . $user->id . '/id_front', 'private');
        $idBackPath = $request->hasFile('id_back')
            ? $request->file('id_back')->store('kyc/' . $user->id . '/id_back', 'private')
            : null;
        $selfiePath = $request->file('selfie')->store('kyc/' . $user->id . '/selfie', 'private');

        // Creer le profil KYC
        $kyc = KycProfile::create([
            'user_id' => $user->id,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'id_front_path' => $idFrontPath,
            'id_back_path' => $idBackPath,
            'selfie_path' => $selfiePath,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country ?? 'Togo',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Notifier l'admin
        $this->notificationService->notifyAdmins(
            'Nouvelle verification KYC',
            'L\'utilisateur ' . $user->name . ' a soumis ses documents KYC.',
            route('admin.users.show', $user)
        );

        return redirect()->route('kyc.verification')
            ->with('success', 'Vos documents ont ete soumis et sont en cours de verification.');
    }

    /**
     * Page d'attente de verification.
     */
    public function verification()
    {
        $kyc = Auth::user()->kycProfile;

        if (!$kyc) {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez soumettre vos documents de verification.');
        }

        if ($kyc->status === 'approved') {
            return redirect()->route('dashboard')
                ->with('success', 'Votre compte est verifie ! Bienvenue sur PayXora.');
        }

        return view('auth.kyc-verification');
    }

    /**
     * Supprime les fichiers KYC.
     */
    private function deleteKycFiles(KycProfile $kyc): void
    {
        if ($kyc->id_front_path) {
            Storage::disk('private')->delete($kyc->id_front_path);
        }
        if ($kyc->id_back_path) {
            Storage::disk('private')->delete($kyc->id_back_path);
        }
        if ($kyc->selfie_path) {
            Storage::disk('private')->delete($kyc->selfie_path);
        }
    }
}

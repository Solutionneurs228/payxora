<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycRequest;
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

        if ($kyc && $kyc->isApproved()) {
            return redirect()->route('dashboard')
                ->with('success', 'Votre compte est deja verifie.');
        }

        return view('auth.kyc', compact('kyc'));
    }

    /**
     * Soumet les informations KYC avec upload de fichiers.
     */
    public function store(KycRequest $request)
    {
        $user = Auth::user();

        $kyc = $this->kycService->submit($user, $request->validated());

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

        if ($kyc->isApproved()) {
            return redirect()->route('dashboard')
                ->with('success', 'Votre compte est verifie ! Bienvenue sur PayXora.');
        }

        return view('auth.kyc-verification', compact('kyc'));
    }

    /**
     * Affiche un document KYC securise (admin ou proprietaire uniquement).
     */
    public function document(Request $request, string $type, int $id)
    {
        $kyc = KycProfile::findOrFail($id);
        $user = Auth::user();

        // Verifier les permissions
        if ($kyc->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Acces non autorise.');
        }

        $path = $this->kycService->getDocument($kyc, $type);

        if (!$path) {
            abort(404, 'Document non trouve.');
        }

        return response()->file($path);
    }
}

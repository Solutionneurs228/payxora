<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kyc\SubmitKycRequest;
use App\Services\KycService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    protected KycService $kycService;

    public function __construct(KycService $kycService)
    {
        $this->kycService = $kycService;
    }

    /**
     * Affiche le formulaire KYC.
     */
    public function show()
    {
        $kyc = Auth::user()->kycProfile;
        return view('auth.kyc', compact('kyc'));
    }

    /**
     * Soumet les informations KYC.
     */
    public function store(SubmitKycRequest $request)
    {
        $this->kycService->submitKyc(Auth::user(), $request->validated());

        return redirect()->route('kyc.verification')
            ->with('success', 'Vos documents ont été soumis et sont en cours de vérification.');
    }

    /**
     * Page d'attente de vérification KYC.
     */
    public function verification()
    {
        $kyc = Auth::user()->kycProfile;

        if (!$kyc || $kyc->status === 'pending') {
            return view('auth.kyc-verification');
        }

        if ($kyc->status === 'approved') {
            return redirect()->route('dashboard')
                ->with('success', 'Votre compte est vérifié ! Bienvenue sur PayXora.');
        }

        return view('auth.kyc-verification')->with('error', 
            'Votre vérification a été rejetée. Veuillez soumettre à nouveau vos documents.');
    }
}

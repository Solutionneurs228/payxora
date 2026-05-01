<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\KycRequest;
use App\Services\KycService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KycController extends Controller
{
    protected KycService $kycService;

    public function __construct(KycService $kycService)
    {
        $this->kycService = $kycService;
    }

    public function show(): View
    {
        $kycProfile = auth()->user()->kycProfile;
        return view('auth.kyc', compact('kycProfile'));
    }

    public function store(KycRequest $request): RedirectResponse
    {
        try {
            $this->kycService->submitKyc(
                auth()->user(),
                $request->only(['id_type', 'id_number']),
                $request->file('id_photo'),
                $request->file('selfie')
            );

            return redirect()->route('kyc.pending')
                ->with('success', 'Vos documents ont été soumis avec succès. Ils seront examinés sous peu.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi des documents. Veuillez réessayer.');
        }
    }

    public function pending(): View
    {
        return view('auth.kyc-verification');
    }
}

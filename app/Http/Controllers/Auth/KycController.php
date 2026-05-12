<?php

namespace App\Http\Controllers\Auth;

use App\Enums\KycStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\KycStoreRequest;
use App\Models\Kyc;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KycController extends Controller
{
    public function show(): View
    {
        $kyc = auth()->user()->kyc;

        return view('auth.kyc', compact('kyc'));
    }

    public function store(KycStoreRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $kyc = Kyc::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->full_name,
                'birth_date' => $request->birth_date,
                'nationality' => $request->nationality,
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'address' => $request->address,
                'document_front' => $request->file('document_front')->store('kyc', 'local'),
                'document_back' => $request->file('document_back')
                    ? $request->file('document_back')->store('kyc', 'local')
                    : null,
                'selfie' => $request->file('selfie')->store('kyc', 'local'),
                'status' => KycStatus::PENDING,
            ]
        );

        return redirect()
            ->route('kyc')
            ->with('success', 'KYC soumis avec succès.');
    }
}

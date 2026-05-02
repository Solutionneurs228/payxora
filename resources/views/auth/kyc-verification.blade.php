@extends('layouts.app')

@section('title', 'KYC en cours — PayXora')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        @if($kyc->status->value === 'pending')
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Verification en cours</h1>
            <p class="text-gray-500 mb-6">Vos documents sont en cours d'examen par notre equipe. Vous recevrez une notification des que votre compte sera valide.</p>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-yellow-700 text-sm">Delai moyen : 24 heures ouvrables</p>
            </div>
        @elseif($kyc->status->value === 'approved')
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Compte verifie !</h1>
            <p class="text-gray-500 mb-6">Votre identite a ete confirmee. Vous pouvez maintenant effectuer des transactions.</p>
            <a href="{{ route('dashboard') }}" class="inline-block bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                Aller au tableau de bord
            </a>
        @elseif($kyc->status->value === 'rejected')
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Verification refusee</h1>
            <p class="text-gray-500 mb-6">{{ $kyc->rejection_reason ?? 'Vos documents n'ont pas pu etre verifies. Veuillez soumettre de nouveaux documents clairs et valides.' }}</p>
            <a href="{{ route('kyc.show') }}" class="inline-block bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                Reessayer
            </a>
        @endif
    </div>
</div>
@endsection

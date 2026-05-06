@extends('layouts.app')

@section('title', 'Verification en cours - PayXora')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow rounded-lg p-8 text-center">
            @if ($kyc->isPending())
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verification en cours</h2>
                <p class="text-gray-600 mb-6">
                    Vos documents sont en cours d'examen par notre equipe. Cela peut prendre jusqu'a 24 heures ouvrables.
                </p>
                <div class="bg-yellow-50 rounded-md p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <strong>Statut :</strong> En attente de validation
                    </p>
                    <p class="text-sm text-yellow-700 mt-1">
                        <strong>Soumis le :</strong> {{ $kyc->submitted_at->format('d/m/Y a H:i') }}
                    </p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                    Retour au tableau de bord
                </a>

            @elseif ($kyc->isRejected())
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verification refusee</h2>
                <p class="text-gray-600 mb-4">
                    Votre verification a ete refusee pour la raison suivante :
                </p>
                <div class="bg-red-50 rounded-md p-4 mb-6">
                    <p class="text-sm text-red-800">{{ $kyc->rejection_reason }}</p>
                </div>
                <a href="{{ route('kyc.show') }}" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Soumettre a nouveau
                </a>

            @else
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Compte verifie !</h2>
                <p class="text-gray-600 mb-6">
                    Votre identite a ete verifiee avec succes. Vous pouvez maintenant utiliser toutes les fonctionnalites de PayXora.
                </p>
                <a href="{{ route('dashboard') }}" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Acceder au tableau de bord
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

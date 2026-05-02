@extends('layouts.app')

@section('title', 'Verification en cours - PayXora')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            @php
                $kyc = Auth::user()->kycProfile;
                $status = $kyc ? $kyc->status : 'pending';
            @endphp

            @if($status === 'pending')
                <div class="mx-auto h-24 w-24 bg-yellow-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <svg class="h-12 w-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Verification en cours</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Vos documents ont ete soumis et sont en cours d'examen par notre equipe.
                </p>
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Temps estimé :</strong> Moins de 24 heures ouvrables.<br>
                        Vous recevrez une notification par email des que votre compte sera verifie.
                    </p>
                </div>
                <div class="mt-8">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-emerald-600 hover:text-emerald-500 font-medium">
                            Se deconnecter et revenir plus tard
                        </button>
                    </form>
                </div>
            @elseif($status === 'approved')
                <div class="mx-auto h-24 w-24 bg-emerald-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Compte verifie !</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Votre identite a ete confirmee. Vous pouvez maintenant utiliser toutes les fonctionnalites de PayXora.
                </p>
                <div class="mt-8">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        Acceder au tableau de bord
                    </a>
                </div>
            @elseif($status === 'rejected')
                <div class="mx-auto h-24 w-24 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Verification rejetee</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Vos documents n'ont pas pu etre verifies. Veuillez soumettre de nouveaux documents clairs et lisibles.
                </p>
                @if($kyc && $kyc->rejection_reason)
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-800">
                            <strong>Raison :</strong> {{ $kyc->rejection_reason }}
                        </p>
                    </div>
                @endif
                <div class="mt-8">
                    <a href="{{ route('kyc.show') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        Soumettre de nouveaux documents
                    </a>
                </div>
            @else
                <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Verification requise</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Veuillez completer votre verification d'identite pour acceder a toutes les fonctionnalites.
                </p>
                <div class="mt-8">
                    <a href="{{ route('kyc.show') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        Commencer la verification
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

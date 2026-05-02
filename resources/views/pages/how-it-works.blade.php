@extends('layouts.app')

@section('title', 'Comment ca marche — PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900">Comment ca marche</h1>
        <p class="text-gray-500 mt-3">Le mecanisme escrow explique en 5 etapes simples</p>
    </div>

    <div class="space-y-12">
        <!-- Step 1 -->
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-full md:w-1/2">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold text-indigo-600">1</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Inscription et verification KYC</h2>
                <p class="text-gray-600">Creez votre compte en quelques minutes. Verifiez votre identite avec un document officiel pour securiser la plateforme.</p>
            </div>
            <div class="w-full md:w-1/2 bg-gray-100 rounded-xl p-8 flex items-center justify-center">
                <svg class="w-32 h-32 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="flex flex-col md:flex-row-reverse items-center gap-8">
            <div class="w-full md:w-1/2">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold text-indigo-600">2</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Creation de la transaction</h2>
                <p class="text-gray-600">Le vendeur decrit le produit, fixe le prix et definit les conditions. Un lien unique est genere pour l'acheteur.</p>
            </div>
            <div class="w-full md:w-1/2 bg-gray-100 rounded-xl p-8 flex items-center justify-center">
                <svg class="w-32 h-32 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-full md:w-1/2">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold text-indigo-600">3</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Paiement et blocage des fonds</h2>
                <p class="text-gray-600">L'acheteur paie via Mobile Money (TMoney ou Moov). L'argent est immediatement bloque sur un compte sequestre. Ni le vendeur ni l'acheteur ne peut y toucher.</p>
            </div>
            <div class="w-full md:w-1/2 bg-gray-100 rounded-xl p-8 flex items-center justify-center">
                <svg class="w-32 h-32 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="flex flex-col md:flex-row-reverse items-center gap-8">
            <div class="w-full md:w-1/2">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold text-indigo-600">4</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Livraison et verification</h2>
                <p class="text-gray-600">Le vendeur expedie le produit. L'acheteur le recoit et l'inspecte. Il dispose de 48 heures pour confirmer la reception ou ouvrir un litige.</p>
            </div>
            <div class="w-full md:w-1/2 bg-gray-100 rounded-xl p-8 flex items-center justify-center">
                <svg class="w-32 h-32 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-full md:w-1/2">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold text-indigo-600">5</span>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Liberation des fonds</h2>
                <p class="text-gray-600">Apres confirmation de l'acheteur, les fonds sont automatiquement liberes au vendeur (moins la commission PayXora de 3%). La transaction est terminee !</p>
            </div>
            <div class="w-full md:w-1/2 bg-gray-100 rounded-xl p-8 flex items-center justify-center">
                <svg class="w-32 h-32 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="mt-16 text-center">
        <a href="{{ route('register') }}" class="inline-block bg-indigo-600 text-white font-bold px-10 py-4 rounded-xl hover:bg-indigo-700 transition shadow-xl text-lg">
            Essayer maintenant
        </a>
    </div>
</div>
@endsection

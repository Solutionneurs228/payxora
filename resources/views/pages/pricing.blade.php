@extends('layouts.app')

@section('title', 'Tarifs — PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900">Nos tarifs</h1>
        <p class="text-gray-500 mt-3">Simple, transparent et sans frais caches</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
        <!-- Standard -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-bold text-gray-900">Standard</h2>
            <p class="text-gray-500 mt-1">Pour les particuliers</p>
            <div class="mt-6">
                <span class="text-4xl font-bold text-gray-900">3%</span>
                <span class="text-gray-500"> par transaction</span>
            </div>
            <ul class="mt-6 space-y-3">
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Paiement securise par escrow
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mobile Money (TMoney / Moov)
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mediation en cas de litige
                </li>
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Support par email
                </li>
            </ul>
            <a href="{{ route('register') }}" class="block w-full text-center bg-indigo-600 text-white font-semibold py-3 rounded-lg mt-8 hover:bg-indigo-700 transition">
                Commencer
            </a>
        </div>

        <!-- Info -->
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Commission minimum</h3>
                <p class="text-gray-600 text-sm">100 FCFA par transaction, meme pour les petits montants.</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Commission maximum</h3>
                <p class="text-gray-600 text-sm">Plafonnee a 50 000 FCFA, meme pour les grosses transactions.</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 mb-2">Frais de retrait</h3>
                <p class="text-gray-600 text-sm">500 FCFA par retrait vers votre compte Mobile Money.</p>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <h3 class="font-semibold text-yellow-800 mb-2">Exemple</h3>
                <p class="text-sm text-yellow-700">Pour une transaction de 50 000 FCFA :</p>
                <ul class="mt-2 text-sm text-yellow-700 space-y-1">
                    <li>Montant total : 50 000 FCFA</li>
                    <li>Commission (3%) : 1 500 FCFA</li>
                    <li>Net pour le vendeur : 48 500 FCFA</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

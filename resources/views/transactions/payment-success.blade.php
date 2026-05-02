@extends('layouts.app')

@section('title', 'Paiement confirme — PayXora')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement confirme !</h1>
        <p class="text-gray-500 mb-6">Votre paiement de {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA a ete effectue avec succes. Les fonds sont maintenant en sequestre.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-500">Reference</span>
                <span class="font-mono font-medium">{{ $transaction->reference }}</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-500">Produit</span>
                <span class="font-medium">{{ $transaction->product_name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Statut</span>
                <span class="font-medium text-indigo-600">En sequestre</span>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('transactions.show', $transaction) }}" class="block w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition">
                Voir la transaction
            </a>
            <a href="{{ route('dashboard') }}" class="block w-full text-gray-500 hover:text-gray-700 py-3 transition">
                Retour au tableau de bord
            </a>
        </div>
    </div>
</div>
@endsection

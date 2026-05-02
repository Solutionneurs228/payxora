@extends('layouts.app')

@section('title', 'Paiement echoue — PayXora')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement non effectue</h1>
        <p class="text-gray-500 mb-6">Le paiement n'a pas pu etre traite. Veuillez verifier votre solde et reessayer.</p>

        <div class="space-y-3">
            <a href="{{ route('payment.show', $transaction) }}" class="block w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition">
                Reessayer le paiement
            </a>
            <a href="{{ route('transactions.show', $transaction) }}" class="block w-full text-gray-500 hover:text-gray-700 py-3 transition">
                Retour a la transaction
            </a>
        </div>
    </div>
</div>
@endsection

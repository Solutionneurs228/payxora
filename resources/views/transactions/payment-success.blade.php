@extends('layouts.app')

@section('title', 'Paiement reussi')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 mx-auto bg-emerald-100 rounded-full flex items-center justify-center mb-6 animate-bounce">
            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 mb-3">Paiement reussi !</h1>
        <p class="text-slate-500 mb-2">Votre paiement a ete effectue avec succes.</p>
        <p class="text-slate-500 mb-8">Les fonds sont maintenant bloques en securite. Le vendeur va expedier votre commande.</p>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-8 text-left">
            <div class="flex justify-between mb-3">
                <span class="text-slate-500">Transaction</span>
                <span class="font-mono text-slate-700">{{ $transaction->reference }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-slate-500">Montant</span>
                <span class="font-bold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between pt-3 border-t border-slate-100">
                <span class="text-slate-500">Statut</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Paye - En sequestre</span>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('transactions.show', $transaction) }}" class="block w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                Voir ma transaction
            </a>
            <a href="{{ route('dashboard') }}" class="block w-full py-3.5 bg-white border-2 border-slate-200 hover:border-emerald-300 text-slate-700 font-semibold rounded-xl transition-all">
                Retour au tableau de bord
            </a>
        </div>
    </div>
</section>
@endsection

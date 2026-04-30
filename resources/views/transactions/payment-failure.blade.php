@extends('layouts.app')

@section('title', 'Paiement echoue')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 mb-3">Paiement echoue</h1>
        <p class="text-slate-500 mb-8">Une erreur s'est produite lors du traitement de votre paiement. Veuillez reessayer.</p>

        <div class="space-y-3">
            <a href="{{ route('payment.show', $transaction) }}" class="block w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                Reessayer le paiement
            </a>
            <a href="{{ route('transactions.show', $transaction) }}" class="block w-full py-3.5 bg-white border-2 border-slate-200 hover:border-emerald-300 text-slate-700 font-semibold rounded-xl transition-all">
                Retour a la transaction
            </a>
        </div>
    </div>
</section>
@endsection

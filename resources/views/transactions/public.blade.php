@extends('layouts.guest')

@section('title', 'Transaction #' . $transaction->reference)

@section('content')
<section class="py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Transaction securisee</h1>
            <p class="text-slate-500 mt-2">Ref: {{ $transaction->reference }}</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                        En attente d'acheteur
                    </span>
                    <span class="text-sm text-slate-400">{{ $transaction->created_at->diffForHumans() }}</span>
                </div>

                <h2 class="text-xl font-bold text-slate-900">{{ $transaction->title }}</h2>
                <p class="text-slate-600 mt-2">{{ $transaction->description ?? 'Aucune description' }}</p>
            </div>

            <div class="p-6 bg-slate-50">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-slate-600">Prix</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Commission (3%)</span>
                    <span class="text-slate-600">{{ number_format($transaction->commission_amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-2 pt-2 border-t border-slate-200">
                    <span class="text-slate-500">Net pour le vendeur</span>
                    <span class="font-medium text-emerald-600">{{ number_format($transaction->net_amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <div class="p-6">
                <div class="flex items-center gap-3 mb-6 p-4 bg-blue-50 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-blue-700">
                        L'argent sera bloque sur un compte sequestre jusqu'a confirmation de livraison.
                    </p>
                </div>

                @auth
                    @if($transaction->seller_id === Auth::id())
                        <div class="text-center p-4 bg-amber-50 rounded-xl">
                            <p class="text-amber-700 text-sm">Vous etes le vendeur de cette transaction.</p>
                            <a href="{{ route('transactions.show', $transaction) }}" class="mt-2 inline-block text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                                Voir mon tableau de bord &rarr;
                            </a>
                        </div>
                    @else
                        <!-- FORMULAIRE POST (pas un lien GET) -->
                        <form method="POST" action="{{ route('transactions.claim', $transaction->reference) }}">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Acheter maintenant — Paiement securise
                            </button>
                        </form>
                    @endif
                @else
                    <div class="space-y-3">
                        <a href="{{ route('register', ['redirect' => route('transactions.public', $transaction->reference)]) }}" class="block w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all text-center">
                            Creer un compte pour acheter
                        </a>
                        <p class="text-center text-sm text-slate-500">
                            Deja un compte ?
                            <a href="{{ route('login', ['redirect' => route('transactions.public', $transaction->reference)]) }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                Se connecter
                            </a>
                        </p>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Trust badges -->
        <div class="grid grid-cols-3 gap-4 mt-8">
            <div class="text-center p-4">
                <svg class="w-8 h-8 mx-auto text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <p class="text-xs text-slate-600">Paiement securise</p>
            </div>
            <div class="text-center p-4">
                <svg class="w-8 h-8 mx-auto text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-slate-600">Livraison sous 48h</p>
            </div>
            <div class="text-center p-4">
                <svg class="w-8 h-8 mx-auto text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <p class="text-xs text-slate-600">Remboursement garanti</p>
            </div>
        </div>
    </div>
</section>
@endsection
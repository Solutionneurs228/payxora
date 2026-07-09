@extends('layouts.app')

@section('title', 'Transaction ' . $transaction->reference . ' — PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('transactions.index') }}" class="text-sm text-slate-500 hover:text-emerald-600 transition">&larr; Retour aux transactions</a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ $transaction->title }}</h1>
                    <p class="text-sm text-slate-500 font-mono">{{ $transaction->reference }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $transaction->getStatusColor() }}">
                    {{ $transaction->getStatusLabel() }}
                </span>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Montant</p>
                    <p class="text-xl font-bold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Commission</p>
                    <p class="text-xl font-bold text-slate-700">{{ number_format($transaction->commission_amount, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Net</p>
                    <p class="text-xl font-bold text-emerald-600">{{ number_format($transaction->net_amount, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-500">Vendeur</p>
                    <p class="font-medium text-slate-900">{{ $transaction->seller->name }} <span class="text-slate-400">({{ $transaction->seller->email }})</span></p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Acheteur</p>
                    <p class="font-medium text-slate-900">
                        @if($transaction->buyer)
                            {{ $transaction->buyer->name }} <span class="text-slate-400">({{ $transaction->buyer->email }})</span>
                        @else
                            <span class="text-amber-600">En attente d'acheteur</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($transaction->description)
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-sm text-slate-500 mb-1">Description</p>
                <p class="text-slate-700">{{ $transaction->description }}</p>
            </div>
            @endif

            <!-- Lien de partage pour le vendeur (DRAFT) -->
            @if($transaction->seller_id === Auth::id() && $transaction->isDraft())
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <p class="text-sm text-blue-700 font-medium mb-2">Partager avec l'acheteur</p>
                <div class="flex gap-2">
                    <input type="text" value="{{ route('transactions.public', $transaction->reference) }}"
                           readonly class="flex-1 px-3 py-2 text-sm bg-white border border-blue-200 rounded-lg text-slate-600">
                    <button onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.textContent='Copie !'; setTimeout(() => this.textContent='Copier', 2000)"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        Copier
                    </button>
                </div>
                <form method="POST" action="{{ route('transactions.publish', $transaction) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition text-sm">
                        Publier la transaction (rendre visible)
                    </button>
                </form>
            </div>
            @endif

            <!-- Lien de partage pour le vendeur (PENDING_PAYMENT) -->
            @if($transaction->seller_id === Auth::id() && $transaction->isPendingPayment())
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <p class="text-sm text-blue-700 font-medium mb-2">En attente de paiement</p>
                <p class="text-xs text-blue-600">Partagez ce lien avec l'acheteur :</p>
                <div class="flex gap-2 mt-1">
                    <input type="text" value="{{ route('transactions.public', $transaction->reference) }}"
                           readonly class="flex-1 px-3 py-2 text-sm bg-white border border-blue-200 rounded-lg text-slate-600">
                    <button onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.textContent='Copie !'; setTimeout(() => this.textContent='Copier', 2000)"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        Copier
                    </button>
                </div>
            </div>
            @endif

            <!-- Payer (acheteur) -->
            @if($transaction->isPendingPayment() && $transaction->buyer_id === Auth::id())
            <div class="border-t border-slate-100 pt-6">
                <a href="{{ route('transactions.pay', $transaction) }}" class="block w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-center transition">
                    Payer maintenant
                </a>
            </div>
            @endif

            <!-- Expedier (vendeur) -->
            @if($transaction->isFunded() && $transaction->seller_id === Auth::id())
            <div class="border-t border-slate-100 pt-6">
                <form method="POST" action="{{ route('transactions.ship', $transaction) }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">
                        Marquer comme expedie
                    </button>
                </form>
            </div>
            @endif

            <!-- Confirmer reception (acheteur) -->
            @if($transaction->isShipped() && $transaction->buyer_id === Auth::id())
            <div class="border-t border-slate-100 pt-6">
                <form method="POST" action="{{ route('transactions.complete', $transaction) }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">
                        Confirmer la reception
                    </button>
                </form>
            </div>
            @endif

            <!-- Annuler -->
            @if($transaction->canBeCancelled())
            <div class="border-t border-slate-100 pt-6">
                <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" onsubmit="return confirm('Annuler cette transaction ?')">
                    @csrf
                    <button type="submit" class="w-full py-3 border-2 border-red-200 text-red-600 hover:bg-red-50 font-bold rounded-xl transition">
                        Annuler la transaction
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

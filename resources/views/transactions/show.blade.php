@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->reference . ' — PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $transaction->title }}</h1>
                <x-status-badge :status="$transaction->status->value" />
            </div>
            <p class="text-sm text-gray-500 mt-1">Référence: <span class="font-mono">{{ $transaction->reference }}</span></p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">
            &larr; Retour aux transactions
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Détails principaux -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info générale -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Montant</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Commission</p>
                        <p class="font-medium text-gray-700">{{ number_format($transaction->commission_amount, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Net pour le vendeur</p>
                        <p class="font-semibold text-indigo-600">{{ number_format($transaction->net_amount, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Devise</p>
                        <p class="font-medium text-gray-700">{{ $transaction->currency }}</p>
                    </div>
                </div>

                @if($transaction->description)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-gray-500 text-sm">Description</p>
                    <p class="text-gray-700 mt-1">{{ $transaction->description }}</p>
                </div>
                @endif

                @if($transaction->shipping_address)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-gray-500 text-sm">Adresse de livraison</p>
                    <p class="text-gray-700 mt-1">{{ $transaction->shipping_address }}</p>
                </div>
                @endif

                @if($transaction->seller_notes)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-gray-500 text-sm">Notes du vendeur</p>
                    <p class="text-gray-700 mt-1">{{ $transaction->seller_notes }}</p>
                </div>
                @endif
            </div>

            <!-- LIEN DE PARTAGE (vendeur uniquement, DRAFT ou PENDING_PAYMENT) -->
            @php
                $isSeller = $transaction->seller_id === auth()->id();
                $isBuyer = $transaction->buyer_id === auth()->id();
                $isDraft = $transaction->status->value === 'draft';
                $isPendingPayment = $transaction->status->value === 'pending_payment';
                $isFunded = $transaction->status->value === 'funded';
                $isShipped = $transaction->status->value === 'shipped';
                $isDelivered = $transaction->status->value === 'delivered';
                $isCompleted = $transaction->status->value === 'completed';
            @endphp

            @if($isSeller && ($isDraft || $isPendingPayment))
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <h3 class="font-semibold text-blue-900 mb-3">Partager avec l'acheteur</h3>

                <div class="flex gap-2 mb-3">
                    <input type="text" value="{{ route('transactions.public', $transaction->reference) }}" 
                           readonly class="flex-1 px-3 py-2 text-sm bg-white border border-blue-200 rounded-lg text-slate-600">
                    <button onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.textContent='Copié !'; setTimeout(() => this.textContent='Copier', 2000)" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                        Copier
                    </button>
                </div>

                @if($isDraft)
                <form method="POST" action="{{ route('transactions.publish', $transaction) }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition text-sm">
                        Publier la transaction (rendre visible)
                    </button>
                </form>
                @else
                <p class="text-xs text-blue-600 mt-2">En attente de paiement de l'acheteur.</p>
                @endif
            </div>
            @endif

            <!-- Historique -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Historique</h2>
                <div class="space-y-4">
                    @forelse($transaction->logs as $log)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($log->action) }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Aucun historique disponible</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar actions -->
        <div class="space-y-4">
            <!-- Participants -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Participants</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                            {{ substr($transaction->seller->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $transaction->seller->name }}</p>
                            <p class="text-xs text-gray-500">Vendeur</p>
                        </div>
                    </div>
                    @if($transaction->buyer)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-xs">
                            {{ substr($transaction->buyer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $transaction->buyer->name }}</p>
                            <p class="text-xs text-gray-500">Acheteur</p>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">En attente d'acheteur</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ========================================== -->
            <!-- ACTIONS PAR RÔLE ET STATUT               -->
            <!-- ========================================== -->

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="font-semibold text-gray-900 mb-3">Actions</h3>

                <!-- ── ACHETEUR : Payer ── -->
                @if($isPendingPayment && !$transaction->buyer_id && !$isSeller)
                    <form method="POST" action="{{ route('transactions.pay', $transaction) }}">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition">
                            💳 Payer maintenant
                        </button>
                    </form>
                @endif

                <!-- ── VENDEUR : Marquer comme expédié ── -->
                @if($isFunded && $isSeller)
                    <form method="POST" action="{{ route('transactions.ship', $transaction) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs text-gray-500 mb-1">Méthode d'expédition *</label>
                            <select name="shipping_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Choisir...</option>
                                <option value="pickup">Retrait en point relais</option>
                                <option value="delivery">Livraison à domicile</option>
                                <option value="in_person">Remise en main propre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="tracking_number" placeholder="Numéro de suivi (optionnel)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition">
                            📦 Marquer comme expédié
                        </button>
                    </form>
                @endif

                <!-- ── ACHETEUR : Confirmer réception (J'ai reçu) ── -->
                @if($isShipped && $isBuyer)
                    <form method="POST" action="{{ route('transactions.receive', $transaction) }}">
                        @csrf
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-3">
                            <p class="text-green-800 text-sm mb-2">
                                📦 <strong>{{ $transaction->seller->name }}</strong> a expédié votre article.
                            </p>
                            <p class="text-xs text-green-600 mb-3">
                                Confirmez la réception pour libérer le paiement au vendeur.
                            </p>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white font-semibold py-2.5 rounded-lg hover:bg-green-700 transition"
                                onclick="return confirm('Confirmez-vous avoir reçu l\'article ? Le paiement sera libéré au vendeur.')">
                            ✅ J'ai bien reçu l'article — Libérer le paiement
                        </button>
                    </form>
                @endif

                <!-- ── ACHETEUR : Litige (si livré mais problème) ── -->
                @if($isDelivered && $isBuyer)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800 text-sm mb-3">
                            ⚠️ Un problème avec la livraison ?
                        </p>
                        @if($transaction->canOpenDispute())
                        <a href="{{ route('disputes.create', $transaction) }}" class="block w-full text-center bg-red-50 text-red-600 font-semibold py-2.5 rounded-lg hover:bg-red-100 transition border border-red-200">
                            🚨 Ouvrir un litige
                        </a>
                        @else
                        <p class="text-xs text-gray-500">Délai de litige dépassé.</p>
                        @endif
                    </div>
                @endif

                <!-- ── Transaction terminée ── -->
                @if($isCompleted)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-center">
                        <p class="text-emerald-800 font-semibold">✅ Transaction terminée</p>
                        <p class="text-xs text-emerald-600 mt-1">Paiement libéré au vendeur.</p>
                    </div>
                @endif

                <!-- ── Annuler (si autorisé) ── -->
                @if($transaction->canBeCancelled())
                    <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette transaction ?');">
                        @csrf
                        <button type="submit" class="w-full text-gray-500 hover:text-red-600 font-medium py-2.5 rounded-lg hover:bg-red-50 transition text-sm">
                            ❌ Annuler la transaction
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
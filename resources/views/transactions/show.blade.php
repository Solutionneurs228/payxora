@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->reference . ' — PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $transaction->product_name }}</h1>
                <x-status-badge :status="$transaction->status->value" />
            </div>
            <p class="text-sm text-gray-500 mt-1">Reference: <span class="font-mono">{{ $transaction->reference }}</span></p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">
            &larr; Retour aux transactions
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details principaux -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info generale -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
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

                @if($transaction->product_description)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-gray-500 text-sm">Description</p>
                    <p class="text-gray-700 mt-1">{{ $transaction->product_description }}</p>
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

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
                <h3 class="font-semibold text-gray-900 mb-3">Actions</h3>

                @if($transaction->isPendingPayment() && !$transaction->buyer_id)
                    <form method="POST" action="{{ route('transactions.pay', $transaction) }}">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition">
                            Payer maintenant
                        </button>
                    </form>
                @endif

                @if($transaction->isFunded() && $transaction->seller_id === auth()->id())
                    <form method="POST" action="{{ route('transactions.ship', $transaction) }}">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="tracking_number" placeholder="Numero de suivi (optionnel)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition">
                            Marquer comme expedie
                        </button>
                    </form>
                @endif

                @if($transaction->isShipped() && ($transaction->seller_id === auth()->id() || auth()->user()->isAdmin()))
                    <form method="POST" action="{{ route('transactions.deliver', $transaction) }}">
                        @csrf
                        <button type="submit" class="w-full bg-purple-600 text-white font-semibold py-2.5 rounded-lg hover:bg-purple-700 transition">
                            Marquer comme livre
                        </button>
                    </form>
                @endif

                @if($transaction->isDelivered() && $transaction->buyer_id === auth()->id())
                    <form method="POST" action="{{ route('transactions.confirm', $transaction) }}">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white font-semibold py-2.5 rounded-lg hover:bg-green-700 transition">
                            Confirmer la reception
                        </button>
                    </form>

                    @if($transaction->canOpenDispute())
                    <a href="{{ route('disputes.create', $transaction) }}" class="block w-full text-center bg-red-50 text-red-600 font-semibold py-2.5 rounded-lg hover:bg-red-100 transition border border-red-200">
                        Ouvrir un litige
                    </a>
                    @endif
                @endif

                @if($transaction->canBeCancelled())
                    <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" onsubmit="return confirm('Etes-vous sur de vouloir annuler cette transaction ?');">
                        @csrf
                        <button type="submit" class="w-full text-gray-500 hover:text-red-600 font-medium py-2.5 rounded-lg hover:bg-red-50 transition text-sm">
                            Annuler la transaction
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

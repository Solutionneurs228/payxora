@extends('layouts.app')

@section('title', 'Transaction ' . $transaction->reference)

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $transaction->product_name }}</h1>
                    <p class="text-slate-500 font-mono">{{ $transaction->reference }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $transaction->getStatusColor() }}-100 text-{{ $transaction->getStatusColor() }}-800 w-fit">
                    {{ $transaction->getStatusLabel() }}
                </span>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Details du produit</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Produit</span>
                            <span class="font-medium text-slate-900">{{ $transaction->product_name }}</span>
                        </div>
                        @if($transaction->product_description)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Description</span>
                                <span class="font-medium text-slate-900 text-right max-w-xs">{{ $transaction->product_description }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-500">Montant</span>
                            <span class="font-bold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Commission (3%)</span>
                            <span class="text-slate-600">{{ number_format($transaction->commission_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-slate-100">
                            <span class="text-slate-500">Net pour le vendeur</span>
                            <span class="font-bold text-emerald-600">{{ number_format($transaction->net_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- Parties -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Parties</h3>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
                                {{ $transaction->seller->initials }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">{{ $transaction->seller->full_name }}</p>
                                <p class="text-xs text-slate-500">Vendeur</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                            @if($transaction->buyer)
                                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm">
                                    {{ $transaction->buyer->initials }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $transaction->buyer->full_name }}</p>
                                    <p class="text-xs text-slate-500">Acheteur</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-500">En attente</p>
                                    <p class="text-xs text-slate-400">Aucun acheteur</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Progression</h3>
                    <div class="relative">
                        @php
                        $steps = [
                            ['status' => 'pending', 'label' => 'Creee', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['status' => 'paid', 'label' => 'Payee', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                            ['status' => 'shipped', 'label' => 'Expediee', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            ['status' => 'delivered', 'label' => 'Livree', 'icon' => 'M5 13l4 4L19 7'],
                            ['status' => 'completed', 'label' => 'Terminee', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ];
                        $currentStep = array_search($transaction->status, array_column($steps, 'status'));
                        if ($currentStep === false) $currentStep = -1;
                        @endphp

                        <div class="flex items-center justify-between">
                            @foreach($steps as $i => $step)
                                <div class="flex flex-col items-center relative z-10">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs mt-2 {{ $i <= $currentStep ? 'text-emerald-600 font-medium' : 'text-slate-400' }}">{{ $step['label'] }}</span>
                                </div>
                                @if($i < count($steps) - 1)
                                    <div class="flex-1 h-1 mx-2 {{ $i < $currentStep ? 'bg-emerald-600' : 'bg-slate-200' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Amount Card -->
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 text-white">
                    <p class="text-emerald-100 text-sm">Montant total</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <p class="text-emerald-100 text-sm">Commission (3%)</p>
                        <p class="font-semibold">{{ number_format($transaction->commission_amount, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($transaction->isPending() && auth()->id() !== $transaction->seller_id)
                            <a href="{{ route('transactions.pay', $transaction) }}" class="block w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-center font-semibold rounded-xl transition-all">
                                Payer maintenant
                            </a>
                        @endif

                        @if($transaction->isDelivered() && $transaction->buyer_id === auth()->id())
                            <form method="POST" action="{{ route('transactions.confirm', $transaction) }}">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all">
                                    Confirmer la reception
                                </button>
                            </form>
                        @endif

                        @if($transaction->canOpenDispute() && $transaction->buyer_id === auth()->id())
                            <a href="{{ route('disputes.create', $transaction) }}" class="block w-full py-3 bg-amber-500 hover:bg-amber-600 text-white text-center font-semibold rounded-xl transition-all">
                                Ouvrir un litige
                            </a>
                        @endif

                        @if($transaction->canBeCancelled())
                            <form method="POST" action="{{ route('transactions.cancel', $transaction) }}" onsubmit="return confirm('Annuler cette transaction ?');">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-white border-2 border-slate-200 hover:border-red-300 text-slate-600 hover:text-red-600 font-semibold rounded-xl transition-all">
                                    Annuler la transaction
                                </button>
                            </form>
                        @endif

                        @if($transaction->isPending())
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <p class="text-xs text-slate-500 mb-2">Partager avec l'acheteur:</p>
                                <div class="flex gap-2">
                                    <input type="text" value="{{ route('transactions.show', $transaction) }}" readonly class="flex-1 text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-600">
                                    <button onclick="navigator.clipboard.writeText('{{ route('transactions.show', $transaction) }}')" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 rounded-lg text-slate-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping -->
                @if($transaction->shipping_address)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h3 class="font-semibold text-slate-900 mb-3">Livraison</h3>
                        <p class="text-sm text-slate-600">{{ $transaction->shipping_address }}</p>
                        @if($transaction->tracking_number)
                            <p class="text-sm text-slate-500 mt-2">Tracking: {{ $transaction->tracking_number }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

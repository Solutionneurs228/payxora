@extends('layouts.app')

@section('title', 'Litige #' . $dispute->id . ' — Admin PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.disputes.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">&larr; Retour aux litiges</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Litige #{{ $dispute->id }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $dispute->transaction->product_name }}</p>
                </div>
                <x-status-badge :status="$dispute->status->value" />
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Initie par</p>
                    <p class="font-medium text-gray-900">{{ $dispute->initiator->name }} ({{ $dispute->initiator->email }})</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Date d'ouverture</p>
                    <p class="font-medium text-gray-900">{{ $dispute->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Montant en jeu</p>
                    <p class="font-medium text-gray-900">{{ number_format($dispute->transaction->amount, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Statut transaction</p>
                    <x-status-badge :status="$dispute->transaction->status->value" />
                </div>
            </div>

            <!-- Motif -->
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Motif</p>
                <p class="font-medium text-gray-900">{{ $dispute->reason }}</p>
            </div>

            @if($dispute->description)
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Description</p>
                <p class="text-gray-700">{{ $dispute->description }}</p>
            </div>
            @endif

            <!-- Messages -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Echanges</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto bg-gray-50 rounded-lg p-4">
                    @forelse($dispute->messages as $msg)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs shrink-0">
                            {{ substr($msg->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $msg->user->name }}</p>
                            <p class="text-sm text-gray-700">{{ $msg->message }}</p>
                            <p class="text-xs text-gray-400">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center">Aucun message</p>
                    @endforelse
                </div>
            </div>

            <!-- Arbitrage -->
            @if($dispute->isOpen())
            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-semibold text-gray-900 mb-4">Arbitrage</h3>
                <form method="POST" action="{{ route('admin.disputes.arbitrate', $dispute) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Decision</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="resolution" value="refund_buyer" class="peer sr-only" required>
                                <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-red-600 peer-checked:bg-red-50 transition hover:border-red-300">
                                    <svg class="w-8 h-8 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Rembourser l'acheteur</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="resolution" value="release_seller" class="peer sr-only">
                                <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-green-600 peer-checked:bg-green-50 transition hover:border-green-300">
                                    <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Liberer au vendeur</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes de resolution (optionnel)</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Prendre la decision
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Transaction ' . $transaction->reference . ' — Admin PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">&larr; Retour aux transactions</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $transaction->product_name }}</h1>
                    <p class="text-sm text-gray-500 font-mono">{{ $transaction->reference }}</p>
                </div>
                <x-status-badge :status="$transaction->status->value" />
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Montant</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Commission</p>
                    <p class="text-xl font-bold text-gray-700">{{ number_format($transaction->commission_amount, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Net</p>
                    <p class="text-xl font-bold text-indigo-600">{{ number_format($transaction->net_amount, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Vendeur</p>
                    <p class="font-medium">{{ $transaction->seller->name }} ({{ $transaction->seller->email }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Acheteur</p>
                    <p class="font-medium">{{ $transaction->buyer?->name ?? 'Non attribue' }} {{ $transaction->buyer?->email ?? '' }}</p>
                </div>
            </div>

            @if($transaction->isDelivered())
            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-semibold text-gray-900 mb-4">Actions admin</h3>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('admin.transactions.release', $transaction) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-green-700 transition">
                            Liberer les fonds
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.transactions.refund', $transaction) }}">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-red-700 transition">
                            Rembourser l'acheteur
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

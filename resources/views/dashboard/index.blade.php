@extends('layouts.app')

@section('title', 'Tableau de bord — PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tableau de bord</h1>

    {{-- ─── CARTES STATISTIQUES CLIQUABLES ─────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total transactions --}}
        <a href="{{ route('transactions.index') }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-indigo-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total transactions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_transactions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </a>

        {{-- En attente de paiement --}}
        <a href="{{ route('transactions.index') }}?status=pending" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-yellow-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En attente</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending_transactions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </a>

        {{-- Séquestre actif --}}
        <a href="{{ route('transactions.index') }}?status=escrow" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-blue-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En sequestre</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['active_escrow'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            </div>
        </a>

        {{-- Terminées --}}
        <a href="{{ route('transactions.index') }}?status=completed" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-green-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Terminees</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['completed_transactions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </a>

        {{-- Ventes totales --}}
        <a href="{{ route('transactions.index') }}?status=completed" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-teal-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Ventes totales</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_sales'], 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </a>

        {{-- Achats totaux --}}
        <a href="{{ route('transactions.index') }}?status=completed" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-purple-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Achats totaux</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_purchases'], 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
        </a>
    </div>

    {{-- ─── TRANSACTIONS RÉCENTES ─────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Transactions recentes</h2>
            <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-medium">Reference</th>
                        <th class="px-6 py-3 font-medium">Produit</th>
                        <th class="px-6 py-3 font-medium">Montant</th>
                        <th class="px-6 py-3 font-medium">Statut</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recent_transactions as $tx)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        onclick="window.location='{{ route('transactions.show', $tx) }}'">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $tx->reference }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ Str::limit($tx->product_name, 30) }}</td>
                        <td class="px-6 py-4 font-medium">{{ number_format($tx->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$tx->status->value" />
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucune transaction</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

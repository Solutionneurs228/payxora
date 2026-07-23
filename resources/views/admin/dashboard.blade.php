@extends('layouts.app')

@section('title', 'Tableau de bord Admin — PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tableau de bord Administrateur</h1>

    {{-- ─── CARTES STATISTIQUES CLIQUABLES ─────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Utilisateurs --}}
        <a href="{{ route('admin.users.index') }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-indigo-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Utilisateurs</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['verified_users'] }} verifiés</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </div>
            </div>
        </a>

        {{-- Transactions --}}
        <a href="{{ route('admin.transactions.index') }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-blue-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Transactions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_transactions'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ $stats['active_transactions'] }} actives</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </a>

        {{-- Terminées --}}
        <a href="{{ route('admin.transactions.index') }}?status=completed" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-green-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Terminees</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['completed_transactions'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ number_format($stats['total_volume'], 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
        </a>

        {{-- Séquestre actif --}}
        <a href="{{ route('admin.transactions.index') }}?status=funded" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-yellow-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En sequestre</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['escrow_balance'], 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-yellow-600 mt-1">{{ $stats['active_transactions'] }} transactions</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            </div>
        </a>

        {{-- Commissions --}}
        <a href="{{ route('admin.transactions.index') }}?status=completed" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-purple-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Commissions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_commissions'], 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-purple-600 mt-1">Revenus plateforme</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </a>

        {{-- Fonds libérés --}}
        <a href="{{ route('admin.transactions.index') }}?status=completed" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-teal-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Fonds liberes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['released_funds'], 0, ',', ' ') }} FCFA</p>
                    <p class="text-xs text-teal-600 mt-1">Verses aux vendeurs</p>
                </div>
                <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </a>

        {{-- Litiges --}}
        <a href="{{ route('admin.disputes.index') }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-red-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Litiges ouverts</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['open_disputes'] }}</p>
                    <p class="text-xs text-red-600 mt-1">Necessitent attention</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
        </a>

        {{-- KYC en attente --}}
        <a href="{{ route('admin.users.index') }}?kyc=pending" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:border-orange-200 transition cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">KYC en attente</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending_kyc'] }}</p>
                    <p class="text-xs text-orange-600 mt-1">A verifier</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                </div>
            </div>
        </a>
    </div>

    {{-- ─── TRANSACTIONS RÉCENTES ─────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Transactions recentes</h2>
            <a href="{{ route('admin.transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-medium">Reference</th>
                        <th class="px-6 py-3 font-medium">Produit</th>
                        <th class="px-6 py-3 font-medium">Vendeur</th>
                        <th class="px-6 py-3 font-medium">Acheteur</th>
                        <th class="px-6 py-3 font-medium">Montant</th>
                        <th class="px-6 py-3 font-medium">Statut</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions as $tx)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        onclick="window.location='{{ route('admin.transactions.show', $tx) }}'">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $tx->reference }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ Str::limit($tx->product_name, 30) }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.show', $tx->seller) }}"
                               class="text-indigo-600 hover:text-indigo-800 hover:underline"
                               onclick="event.stopPropagation();">
                                {{ $tx->seller->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($tx->buyer)
                                <a href="{{ route('admin.users.show', $tx->buyer) }}"
                                   class="text-indigo-600 hover:text-indigo-800 hover:underline"
                                   onclick="event.stopPropagation();">
                                    {{ $tx->buyer->name }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium">{{ number_format($tx->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$tx->status->value" />
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">Aucune transaction</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ─── LITIGES OUVERTS ─────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Litiges ouverts ({{ $stats['open_disputes'] }})</h2>
            <a href="{{ route('admin.disputes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-medium">Transaction</th>
                        <th class="px-6 py-3 font-medium">Initie par</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                        <th class="px-6 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($openDisputes as $dispute)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        onclick="window.location='{{ route('admin.disputes.show', $dispute) }}'">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $dispute->transaction->product_name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.show', $dispute->opener) }}"
                               class="text-indigo-600 hover:text-indigo-800 hover:underline"
                               onclick="event.stopPropagation();">
                                {{ $dispute->opener->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $dispute->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Arbitrer</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucun litige ouvert</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

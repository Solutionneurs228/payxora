@extends('layouts.app')

@section('title', 'Administration — PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tableau de bord Admin</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Utilisateurs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-green-600 mt-1">{{ $stats['active_users'] }} actifs</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">KYC en attente</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_kyc'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Transactions</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_transactions'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Volume total</p>
            <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['total_volume'], 0, ',', ' ') }} FCFA</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transactions recentes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Transactions recentes</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_transactions as $tx)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $tx->product_name }}</p>
                        <p class="text-xs text-gray-500">{{ $tx->seller->name }} &rarr; {{ $tx->buyer?->name ?? 'En attente' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">{{ number_format($tx->amount, 0, ',', ' ') }} FCFA</p>
                        <x-status-badge :status="$tx->status->value" />
                    </div>
                </div>
                @empty
                <div class="px-6 py-6 text-center text-gray-500 text-sm">Aucune transaction</div>
                @endforelse
            </div>
        </div>

        <!-- Litiges ouverts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Litiges ouverts ({{ $stats['open_disputes'] }})</h2>
                <a href="{{ route('admin.disputes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_disputes as $dispute)
                <div class="px-6 py-3 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $dispute->transaction->product_name }}</p>
                            <p class="text-xs text-gray-500">Par {{ $dispute->initiator->name }} — {{ $dispute->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.disputes.show', $dispute) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Arbitrer</a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-6 text-center text-gray-500 text-sm">Aucun litige ouvert</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

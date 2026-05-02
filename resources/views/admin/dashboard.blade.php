@extends('layouts.app')

@section('title', 'Tableau de Bord Admin - PayXora')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header Admin -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Administration PayXora</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 text-sm">
                        Retour au site
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Utilisateurs</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir tout &rarr;</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Transactions</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_transactions'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <a href="{{ route('admin.transactions.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir tout &rarr;</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Litiges Ouverts</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['open_disputes'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <a href="{{ route('admin.disputes.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir tout &rarr;</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">KYC en Attente</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_kyc'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <a href="{{ route('admin.users.index') }}?filter=pending_kyc" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir tout &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Dernieres Transactions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Dernieres Transactions</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentTransactions ?? [] as $transaction)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->title }}</p>
                                    <p class="text-sm text-gray-500">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($transaction->status === 'completed') bg-green-100 text-green-800
                                    @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status === 'disputed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">Aucune transaction recente.</div>
                    @endforelse
                </div>
            </div>

            <!-- Derniers Litiges -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Derniers Litiges</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentDisputes ?? [] as $dispute)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Litige #{{ $dispute->id }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($dispute->status === 'open') bg-yellow-100 text-yellow-800
                                    @elseif($dispute->status === 'mediation') bg-blue-100 text-blue-800
                                    @elseif($dispute->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($dispute->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500">Aucun litige recent.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

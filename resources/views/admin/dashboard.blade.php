@extends('layouts.app')

@section('title', 'Tableau de bord Admin - PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Tableau de bord Admin</h1>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md mb-6">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md mb-6">
            <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
        </div>
    @endif

    {{-- Cartes statistiques avec liens --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Utilisateurs --}}
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition hover:border-indigo-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $stats['active_users'] }} actifs</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-sm text-indigo-600 font-medium">Voir les utilisateurs →</span>
            </div>
        </a>

        {{-- KYC en attente --}}
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition hover:border-yellow-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">KYC en attente</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['pending_kyc'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['verified_users'] }} verifies</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-sm text-yellow-600 font-medium">Valider les KYC →</span>
            </div>
        </a>

        {{-- Transactions --}}
        <a href="{{ route('admin.transactions.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition hover:border-blue-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Transactions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_transactions'] }}</p>
                    <p class="text-sm text-blue-600 mt-1">{{ $stats['active_transactions'] }} actives</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-sm text-blue-600 font-medium">Voir les transactions →</span>
            </div>
        </a>

        {{-- Volume & Commissions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Volume total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_volume'], 0, ',', ' ') }} FCFA</p>
                    <p class="text-sm text-green-600 mt-1">{{ number_format($stats['total_commissions'], 0, ',', ' ') }} FCFA commissions</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <span class="text-sm text-gray-400">{{ $stats['completed_transactions'] }} terminees</span>
            </div>
        </div>
    </div>

    {{-- Alertes litiges --}}
    @if($stats['open_disputes'] > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="font-semibold text-red-800">{{ $stats['open_disputes'] }} litige(s) ouvert(s)</p>
                    <p class="text-sm text-red-600">Necessite une attention immediate</p>
                </div>
            </div>
            <a href="{{ route('admin.disputes.index') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                Arbitrer les litiges
            </a>
        </div>
    </div>
    @endif

    {{-- Tableaux recents --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Transactions recentes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Transactions recentes</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_transactions as $tx)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $tx->title }}</p>
                            <p class="text-sm text-gray-500">{{ $tx->seller->name }} → {{ $tx->buyer?->name ?? 'En attente' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ number_format($tx->amount, 0, ',', ' ') }} FCFA</p>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full
                                {{ $tx->status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($tx->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($tx->status === 'paid' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ $tx->status }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    Aucune transaction recente
                </div>
                @endforelse
            </div>
        </div>

        {{-- Utilisateurs recents - CLIQUABLES --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Utilisateurs recents</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_users as $user)
                <a href="{{ route('admin.users.show', $user) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                {{ $user->initials }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Statut KYC avec info --}}
                            @if($user->kyc)
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $user->kyc->status === 'verified' ? 'bg-green-100 text-green-800' :
                                       ($user->kyc->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    KYC: {{ $user->kyc->status }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">KYC: non soumis</span>
                            @endif

                            {{-- Statut compte --}}
                            @if(!$user->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">SUSPENDU</span>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    Aucun utilisateur recent
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

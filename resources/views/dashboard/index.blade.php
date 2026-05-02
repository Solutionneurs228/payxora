@extends('layouts.app')

@section('title', 'Tableau de bord — PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ auth()->user()->name }} !</h1>
        <p class="text-gray-500 mt-1">Voici un apercu de votre activite</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Transactions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_transactions'] }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_transactions'] }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">En sequestre</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['active_escrow'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Terminees</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed_transactions'] }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Nouvelle transaction ?</h2>
                <p class="text-indigo-100 mt-1">Creez une transaction securisee en quelques clics</p>
            </div>
            <a href="{{ route('transactions.create') }}" class="bg-white text-indigo-600 font-semibold px-6 py-3 rounded-lg hover:bg-indigo-50 transition shadow-md">
                + Nouvelle transaction
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Transactions recentes -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Transactions recentes</h2>
                    <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">Voir tout</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_transactions as $tx)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $tx->product_name }}</p>
                                <p class="text-sm text-gray-500">{{ $tx->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ number_format($tx->amount, 0, ',', ' ') }} FCFA</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $tx->getStatusColor() }}-100 text-{{ $tx->getStatusColor() }}-800">
                                {{ $tx->getStatusLabel() }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>Aucune transaction pour le moment</p>
                        <a href="{{ route('transactions.create') }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block">Creer votre premiere transaction</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Notifications</h2>
                    @if($unread_count > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unread_count }}</span>
                    @endif
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($unread_notifications as $notif)
                    <div class="px-6 py-3 hover:bg-gray-50 transition">
                        <p class="text-sm font-medium text-gray-900">{{ $notif->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $notif->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <div class="px-6 py-6 text-center text-gray-400 text-sm">
                        <p>Pas de nouvelles notifications</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

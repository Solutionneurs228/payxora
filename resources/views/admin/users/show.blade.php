@extends('layouts.app')

@section('title', $user->name . ' — Admin PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition">&larr; Retour aux utilisateurs</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }} &middot; {{ $user->phone }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        {{ $user->role->label() }}
                    </span>
                    @if($user->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Actif</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Suspendu</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6 space-y-8">
            <!-- KYC -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Verification KYC</h2>
                @if($user->kycProfile)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Type de document</p>
                        <p class="font-medium text-gray-900">{{ strtoupper($user->kycProfile->id_type) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Numero</p>
                        <p class="font-medium text-gray-900">{{ $user->kycProfile->id_number }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Statut</p>
                        <x-kyc-verification-badge :status="$user->kycProfile->status->value" />
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Soumis le</p>
                        <p class="font-medium text-gray-900">{{ $user->kycProfile->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($user->kycProfile->status->value === 'pending')
                <form method="POST" action="{{ route('admin.users.validate-kyc', $user) }}" class="mt-4">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        Valider le KYC
                    </button>
                </form>
                @endif
                @else
                <p class="text-gray-500">Aucun document KYC soumis</p>
                @endif
            </div>

            <hr class="border-gray-100">

            <!-- Transactions -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Transactions</h2>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $user->transactionsAsSeller->count() }}</p>
                        <p class="text-sm text-gray-500">Ventes</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $user->transactionsAsBuyer->count() }}</p>
                        <p class="text-sm text-gray-500">Achats</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($user->transactionsAsSeller->where('status', 'completed')->sum('net_amount'), 0, ',', ' ') }} FCFA</p>
                        <p class="text-sm text-gray-500">Revenus</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Actions -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                @if($user->is_active)
                <form method="POST" action="{{ route('admin.users.suspend', $user) }}" onsubmit="return confirm('Suspendre cet utilisateur ?');">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-red-700 transition">
                        Suspendre l'utilisateur
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

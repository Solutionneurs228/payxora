@extends('layouts.app')

@section('title', 'Detail Utilisateur - Admin PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Retour aux utilisateurs</a>
    </div>

    {{-- ALERTE SUSPENSION --}}
    @if(!$user->is_active)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-red-800">Compte suspendu</h3>
                @if($user->kyc && $user->kyc->admin_notes)
                    <p class="text-sm text-red-600 mt-1">Motif : {{ $user->kyc->admin_notes }}</p>
                @endif
                <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                        Reactiver le compte
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Colonne 1: Infos utilisateur --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl">
                        {{ $user->initials }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Telephone</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->phone }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Role</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' :
                               ($user->role === 'seller' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ match($user->role) { 'admin' => 'Admin', 'seller' => 'Vendeur', 'buyer' => 'Acheteur', default => $user->role } }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Statut compte</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Actif' : 'Suspendu' }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">KYC</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $user->kyc_status === 'verified' ? 'bg-green-100 text-green-800' :
                               ($user->kyc_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ match($user->kyc_status) { 'verified' => 'Verifie', 'rejected' => 'Rejete', 'pending' => 'En attente', default => $user->kyc_status } }}
                        </span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Inscrit le</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-gray-500">Derniere connexion</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Jamais' }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    {{-- BOUTON VALIDER KYC - TOUJOURS VISIBLE si KYC pending --}}
                    @if($user->kyc && $user->kyc->status !== 'verified')
                        <form method="POST" action="{{ route('admin.users.validate-kyc', $user) }}" id="kyc">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Notes (optionnel)</label>
                                <textarea name="admin_notes" rows="2" placeholder="Commentaire sur la validation..." class="w-full text-xs px-2 py-1 border border-gray-300 rounded"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Valider le KYC
                            </button>
                        </form>
                    @elseif($user->kyc && $user->kyc->status === 'verified')
                        <div class="p-3 bg-green-50 rounded-lg text-center">
                            <svg class="w-6 h-6 text-green-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800">KYC verifie</p>
                            <p class="text-xs text-green-600">{{ $user->kyc->verified_at ? $user->kyc->verified_at->format('d/m/Y H:i') : '' }}</p>
                        </div>
                    @endif

                    {{-- SUSPENDRE / REACTIVER --}}
                    @if($user->is_active)
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}" onsubmit="return confirm('Suspendre cet utilisateur ?')">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Motif de suspension *</label>
                                <textarea name="reason" rows="2" required placeholder="Pourquoi suspendre cet utilisateur ?" class="w-full text-xs px-2 py-1 border border-gray-300 rounded"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Suspendre le compte
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Reactiver le compte
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Colonne 2-3: Details KYC --}}
        <div class="lg:col-span-2">
            @if($user->kyc)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" id="kyc-section">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Informations KYC</h2>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                        {{ $user->kyc->status === 'verified' ? 'bg-green-100 text-green-800' :
                           ($user->kyc->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ match($user->kyc->status) { 'verified' => 'Verifie', 'rejected' => 'Rejete', 'pending' => 'En attente', 'not_submitted' => 'Non soumis', default => $user->kyc->status } }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom complet</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de naissance</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->birth_date ? $user->kyc->birth_date->format('d/m/Y') : 'Non renseigne' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nationalite</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->nationality }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Adresse</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->address ?? 'Non renseignee' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type de document</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->document_type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Numero du document</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->document_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telephone verifie</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->phone_verified ? 'Oui' : 'Non' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Soumis le</label>
                            <p class="text-sm font-medium text-gray-900">{{ $user->kyc->submitted_at ? $user->kyc->submitted_at->format('d/m/Y H:i') : 'Non soumis' }}</p>
                        </div>
                    </div>
                </div>

                @if($user->kyc->admin_notes)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500">Notes admin / Motif</label>
                    <p class="text-sm text-gray-900 mt-1">{{ $user->kyc->admin_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Documents KYC --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Documents fournis</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Recto --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Recto du document</label>
                        @if($user->kyc->document_front)
                            <div class="aspect-[3/2] bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $user->kyc->document_front) }}" alt="Recto" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">Cliquez pour agrandir</p>
                        @else
                            <div class="aspect-[3/2] bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm border border-gray-200 border-dashed">
                                Non fourni
                            </div>
                        @endif
                    </div>

                    {{-- Verso --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Verso du document</label>
                        @if($user->kyc->document_back)
                            <div class="aspect-[3/2] bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $user->kyc->document_back) }}" alt="Verso" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">Cliquez pour agrandir</p>
                        @else
                            <div class="aspect-[3/2] bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm border border-gray-200 border-dashed">
                                Non fourni
                            </div>
                        @endif
                    </div>

                    {{-- Selfie --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Selfie avec document</label>
                        @if($user->kyc->selfie)
                            <div class="aspect-[3/2] bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $user->kyc->selfie) }}" alt="Selfie" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">Cliquez pour agrandir</p>
                        @else
                            <div class="aspect-[3/2] bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm border border-gray-200 border-dashed">
                                Non fourni
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun KYC soumis</h3>
                <p class="text-gray-500">Cet utilisateur n'a pas encore soumis de documents KYC.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

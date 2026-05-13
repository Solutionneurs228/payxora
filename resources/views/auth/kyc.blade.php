@extends('layouts.app')

@section('title', 'Verification KYC - PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Verification d'identite (KYC)</h2>

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

        {{-- ALERTE COMPTE SUSPENDU --}}
        @if(!auth()->user()->is_active)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-red-800">Compte suspendu</h3>
                    @if(auth()->user()->kyc && auth()->user()->kyc->admin_notes)
                        <p class="text-sm text-red-600 mt-1">Motif : {{ auth()->user()->kyc->admin_notes }}</p>
                    @else
                        <p class="text-sm text-red-600 mt-1">Votre compte a ete suspendu par un administrateur. Contactez le support pour plus d'informations.</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- STATUT KYC ACTUEL --}}
        @if(isset($kyc) && $kyc)
        <div class="mb-6 p-4 rounded-lg
            {{ $kyc->status === 'verified' ? 'bg-green-50 border border-green-200' :
               ($kyc->status === 'rejected' ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200') }}">
            <div class="flex items-center gap-3">
                @if($kyc->status === 'verified')
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @elseif($kyc->status === 'rejected')
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
                <div>
                    <p class="font-semibold
                        {{ $kyc->status === 'verified' ? 'text-green-800' :
                           ($kyc->status === 'rejected' ? 'text-red-800' : 'text-yellow-800') }}">
                        Statut KYC :
                        {{ match($kyc->status) {
                            'verified' => 'Verifie',
                            'rejected' => 'Rejete',
                            'pending' => 'En attente de verification',
                            'not_submitted' => 'Non soumis',
                            default => $kyc->status
                        } }}
                    </p>
                    @if($kyc->status === 'rejected' && $kyc->admin_notes)
                        <p class="text-sm text-red-600 mt-1">Motif du rejet : {{ $kyc->admin_notes }}</p>
                    @elseif($kyc->status === 'pending')
                        <p class="text-sm text-yellow-600 mt-1">Vos documents sont en cours d'examen par notre equipe.</p>
                    @elseif($kyc->status === 'verified')
                        <p class="text-sm text-green-600 mt-1">Votre identite a ete verifiee le {{ $kyc->verified_at?->format('d/m/Y') }}.</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- FORMULAIRE KYC --}}
        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input type="text" name="full_name" placeholder="Nom complet" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('full_name', $kyc->full_name ?? '') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                    <input type="date" name="birth_date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('birth_date', $kyc->birth_date ?? '') }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalite</label>
                <input type="text" name="nationality" placeholder="Ex: Togolaise" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    value="{{ old('nationality', $kyc->nationality ?? '') }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                    <select name="document_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Choisir...</option>
                        <option value="cni" {{ old('document_type', $kyc->document_type ?? '') === 'cni' ? 'selected' : '' }}>Carte Nationale d'Identite</option>
                        <option value="passport" {{ old('document_type', $kyc->document_type ?? '') === 'passport' ? 'selected' : '' }}>Passeport</option>
                        <option value="driver_license" {{ old('document_type', $kyc->document_type ?? '') === 'driver_license' ? 'selected' : '' }}>Permis de conduire</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numero du document</label>
                    <input type="text" name="document_number" placeholder="Numero du document" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('document_number', $kyc->document_number ?? '') }}">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse complete</label>
                <textarea name="address" placeholder="Votre adresse complete" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $kyc->address ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recto du document</label>
                    <input type="file" name="document_front" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @if($kyc && $kyc->document_front)
                        <p class="text-xs text-green-600 mt-1">Document deja fourni</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Verso du document</label>
                    <input type="file" name="document_back" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @if($kyc && $kyc->document_back)
                        <p class="text-xs text-green-600 mt-1">Document deja fourni</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selfie avec document</label>
                    <input type="file" name="selfie" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @if($kyc && $kyc->selfie)
                        <p class="text-xs text-green-600 mt-1">Selfie deja fourni</p>
                    @endif
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Soumettre ma verification
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

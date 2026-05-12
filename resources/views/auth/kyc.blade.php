@extends('layouts.app')

@section('title', 'Vérification KYC - PayXora')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Vérification d'identité (KYC)</h2>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md mb-6 fade-in">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input type="text" name="full_name" placeholder="Nom complet" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                    <input type="date" name="birth_date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                <input type="text" name="nationality" placeholder="Ex: Togolaise" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                    <select name="document_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Choisir...</option>
                        <option value="cni">Carte Nationale d'Identité</option>
                        <option value="passport">Passeport</option>
                        <option value="driver_license">Permis de conduire</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro du document</label>
                    <input type="text" name="document_number" placeholder="Numéro du document" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse complète</label>
                <textarea name="address" placeholder="Votre adresse complète" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recto du document</label>
                    <input type="file" name="document_front" accept="image/*" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Verso du document</label>
                    <input type="file" name="document_back" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selfie avec document</label>
                    <input type="file" name="selfie" accept="image/*" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Soumettre ma vérification
                </button>
            </div>
        </form>

        @if(isset($kyc) && $kyc)
            <hr class="my-8 border-gray-200">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-700">
                    Statut actuel :
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        {{ $kyc->status->value === 'verified' ? 'bg-green-100 text-green-800' :
                           ($kyc->status->value === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $kyc->status->value }}
                    </span>
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

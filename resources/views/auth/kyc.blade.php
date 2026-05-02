@extends('layouts.app')

@section('title', 'Verification d identite - PayXora')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <div class="mx-auto h-16 w-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1zm5.5 0c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Verification d'identite</h1>
            <p class="mt-2 text-gray-600">Cette etape est obligatoire pour utiliser PayXora. Vos documents sont securises et verifies en moins de 24h.</p>
        </div>

        @if($kyc && $kyc->status === 'rejected')
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">Votre precedente verification a ete rejetee. Veuillez soumettre de nouveaux documents clairs et lisibles.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Type de piece -->
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700">Type de piece d'identite</label>
                        <select id="id_type" name="id_type" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md
                                @error('id_type') border-red-300 @enderror">
                            <option value="">Selectionnez...</option>
                            <option value="cni" {{ old('id_type') == 'cni' ? 'selected' : '' }}>Carte Nationale d'Identite (CNI)</option>
                            <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passeport</option>
                            <option value="driving_license" {{ old('id_type') == 'driving_license' ? 'selected' : '' }}>Permis de conduire</option>
                        </select>
                        @error('id_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Numero de piece -->
                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">Numero de la piece</label>
                        <div class="mt-1">
                            <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" required
                                   class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md
                                   @error('id_number') border-red-300 @enderror"
                                   placeholder="Ex: TGO123456789">
                        </div>
                        @error('id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Photo recto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo recto de la piece</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-emerald-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="id_front" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                        <span>Choisir un fichier</span>
                                        <input id="id_front" name="id_front" type="file" accept="image/*" required class="sr-only" onchange="showFileName(this, 'id-front-name')">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG jusqu'a 2MB</p>
                                <p id="id-front-name" class="text-xs text-emerald-600 font-medium mt-1"></p>
                            </div>
                        </div>
                        @error('id_front')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Photo verso -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo verso de la piece (optionnel)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-emerald-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="id_back" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                        <span>Choisir un fichier</span>
                                        <input id="id_back" name="id_back" type="file" accept="image/*" class="sr-only" onchange="showFileName(this, 'id-back-name')">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG jusqu'a 2MB</p>
                                <p id="id-back-name" class="text-xs text-emerald-600 font-medium mt-1"></p>
                            </div>
                        </div>
                        @error('id_back')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selfie -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Selfie avec votre piece</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-emerald-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="selfie" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                        <span>Choisir un fichier</span>
                                        <input id="selfie" name="selfie" type="file" accept="image/*" capture="user" required class="sr-only" onchange="showFileName(this, 'selfie-name')">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Prenez une photo de vous tenant votre piece</p>
                                <p id="selfie-name" class="text-xs text-emerald-600 font-medium mt-1"></p>
                            </div>
                        </div>
                        @error('selfie')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Adresse complete</label>
                        <div class="mt-1">
                            <textarea id="address" name="address" rows="3" required
                                      class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md
                                      @error('address') border-red-300 @enderror"
                                      placeholder="Rue, quartier, ville">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ville -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                        <div class="mt-1">
                            <input type="text" name="city" id="city" value="{{ old('city') }}" required
                                   class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full sm:text-sm border-gray-300 rounded-md
                                   @error('city') border-red-300 @enderror"
                                   placeholder="Ex: Lome">
                        </div>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Soumettre ma verification
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-emerald-600 hover:text-emerald-500">
                    Se deconnecter et continuer plus tard
                </a>
            </p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showFileName(input, displayId) {
    const display = document.getElementById(displayId);
    if (input.files && input.files[0]) {
        display.textContent = 'Fichier selectionne : ' + input.files[0].name;
    }
}
</script>
@endpush
@endsection

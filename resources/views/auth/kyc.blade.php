@extends('layouts.app')

@section('title', 'Verification KYC - PayXora')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Verification d'identite (KYC)</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Pour votre securite et celle de la communaute, nous devons verifier votre identite.
                </p>
            </div>

            @if ($kyc && $kyc->isRejected())
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Verification refusee</h3>
                            <p class="mt-1 text-sm text-red-700">{{ $kyc->rejection_reason }}</p>
                            <p class="mt-2 text-sm text-red-600">Veuillez soumettre a nouveau vos documents.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erreurs dans le formulaire</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700">Type de piece *</label>
                        <select id="id_type" name="id_type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="cni" {{ old('id_type') == 'cni' ? 'selected' : '' }}>Carte Nationale d'Identite</option>
                            <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passeport</option>
                            <option value="driver_license" {{ old('id_type') == 'driver_license' ? 'selected' : '' }}>Permis de conduire</option>
                            <option value="residence" {{ old('id_type') == 'residence' ? 'selected' : '' }}>Carte de residence</option>
                        </select>
                    </div>

                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">Numero de la piece *</label>
                        <input type="text" id="id_number" name="id_number" required value="{{ old('id_number') }}"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Ex: TG12345678">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="id_front" class="block text-sm font-medium text-gray-700">Photo recto *</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="id_front" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Choisir un fichier</span>
                                        <input id="id_front" name="id_front" type="file" accept="image/*" required class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG jusqu'a 5 Mo</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="id_back" class="block text-sm font-medium text-gray-700">Photo verso</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="id_back" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Choisir un fichier</span>
                                        <input id="id_back" name="id_back" type="file" accept="image/*" class="sr-only">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Optionnel</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="selfie" class="block text-sm font-medium text-gray-700">Selfie avec la piece *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M14 40H4v-4a6 6 0 0110.712-3.714M16 3.468a6 6 0 0111.612 0M12 40a6 6 0 0112 0m0 0v-4a6 6 0 00-12 0v4m0 0h12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="selfie" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Prendre une photo</span>
                                    <input id="selfie" name="selfie" type="file" accept="image/*" capture="user" required class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">Tenez la piece pres de votre visage, bien visible</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Adresse *</label>
                        <input type="text" id="address" name="address" required value="{{ old('address') }}"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Rue, quartier">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Ville *</label>
                        <input type="text" id="city" name="city" required value="{{ old('city') }}"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Ex: Lome">
                    </div>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
                    <input type="text" id="country" name="country" value="{{ old('country', 'Togo') }}"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Soumettre pour verification
                    </button>
                </div>

                <p class="text-xs text-gray-500 text-center">
                    Vos documents sont securises et confidentiels. Ils ne seront utilises que pour la verification de votre identite.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Verification KYC - PayXora')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Verification d'identite (KYC)</h2>

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 mb-4">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700">Type de piece d'identite</label>
                        <select id="id_type" name="id_type" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="cni">Carte Nationale d'Identite (CNI)</option>
                            <option value="passport">Passeport</option>
                            <option value="driver_license">Permis de conduire</option>
                        </select>
                    </div>

                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">Numero de la piece</label>
                        <input type="text" id="id_number" name="id_number" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="id_front" class="block text-sm font-medium text-gray-700">Photo recto de la piece</label>
                        <input type="file" id="id_front" name="id_front" accept="image/*" required
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div>
                        <label for="id_back" class="block text-sm font-medium text-gray-700">Photo verso de la piece (optionnel)</label>
                        <input type="file" id="id_back" name="id_back" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div>
                        <label for="selfie" class="block text-sm font-medium text-gray-700">Selfie avec la piece</label>
                        <input type="file" id="selfie" name="selfie" accept="image/*" required
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Tenez la piece pres de votre visage, bien visible.</p>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" id="address" name="address" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                        <input type="text" id="city" name="city" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Soumettre pour verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

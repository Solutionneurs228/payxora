@extends('layouts.app')

@section('title', 'Verification KYC — PayXora')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Verification d'identite</h1>
            <p class="text-gray-500 mt-2">Pour votre securite, nous devons verifier votre identite avant de proceder aux transactions.</p>
        </div>

        @if($kyc && $kyc->status->value === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-700 text-sm">Vos documents sont en cours de verification. Cela peut prendre jusqu'a 24 heures.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="id_type" class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                    <select name="id_type" id="id_type" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="cni" {{ old('id_type') == 'cni' ? 'selected' : '' }}>Carte Nationale d'Identite</option>
                        <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passeport</option>
                        <option value="driving_license" {{ old('id_type') == 'driving_license' ? 'selected' : '' }}>Permis de conduire</option>
                    </select>
                </div>

                <div>
                    <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">Numero du document</label>
                    <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $kyc->id_number ?? '') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('id_number') border-red-500 @enderror">
                    @error('id_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="id_document" class="block text-sm font-medium text-gray-700 mb-1">Document d'identite (recto)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition cursor-pointer" onclick="document.getElementById('id_document').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <span class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">Telecharger un fichier</span>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF jusqu'a 2 Mo</p>
                    </div>
                </div>
                <input type="file" name="id_document" id="id_document" accept=".jpg,.jpeg,.png,.pdf" required class="hidden"
                    onchange="updateFileName(this, 'id-doc-name')">
                <p id="id-doc-name" class="mt-1 text-sm text-indigo-600"></p>
                @error('id_document')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="selfie" class="block text-sm font-medium text-gray-700 mb-1">Selfie avec le document</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition cursor-pointer" onclick="document.getElementById('selfie').click()">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <span class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">Prendre une photo</span>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG jusqu'a 2 Mo</p>
                    </div>
                </div>
                <input type="file" name="selfie" id="selfie" accept=".jpg,.jpeg,.png" required class="hidden"
                    onchange="updateFileName(this, 'selfie-name')">
                <p id="selfie-name" class="mt-1 text-sm text-indigo-600"></p>
                @error('selfie')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Soumettre ma verification
            </button>
        </form>
    </div>
</div>

<script>
function updateFileName(input, displayId) {
    const display = document.getElementById(displayId);
    if (input.files && input.files[0]) {
        display.textContent = 'Fichier selectionne : ' + input.files[0].name;
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Verification d\'identite')

@section('content')
<section class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto bg-emerald-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Verification d'identite</h2>
            <p class="mt-2 text-slate-500">Ces informations sont necessaires pour securiser les transactions</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center justify-center mb-10">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">1</div>
                <div class="w-16 h-1 bg-emerald-600"></div>
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">2</div>
                <div class="w-16 h-1 bg-slate-200"></div>
                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-sm font-bold">3</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
            <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- ID Document -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Piece d'identite (CNI, Passeport, Permis)</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-400 transition-colors cursor-pointer" onclick="document.getElementById('id_document').click()">
                        <input type="file" name="id_document" id="id_document" accept=".jpg,.jpeg,.png,.pdf" required class="hidden" onchange="previewFile(this, 'id-preview')">
                        <div id="id-preview-placeholder">
                            <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-slate-500">Cliquez pour telecharger ou glissez-deposez</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG ou PDF (max 5Mo)</p>
                        </div>
                        <div id="id-preview" class="hidden">
                            <img src="" alt="Preview" class="max-h-32 mx-auto rounded-lg">
                            <p class="text-sm text-emerald-600 mt-2 font-medium">Document selectionne</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Photo -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Photo de profil</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-400 transition-colors cursor-pointer" onclick="document.getElementById('profile_photo').click()">
                        <input type="file" name="profile_photo" id="profile_photo" accept=".jpg,.jpeg,.png" required class="hidden" onchange="previewFile(this, 'photo-preview')">
                        <div id="photo-preview-placeholder">
                            <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-slate-500">Photo claire de votre visage</p>
                            <p class="text-xs text-slate-400 mt-1">JPG ou PNG (max 2Mo)</p>
                        </div>
                        <div id="photo-preview" class="hidden">
                            <img src="" alt="Preview" class="max-h-32 mx-auto rounded-lg">
                            <p class="text-sm text-emerald-600 mt-2 font-medium">Photo selectionnee</p>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Adresse complete</label>
                    <textarea name="address" id="address" rows="2" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none"
                        placeholder="Rue, quartier, point de repere..."></textarea>
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                    <input type="text" name="city" id="city" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white"
                        placeholder="Lome">
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-amber-700">Vos documents seront verifies sous 24h. Vous pourrez utiliser la plateforme des la validation.</p>
                </div>

                <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40 transition-all transform hover:-translate-y-0.5">
                    Soumettre pour verification
                </button>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
function previewFile(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(previewId + '-placeholder');
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection

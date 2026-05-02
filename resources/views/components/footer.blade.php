<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    @include('components.application-logo')
                    <span class="font-bold text-lg text-indigo-700">PayXora</span>
                </div>
                <p class="text-sm text-gray-500 max-w-sm">La plateforme de paiement securise par escrow pour le Togo et l'Afrique de l'Ouest. Achetez et vendez en toute confiance.</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Navigation</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-indigo-600 transition">Accueil</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="text-gray-500 hover:text-indigo-600 transition">Comment ca marche</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-gray-500 hover:text-indigo-600 transition">Tarifs</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-500 hover:text-indigo-600 transition">A propos</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Legal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Conditions d'utilisation</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Politique de confidentialite</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-indigo-600 transition">Mentions legales</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-100 mt-8 pt-6 text-center">
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} PayXora. Tous droits reserves.</p>
        </div>
    </div>
</footer>

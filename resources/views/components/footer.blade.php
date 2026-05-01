<footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-8 h-8 text-emerald-500" viewBox="0 0 32 32" fill="none">
                        <rect x="2" y="8" width="28" height="16" rx="4" stroke="currentColor" stroke-width="2.5" fill="none"/>
                        <path d="M8 16h16M16 12v8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="24" cy="8" r="3" fill="#10B981"/>
                    </svg>
                    <span class="text-xl font-bold text-white">Pay<span class="text-emerald-500">Xora</span></span>
                </div>
                <p class="text-sm text-slate-400 mb-4">La plateforme de paiement securise (escrow) au Togo. Protegez vos transactions en ligne.</p>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Produit</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-emerald-500 transition">Comment ca marche</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-emerald-500 transition">Tarifs</a></li>
                    <li><a href="#" class="hover:text-emerald-500 transition">Securite</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Entreprise</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-emerald-500 transition">A propos</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-emerald-500 transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Legal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-emerald-500 transition">Conditions d utilisation</a></li>
                    <li><a href="#" class="hover:text-emerald-500 transition">Politique de confidentialite</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 mt-8 pt-8 text-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} PayXora SARL. Tous droits reserves. Lome, Togo.</p>
        </div>
    </div>
</footer>
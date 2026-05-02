<section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white overflow-hidden">
    <!-- Motif de fond subtil -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Texte -->
            <div class="fade-up">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6 text-sm font-medium">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Disponible au Togo
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Payez en toute <span class="text-blue-400">confiance</span>
                </h1>

                <p class="text-lg text-slate-300 mb-8 max-w-lg">
                    PayXora sécurise vos transactions entre particuliers et professionnels. 
                    L'argent est bloqué jusqu'à confirmation de livraison.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 hover:scale-105 shadow-lg shadow-blue-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Créer un compte gratuit
                    </a>
                    <a href="{{ route('how-it-works') }}" 
                       class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Comment ça marche
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/10">
                    <div>
                        <div class="text-2xl font-bold text-blue-400">0 FCFA</div>
                        <div class="text-sm text-slate-400">Commission pour l'acheteur</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-400">48h</div>
                        <div class="text-sm text-slate-400">Délai de confirmation</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-400">100%</div>
                        <div class="text-sm text-slate-400">Transactions sécurisées</div>
                    </div>
                </div>
            </div>

            <!-- Illustration / Mockup -->
            <div class="hidden lg:block fade-up delay-2">
                <div class="relative">
                    <!-- Carte transaction -->
                    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm mx-auto transform rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800">iPhone 15 Pro</div>
                                <div class="text-sm text-slate-500">Vendeur : Jean K.</div>
                            </div>
                        </div>
                        <div class="border-t pt-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-500">Montant</span>
                                <span class="font-semibold text-slate-800">450 000 FCFA</span>
                            </div>
                            <div class="flex justify-between text-sm mb-4">
                                <span class="text-slate-500">Frais</span>
                                <span class="font-semibold text-green-600">0 FCFA</span>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <div class="text-xs text-blue-600 font-medium uppercase tracking-wide">Statut</div>
                                <div class="text-blue-700 font-semibold">En séquestre</div>
                            </div>
                        </div>
                    </div>

                    <!-- Badge flottant -->
                    <div class="absolute -top-4 -right-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg animate-bounce">
                        Protégé
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vague de transition -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

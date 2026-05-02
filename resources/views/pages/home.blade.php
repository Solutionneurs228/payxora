@extends('layouts.app')

@section('title', 'PayXora - Paiement Securise au Togo')

@section('content')

    <!-- ===== SLIDER / HERO ===== -->
    <div class="relative bg-emerald-900 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                </pattern>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                <!-- Texte -->
                <div class="text-center lg:text-left">
                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-300 mb-6 border border-emerald-500/30">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                        Nouveau au Togo
                    </div>
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl lg:text-6xl">
                        Achetez et vendez en ligne<br>
                        <span class="text-emerald-400">en toute confiance</span>
                    </h1>
                    <p class="mt-6 text-lg text-emerald-100 max-w-lg mx-auto lg:mx-0">
                        PayXora securise vos transactions avec un systeme d'escrow. L'argent est bloque jusqu'a confirmation
                        de livraison.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-900 bg-white hover:bg-emerald-50 transition-colors shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Proteger mes transactions
                        </a>
                        <a href="{{ route('how-it-works') }}"
                            class="inline-flex items-center justify-center px-8 py-3 border border-emerald-400 text-base font-medium rounded-lg text-emerald-100 hover:bg-emerald-800/50 transition-colors">
                            Comment ca marche ?
                        </a>
                    </div>
                </div>

                <!-- Cadenas animé -->
                <div class="mt-12 lg:mt-0 flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-48 w-48 rounded-full bg-emerald-400/20 animate-ping"
                                style="animation-duration: 3s;"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-40 w-40 rounded-full bg-emerald-400/10 animate-ping"
                                style="animation-duration: 3s; animation-delay: 0.5s;"></div>
                        </div>
                        <div
                            class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-emerald-400/20">
                            <svg class="h-32 w-32 text-emerald-400 animate-float" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <div class="mt-4 text-center">
                                <p class="text-emerald-300 font-semibold">Vos fonds proteges</p>
                                <p class="text-emerald-400/60 text-sm">Escrow securise</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vague bas -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" preserveAspectRatio="none" class="w-full h-12">
                <path
                    d="M0 80L60 73.3C120 66.7 240 53.3 360 46.7C480 40 600 40 720 46.7C840 53.3 960 66.7 1080 70C1200 73.3 1320 66.7 1380 63.3L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z"
                    fill="#f9fafb" />
            </svg>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    {{-- <div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-emerald-100 mb-4">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 stat-counter">{{ $stats['total_transactions'] ?? '0' }}</p>
                <p class="mt-1 text-sm text-gray-500">Transactions</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-emerald-100 mb-4">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 stat-counter">{{ $stats['completed_transactions'] ?? '0' }}</p>
                <p class="mt-1 text-sm text-gray-500">Terminees</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-emerald-100 mb-4">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-gray-900 stat-counter">{{ $stats['active_users'] ?? '0' }}</p>
                <p class="mt-1 text-sm text-gray-500">Utilisateurs</p>
            </div>
        </div>
    </div>
</div> --}}
    <div class="mt-16 max-w-3xl mx-auto">
        <div class="prose prose-indigo prose-lg text-gray-500 mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">problématique</h3>
            <p class="text-xl leading-8 font-bold">
                PayXora est né d'un constat simple :
            </p>
            <br>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3"><strong>l'acheteur </strong> craint d'envoyer l'argent sans être livré ou sans
                        recevoir ce que le vendeur lui a promis</span>
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3"><strong>le vendeur </strong> ne peut pas accepter livrer sans être payé</span>
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3"><strong>Résultat : </strong> personne ne tire profit de l'E-commerce</span>
                </li>
            </ul>

            <div class="my-12 bg-indigo-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Que propose PayXora ?</h3>
                <p class="text-lg font-bold">
                    c'est simple mais efficace :
                </p>
                <br>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3"><strong>l'acheteur </strong> sait que son argent est protégé jusqu'à ce que
                            lui même confirme qu'il a été livré</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3"><strong>le vendeur </strong> sait que les fonds sont déjà libérés ; il lui
                            suffit de livrer le produit et il va encaisser</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3"><strong>Résultat : </strong> tout le monde tire aisement profit de
                            l'E-commerce</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="bg-emerald-700">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Vous êtes vendeur ?</h2>
            <p class="mt-3 text-lg text-emerald-200">
                voici ce que vous gagnez de plus : <strong> la crédibilité</strong> 
            </p>

           
        </div>
         <div class="mt-8 lg:mt-0 lg:flex-shrink-0">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-900 bg-white hover:bg-emerald-50 transition-colors shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Creer un compte gratuit
                </a>
            </div>
    </div>
</div>

    <!-- ===== COMMENT CA MARCHE (mini) ===== -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">Comment ca marche ?</h2>
                <p class="mt-3 text-lg text-gray-500">5 etapes simples pour securiser votre transaction</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                @php
                    $steps = [
                        [
                            'num' => '1',
                            'title' => 'Inscription',
                            'desc' => 'Creez votre compte ; gratuit et simple',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        ],
                        [
                            'num' => '2',
                            'title' => 'Creation',
                            'desc' => 'Le vendeur ou l\'acheteur enregistre la vente',
                            'icon' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        ],
                        [
                            'num' => '3',
                            'title' => 'Paiement',
                            'desc' => 'l\'acheteur effectue le paiement mais le vendeur n\'encaisse pas encore',
                            'icon' =>
                                'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                        ],
                        [
                            'num' => '4',
                            'title' => 'Livraison',
                            'desc' => 'Le vendeur expedie sachant que les fonds sont déjà libérés',
                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                        ],
                        [
                            'num' => '5',
                            'title' => 'Liberation',
                            'desc' => 'l\'acheteur confirme la reception et le vendeur encaisse',
                            'icon' => 'M9_12l2_2_4-4m6_2a9_9_0_11-18_0_9_9_0_0118_0z',
                        ],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div
                        class="relative bg-gray-50 rounded-xl p-6 text-center border border-gray-100 hover:border-emerald-300 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                            <span
                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-600 text-white text-sm font-bold shadow-md">{{ $step['num'] }}</span>
                        </div>
                        <div class="mt-4">
                            <svg class="h-10 w-10 text-emerald-500 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="{{ $step['icon'] }}" />
                            </svg>
                            <h3 class="font-semibold text-gray-900">{{ $step['title'] }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('how-it-works') }}"
                    class="inline-flex items-center text-emerald-600 font-medium hover:text-emerald-700 transition-colors">
                    Voir le processus complet
                    <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== POURQUOI PAYXORA ===== -->
    <div class="bg-emerald-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">Pourquoi PayXora ?</h2>
                <p class="mt-3 text-lg text-gray-500">La confiance au coeur de chaque transaction</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-emerald-100 mb-5">
                        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Zero arnaque</h3>
                    <p class="text-gray-500">L'argent est bloque en sequestre. Le vendeur ne touche rien sans livrer.
                        L'acheteur ne recoit rien sans payer.</p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-emerald-100 mb-5">
                        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">3% seulement</h3>
                    <p class="text-gray-500">Commission transparente de 3% par transaction. Pas de frais caches, pas
                        d'abonnement obligatoire.</p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-emerald-100 mb-5">
                        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Mobile Money</h3>
                    <p class="text-gray-500">Paiement via TMoney et Moov Money. Aucun compte bancaire requis. Simple,
                        rapide, local.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CTA ===== -->
    <div class="bg-emerald-700">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Pret a securiser vos transactions ?</h2>
                <p class="mt-3 text-lg text-emerald-200">Rejoignez PayXora et faites partie de la revolution du e-commerce
                    securise au Togo.</p>
            </div>
            <div class="mt-8 lg:mt-0 lg:flex-shrink-0">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-900 bg-white hover:bg-emerald-50 transition-colors shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Creer un compte gratuit
                </a>
            </div>
        </div>
    </div>

@endsection

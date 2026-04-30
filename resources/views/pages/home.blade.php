@extends('layouts.app')

@section('title', 'Paiement Securise au Togo')

@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden bg-white">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <!-- Floating shapes -->
    <div class="absolute top-20 right-10 w-64 h-64 bg-emerald-100 rounded-full blur-3xl opacity-60"></div>
    <div class="absolute bottom-10 left-10 w-48 h-48 bg-teal-100 rounded-full blur-3xl opacity-40"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-40">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-full mb-6">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-emerald-700">Nouveau au Togo</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold font-['Space_Grotesk'] text-slate-900 leading-tight">
                    Achetez & Vendez en <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">toute confiance</span>
                </h1>

                <p class="mt-6 text-lg text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    PayXora securise vos transactions en ligne au Togo. L'argent est bloque jusqu'a confirmation de livraison. Zero arnaque, zero impaye.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @auth
                        <a href="{{ route('transactions.create') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl shadow-xl shadow-emerald-600/25 hover:shadow-emerald-600/40 transition-all transform hover:-translate-y-0.5 text-center">
                            Creer une transaction
                        </a>
                        <a href="{{ route('how-it-works') }}" class="px-8 py-4 bg-white border-2 border-slate-200 hover:border-emerald-300 text-slate-700 font-semibold rounded-2xl hover:bg-slate-50 transition-all text-center">
                            Comment ca marche
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl shadow-xl shadow-emerald-600/25 hover:shadow-emerald-600/40 transition-all transform hover:-translate-y-0.5 text-center">
                            Commencer gratuitement
                        </a>
                        <a href="{{ route('how-it-works') }}" class="px-8 py-4 bg-white border-2 border-slate-200 hover:border-emerald-300 text-slate-700 font-semibold rounded-2xl hover:bg-slate-50 transition-all text-center">
                            Voir le fonctionnement
                        </a>
                    @endauth
                </div>

                <!-- Trust badges -->
                <div class="mt-10 flex flex-wrap items-center gap-6 justify-center lg:justify-start text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>100% Securise</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>3% de commission</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Mobile Money</span>
                    </div>
                </div>
            </div>

            <!-- Hero Illustration -->
            <div class="relative hidden lg:block">
                <div class="relative bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-8 border border-emerald-100">
                    <!-- Card 1: Seller -->
                    <div class="bg-white rounded-2xl shadow-lg p-5 mb-4 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Vendeur</p>
                                <p class="text-xs text-slate-500">Produit : iPhone 14 Pro</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-slate-50 rounded-lg p-3">
                            <span class="text-sm text-slate-600">Prix</span>
                            <span class="text-lg font-bold text-slate-900">450 000 FCFA</span>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="flex justify-center my-2">
                        <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Card 2: Escrow -->
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-lg p-5 mb-4 transform rotate-1 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">Sequestre PayXora</p>
                                <p class="text-xs text-emerald-100">Fonds bloques en securite</p>
                            </div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-emerald-100">Montant bloque</span>
                                <span class="text-lg font-bold text-white">450 000 FCFA</span>
                            </div>
                            <div class="mt-2 h-2 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full w-3/4 bg-white rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="flex justify-center my-2">
                        <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Card 3: Buyer -->
                    <div class="bg-white rounded-2xl shadow-lg p-5 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Acheteur</p>
                                <p class="text-xs text-slate-500">Confirmation reception</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 bg-emerald-50 rounded-lg p-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-medium text-emerald-700">Livraison confirmee</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-slate-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-white font-['Space_Grotesk']">{{ number_format($stats['transactions'] ?? 0) }}</div>
                <div class="mt-2 text-sm text-slate-400">Transactions securisees</div>
            </div>
            <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-white font-['Space_Grotesk']">{{ number_format($stats['volume'] ?? 0, 0, ',', ' ') }} FCFA</div>
                <div class="mt-2 text-sm text-slate-400">Volume traite</div>
            </div>
            <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-white font-['Space_Grotesk']">{{ number_format($stats['users'] ?? 0) }}</div>
                <div class="mt-2 text-sm text-slate-400">Utilisateurs verifies</div>
            </div>
            <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-emerald-400 font-['Space_Grotesk']">0%</div>
                <div class="mt-2 text-sm text-slate-400">Taux d'arnaque</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Preview -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold font-['Space_Grotesk'] text-slate-900">Comment ca marche ?</h2>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">5 etapes simples pour securiser vos transactions en ligne</p>
        </div>

        <div class="grid md:grid-cols-5 gap-6">
            @php
            $steps = [
                ['icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'title' => 'Inscription', 'desc' => 'Creez votre compte avec KYC leger'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Creation', 'desc' => 'Le vendeur cree la transaction'],
                ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Paiement', 'desc' => 'L\'acheteur paye, fonds bloques'],
                ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Livraison', 'desc' => 'Le vendeur expedie le produit'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Confirmation', 'desc' => 'Fonds liberes au vendeur'],
            ];
            @endphp

            @foreach($steps as $i => $step)
                <div class="relative text-center group">
                    <div class="w-16 h-16 mx-auto bg-emerald-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="text-sm font-bold text-slate-900 mb-1">{{ $i + 1 }}. {{ $step['title'] }}</div>
                    <p class="text-xs text-slate-500">{{ $step['desc'] }}</p>
                    @if($i < 4)
                        <div class="hidden md:block absolute top-8 left-[60%] w-full">
                            <svg class="w-6 h-6 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('how-it-works') }}" class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                Voir les details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-br from-emerald-600 to-teal-700 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <pattern id="cta-grid" width="8" height="8" patternUnits="userSpaceOnUse">
                <circle cx="1" cy="1" r="1" fill="white"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#cta-grid)"/>
        </svg>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-5xl font-bold text-white font-['Space_Grotesk'] mb-6">
            Pret a vendre en toute securite ?
        </h2>
        <p class="text-lg text-emerald-100 mb-10 max-w-2xl mx-auto">
            Rejoignez des milliers de vendeurs et acheteurs qui utilisent PayXora pour securiser leurs transactions au Togo.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('transactions.create') }}" class="px-8 py-4 bg-white text-emerald-700 font-bold rounded-2xl shadow-xl hover:bg-slate-50 transition-all transform hover:-translate-y-0.5">
                    Creer ma transaction
                </a>
            @else
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-emerald-700 font-bold rounded-2xl shadow-xl hover:bg-slate-50 transition-all transform hover:-translate-y-0.5">
                    Creer un compte gratuit
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-emerald-700 text-white font-bold rounded-2xl border-2 border-emerald-500 hover:bg-emerald-800 transition-all">
                    J'ai deja un compte
                </a>
            @endauth
        </div>
    </div>
</section>

@endsection

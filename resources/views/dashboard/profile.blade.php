
# === PROFIL + PAGES PUBLIQUES (4 fichiers) ===

profile = r"""@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Mon profil</h1>
            <p class="text-slate-500">Gerer vos informations personnelles</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                        {{ auth()->user()->initials }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ auth()->user()->full_name }}</h2>
                        <p class="text-emerald-100">{{ auth()->user()->email }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/20">
                                {{ auth()->user()->role === 'seller' ? 'Vendeur' : 'Acheteur' }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->isKycVerified() ? 'bg-emerald-400/30' : 'bg-amber-400/30' }}">
                                {{ auth()->user()->isKycVerified() ? 'KYC Verifie' : 'KYC En attente' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Prenom</label>
                            <input type="text" name="first_name" value="{{ auth()->user()->first_name }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                            <input type="text" name="last_name" value="{{ auth()->user()->last_name }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Telephone</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">+228</span>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required
                                class="w-full pl-14 pr-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Adresse</label>
                        <textarea name="address" rows="2"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none">{{ auth()->user()->address }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                        <input type="text" name="city" value="{{ auth()->user()->city }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                        Mettre a jour le profil
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Changer le mot de passe</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe actuel</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl shadow-lg transition-all">
                    Changer le mot de passe
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
"""

how_it_works = r"""@extends('layouts.app')

@section('title', 'Comment ca marche')

@section('content')
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-3xl lg:text-4xl font-bold text-slate-900">Comment ca marche ?</h1>
            <p class="mt-4 text-lg text-slate-500">5 etapes simples pour securiser vos transactions</p>
        </div>

        @php
        $steps = [
            ['num' => '1', 'title' => 'Inscription', 'desc' => 'Creez votre compte en 2 minutes. Verification KYC legere (telephone, photo, piece d\'identite).', 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
            ['num' => '2', 'title' => 'Creation de la transaction', 'desc' => 'Le vendeur enregistre la vente sur PayXora (produit, prix, conditions de livraison).', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['num' => '3', 'title' => 'Paiement bloque', 'desc' => 'L\'acheteur paie via T-Money ou Moov Money. L\'argent est bloque sur un compte sequestre securise.', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
            ['num' => '4', 'title' => 'Livraison', 'desc' => 'Le vendeur expedie le produit. Le client recoit et inspecte la marchandise.', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['num' => '5', 'title' => 'Deblocage des fonds', 'desc' => 'Le client confirme la reception. L\'argent est transfere au vendeur. Transaction terminee !', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
        @endphp

        <div class="space-y-8">
            @foreach($steps as $step)
                <div class="flex gap-6 items-start">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 shadow-lg">
                        {{ $step['num'] }}
                    </div>
                    <div class="flex-1 pt-2">
                        <h3 class="text-xl font-bold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="ml-7 w-px h-8 bg-slate-200"></div>
                @endif
            @endforeach
        </div>

        <div class="mt-16 bg-emerald-50 rounded-2xl p-8 text-center">
            <h3 class="text-xl font-bold text-emerald-900 mb-3">En cas de litige ?</h3>
            <p class="text-emerald-700 mb-6">Si un probleme survient, notre equipe d'arbitrage intervient sous 48h. Preuves et mediation garanties.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                Commencer maintenant
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endsection
"""

pricing = r"""@extends('layouts.app')

@section('title', 'Tarifs')

@section('content')
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-3xl lg:text-4xl font-bold text-slate-900">Nos tarifs</h1>
            <p class="mt-4 text-lg text-slate-500">Simple, transparent, sans frais caches</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Free -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 hover:shadow-xl transition-shadow">
                <h3 class="text-lg font-semibold text-slate-900">Gratuit</h3>
                <p class="text-slate-500 text-sm mt-1">Pour debuter</p>
                <div class="mt-6">
                    <span class="text-4xl font-bold text-slate-900">0%</span>
                    <span class="text-slate-500">commission</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm text-slate-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        1 transaction gratuite
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        KYC standard
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Support email
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block w-full py-3 text-center border-2 border-slate-200 hover:border-emerald-500 text-slate-700 hover:text-emerald-600 font-semibold rounded-xl transition-all">
                    Commencer
                </a>
            </div>

            <!-- Standard -->
            <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-4 right-4 bg-white/20 px-3 py-1 rounded-full text-xs font-medium">Populaire</div>
                <h3 class="text-lg font-semibold">Standard</h3>
                <p class="text-emerald-100 text-sm mt-1">Pour les vendeurs reguliers</p>
                <div class="mt-6">
                    <span class="text-4xl font-bold">3%</span>
                    <span class="text-emerald-100">par transaction</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm text-emerald-100">
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Transactions illimitees
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        KYC prioritaire
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Support WhatsApp
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tableau de bord avance
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block w-full py-3 text-center bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition-all shadow-lg">
                    Choisir Standard
                </a>
            </div>

            <!-- Premium -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 hover:shadow-xl transition-shadow">
                <h3 class="text-lg font-semibold text-slate-900">Premium</h3>
                <p class="text-slate-500 text-sm mt-1">Pour les professionnels</p>
                <div class="mt-6">
                    <span class="text-4xl font-bold text-slate-900">2%</span>
                    <span class="text-slate-500">par transaction</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm text-slate-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tout du Standard
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        API developpeur
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Support telephonique
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Badge vendeur verifie
                    </li>
                </ul>
                <a href="{{ route('contact') }}" class="mt-8 block w-full py-3 text-center border-2 border-slate-200 hover:border-emerald-500 text-slate-700 hover:text-emerald-600 font-semibold rounded-xl transition-all">
                    Nous contacter
                </a>
            </div>
        </div>

        <div class="mt-12 text-center text-sm text-slate-500">
            <p>Frais de retrait: 500 FCFA par operation. Pas de frais mensuels, pas d'engagement.</p>
        </div>
    </div>
</section>
@endsection
"""

about = r"""@extends('layouts.app')

@section('title', 'A propos')

@section('content')
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-3xl lg:text-4xl font-bold text-slate-900">A propos de PayXora</h1>
            <p class="mt-4 text-lg text-slate-500">Securiser le commerce en ligne au Togo</p>
        </div>

        <div class="prose prose-lg prose-slate mx-auto">
            <p class="text-slate-600 leading-relaxed">
                PayXora est ne d'un constat simple: au Togo, 7 internautes sur 10 hesitent a acheter en ligne par peur d'arnaque.
                Les vendeurs individuels manquent de credibilite, et aucun mecanisme escrow n'existait localement pour securiser les echanges P2P.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-12 mb-4">Notre mission</h2>
            <p class="text-slate-600 leading-relaxed">
                Democratiser la confiance dans le e-commerce togolais en proposant une solution de paiement securise (escrow)
                accessible a tous. Que vous soyez vendeur ou acheteur, PayXora garantit que chaque transaction se deroule en toute securite.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-12 mb-4">Comment ca marche ?</h2>
            <p class="text-slate-600 leading-relaxed">
                L'argent de l'acheteur est bloque sur un compte sequestre jusqu'a confirmation de livraison.
                Ni le vendeur ne peut encaisser sans livrer, ni l'acheteur ne peut recevoir sans payer.
                En cas de litige, notre equipe d'arbitrage intervient pour trouver une solution equitable.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-12 mb-4">Nos partenaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                <div class="p-4 bg-slate-50 rounded-xl text-center">
                    <div class="w-12 h-12 mx-auto bg-yellow-400 rounded-lg flex items-center justify-center mb-2">
                        <span class="text-yellow-900 font-bold text-xs">T-Money</span>
                    </div>
                    <p class="text-sm text-slate-600">Togocom</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl text-center">
                    <div class="w-12 h-12 mx-auto bg-blue-500 rounded-lg flex items-center justify-center mb-2">
                        <span class="text-white font-bold text-xs">Moov</span>
                    </div>
                    <p class="text-sm text-slate-600">Moov Africa</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl text-center">
                    <div class="w-12 h-12 mx-auto bg-emerald-600 rounded-lg flex items-center justify-center mb-2">
                        <span class="text-white font-bold text-xs">Ecobank</span>
                    </div>
                    <p class="text-sm text-slate-600">Ecobank</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl text-center">
                    <div class="w-12 h-12 mx-auto bg-teal-600 rounded-lg flex items-center justify-center mb-2">
                        <span class="text-white font-bold text-xs">Orabank</span>
                    </div>
                    <p class="text-sm text-slate-600">Orabank</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
"""

contact = r"""@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-3xl lg:text-4xl font-bold text-slate-900">Contactez-nous</h1>
            <p class="mt-4 text-lg text-slate-500">Une question ? Nous sommes la pour vous aider</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-xl font-bold text-slate-900 mb-6">Nos coordonnees</h2>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">Adresse</p>
                            <p class="text-slate-500">Lome, Togo</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">Email</p>
                            <p class="text-slate-500">contact@payxora.tg</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">Telephone</p>
                            <p class="text-slate-500">+228 90 00 00 00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Envoyer un message</h2>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Message</label>
                        <textarea rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-white resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
"""

with open('/mnt/agents/output/payxora-togo/resources/views/dashboard/profile.blade.php', 'w') as f:
    f.write(profile)

with open('/mnt/agents/output/payxora-togo/resources/views/pages/how-it-works.blade.php', 'w') as f:
    f.write(how_it_works)

with open('/mnt/agents/output/payxora-togo/resources/views/pages/pricing.blade.php', 'w') as f:
    f.write(pricing)

with open('/mnt/agents/output/payxora-togo/resources/views/pages/about.blade.php', 'w') as f:
    f.write(about)

with open('/mnt/agents/output/payxora-togo/resources/views/pages/contact.blade.php', 'w') as f:
    f.write(contact)

print("✅ profile + how-it-works + pricing + about + contact crees (5 fichiers)")
print("📊 Reste: 9 fichiers (Admin 7 + CSS 1 + JS 1)")

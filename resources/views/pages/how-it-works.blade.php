@extends('layouts.app')

@section('title', 'Comment ca marche - PayXora')

@section('content')

<!-- Header -->
<div class="bg-emerald-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-white sm:text-5xl">Comment ca marche ?</h1>
        <p class="mt-4 text-xl text-emerald-200">Le mecanisme escrow en 5 etapes simples et transparentes</p>
    </div>
</div>

<!-- Etapes detaillees -->
<div class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Etape 1 -->
        <div class="relative mb-16">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-2xl bg-emerald-600 text-white text-3xl font-bold shadow-lg">
                        1
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 lg:col-span-10">
                    <h2 class="text-2xl font-bold text-gray-900">Inscription</h2>
                    <p class="mt-3 text-lg text-gray-600">
                        Vendeur et acheteur creent un compte sur PayXora. La verification KYC est legere : numero de telephone, photo de profil, et piece d'identite (passeport, CNI ou permis de conduire). Cette etape garantit l'identite de chaque partie et renforce la confiance des le depart.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-emerald-600">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verification en moins de 24h
                    </div>
                </div>
            </div>
        </div>

        <!-- Fleche -->
        <div class="flex justify-center mb-16">
            <svg class="h-12 w-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>

        <!-- Etape 2 -->
        <div class="relative mb-16">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-2xl bg-emerald-600 text-white text-3xl font-bold shadow-lg">
                        2
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 lg:col-span-10">
                    <h2 class="text-2xl font-bold text-gray-900">Creation de la transaction</h2>
                    <p class="mt-3 text-lg text-gray-600">
                        Le vendeur enregistre la vente sur la plateforme : titre du produit, description detaillee, montant, conditions de livraison, et delai. L'acheteur recoit une notification et peut consulter les details avant d'accepter. Tout est transparent des le depart.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-emerald-600">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Montant minimum : 100 FCFA | Maximum : 10 000 000 FCFA
                    </div>
                </div>
            </div>
        </div>

        <!-- Fleche -->
        <div class="flex justify-center mb-16">
            <svg class="h-12 w-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>

        <!-- Etape 3 -->
        <div class="relative mb-16">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-2xl bg-emerald-600 text-white text-3xl font-bold shadow-lg">
                        3
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 lg:col-span-10">
                    <h2 class="text-2xl font-bold text-gray-900">Paiement bloque</h2>
                    <p class="mt-3 text-lg text-gray-600">
                        L'acheteur paie via Mobile Money (TMoney ou Moov Money). L'argent est immediatement bloque sur un compte sequestre corporate. Ni le vendeur ni l'acheteur ne peut y toucher. PayXora est gestionnaire, pas proprietaire de ces fonds.
                    </p>
                    <div class="mt-4 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-lg">
                        <p class="text-sm text-emerald-800">
                            <strong>Important :</strong> L'argent des clients ne passe JAMAIS sur le compte personnel de l'equipe PayXora. Il transite via un compte sequestre corporate chez une banque partenaire.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fleche -->
        <div class="flex justify-center mb-16">
            <svg class="h-12 w-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>

        <!-- Etape 4 -->
        <div class="relative mb-16">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-2xl bg-emerald-600 text-white text-3xl font-bold shadow-lg">
                        4
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 lg:col-span-10">
                    <h2 class="text-2xl font-bold text-gray-900">Livraison</h2>
                    <p class="mt-3 text-lg text-gray-600">
                        Le vendeur expedie la marchandise via un partenaire de livraison (DHL ou coursier local). Le client recoit et inspecte le produit. La preuve de livraison inclut : photo, signature electronique, et geolocalisation. Le delai de confirmation est de 48h apres livraison.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-emerald-600">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Suivi de colis en temps reel via API
                    </div>
                </div>
            </div>
        </div>

        <!-- Fleche -->
        <div class="flex justify-center mb-16">
            <svg class="h-12 w-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>

        <!-- Etape 5 -->
        <div class="relative">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                <div class="lg:col-span-2 flex justify-center lg:justify-start">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-2xl bg-emerald-600 text-white text-3xl font-bold shadow-lg">
                        5
                    </div>
                </div>
                <div class="mt-6 lg:mt-0 lg:col-span-10">
                    <h2 class="text-2xl font-bold text-gray-900">Deblocage des fonds</h2>
                    <p class="mt-3 text-lg text-gray-600">
                        Le client confirme la reception satisfaisante. L'argent est automatiquement transfere au vendeur (deduction faite de la commission PayXora de 3%). Si le client ne confirme pas dans les 48h, les fonds sont liberes automatiquement.
                    </p>
                    <div class="mt-4 flex items-center text-sm text-emerald-600">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Retrait disponible via Mobile Money ou virement bancaire
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Litiges -->
<div class="bg-red-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-red-100">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold text-red-900">En cas de litige</h2>
                <ul class="mt-4 space-y-3 text-red-800">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-5 w-5 text-red-500 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Délai de confirmation : 48h apres livraison
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-5 w-5 text-red-500 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Non-reception signalee = enquete + preuve de livraison exigee
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-5 w-5 text-red-500 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Mediation interne puis arbitrage si necessaire
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-5 w-5 text-red-500 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Remboursement acheteur si le vendeur ne prouve pas la livraison
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="bg-emerald-700 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">Pret a commencer ?</h2>
        <p class="mt-4 text-lg text-emerald-200">Inscription gratuite. Premiere transaction sans commission.</p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-900 bg-white hover:bg-emerald-50 transition-colors shadow-lg">
                Creer mon compte
            </a>
        </div>
    </div>
</div>

@endsection

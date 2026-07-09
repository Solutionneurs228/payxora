@extends('layouts.app')

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

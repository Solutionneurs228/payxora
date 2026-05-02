@extends('layouts.app')

@section('title', 'A propos - PayXora')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">A propos</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Securiser le commerce en ligne au Togo</p>
        </div>

        <div class="mt-16 max-w-3xl mx-auto">
            <div class="prose prose-indigo prose-lg text-gray-500 mx-auto">
                <p class="text-xl leading-8">
                    PayXora est ne d'un constat simple : au Togo, 7 internautes sur 10 n'achetent pas en ligne par peur d'arnaque. Les vendeurs individuels manquent de credibilite face aux acheteurs. Aucun mecanisme escrow n'existait localement pour securiser les transactions P2P.
                </p>

                <div class="my-12 bg-indigo-50 rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Notre mission</h3>
                    <p class="text-lg">
                        Donner confiance au commerce en ligne au Togo et en Afrique de l'Ouest en bloquant l'argent jusqu'a confirmation de livraison. Ni le vendeur ne peut encaisser sans livrer, ni l'acheteur ne peut recevoir sans payer.
                    </p>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mt-12 mb-4">Pourquoi PayXora ?</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-3"><strong>Pour le vendeur :</strong> Credibilite accrue, argument marketing fort, reduction des impayes</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-3"><strong>Pour l'acheteur :</strong> Argent protege, livraison garantie avant deblocage du paiement</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-indigo-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-3"><strong>Pour le marche :</strong> Acceleration de la digitalisation des transactions</span>
                    </li>
                </ul>

                <div class="my-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Zone de couverture</h3>
                    <p class="text-lg">
                        <strong>Phase 1 :</strong> Togo (Lome, Sokode, Kara)<br>
                        <strong>Phase 2 :</strong> Extension UEMOA (Benin, Cote d'Ivoire, Burkina Faso, Senegal)
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

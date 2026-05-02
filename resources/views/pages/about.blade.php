@extends('layouts.app')

@section('title', 'A propos — PayXora')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900">A propos de PayXora</h1>
        <p class="text-gray-500 mt-3">La confiance au coeur de chaque transaction</p>
    </div>

    <div class="prose prose-indigo mx-auto">
        <p class="text-lg text-gray-600 leading-relaxed">
            PayXora est une plateforme de paiement securise par escrow, nee au Togo avec l'ambition de revolutionner le commerce en ligne en Afrique de l'Ouest.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">Notre mission</h2>
        <p class="text-gray-600 leading-relaxed">
            Eliminer la peur de l'arnaque dans les transactions en ligne. Nous croyons que la confiance est le moteur du commerce digital. En bloquant les fonds jusqu'a confirmation de livraison, nous protegeons a la fois l'acheteur et le vendeur.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">Pourquoi PayXora ?</h2>
        <ul class="space-y-3 text-gray-600">
            <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><strong>Securite totale</strong> — Votre argent est protege jusqu'a confirmation</span>
            </li>
            <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><strong>Mobile Money</strong> — Paiement via TMoney et Moov, sans carte bancaire</span>
            </li>
            <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><strong>Mediation</strong> — Resolution des litiges par notre equipe</span>
            </li>
            <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span><strong>KYC verifie</strong> — Tous les utilisateurs sont identifies</span>
            </li>
        </ul>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">Zone de couverture</h2>
        <p class="text-gray-600 leading-relaxed">
            Phase 1 : Togo<br>
            Phase 2 : Benin, Cote d'Ivoire, Ghana (UEMOA / CEDEAO)
        </p>
    </div>
</div>
@endsection

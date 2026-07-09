@extends('layouts.app')

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

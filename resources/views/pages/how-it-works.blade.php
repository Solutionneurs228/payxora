@extends('layouts.app')

@section('title', 'Comment ca marche')

@section('content')
<div class="gradient-hero py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Le mecanisme Escrow</h1>
        <p class="text-slate-300 text-lg max-w-2xl mx-auto">Comprendre comment PayXora protege vendeurs et acheteurs.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    @php
    $steps = [
        ['num' => '01', 'title' => 'Inscription & KYC', 'desc' => 'Vendeur et acheteur creent un compte avec verification d identite.', 'color' => 'emerald'],
        ['num' => '02', 'title' => 'Creation de la transaction', 'desc' => 'Le vendeur enregistre la vente sur la plateforme.', 'color' => 'blue'],
        ['num' => '03', 'title' => 'Paiement bloque', 'desc' => 'L acheteur paie via Mobile Money. L argent est bloque sur un compte sequestre.', 'color' => 'amber'],
        ['num' => '04', 'title' => 'Livraison', 'desc' => 'Le vendeur expedie la marchandise. Le client recoit et inspecte.', 'color' => 'purple'],
        ['num' => '05', 'title' => 'Deblocage des fonds', 'desc' => 'Le client confirme la reception sous 48h. L argent est transfere au vendeur.', 'color' => 'emerald'],
    ];
    @endphp

    <div class="space-y-12">
        @foreach($steps as $step)
        <div class="flex gap-6 items-start">
            <div class="flex-shrink-0 w-16 h-16 bg-{{ $step['color'] }}-100 rounded-2xl flex items-center justify-center">
                <span class="text-2xl font-bold text-{{ $step['color'] }}-600">{{ $step['num'] }}</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-16 bg-red-50 border border-red-200 rounded-2xl p-8">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <h3 class="text-xl font-bold text-red-800">En cas de litige</h3>
        </div>
        <ul class="space-y-3 text-red-700">
            <li class="flex items-start gap-2"><svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg><span>Delai de confirmation : <strong>48h</strong> apres livraison</span></li>
            <li class="flex items-start gap-2"><svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg><span>Non-reception signalee -> enquete + preuve de livraison exigee</span></li>
            <li class="flex items-start gap-2"><svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg><span>Mediation interne puis arbitrage si necessaire</span></li>
            <li class="flex items-start gap-2"><svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg><span>Remboursement acheteur si le vendeur ne prouve pas la livraison</span></li>
        </ul>
    </div>
</div>
@endsection
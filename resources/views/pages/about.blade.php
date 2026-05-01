@extends('layouts.app')

@section('title', 'A propos')

@section('content')
<div class="gradient-hero py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">A propos de PayXora</h1>
        <p class="text-slate-300 text-lg max-w-2xl mx-auto">Securiser le commerce en ligne au Togo et en Afrique de l Ouest.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="prose prose-lg max-w-none text-slate-600">
        <h2 class="text-2xl font-bold text-slate-900 mb-4">Notre mission</h2>
        <p class="mb-6">Au Togo, pres de 70% des internautes hesitent a acheter en ligne par peur d arnaque. PayXora resout ce probleme fondamental avec un mecanisme d escrow simple et securise.</p>

        <h2 class="text-2xl font-bold text-slate-900 mb-4">Notre vision</h2>
        <p class="mb-6">Devenir la plateforme de reference pour les paiements securises en Afrique de l Ouest (UEMOA), en commencant par le Togo.</p>

        <h2 class="text-2xl font-bold text-slate-900 mb-4">Nos valeurs</h2>
        <div class="grid md:grid-cols-3 gap-6 not-prose mb-8">
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">Confiance</h3>
                <p class="text-sm text-slate-500">La securite de chaque transaction est notre priorite absolue.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">Rapidite</h3>
                <p class="text-sm text-slate-500">Transactions instantanees avec Mobile Money.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">Communaute</h3>
                <p class="text-sm text-slate-500">Construire un ecosysteme de confiance pour le e-commerce.</p>
            </div>
        </div>
    </div>
</div>
@endsection
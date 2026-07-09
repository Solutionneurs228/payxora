@extends('layouts.app')

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

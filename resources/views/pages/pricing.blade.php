@extends('layouts.app')

@section('title', 'Tarifs')

@section('content')
<div class="gradient-hero py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Nos tarifs</h1>
        <p class="text-slate-300 text-lg max-w-2xl mx-auto">Simple, transparent, sans frais caches.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl border border-slate-200 p-8 card-hover">
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Gratuit</h3>
            <div class="text-4xl font-bold text-slate-900 mb-1">0%</div>
            <p class="text-slate-500 text-sm mb-6">Commission</p>
            <ul class="space-y-3 text-sm text-slate-600 mb-8">
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Premiere transaction gratuite</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>KYC standard</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Support email</li>
            </ul>
            <a href="{{ route('register') }}" class="block w-full text-center border border-slate-300 text-slate-700 py-3 rounded-xl font-semibold hover:bg-slate-50 transition">Commencer</a>
        </div>

        <div class="bg-white rounded-2xl border-2 border-emerald-500 p-8 card-hover relative">
            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                <span class="gradient-accent text-white px-4 py-1 rounded-full text-sm font-semibold">Populaire</span>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Standard</h3>
            <div class="text-4xl font-bold text-emerald-600 mb-1">3%</div>
            <p class="text-slate-500 text-sm mb-6">Commission par transaction</p>
            <ul class="space-y-3 text-sm text-slate-600 mb-8">
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Transactions illimitees</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>KYC prioritaire</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Support WhatsApp</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Suivi de livraison</li>
            </ul>
            <a href="{{ route('register') }}" class="block w-full text-center gradient-accent text-white py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-lg shadow-emerald-500/25">Choisir Standard</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-8 card-hover">
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Premium</h3>
            <div class="text-4xl font-bold text-slate-900 mb-1">2%</div>
            <p class="text-slate-500 text-sm mb-6">Commission (abonnement)</p>
            <ul class="space-y-3 text-sm text-slate-600 mb-8">
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Commission reduite</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Badge vendeur verifie</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Support prioritaire 24/7</li>
                <li class="flex items-center gap-2"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>API access</li>
            </ul>
            <a href="{{ route('contact') }}" class="block w-full text-center border border-slate-300 text-slate-700 py-3 rounded-xl font-semibold hover:bg-slate-50 transition">Nous contacter</a>
        </div>
    </div>
    <div class="mt-12 text-center text-sm text-slate-500">
        <p>Commission minimum : 500 FCFA | Commission maximum : 50 000 FCFA</p>
    </div>
</div>
@endsection
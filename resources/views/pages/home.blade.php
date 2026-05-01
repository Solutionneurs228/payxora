@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="gradient-hero text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-emerald-500/20 border border-emerald-500/30 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-emerald-300 text-sm font-medium">Nouveau au Togo</span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6">
                    Achetez en ligne<br>
                    <span class="text-gradient">en toute confiance</span>
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-xl">
                    PayXora securise vos transactions avec un systeme d escrow. L argent est bloque jusqu a confirmation de livraison.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ route('transactions.create') }}" class="gradient-accent text-white px-8 py-4 rounded-xl font-semibold text-center hover:opacity-90 transition shadow-lg shadow-emerald-500/25">Creer une transaction</a>
                    @else
                        <a href="{{ route('register') }}" class="gradient-accent text-white px-8 py-4 rounded-xl font-semibold text-center hover:opacity-90 transition shadow-lg shadow-emerald-500/25">Commencer gratuitement</a>
                    @endauth
                    <a href="{{ route('how-it-works') }}" class="border border-slate-600 text-white px-8 py-4 rounded-xl font-semibold text-center hover:bg-slate-800 transition">Comment ca marche</a>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="animate-float">
                    <svg class="w-full max-w-lg mx-auto" viewBox="0 0 400 300" fill="none">
                        <path d="M200 20L340 80V180C340 240 280 280 200 300C120 280 60 240 60 180V80L200 20Z" fill="rgba(16,185,129,0.1)" stroke="#10B981" stroke-width="3"/>
                        <path d="M200 60L300 105V175C300 220 255 250 200 270C145 250 100 220 100 175V105L200 60Z" fill="rgba(16,185,129,0.2)"/>
                        <path d="M160 150L185 175L240 120" stroke="#10B981" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="120" cy="200" r="25" fill="#F59E0B" opacity="0.8"/>
                        <circle cx="280" cy="200" r="25" fill="#F59E0B" opacity="0.8"/>
                        <circle cx="200" cy="220" r="30" fill="#F59E0B" opacity="0.9"/>
                        <rect x="180" y="100" width="40" height="35" rx="4" fill="#1E3A5F" stroke="#10B981" stroke-width="2"/>
                        <path d="M190 100V90C190 84.477 194.477 80 200 80C205.523 80 210 84.477 210 90V100" stroke="#10B981" stroke-width="2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-white py-12 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div><div class="text-3xl font-bold text-slate-900">{{ $stats['total_transactions'] ?? '0' }}</div><div class="text-sm text-slate-500 mt-1">Transactions</div></div>
            <div><div class="text-3xl font-bold text-emerald-600">{{ $stats['completed_transactions'] ?? '0' }}</div><div class="text-sm text-slate-500 mt-1">Terminees</div></div>
            <div><div class="text-3xl font-bold text-slate-900">{{ $stats['active_users'] ?? '0' }}</div><div class="text-sm text-slate-500 mt-1">Utilisateurs</div></div>
            <div><div class="text-3xl font-bold text-emerald-600">0</div><div class="text-sm text-slate-500 mt-1">Litiges</div></div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Comment ca marche</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">5 etapes simples pour securiser votre transaction</p>
        </div>
        <div class="grid md:grid-cols-5 gap-6">
            @php
            $steps = [
                ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Inscription', 'desc' => 'Creez votre compte avec KYC'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Creation', 'desc' => 'Le vendeur cree la transaction'],
                ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Paiement', 'desc' => 'L acheteur paie via Mobile Money'],
                ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Livraison', 'desc' => 'Le vendeur expedie la marchandise'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Liberation', 'desc' => 'L argent est transfere au vendeur'],
            ];
            @endphp
            @foreach($steps as $index => $step)
            <div class="relative text-center card-hover bg-white rounded-2xl p-6 border border-slate-200">
                <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/></svg>
                </div>
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-bold mx-auto mb-3 -mt-12 ml-auto mr-0 relative z-10 border-4 border-white">{{ $index + 1 }}</div>
                <h3 class="font-semibold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-slate-500">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="gradient-hero py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Pret a securiser vos transactions ?</h2>
        <p class="text-slate-300 mb-8 text-lg">Rejoignez PayXora et faites partie de la revolution du e-commerce securise au Togo.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('transactions.create') }}" class="gradient-accent text-white px-8 py-4 rounded-xl font-semibold hover:opacity-90 transition shadow-lg shadow-emerald-500/25">Creer ma premiere transaction</a>
            @else
                <a href="{{ route('register') }}" class="gradient-accent text-white px-8 py-4 rounded-xl font-semibold hover:opacity-90 transition shadow-lg shadow-emerald-500/25">Creer un compte gratuit</a>
            @endauth
        </div>
    </div>
</section>
@endsection
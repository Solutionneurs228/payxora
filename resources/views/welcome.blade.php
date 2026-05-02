@extends('layouts.app')

@section('title', 'PayXora — Paiement Sécurisé au Togo')

@section('content')
    <x-landing.hero />
    <x-landing.how-it-works />
    <x-landing.features />
    <x-landing.trust />
    <x-landing.pricing />
    <x-landing.cta />
    <x-landing.footer />
@endsection

@push('styles')
    <style>
        /* Animations discrètes */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.6s ease forwards;
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
    </style>
@endpush

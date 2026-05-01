<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PayXora') — Paiement Securise Escrow au Togo</title>
    <meta name="description" content="PayXora : La plateforme de paiement securise (escrow) au Togo. Protegez vos transactions en ligne avec Mobile Money.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-hero { background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #0F172A 100%); }
        .gradient-accent { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .text-gradient { background: linear-gradient(135deg, #10B981 0%, #34D399 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    @include('components.navbar')
    <x-flash-messages />
    <main>@yield('content')</main>
    @include('components.footer')
    @stack('scripts')
</body>
</html>
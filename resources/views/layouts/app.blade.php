<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PayXora Togo - Plateforme de paiement securise par escrow. Achetez et vendez en toute confiance au Togo.">
    <title>@yield('title', 'PayXora Togo') — Paiement Securise</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/payxora.css') }}">

    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">

    <!-- Navigation -->
    <x-navbar />

    <!-- Flash Messages -->
    <x-flash-messages />

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Scripts -->
    <script src="{{ asset('js/payxora.js') }}" defer></script>
    @stack('scripts')

    <!-- Livewire (si utilise) -->
    @livewireScripts
</body>
</html>

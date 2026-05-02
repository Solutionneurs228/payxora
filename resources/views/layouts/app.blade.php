<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PayXora - Plateforme de paiement securise par escrow au Togo. Protegez vos transactions en ligne.">

    <title>@yield('title', 'PayXora - Paiement Securise au Togo')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        body { font-family: 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .slide-in { animation: slideIn 0.4s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Flash Messages -->
        @if(session('success') || session('error') || session('info') || session('warning'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md fade-in" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md fade-in" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(session('info'))
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md fade-in" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-1">
                        <h3 class="text-lg font-bold text-indigo-600">PayXora</h3>
                        <p class="mt-2 text-sm text-gray-500">Paiement securise par escrow au Togo. Protegez vos transactions en ligne.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Navigation</h4>
                        <ul class="mt-4 space-y-2">
                            <li><a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Accueil</a></li>
                            <li><a href="{{ route('how-it-works') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Comment ca marche</a></li>
                            <li><a href="{{ route('pricing') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Tarifs</a></li>
                            <li><a href="{{ route('about') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">A propos</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Legal</h4>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Conditions d'utilisation</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Politique de confidentialite</a></li>
                            <li><a href="#" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Politique de remboursement</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Contact</h4>
                        <ul class="mt-4 space-y-2">
                            <li class="text-sm text-gray-500">Lome, Togo</li>
                            <li><a href="mailto:contact@payxora.tg" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">contact@payxora.tg</a></li>
                            <li class="text-sm text-gray-500">+228 XX XX XX XX</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-400">&copy; {{ date('Y') }} PayXora. Tous droits reserves.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>

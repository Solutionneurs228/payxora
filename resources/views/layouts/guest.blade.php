<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PayXora')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/payxora.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 min-h-screen">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
        <!-- Logo -->
        <div class="mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @include('components.application-logo')
            </a>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
            @yield('content')
        </div>

        <!-- Footer links -->
        <div class="mt-6 text-center text-white/60 text-sm">
            <p>&copy; {{ date('Y') }} PayXora. Tous droits reserves.</p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

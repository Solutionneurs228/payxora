@extends('layouts.guest')

@section('title', 'Connexion — PayXora')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Connexion</h1>
        <p class="text-gray-500 mt-1">Accedez a votre compte PayXora</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mot de passe avec toggle --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <div class="relative">
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('password') border-red-500 @enderror">

                <button type="button" onclick="togglePassword('password', this)"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none z-10">
                    <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Se souvenir de moi + Mot de passe oublié --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Se souvenir de moi</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">Mot de passe oublie ?</a>
        </div>

        {{-- Bouton submit --}}
        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            Se connecter
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:text-indigo-800 transition">S'inscrire</a>
        </p>
    </div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endpush

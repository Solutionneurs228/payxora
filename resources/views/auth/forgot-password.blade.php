@extends('layouts.guest')

@section('title', 'Mot de passe oublie — PayXora')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mot de passe oublie ?</h1>
        <p class="text-gray-500 mt-1">Entrez votre email pour recevoir un lien de reinitialisation</p>
    </div>

    {{-- Message de succès --}}
    @if(session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-green-800 font-medium">{{ session('status') }}</p>
                    <p class="text-green-600 text-sm mt-1">Verifiez votre boite de reception (et vos spams) pour trouver le lien.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour a la connexion
            </a>
        </div>
    @else
        {{-- Formulaire --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Envoyer le lien
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">Retour a la connexion</a>
        </div>
    @endif
@endsection
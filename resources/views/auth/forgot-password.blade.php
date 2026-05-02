@extends('layouts.guest')

@section('title', 'Mot de passe oublie - PayXora')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2">
                <svg class="h-10 w-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span class="text-2xl font-bold text-gray-900">PayXora</span>
            </a>
            <p class="mt-2 text-sm text-gray-600">Reinitialiser votre mot de passe</p>
        </div>

        @if(session('status'))
            <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-md">
                <p class="text-sm text-emerald-700">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm
                           @error('email') border-red-300 @enderror"
                           placeholder="votre@email.tg">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    Envoyer le lien de reinitialisation
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-500">
                    Retour a la connexion
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

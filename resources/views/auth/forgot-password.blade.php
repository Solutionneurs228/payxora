@extends('layouts.guest')

@section('title', 'Mot de passe oublie — PayXora')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mot de passe oublie ?</h1>
        <p class="text-gray-500 mt-1">Entrez votre email pour recevoir un lien de reinitialisation</p>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

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
@endsection

@extends('layouts.guest')

@section('title', 'Reinitialiser le mot de passe — PayXora')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Nouveau mot de passe</h1>
        <p class="text-gray-500 mt-1">Choisissez un nouveau mot de passe securise</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}" required readonly
                class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-gray-600">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" required autofocus
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('password') border-red-500 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            Reinitialiser le mot de passe
        </button>
    </form>
@endsection

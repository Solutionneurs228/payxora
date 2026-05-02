@extends('layouts.app')

@section('title', 'Inscription - PayXora')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Creer votre compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    connectez-vous a votre compte existant
                </a>
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Il y a {{ $errors->count() }} erreur(s) dans le formulaire
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="name" class="sr-only">Nom complet</label>
                    <input id="name" name="name" type="text" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Nom complet" value="{{ old('name') }}">
                </div>
                <div>
                    <label for="email" class="sr-only">Adresse email</label>
                    <input id="email" name="email" type="email" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Adresse email" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="phone" class="sr-only">Telephone</label>
                    <input id="phone" name="phone" type="tel" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Telephone (+228...)" value="{{ old('phone') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input id="password" name="password" type="password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Mot de passe (8+ caracteres, majuscule, chiffre, symbole)">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Confirmer le mot de passe">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Type de compte</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center">
                        <input id="role-buyer" name="role" type="radio" value="buyer" required
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                            {{ old('role') === 'buyer' ? 'checked' : '' }}>
                        <label for="role-buyer" class="ml-3 block text-sm font-medium text-gray-700">
                            Acheteur - Je veux acheter en toute securite
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="role-seller" name="role" type="radio" value="seller" required
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                            {{ old('role') === 'seller' ? 'checked' : '' }}>
                        <label for="role-seller" class="ml-3 block text-sm font-medium text-gray-700">
                            Vendeur - Je veux vendre et etre paye en securite
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    J'accepte les <a href="#" class="text-indigo-600 hover:text-indigo-500">conditions d'utilisation</a>
                    et la <a href="#" class="text-indigo-600 hover:text-indigo-500">politique de confidentialite</a>
                </label>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

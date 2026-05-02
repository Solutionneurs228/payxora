@extends('layouts.app')

@section('title', 'Verification Email - PayXora')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verifiez votre email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Un lien de verification a ete envoye a <strong>{{ auth()->user()->email }}</strong>
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (app()->environment('local', 'testing'))
            <div class="rounded-md bg-yellow-50 p-4 border border-yellow-200">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Mode Developpement</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>En production, un email reel serait envoye via Brevo.</p>
                            <p class="mt-2">Pour tester sans email :</p>
                            <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="text-yellow-800 underline font-medium">
                                    Cliquez ici pour verifier automatiquement
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-center">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="text-indigo-600 hover:text-indigo-500 font-medium text-sm">
                    Renvoyer l'email de verification
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                    Deconnexion
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

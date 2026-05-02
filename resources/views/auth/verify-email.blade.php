@extends('layouts.guest')

@section('title', 'Verifier votre email - PayXora')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Verifiez votre email</h2>
            <p class="mt-2 text-sm text-gray-600">
                Merci de vous etre inscrit ! Un lien de verification a ete envoye a votre adresse email. Cliquez dessus pour activer votre compte.
            </p>
        </div>

        @if(session('status') === 'verification-link-sent')
            <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-md">
                <p class="text-sm text-emerald-700">Un nouveau lien de verification a ete envoye a l'adresse email que vous avez fournie lors de l'inscription.</p>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    Renvoyer l'email de verification
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    Se deconnecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

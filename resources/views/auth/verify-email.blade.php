@extends('layouts.guest')

@section('title', 'Verifier votre email — PayXora')

@section('content')
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Verifiez votre email</h1>
        <p class="text-gray-500 mt-2">Un lien de verification a ete envoye a <strong>{{ auth()->user()->email }}</strong></p>
    </div>

    @if(session('status') === 'verification-link-sent')
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg text-sm">
            Un nouveau lien de verification a ete envoye.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition">
                Renvoyer le lien
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-gray-500 text-sm hover:text-gray-700 transition">
                Deconnexion
            </button>
        </form>
    </div>
@endsection

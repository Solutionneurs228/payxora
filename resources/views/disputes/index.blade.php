@extends('layouts.app')

@section('title', 'Mes Litiges - PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Litiges</h1>
        <p class="mt-2 text-gray-600">Gerez vos litiges en cours et passes.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($disputes->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul class="divide-y divide-gray-200">
                @foreach($disputes as $dispute)
                    <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        Litige #{{ $dispute->id }}
                                    </p>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($dispute->status === 'open') bg-yellow-100 text-yellow-800
                                        @elseif($dispute->status === 'mediation') bg-blue-100 text-blue-800
                                        @elseif($dispute->status === 'resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($dispute->status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Transaction: {{ $dispute->transaction->title ?? 'N/A' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Motif: {{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-400">
                                    Ouvert le {{ $dispute->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <a href="{{ route('disputes.show', $dispute) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    Voir le litige
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-6">
            {{ $disputes->links() }}
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun litige</h3>
            <p class="mt-1 text-sm text-gray-500">Vous n'avez aucun litige en cours.</p>
            <div class="mt-6">
                <a href="{{ route('transactions.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Voir mes transactions
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

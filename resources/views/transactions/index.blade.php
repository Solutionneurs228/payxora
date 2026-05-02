@extends('layouts.app')

@section('title', 'Mes transactions — PayXora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mes transactions</h1>
        <a href="{{ route('transactions.create') }}" class="bg-indigo-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-md">
            + Nouvelle transaction
        </a>
    </div>

    <div class="space-y-4">
        @forelse($transactions as $transaction)
            <x-transaction-card :transaction="$transaction" />
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucune transaction</h3>
                <p class="text-gray-500 mb-4">Commencez par creer votre premiere transaction securisee</p>
                <a href="{{ route('transactions.create') }}" class="inline-block bg-indigo-600 text-white font-semibold px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Creer une transaction
                </a>
            </div>
        @endforelse
    </div>

    @if($transactions->hasPages())
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection

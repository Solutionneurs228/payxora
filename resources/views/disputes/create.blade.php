@extends('layouts.app')

@section('title', 'Ouvrir un litige — PayXora')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-orange-600">
            <h1 class="text-xl font-bold text-white">Ouvrir un litige</h1>
            <p class="text-red-100 text-sm mt-1">{{ $transaction->product_name }}</p>
        </div>

        <form method="POST" action="{{ route('disputes.store') }}" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Motif du litige</label>
                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" required
                    placeholder="Ex: Produit non conforme a la description"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('reason') border-red-500 @enderror">
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description detaillee</label>
                <textarea name="description" id="description" rows="5" required
                    placeholder="Decrivez le probleme en detail..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-700">
                    <strong>Attention :</strong> L'ouverture d'un litige bloque les fonds en sequestre. Notre equipe examinera votre demande dans les plus brefs delais.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="flex-1 bg-red-600 text-white font-semibold py-3 rounded-lg hover:bg-red-700 transition shadow-lg shadow-red-200">
                    Ouvrir le litige
                </button>
                <a href="{{ route('transactions.show', $transaction) }}" class="px-6 py-3 text-gray-500 hover:text-gray-700 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

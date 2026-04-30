@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux transactions
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Nouvelle transaction</h1>
            <p class="text-slate-500">Creez une vente securisee pour votre client</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="product_name" class="block text-sm font-medium text-slate-700 mb-1">Nom du produit / service</label>
                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white"
                        placeholder="Ex: iPhone 14 Pro 256Go">
                </div>

                <div>
                    <label for="product_description" class="block text-sm font-medium text-slate-700 mb-1">Description (optionnel)</label>
                    <textarea name="product_description" id="product_description" rows="3"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none"
                        placeholder="Details du produit, etat, couleur..."></textarea>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-1">Montant (FCFA)</label>
                    <div class="relative">
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="1000" step="100"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white pr-16"
                            placeholder="450000">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">FCFA</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Minimum 1 000 FCFA. Commission: 3%</p>
                </div>

                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-slate-700 mb-1">Adresse de livraison</label>
                    <textarea name="shipping_address" id="shipping_address" rows="2" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none"
                        placeholder="Quartier, rue, point de repere..."></textarea>
                </div>

                <div>
                    <label for="seller_notes" class="block text-sm font-medium text-slate-700 mb-1">Notes pour l'acheteur (optionnel)</label>
                    <textarea name="seller_notes" id="seller_notes" rows="2"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none"
                        placeholder="Delai de livraison, conditions speciales..."></textarea>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-emerald-800">Comment ca marche ?</p>
                            <p class="text-sm text-emerald-700 mt-1">Une fois creee, partagez le lien avec votre acheteur. Il paiera via Mobile Money et les fonds seront bloques jusqu'a confirmation de livraison.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40 transition-all transform hover:-translate-y-0.5">
                    Creer la transaction
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

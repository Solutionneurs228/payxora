@extends('layouts.app')

@section('title', 'Nouvelle transaction — PayXora')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
            <h1 class="text-xl font-bold text-white">Nouvelle transaction</h1>
            <p class="text-indigo-100 text-sm mt-1">Decrivez votre produit et definissez le prix</p>
        </div>

        <form method="POST" action="{{ route('transactions.store') }}" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du produit / service</label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                    placeholder="Ex: iPhone 14 Pro 256Go"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('product_name') border-red-500 @enderror">
                @error('product_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="product_description" class="block text-sm font-medium text-gray-700 mb-1">Description (optionnel)</label>
                <textarea name="product_description" id="product_description" rows="3"
                    placeholder="Decrivez le produit, son etat, les conditions de livraison..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('product_description') border-red-500 @enderror">{{ old('product_description') }}</textarea>
                @error('product_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="1000" step="100"
                        placeholder="50000"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('amount') border-red-500 @enderror">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
                    <select name="currency" id="currency"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="XOF" selected>XOF (FCFA)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse de livraison</label>
                <textarea name="shipping_address" id="shipping_address" rows="2" required
                    placeholder="Quartier, ville, instructions de livraison..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address') }}</textarea>
                @error('shipping_address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="seller_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes pour l'acheteur (optionnel)</label>
                <textarea name="seller_notes" id="seller_notes" rows="2"
                    placeholder="Conditions particulieres, delais..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ old('seller_notes') }}</textarea>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Commission PayXora ({{ config('payxora.commission_rate') }}%)</span>
                    <span class="font-medium text-gray-900" id="commission-display">0 FCFA</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-2 pt-2 border-t border-gray-200">
                    <span class="font-medium text-gray-900">Vous recevrez</span>
                    <span class="font-bold text-indigo-600" id="net-amount-display">0 FCFA</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    Creer la transaction
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 text-gray-500 hover:text-gray-700 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    const rate = {{ config('payxora.commission_rate') }};
    const commission = Math.min(Math.max(amount * (rate / 100), {{ config('payxora.commission_minimum') }}), {{ config('payxora.commission_maximum') }});
    const net = amount - commission;
    
    document.getElementById('commission-display').textContent = commission.toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('net-amount-display').textContent = net.toLocaleString('fr-FR') + ' FCFA';
});
</script>
@endsection

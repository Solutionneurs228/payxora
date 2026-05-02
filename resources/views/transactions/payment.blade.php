@extends('layouts.app')

@section('title', 'Paiement — PayXora')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
            <h1 class="text-xl font-bold text-white">Paiement securise</h1>
            <p class="text-green-100 text-sm mt-1">{{ $transaction->product_name }}</p>
        </div>

        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-gray-500 text-sm">Montant a payer</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400 mt-1">Reference: {{ $transaction->reference }}</p>
            </div>

            <form method="POST" action="{{ route('payment.mobile-money', $transaction) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Operateur Mobile Money</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="provider" value="tmoney" class="peer sr-only" required>
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition hover:border-indigo-300">
                                <div class="w-10 h-10 bg-blue-600 rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold text-xs">T</div>
                                <span class="text-sm font-medium text-gray-700">TMoney</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="provider" value="moov" class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition hover:border-indigo-300">
                                <div class="w-10 h-10 bg-orange-500 rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold text-xs">M</div>
                                <span class="text-sm font-medium text-gray-700">Moov</span>
                            </div>
                        </label>
                    </div>
                    @error('provider')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Numero de telephone</label>
                    <input type="tel" name="phone_number" id="phone_number" required placeholder="90000000"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Mode developpement</p>
                            <p class="text-xs text-yellow-700 mt-1">Le paiement est simule. En production, une notification USSD sera envoyee sur votre telephone pour confirmer.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    Confirmer le paiement
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

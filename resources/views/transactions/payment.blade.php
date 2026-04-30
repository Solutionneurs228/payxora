@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-lg mx-auto">
        <div class="mb-8 text-center">
            <div class="w-16 h-16 mx-auto bg-emerald-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Paiement securise</h1>
            <p class="text-slate-500">Transaction: {{ $transaction->reference }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <!-- Transaction Summary -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-emerald-100">Produit</span>
                    <span class="font-medium">{{ Str::limit($transaction->product_name, 30) }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-emerald-100">Montant</span>
                    <span class="font-bold text-xl">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-white/20">
                    <span class="text-emerald-100">Frais (1%)</span>
                    <span class="font-medium">{{ number_format($transaction->amount * 0.01, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-emerald-100">Total a payer</span>
                    <span class="font-bold text-2xl">{{ number_format($transaction->amount * 1.01, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Choisir un mode de paiement</h3>

                <form method="POST" action="{{ route('payment.mobile-money', $transaction) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="tmoney" class="peer sr-only" checked>
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all text-center">
                                <div class="w-12 h-12 mx-auto mb-2 bg-yellow-400 rounded-lg flex items-center justify-center">
                                    <span class="text-yellow-900 font-bold text-xs">T-Money</span>
                                </div>
                                <span class="text-sm font-medium text-slate-700 peer-checked:text-emerald-700">T-Money</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="moov" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all text-center">
                                <div class="w-12 h-12 mx-auto mb-2 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xs">Moov</span>
                                </div>
                                <span class="text-sm font-medium text-slate-700 peer-checked:text-emerald-700">Moov Money</span>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Numero Mobile Money</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">+228</span>
                            <input type="tel" name="phone" id="phone" required
                                class="w-full pl-14 pr-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white"
                                placeholder="90 00 00 00">
                        </div>
                        <p class="mt-1 text-xs text-slate-400">Vous recevrez une demande de confirmation sur ce numero</p>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-amber-800">Paiement securise</p>
                            <p class="text-sm text-amber-700">Vos fonds seront bloques dans un compte sequestre jusqu'a confirmation de livraison.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 transition-all transform hover:-translate-y-0.5 text-lg">
                        Payer {{ number_format($transaction->amount * 1.01, 0, ',', ' ') }} FCFA
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-sm text-slate-400 mt-6">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Transaction securisee par PayXora Escrow
        </p>
    </div>
</section>
@endsection

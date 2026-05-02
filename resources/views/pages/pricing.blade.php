@extends('layouts.app')

@section('title', 'Tarifs - PayXora')

@section('content')

<!-- Header -->
<div class="bg-emerald-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-extrabold text-white sm:text-5xl">Nos tarifs</h1>
        <p class="mt-4 text-xl text-emerald-200">Simple et transparent. Pas de frais caches.</p>
    </div>
</div>

<!-- Pricing Cards -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">

            <!-- Standard -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-900">Standard</h3>
                    <p class="mt-2 text-gray-500">Parfait pour demarrer</p>
                    <div class="mt-6">
                        <span class="text-5xl font-extrabold text-gray-900">3%</span>
                        <span class="text-gray-500">/transaction</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-400">+ 500 FCFA de frais de retrait</p>
                </div>
                <div class="border-t border-gray-100 p-8">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Protection escrow</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">KYC legere</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Paiement Mobile Money</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Support par email</span>
                        </li>
                    </ul>
                    <div class="mt-8">
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 border border-emerald-600 text-emerald-600 font-medium rounded-lg hover:bg-emerald-50 transition-colors">
                            Choisir Standard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Premium -->
            <div class="bg-white rounded-2xl shadow-lg border-2 border-emerald-500 overflow-hidden relative">
                <div class="absolute top-0 right-0 bg-emerald-500 text-white px-4 py-1 text-sm font-medium rounded-bl-lg">Populaire</div>
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-900">Premium</h3>
                    <p class="mt-2 text-gray-500">Pour les vendeurs actifs</p>
                    <div class="mt-6">
                        <span class="text-5xl font-extrabold text-emerald-600">2%</span>
                        <span class="text-gray-500">/transaction</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-400">+ 500 FCFA de frais de retrait</p>
                </div>
                <div class="border-t border-gray-100 p-8">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Tout du plan Standard</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Commission reduite</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Support prioritaire</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Badge vendeur verifie</span>
                        </li>
                    </ul>
                    <div class="mt-8">
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-md">
                            Choisir Premium
                        </a>
                    </div>
                </div>
            </div>

            <!-- Entreprise -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-900">Entreprise</h3>
                    <p class="mt-2 text-gray-500">Pour les grandes structures</p>
                    <div class="mt-6">
                        <span class="text-3xl font-extrabold text-gray-900">Sur mesure</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-400">Contactez-nous pour un devis</p>
                </div>
                <div class="border-t border-gray-100 p-8">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Commission negociable</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Integration API</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">Account manager dedie</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600">White-label possible</span>
                        </li>
                    </ul>
                    <div class="mt-8">
                        <a href="{{ route('contact') }}" class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details -->
<div class="bg-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-12">Details des frais</h2>

        <div class="bg-gray-50 rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-900">Frais</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Commission Standard</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right">3% du montant</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Commission Premium</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right">2% du montant</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Frais de retrait</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right">500 FCFA</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Montant minimum</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right">100 FCFA</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Montant maximum</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right">10 000 000 FCFA</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Inscription</td>
                        <td class="px-6 py-4 text-sm text-emerald-600 font-medium text-right">Gratuite</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-600">Premiere transaction</td>
                        <td class="px-6 py-4 text-sm text-emerald-600 font-medium text-right">0% commission</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-8 bg-emerald-50 border border-emerald-200 rounded-xl p-6">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-emerald-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-emerald-900">Exemple de calcul</h3>
                    <p class="mt-2 text-emerald-800">
                        Pour une transaction de <strong>50 000 FCFA</strong> en plan Standard :<br>
                        Commission PayXora (3%) = 1 500 FCFA<br>
                        Le vendeur recoit = 48 500 FCFA<br>
                        Frais de retrait = 500 FCFA<br>
                        <strong>Net au vendeur = 48 000 FCFA</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="bg-emerald-700 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white">Premiere transaction gratuite</h2>
        <p class="mt-4 text-lg text-emerald-200">0% de commission sur votre premiere vente. Testez sans risque.</p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-900 bg-white hover:bg-emerald-50 transition-colors shadow-lg">
                Commencer gratuitement
            </a>
        </div>
    </div>
</div>

@endsection

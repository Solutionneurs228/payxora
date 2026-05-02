@extends('layouts.app')

@section('title', 'Contact - PayXora')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Contact</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Parlons de votre projet</p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">Une question ? Un partenariat ? Contactez-nous.</p>
        </div>

        <div class="mt-16 max-w-lg mx-auto lg:max-w-none lg:grid lg:grid-cols-2 lg:gap-8">
            <!-- Contact Form -->
            <div class="bg-gray-50 rounded-lg p-8">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Envoyez-nous un message</h3>
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="contact-name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="name" id="contact-name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="contact-email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="contact-email" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="contact-subject" class="block text-sm font-medium text-gray-700">Sujet</label>
                        <select name="subject" id="contact-subject" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Selectionnez...</option>
                            <option value="general">Question generale</option>
                            <option value="partnership">Partenariat</option>
                            <option value="support">Support technique</option>
                            <option value="investor">Investisseur</option>
                        </select>
                    </div>
                    <div>
                        <label for="contact-message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" id="contact-message" rows="4" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Envoyer
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="mt-12 lg:mt-0 space-y-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Coordonnees</h3>
                    <dl class="mt-4 space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-500">Lome, Togo</dd>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">Email</dt>
                                <dd class="mt-1 text-sm text-gray-500">contact@payxora.tg</dd>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">Telephone</dt>
                                <dd class="mt-1 text-sm text-gray-500">+228 XX XX XX XX</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900">Heures d'ouverture</h3>
                    <div class="mt-4 text-sm text-gray-500">
                        <p>Lundi - Vendredi : 8h00 - 18h00</p>
                        <p>Samedi : 9h00 - 14h00</p>
                        <p>Dimanche : Ferme</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

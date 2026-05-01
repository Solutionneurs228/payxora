@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<div class="gradient-hero py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Contactez-nous</h1>
        <p class="text-slate-300 text-lg max-w-2xl mx-auto">Une question ? Notre equipe est la pour vous aider.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 gap-12">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Informations de contact</h2>
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div><h3 class="font-semibold text-slate-900">Email</h3><p class="text-slate-600">contact@payxora.tg</p></div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div><h3 class="font-semibold text-slate-900">Telephone</h3><p class="text-slate-600">+228 XX XX XX XX</p></div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div><h3 class="font-semibold text-slate-900">Adresse</h3><p class="text-slate-600">Lome, Togo</p></div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-8">
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div><label class="block text-sm font-medium text-slate-700 mb-1">Nom</label><input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1">Email</label><input type="email" name="email" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1">Sujet</label><select name="subject" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"><option>Question generale</option><option>Support technique</option><option>Partenariat</option><option>Autre</option></select></div>
                <div><label class="block text-sm font-medium text-slate-700 mb-1">Message</label><textarea name="message" rows="4" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required></textarea></div>
                <button type="submit" class="w-full gradient-accent text-white py-3 rounded-xl font-semibold hover:opacity-90 transition">Envoyer le message</button>
            </form>
        </div>
    </div>
</div>
@endsection
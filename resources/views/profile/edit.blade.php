@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<section class="py-8 px-4 sm:px-6 lg:px-8 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Mon profil</h1>
            <p class="text-slate-500">Gerer vos informations personnelles</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                        {{ auth()->user()->initials }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ auth()->user()->full_name }}</h2>
                        <p class="text-emerald-100">{{ auth()->user()->email }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white/20">
                                {{ auth()->user()->role === 'seller' ? 'Vendeur' : 'Acheteur' }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->isKycVerified() ? 'bg-emerald-400/30' : 'bg-amber-400/30' }}">
                                {{ auth()->user()->isKycVerified() ? 'KYC Verifie' : 'KYC En attente' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nom complet</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Telephone</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">+228</span>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required
                                class="w-full pl-14 pr-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Adresse</label>
                        <textarea name="address" rows="2"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white resize-none">{{ auth()->user()->address }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ville</label>
                        <input type="text" name="city" value="{{ auth()->user()->city }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                        Mettre a jour le profil
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Changer le mot de passe</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe actuel</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all bg-slate-50 focus:bg-white">
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl shadow-lg transition-all">
                    Changer le mot de passe
                </button>
            </form>
        </div>
    </div>
</section>
@endsection

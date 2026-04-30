@extends('layouts.app')

@section('title', 'Verification en cours')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto bg-emerald-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-3">Documents soumis !</h2>
        <p class="text-slate-500 mb-8">Votre verification KYC est en cours d'examen. Notre equipe valide vos documents sous 24h maximum.</p>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="font-semibold text-slate-800">Documents recus</p>
                    <p class="text-sm text-emerald-600">Valide</p>
                </div>
            </div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center animate-pulse">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="font-semibold text-slate-800">Verification en cours</p>
                    <p class="text-sm text-amber-600">En attente...</p>
                </div>
            </div>
            <div class="flex items-center gap-4 opacity-50">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="font-semibold text-slate-800">Acces complet</p>
                    <p class="text-sm text-slate-400">Bientot disponible</p>
                </div>
            </div>
        </div>

        <p class="text-sm text-slate-400">Vous serez notifie par email des que votre compte sera valide.</p>
    </div>
</section>
@endsection

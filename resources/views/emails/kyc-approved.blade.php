@extends('layouts.app')

@section('title', 'KYC approuvé')

@section('content')
    <div class="container">
        <div class="header">
            <h1>🎉 Vérification KYC approuvée !</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            
            <p>Votre vérification d'identité a été <strong>approuvée</strong> avec succès.</p>
            
            <p>Vous avez maintenant accès à toutes les fonctionnalités de PayXora :</p>
            
            <ul style="color: #374151; line-height: 1.8; padding-left: 20px;">
                <li>Créer des transactions sans limite</li>
                <li>Effectuer des paiements sécurisés</li>
                <li>Retirer vos fonds</li>
            </ul>
            
            <a href="{{ route('dashboard') }}" class="btn">Accéder à mon tableau de bord</a>
            
            <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
                Cet email a été envoyé automatiquement par PayXora.<br>
                Si vous avez des questions, contactez notre support.
            </p>
        </div>
    </div>
@endsection
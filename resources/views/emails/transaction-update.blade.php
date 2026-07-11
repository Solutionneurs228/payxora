@extends('layouts.app')

@section('title', 'Mise à jour transaction')

@section('content')
    <div class="container">
        <div class="header">
            <h1>📢 Mise à jour de votre transaction</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name ?? 'Utilisateur' }},</p>
            
            <p>Votre transaction <strong>{{ $transaction->title ?? 'N/A' }}</strong> a été mise à jour.</p>
            
            <div class="details">
                <p><strong>Statut :</strong> <span style="color: #4f46e5; font-weight: bold;">{{ $type ?? 'Mis à jour' }}</span></p>
                <p><strong>Montant :</strong> {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                <p><strong>Référence :</strong> {{ $transaction->reference ?? 'N/A' }}</p>
            </div>
            
            <a href="{{ route('transactions.show', $transaction) }}" class="btn">Voir la transaction</a>
            
            <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
                Cet email a été envoyé automatiquement par PayXora.<br>
                Si vous avez des questions, contactez notre support.
            </p>
        </div>
    </div>
@endsection
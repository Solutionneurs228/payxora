@extends('layouts.app')

@section('title', 'Paiement libéré')

@section('content')
    <div class="container">
        <div class="header">
            <h1>✅ Paiement libéré !</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name ?? 'Utilisateur' }},</p>
            
            <p>Le paiement pour votre transaction <strong>{{ $transaction->title ?? 'N/A' }}</strong> a été libéré avec succès.</p>
            
            <div class="details">
                <p><strong>Montant :</strong> <span class="amount">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span></p>
                <p><strong>Référence :</strong> {{ $transaction->reference ?? 'N/A' }}</p>
            </div>
            
            <p>Les fonds sont maintenant disponibles sur votre compte.</p>
            
            <a href="{{ route('transactions.show', $transaction) }}" class="btn">Voir la transaction</a>
            
            <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
                Cet email a été envoyé automatiquement par PayXora.<br>
                Si vous avez des questions, contactez notre support.
            </p>
        </div>
    </div>
@endsection
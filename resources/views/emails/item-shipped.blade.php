@extends('layouts.app')

@section('title', 'Article expedie')

@section('content')
    <div class="container">
        <div class="header">
            <h1>📦 Article expedie !</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->name ?? 'Utilisateur' }},</p>

            <p>Le vendeur a expedie l'article pour votre transaction <strong>{{ $transaction->title ?? 'N/A' }}</strong>.</p>

            <div class="details">
                <p><strong>Montant :</strong> <span class="amount">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span></p>
                <p><strong>Reference :</strong> {{ $transaction->reference ?? 'N/A' }}</p>
            </div>

            <p>Vous pouvez suivre la livraison et confirmer la reception.</p>

            <a href="{{ route('transactions.show', $transaction) }}" class="btn">Voir la transaction</a>

            <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
                Cet email a ete envoye automatiquement par PayXora.<br>
                Si vous avez des questions, contactez notre support.
            </p>
        </div>
    </div>
@endsection

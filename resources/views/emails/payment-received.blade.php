@extends('layouts.app')

@section('title', 'mail')

@section('content')


    <div class="container">
        <div class="header">
            <h1>💰 Paiement reçu !</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $seller->name ?? 'Vendeur' }},</p>
            
            <p>Un acheteur vient d'effectuer un paiement pour votre transaction.</p>
            
            <div class="details">
                <p><strong>Transaction :</strong> {{ $transaction->title ?? 'N/A' }}</p>
                <p><strong>Montant :</strong> <span class="amount">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span></p>
                <p><strong>Méthode :</strong> {{ strtoupper($transaction->payment_method ?? 'Mobile Money') }}</p>
                <p><strong>Référence :</strong> {{ $transaction->payment_reference ?? 'N/A' }}</p>
            </div>
            
            <p>Le montant est maintenant sécurisé dans notre système d'escrow. Vous pouvez procéder à l'expédition de l'article.</p>
            
            <a href="{{ route('transactions.show', $transaction) }}" class="btn">Voir la transaction</a>
            
            <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
                Cet email a été envoyé automatiquement par Payxora.<br>
                Si vous avez des questions, contactez notre support.
            </p>
        </div>
    </div>
@endsection

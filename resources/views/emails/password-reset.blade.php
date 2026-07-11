@extends('layouts.app')

@section('title', 'Réinitialisation de mot de passe')

@section('content')
    <div class="container">
        <div class="header">
            <h1>🔐 Réinitialisation de votre mot de passe</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            
            <p>Vous avez demandé la réinitialisation de votre mot de passe pour votre compte PayXora. Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe sécurisé :</p>
            
            <a href="{{ $link }}" class="btn">Réinitialiser mon mot de passe</a>
            
            <p style="margin-top: 20px; font-size: 13px; color: #92400e; background: #fef3c7; padding: 12px; border-radius: 6px;">
                ⚠️ Ce lien est valable pendant <strong>60 minutes</strong>. Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email — votre compte reste sécurisé.
            </p>
            
            <p style="margin-top: 20px; font-size: 12px; color: #6b7280;">
                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
                <span style="word-break: break-all;">{{ $link }}</span>
            </p>
            
            <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
                Cet email a été envoyé automatiquement par PayXora.<br>
                Si vous avez des questions, contactez notre support.
            </p>
        </div>
    </div>
@endsection
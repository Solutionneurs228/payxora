# PayXora - Phase 2 : Authentification + KYC

## Fichiers fournis

### Views Auth (a remplacer)
- `resources/views/auth/forgot-password.blade.php` — Email pour reset
- `resources/views/auth/reset-password.blade.php` — Nouveau mot de passe
- `resources/views/auth/verify-email.blade.php` — Verification email
- `resources/views/auth/confirm-password.blade.php` — Confirmation password

### Views KYC (a remplacer)
- `resources/views/auth/kyc.blade.php` — Formulaire upload complet (recto/verso/selfie)
- `resources/views/auth/kyc-verification.blade.php` — Page attente/approuve/rejete

### Controllers (a remplacer)
- `app/Http/Controllers/Auth/KycController.php` — Upload fichiers, stockage, notifications

### Middleware (a remplacer)
- `app/Http/Middleware/KycMiddleware.php` — Redirection si KYC non valide

### Services (a remplacer)
- `app/Services/NotificationService.php` — Emails KYC, transactions, welcome
- `app/Services/BrevoService.php` — API Brevo, templates HTML

### Routes (a ajouter)
- `routes/web_email_verification.php` — Routes email verification Laravel

### Migrations (a executer)
- `database/migrations/2026_05_01_000002_update_kyc_profiles.php` — Colonnes fichiers + dates

## Installation

### 1. Remplacer les fichiers
Extraire l'archive dans ton projet.

### 2. Ajouter le disk private
Dans `config/filesystems.php`, ajouter le disk 'private' (voir `config/filesystems_addition.txt`).
Puis :
```bash
mkdir -p storage/app/private
chmod 750 storage/app/private
```

### 3. Ajouter les routes email verification
Dans `routes/web.php`, ajouter en haut :
```php
require __DIR__.'/web_email_verification.php';
```

### 4. Executer la migration
```bash
php artisan migrate
```

### 5. Configurer Brevo
Dans `.env` :
```
BREVO_API_KEY=your_brevo_api_key_here
```

### 6. Configurer le mail
Dans `.env` :
```
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_username
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contact@payxora.tg
MAIL_FROM_NAME="PayXora"
```

## Fonctionnalites livrees

- [x] Login avec redirection KYC
- [x] Register avec redirection KYC
- [x] Forgot/Reset password via Brevo
- [x] Email verification (Laravel built-in)
- [x] KYC formulaire avec upload 3 fichiers (recto, verso, selfie)
- [x] Preview nom de fichier selectionne (JS)
- [x] KYC status : pending / approved / rejected
- [x] Page d'attente avec animation pulse
- [x] Page approuve avec CTA dashboard
- [x] Page rejete avec raison + bouton retry
- [x] Stockage fichiers dans disk private (securise)
- [x] Notifications admin sur nouvelle KYC
- [x] Emails automatiques : welcome, KYC approved, KYC rejected, transaction completed
- [x] Middleware KYC avec bypass admin

## Phase 3 preview
- Transactions creation + paiement
- Escrow service complet
- Mobile Money integration

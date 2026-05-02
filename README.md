# PayXora - Plateforme de Paiement Securise (Escrow)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue)](https://php.net)

PayXora est une plateforme de paiement securise par escrow pour le Togo et l'Afrique de l'Ouest.

## Fonctionnalites

- **Escrow securise** : Les fonds sont bloques jusqu'a confirmation de livraison
- **KYC leger** : Verification d'identite avec piece d'identite et selfie
- **Paiement Mobile Money** : Integration TMoney et Moov Money
- **Gestion des litiges** : Mediation et arbitrage interne
- **Tableau de bord** : Suivi des transactions en temps reel

## Installation

```bash
# 1. Cloner le repo
git clone https://github.com/Solutionneurs228/payxora.git
cd payxora

# 2. Installer les dependances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de donnees
php artisan migrate:fresh --seed

# 5. Build assets
npm run build

# 6. Lancer
php artisan serve
```

## Comptes de demonstration

| Role | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@payxora.tg | password |
| Vendeur | seller@payxora.tg | password |
| Acheteur | buyer@payxora.tg | password |

## Architecture

```
app/
  Controllers/      # Controllers HTTP
  Models/           # Eloquent Models
  Services/         # Logique metier
  Contracts/        # Interfaces
  Enums/            # Enumerations PHP 8.1
  Policies/         # Autorisations
  Http/Requests/    # Form Requests
  Console/Commands/ # Commandes artisan
```

## Commandes utiles

```bash
# Annuler transactions expirees
php artisan payxora:expire-transactions

# Liberer fonds auto
php artisan payxora:release-escrow
```

## License

MIT

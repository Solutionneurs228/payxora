# PAYXORA - CORRECTION BASE DE DONNÉES

## 📦 Contenu du package

Ce package corrige tous les problèmes de base de données de PayXora :

### ✅ Ce qui a été corrigé

| Problème | Solution |
|----------|----------|
| 10 migrations correctives qui s'accumulaient | Fusionnées dans les migrations mères |
| Table `kycs` manquante | Créée proprement |
| Conflit entre `kyc_profiles` et `kycs` | Unifié sous `kycs` uniquement |
| `first_name`/`last_name` vs `name` | Tout uniformisé sous `name` |
| Enums `UserRole`, `KycStatus` inexistants | Créés dans `app/Enums/` |
| Champs manquants dans transactions | `reference`, `commission_amount`, `net_amount`, `dispute_deadline` ajoutés |
| Seeders qui plantent | Corrigés avec `Kyc::create()` au lieu de `KycProfile::create()` |
| Factories avec enums inexistants | Corrigés avec strings |
| Relation `kyc()` manquante dans User | Ajoutée |

---

## 🚀 Installation

### Étape 1 : Sauvegarder (optionnel mais recommandé)

Copie ton dossier `database/migrations/` quelque part au cas où.

### Étape 2 : Supprimer les anciennes migrations

Dans CMD, supprime TOUTES les migrations existantes :

```cmd
cd C:\1SSD\ProjetWeb\payxora\database\migrations
del /Q *.php
```

### Étape 3 : Copier les nouvelles migrations

Dézippe `payxora_db_fix.zip` et copie le contenu :
- `database/migrations/*.php` → `database/migrations/`
- `database/seeders/*.php` → `database/seeders/`
- `database/factories/*.php` → `database/factories/`
- `app/Models/*.php` → `app/Models/`
- `app/Enums/*.php` → `app/Enums/`

### Étape 4 : Supprimer l'ancien modèle KycProfile (s'il existe)

Si `app/Models/KycProfile.php` existe, supprime-le :
```cmd
cd C:\1SSD\ProjetWeb\payxora
del app\Models\KycProfile.php
```

### Étape 5 : Vider le cache

```cmd
cd C:\1SSD\ProjetWeb\payxora
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Étape 6 : Réinitialiser la base de données

```cmd
php artisan migrate:fresh --seed
```

⚠️ **Cette commande supprime TOUTES les données existantes !**

---

## 📋 Tables créées (15 migrations)

| # | Table | Description |
|---|-------|-------------|
| 1 | `users` | Utilisateurs (admin, vendeur, acheteur) |
| 2 | `transactions` | Transactions escrow |
| 3 | `payments` | Paiements |
| 4 | `disputes` | Litiges |
| 5 | `dispute_messages` | Messages dans les litiges |
| 6 | `notifications` | Notifications utilisateurs |
| 7 | `escrow_accounts` | Comptes séquestre |
| 8 | `activity_logs` | Logs d'activité |
| 9 | `kycs` | Vérifications KYC |
| 10 | `sessions` | Sessions Laravel |
| 11 | `transaction_logs` | Logs de transactions |
| 12 | `payment_transactions` | Transactions de paiement |
| 13 | `password_resets` | Réinitialisation mot de passe |
| 14 | `cache` + `cache_locks` | Cache Laravel |
| 15 | `jobs` + `job_batches` + `failed_jobs` | Files d'attente Laravel |

---

## 🔑 Comptes de démonstration (Seeders)

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@payxora.tg | password |
| Vendeur | vendeur@payxora.tg | password |
| Acheteur | acheteur@payxora.tg | password |

---

## 🛠️ Si tu as une erreur

### "Class KycProfile not found"
→ Supprime `app/Models/KycProfile.php` et vérifie que `app/Models/Kyc.php` existe

### "Enum UserRole not found"
→ Vérifie que `app/Enums/UserRole.php` existe

### "Table kyc_profiles doesn't exist"
→ Le code utilise maintenant `kycs` partout. Cherche et remplace `kyc_profiles` par `kycs` dans les contrôleurs restants.

---

## 📁 Structure des fichiers livrés

```
payxora_db_fix/
├── app/
│   ├── Enums/
│   │   ├── KycStatus.php
│   │   ├── PaymentMethod.php
│   │   ├── PaymentStatus.php
│   │   ├── TransactionStatus.php
│   │   └── UserRole.php
│   └── Models/
│       ├── Kyc.php          (NOUVEAU)
│       ├── Transaction.php  (CORRIGÉ)
│       └── User.php         (CORRIGÉ)
├── database/
│   ├── factories/
│   │   ├── PaymentFactory.php     (NOUVEAU)
│   │   ├── TransactionFactory.php (NOUVEAU)
│   │   └── UserFactory.php        (CORRIGÉ)
│   ├── migrations/
│   │   └── [15 migrations propres]
│   └── seeders/
│       ├── DatabaseSeeder.php (CORRIGÉ)
│       └── UserSeeder.php     (CORRIGÉ)
```

---

## ✅ Vérification finale

Après installation, teste :
1. `php artisan migrate:fresh --seed` → doit réussir
2. Inscription d'un nouvel utilisateur → doit fonctionner
3. Page KYC → doit afficher le formulaire
4. Connexion avec comptes demo → doit marcher

**Bonne chance avec PayXora ! 🚀**

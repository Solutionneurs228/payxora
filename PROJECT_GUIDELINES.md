# PAYXORA — MÉMOIRE PROJET & GUIDE D'ORCHESTRATION IA

> Document de référence officiel à placer à la racine du repository `payxora`.
>
> Ce fichier sert de mémoire technique, produit, architecture et méthode de travail.
>
> Son objectif est de permettre à toute IA ou développeur rejoignant le projet de comprendre rapidement :
>
> - la vision du produit,
> - l'architecture attendue,
> - les contraintes,
> - les objectifs,
> - l'état du développement,
> - et la manière correcte de continuer le projet.

---

# 1. IDENTITÉ DU PROJET

## Nom du projet

**PayXora Togo**

Ancien nom documentaire : TrustPay Togo.

---

## Domaine

Fintech / Escrow / Paiement sécurisé / E-commerce.

---

## Zone cible

### Phase 1

- Togo

### Phase 2

- UEMOA
- Afrique de l'Ouest francophone

---

# 2. VISION DU PRODUIT

PayXora est une plateforme de paiement sécurisé ("escrow") destinée à sécuriser les transactions entre acheteurs et vendeurs sur internet.

Le mécanisme principal :

1. le vendeur crée une transaction,
2. l'acheteur paie,
3. l'argent est bloqué,
4. le vendeur livre,
5. l'acheteur confirme,
6. les fonds sont libérés.

Le système protège simultanément :

- l'acheteur contre les arnaques,
- le vendeur contre les impayés.

---

# 3. OBJECTIFS DU MVP

Le MVP doit prioriser :

1. la confiance,
2. la stabilité,
3. la simplicité,
4. la crédibilité,
5. une UX claire,
6. une architecture évolutive.

Le projet ne doit PAS chercher une complexité inutile dès le départ.

---

# 4. STACK TECHNIQUE

## Backend

- Laravel
- PHP 8+
- MySQL

---

## Frontend

- Blade
- TailwindCSS
- Alpine.js
- Layout principal : `layouts/app.blade.php`

---

## Paiements

### Mobile Money

- TMoney
- Moov Money

### Cartes bancaires

Le projet doit aussi prévoir l'intégration des paiements par cartes bancaires.

IMPORTANT :

L'intégration carte bancaire doit être pensée dès maintenant AU NIVEAU ARCHITECTURE.

Même en développement local.

Objectifs :

- préparer l'architecture multi-provider,
- éviter une refonte plus tard,
- standardiser les paiements,
- permettre l'ajout futur de Stripe, CinetPay, PayDunya, Flutterwave ou banques locales.

---

## Développement local des paiements cartes

En environnement local :

- utiliser des providers sandbox,
- utiliser des fausses cartes de test,
- simuler les webhooks,
- préparer les flux sans dépendre de la production.

Architecture recommandée :

- `PaymentProviderInterface`
- `CardPaymentService`
- `TMoneyService`
- `MoovMoneyService`
- `WebhookController`
- `PaymentTransaction`

Les paiements cartes doivent être abstraits comme les paiements Mobile Money.

---

## Notifications

- Brevo
- Emails transactionnels
- Notifications système

---

# 5. REPOSITORY OFFICIEL

Repository officiel :

`https://github.com/Solutionneurs228/payxora.git`

IMPORTANT :

Ce repository est la BASE OFFICIELLE DU PROJET.

Aucune IA ou développeur ne doit repartir de zéro.

---

# 6. MÉTHODE DE TRAVAIL OBLIGATOIRE

Le projet doit être développé par :

- phases,
- sous-tâches,
- lots légers,
- intégrations progressives.

Chaque sous-tâche doit :

- être autonome,
- être intégrable immédiatement,
- éviter les régressions,
- éviter de casser l'existant.

---

# 7. RÈGLES STRICTES

## INTERDICTIONS

Ne jamais :

- recréer le projet depuis zéro,
- générer massivement des centaines de fichiers,
- remplacer brutalement l'architecture,
- ignorer le repository existant,
- casser les dépendances,
- introduire une complexité inutile.

---

## OBLIGATIONS

Toujours :

1. analyser avant d'agir,
2. comprendre l'existant,
3. travailler progressivement,
4. respecter la structure actuelle,
5. livrer des fichiers complets,
6. minimiser les manipulations manuelles,
7. préparer l'évolutivité.

---

# 8. FORMAT DE LIVRAISON OBLIGATOIRE

Quand une IA produit du travail :

## Elle doit :

- livrer des fichiers COMPLETS,
- respecter les chemins exacts,
- fournir des dossiers prêts à coller,
- générer des ZIP quand possible,
- limiter les copier/coller.

---

## Elle doit éviter :

- les snippets partiels,
- les réponses vagues,
- les modifications imprécises,
- les changements dangereux.

---

# 9. PHASES DU PROJET

## PHASE 1 — Fondation & Architecture

Objectifs :

- structure Laravel propre,
- architecture services,
- modèles principaux,
- layouts,
- rôles,
- base sécurité,
- transactions,
- KYC léger.

---

## PHASE 2 — Authentification & Utilisateurs

Fonctionnalités :

- inscription,
- connexion,
- profils,
- vérification téléphone,
- KYC.

---

## PHASE 3 — Escrow & Transactions

Fonctionnalités :

- création transaction,
- blocage fonds,
- validation livraison,
- libération argent,
- remboursements,
- litiges.

---

## PHASE 4 — Paiements

Fonctionnalités :

### Mobile Money

- TMoney,
- Moov Money.

### Cartes bancaires

- paiement carte,
- webhooks,
- statuts,
- callbacks,
- remboursements.

Architecture multi-provider obligatoire.

---

## PHASE 5 — UI/UX Moderne

Objectifs :

- design moderne,
- responsive,
- animations légères,
- dashboards,
- administration.

---

## PHASE 6 — Production & Sécurité

Objectifs :

- optimisation,
- monitoring,
- logs,
- permissions,
- anti-fraude,
- sécurisation API,
- scaling.

---

# 10. ARCHITECTURE MÉTIER ATTENDUE

## Entités principales

### User

Types :

- acheteur,
- vendeur,
- admin.

---

### Transaction

États possibles :

- pending,
- paid,
- shipped,
- delivered,
- completed,
- disputed,
- refunded,
- cancelled.

---

### PaymentTransaction

Historique détaillé des paiements :

- provider,
- montant,
- statut,
- référence externe,
- callbacks,
- erreurs.

---

### EscrowAccount

Compte logique de séquestre.

---

### Withdrawal

Retraits Mobile Money.

---

### KycProfile

Contient :

- document,
- selfie,
- statut,
- vérification.

---

### Notification

Notifications système.

---

# 11. PRINCIPES D'ARCHITECTURE

## Backend

Architecture recommandée :

- Controllers légers,
- logique métier dans Services,
- interfaces pour providers,
- Enums pour statuts,
- Policies pour permissions,
- Form Requests pour validation.

---

## Paiements

Tous les paiements doivent passer par une architecture unifiée.

Utiliser :

- `PaymentProviderInterface`

Providers :

- `TMoneyService`
- `MoovMoneyService`
- `CardPaymentService`

Le système doit pouvoir changer de provider sans modifier le cœur métier.

---

## KYC

Utiliser :

- `KycService`
- providers interchangeables plus tard.

---

# 12. STYLE UI/UX ATTENDU

Le design doit être :

- moderne,
- crédible fintech,
- propre,
- léger,
- responsive,
- orienté confiance.

Inspirations :

- SaaS modernes,
- dashboards fintech,
- interfaces professionnelles minimalistes.

Animations :

- discrètes,
- utiles,
- performantes.

---

# 13. SÉCURITÉ MINIMALE OBLIGATOIRE

Le projet doit prévoir :

- validation serveur,
- protection CSRF,
- auth robuste,
- logs,
- limitation brute force,
- sécurisation webhooks,
- audit minimal,
- validation des callbacks paiement,
- contrôle des transactions critiques.

---

# 14. STRATÉGIE MVP

Le MVP doit être réaliste.

Priorités réelles :

1. stabilité,
2. confiance,
3. UX claire,
4. paiements fonctionnels,
5. workflow escrow fiable.

Pas besoin d'une architecture ultra-complexe dès la première version.

---

# 15. ÉTAT ACTUEL DU REPOSITORY

Le repository est en développement progressif.

Avant toute nouvelle génération :

L'IA doit :

1. analyser les fichiers existants,
2. identifier :
   - ce qui fonctionne,
   - ce qui manque,
   - ce qui est incohérent,
   - ce qui est cassé,
3. proposer des corrections progressives.

---

# 16. STRATÉGIE DE CORRECTION

Quand un problème est détecté :

## L'IA doit :

- corriger proprement,
- préserver la compatibilité,
- livrer le fichier entier,
- minimiser les impacts,
- éviter les réécritures inutiles.

---

# 17. OBJECTIF LONG TERME

Préparer progressivement :

- applications mobiles,
- API publiques,
- expansion UEMOA,
- multi-banques,
- multi-providers,
- vraie infrastructure fintech.

---

# 18. DOCUMENTS MÉTIER IMPORTANTS

Document métier principal :

- `TrustPay_Togo_Document_Structuration.docx`

Ce document contient :

- vision business,
- modèle économique,
- workflow escrow,
- stratégie,
- recommandations,
- partenariats,
- sécurité juridique,
- étapes de croissance.

---

# 19. CE QUE L'IA DOIT FAIRE AVANT CHAQUE PHASE

Avant toute génération :

1. expliquer ce qu'elle a compris,
2. analyser l'impact,
3. découper le travail,
4. proposer une stratégie,
5. identifier les risques,
6. puis seulement commencer les livraisons.

---

# 20. COMPORTEMENT ATTENDU D'UNE IA TRAVAILLANT SUR PAYXORA

L'IA doit se comporter comme :

- un architecte Laravel senior,
- un développeur produit,
- un ingénieur pragmatique,
- un intégrateur progressif,
- et non comme un simple générateur massif de snippets.

---

# 21. RÉSUMÉ EXÉCUTIF

PayXora est une plateforme fintech escrow togolaise visant à sécuriser les transactions e-commerce.

Le développement doit rester :

- progressif,
- structuré,
- maintenable,
- compatible avec une croissance future.

Le repository GitHub existant est la base officielle du projet.

Chaque intervention doit être incrémentale, stable et immédiatement intégrable.

Les paiements Mobile Money ET cartes bancaires doivent être prévus dès maintenant au niveau architecture.

---

# 22. INSTRUCTION FINALE POUR L'IA

Avant de continuer le développement, l'IA doit toujours répondre :

1. ce qu'elle a compris,
2. ce qu'elle a analysé,
3. les problèmes identifiés,
4. le plan exact de la sous-tâche suivante,
5. les impacts potentiels,
6. puis seulement commencer les livraisons.

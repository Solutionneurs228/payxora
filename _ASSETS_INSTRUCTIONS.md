# PayXora - Instructions Assets (CSS/JS)

## Probleme
Tailwind CSS n'etait pas compile ou mal configure. Le repo avait:
- Tailwind v3 + @tailwindcss/vite v4 (INCOMPATIBLE)
- app.css de 59 bytes (vide)
- payxora.css existait mais pas importe
- app.js ne chargeait pas les styles

## Solution
Cette archive contient une stack Vite + Tailwind v3 + AlpineJS propre.

## Fichiers a remplacer

| Fichier | Action |
|---------|--------|
| `package.json` | Remplacer (deps corrigees) |
| `vite.config.js` | Remplacer (config propre) |
| `tailwind.config.js` | Remplacer (colors emerald, animations) |
| `postcss.config.js` | Remplacer |
| `resources/css/app.css` | Remplacer (import font + tailwind) |
| `resources/css/payxora.css` | Remplacer (styles custom verts + animations) |
| `resources/js/app.js` | Remplacer (import Alpine + CSS + payxora.js) |
| `resources/js/bootstrap.js` | Creer (axios config) |
| `resources/js/payxora.js` | Creer (animations, compteurs, scroll reveal) |

## Installation

```bash
# 1. Supprimer node_modules et package-lock
rm -rf node_modules package-lock.json

# 2. Installer les nouvelles dependances
npm install

# 3. Build les assets
npm run build

# 4. En dev
npm run dev
```

## Verifications

Si tu vois toujours pas de style:
1. Verifie que `public/build/` existe apres `npm run build`
2. Verifie que le HTML source contient `<link rel="stylesheet"` vers le build
3. Verifie que `@vite(['resources/css/app.css', 'resources/js/app.js'])` est dans le `<head>` du layout

## Fonctionnalites JS incluses

- **Scroll Reveal** : elements qui apparaissent au scroll (classe `.reveal`)
- **Compteurs animés** : stats qui comptent jusqu'au chiffre (classe `.stat-counter`)
- **Menu mobile** : toggle avec `[data-mobile-menu-btn]`
- **Dropdown notifications** : click outside pour fermer
- **Flash messages auto-dismiss** : disparaissent apres 5s
- **Validation visuelle** : shake animation sur champs invalides
- **Copy to clipboard** : `[data-copy]` attribute
- **Lazy loading images** : `data-src` -> `src`

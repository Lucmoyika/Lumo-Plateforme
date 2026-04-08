# Lumo Plateforme

Lumo est une plateforme multi-modules construite avec **Laravel 11**, **Tailwind CSS** et **Alpine.js**. Elle regroupe dans une seule application :

- 🏫 Gestion des écoles et universités
- 💼 Offres d'emploi et candidatures
- 🏢 Annuaire des entreprises
- 🛒 E-commerce (boutique, panier, commandes)
- 💬 Messagerie / Chat
- 💰 Portefeuille électronique & paiements
- 🎬 Vidéos (achat et streaming)
- 🚚 Logistique & expéditions
- 📊 Analytics & tableaux de bord
- 🔐 Rôles & permissions (via Spatie)
- 🗂️ Panneau d'administration (utilisateurs, logs d'audit)

---

## Prérequis

Avant de commencer, assure-toi d'avoir installé :

| Outil | Version minimale |
|-------|-----------------|
| PHP | 8.2 |
| Composer | 2.x |
| Node.js | 18.x |
| npm | 9.x |
| SQLite **ou** MySQL/PostgreSQL | — |

---

## Installation & démarrage en local

### 1. Cloner le dépôt

```bash
git clone https://github.com/Lucmoyika/Lumo-Plateforme.git
cd Lumo-Plateforme
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

> **SQLite (par défaut, le plus simple) :** le fichier `.env.example` utilise déjà SQLite. Aucune configuration supplémentaire n'est nécessaire — la base de données sera créée automatiquement à l'étape suivante.

> **MySQL / PostgreSQL :** édite `.env` et remplis les variables `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

### 5. Créer la base de données et exécuter les migrations

```bash
# Crée le fichier SQLite si tu utilises SQLite
touch database/database.sqlite

# Lance toutes les migrations
php artisan migrate

# (Optionnel) Remplis la base avec des données de test
php artisan db:seed
```

### 6. Créer le lien symbolique pour le stockage public

```bash
php artisan storage:link
```

### 7. Lancer l'application

**Option A — tout en une commande (serveur + queue + logs + Vite) :**

```bash
composer run dev
```

**Option B — deux terminaux séparés :**

```bash
# Terminal 1 : serveur Laravel
php artisan serve

# Terminal 2 : compilation des assets (Vite / Tailwind)
npm run dev
```

L'application est accessible sur **http://localhost:8000**.

---

## Compilation des assets pour la production

```bash
npm run build
php artisan optimize
```

---

## Commandes utiles

```bash
# Vider tous les caches
php artisan optimize:clear

# Relancer les migrations (⚠️ supprime toutes les données)
php artisan migrate:fresh --seed

# Lancer les tests
php artisan test

# Accéder à la console interactive
php artisan tinker
```

---

## Structure du projet

```
app/
├── Http/          # Controllers, Middleware, Requests
├── Models/        # Modèles Eloquent
├── Modules/       # Modules métier
│   ├── Analytics/
│   ├── Communication/
│   ├── Core/
│   ├── Ecommerce/
│   ├── Education/
│   ├── Emploi/
│   ├── Entreprises/
│   ├── Logistique/
│   └── Paiement/
├── Repositories/
└── Services/
database/
├── migrations/    # Toutes les migrations (écoles, universités, emplois, etc.)
└── seeders/       # Données initiales (rôles, permissions, utilisateurs)
resources/
└── views/         # Templates Blade (Tailwind CSS + Alpine.js)
routes/
├── web.php        # Routes web
└── api.php        # Routes API (JWT via tymon/jwt-auth)
```

---

## Variables d'environnement clés

| Variable | Description | Défaut |
|----------|-------------|--------|
| `APP_NAME` | Nom de l'application | `Laravel` |
| `APP_ENV` | Environnement (`local`, `production`) | `local` |
| `APP_URL` | URL de base | `http://localhost` |
| `DB_CONNECTION` | Driver BDD (`sqlite`, `mysql`, `pgsql`) | `sqlite` |
| `MAIL_MAILER` | Pilote mail (`log`, `smtp`, `mailgun`…) | `log` |
| `QUEUE_CONNECTION` | Pilote de queue (`sync`, `database`, `redis`) | `database` |
| `REDIS_HOST` | Hôte Redis (optionnel) | `127.0.0.1` |

---

## Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT).

# Colink API

API REST construite avec **Laravel 12** pour une plateforme de mise en relation entre **candidats** et **recruteurs**. Authentification via **JWT** (`tymon/jwt-auth`), rôles (`candidat`, `recruteur`, `admin`), ownership strict sur les ressources, et traçabilité des candidatures via Events & Listeners.

## Fonctionnalités

- Authentification JWT (register, login, refresh, logout, me).
- Rôles et autorisations via middleware `role`.
- Gestion de profils candidats et compétences (many-to-many avec niveau).
- Gestion d'offres d'emploi (CRUD pour recruteurs, liste publique paginée/filtrée).
- Candidatures (postuler, suivre ses candidatures, traitement recruteur).
- Administration (utilisateurs, activation d'offres).
- Logging des événements de candidatures dans `storage/logs/candidatures.log`.

## Prérequis

- **PHP >= 8.2** avec extensions : `openssl`, `pdo`, `pdo_sqlite` (ou `pdo_mysql`), `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`.
- **Composer 2+**
- **Node.js 18+** et **npm** (pour Vite)
- Base de données : **SQLite** (par défaut) ou MySQL/PostgreSQL

## Installation

```bash
git clone <repo-url> colink-app
cd colink-app

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Configurer la base dans `.env` (SQLite par défaut). Pour SQLite :

```bash
# Windows PowerShell
ni database/database.sqlite
# Linux/macOS
touch database/database.sqlite
```

Publier la config JWT et générer le secret :

```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

Migrations + données de démo (2 admins, 5 recruteurs avec 2–3 offres, 10 candidats avec profils et compétences) :

```bash
php artisan migrate --seed
```

Lancer le serveur :

```bash
php artisan serve
# et, en parallèle, pour l'asset dev :
npm run dev
```

L'API est servie sous `http://127.0.0.1:8000/api`.

### Comptes de démo

- **Admins** : `admin1@colink.test`, `admin2@colink.test`
- **Recruteurs / candidats** : générés aléatoirement (voir `users` table)
- Mot de passe par défaut des factories : `password`

## Authentification

Ajouter le header sur les routes protégées :

```
Authorization: Bearer <access_token>
```

## Collection Postman

- Fichier de collection : `postman/Colink API.postman_collection.json`
- Import direct dans Postman : **Import** → **Upload Files** → sélectionner ce fichier.
- La collection couvre : inscription, connexion, CRUD profil, CRUD offres, candidatures, changement de statut, et cas d'erreur (`401`, `403`, `422`).

## Routes

Toutes les routes sont préfixées par `/api`.

### Auth

| Méthode | URI | Rôle requis | Description |
|---|---|---|---|
| POST | `/register` | public | Inscription (crée un candidat) |
| POST | `/login` | public | Connexion, retourne un JWT |
| POST | `/logout` | auth | Invalide le token |
| POST | `/refresh` | auth | Rafraîchit le token |
| GET | `/me` | auth | Utilisateur courant |

### Profil (candidat)

| Méthode | URI | Description |
|---|---|---|
| POST | `/profil` | Créer son profil (une seule fois) |
| GET | `/profil` | Consulter son propre profil |
| PUT | `/profil` | Modifier son profil |
| POST | `/profil/competences` | Ajouter une compétence (`competence_id`, `niveau`) |
| DELETE | `/profil/competences/{competence}` | Retirer une compétence |

### Offres

| Méthode | URI | Rôle requis | Description |
|---|---|---|---|
| GET | `/offres` | public | Liste des offres actives. Filtres : `localisation`, `type` (`CDI\|CDD\|stage`), `sort=asc\|desc`. Pagination : 10/page |
| GET | `/offres/{offre}` | public | Détail d'une offre |
| POST | `/offres` | recruteur | Créer une offre |
| PUT | `/offres/{offre}` | recruteur (propriétaire) | Modifier son offre |
| DELETE | `/offres/{offre}` | recruteur (propriétaire) | Supprimer son offre |

### Candidatures

| Méthode | URI | Rôle requis | Description |
|---|---|---|---|
| POST | `/offres/{offre}/candidater` | candidat | Postuler à une offre |
| GET | `/mes-candidatures` | candidat | Ses propres candidatures |
| GET | `/offres/{offre}/candidatures` | recruteur (propriétaire) | Candidatures reçues sur son offre |
| PATCH | `/candidatures/{candidature}/statut` | recruteur (propriétaire) | Changer le statut (`en_attente\|acceptee\|refusee`) |

### Administration

| Méthode | URI | Description |
|---|---|---|
| GET | `/admin/users` | Liste des utilisateurs (filtres `role`, `search`) |
| DELETE | `/admin/users/{user}` | Supprimer un compte |
| PATCH | `/admin/offres/{offre}` | Activer / désactiver une offre |

## Règles d'ownership

- Un **recruteur** ne peut modifier/supprimer/consulter les candidatures **que de ses propres offres** → sinon `403`.
- Un **candidat** ne peut consulter que **ses propres** candidatures.
- L'**admin** a accès complet via `role:admin`.

## Events & Listeners

- `CandidatureDeposee` → `LogCandidatureDeposee` : log date, candidat, offre.
- `StatutCandidatureMis` → `LogStatutCandidatureMis` : log date, ancien/nouveau statut.

Fichier de sortie : `storage/logs/candidatures.log` (channel `candidatures` dans `config/logging.php`).

## Structure principale

```
app/
  Events/          CandidatureDeposee, StatutCandidatureMis
  Http/
    Controllers/   Auth, Profil, Offre, Candidature, Admin
    Middleware/    CheckRole
  Listeners/       LogCandidatureDeposee, LogStatutCandidatureMis
  Models/          User, Profil, Offre, Candidature, Competence, ProfilCompetence
database/
  factories/       toutes les factories (User, Profil, Offre, Competence, ...)
  migrations/      schéma
  seeders/         DatabaseSeeder -> ColinkDemoSeeder
routes/
  api.php          routes API (groupées par auth + role)
```

## Commandes utiles

```bash
php artisan migrate:fresh --seed     # reset + seed
php artisan db:seed                  # seed seul
php artisan route:list --path=api    # inspecter les routes API
php artisan config:clear             # après modif .env / config
```

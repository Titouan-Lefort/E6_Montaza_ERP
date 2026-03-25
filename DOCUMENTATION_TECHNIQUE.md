# Documentation Technique - Montaza ERP

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture technique](#architecture-technique)
3. [Stack technologique](#stack-technologique)
4. [Structure du projet](#structure-du-projet)
5. [Modèles de données](#modèles-de-données)
6. [Modules fonctionnels](#modules-fonctionnels)
7. [Services](#services)
8. [Routes et Contrôleurs](#routes-et-contrôleurs)
9. [Composants Livewire](#composants-livewire)
10. [Helpers](#helpers)
11. [Gestion des permissions](#gestion-des-permissions)
12. [Système de notifications](#système-de-notifications)
13. [Gestion des médias](#gestion-des-médias)
14. [Installation et configuration](#installation-et-configuration)
15. [Tests](#tests)
16. [Scripts de migration](#scripts-de-migration)

---

## Vue d'ensemble

**Montaza** est une application web ERP (Enterprise Resource Planning) développée avec Laravel 12, conçue pour la gestion complète d'une entreprise de tuyauterie et de montage industriel. L'application centralise la gestion commerciale, les stocks, les affaires (projets), les devis, la gestion du personnel et des ateliers.

### Objectifs du système

- **Gestion commerciale** : Commandes (CDE), Demandes de Prix (DDP), Devis
- **Gestion de projets** : Affaires avec suivi budgétaire et planning
- **Gestion des stocks** : Matières premières avec mouvements et alertes
- **Gestion du personnel** : Planning, affectations, congés
- **Atelier et SAV** : Réparations, matériel
- **Administration** : Utilisateurs, permissions, paramètres

---

## Architecture technique

### Architecture globale

```
┌─────────────────────────────────────────────────────────────┐
│                    Navigateur Web (Client)                   │
│                  (Blade Templates + Livewire)                │
└────────────────────────────┬─────────────────────────────────┘
                             │ HTTP/HTTPS
┌────────────────────────────▼─────────────────────────────────┐
│                   Serveur Web (Nginx/Apache)                  │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                    Laravel 12 Application                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │ Controllers  │  │   Services   │  │   Models     │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │  Livewire    │  │  Middleware  │  │   Helpers    │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                   PostgreSQL Database                         │
└───────────────────────────────────────────────────────────────┘
```

### Modèle MVC + Services

L'application suit le pattern MVC (Model-View-Controller) enrichi par :

- **Models** : Eloquent ORM pour l'accès aux données
- **Views** : Templates Blade + Composants Livewire
- **Controllers** : Logique de routing et orchestration
- **Services** : Logique métier complexe (`StockService`, `AccountingService`)
- **Observers** : Événements et actions automatiques sur les modèles
- **Middleware** : Sécurité (XSS, CSRF, permissions)

---

## Stack technologique

### Backend

| Technologie | Version | Usage |
|------------|---------|--------|
| PHP | 8.2+ | Langage serveur |
| Laravel | 12.0 | Framework principal |
| PostgreSQL | - | Base de données |
| Livewire | 3.6 | Composants dynamiques |
| Composer | - | Gestion des dépendances PHP |

### Packages Laravel principaux

```json
{
  "laravel/framework": "^12.0",
  "laravel/tinker": "^2.9",
  "laravel/breeze": "^2.2",
  "livewire/livewire": "^3.6",
  "barryvdh/laravel-dompdf": "^3.0",
  "simplesoftwareio/simple-qrcode": "^4.2",
  "laravel-lang/lang": "^15.7"
}
```

### Frontend

| Technologie | Usage |
|------------|--------|
| Blade | Template engine |
| Tailwind CSS | Framework CSS |
| Alpine.js | Interactions JavaScript légères |
| Vite | Build tool |

### Outils de développement

| Outil | Usage |
|-------|--------|
| Laravel Debugbar | Debugging |
| Laravel IDE Helper | Autocomplétion IDE |
| PHPUnit | Tests unitaires |
| PHP Insights | Qualité du code |
| Laravel Pint | Formatage du code |
| PHPStan | Analyse statique |

---

## Structure du projet

```
montaza-laravel/
├── app/
│   ├── Console/
│   │   └── Commands/           # Commandes Artisan personnalisées
│   ├── Helpers/
│   │   ├── DateHelper.php      # Helpers de formatage de dates
│   │   └── NumberHelper.php    # Helpers de formatage de nombres
│   ├── Http/
│   │   ├── Controllers/        # Contrôleurs applicatifs
│   │   └── Middleware/         # Middleware personnalisés
│   ├── Livewire/               # Composants Livewire
│   ├── Mail/                   # Classes d'emails
│   ├── Models/                 # Modèles Eloquent (60+ modèles)
│   ├── Observers/              # Observers pour événements modèles
│   ├── Providers/              # Service Providers
│   ├── Services/               # Services métier
│   │   ├── StockService.php
│   │   └── AccountingService.php
│   └── View/                   # View Composers
├── bootstrap/
│   ├── app.php                 # Initialisation de l'application
│   └── providers.php           # Enregistrement des providers
├── config/                     # Fichiers de configuration
├── database/
│   ├── migrations/             # 40+ migrations
│   ├── seeders/                # Seeders (données initiales)
│   └── factories/              # Factories pour tests
├── lang/                       # Fichiers de traduction (fr/en)
├── public/                     # Point d'entrée web
│   ├── index.php
│   └── img/
├── resources/
│   ├── css/                    # Styles CSS/Tailwind
│   ├── js/                     # JavaScript/Alpine
│   └── views/                  # Templates Blade
├── routes/
│   ├── web.php                 # Routes web principales
│   ├── auth.php                # Routes d'authentification
│   ├── console.php             # Routes console
│   └── dev_tools.php           # Routes de développement
├── storage/                    # Fichiers générés, logs, cache
├── tests/                      # Tests unitaires et fonctionnels
│   ├── Feature/
│   └── Unit/
├── vendor/                     # Dépendances Composer
├── composer.json               # Dépendances PHP
├── package.json                # Dépendances NPM
├── phpunit.xml                 # Configuration PHPUnit
├── phpstan.neon                # Configuration PHPStan
└── vite.config.js              # Configuration Vite
```

---

## Modèles de données

Le projet contient **60+ modèles Eloquent** organisés en domaines fonctionnels.

### Modèles principaux

#### Gestion commerciale

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Cde` | Commande d'achat | `cdeLignes`, `affaire`, `ddp`, `entite` |
| `CdeLigne` | Ligne de commande | `cde`, `matiere`, `unite` |
| `Ddp` | Demande de prix | `ddpLignes`, `affaire`, `entite` |
| `DdpLigne` | Ligne de demande de prix | `ddp`, `matiere` |
| `DdpLigneFournisseur` | Réponses fournisseurs | `ddpLigne`, `societe` |
| `Facture` | Factures | `affaire` |

#### Affaires et projets

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Affaire` | Projet/Chantier | `cdes`, `ddps`, `reparations`, `devisTuyauteries`, `personnels` |
| `AffairePersonnel` | Affectation du personnel | `affaire`, `personnel`, `taches` |
| `AffairePersonnelTache` | Tâches assignées | `affairePersonnel` |
| `AffaireSuiviLigne` | Lignes de suivi d'affaire | `affaire` |

#### Devis

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `DevisTuyauterie` | Devis de tuyauterie | `sections`, `affaire`, `societe` |
| `DevisTuyauterieSection` | Section de devis | `devisTuyauterie`, `lignes` |
| `DevisTuyauterieLigne` | Ligne de devis | `section`, `matiere` |
| `DossierDevis` | Dossier de devis | `quantitatifs`, `standards` |
| `DossierDevisQuantitatif` | Quantitatif de devis | `dossierDevis`, `matiere` |

#### Stock et matières

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Matiere` | Article/Matière première | `famille`, `sousFamille`, `unite`, `stocks` |
| `Stock` | Stock disponible | `matiere`, `entite` |
| `MouvementStock` | Mouvements de stock | `matiere`, `entite`, `user` |
| `Famille` | Famille d'articles | `sousFamilles`, `matieres` |
| `SousFamille` | Sous-famille d'articles | `famille`, `matieres` |
| `Unite` | Unités de mesure | `matieres` |
| `Standard` | Standards techniques | `versions` |

#### Tiers (Sociétés)

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Societe` | Client/Fournisseur | `etablissements`, `societeContacts`, `matieres` |
| `Etablissement` | Établissement d'une société | `societe`, `contacts`, `pays` |
| `SocieteContact` | Contact dans un établissement | `etablissement` |
| `SocieteMatiere` | Prix fournisseur | `societe`, `matiere`, `etablissement` |
| `SocieteType` | Type de société | (Client/Fournisseur/Les deux) |

#### Personnel et ressources

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Personnel` | Employé | `user`, `affaires`, `conges` |
| `PersonnelConge` | Congés du personnel | `personnel` |
| `User` | Utilisateur système | `role`, `personnel`, `entite` |

#### Atelier et SAV

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Reparation` | Dossier de réparation | `affaire`, `materiel`, `societe` |
| `Materiel` | Parc matériel | `affaires` (relation many-to-many) |

#### Administration

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Role` | Rôle utilisateur | `permissions`, `users` |
| `Permission` | Permission | `roles` (many-to-many) |
| `Entite` | Entité juridique | `cdes`, `ddps`, `stocks` |
| `AppSetting` | Paramètres globaux | - |
| `Notification` | Notifications | `role`, `user` |
| `ModelChange` | Historique des modifications | - |

#### Données de référence

| Modèle | Description |
|--------|-------------|
| `Pays` | Pays |
| `FormeJuridique` | Formes juridiques |
| `CodeApe` | Codes APE |
| `ConditionPaiement` | Conditions de paiement |
| `TypeExpedition` | Types d'expédition |
| `Mailtemplate` | Templates d'emails |

#### Médias et commentaires

| Modèle | Description | Relations principales |
|--------|-------------|----------------------|
| `Media` | Fichiers attachés | Polymorphique (tous modèles) |
| `MediaType` | Types de médias | `medias` |
| `Commentaire` | Commentaires | Polymorphique |

### Relations importantes

#### Affaire → Documents commerciaux

```
Affaire (1) ──── (*) Cde          (Commandes)
            ──── (*) Ddp          (Demandes de prix)
            ──── (*) DevisTuyauterie (Devis)
            ──── (*) Reparation   (Réparations)
```

#### Commande → Lignes → Stock

```
Cde (1) ──── (*) CdeLigne
                  └── (1) Matiere
                  └── Auto-stockage au passage statut "Livrée"
```

#### Société → Établissements → Contacts

```
Societe (1) ──── (*) Etablissement (1) ──── (*) SocieteContact
```

#### Matière → Stock → Mouvements

```
Matiere (1) ──── (*) Stock (par entité)
           └──── (*) MouvementStock (historique)
```

---

## Modules fonctionnels

### 1. Gestion Commerciale

#### Commandes (CDE)

**Contrôleur** : `CdeController.php`

**Fonctionnalités** :
- Création, édition, suppression de commandes
- Gestion des lignes de commande
- Passage de statuts (En attente → En cours → Livrée)
- Auto-stockage lors du passage au statut "Livrée"
- Génération de PDF de commande
- Association à une affaire
- Gestion des notes et commentaires

**Routes principales** :
```php
Route::resource('commandes', CdeController::class);
Route::get('/commandes/{cde}/pdf', [CdeController::class, 'generatePdf']);
```

**Statuts de commande** :
- En attente
- En cours
- Livrée (déclenche l'auto-stockage)
- Annulée

#### Demandes de Prix (DDP)

**Contrôleur** : `DdpController.php`

**Fonctionnalités** :
- Création de DDP multi-fournisseurs
- Comparaison des réponses fournisseurs
- Conversion DDP → CDE
- Génération de PDF

#### Devis de Tuyauterie

**Contrôleur** : `DevisTuyauterieController.php`

**Fonctionnalités** :
- Création de devis multi-sections
- Calcul automatique des totaux, marges, TVA
- Réservation de stock temporaire
- Génération de PDF personnalisé
- Association à une affaire et une société
- Archivage des devis

**Structure** :
```
DevisTuyauterie
  └── DevisTuyauterieSection (1..*)
        └── DevisTuyauterieLigne (1..*)
              └── Matiere (0..1)
```

### 2. Gestion des Affaires

**Contrôleur** : `AffaireController.php`

**Fonctionnalités** :
- Création et suivi des affaires (projets)
- Gestion budgétaire avec alertes
- Affectation du personnel et planning
- Tâches et créneaux horaires
- Affectation du matériel
- Totalisation automatique des documents liés (CDE, DDP, Devis)
- Statuts : En attente, En cours, Terminé, Archivé

**Relations** :
- Une affaire agrège : commandes, demandes de prix, devis, réparations, factures
- Suivi du personnel affecté avec tâches et créneaux
- Gestion des congés du personnel

**Calculs automatiques** :
```php
// app/Models/Affaire.php
public function updateTotal()
{
    $totalCdes = $this->cdes->sum('total_ht');
    $totalDdps = $this->ddps->sum('total_ht');
    // ... totalisation
    $this->total_ht = $total;
    $this->saveQuietly();
}
```

### 3. Gestion des Stocks

**Service** : `StockService.php`

**Fonctionnalités** :
- Entrées/Sorties de stock
- Mouvements tracés (historique complet)
- Alertes de stock minimum
- Calcul du stock disponible par entité
- Réservation de stock pour devis
- Auto-stockage lors de la réception de commandes

**Méthodes principales** :

```php
class StockService
{
    public function stock(
        int $matiereId,
        string $type,        // 'entree' ou 'sortie'
        float $quantite,
        ?float $valeurUnitaire,
        string $motif,
        ?int $relatedId = null
    ): void
    
    public function checkStockMinimum(Matiere $matiere): void
    
    public function reserveStock(DevisTuyauterie $devis): void
}
```

**Alerte de stock minimum** :
- Notification automatique lorsque le stock passe sous le seuil
- Flag `stock_min_notif_envoyee` pour éviter les notifications multiples

### 4. Gestion du Personnel

**Contrôleur** : `PersonnelController.php`

**Fonctionnalités** :
- Fiche personnel (informations, photo, coordonnées)
- Affectations aux affaires
- Gestion des tâches et créneaux horaires
- Gestion des congés
- Emploi du temps
- Historique des affectations
- Statut (Actif/Ancien employé)

**Modèle** : `Personnel.php`
```php
// Relations
public function user()       // Compte utilisateur
public function affaires()   // Affaires assignées
public function conges()     // Congés
public function taches()     // Tâches via affaires
```

### 5. Réparations et Matériel

**Contrôleur** : `ReparationController.php`, `MaterielController.php`

**Fonctionnalités Réparations** :
- Création de dossiers de réparation
- Association au matériel et à une affaire
- Suivi des interventions
- Coûts et facturation

**Fonctionnalités Matériel** :
- Catalogue du parc matériel
- Affectation aux affaires
- Historique des affectations
- Statut (Disponible/En cours/Hors service)

### 6. Administration

**Contrôleur** : `AdministrationController.php`

**Fonctionnalités** :
- Gestion des entités juridiques
- Paramètres globaux (`AppSetting`)
- Gestion des utilisateurs
- Gestion des rôles et permissions
- Historique des modifications (`ModelChange`)
- Données de référence

### 7. Système de Notifications

**Contrôleur** : `NotificationController.php`

**Fonctionnalités** :
- Notifications système par rôle
- Notifications personnelles par utilisateur
- Marquage lu/non lu
- Transfert de notifications
- Notifications temps réel
- Types : stock, budget, information, alerte

**Création de notification** :
```php
Notification::createNotification(
    $role,
    $type,        // 'stock', 'budget', 'information', 'alerte'
    $titre,
    $description,
    $detail,
    $route_name,
    $route_params,
    $cta_text
);
```

---

## Services

### StockService

Gère toute la logique métier liée aux stocks.

**Responsabilités** :
- Enregistrement des mouvements de stock (entrée/sortie)
- Calcul des stocks disponibles
- Vérification des seuils minimums
- Notifications d'alerte
- Traçabilité complète

**Utilisation** :
```php
$stockService = app(\App\Services\StockService::class);
$stockService->stock(
    matiereId: 42,
    type: 'entree',
    quantite: 100,
    valeurUnitaire: 15.50,
    motif: 'Livraison commande CDE-2026-001',
    relatedId: $cdeLigne->id
);
```

### AccountingService

Service pour la gestion comptable et financière.

**Responsabilités** :
- Calculs de totaux, TVA, marges
- Génération de rapports financiers
- Consolidation des données par affaire

---

## Routes et Contrôleurs

### Organisation des routes

| Fichier | Description |
|---------|-------------|
| `web.php` | Routes principales de l'application |
| `auth.php` | Authentification (Laravel Breeze) |
| `console.php` | Commandes Artisan |
| `dev_tools.php` | Outils de développement |

### Middleware appliqués

```php
Route::middleware(['GetGlobalVariable', 'XSSProtection', 'auth'])->group(function () {
    // Routes protégées
});
```

**Middleware personnalisés** :
- `GetGlobalVariable` : Injecte des variables globales (entités, etc.)
- `XSSProtection` : Protection contre les attaques XSS
- `permission:nom_permission` : Vérification des permissions

### Structure des contrôleurs

La plupart des contrôleurs suivent le pattern **Resource Controller** :

```php
class CdeController extends Controller
{
    public function index()     // Liste
    public function create()    // Formulaire de création
    public function store()     // Enregistrement
    public function show($id)   // Détail
    public function edit($id)   // Formulaire d'édition
    public function update($id) // Mise à jour
    public function destroy($id)// Suppression
}
```

**Contrôleurs spécialisés** :
- `ReferenceDataController` : Gestion des données de référence (familles, sous-familles, etc.)
- `PersonnelEmploiDuTempsController` : Emploi du temps du personnel
- `MatierePrixController` : Gestion des prix fournisseurs
- `MediaController` : Upload et gestion des fichiers

---

## Composants Livewire

L'application utilise **Livewire 3.6** pour des composants réactifs sans JavaScript.

### Composants disponibles

| Composant | Fichier | Usage |
|-----------|---------|-------|
| `CdeList` | `app/Livewire/CdeList.php` | Liste interactive des commandes |
| `DevisTuyauterieForm` | `app/Livewire/DevisTuyauterieForm.php` | Formulaire de devis dynamique |
| `MediaSidebar` | `app/Livewire/MediaSidebar.php` | Sidebar de gestion des médias |

### Exemple : DevisTuyauterieForm

Ce composant gère la création/édition de devis avec :
- Ajout/suppression de sections
- Ajout/suppression de lignes
- Calcul automatique des totaux
- Recherche de matières
- Calcul de marges

---

## Helpers

### DateHelper

**Fichier** : `app/Helpers/DateHelper.php`

**Fonction** :
```php
function formatDate($date_string, $avec_heure = false): string
```

Formate une date au format français :
- Avec heure : `d/m/Y H:i`
- Sans heure : `d/m/Y`

### NumberHelper

**Fichier** : `app/Helpers/NumberHelper.php`

**Fonctions** :
```php
function formatNumber($number, $decimales = 2): string
function formatMontant($montant): string
function formatQuantite($quantite): string
```

Formatage des nombres avec séparateurs français :
- Milliers : espace
- Décimales : virgule

---

## Gestion des permissions

### Système de rôles et permissions

L'application utilise un système personnalisé de rôles et permissions.

**Modèles** :
- `Role` : Rôle utilisateur (Admin, Acheteur, Commercial, etc.)
- `Permission` : Permission granulaire
- Relation many-to-many entre `Role` et `Permission`

### Permissions standard

| Permission | Description |
|-----------|-------------|
| `gerer_les_utilisateurs` | Gestion des utilisateurs |
| `gerer_les_permissions` | Attribution des permissions |
| `gerer_les_postes` | Gestion des rôles |
| `gerer_les_donnees_de_reference` | Données de référence |
| `voir_historique` | Accès à l'historique des modifications |

### Vérification des permissions

**Dans les routes** :
```php
Route::middleware('permission:gerer_les_utilisateurs')->group(function () {
    // Routes réservées
});
```

**Dans les vues Blade** :
```blade
@can('permission', 'gerer_les_utilisateurs')
    <!-- Contenu réservé -->
@endcan
```

**Dans les contrôleurs** :
```php
if (!auth()->user()->can('gerer_les_utilisateurs')) {
    abort(403);
}
```

---

## Système de notifications

### Types de notifications

| Type | Usage | Couleur |
|------|-------|---------|
| `information` | Information générale | Bleu |
| `alerte` | Alerte importante | Rouge |
| `stock` | Alerte de stock | Orange |
| `budget` | Dépassement de budget | Rouge |

### Création de notifications

**Par rôle** :
```php
Notification::createNotification(
    $role,                      // Role model
    'stock',
    'Stock critique',
    'Le stock de Tubes Inox est critique.',
    'Stock actuel : 5 unités',
    'matieres.show',
    ['matiere' => 42],
    'Voir la matière'
);
```

**Par utilisateur** :
```php
Notification::createNotificationForUser(
    $user,                      // User model
    'information',
    'Nouvelle affectation',
    'Vous avez été affecté à l\'affaire AFF-2026-001.',
    null,
    'affaires.show',
    ['affaire' => 123],
    'Voir l\'affaire'
);
```

### Récupération des notifications

```php
// Notifications non lues de l'utilisateur
$notifications = auth()->user()->notifications()
    ->where('lu', false)
    ->orderBy('created_at', 'desc')
    ->get();
```

---

## Gestion des médias

### Système de médias

Le système permet d'attacher des fichiers à n'importe quel modèle via une relation **polymorphique**.

**Modèles** :
- `Media` : Fichier attaché
- `MediaType` : Type de média (PDF, Image, Document, etc.)
- Trait `MediaableTrait` : À utiliser dans les modèles concernés

### Utilisation

**Dans un modèle** :
```php
use App\Models\MediaableTrait;

class Cde extends Model
{
    use MediaableTrait;
}
```

**Récupération des médias** :
```php
$cde = Cde::find(1);
$medias = $cde->medias; // Tous les médias attachés
```

**Ajout de média** :
```php
$media = new Media([
    'nom' => 'Bon de livraison.pdf',
    'chemin' => $path,
    'media_type_id' => 1,
]);
$cde->medias()->save($media);
```

### Types de médias par défaut

- PDF
- Image (JPEG, PNG, GIF)
- Document (Word, Excel)
- Archive (ZIP, RAR)
- Autre

---

## Installation et configuration

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js 18+ et NPM
- PostgreSQL 13+
- Serveur web (Nginx recommandé)
- Extension PHP : `pgsql`, `pdo_pgsql`, `mbstring`, `xml`, `gd`

### Installation

#### 1. Cloner le dépôt

```bash
git clone https://github.com/votre-org/montaza-laravel.git
cd montaza-laravel
```

#### 2. Installer les dépendances

```bash
composer install
npm install
```

#### 3. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

**Configurer `.env`** :

```ini
APP_NAME=Montaza
APP_ENV=production
APP_DEBUG=false
APP_URL=https://montaza.votredomaine.fr

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=montaza
DB_USERNAME=montaza_user
DB_PASSWORD=votre_mot_de_passe_securise

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votredomaine.fr
MAIL_FROM_NAME="${APP_NAME}"
```

#### 4. Créer la base de données

```sql
CREATE DATABASE montaza;
CREATE USER montaza_user WITH PASSWORD 'mot_de_passe';
GRANT ALL PRIVILEGES ON DATABASE montaza TO montaza_user;
```

#### 5. Exécuter les migrations et seeders

```bash
php artisan migrate:fresh --seed
```

**Important** : Le seeder crée les utilisateurs par défaut, les rôles, les permissions et des données de test.

#### 6. Compiler les assets

**Développement** :
```bash
npm run dev
```

**Production** :
```bash
npm run build
```

#### 7. Configurer les permissions de storage

**Linux/Mac** :
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**Windows** : Utiliser le script fourni
```powershell
.\fix-storage-permissions.sh
```

#### 8. Générer les helpers IDE

```bash
php artisan ide-helper:generate
php artisan ide-helper:models --nowrite
```

#### 9. Lancer le serveur de développement

```bash
php artisan serve
```

L'application est accessible sur `http://localhost:8000`

### Comptes par défaut

Après le seeding :

**Administrateur** :
- Email : `admin@atlantismontaza.fr`
- Mot de passe : `Not24get`

**Autres utilisateurs** :
- Format : `InitialeNomAnnée` (ex: `Gjosipovic2025`)

---

## Tests

### Configuration des tests

**Fichier** : `phpunit.xml`

Les tests utilisent une base de données séparée (en mémoire SQLite ou PostgreSQL de test).

### Lancement des tests

**Tous les tests** :
```bash
php artisan test
```

**Tests spécifiques** :
```bash
php artisan test --filter=CdeTest
php artisan test tests/Feature/CdeTest.php
```

### Scripts de tests fournis

| Script | Description |
|--------|-------------|
| `TEST_ALL.bat` | Lance tous les tests |
| `TEST_CDE.bat` | Tests des commandes |
| `TEST_CDELIGNES.bat` | Tests des lignes de commande |
| `TEST_PERSONNEL.bat` | Tests du personnel |

### Exemples de tests

**Tests unitaires** : `tests/Unit/`
- Tests des modèles
- Tests des helpers
- Tests des services

**Tests fonctionnels** : `tests/Feature/`
- Tests des contrôleurs
- Tests des routes
- Tests d'intégration

---

## Scripts de migration

L'application fournit plusieurs scripts PowerShell pour faciliter les migrations de données.

| Script | Description |
|--------|-------------|
| `MIGRATION_PERSONNELS.bat` | Import du personnel depuis CSV |
| `MIGRATION_CONGES.bat` | Import des congés |
| `MIGRATION_CRENEAUX.bat` | Import des créneaux horaires |
| `EXECUTE_THIS_SQL.sql` | Scripts SQL de migration manuelle |

---

## Évolutions et maintenance

### Bonnes pratiques

1. **Migrations** : Toujours créer une migration pour les modifications de schéma
2. **Seeders** : Maintenir les seeders à jour pour les tests
3. **Tests** : Écrire des tests pour les nouvelles fonctionnalités
4. **Documentation** : Documenter les changements importants
5. **Versionning** : Utiliser Git Flow ou équivalent

### Commandes utiles

```bash
# Rafraîchir la base avec données de test
php artisan migrate:fresh --seed

# Nettoyer les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimisation production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Générer la documentation de code
php artisan ide-helper:generate

# Analyse de code
./vendor/bin/phpstan analyse
./vendor/bin/pint

# Qualité du code
php artisan insights
```

### Structure des migrations

Les migrations sont organisées chronologiquement :

1. **2024-10** : Création des tables de base (users, sociétés, etc.)
2. **2025-01** : Ajout des DDP et CDE
3. **2025-02** : Système de mouvements de stock
4. **2025-05-06** : Médias et paramètres
5. **2025-10-11** : Matériel et réparations
6. **2026-01-02** : Devis de tuyauterie, personnels, affaires
7. **2026-03** : Suivi d'affaires

---

## Sécurité

### Mesures de sécurité implémentées

1. **Authentification** : Laravel Breeze (session-based)
2. **Autorisation** : Système de rôles et permissions personnalisé
3. **CSRF Protection** : Tokens CSRF sur tous les formulaires
4. **XSS Protection** : Middleware `XSSProtection` personnalisé
5. **SQL Injection** : Utilisation exclusive d'Eloquent ORM
6. **Validation** : Validation stricte de toutes les entrées
7. **Hash des mots de passe** : Bcrypt
8. **HTTPS** : Recommandé en production

### Middleware de sécurité

```php
// app/Http/Middleware/XSSProtection.php
// Nettoie les inputs pour prévenir les attaques XSS
```

### Permissions et accès

Toutes les routes sensibles sont protégées par :
- Middleware `auth` (authentification obligatoire)
- Middleware `permission` (vérification des droits)

---

## Performance

### Optimisations implémentées

1. **Eager Loading** : Utilisation systématique de `with()` pour éviter le problème N+1
2. **Cache** : Configuration de cache Redis/Memcached recommandée
3. **Queue** : Tâches lourdes en arrière-plan (emails, PDF)
4. **Indexes** : Indexes sur les clés étrangères et champs fréquemment recherchés
5. **Pagination** : Pagination de toutes les listes longues

### Configuration recommandée en production

**File `.env`** :
```ini
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**Serveur** :
- Nginx avec PHP-FPM
- PostgreSQL avec tuning approprié
- Redis pour cache et sessions
- Supervisor pour les queues

---

## Annexes

### Diagramme ERD simplifié

```
┌──────────┐       ┌──────────┐       ┌──────────┐
│  User    │1─────*│Personnel │*─────*│ Affaire  │
└──────────┘       └──────────┘       └────┬─────┘
                                            │
                   ┌────────────────────────┼────────────────────┐
                   │                        │                    │
                   │                        │                    │
              ┌────▼────┐            ┌─────▼─────┐       ┌──────▼──────┐
              │   Cde   │            │    Ddp    │       │ DevisTuyau. │
              └────┬────┘            └─────┬─────┘       └──────┬──────┘
                   │                       │                    │
              ┌────▼────┐            ┌─────▼─────┐       ┌──────▼──────┐
              │CdeLigne │            │DdpLigne   │       │   Section   │
              └────┬────┘            └─────┬─────┘       └──────┬──────┘
                   │                       │                    │
                   └──────────┬────────────┘             ┌──────▼──────┐
                              │                          │    Ligne    │
                        ┌─────▼─────┐                    └──────┬──────┘
                        │  Matiere  │                           │
                        └─────┬─────┘                           │
                              │                                 │
                        ┌─────▼─────┐                           │
                        │   Stock   │◄──────────────────────────┘
                        └───────────┘
```

### Glossaire

| Terme | Signification |
|-------|---------------|
| **CDE** | Commande d'achat |
| **DDP** | Demande de Prix (devis fournisseur) |
| **Affaire** | Projet ou chantier |
| **Matière** | Article, matière première, produit |
| **Entité** | Entité juridique de l'entreprise |
| **Établissement** | Site physique d'une société |
| **Stock** | Quantité disponible d'une matière |
| **Mouvement** | Entrée ou sortie de stock |
| **Personnel** | Employé de l'entreprise |
| **Tâche** | Tâche assignée à un personnel sur une affaire |

### Contacts et support

- **Documentation utilisateur** : Voir `DOCUMENTATION_COMPLETE.md`
- **Documentation système de tâches** : Voir `SYSTEME_TACHES.md`
- **Tests de commandes** : Voir `TESTS_COMMANDES.md`
- **Import de matières** : Voir `IMPORT_MATIERES.md`

---

## Licence

Ce projet est propriétaire et confidentiel.

**© 2024-2026 Atlantis Montaza - Tous droits réservés**

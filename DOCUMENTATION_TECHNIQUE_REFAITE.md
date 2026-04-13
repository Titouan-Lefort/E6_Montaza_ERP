# Documentation Technique - Montaza ERP

## 1. Présentation générale

Montaza ERP est une application web de gestion d'entreprise développée avec Laravel 12. Elle centralise la gestion commerciale, les achats, les affaires, les stocks, le personnel, l'atelier et les données de référence.

### Objectifs

- Gérer les **commandes (CDE)**, **demandes de prix (DDP)** et **devis de tuyauterie**.
- Piloter les **affaires / projets** et les budgets associés.
- Suivre les **stocks de matières** et les mouvements de stock.
- Administrer les **utilisateurs, rôles et permissions**.
- Gérer le **personnel**, les **congés** et les **emplois du temps**.
- Consolider les **données de référence** et les **notifications**.

---

## 2. Architecture technique

### Schéma global

- Client web : Blade + Livewire
- Serveur applicatif : Laravel 12
- Base de données : PostgreSQL
- Gestion des dépendances : Composer
- Build frontend : Vite

### Composants principaux

- `app/Http/Controllers` : Contrôleurs HTTP
- `app/Livewire` : Composants Livewire
- `app/Models` : Modèles Eloquent
- `app/Services` : Logique métier indépendante des contrôleurs
- `app/Helpers` : Fonctions utilitaires globales
- `app/Observers` : Observers de modèles
- `routes/` : Déclaration des routes
- `resources/views` : Templates Blade

### Pattern

L'architecture repose sur un modèle MVC enrichi par des services métier et des composants Livewire pour l'interactivité.

---

## 3. Stack technologique

### Backend

- PHP 8.2+
- Laravel 12
- PostgreSQL
- Composer
- `barryvdh/laravel-dompdf`
- `simplesoftwareio/simple-qrcode`

### Frontend

- Blade
- Livewire 3.6
- Tailwind CSS
- Alpine.js
- Vite

### Outils de développement

- Laravel Debugbar
- Laravel IDE Helper
- PHPUnit
- PHPStan
- Laravel Pint
- PHP Insights

---

## 4. Structure du projet

```
app/
  Console/
  Helpers/
  Http/
    Controllers/
    Middleware/
  Livewire/
  Mail/
  Models/
  Observers/
  Providers/
  Services/
  View/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
```

### Fichiers importants

- `routes/web.php` : Routes principales
- `app/Providers/AppServiceProvider.php` : Enregistrement des services
- `config/app.php` : Configuration de base
- `composer.json` : Dépendances PHP
- `package.json` : Dépendances Node

---

## 5. Modèles de données

L'application contient de nombreux modèles Eloquent organisés par domaine : commercial, stock, affaires, tiers, personnel, atelier et administration.

### Domaines clés

- `Cde`, `CdeLigne` : commandes et lignes de commande
- `Ddp`, `DdpLigne`, `DdpLigneFournisseur` : demandes de prix et réponses fournisseurs
- `Affaire`, `AffairePersonnel`, `AffaireSuiviLigne` : projets et suivi
- `DevisTuyauterie`, `DevisTuyauterieSection`, `DevisTuyauterieLigne` : devis de tuyauterie
- `Matiere`, `Stock`, `MouvementStock` : stock et matières
- `Societe`, `Etablissement`, `SocieteContact` : tiers et contacts
- `Personnel`, `PersonnelConge`, `User` : ressources humaines
- `Reparation`, `Materiel` : atelier et SAV
- `Role`, `Permission`, `Entite`, `AppSetting` : administration
- `Media`, `MediaType`, `Commentaire` : médias et commentaires

### Relations importantes

- Une `Affaire` agrège des `Cde`, `Ddp`, `DevisTuyauterie`, `Reparation` et du personnel.
- Une `Cde` contient plusieurs `CdeLigne` et peut générer un mouvement de stock lors de la livraison.
- Une `Societe` possède plusieurs `Etablissement`, chaque établissement contient des `SocieteContact`.
- Une `Matiere` dispose d'un `Stock` par entité et d'un historique de `MouvementStock`.
- Les modèles administratifs utilisent des relations many-to-many entre `Role` et `Permission`.

---

## 6. Modules fonctionnels

### Gestion commerciale

- Commandes (CDE) : création, édition, validation, passage de statut, génération PDF.
- Demandes de prix (DDP) : création multi-fournisseurs, comparaison des offres, transformation en commande.
- Devis de tuyauterie : structure en sections/lignes, calcul des totaux, marges et génération PDF.

### Gestion des affaires

- Suivi des projets avec budgets, statuts et alertes.
- Affectation du personnel, du matériel et des tâches.
- Synthèse des coûts liés aux commandes, devis, demandes de prix et réparations.

### Gestion des stocks

- Enregistrement des entrées et sorties de stock.
- Historique des mouvements.
- Alertes de stock minimum.
- Réservations de stock pour les devis.

### Gestion du personnel

- Fiche employé complète.
- Gestion des congés et des emplois du temps.
- Affectations sur les affaires.

### Administration

- Gestion des utilisateurs, rôles et permissions.
- Données de référence : pays, formes juridiques, codes APE, conditions de paiement.
- Historique des modifications via `ModelChange`.

### Notifications

- Notifications par rôle et par utilisateur.
- Marquage lu / non lu.
- Types d'alerte : stock, budget, information.

### Médias

- Système de médias polymorphique.
- Fichiers attachables à n'importe quel modèle.
- Types de médias : PDF, image, document.

---

## 7. Services métier

### StockService

Gère la logique métier des stocks :

- Enregistrements des mouvements (entrée/sortie).
- Calcul du stock disponible.
- Réservation de stock pour les devis.
- Alertes de seuil minimum.

### AccountingService

Gère les calculs financiers :

- Totaux, marges et TVA.
- Rapports par affaire.
- Consolidation des données commerciales.

---

## 8. Routes et contrôleurs

### Routes principales

- `routes/web.php` : routes applicatives.
- `routes/auth.php` : routes d'authentification.
- `routes/dev_tools.php` : outils de développement.
- `routes/console.php` : commandes Artisan.

### Middleware

- `GetGlobalVariable` : injection des variables globales.
- `XSSProtection` : protection contre les attaques XSS.
- `permission:...` : vérification des permissions.

### Contrôleurs remarquables

- `AffaireController`
- `CdeController`
- `DdpController`
- `DevisTuyauterieController`
- `PersonnelController`
- `AdministrationController`
- `ReferenceDataController`
- `NotificationController`
- `MediaController`

---

## 9. Composants Livewire

L'application utilise Livewire pour des formulaires interactifs et des listes dynamiques sans SPA full JS.

Exemples de composants :

- Gestion des commandes
- Formulaire de devis dynamique
- Sidebar des médias
- Emploi du temps du personnel

---

## 10. Helpers

### `DateHelper`

- Formatage de dates en français.
- Support de l'affichage avec ou sans heure.

### `NumberHelper`

- Formatage des montants.
- Gestion des quantités et séparateurs.

---

## 11. Gestion des permissions

- Système de rôles et permissions personnalisées.
- Permissions appliquées aux routes et aux interfaces.
- Administration via `RoleController` et `PermissionController`.

---

## 12. Installation et configuration

### Prérequis

- PHP 8.2+
- Composer
- Node.js + NPM
- PostgreSQL
- Extensions PHP : `pgsql`, `pdo_pgsql`, `mbstring`, `xml`, `gd`

### Étapes

1. `composer install`
2. `npm install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. Configurer la connexion PostgreSQL dans `.env`
6. `php artisan migrate --seed`
7. `npm run dev` ou `npm run build`

---

## 13. Tests

- `php artisan test`
- `phpunit` via `phpunit.xml`
- Scripts Windows fournis : `TEST_ALL.bat`, `TEST_CDE.bat`, `TEST_CDELIGNES.bat`, `TEST_PERSONNEL.bat`

---

## 14. Notes de déploiement

- Compiler les assets en production : `npm run build`
- Activer le cache Laravel : `php artisan config:cache`
- Assurer les permissions sur `storage` et `bootstrap/cache`
- Utiliser un serveur PHP-FPM avec Nginx ou Apache

> Ce document a été entièrement refait pour fournir une vue technique claire et structurée du projet Montaza ERP.

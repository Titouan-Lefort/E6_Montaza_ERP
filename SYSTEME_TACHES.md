# Système de gestion des tâches pour le personnel assigné aux affaires

## Vue d'ensemble

Un système complet de gestion des tâches a été ajouté pour les employés assignés aux affaires. Chaque employé peut avoir plusieurs tâches définies dans le cadre de son assignation à une affaire, avec un emploi du temps global.

## Fonctionnalités

### 1. Gestion des tâches par affaire

Dans la **page de détails d'une affaire**, pour chaque employé assigné :
- Cliquez sur **"Tâches"** pour accéder à la gestion des tâches
- Ajoutez des tâches avec :
  - Titre et description
  - Date de début et de fin
  - Statut (À faire, En cours, Terminé)
  - Priorité (Basse, Normale, Haute)

### 2. Emploi du temps de l'employé

Dans la **page de profil d'un employé** :
- Bouton **"📅 Emploi du temps"** en haut de la page
- Vision chronologique de toutes les affaires et tâches
- Statistiques :
  - Nombre d'affaires assignées
  - Tâches totales
  - Tâches en cours
  - Tâches terminées

### 3. Liste des affaires dans le profil

Une nouvelle section **"Affaires assignées"** affiche :
- Code et nom de l'affaire
- Rôle de l'employé
- Période d'assignation
- Statut de l'affaire
- Nombre de tâches
- Lien direct vers la gestion des tâches

## Structure de la base de données

### Table : `affaire_personnel_taches`

| Colonne | Type | Description |
|---------|------|-------------|
| id | bigint | Identifiant unique |
| affaire_personnel_id | bigint | Référence vers affaire_personnel |
| titre | string | Titre de la tâche |
| description | text | Description détaillée (optionnel) |
| date_debut | date | Date de début de la tâche |
| date_fin | date | Date de fin de la tâche |
| statut | enum | a_faire, en_cours, termine |
| priorite | enum | basse, normale, haute |
| ordre | integer | Ordre d'affichage |
| created_at | timestamp | Date de création |
| updated_at | timestamp | Date de mise à jour |

## Routes disponibles

### Tâches
```php
// Afficher les tâches d'un employé pour une affaire
GET /affaires/{affaire}/personnel/{personnel}/taches

// Ajouter une tâche
POST /affaires/{affaire}/personnel/{personnel}/taches

// Modifier une tâche
PATCH /affaires/{affaire}/personnel/{personnel}/taches/{tache}

// Supprimer une tâche
DELETE /affaires/{affaire}/personnel/{personnel}/taches/{tache}
```

### Emploi du temps
```php
// Afficher l'emploi du temps d'un employé
GET /personnel/{personnel}/emploi-du-temps
```

## Modèles

### AffairePersonnelTache
```php
// Créer une tâche
AffairePersonnelTache::create([
    'affaire_personnel_id' => $pivotId,
    'titre' => 'Installation équipement',
    'description' => 'Installer les équipements sur site',
    'date_debut' => '2026-02-15',
    'date_fin' => '2026-02-17',
    'statut' => 'a_faire',
    'priorite' => 'haute',
]);
```

### AffairePersonnel (Pivot personnalisé)
```php
// Accéder aux tâches d'une assignation
$assignation = AffairePersonnel::find($id);
$taches = $assignation->taches;

// Via une affaire
$affaire->personnels->each(function($personnel) {
    $taches = $personnel->pivot->taches;
});
```

## Interface utilisateur

### Page de gestion des tâches
- URL : `/affaires/{id}/personnel/{id}/taches`
- Affiche l'assignation (rôle, période, notes)
- Liste toutes les tâches avec badges de statut et priorité
- Modal pour ajouter une tâche
- Modal pour modifier une tâche
- Suppression avec confirmation

### Emploi du temps
- URL : `/personnel/{id}/emploi-du-temps`
- Chronologie organisée par mois
- Affaires en bleu avec leur période
- Tâches en blanc avec icônes selon le statut :
  - ✓ Terminé (vert)
  - ⏱ En cours (bleu)
  - □ À faire (gris)
- KPI en haut de page

## Pages modifiées

### 1. [affaires/show.blade.php](resources/views/affaires/show.blade.php)
- Ajout du lien "Tâches" pour chaque employé assigné

### 2. [personnel/show.blade.php](resources/views/personnel/show.blade.php)
- Bouton "📅 Emploi du temps" en haut
- Section "Affaires assignées" avec tableau détaillé
- Lien "Gérer les tâches" pour chaque affaire

### 3. Nouvelles pages créées
- [affaires/personnel-taches.blade.php](resources/views/affaires/personnel-taches.blade.php) - Gestion des tâches
- [personnel/emploi-du-temps.blade.php](resources/views/personnel/emploi-du-temps.blade.php) - Emploi du temps

## Fichiers créés/modifiés

### Migrations
- `2026_02_11_000001_create_affaire_personnel_table.php` - Table pivot personnel/affaire
- `2026_02_11_000002_create_affaire_personnel_taches_table.php` - **NOUVELLE** table des tâches

### Modèles
- `app/Models/AffairePersonnel.php` - **NOUVEAU** modèle pivot personnalisé
- `app/Models/AffairePersonnelTache.php` - **NOUVEAU** modèle des tâches
- `app/Models/Affaire.php` - Relation personnels() mise à jour
- `app/Models/Personnel.php` - Relation affaires() mise à jour

### Contrôleurs
- `app/Http/Controllers/AffaireController.php` - Méthodes tâches ajoutées
- `app/Http/Controllers/PersonnelEmploiDuTempsController.php` - **NOUVEAU** contrôleur

### Routes
- `routes/web.php` - Routes tâches et emploi du temps ajoutées

## Installation

Exécutez le fichier [MIGRATION_PERSONNELS.bat](MIGRATION_PERSONNELS.bat) ou manuellement :

```bash
vagrant ssh
cd /home/vagrant/code/montaza
php artisan migrate
php artisan view:clear
php artisan config:clear
php artisan cache:clear
exit
```

## Cas d'usage

### Ajouter des tâches à un employé
1. Aller sur la page de l'affaire
2. Dans "Personnel Assigné", cliquer sur "Tâches" pour l'employé
3. Cliquer sur "Ajouter une tâche"
4. Remplir le formulaire et valider

### Consulter l'emploi du temps
1. Aller sur le profil de l'employé
2. Cliquer sur "📅 Emploi du temps"
3. Voir la chronologie complète avec statistiques

### Suivre l'avancement
- Les tâches peuvent être mises à jour avec leur statut
- L'emploi du temps affiche visuellement l'état d'avancement
- Les statistiques donnent une vue d'ensemble rapide

## Notes importantes

- Les tâches sont supprimées automatiquement si l'employé est désassigné de l'affaire (CASCADE)
- Les dates des tâches peuvent déborder de la période d'assignation
- La priorité "haute" est mise en évidence avec un badge rouge
- Les tâches terminées ont une coche verte dans l'emploi du temps

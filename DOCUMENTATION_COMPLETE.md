# Documentation Utilisateur Complète - Montaza ERP

**Version:** 1.0  
**Date:** 24 Mars 2026  
**Application:** Montaza - Système de Gestion ERP

---

# Table des Matières

1. [Vue d'ensemble de l'application](#vue-densemble-de-lapplication)
2. [Installation et Configuration](#installation-et-configuration)
3. [Fonctionnalités Principales](#fonctionnalités-principales)
4. [Système de Gestion des Tâches](#système-de-gestion-des-tâches)
5. [Gestion des Devis et Affaires](#gestion-des-devis-et-affaires)
6. [Questions Fréquentes](#questions-fréquentes)

---

# Vue d'ensemble de l'application

Montaza est une application web de gestion d'entreprise (ERP) développée avec Laravel. Elle permet de gérer les commandes, les demandes de prix, les affaires, les stocks de matières premières, les sociétés partenaires et les réparations.

## Fonctionnalités Principales

### Gestion Commerciale

*   **Commandes (CDE)** : Création, suivi, validation et génération de PDF.
*   **Demandes de Prix (DDP)** : Gestion des demandes fournisseurs.
*   **Affaires** : Suivi des projets et budgets associés.

### Gestion des Stocks & Articles

*   **Matières** : Base de données des articles, familles, sous-familles et standards.
*   **Mouvements de Stock** : Suivi des entrées/sorties.

### Tiers

*   **Sociétés** : Clients, Fournisseurs, Sous-traitants.
*   **Contacts** : Gestion des interlocuteurs par établissement.

### Atelier & SAV

*   **Réparations** : Suivi des dossiers de réparation.
*   **Matériel** : Parc matériel.

### Administration

*   Gestion des utilisateurs, rôles et permissions.
*   Configuration des entités et paramètres globaux.

---

# Installation et Configuration

## Prérequis Technique

*   PHP 8.3+
*   Composer
*   Node.js & NPM
*   PostgreSQL
*   Serveur Web (Nginx/Apache) ou Laravel Homestead/Valet
*   `wkhtmltopdf` (pour la génération des PDF)

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/votre-utilisateur/montaza.git
cd montaza
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration de l'environnement

Copiez le fichier d'exemple et configurez votre base de données :

```bash
cp .env.example .env
php artisan key:generate
```

*Assurez-vous de configurer correctement la connexion PostgreSQL dans le fichier `.env`.*

### 4. Base de données

Exécutez les migrations et les seeders pour initialiser la base de données avec des données de test et les comptes par défaut :

```bash
php artisan migrate:fresh --seed
```

### 5. Compilation des assets

Pour le développement :
```bash
npm run dev
```

Pour la production :
```bash
npm run build
```

## Comptes par défaut (Seeding)

Une fois le `db:seed` terminé, vous pouvez vous connecter avec les identifiants suivants :

*   **Administrateur** :
    *   Email : `admin@atlantismontaza.fr`
    *   Mot de passe : `Not24get`

*   **Autres utilisateurs** :
    *   Le seeder génère également des utilisateurs spécifiques (Goran, Sylvie, etc.) avec des mots de passe suivant le format `InitialeNomAnnée` (ex: `Gjosipovic2025`).

## Structure du Projet

*   **Backend** : Laravel 12
*   **Frontend** : Blade, Livewire, Tailwind CSS
*   **Base de données** : PostgreSQL

## Commandes Utiles

*   Lancer le serveur de développement : `php artisan serve`
*   Rafraîchir la base de données : `php artisan migrate:fresh --seed`

---

# Système de Gestion des Tâches

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



## Utilisation

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

---

# Gestion des Devis et Affaires

## Schéma de Liaison : Devis Tuyauterie ↔ Affaires

### Vue d'ensemble de la relation

```
┌─────────────────────────────────────┐
│         AFFAIRES                     │
│  (Projets / Chantiers)              │
├─────────────────────────────────────┤
│ • id (PK)                           │
│ • code (ex: 26-001)                 │
│ • nom                               │
│ • statut                            │
│ • budget                            │
│ • total_ht                          │
│ • date_debut                        │
│ • date_fin_prevue                   │
└──────────────┬──────────────────────┘
               │
               │ Une affaire peut avoir plusieurs :
               ├─────────────────────────────────────┐
               │                                     │
               │                                     │
       ┌───────▼────────┐                   ┌───────▼────────┐
       │  COMMANDES     │                   │  DEVIS         │
       │  (Cde)         │                   │  TUYAUTERIE    │
       ├────────────────┤                   ├────────────────┤
       │ • id           │                   │ • id           │
       │ • affaire_id   │◄──────┐           │ • affaire_id   │
       │ • code         │       │           │ • reference    │
       │ • total_ht     │       │           │ • total_ttc    │
       └────────────────┘       │           └────────────────┘
                                │
                                │
                       ┌────────▼────────┐
                       │  DEMANDES DE    │
                       │  PRIX (DDP)     │
                       ├─────────────────┤
                       │ • id            │
                       │ • affaire_id    │
                       │ • code          │
                       └─────────────────┘
```



## Flux de données

```
1. CRÉATION D'UNE AFFAIRE
   ↓
   Affaire 26-001 "Rénovation Ligne Vapeur"
   
2. CRÉATION DE DEVIS LIÉS À L'AFFAIRE
   ↓
   ┌─────────────────────────────────────┐
   │ Devis 1: Préfabrication atelier     │
   │ affaire_id = 26-001                 │
   │ total_ttc = 25,000 €                │
   └─────────────────────────────────────┘
   
   ┌─────────────────────────────────────┐
   │ Devis 2: Installation sur site      │
   │ affaire_id = 26-001                 │
   │ total_ttc = 18,000 €                │
   └─────────────────────────────────────┘

3. CRÉATION DE COMMANDES FOURNISSEURS
   ↓
   ┌─────────────────────────────────────┐
   │ CDE-26-0045                         │
   │ affaire_id = 26-001                 │
   │ total_ht = 15,000 €                 │
   └─────────────────────────────────────┘

4. SUIVI GLOBAL DANS L'AFFAIRE
   ↓
   Vue consolidée:
   - Budget: 50,000 €
   - Devis émis: 43,000 € TTC
   - Commandes: 15,000 € HT
   - Matériel assigné: 3 items
```

## Utilisation de l'interface

### 1. Page AFFAIRE

```
┌──────────────────────────────────────────────────┐
│ Affaire 26-001: Rénovation Ligne Vapeur         │
│ Budget: 50,000 € | Engagé: 15,000 €             │
├──────────────────────────────────────────────────┤
│                                                  │
│ COMMANDES (3)          DEVIS TUYAUTERIE (2)    │
│ • CDE-26-0045           • Préfabrication        │
│   15,000 €               25,000 € TTC          │
│                          08/02/2026             │
│ • CDE-26-0046                                  │
│   8,500 €              • Installation          │
│                          18,000 € TTC          │
│                          10/02/2026             │
└──────────────────────────────────────────────────┘
```

### 2. Formulaire DEVIS

```
┌──────────────────────────────────────────────┐
│ Créer un devis de tuyauterie                 │
├──────────────────────────────────────────────┤
│                                              │
│ Affaire liée (optionnel)                    │
│ [▼ Sélectionner une affaire      ]          │
│    ├─ 26-001 - Rénovation Vapeur            │
│    ├─ 26-002 - Extension Usine              │
│    └─ 25-045 - Maintenance annuelle         │
│                                              │
│ Référence Projet                            │
│ [Préfabrication Ligne 4          ]          │
│                                              │
│ Client                                      │
│ [▼ ACME Corporation              ]          │
│                                              │
└──────────────────────────────────────────────┘
```

## Avantages de cette liaison

✅ **Traçabilité** : Voir tous les devis liés à un projet  
✅ **Suivi financier** : Comparaison budget vs devis émis  
✅ **Organisation** : Regroupement logique par chantier  
✅ **Reporting** : Statistiques par affaire  
✅ **Optionnel** : Les devis peuvent exister sans affaire (colonne nullable)

---

# Annexes

## Questions Fréquentes

### Comment ajouter un nouvel utilisateur ?
1. Connectez-vous avec un compte administrateur
2. Accédez au menu "Administration"
3. Cliquez sur "Utilisateurs"
4. Utilisez le bouton "Nouvel utilisateur"

### Comment créer une nouvelle affaire ?
1. Accédez au menu "Affaires"
2. Cliquez sur "Nouvelle affaire"
3. Remplissez les informations requises (code, nom, budget, dates)
4. Enregistrez

### Comment générer un PDF de commande ?
1. Ouvrez la commande concernée
2. Cliquez sur le bouton "Générer PDF"
3. Le PDF s'ouvrira automatiquement ou sera téléchargé

## Support et Contact

Pour toute question ou problème technique, veuillez contacter l'équipe de développement.

---

**Fin de la documentation**

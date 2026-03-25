# Montaza - Système de Gestion ERP

Montaza est une application web de gestion d'entreprise (ERP) développée avec Laravel. Elle permet de gérer les commandes, les demandes de prix, les affaires, les stocks de matières premières, les sociétés partenaires et les réparations.

## Fonctionnalités Principales

*   **Gestion Commerciale** :
    *   **Commandes (CDE)** : Création, suivi, validation et génération de PDF.
    *   **Demandes de Prix (DDP)** : Gestion des demandes fournisseurs.
    *   **Affaires** : Suivi des projets et budgets associés.
*   **Gestion des Stocks & Articles** :
    *   **Matières** : Base de données des articles, familles, sous-familles et standards.
    *   **Mouvements de Stock** : Suivi des entrées/sorties.
*   **Tiers** :
    *   **Sociétés** : Clients, Fournisseurs, Sous-traitants.
    *   **Contacts** : Gestion des interlocuteurs par établissement.
*   **Atelier & SAV** :
    *   **Réparations** : Suivi des dossiers de réparation.
    *   **Matériel** : Parc matériel.
*   **Administration** :
    *   Gestion des utilisateurs, rôles et permissions.
    *   Configuration des entités et paramètres globaux.

## Prérequis Technique

*   PHP 8.3+
*   Composer
*   Node.js & NPM
*   PostgreSQL
*   Serveur Web (Nginx/Apache) ou Laravel Homestead/Valet
*   `wkhtmltopdf` (pour la génération des PDF)

## Installation

1.  **Cloner le dépôt**

    ```bash
    git clone https://github.com/votre-utilisateur/montaza.git
    cd montaza
    ```

2.  **Installer les dépendances**

    ```bash
    composer install
    npm install
    ```

3.  **Configuration de l'environnement**

    Copiez le fichier d'exemple et configurez votre base de données :

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    *Assurez-vous de configurer correctement la connexion PostgreSQL dans le fichier `.env`.*

4.  **Base de données**

    Exécutez les migrations et les seeders pour initialiser la base de données avec des données de test et les comptes par défaut :

    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Compilation des assets**

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
*   Vider le cache : `php artisan optimize:clear`


    ```bash
    php artisan migrate
    ```

7. **Lancer le serveur de développement**

    ```bash
    php artisan serve
    ```




8. **Compiler pour la production**

  ```bash
  npm run build
  ```

    Vous pouvez maintenant accéder à l'application à l'adresse [http://localhost:8000](http://localhost:8000).

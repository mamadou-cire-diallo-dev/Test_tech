# Application de Gestion des Notes de Frais

Ce projet est une application Laravel pour la gestion des notes de frais, permettant aux employés de soumettre des dépenses et aux managers de les approuver, rejeter ou marquer comme payées. Il inclut une API REST sécurisée et une interface utilisateur minimale en Blade.

## Fonctionnalités Implémentées

### Pour l'Employé
*   Créer, modifier et soumettre ses propres notes de frais.
*   Suivi du workflow de statut : DRAFT → SUBMITTED → (REJECTED ou APPROVED → PAID).
*   Filtrer ses notes par période, catégorie, statut.

### Pour le Manager
*   Visualiser toutes les notes de frais (avec filtres).
*   Approuver ou rejeter les notes de frais (avec motif).
*   Marquer une dépense comme payée.
*   Exporter les notes de frais approuvées au format CSV (par mois).
*   Consulter des statistiques (totaux par catégorie et par mois).

### API
Une API REST complète est disponible pour interagir avec l'application.

## Installation

Suivez ces étapes pour configurer et exécuter le projet localement :

1.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/mamadou-cire-diallo-dev/Test_tech.git
    cd TestTechnique_N_F
    ```

2.  **Installer les dépendances Composer :**
    ```bash
    composer install
    ```

3.  **Copier le fichier d'environnement et configurer :**
    ```bash
    cp .env.example .env
    ```
    Ouvrez `.env` et configurez votre base de données (par exemple, MySQL ou SQLite).

4.  **Installer les dépendances Node.js et compiler les assets front-end :**
    ```bash
    npm install
    npm run build # ou npm run dev pour le développement
    ```
    Ceci est nécessaire pour compiler le CSS (Tailwind CSS) et le JavaScript.

5.  **Générer la clé d'application :**
    ```bash
    php artisan key:generate
    ```

6.  **Exécuter les migrations et les seeders :**
    Ceci créera les tables de la base de données et insérera les comptes de test (manager et employés) ainsi que des notes de frais d'exemple.
    ```bash
    php artisan migrate --seed
    ```

7.  **Lancer le serveur de développement Laravel :**
    ```bash
    php artisan serve
    ```
    L'application sera accessible à `http://127.0.0.1:8000`.

## Comptes de Test

Les seeders créent les comptes suivants pour faciliter les tests :

*   **Manager :**
    *   Email : `manager1@gmail.com`
    *   Mot de passe : `password`
*   **Employé 1 :**
    *   Email : `employee1@gmail.com`
    *   Mot de passe : `password`
*   **Employé 2 :**
    *   Email : `employee2@gmail.com`
    *   Mot de passe : `password`

## API Endpoints Clés

Voici quelques-uns des endpoints API principaux :

*   `POST /api/login` : Authentification de l'utilisateur.
*   `GET /api/expenses` : Liste filtrée des dépenses (nécessite authentification).
*   `POST /api/expenses` : Création d'une dépense (nécessite authentification).
*   `PUT /api/expenses/{id}` : Modification d'une dépense (nécessite authentification).
*   `POST /api/expenses/{id}/submit` : Soumission d'une dépense par un employé.
*   `POST /api/expenses/{id}/approve` : Approbation d'une dépense par un manager.
*   `POST /api/expenses/{id}/reject` : Rejet d'une dépense par un manager.
*   `POST /api/expenses/{id}/pay` : Marquage d'une dépense comme payée par un manager.
*   `GET /api/stats/summary?period=YYYY-MM` : Statistiques des dépenses (mise en cache 60s).
*   `POST /api/exports/expenses?status=APPROVED&period=YYYY-MM` : Lancement de l'export CSV.
*   `GET /api/exports/{id}` : Récupération du lien de téléchargement de l'export.

## Décisions Architecturales et Techniques

*   **Laravel Sanctum :** Utilisé pour l'authentification API via des tokens.
*   **Policies/Gates :** Implémentées pour une gestion fine des autorisations basées sur les rôles (`EMPLOYEE`, `MANAGER`) et les statuts des ressources.
*   **FormRequests :** Utilisés pour la validation des requêtes entrantes, assurant la propreté et la sécurité des données.
*   **Mise en Cache :** Les endpoints de statistiques (`/api/stats/summary`) sont mis en cache pendant 60 secondes pour optimiser les performances.
*   **Jobs Laravel :** L'exportation des données au format CSV est gérée de manière asynchrone via un Job Laravel (`ExportExpensesJob`), permettant une meilleure scalabilité et expérience utilisateur.
*   **Tests de Fonctionnalité :** Des tests de fonctionnalité sont inclus pour les scénarios clés de gestion des dépenses, assurant la fiabilité du workflow.


## Exécution des Tests

Pour exécuter les tests du projet, utilisez la commande suivante :

```bash
php artisan test
```
# Documentation Utilisateur - SAE05

## Introduction

SAE05 est une application web multi-service qui propose :
- Une API en PHP.
- Un serveur web pour du contenu statique.
- Une interface de gestion de base de données (PhpMyAdmin).

Cette documentation vous guidera sur l'utilisation des différentes fonctionnalités de l'application.

---
### Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :
- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- [Git](https://git-scm.com/)


---
### Étapes pour importer et lancer le projet

1. **Cloner le dépôt GitHub**  
   Ouvrez un terminal et exécutez la commande suivante :  
   ```bash
   git clone https://github.com/paramenter/SAE05.git
   ```
    Cela téléchargera le projet sur votre machine.

2. **Naviguer dans le répertoire du projet**
   
    Accédez au dossier cloné :
    ```bash
    cd SAE05
    ```
3. **Configurer les variables d'environnement**

    Créez un fichier .env à la racine du projet si ce n'est pas déjà fait. Voici un exemple de configuration :
    ```bash
    APP_NAME=SAE05
    APP_PORT=8080
    APP_DB_ADMIN_PORT=8081
    DB_PORT=3307
    WEB_PORT=81

    MYSQL_ROOT_PASS=superSecr3t
    MYSQL_USER=app_user
    MYSQL_PASS=t3rceS
    MYSQL_DB=SAE05
    ```
4. **Lancer les conteneurs Docker**

    Exécutez la commande suivante pour démarrer tous les services définis dans le fichier docker-compose.yml :
    ```bash
    docker-compose up -d
    ```
    Cela démarrera tous les conteneurs en arrière-plan.

5. **Vérifier le statut des conteneurs**

    Assurez-vous que tous les services sont en cours d'exécution :
    ```bash
    docker ps
    ```
    Vous devriez voir une liste de conteneurs avec leurs noms et leurs ports.

## Accès aux différents services


#### Endpoints disponibles :
- `GET /api/depots` : Récupérer la liste des dépots.
- `POST /api/depot` : Ajouter un nouveau dépot.
- `PUT /api/depot/:id` : Mettre à jour un dépot.
- `DELETE /api/depot/:id` : Supprimer un dépot.
- `GET /api/tournee/:id/depots` : Récupérer les dépots d'un itinéraire.
- `POST /api/tournee` : Ajouter un nouvelle itinéraire.
- `POST /api/legumes/repartition` : Fait la répartition des légume dans les panier.
- `POST /api/paniers` : Créé tout les panier pour une semaine. 
- `GET /api/paniers` : Récupérer la liste des panier.
- `GET /api/paniers/count/:semaine` :Récupérer le nombre de panier par type de panier. 

> **Remarque** : Consultez la documentation technique pour obtenir les détails des paramètres et des réponses de l'API a adresse : http://localhost:81/docAPI.

---

### 1. **API PHP**
- **URL**: [http://localhost:8080](http://localhost:8080)
- **Description**: API gérant les données et intégrée avec une base de données MariaDB.

#### Fonctionnalités principales :
- Consultation et modification des données.


---

### 2. **Gestion de la base de données (PhpMyAdmin)**
- **URL**: [http://localhost:8081](http://localhost:8081)
- **Description**: Interface graphique pour gérer la base de données MariaDB.

#### Informations de connexion :
- **Serveur** : `db_server`
- **Utilisateur** : `app_user`
- **Mot de passe** : `t3rceS`
- **Base de données** : `SAE05`

#### Actions possibles :
- Visualiser les tables et les données.
- Exécuter des requêtes SQL.
- Exporter et importer des données.

> **Attention** : Modifiez les données directement ici uniquement si nécessaire.

---

### 3. **Serveur web statique**
- **URL**: [http://localhost:81](http://localhost:81)
- **Description**: Héberge des fichiers HTML, CSS et JavaScript statiques.

#### Contenu disponible :
- Site d'accueil de l'application.
- Pages d'aide ou de documentation additionnelle.

Pour mettre à jour les fichiers du serveur web statique, modifiez le contenu du dossier `WEB` dans le projet.

---


# SAE 05
## Lancer le programme 
Créé un fichier .env 
```shell
mkdir .env
```

Ajouter dans le fichier .env les parametre suivant : 

```shell
MYSQL_ROOT_PASSWORD=root_password   #mot de passe base de données
MYSQL_USER=user                     #Nom d'utilisateur phpmyadmin
MYSQL_PASSWORD=password             #Mot de pass phpmyadmin
MYSQL_DATABASE=mydb                 #Nom de la base de données

BACKEND_PORT=3000                   #Port API
WEB_PORT=8080                       #Port web
MYSQL_PORT=3307                     #Port Base de données 
PHP_MY_ADMIN_PORT=8081              #Port Phpmyadmin

```


Pour lancer le web service :
```shell
sudo docker-compose up --build
```


## Web 
Pour acceder au service web aller a l'adresse : https://127.0.0.1:8080

## Base de données 
Pour acceder a la base de données aller a l'adresse : https://127.0.0.1:8081

## Backend
Pour acceder a API aller a l'adresse : https://127.0.0.1:3000 


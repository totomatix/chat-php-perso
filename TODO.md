# Créer l'image docker

## Mettre à jour vers la dernière version de CodeIgniter

Lancer la commande suivante à la racine du projet :
`composer update`

## Changer l'URL de l'application

Mettre la bonne URL [ici](./app/Config/App.php) à la ligne 19 (on peut mettre ce qu'on veut mail il faut qu'il y est quelque chose).

## Lancer le build de l'image docker

Lancer la commande suivante à la racine du projet :
`docker build -t {nom_image} .`

## Déployer une BDD MySQL sur docker desktop et l'utilisé (optionnel mais recommandé)

- Chercher `mysql` dans la barre de recherche en haut
- Télécharger l'image résultante
- La lancer avec les paramètres suivant :
  - un port pour mapper le port 3306 (ce sera celui là à mettre dans le fichier Config/Database.php)
  - Setter la variable `MYSQL_ROOT_PASSWORD` avec un mot de passe (ce sera celui là à mettre dans le fichier Config/Database.php)

## Connexion bdd

Modifier le fichier [Database.php](./app/Config/Database.php) afin de paramétrer la connexion à la BDD.

Pour se connecter à une BDD sur l'hôte du container docker il faut utiliser le hostname suivant : `host.docker.internal`

Si une BDD MySQL a été déployé avec Docker Desktop il faut utilisé les valeurs utilisées au lancement du container MySQL.

## Lancement du chat

Lancer l'image du chat dans un nouveau container et mapper le port 80 avec le port souhaité.
# MiniProjet 

Utilisation de l'application
Une fois que vous avez téléchargé le projet "MiniProjet" et que vous y avez ajoutez le dossier "vendor",
vous pouvez lancez ces commandes :
- php bin/console doctrine:database:create --if-not-exists
  (la base de données locale va être créée si elle n'existe pas déjà)
 

- php bin/console doctrine:schema:update --dump-sql
- php bin/console doctrine:schema:update --force
  (la base de données va être remplie avec toutes les entités du projet)
  

- php bin/console doctrine:fixtures:load
  (la base de données va être remplie avec des données exemples créées depuis les fichiers fixtures)


- php -S localhost:8000 -t public
  (le serveur local pour le site internet est lancé, il est accessible à l'adresse http://127.0.0.1:8000/)
# Tp_Not-_Symfony

(Le code est dans la branche master)

## Pour démarer le projet : 
-Installer Symfony CLI
-Faire un git clone du projet
Cloner le projet
cd gestion_reservation

# Installer les dépendances
composer install

# Copier le fichier .env
cp .env .env.local

# Modifier le fichier .env.local avec vos informations
DATABASE_URL="mysql://root:@127.0.0.1:3306/reservation?serverVersion=16&charset=utf8"

# Créer la base de données
php bin/console doctrine:database:create

# Créer les migrations
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Démarrer le serveur Symfony
symfony server:start

- Une fois le projet démarré tapé l'url : *http://127.0.0.8:8000/register* et creer sont user (pr defaut il aura le role ROLE_USER) pour lui appliquer le role ROLE_ADMIN il faudras le modifier dans la base de donnée
- Ensuite aller sur *http://127.0.0.8:8000/login* et se connecter
- pour se déconnecter taper l'url : *http://127.0.0.8:8000/logout*

## Les routes disnponibles sont : 

### Pour se connecter 

- /register : pour creer un utilisateur
- /login : pour se connecter

### Pour un administrateur
- /admin : pour acceder au dasboard admin
- /admin/users: pour avoir la liste des users
- /admin/user/new : pour creer un utilisateur en tant qu'admin
- /admin/user/edit/{id} : pour modifier un utilisateur
- /admin/user/delete/{id} : pour supprimer un utilisateur
- /admin/reservation pour voir toute les reservation
- /admin/reservation/new : pou creer une reservation
- /admin/reservation/edit/{id} : pour modifier une reservation
- /admin/reservation/delete/{id} : pour supprimer une reservation
- /admin/user/new-admin : pour creer un nouvel admin

### Pour un user 
- /user/new : pour creer un utilisateur (en mode admin)
- /user/{id} : pour voir l'utilisateur (en mode admin)
- /user/{id}/edit : pour modifier l'utilisateur (en mode admin)
- /user/{id}/delete : pour supprimer un utilisateur (en mode admin)
- /user/ : pour avoir les details des reservation de l'utilisateur
  
### Pour les reservation : 
- /reservation/new : pour en creer une
- /reservation/id : pour voir la reservation et ses details (seulement en mode admin)



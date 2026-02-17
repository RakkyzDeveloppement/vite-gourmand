# Vite & Gourmand

Application web PHP native realisee pour l'ECF TP Developpeur Web et Web Mobile.

## Stack
- Front: HTML, CSS, Bootstrap, JS
- Back: PHP 8 (sans framework)
- SQL: MariaDB / MySQL
- NoSQL: MongoDB (Atlas)

## Prerequis
- PHP 8.5+ avec extensions:
  - `pdo_mysql`
  - `mongodb`
- MariaDB/MySQL
- Composer

## Installation locale
1. Cloner le projet.
2. Importer la base SQL (au choix):
   - `database/tout-en-un.sql`
   - ou `database/schema.sql` puis `database/seed.sql`
3. Configurer les acces DB dans `config/db.php`.
4. Installer les dependances PHP:
   - `composer install`
5. Lancer le serveur:
   - `php -S localhost:8000 -t public router.php`
6. Ouvrir:
   - `http://localhost:8000`

## Configuration MongoDB Atlas
Dans `config/db.php`:
- `mongo.uri`: URI Atlas complete
- `mongo.database`: nom de base (ex: `vite-gourmand`)

Important:
- Encoder les caracteres speciaux du mot de passe dans l'URI (`*` => `%2A`).

## Comptes de demonstration
- Admin:
  - email: `admin@vite-gourmand.fr`
  - mot de passe: `password`
- Employe:
  - email: `employe2@vite-gourmand.fr`
  - mot de passe: `password`
- Utilisateur:
  - a creer via l'inscription publique

## Fonctions principales
- Catalogue menus + filtres dynamiques
- Detail menu + commande
- Espace utilisateur (suivi, modification avant acceptation, avis)
- Espace employe (statuts, annulation motivee, avis, menus/plats/horaires)
- Espace admin (comptes employes, CA SQL filtre, stats NoSQL + graphique)

## Arborescence
- `public/` front controller et assets
- `app/controllers/` logique applicative
- `app/models/` acces donnees
- `app/views/` vues
- `app/services/` services (mail, Mongo)
- `database/` scripts SQL
- `storage/` logs applicatifs

## Notes
- Les mails utilisent `mail()` si dispo, sinon fallback log.
- Pour la demo NoSQL, bouton admin: `Synchroniser depuis SQL`.

## Migration utile
- Si votre base existait deja avant la desactivation des menus, executez:
  - database/migration_add_menu_is_active.sql` .

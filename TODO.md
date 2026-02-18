# TODO ECF - Vite & Gourmand 

## P0 - 

### Parcours 
- [x] Inscription utilisateur complete (donnees + mot de passe fort)
- [x] Connexion / deconnexion
- [x] Mot de passe oublie + reset fonctionnel
- [x] Liste menus visible visiteur/connecte
- [x] Filtres dynamiques menus sans rechargement
- [x] Detail menu complet + conditions visibles
- [x] Commande depuis detail menu
- [x] Calcul prix commande (min personnes, reduc 10%, livraison Bordeaux/hors Bordeaux)
- [x] Mail confirmation commande
- [x] Espace utilisateur: suivi commandes + avis

### Roles et back-office
- [x] Espace employe: mise a jour statuts commandes
- [x] Espace employe: validation/refus avis
- [x] Espace admin: creation compte employe
- [x] Espace admin: desactivation compte employe
- [x] Interdire creation admin depuis l'application

### Donnees et securite
- [x] SQL final propre et rejouable (`schema` + `seed` ou `init`)
- [x] Controle d'acces par role partout
- [x] CSRF sur tous les formulaires
- [x] Hash mots de passe + validation serveur

### Deploiement
- [X] Application deployee et accessible en ligne
- [X] Base relationnelle connectee en production
- [X] Base NoSQL connectee en production (stats admin)

## P1 - Exigences fortes ECF

### Fonctionnel avance
- [x] Utilisateur: modification commande possible avant `acceptee` (hors menu)
- [x] Employe: filtre commandes par statut
- [x] Employe: filtre commandes par client
- [x] Employe: CRUD plats
- [x] Employe: gestion horaires
- [x] Employe: annulation commande avec motif + mode de contact
- [x] Employe: statut `en attente retour materiel` + mail alerte 10 jours

### Admin/NoSQL
- [x] Stats commandes par menu (NoSQL)
- [x] Comparaison via graphique
- [x] Chiffre d'affaires par menu avec filtre periode

### Accessibilite / RGAA
- [x] Navigation clavier complete
- [x] Focus visibles
- [X] Contrastes valides
- [x] Labels/messages d'erreur accessibles

## P2 - Livrables documentaires

### Depot et organisation
- [X] Repo GitHub public
- [X] Branches Git conformes (main/dev/features)
- [x] README deployment local clair

### Documentation attendue
- [X] Manuel utilisateur PDF (avec identifiants test)
- [X] Charte graphique PDF
- [X] 3 maquettes desktop + 3 maquettes mobile
- [X] Documentation gestion de projet
- [X] Documentation technique (choix techno, env, MCD/diagrammes, deploiement)
- [X] Lien outil de gestion de projet (Notion/Trello/Jira)

## Verification finale avant rendu
- [X] Recette manuelle complete des parcours P0
- [X] Correction des bugs bloquants restants
- [X] Verification liens livrables (repo, app deployee, gestion projet)
- [X] Verification orthographe/coherence des docs PDF








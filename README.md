# Sciences-U - B3 IW - PHP MVC - 2023

# Projet d'amélioration de l'application

Ce projet vise à améliorer une application existante en ajoutant de nouvelles fonctionnalités et en optimisant certains composants.

## Démarrage

### Composer

Pour récupérer les dépendances déclarées dans `composer.json` et générer l'autoloader PSR-4, exécuter la commande suivante :

`composer install`

### DB Configuration

La configuration de la base de données doit être inscrite dans un fichier `.env.local`, sur le modèle du fichier `.env`.

### Démarrer l'application

Commande :

`composer start`

Créer la base de données avec comme nom : **`bdd_mvc`.**

Une fois installé il suffit de se créer un compte.

## Cours

Le cours complet se trouve à [cette adresse](https://ld-web.github.io/su-2023-php-mvc-course/).

# ProjetMvcPhp

L'objectif du projet est de créer de A à Z à la “main” un système pour gérer **l'authentification** et les **autorisations**. Notre idéale est de créer une base de projet qui **facilite l'ajout de restrictions** sur certaines fonctions, limitant ainsi l'accès aux utilisateurs connectés qui n'ont pas le rôle approprié.

## Base de données

Pour la base de données nous avons fait au plus simples, notre choix a été de créer simplement une table `users` dans laquelle on implémente les champs suivants ; `username`, `password`, `rôle`.

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL
);
```

**Fonctionnalités implémentées**

- Base de données : la base de données a été créée à l'aide de phpMyAdmin. Elle contient les tables nécessaires pour l'application.
- Connexion à la base de données en utilisant PDO : la connexion à la base de données a été établie en utilisant l'extension PDO de PHP.
- Formulaire de connexion : un formulaire de connexion a été créé pour permettre aux utilisateurs de se connecter à l'application.
- Formulaire d'inscription : un formulaire d'inscription a été mis en place pour permettre aux nouveaux utilisateurs de créer un compte dans l'application.
- Initialisation des sessions : les informations de l'utilisateur sont enregistrées dans des variables de session pour gérer l'authentification.
- Déconnexion : une fonctionnalité de déconnexion a été ajoutée pour permettre aux utilisateurs de se déconnecter de l'application.
- Authentification et autorisations : des attributs d'authentification ont été ajoutés pour vérifier les rôles et les droits de l'utilisateur. Un middleware a été utilisé pour vérifier l'accès aux routes en fonction des attributs d'authentification

**Explication Authentification et autorisations**

- L'attribut #[Authorize] est utilisé pour vérifier à la fois si l'utilisateur est autorisé à accéder à une certaine page et s'il est connecté. Il permet de contrôler l'accès aux routes en fonction du rôle de l'utilisateur.

- Dans la méthode verifyRole() du Router, le code vérifie si l'attribut #[Authorize] est présent pour la méthode en cours d'exécution. S'il est présent, le code vérifie d'abord si l'utilisateur est connecté en vérifiant la présence de la variable de session $_SESSION['user']. Ensuite, il vérifie si le rôle de l'utilisateur correspond au rôle requis spécifié dans l'attribut #[Authorize].

- Si l'utilisateur n'est pas connecté ou n'a pas le rôle requis, le code effectue une redirection vers la page d'accueil (/) en utilisant la fonction header(). Cela garantit que seuls les utilisateurs connectés et ayant le rôle requis peuvent accéder à la page protégée.

- En résumé, l'attribut #[Authorize] sert à la fois à vérifier l'autorisation d'accès et à s'assurer que l'utilisateur est connecté avant d'exécuter une méthode spécifique. Cela permet de contrôler finement l'accès aux différentes parties de votre application.
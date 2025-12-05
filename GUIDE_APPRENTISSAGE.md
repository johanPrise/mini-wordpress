# 🎓 Guide d'Apprentissage Progressif PHP - Mini WordPress

Bienvenue dans ton parcours d'apprentissage PHP ! Ce guide est conçu pour que tu puisses apprendre en **comprenant chaque concept** avant de coder, et en **écrivant le code toi-même** plutôt que de simplement copier-coller.

---

## 📋 Table des Matières

0. [🐳 Démarrer avec Docker](#0--démarrer-avec-docker)
1. [Comprendre la Structure du Projet](#1-comprendre-la-structure-du-projet)
2. [Niveau 1 : Les Bases de PHP](#2-niveau-1--les-bases-de-php)
3. [Niveau 2 : Le Pattern MVC](#3-niveau-2--le-pattern-mvc)
4. [Niveau 3 : Le Routeur](#4-niveau-3--le-routeur)
5. [Niveau 4 : Les Controllers](#5-niveau-4--les-controllers)
6. [Niveau 5 : Les Vues](#6-niveau-5--les-vues)
7. [Niveau 6 : La Base de Données](#7-niveau-6--la-base-de-données)
8. [Niveau 7 : Les Modèles](#8-niveau-7--les-modèles)
9. [Niveau 8 : L'Authentification](#9-niveau-8--lauthentification)
10. [Exercices Pratiques](#10-exercices-pratiques)

---

## 0. 🐳 Démarrer avec Docker

### 🎯 Objectif
Lancer l'environnement de développement sans installer PHP, MySQL, Apache sur ta machine.

### ❓ Pourquoi Docker ?

Docker permet de créer un environnement **isolé** et **reproductible** :
- Pas besoin d'installer PHP, MySQL, Apache manuellement
- Tout le monde a exactement le même environnement
- Un seul fichier (`docker-compose.yml`) définit toute l'infrastructure

### 📦 Prérequis

1. Installe **Docker Desktop** : https://www.docker.com/products/docker-desktop
2. Vérifie l'installation en ouvrant un terminal :
   ```bash
   docker --version
   docker-compose --version
   ```

### 🚀 Lancer le projet

```bash
# 1. Ouvre un terminal dans le dossier du projet

# 2. Lance les conteneurs Docker (première fois = télécharge les images)
docker-compose up -d

# 3. Vérifie que tout fonctionne
docker-compose ps
```

### 🌐 Accéder à l'application

| Service | URL | Description |
|---------|-----|-------------|
| 🌐 Application | http://localhost:8080 | Ton site Mini WordPress |
| 🔧 phpMyAdmin | http://localhost:8081 | Interface pour gérer la BDD |

### 📊 Identifiants phpMyAdmin

- **Serveur** : db
- **Utilisateur** : mini_wp_user
- **Mot de passe** : mini_wp_password

### 🛑 Arrêter le projet

```bash
# Arrête les conteneurs (les données sont conservées)
docker-compose down

# Arrête ET supprime les données de la BDD
docker-compose down -v
```

### 💡 Commandes utiles

```bash
# Voir les logs en temps réel
docker-compose logs -f

# Accéder au conteneur PHP pour exécuter des commandes
docker-compose exec web bash

# Redémarrer après modification du Dockerfile
docker-compose up -d --build
```

---

## 1. Comprendre la Structure du Projet

### 🎯 Objectif
Avant d'écrire une seule ligne de code, comprends **pourquoi** le projet est organisé ainsi.

### 📁 Structure des Dossiers

```
mini-wordpress/
├── app/                    # 🧠 Le "cerveau" - Code métier
│   ├── Controllers/        # Gèrent les requêtes utilisateur
│   ├── Core/               # Classes fondamentales réutilisables
│   ├── Models/             # Représentent les données
│   └── Views/              # Affichage HTML
├── config/                 # ⚙️ Configuration (BDD, mail, app)
├── migrations/             # 📊 Scripts SQL pour créer les tables
├── public/                 # 🌐 Point d'entrée web (index.php)
└── routes/                 # 🛣️ Définition des URLs
```

### ❓ Pourquoi cette organisation ?

| Dossier | Rôle | Analogie |
|---------|------|----------|
| `public/` | Seul dossier accessible depuis Internet | La porte d'entrée de ta maison |
| `app/` | Contient toute la logique | Les pièces intérieures |
| `config/` | Paramètres modifiables | Le tableau électrique |
| `routes/` | Définit quelles URLs existent | Le plan de la maison |

---

## 2. Niveau 1 : Les Bases de PHP

### 🎯 Ce que tu vas apprendre
- Syntaxe de base PHP
- Variables et types
- Fonctions

### 📖 Explication : Pourquoi `<?php` ?

PHP est un langage qui s'exécute côté serveur. Quand tu écris `<?php`, tu dis au serveur : *"Attention, ce qui suit est du code PHP, exécute-le !"*

```php
<?php
// Tout ce qui est après <?php sera exécuté par le serveur
// Les commentaires commencent par // ou /* */

// Ceci ne sera JAMAIS vu par l'utilisateur dans son navigateur
// Le serveur exécute le code et envoie le RÉSULTAT
```

### ✏️ Exercice 1.1 : Ta première variable

**Pourquoi les variables ?**
Les variables stockent des informations que tu peux réutiliser. En PHP, elles commencent toujours par `$`.

**Ta mission :** Ouvre le fichier `config/app.php` et observe :

```php
<?php
const APP_NAME = 'Mini_WordPress';     // Constante = ne change jamais
const APP_VERSION = "1.0.0";           // Même chose
const APP_URL = 'http://localhost:8000';
```

**Question à te poser :** Pourquoi utiliser `const` plutôt que `$variable` ici ?

<details>
<summary>💡 Réponse</summary>

`const` crée une **constante** = une valeur qui ne changera JAMAIS pendant l'exécution du programme. Le nom de l'app, sa version, son URL... ce sont des valeurs fixes. Utiliser `const` empêche de les modifier accidentellement.

</details>

---

## 3. Niveau 2 : Le Pattern MVC

### 🎯 Ce que tu vas apprendre
- Qu'est-ce que MVC (Model-View-Controller)
- Pourquoi séparer le code

### 📖 Explication : Pourquoi MVC ?

Imagine une pizzeria :
- **Model (Modèle)** = La cuisine (prépare les ingrédients, stocke les recettes)
- **View (Vue)** = La salle (présente le plat au client)
- **Controller (Contrôleur)** = Le serveur (prend la commande, transmet à la cuisine, apporte le plat)

```
┌─────────────────────────────────────────────────────────────┐
│                     REQUÊTE UTILISATEUR                      │
│                    (ex: /admin/users)                        │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       CONTROLLER                             │
│  - Reçoit la requête                                        │
│  - Demande les données au Model                             │
│  - Envoie les données à la View                             │
└─────────────────────────────────────────────────────────────┘
                      │               │
                      ▼               ▼
┌─────────────────────────┐   ┌─────────────────────────┐
│         MODEL           │   │          VIEW           │
│  - Accède à la BDD      │   │  - Génère le HTML       │
│  - Traite les données   │   │  - Affiche à l'écran    │
└─────────────────────────┘   └─────────────────────────┘
```

### ✏️ Exercice 2.1 : Identifier les composants

**Ta mission :** Pour chaque fichier, identifie s'il s'agit d'un Model, View ou Controller :

1. `app/Controllers/HomeController.php` → ?
2. `app/Models/User.php` → ?
3. `app/Views/front/home.php` → ?

<details>
<summary>💡 Réponses</summary>

1. **Controller** - Gère les requêtes pour la page d'accueil
2. **Model** - Représente un utilisateur dans la base de données
3. **View** - Affiche la page d'accueil

</details>

---

## 4. Niveau 3 : Le Routeur

### 🎯 Ce que tu vas apprendre
- Comment PHP sait quelle page afficher
- Le fichier `routes.yaml`

### 📖 Explication : Qu'est-ce qu'un routeur ?

Quand tu tapes `http://monsite.com/login`, comment PHP sait quoi faire ?

Le **routeur** fait la correspondance :
```
URL demandée     →    Controller + Action
─────────────────────────────────────────
/                →    HomeController->index()
/login           →    AuthController->showLogin()
/admin/users     →    UserController->index()
```

### 📄 Observe le fichier `routes/routes.yaml`

```yaml
# Chaque route définit : URL → Controller + Action
/:
  controller: HomeController    # Quel controller utiliser
  action: index                 # Quelle méthode appeler

/login:
  controller: AuthController
  action: showLogin
```

### ✏️ Exercice 3.1 : Ajouter une route

**Ta mission :** Ajoute une nouvelle route pour `/contact`

1. Ouvre `routes/routes.yaml`
2. À la fin du fichier, ajoute :

```yaml
/contact:
  controller: PageController
  action: showContact
```

**Question :** Que faudra-t-il créer ensuite pour que cette route fonctionne ?

<details>
<summary>💡 Réponse</summary>

Il faudra créer :
1. La méthode `showContact()` dans `PageController.php`
2. Une vue `app/Views/front/contact.php`

</details>

---

## 5. Niveau 4 : Les Controllers

### 🎯 Ce que tu vas apprendre
- Créer un controller
- Les classes en PHP
- Les méthodes

### 📖 Explication : Qu'est-ce qu'une classe ?

Une **classe** est un plan/modèle pour créer des objets. Pense à elle comme un moule à gâteau :
- Le moule (classe) définit la forme
- Les gâteaux (objets) sont créés à partir du moule

```php
<?php
// Ceci est une classe
class HomeController {
    // Ceci est une méthode (fonction dans une classe)
    public function index() {
        // Code qui s'exécute quand on appelle cette méthode
    }
}
```

### ✏️ Exercice 4.1 : Créer ton premier controller

**Ta mission :** Complète le fichier `app/Controllers/HomeController.php`

**Étape par étape :**

```php
<?php
// Étape 1 : Déclarer la classe
// 'class' = mot-clé pour créer une classe
// 'HomeController' = nom de la classe (commence toujours par une majuscule)

class HomeController {
    
    // Étape 2 : Créer la méthode index()
    // 'public' = accessible depuis l'extérieur de la classe
    // 'function' = mot-clé pour créer une fonction
    // 'index' = nom de la méthode
    
    public function index() {
        // Étape 3 : Pour l'instant, affichons juste un message
        echo "Bienvenue sur Mini WordPress !";
    }
}
```

**Pourquoi `public` ?**
- `public` = tout le monde peut appeler cette méthode
- `private` = seule la classe elle-même peut l'appeler
- `protected` = la classe et ses enfants peuvent l'appeler

---

## 6. Niveau 5 : Les Vues

### 🎯 Ce que tu vas apprendre
- Séparer la logique de l'affichage
- Inclure des fichiers PHP
- Mixer PHP et HTML

### 📖 Explication : Pourquoi séparer Controller et View ?

Le controller ne devrait **jamais** contenir de HTML. Pourquoi ?

1. **Lisibilité** : Un designer peut modifier le HTML sans toucher au PHP
2. **Réutilisation** : La même vue peut être utilisée par différents controllers
3. **Maintenance** : Plus facile à débugger

### ✏️ Exercice 5.1 : Créer une vue simple

**Ta mission :** Crée le fichier `app/Views/front/home.php`

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>Bienvenue sur <?php echo APP_NAME; ?></h1>
    <p>Version : <?php echo APP_VERSION; ?></p>
</body>
</html>
```

**Remarque :** On peut écrire `<?= APP_NAME ?>` au lieu de `<?php echo APP_NAME; ?>` - c'est un raccourci !

### ✏️ Exercice 5.2 : Connecter Controller et View

**Ta mission :** Modifie `HomeController.php` pour utiliser la vue

```php
<?php
class HomeController {
    
    public function index() {
        // Au lieu d'echo, on inclut la vue
        // __DIR__ = chemin du fichier actuel
        // /../Views = on remonte d'un dossier puis on va dans Views
        
        require __DIR__ . '/../Views/front/home.php';
    }
}
```

---

## 7. Niveau 6 : La Base de Données

### 🎯 Ce que tu vas apprendre
- Se connecter à MySQL avec PDO
- Le pattern Singleton
- Les requêtes préparées

### 📖 Explication : Qu'est-ce que PDO ?

**PDO** (PHP Data Objects) est une interface pour communiquer avec des bases de données. C'est comme un traducteur entre PHP et MySQL.

### 📖 Explication : Le Pattern Singleton

Regarde le fichier `app/Core/Database.php` :

```php
<?php
class Database {
    // Une seule instance existe (singleton)
    private static $instance = null;
    
    // Constructeur privé = personne ne peut faire "new Database()"
    private function __construct() {}
    
    // Seule façon d'obtenir la connexion
    public static function getInstance() {
        if (self::$instance === null) {
            // Première fois : on crée la connexion
            self::$instance = new PDO(...);
        }
        // On retourne toujours la MÊME instance
        return self::$instance;
    }
}
```

**Pourquoi ?** Pour éviter d'ouvrir 50 connexions à la base de données ! Une seule suffit.

### ✏️ Exercice 6.1 : Comprendre la configuration

**Ta mission :** Observe `config/database.php` et réponds :

```php
<?php
const DB_HOST = 'localhost';      // Où est le serveur MySQL ?
const DB_NAME = 'mini_wordpress'; // Nom de la base de données
const DB_USER = 'root';           // Utilisateur MySQL
const DB_PASSWORD = '';           // Mot de passe (vide en local)
```

**Question :** Pourquoi le mot de passe est vide ?

<details>
<summary>💡 Réponse</summary>

En environnement de développement local (sur ton PC), MySQL est souvent configuré avec l'utilisateur `root` sans mot de passe pour simplifier. **En production (sur un vrai serveur), il faut TOUJOURS un mot de passe fort !**

</details>

---

## 8. Niveau 7 : Les Modèles

### 🎯 Ce que tu vas apprendre
- Représenter des données en objet
- Interagir avec la base de données
- Les méthodes CRUD (Create, Read, Update, Delete)

### 📖 Explication : Qu'est-ce qu'un Model ?

Un Model représente une **table** de la base de données sous forme de **classe PHP**.

```
Table "users" en BDD          →    Classe "User" en PHP
────────────────────────────────────────────────────────
id, email, password, name     →    $user->id, $user->email...
```

### ✏️ Exercice 7.1 : Créer le Model User

**Ta mission :** Complète `app/Models/User.php`

```php
<?php
// On a besoin de la classe Database
require_once __DIR__ . '/../Core/Database.php';

class User {
    // Propriétés = colonnes de la table
    public $id;
    public $email;
    public $password;
    public $name;
    public $role;
    public $created_at;
    
    // Méthode statique = on l'appelle sans créer d'objet
    // User::findAll() au lieu de $user->findAll()
    public static function findAll() {
        // 1. Obtenir la connexion à la BDD
        $db = Database::getInstance();
        
        // 2. Préparer la requête SQL
        $stmt = $db->prepare("SELECT * FROM users");
        
        // 3. Exécuter la requête
        $stmt->execute();
        
        // 4. Récupérer les résultats sous forme d'objets User
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }
    
    // Trouver UN utilisateur par son ID
    public static function findById($id) {
        $db = Database::getInstance();
        
        // Le ? sera remplacé par $id (sécurité contre injections SQL)
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        // fetch = UN résultat, fetchAll = PLUSIEURS résultats
        return $stmt->fetchObject('User');
    }
}
```

**Pourquoi `static` ?**
Les méthodes `static` appartiennent à la CLASSE, pas à un objet particulier. On peut les appeler directement : `User::findAll()` sans avoir besoin de faire `$user = new User(); $user->findAll();`

---

## 9. Niveau 8 : L'Authentification

### 🎯 Ce que tu vas apprendre
- Les sessions PHP
- Hasher les mots de passe
- Sécurité de base

### 📖 Explication : Qu'est-ce qu'une session ?

HTTP est "stateless" = le serveur oublie qui tu es après chaque page. Les **sessions** permettent de se souvenir de l'utilisateur.

```php
<?php
session_start();  // TOUJOURS au début du script

// Stocker une info
$_SESSION['user_id'] = 42;

// Récupérer l'info sur une autre page
echo $_SESSION['user_id'];  // Affiche 42

// Supprimer la session (déconnexion)
session_destroy();
```

### 📖 Explication : Pourquoi hasher les mots de passe ?

**JAMAIS** stocker un mot de passe en clair ! Si quelqu'un vole la BDD, il a tous les mots de passe.

```php
<?php
// MAUVAIS ❌
$password = "monmotdepasse";
// Stocké en BDD : monmotdepasse

// BON ✅
$password = "monmotdepasse";
$hash = password_hash($password, PASSWORD_DEFAULT);
// Stocké en BDD : $2y$10$X8kM...

// Pour vérifier à la connexion
if (password_verify("motdepasseEntré", $hashStocké)) {
    echo "Connexion réussie !";
}
```

### ✏️ Exercice 8.1 : Créer la méthode de connexion

**Ta mission :** Dans `app/Controllers/AuthController.php`

```php
<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    
    // Afficher le formulaire de connexion
    public function showLogin() {
        require __DIR__ . '/../Views/auth/login.php';
    }
    
    // Traiter la soumission du formulaire
    public function login() {
        // 1. Récupérer les données du formulaire
        $email = $_POST['email'] ?? '';      // ?? '' = si non défini, mettre ''
        $password = $_POST['password'] ?? '';
        
        // 2. Chercher l'utilisateur par email
        $user = User::findByEmail($email);
        
        // 3. Vérifier le mot de passe
        if ($user && password_verify($password, $user->password)) {
            // Connexion réussie !
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            
            // Redirection vers l'accueil
            header('Location: /');
            exit;  // TOUJOURS exit après header()
        }
        
        // Échec : retour au formulaire avec erreur
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: /login');
        exit;
    }
    
    // Déconnexion
    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
```

---

## 10. Exercices Pratiques

### 🏋️ Exercice Final A : Créer une page "À propos"

**Objectif :** Mettre en pratique tout ce que tu as appris

1. Ajoute la route `/about` dans `routes/routes.yaml`
2. Crée la méthode `showAbout()` dans `PageController.php`
3. Crée la vue `app/Views/front/about.php`

### 🏋️ Exercice Final B : Liste des utilisateurs (Admin)

**Objectif :** Afficher une liste depuis la base de données

1. Complète la méthode `index()` dans `UserController.php`
2. Crée la vue `app/Views/admin/users/index.php`
3. Utilise une boucle `foreach` pour afficher chaque utilisateur

---

## 📚 Ressources Complémentaires

- [Documentation PHP officielle](https://www.php.net/manual/fr/)
- [PDO - PHP Data Objects](https://www.php.net/manual/fr/book.pdo.php)
- [Sécurité PHP](https://www.php.net/manual/fr/security.php)

---

## 🎯 Checklist de Progression

- [ ] Je comprends la structure MVC
- [ ] Je sais créer une variable et une constante
- [ ] Je sais créer une classe et une méthode
- [ ] Je sais ajouter une route
- [ ] Je sais créer un Controller
- [ ] Je sais créer une Vue
- [ ] Je comprends comment fonctionne la connexion BDD
- [ ] Je sais créer un Model basique
- [ ] Je comprends les sessions
- [ ] Je sais hasher un mot de passe

---

*Ce guide évoluera avec ton apprentissage. N'hésite pas à revenir dessus et à cocher ta progression !*

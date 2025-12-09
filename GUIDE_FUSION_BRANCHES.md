# Guide de Fusion des Branches - Mini WordPress

## 📋 Vue d'ensemble

Ce document explique comment fusionner le travail de la branche `houda` dans la branche `main` de manière sécurisée et méthodique.

### Situation actuelle

- **Branche `main`** : CMS complet avec gestion sophistiquée des pages, utilisateurs, et système d'administration
- **Branche `houda`** : Système d'authentification complet avec Docker, Composer, et PHPMailer

---

## 🎯 Stratégie de fusion recommandée

### Principe : **Fusion sélective**
Adopter les nouvelles fonctionnalités de `houda` (authentification, infrastructure moderne) tout en **préservant** les fonctionnalités avancées de `main`.

---

## ✅ À GARDER de la branche `main`

### 1. **Fonctionnalités CMS avancées**
La branche `main` possède un système de gestion de pages beaucoup plus sophistiqué :

**PageController (main)**
- ✅ CRUD complet avec méthodes distinctes (index, create, store, edit, update, delete)
- ✅ Gestion du statut des pages (draft, published)
- ✅ Système de menu (in_menu, menu_order)
- ✅ Gestion des slugs automatiques
- ✅ Association auteur-page (author_id)
- ✅ Flash messages pour les retours utilisateur
- ✅ Middleware d'authentification

**UserController (main)**
- ✅ CRUD administrateur complet
- ✅ Protection contre l'auto-suppression
- ✅ Middleware requireAdmin()
- ✅ Vues admin professionnelles

**Modèles avancés (main)**
- ✅ User model avec méthodes de recherche multiples (findByEmail, findByUsername, findByVerificationToken, findByResetToken)
- ✅ Vérifications d'existence (emailExists, usernameExists)
- ✅ Pagination intégrée
- ✅ Gestion des rôles
- ✅ Tokens de vérification et réinitialisation

**Page model (main)**
- ✅ Génération automatique de slugs
- ✅ Méthodes de recherche avancées (findBySlug, published, inMenu)
- ✅ Comptage et pagination

### 2. **Vues et interface admin**
- ✅ Layouts admin/main structurés
- ✅ Formulaires de création/édition de pages complets
- ✅ Interface de gestion des utilisateurs

---

## ➕ À ADOPTER de la branche `houda`

### 1. **Système d'authentification complet** ⭐
**TRÈS IMPORTANT** : Ces fonctionnalités n'existent pas dans `main`

**AuthController (houda)**
```php
- register()           // Inscription avec validation
- activate()          // Activation par email
- login()             // Connexion sécurisée
- logout()            // Déconnexion
- forgotPassword()    // Demande de réinitialisation
- resetPassword()     // Réinitialisation du mot de passe
```

**Fonctionnalités clés :**
- ✅ Validation d'email (doublon check)
- ✅ Hachage sécurisé des mots de passe
- ✅ Tokens d'activation par email
- ✅ Système de réinitialisation de mot de passe
- ✅ Vérification d'activation avant connexion
- ✅ Gestion de session utilisateur

### 2. **Infrastructure moderne**

**Docker (houda)**
- ✅ `Dockerfile` - Image PHP personnalisée
- ✅ `docker-compose.yml` - Stack complet (PHP/MySQL/phpMyAdmin)
- ➡️ **À adopter** : Facilite le déploiement et le développement

**Composer (houda)**
- ✅ `composer.json` - Gestion des dépendances PHP
- ✅ PHPMailer - Envoi d'emails
- ✅ Symfony YAML - Parser YAML
- ➡️ **À adopter** : Professionnalise le projet

### 3. **Système d'envoi d'emails**

**Mail.php (houda)**
```php
- sendActivationMail($email, $token)
- sendPasswordResetMail($email, $token)
```
- ✅ Intégration PHPMailer
- ✅ Templates HTML pour emails
- ✅ Configuration via variables d'environnement

### 4. **Base de données enrichie**

**Nouvelles colonnes User (houda)**
```sql
- token VARCHAR(255)              -- Token d'activation/reset
- is_active BOOLEAN DEFAULT FALSE -- Statut d'activation
```

**Nouvelles vues**
- ✅ `auth/reset-password.php` - Réinitialisation
- ✅ Formulaires auth améliorés

---

## ❌ À EXCLURE / NE PAS COMMITER

### 1. **Fichiers sensibles** ⚠️ SÉCURITÉ
```
❌ .env                    -- Contient les credentials (JAMAIS dans Git!)
❌ vendor/                 -- Dépendances Composer (à générer localement)
❌ composer.lock           -- Peut être commité mais pas vendor/
```

**Action requise :**
```bash
# Ajouter au .gitignore
echo ".env" >> .gitignore
echo "vendor/" >> .gitignore
```

### 2. **Fichiers inutiles**
```
❌ package-lock.json       -- Non pertinent pour un projet PHP pur
```

---

## 🔧 Plan d'action étape par étape

### Étape 1 : Préparation
```bash
# Sauvegarder votre travail actuel
git stash

# Créer une branche d'intégration
git checkout main
git checkout -b integration/houda-auth

# Voir l'état actuel
git status
```

### Étape 2 : Fusion sélective des fichiers d'infrastructure

**2.1 - Adopter Docker**
```bash
# Copier depuis houda
git checkout houda -- Dockerfile
git checkout houda -- docker-compose.yml

# Examiner et ajuster si nécessaire
cat Dockerfile
cat docker-compose.yml
```

**2.2 - Adopter Composer**
```bash
# Copier les fichiers de configuration
git checkout houda -- composer.json

# Ne PAS copier vendor/ ni .env
# Générer localement après
composer install
```

**2.3 - Créer .gitignore**
```bash
cat > .gitignore << 'EOF'
# Environnement
.env

# Dépendances
vendor/

# IDE
.vscode/
.idea/

# OS
.DS_Store
Thumbs.db
EOF

git add .gitignore
```

### Étape 3 : Fusionner AuthController (IMPORTANT)

**3.1 - Comparer les versions**
```bash
# Voir les différences
git diff main houda -- app/Controllers/AuthController.php
```

**3.2 - Stratégie de fusion**
La version `houda` est **beaucoup plus complète** :
- ✅ Inscription avec activation email
- ✅ Login avec vérification d'activation
- ✅ Mot de passe oublié
- ✅ Réinitialisation de mot de passe

**Recommandation : Adopter COMPLÈTEMENT la version houda**
```bash
git checkout houda -- app/Controllers/AuthController.php
```

### Étape 4 : Fusionner le système Mail

**4.1 - Adopter Mail.php (nouveau fichier)**
```bash
# Mail.php existe dans houda, pas dans main
git checkout houda -- app/Core/Mail.php

# Supprimer l'ancien Mailer.php si conflit
# (Mail.php remplace Mailer.php)
```

**4.2 - Mettre à jour config/mail.php**
```bash
# Comparer les versions
git diff main houda -- config/mail.php

# Adopter la version houda (PHPMailer config)
git checkout houda -- config/mail.php
```

### Étape 5 : Mettre à jour les modèles (DÉLICAT)

**5.1 - User Model (FUSION MANUELLE NÉCESSAIRE)**

La version `main` est plus riche, mais `houda` ajoute des méthodes essentielles pour l'auth.

**Stratégie :**
1. Partir de la version `main` (complète)
2. Ajouter les méthodes manquantes de `houda` :
   - `activate($email, $token)` - Activation par email
   - `setResetToken($userId, $token)` - Pour reset password
   
```bash
# Garder main comme base
cp app/Models/User.php app/Models/User.php.backup

# Éditer manuellement pour ajouter les méthodes de houda
```

**Code à AJOUTER dans User.php (main) :**
```php
/**
 * Activer un utilisateur via email et token
 */
public static function activate(string $email, string $token): int
{
    $stmt = self::getDb()->prepare(
        "UPDATE " . static::$table . " 
         SET is_active = TRUE, token = NULL, email_verified_at = NOW()
         WHERE email = :email AND token = :token"
    );
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount();
}
```

**5.2 - Page Model**
✅ **GARDER la version main** (beaucoup plus sophistiquée)
```bash
# Ne rien faire, garder la version main
```

### Étape 6 : Mettre à jour les vues

**6.1 - Vues d'authentification**
```bash
# Adopter les vues auth améliorées de houda
git checkout houda -- app/Views/auth/login.php
git checkout houda -- app/Views/auth/register.php
git checkout houda -- app/Views/auth/forgot-password.php
git checkout houda -- app/Views/auth/reset-password.php
```

**6.2 - Vues admin**
✅ **GARDER les versions main** (plus complètes)
```bash
# Ne rien faire, garder les versions main
# app/Views/admin/pages/*
# app/Views/admin/users/*
```

**6.3 - Layouts**
Comparer et fusionner manuellement si nécessaire
```bash
git diff main houda -- app/Views/layouts/main.php
git diff main houda -- app/Views/layouts/admin.php
```

### Étape 7 : Mettre à jour la base de données

**7.1 - Migrations**
Fusionner les schémas des deux branches

**Changements de houda à intégrer dans users :**
```sql
ALTER TABLE users 
ADD COLUMN token VARCHAR(255) DEFAULT NULL,
ADD COLUMN is_active BOOLEAN DEFAULT FALSE;
```

**7.2 - Créer un fichier de migration**
```bash
# Créer migrations/002_add_user_activation.sql
cat > migrations/002_add_user_activation.sql << 'EOF'
-- Migration pour ajouter le système d'activation
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS token VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT FALSE;

-- Activer les utilisateurs existants
UPDATE users SET is_active = TRUE WHERE is_active IS NULL OR is_active = FALSE;
EOF
```

### Étape 8 : Mettre à jour la configuration

**8.1 - config/database.php**
```bash
# Comparer les versions
git diff main houda -- config/database.php

# Si houda ajoute le support Docker, adopter
git checkout houda -- config/database.php
```

**8.2 - config/app.php**
```bash
# Vérifier les ajouts de houda
git diff main houda -- config/app.php

# Fusionner manuellement si nécessaire
```

### Étape 9 : Routes

**9.1 - Ajouter les routes d'authentification**
Éditer `routes/routes.yaml` pour inclure :

```yaml
auth:
  register:
    path: /register
    controller: AuthController
    action: register
    methods: [GET, POST]
  
  activate:
    path: /activate
    controller: AuthController
    action: activate
    methods: [GET]
  
  login:
    path: /login
    controller: AuthController
    action: login
    methods: [GET, POST]
  
  logout:
    path: /logout
    controller: AuthController
    action: logout
    methods: [GET]
  
  forgot-password:
    path: /forgot-password
    controller: AuthController
    action: forgotPassword
    methods: [GET, POST]
  
  reset-password:
    path: /reset-password
    controller: AuthController
    action: resetPassword
    methods: [GET, POST]
```

### Étape 10 : Créer le fichier .env

**10.1 - Créer .env.example (à commiter)**
```bash
cat > .env.example << 'EOF'
# Database
DB_HOST=mysql
DB_NAME=mini_wordpress
DB_USER=root
DB_PASS=root

# Mail (SMTP)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_FROM=votre-email@gmail.com
MAIL_FROM_NAME="Mini WordPress"

# App
APP_URL=http://localhost:8080
APP_ENV=development
EOF

git add .env.example
```

**10.2 - Créer votre .env local (NE PAS COMMITER)**
```bash
cp .env.example .env
# Éditer .env avec vos vraies credentials
nano .env
```

### Étape 11 : Tester la fusion

**11.1 - Lancer Docker**
```bash
# Démarrer les services
docker-compose up -d

# Vérifier que tout fonctionne
docker-compose ps
```

**11.2 - Installer les dépendances**
```bash
# Dans le container ou en local
docker-compose exec app composer install
```

**11.3 - Exécuter les migrations**
```bash
# Importer le schéma initial
docker-compose exec mysql mysql -uroot -proot mini_wordpress < migrations/init.sql

# Exécuter la migration d'activation
docker-compose exec mysql mysql -uroot -proot mini_wordpress < migrations/002_add_user_activation.sql
```

**11.4 - Tests fonctionnels**

✅ **Test 1 : Inscription**
- Aller sur `/register`
- Créer un compte
- Vérifier que l'email d'activation est envoyé

✅ **Test 2 : Activation**
- Cliquer sur le lien d'activation dans l'email
- Vérifier que le compte est activé

✅ **Test 3 : Connexion**
- Se connecter avec le compte activé
- Vérifier l'accès à `/admin/users`

✅ **Test 4 : Gestion des pages (main)**
- Créer une page
- Modifier une page
- Publier une page
- Vérifier le menu

✅ **Test 5 : Gestion des utilisateurs (main)**
- Créer un utilisateur
- Modifier un utilisateur
- Tester la protection anti-auto-suppression

✅ **Test 6 : Mot de passe oublié**
- Aller sur `/forgot-password`
- Demander un reset
- Vérifier l'email
- Réinitialiser le mot de passe

### Étape 12 : Commiter la fusion

**12.1 - Vérifier ce qui a changé**
```bash
git status
git diff
```

**12.2 - Ajouter les fichiers**
```bash
# Infrastructure
git add Dockerfile docker-compose.yml
git add composer.json .gitignore .env.example

# Auth
git add app/Controllers/AuthController.php
git add app/Core/Mail.php
git add app/Views/auth/

# Config
git add config/mail.php

# Migrations
git add migrations/002_add_user_activation.sql

# Routes
git add routes/routes.yaml

# Modèles (si modifiés manuellement)
git add app/Models/User.php
```

**12.3 - Commit**
```bash
git commit -m "feat: Merge authentication system from houda branch

- Add complete authentication flow (register, login, activate, reset password)
- Add AuthController with email validation and token management
- Add Mail.php for sending activation and reset emails
- Add Docker environment (PHP, MySQL, phpMyAdmin)
- Add Composer dependencies (PHPMailer, Symfony YAML)
- Add user activation system (token, is_active columns)
- Add authentication routes
- Preserve sophisticated CMS features from main (pages, users CRUD)
- Add .env.example for environment configuration
- Add security best practices (.gitignore for .env and vendor/)
"
```

**12.4 - Push**
```bash
git push origin integration/houda-auth
```

### Étape 13 : Créer une Pull Request

Sur GitHub :
1. Aller dans "Pull Requests"
2. Créer une PR : `integration/houda-auth` → `main`
3. Titre : "Fusion du système d'authentification de Houda"
4. Description : Copier les sections pertinentes de ce guide
5. Demander une revue de code

---

## 🚨 Problèmes courants et solutions

### Problème 1 : Conflit sur AuthController
**Symptôme :** Git signale un conflit
**Solution :** Adopter la version houda (plus complète)
```bash
git checkout --theirs app/Controllers/AuthController.php
```

### Problème 2 : .env manquant
**Symptôme :** Erreur de connexion DB ou mail
**Solution :** 
```bash
cp .env.example .env
# Éditer avec vos credentials
```

### Problème 3 : Vendor/ manquant
**Symptôme :** Class 'PHPMailer' not found
**Solution :**
```bash
composer install
```

### Problème 4 : Emails ne partent pas
**Symptôme :** Erreur SMTP
**Solution :**
- Vérifier `config/mail.php`
- Vérifier `.env` (MAIL_*)
- Utiliser un "App Password" pour Gmail
- Activer "Less secure apps" si nécessaire

### Problème 5 : Docker ne démarre pas
**Symptôme :** Port déjà utilisé
**Solution :**
```bash
# Changer les ports dans docker-compose.yml
ports:
  - "8081:80"    # au lieu de 8080
  - "3307:3306"  # au lieu de 3306
```

### Problème 6 : Base de données vide
**Symptôme :** Erreur "Table doesn't exist"
**Solution :**
```bash
docker-compose exec mysql mysql -uroot -proot mini_wordpress < migrations/init.sql
docker-compose exec mysql mysql -uroot -proot mini_wordpress < migrations/002_add_user_activation.sql
```

---

## 📊 Comparaison des branches

| Fonctionnalité | main | houda | Décision |
|----------------|------|-------|----------|
| **Controllers** |
| PageController CRUD complet | ✅ | ❌ Basic | **GARDER main** |
| UserController CRUD admin | ✅ | ❌ Limité | **GARDER main** |
| AuthController complet | ⚠️ Partiel | ✅ Complet | **ADOPTER houda** |
| **Models** |
| User model avancé | ✅ | ⚠️ Basic | **GARDER main + ajouter activate()** |
| Page model avec slugs | ✅ | ⚠️ Basic | **GARDER main** |
| **Infrastructure** |
| Docker | ❌ | ✅ | **ADOPTER houda** |
| Composer + PHPMailer | ❌ | ✅ | **ADOPTER houda** |
| **Authentification** |
| Système activation email | ❌ | ✅ | **ADOPTER houda** |
| Reset password | ❌ | ✅ | **ADOPTER houda** |
| **Vues** |
| Admin pages sophistiquées | ✅ | ❌ | **GARDER main** |
| Admin users sophistiquées | ✅ | ❌ | **GARDER main** |
| Auth views complètes | ⚠️ | ✅ | **ADOPTER houda** |
| **Base de données** |
| Tables complètes | ✅ | ⚠️ | **GARDER main + ajouter colonnes houda** |
| Système tokens | ❌ | ✅ | **AJOUTER** |

---

## 🎓 Résumé de la stratégie

### ✅ ADOPTER de houda
1. **AuthController** (complet)
2. **Mail.php** (nouveau)
3. **Docker + Composer** (infrastructure)
4. **Vues auth** (améliorées)
5. **Système tokens** (activation + reset)
6. **Routes auth** (nouvelles)

### ✅ GARDER de main
1. **PageController** (CRUD sophistiqué)
2. **UserController** (admin complet)
3. **Page Model** (avec slugs)
4. **User Model** (base - ajouter activate())
5. **Vues admin** (pages + users)
6. **Schéma DB** (enrichi)

### ❌ EXCLURE
1. **.env** (sensitive)
2. **vendor/** (généré localement)
3. **package-lock.json** (inutile)

---

## 📞 Aide et support

Si vous rencontrez des problèmes :
1. Vérifier ce guide étape par étape
2. Consulter les logs Docker : `docker-compose logs`
3. Vérifier le .env
4. Tester les migrations SQL manuellement
5. Demander de l'aide avec le message d'erreur exact

---

**Bonne fusion ! 🚀**

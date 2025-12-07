# Guide d'Intégration - Comment Ajouter le Travail de Houda

## Guide Pratique Étape par Étape

Ce guide vous aide à intégrer le travail de Houda avec votre branche main de manière sécurisée et structurée.

---

## 📋 Vue d'ensemble

**Objectif** : Combiner le système d'authentification de Houda avec votre système CMS de la branche main.

**Approche recommandée** : Fusion sélective (cherry-picking) plutôt qu'un merge complet.

---

## 🚀 Étape 1 : Préparation

### 1.1 Créer une branche d'intégration

```bash
# Partir de votre branche main
git checkout main
git pull origin main

# Créer une nouvelle branche pour l'intégration
git checkout -b integration/houda-auth
```

### 1.2 Sauvegarder votre travail actuel

```bash
# Créer une branche de sauvegarde au cas où
git branch backup/main-before-integration
```

---

## 📦 Étape 2 : Intégrer l'Infrastructure Moderne

### 2.1 Ajouter Composer et les dépendances

```bash
# Récupérer composer.json de la branche houda
git checkout houda -- composer.json

# Installer les dépendances (NE PAS committer vendor/)
composer install

# Créer .gitignore si absent
cat >> .gitignore << EOF
/vendor/
.env
node_modules/
EOF
```

### 2.2 Ajouter Docker

```bash
# Récupérer la configuration Docker
git checkout houda -- Dockerfile docker-compose.yml

# Créer .env.example (sans données sensibles)
cat > .env.example << EOF
DB_HOST=db
DB_PORT=5432
DB_NAME=mini_wordpress
DB_USER=your_user
DB_PASSWORD=your_password

MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USER=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_FROM=noreply@example.com
EOF
```

**Important** : NE PAS récupérer le fichier `.env` de Houda (contient des données sensibles).

---

## 🔐 Étape 3 : Intégrer le Système d'Authentification

### 3.1 Récupérer les fichiers du Core

```bash
# Système de session amélioré
git checkout houda -- app/Core/Session.php

# Système d'emails (nouveau fichier Mail.php)
git checkout houda -- app/Core/Mail.php

# NE PAS supprimer Mailer.php si vous l'utilisez ailleurs
```

### 3.2 Récupérer le AuthController

```bash
git checkout houda -- app/Controllers/AuthController.php
```

### 3.3 Récupérer le modèle User amélioré

**ATTENTION** : Le User.php de Houda est simplifié. Il faut le fusionner manuellement.

```bash
# Voir les différences
git diff main houda -- app/Models/User.php

# Option 1 : Récupérer celui de Houda et ajouter vos méthodes
git checkout houda -- app/Models/User.php
# Puis éditez manuellement pour ajouter vos méthodes manquantes

# Option 2 : Garder le vôtre et ajouter les méthodes de Houda
# Méthodes à ajouter depuis Houda :
# - findByEmail()
# - activate()
# - create() avec support des tokens
```

### 3.4 Récupérer les vues d'authentification

```bash
git checkout houda -- app/Views/auth/login.php
git checkout houda -- app/Views/auth/register.php
git checkout houda -- app/Views/auth/forgot-password.php
git checkout houda -- app/Views/auth/reset-password.php
```

---

## 📄 Étape 4 : Gérer le Système de Pages

**IMPORTANT** : NE PAS écraser votre PageController et Page.php

### 4.1 Conserver votre système de pages

```bash
# Vérifier que vous gardez bien VOTRE version
git status

# Si PageController.php ou Page.php ont été modifiés par erreur :
git checkout main -- app/Controllers/PageController.php
git checkout main -- app/Models/Page.php
```

### 4.2 Ajouter l'authentification à PageController

Éditez manuellement `app/Controllers/PageController.php` pour ajouter :

```php
// Au début de chaque méthode admin (create, edit, delete)
if (!Session::get('user')) {
    header('Location: /login');
    exit;
}
```

---

## 🗄️ Étape 5 : Gérer la Base de Données

### 5.1 Décider : MySQL ou PostgreSQL ?

**Si vous restez avec MySQL** (recommandé pour la compatibilité) :

```bash
# Garder VOTRE migration
git checkout main -- migrations/init.sql
```

Puis éditez manuellement `migrations/init.sql` pour ajouter les champs manquants :

```sql
-- Ajouter ces colonnes à la table users :
ALTER TABLE users 
ADD COLUMN firstname VARCHAR(100),
ADD COLUMN lastname VARCHAR(100),
ADD COLUMN is_active BOOLEAN DEFAULT FALSE,
ADD COLUMN token VARCHAR(255);
```

**Si vous passez à PostgreSQL** :

```bash
# Récupérer la migration de Houda
git checkout houda -- migrations/init.sql

# Puis restaurer les champs sophistiqués de pages :
# - status
# - in_menu
# - menu_order
# - author_id
```

### 5.2 Adapter Database.php

Si vous restez avec MySQL :

```bash
# Garder votre version
git checkout main -- app/Core/Database.php
```

Si vous passez à PostgreSQL :

```bash
# Récupérer celle de Houda
git checkout houda -- app/Core/Database.php
```

---

## ⚙️ Étape 6 : Configuration

### 6.1 Mettre à jour les fichiers de config

```bash
# Récupérer les configs de Houda
git checkout houda -- config/mail.php

# Pour database.php et app.php, fusionner manuellement
# Gardez votre config DB si vous restez avec MySQL
```

### 6.2 Créer votre fichier .env

```bash
# Copier l'exemple
cp .env.example .env

# Éditer avec vos vraies valeurs
nano .env  # ou vim, code, etc.
```

---

## 🧪 Étape 7 : Tester l'Intégration

### 7.1 Installer et démarrer

```bash
# Option 1 : Avec Docker
docker compose up -d
docker compose exec app composer install

# Option 2 : Sans Docker
composer install
# Configurer votre serveur web local (XAMPP, WAMP, etc.)
```

### 7.2 Initialiser la base de données

```bash
# Se connecter à votre DB et exécuter
mysql -u root -p mini_wordpress < migrations/init.sql
# OU si PostgreSQL :
psql -U postgres -d mini_wordpress -f migrations/init.sql
```

### 7.3 Tester les fonctionnalités

1. **Test inscription** : Aller sur `/register`
   - Remplir le formulaire
   - Vérifier l'email d'activation
   - Cliquer sur le lien d'activation

2. **Test connexion** : Aller sur `/login`
   - Se connecter avec le compte activé
   - Vérifier la redirection vers admin

3. **Test pages** : 
   - Créer une page
   - Vérifier que tous les champs sont présents (status, menu, etc.)

---

## 🔍 Étape 8 : Nettoyer et Finaliser

### 8.1 Vérifier les fichiers à committer

```bash
git status

# S'assurer que vendor/ et .env ne sont PAS dans la liste
# Si oui, les ajouter à .gitignore et :
git rm --cached -r vendor/ 2>/dev/null || true
git rm --cached .env 2>/dev/null || true
```

### 8.2 Committer l'intégration

```bash
git add .
git commit -m "feat: Integrate Houda's authentication system with main CMS

- Add authentication system (register, login, activation, password reset)
- Add Docker infrastructure for development
- Add Composer dependencies (PHPMailer, Symfony YAML)
- Add Mail system for email notifications
- Enhance Session management
- Keep sophisticated page management from main
- Update User model with authentication fields
- Add security best practices"
```

### 8.3 Tester une dernière fois

```bash
# Relancer les tests complets
# Vérifier que tout fonctionne
```

---

## ✅ Checklist Finale

Avant de merger dans main, vérifier :

- [ ] Composer est configuré et fonctionne
- [ ] Docker fonctionne (si utilisé)
- [ ] .env n'est PAS commité
- [ ] vendor/ n'est PAS commité
- [ ] L'inscription fonctionne
- [ ] L'activation par email fonctionne
- [ ] La connexion fonctionne
- [ ] Les sessions fonctionnent
- [ ] Le système de pages est complet (status, menu, etc.)
- [ ] Les migrations DB sont correctes
- [ ] La configuration mail est correcte
- [ ] Tous les tests passent

---

## 🚨 Problèmes Courants

### Problème : Conflit lors du checkout

```bash
# Si git refuse de checkout un fichier (fichier modifié localement)
# D'abord sauvegarder vos modifications
git stash
# Puis récupérer le fichier
git checkout houda -- path/to/file
# Ou utiliser git restore
git restore --source=houda path/to/file
```

### Problème : Vendor déjà commité

```bash
# Si vendor/ est déjà dans l'historique git
git rm -r --cached vendor/ 2>/dev/null || true
echo "/vendor/" >> .gitignore
git add .gitignore
git commit -m "chore: Remove vendor directory from git"
```

### Problème : Base de données incompatible

```bash
# Si les migrations ne passent pas, vérifier :
# 1. La syntaxe SQL correspond à votre DB (MySQL vs PostgreSQL)
# 2. Les champs correspondent entre migration et modèles
# 3. Les types de données sont corrects
```

### Problème : Emails ne partent pas

```bash
# Vérifier .env :
# - MAIL_HOST est correct
# - MAIL_PORT est correct
# - MAIL_USER et MAIL_PASSWORD sont corrects
# - Votre hébergeur autorise l'envoi SMTP
```

---

## 📚 Ressources Additionnelles

- **Analyse complète** : Voir `ANALYSE_BRANCHE_HOUDA.md`
- **Résumé exécutif** : Voir `RESUME_ANALYSE.md`
- **Documentation Git** : https://git-scm.com/doc
- **Documentation Composer** : https://getcomposer.org/doc/
- **Documentation Docker** : https://docs.docker.com/

---

## 💡 Conseil Final

**Ne pas précipiter l'intégration !**

1. Commencer par une branche de test
2. Tester chaque fonctionnalité
3. Documenter les changements
4. Faire une revue de code
5. Merger seulement quand tout fonctionne

**La qualité prime sur la vitesse.**

---

*Guide créé le 7 décembre 2025*
*Basé sur l'analyse comparative des branches main et houda*

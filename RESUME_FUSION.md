# Résumé de la Fusion - Guide Rapide

## 🎯 Objectif
Fusionner le système d'authentification de la branche `houda` avec les fonctionnalités CMS avancées de la branche `main`.

---

## ⚡ Actions Rapides

### 1. Créer la branche d'intégration
```bash
git checkout main
git checkout -b integration/houda-auth
```

### 2. Fichiers à adopter COMPLÈTEMENT de houda
```bash
# Infrastructure
git checkout houda -- Dockerfile
git checkout houda -- docker-compose.yml
git checkout houda -- composer.json

# Authentification
git checkout houda -- app/Controllers/AuthController.php
git checkout houda -- app/Core/Mail.php
git checkout houda -- config/mail.php

# Vues auth
git checkout houda -- app/Views/auth/login.php
git checkout houda -- app/Views/auth/register.php
git checkout houda -- app/Views/auth/forgot-password.php
git checkout houda -- app/Views/auth/reset-password.php
```

### 3. Fichiers à GARDER de main (ne rien faire)
- ✅ `app/Controllers/PageController.php`
- ✅ `app/Controllers/UserController.php`
- ✅ `app/Models/Page.php`
- ✅ `app/Views/admin/pages/*`
- ✅ `app/Views/admin/users/*`

### 4. Fichiers à fusionner MANUELLEMENT

**User.php** - Ajouter cette méthode au modèle main :
```php
/**
 * Activer un utilisateur via email et token
 */
public static function activate(string $email, string $token): int
{
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 0;
    }
    
    // Validation du token (doit être hexadécimal, 64 caractères)
    if (!ctype_xdigit($token) || strlen($token) !== 64) {
        return 0;
    }
    
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

### 5. Créer .gitignore
```bash
cat > .gitignore << 'EOF'
.env
vendor/
.vscode/
.idea/
.DS_Store
EOF
```

### 6. Créer .env.example
```bash
cat > .env.example << 'EOF'
DB_HOST=mysql
DB_NAME=mini_wordpress
DB_USER=root
DB_PASS=root

MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_FROM=votre-email@gmail.com
MAIL_FROM_NAME="Mini WordPress"

APP_URL=http://localhost:8080
APP_ENV=development
EOF
```

### 7. Migration base de données
Créer `migrations/002_add_user_activation.sql` :
```sql
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS token VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT FALSE;

UPDATE users SET is_active = TRUE WHERE is_active IS NULL OR is_active = FALSE;
```

### 8. Ajouter routes auth dans routes/routes.yaml
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

### 9. Lancer l'environnement
```bash
# Copier .env
cp .env.example .env
# Éditer .env avec vos credentials

# Démarrer Docker
docker-compose up -d

# Installer dépendances
docker-compose exec app composer install

# Importer DB (utilisez les credentials de votre docker-compose.yml)
docker-compose exec mysql mysql -u${DB_USER:-root} -p${DB_PASS:-root} mini_wordpress < migrations/init.sql
docker-compose exec mysql mysql -u${DB_USER:-root} -p${DB_PASS:-root} mini_wordpress < migrations/002_add_user_activation.sql
```

### 10. Tester
- ✅ `/register` - Inscription
- ✅ Email d'activation reçu
- ✅ Lien d'activation fonctionne
- ✅ `/login` - Connexion
- ✅ `/admin/pages` - CRUD pages (de main)
- ✅ `/admin/users` - CRUD users (de main)
- ✅ `/forgot-password` - Reset password

### 11. Commit et Push
```bash
git add .
git commit -m "feat: Merge authentication system from houda branch"
git push origin integration/houda-auth
```

---

## ❌ À NE JAMAIS COMMITER
- ❌ `.env` (credentials)
- ❌ `vendor/` (dépendances)
- ❌ `package-lock.json` (inutile)

---

## 🎁 Ce que vous obtenez

### De houda ✨
- 🔐 Système d'authentification complet
- 📧 Activation par email
- 🔑 Réinitialisation de mot de passe
- 🐳 Docker (PHP + MySQL + phpMyAdmin)
- 📦 Composer + PHPMailer

### De main ✨
- 📄 CRUD pages sophistiqué (statuts, menu, slugs)
- 👥 CRUD utilisateurs admin complet
- 🔒 Middlewares de protection
- 🎨 Vues admin professionnelles
- 🗃️ Modèles avancés avec pagination

---

## 🚨 Problèmes fréquents

| Problème | Solution |
|----------|----------|
| PHPMailer not found | `composer install` |
| DB connection failed | Vérifier `.env` |
| Emails ne partent pas | Vérifier config SMTP dans `.env` |
| Port 8080 occupé | Changer port dans `docker-compose.yml` |
| Table doesn't exist | Exécuter les migrations SQL |

---

**Guide complet :** Voir `GUIDE_FUSION_BRANCHES.md`

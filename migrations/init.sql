-- =====================================================================
-- 📊 SCRIPT D'INITIALISATION DE LA BASE DE DONNÉES
-- =====================================================================
--
-- Ce fichier contient les commandes SQL pour créer les tables
-- nécessaires au fonctionnement de Mini WordPress.
--
-- 💡 COMMENT L'UTILISER ?
--    1. Ouvre MySQL (phpMyAdmin, MySQL Workbench, ou terminal)
--    2. Crée la base de données : CREATE DATABASE mini_wordpress;
--    3. Sélectionne-la : USE mini_wordpress;
--    4. Exécute ce script
--
-- =====================================================================

-- ================================
-- 👥 TABLE USERS (Utilisateurs)
-- ================================
-- Stocke les informations des utilisateurs du site

CREATE TABLE IF NOT EXISTS users (
    -- 🔑 id : Identifiant unique, auto-incrémenté
    -- INT = nombre entier
    -- AUTO_INCREMENT = s'incrémente automatiquement à chaque INSERT
    -- PRIMARY KEY = clé primaire (identifiant unique)
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- 📧 email : Adresse email (unique pour chaque utilisateur)
    -- VARCHAR(255) = chaîne de max 255 caractères
    -- NOT NULL = obligatoire
    -- UNIQUE = aucun doublon autorisé
    email VARCHAR(255) NOT NULL UNIQUE,
    
    -- 🔒 password : Mot de passe HASHÉ (jamais en clair !)
    -- Les hashes PHP font ~60 caractères, mais 255 pour le futur
    password VARCHAR(255) NOT NULL,
    
    -- 👤 name : Nom d'affichage
    name VARCHAR(100) NOT NULL,
    
    -- 🎭 role : Rôle de l'utilisateur
    -- ENUM = liste de valeurs autorisées
    -- DEFAULT = valeur par défaut si non spécifiée
    role ENUM('admin', 'editor', 'user') DEFAULT 'user',
    
    -- 📧 Vérification d'email
    email_token VARCHAR(100) DEFAULT NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    
    -- 🔑 Réinitialisation de mot de passe
    reset_token VARCHAR(100) DEFAULT NULL,
    reset_token_expires DATETIME DEFAULT NULL,
    
    -- 📅 Date de création
    -- DATETIME = date et heure
    -- DEFAULT CURRENT_TIMESTAMP = date/heure actuelle
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- 📄 TABLE PAGES (Pages de contenu)
-- ================================
-- Stocke les pages du site (À propos, Contact, etc.)

CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- 📝 Titre de la page
    title VARCHAR(200) NOT NULL,
    
    -- 🔗 Slug = URL amicale (ex: "a-propos" pour "À propos")
    slug VARCHAR(200) NOT NULL UNIQUE,
    
    -- 📄 Contenu HTML de la page
    -- TEXT = pour les textes longs (jusqu'à 65,535 caractères)
    content TEXT,
    
    -- 👁️ Statut de publication
    -- 1 = publiée, 0 = brouillon
    published BOOLEAN DEFAULT FALSE,
    
    -- 📅 Dates
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- 🌱 DONNÉES INITIALES (Seeds)
-- ================================
-- Crée un administrateur par défaut et quelques pages d'exemple

-- Utilisateur admin (mot de passe: "admin123" hashé)
-- ⚠️ CHANGE CE MOT DE PASSE EN PRODUCTION !
INSERT INTO users (email, password, name, role, email_verified) VALUES
('admin@mini-wordpress.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', 'admin', TRUE);

-- Pages d'exemple
INSERT INTO pages (title, slug, content, published) VALUES
('Accueil', 'accueil', '<h2>Bienvenue sur Mini WordPress !</h2><p>Ceci est votre page d''accueil. Modifiez-la depuis l''administration.</p>', TRUE),
('À propos', 'a-propos', '<h2>À propos de nous</h2><p>Mini WordPress est un projet éducatif pour apprendre PHP.</p>', TRUE),
('Contact', 'contact', '<h2>Nous contacter</h2><p>Vous pouvez nous joindre par email.</p>', TRUE);

-- ================================
-- 📊 VÉRIFICATION
-- ================================
-- Ces commandes affichent les tables créées

-- SHOW TABLES;
-- DESCRIBE users;
-- DESCRIBE pages;
-- SELECT * FROM users;
-- SELECT * FROM pages;

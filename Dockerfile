# =====================================================================
# 🐳 DOCKERFILE - Image PHP/Apache pour Mini WordPress
# =====================================================================
#
# Ce fichier définit comment construire l'image Docker du serveur web.
#
# 💡 ÉTAPES DE CONSTRUCTION :
#    1. Partir d'une image PHP officielle avec Apache
#    2. Installer les extensions PHP nécessaires
#    3. Configurer Apache pour le projet
#    4. Copier le code source
#
# =====================================================================

# 📦 Image de base : PHP 8.2 avec Apache
FROM php:8.2-apache

# 👤 Informations sur le mainteneur
LABEL maintainer="Mini WordPress Learning Project"
LABEL description="Environnement PHP/Apache pour apprendre le développement web"

# ================================
# 📦 INSTALLATION DES DÉPENDANCES
# ================================

# Mise à jour et installation des outils nécessaires
RUN apt-get update && apt-get install -y \
    # Pour l'extension GD (manipulation d'images)
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    # Pour l'extension ZIP
    libzip-dev \
    zip \
    unzip \
    # Pour l'extension YAML
    libyaml-dev \
    # Outils utiles
    vim \
    curl \
    && rm -rf /var/lib/apt/lists/*

# ================================
# 🔧 INSTALLATION DES EXTENSIONS PHP
# ================================

# Configuration et installation de GD (images)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Installation des autres extensions
RUN docker-php-ext-install \
    # PDO MySQL - Pour se connecter à la base de données
    pdo \
    pdo_mysql \
    # ZIP - Pour manipuler les archives
    zip

# Installation de l'extension YAML via PECL
RUN pecl install yaml && docker-php-ext-enable yaml

# ================================
# ⚙️ CONFIGURATION D'APACHE
# ================================

# Active le module rewrite (pour les URLs propres)
RUN a2enmod rewrite

# Configure le DocumentRoot vers /var/www/html/public
# C'est là que se trouve notre index.php
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Autorise les fichiers .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ================================
# 📂 COPIE DU CODE SOURCE
# ================================

# Copie tout le projet dans le conteneur
COPY . /var/www/html

# Définit les permissions correctes
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ================================
# 🚀 DÉMARRAGE
# ================================

# Expose le port 80 (Apache)
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]

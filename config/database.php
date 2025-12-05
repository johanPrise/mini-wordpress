<?php
/**
 * =====================================================================
 * ⚙️ CONFIGURATION DE LA BASE DE DONNÉES
 * =====================================================================
 * 
 * Ce fichier contient les constantes pour se connecter à MySQL.
 * 
 * 🔒 SÉCURITÉ : Ne jamais commiter ce fichier avec de vrais mots de passe !
 *              En production, utilisez des variables d'environnement.
 * 
 * 💡 Les constantes (const) sont utilisées car ces valeurs ne changent
 *    JAMAIS pendant l'exécution du programme.
 * =====================================================================
 */

/**
 * 🖥️ DB_HOST : Adresse du serveur MySQL
 * 
 * - 'localhost' = Le serveur est sur la même machine
 * - En production, ce sera l'adresse IP ou le nom du serveur
 */
const DB_HOST = 'localhost';

/**
 * 📁 DB_NAME : Nom de la base de données
 * 
 * Tu dois créer cette base dans MySQL avant de lancer l'application.
 * Commande SQL : CREATE DATABASE mini_wordpress;
 */
const DB_NAME = 'mini_wordpress';

/**
 * 👤 DB_USER : Nom d'utilisateur MySQL
 * 
 * - 'root' = L'administrateur par défaut de MySQL
 * - En production, crée un utilisateur dédié avec moins de droits !
 */
const DB_USER = 'root';

/**
 * 🔑 DB_PASSWORD : Mot de passe MySQL
 * 
 * - Vide en développement local (souvent la config par défaut)
 * - ⚠️ EN PRODUCTION : Utilise TOUJOURS un mot de passe fort !
 */
const DB_PASSWORD = '';

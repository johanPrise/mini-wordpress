<?php
/**
 * =====================================================================
 * ⚙️ CONFIGURATION GÉNÉRALE DE L'APPLICATION
 * =====================================================================
 * 
 * Ce fichier contient les constantes globales de l'application.
 * Ces valeurs sont accessibles partout dans le code.
 * 
 * 💡 Utilise ces constantes dans tes vues pour afficher le nom de l'app,
 *    générer des liens, etc.
 *    Exemple : <title><?php echo APP_NAME; ?></title>
 * =====================================================================
 */

/**
 * 📛 APP_NAME : Nom de l'application
 * 
 * Utilisé dans les titres de page, les emails, etc.
 */
const APP_NAME = 'Mini_WordPress';

/**
 * 🏷️ APP_VERSION : Version actuelle de l'application
 * 
 * Suit le format Semantic Versioning (semver) : MAJEUR.MINEUR.PATCH
 * - MAJEUR : changements incompatibles
 * - MINEUR : nouvelles fonctionnalités compatibles
 * - PATCH : corrections de bugs
 */
const APP_VERSION = "1.0.0";

/**
 * 🌐 APP_URL : URL de base de l'application
 * 
 * Utilisé pour générer des liens absolus (emails, redirections, etc.)
 * ⚠️ Change cette valeur en production !
 */
const APP_URL = 'http://localhost:8000';
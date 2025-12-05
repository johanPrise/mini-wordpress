<?php
/**
 * =====================================================================
 * 📚 POINT D'ENTRÉE DE L'APPLICATION (Front Controller)
 * =====================================================================
 * 
 * Ce fichier est le SEUL point d'entrée de l'application.
 * Toutes les requêtes HTTP passent par ici grâce au fichier .htaccess
 * 
 * 🎯 Son rôle :
 *    1. Démarrer la session utilisateur
 *    2. Charger les configurations
 *    3. Analyser l'URL demandée
 *    4. Trouver la route correspondante
 *    5. Appeler le bon Controller et la bonne méthode
 * 
 * 💡 C'est le "chef d'orchestre" de l'application !
 * =====================================================================
 */

// 🔐 session_start() : Démarre ou reprend une session utilisateur
// Les sessions permettent de stocker des infos entre les pages (ex: utilisateur connecté)
// DOIT être appelé AVANT tout output (echo, HTML, etc.)
session_start();

// 📦 require_once : Inclut un fichier PHP une seule fois
// __DIR__ : Chemin absolu du dossier courant (public/)
// ../ : Remonte d'un niveau dans l'arborescence
require_once __DIR__ . '/../config/database.php';  // Configuration base de données
require_once __DIR__ . '/../config/mail.php';      // Configuration email
require_once __DIR__ . '/../config/app.php';       // Configuration générale

// 🌐 $_SERVER['REQUEST_URI'] : Contient l'URL demandée par l'utilisateur
// Exemple : "/login" ou "/admin/users?page=2"
$uri = $_SERVER['REQUEST_URI'];

// 🔪 explode('?', $uri) : Coupe la chaîne en morceaux au niveau du "?"
// Exemple : "/page?id=5" devient ["/page", "id=5"]
// On garde seulement la première partie (l'URL sans les paramètres GET)
$uriExploded = explode('?', $uri);
if(is_array($uriExploded)){
    $uri = $uriExploded[0];  // On ne garde que la partie avant le "?"
}

// ✂️ rtrim($uri, '/') : Supprime les "/" à la fin de l'URL
// Exemple : "/login/" devient "/login"
// Cela évite d'avoir 2 routes différentes pour la même page
if(strlen($uri > 1)){
    $uri = rtrim($uri, '/');
}

// 📋 CHARGEMENT DU FICHIER DE ROUTES
// ===================================
// Le fichier routes.yaml définit toutes les URLs de l'application
// Format YAML : facile à lire et modifier

$routesFile = __DIR__ . '/../routes/routes.yaml';
if (! file_exists($routesFile)) {
    die("❌ Fichier routes.yml introuvable");
}

// 📖 yaml_parse_file() : Lit un fichier YAML et le convertit en tableau PHP
// Le résultat est un tableau associatif : $routes['/login'] = ['controller' => 'AuthController', 'action' => 'showLogin']
$routes = yaml_parse_file($routesFile);

// 🔍 RECHERCHE DE LA ROUTE CORRESPONDANTE
// ========================================
// On vérifie si l'URL demandée existe dans notre fichier de routes

if (empty($routes[$uri])) {
    http_response_code(404);  // Code HTTP "Page non trouvée"
    die("❌ 404 - Page non trouvée");
}

// 🎮 EXTRACTION DU CONTROLLER ET DE L'ACTION
// ===========================================
// Chaque route doit définir :
// - controller : quelle classe PHP va traiter la requête
// - action : quelle méthode de cette classe appeler

if (empty($routes[$uri]["controller"])) {
    die("❌ Pas de controller pour cette route");
}
if (empty($routes[$uri]["action"])) {
    die("❌ Pas d'action pour cette route");
}

$controller = $routes[$uri]["controller"];  // Ex: "HomeController"
$action = $routes[$uri]["action"];          // Ex: "index"

// 📂 CHARGEMENT DU FICHIER CONTROLLER
// ====================================
// On construit le chemin vers le fichier du controller
$controllerFile = __DIR__ . '/../app/Controllers/' . $controller . '.php';
if (!file_exists($controllerFile)) {
    die("❌ Controller introuvable : " .  $controller);
}
require_once $controllerFile;  // Inclut le fichier PHP du controller

// ✅ VÉRIFICATION DE L'EXISTENCE DE LA CLASSE
// ============================================
// class_exists() vérifie qu'une classe PHP est bien définie
if (!class_exists($controller)) {
    die("❌ Classe introuvable : " .  $controller);
}

// 🏗️ CRÉATION DE L'INSTANCE DU CONTROLLER
// =========================================
// new $controller() : Crée un nouvel objet de la classe
// La variable $controller contient le NOM de la classe (ex: "HomeController")
$objController = new $controller();

// ✅ VÉRIFICATION DE L'EXISTENCE DE LA MÉTHODE
// =============================================
// method_exists() vérifie qu'une méthode existe dans un objet
if (!method_exists($objController, $action)) {
    die("❌ Méthode introuvable : " .  $action);
}

// 🚀 EXÉCUTION DE L'ACTION
// =========================
// $objController->$action() : Appelle la méthode dont le nom est dans $action
// Exemple : si $action = "index", ça appelle $objController->index()
$objController->$action();
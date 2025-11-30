<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../config/app.php';

$uri = $_SERVER['REQUEST_URI'];

$uriExploded = explode('?', $uri);
if(is_array($uriExploded)){
    $uri = $uriExploded[0];
}
if(strlen($uri > 1)){
    $uri = rtrim($uri, '/');
}

$routesFile = __DIR__ . '/../routes.yml';
if (! file_exists($routesFile)) {
    die("❌ Fichier routes.yml introuvable");
}
$routes = yaml_parse_file($routesFile);

if (empty($routes[$uri])) {
    http_response_code(404);
    die("❌ 404 - Page non trouvée");
}

if (empty($routes[$uri]["controller"])) {
    die("❌ Pas de controller pour cette route");
}
if (empty($routes[$uri]["action"])) {
    die("❌ Pas d'action pour cette route");
}

$controller = $routes[$uri]["controller"];
$action = $routes[$uri]["action"];

$controllerFile = __DIR__ . '/../app/Controllers/' . $controller . '.php';
if (!file_exists($controllerFile)) {
    die("❌ Controller introuvable : " .  $controller);
}
require_once $controllerFile;

if (!class_exists($controller)) {
    die("❌ Classe introuvable : " .  $controller);
}

$objController = new $controller();

if (!method_exists($objController, $action)) {
    die("❌ Méthode introuvable : " .  $action);
}

$objController->$action();
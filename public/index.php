<?php

use App\Core\App;

spl_autoload_register(function ($class) {

    // Exemple: App\Core\Database → app/Core/Database.php
    $class = str_replace("App\\", "", $class);
    $class = str_replace("\\", "/", $class);

    $file = __DIR__ . "/../" . $class . ".php";

    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Autoload error: fichier introuvable → $file");
    }
});

require_once __DIR__ . '/../app/Core/App.php';
require_once __DIR__ . '/../vendor/autoload.php'; // optionnel si tu utilises composer

$app = new App();
$app->run();

<?php

namespace App\Core;

use Symfony\Component\Yaml\Yaml;

class Router
{
    public function handleRequest()
    {
        // Nettoyer l'URL
        $uri = strtok($_SERVER['REQUEST_URI'], '?');

        // Charger routes.yaml si on veut garder les deux
        $yamlRoutes = [];
        $yamlFile = __DIR__ . '/../../routes/routes.yaml';
        if (file_exists($yamlFile)) {
            $yamlRoutes = Yaml::parseFile($yamlFile);
        }

        // Charger routes web.php
        $phpRoutes = require __DIR__ . '/../../routes/web.php';

        // Fusionner YAML + PHP
        $routes = array_merge($yamlRoutes, $phpRoutes);

        foreach ($routes as $route => $params) {

            // ROUTES DYNAMIQUES : /page/{slug}
            if (preg_match('#\{([a-zA-Z0-9_]+)\}#', $route)) {

                $pattern = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([a-zA-Z0-9\-]+)', $route);

                if (preg_match("#^$pattern$#", $uri, $match)) {
                    $controller = $params[0];
                    $action = $params[1];

                    return (new $controller())->$action($match[1]);
                }
            }

            // ROUTES SIMPLES
            if ($uri === $route) {
                $controller = $params[0];
                $action = $params[1];

                return (new $controller())->$action();
            }
        }

        http_response_code(404);
        echo "<h1>404 - Page introuvable</h1>";
    }
}
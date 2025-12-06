<?php
namespace App\Core;

use Symfony\Component\Yaml\Yaml;

class App{
    protected Router $router;

    public function __construct()
    {
        Session::start();
        $this->router = new Router();
    }

    public function run(): void
    {
        // 1. Charger les routes depuis YAML
        $this->loadRoutes();
        // 2. Récupérer l'URI et méthode HTTP actuelles
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // 3. Dispatcher
        $this->router->dispatch($uri, $method);
    }

    protected function loadRoutes(): void
    {
        $routesFile = dirname(__DIR__, 2) . '/routes/routes.yaml';

        if (!file_exists($routesFile)) {
            throw new \RuntimeException("Le fichier routes.yaml n'existe pas : " . $routesFile);
        }

        $routes = Yaml::parseFile($routesFile);

        foreach ($routes as $path => $config) {
            $controller = 'App\\Controllers\\' . $config['controller'];
            $action = $config['action'];
            $method = strtoupper($config['method'] ?? 'GET');

            $this->router->addRoute($method, $path, $controller . '@' . $action);
        }
    }
}

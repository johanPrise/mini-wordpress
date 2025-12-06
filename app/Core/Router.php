<?php
namespace App\Core;


class Router{
    protected array $routes = [];

    public function addRoute($method, $path, $controller){
        $compiled = $this->compileRoute($path);
        $this->routes[] = [
            'method' => $method,
            'regex' => $compiled['regex'],
            'params' => $compiled['params'],
            'controller' => $controller
        ];
    }

    public function match($method, $path)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['regex'], $path, $matches)) {
                $params = array_combine($route['params'], array_slice($matches, 1));
                return [
                    'controller' => $route['controller'],
                    'params' => $params
                ];
            }
        }
        return null;
    }

    protected function compileRoute(string $path): array
    {
        $params = []; // stocke les noms comme ['id', 'slug']

        // preg_replace_callback trouve chaque {xxx} et le remplace
        $regex = preg_replace_callback('#\{([^}]+)\}#', function ($match) use (&$params) {
            $params[] = $match[1]; // $match[1] = le nom du param (ex: 'id')
            return '([^/]+)';      // remplace {id} par la capture regex
        }, $path);

        // Ajoute les délimiteurs et ancres
        $regex = '#^' . $regex . '/?$#';

        return [
            'regex' => $regex,
            'params' => $params
        ];
    }

    protected function invokeHandler($handler, array $params)
    {
        // Cas 1: closure
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
       //Cas 2: "Controller@method"
        if(is_string($handler) && strpos($handler, '@') !== false) {
            [$controller, $method] = explode('@', $handler);
            $controller = new $controller();
            return call_user_func_array([$controller, $method], $params);
        }
        throw new \InvalidArgumentException("Invalid handler: $handler");
    }

    public function dispatch(string $uri, string $method): void
    {
        // Nettoyer l'URI (enlever les query strings)
        $uri = parse_url($uri, PHP_URL_PATH);

        $match = $this->match($method, $uri);

        if ($match) {
            $this->invokeHandler($match['controller'], $match['params']);
        } else {
            // Route non trouvée - 404
            http_response_code(404);
            echo "Page non trouvée (404)";
        }
    }
}
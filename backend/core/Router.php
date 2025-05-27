<?php
namespace MyFramework\Core;

class Router {
    private $routes = [];

    public function add_route($path, $callback, $method = 'POST') {
        $this->routes[] = [
            'path' => trim($path, '/'),
            'callback' => $callback,
            'method' => strtoupper($method)
        ];
        error_log("Router - Ruta registrada: Path: {$path}, Method: {$method}");
    }

    public function dispatch($path, $method, $db) {
        $path = trim($path, '/');
        $method = strtoupper($method);

        error_log("Router - Dispatching: Path: $path, Method: $method");
        error_log("Router - Rutas registradas: " . print_r($this->routes, true));

        foreach ($this->routes as $route) {
            error_log("Router - Comparando: Route Path: {$route['path']}, Route Method: {$route['method']} con Path: $path, Method: $method");
            if ($route['path'] === $path && $route['method'] === $method) {
                if (is_array($route['callback']) && is_callable($route['callback'])) {
                    error_log("Router - Ruta encontrada, ejecutando callback: " . print_r($route['callback'], true));
                    return call_user_func($route['callback'], $db);
                }
                return ['success' => false, 'message' => 'Callback no válido.'];
            }
        }

        return ['success' => false, 'message' => 'Ruta no encontrada.'];
    }
}
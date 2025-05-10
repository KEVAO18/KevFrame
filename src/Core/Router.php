<?php

namespace App\Core;

use App\Http\Controllers\ErrorController;

class Router
{
    private $routes = [];

    /**
     * Registra una nueva ruta en el enrutador
     * 
     * @param string $method Método HTTP (GET, POST, etc.)
     * @param string $path Ruta URL
     * @param string $controller Nombre completo de la clase controladora
     * @param string $action Nombre del método accion
     */
    public function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Maneja la solicitud HTTP y ejecuta la acción correspondiente
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request_method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$request_method] as $path => $route) {
            if ($path === $request_uri) {
                $controller = new $route['controller']();
                $controller->{$route['action']}();
                return;
            }
        }

        $controller = new ErrorController();
        $controller->notFound();
    }

    /**
     * Obtiene todas las rutas registradas
     *
     * @return array
     * 
     */
    public function getRoutes(): array{
        return $this->routes;
    }
}
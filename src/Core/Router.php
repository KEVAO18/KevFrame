<?php

namespace App\Core;

use App\Core\Request;
use App\Http\Controllers\ErrorController;
use App\Security\CSRF;

class Router{
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
        // Verificar si el método HTTP existe en el arreglo de rutas
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        // Verificar si la ruta ya existe en el arreglo de rutas
        if (isset($this->routes[$method][$path])) {
            throw new \Exception("La ruta '$path' ya existe para el método '$method'.");
        }

        // Verificar si la clase controladora existe
        if (!class_exists($controller)) {
            throw new \Exception("La clase controladora '$controller' no existe.");
        }

        // Verificar si el método acción existe en la clase controladora
        if (!method_exists(new $controller(), $action)) {
            throw new \Exception("El método acción '$action' no existe en la clase controladora '$controller'.");
        }

        // Extraer los parámetros
        preg_match_all('/\{([^}]+)\}/', $path, $matches);
        $params = $matches[1];

        // Convertir la ruta a una expresión regular
        $regex = preg_replace('/\{[^}]+\}/', '([^/]+)', $path);
        $regex = "#^" . $regex . "$#";

        // Agregar la ruta al arreglo de rutas
        $this->routes[$method][] = [
            'original_path' => $path,
            'regex' => $regex,
            'controller' => $controller,
            'action' => $action,
            'params' => $params
        ];

    }

    /**
     * Maneja la solicitud HTTP y ejecuta la acción correspondiente
     * 
     * @return void
     */
    public function dispatch(): void
    {

        // Verificar el token CSRF
        CSRF::verifyToken();

        // Obtener la ruta solicitada
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Obtener el método HTTP
        $request_method = $_SERVER['REQUEST_METHOD'];

        // Verificar el método HTTP
        if (!isset($this->routes[$request_method])) {
            $controller = new ErrorController(500);
            return;
        }

        // Buscar la ruta correspondiente
        foreach ($this->routes[$request_method] as $route) {

            // Verificar si la ruta coincide con la solicitud
            if (preg_match($route['regex'], $request_uri, $matches)) {

                // Eliminar el primer elemento del arreglo de coincidencias
                array_shift($matches);

                // Asociar valores con nombres
                $params = array_combine($route['params'], $matches);

                // crear un objeto Request
                $request = new Request($params);

                $controller = new $route['controller']();
                $controller->{$route['action']}($request);

                return;
            }
        }

        $controller = new ErrorController(404);
    }

    /**
     * Obtiene todas las rutas registradas
     *
     * @return array
     * 
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Métodos para agregar rutas de manera más concisa
     * 
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function get(string $path, string $controller, string $action): void
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    /**
     * Métodos para agregar rutas de manera más concisa
     * 
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function post(string $path, string $controller, string $action): void
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    /**
     * Métodos para agregar rutas de manera más concisa
     *
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function put(string $path, string $controller, string $action): void
    {
        $this->addRoute('PUT', $path, $controller, $action);
    }

    /**
     * Métodos para agregar rutas de manera más concisa
     *
     * @param string $method
     * @param string $path
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function delete(string $path, string $controller, string $action): void
    {
        $this->addRoute('DELETE', $path, $controller, $action);
    }
}
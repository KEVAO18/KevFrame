<?php

namespace App\Core;

use App\Core\Router;
use App\Http\Controllers\IndexController;

// Crear una instancia de la clase Router y asignarla a la variable $router
$router = new Router();

/**
 * ruta principal
 */
$router->get('/', IndexController::class, 'index');
$router->get('/home', IndexController::class, 'index');

/**
 * ejecucion de la ruta solicitada
 */
$router->dispatch();

<?php

namespace App\Core;

use App\Core\Router;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\UsuarioController;

// Crear una instancia de la clase Router y asignarla a la variable $router
$router = new Router();

/**
 * ruta principal
 */
$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/home', IndexController::class, 'index');

/**
 * rutas de productos
 * 
 * 1. productos
 * 2. producto/{id}
 * 3. productos/{filtro}/{valor}
 * 4. productos/top_comprados
 * 
 */
// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos', ProductoController::class, 'index');

/**
 * 
 * rutas de carrito
 * 
 * 1. carrito
 * 
 */
// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Carrito', CarritoController::class, 'index');

/**
 * Usuario
 * 
 * 1. perfil/{username}
 * 2. configuracion
 * 3. historial_compras
 * 4. pedidos
 * 5. pedido/{id}
 * 
 */

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/perfil/{user_id}', UsuarioController::class, 'perfil');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/login', UsuarioController::class, 'perfil');

// $router->addRoute('PATH', $_ENV["APP_DIRECTORY"].'/login', UsuarioController::class, 'perfil');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/login', UsuarioController::class, 'register');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/configuracion', UsuarioController::class, 'config');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/historial_compras', UsuarioController::class, 'historial');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/pedidos', UsuarioController::class, 'pedidos');

$router->dispatch();

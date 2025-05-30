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
 * 
 */
// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos', ProductoController::class, 'index');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos/{id}', ProductoController::class, 'producto');

// $router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos/{filtro}/{valor}', ProductoController::class, 'filtro');

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

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/configuracion', UsuarioController::class, 'configuracion');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/historial_compras', UsuarioController::class, 'historial_compras');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/pedidos', UsuarioController::class, 'pedidos');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/pedido/{id}', UsuarioController::class, 'pedido');

$router->dispatch();

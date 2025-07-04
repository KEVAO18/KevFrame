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
    
$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/', IndexController::class, 'index');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/home', IndexController::class, 'index');

/**
 * rutas de productos
 * 
 * 1. productos
 * 2. producto/{id}
 * 3. productos/{filtro}/{valor}
 * 
 */

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos', ProductoController::class, 'index');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Producto/{id}', ProductoController::class, 'show');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos/filtro/{campo}/{valor}', ProductoController::class, 'filtro');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos/filtro/nuevos', ProductoController::class, 'nuevos');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Productos/filtro/top-ventas', ProductoController::class, 'topVentas');

/**
 * 
 * rutas de carrito
 * 
 * 1. carrito
 * 
 */
$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Carrito', CarritoController::class, 'mostrar');

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

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/perfil', UsuarioController::class, 'perfil');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/facturas', UsuarioController::class, 'facturas');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/pedidos', UsuarioController::class, 'pedidos');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/pedido/{id}', UsuarioController::class, 'pedido');

/**
 * Manejo de sesiones
 * 
 * 1. registro
 * 2. iniciar
 * 3. cerrar
 * 
 */

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/registro', UsuarioController::class, 'registro');

$router->addRoute('POST', $_ENV["APP_DIRECTORY"].'/registro', UsuarioController::class, 'registro_post');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/iniciar', UsuarioController::class, 'iniciar');

$router->addRoute('POST', $_ENV["APP_DIRECTORY"].'/iniciar', UsuarioController::class, 'iniciar_post');

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/cerrar', UsuarioController::class, 'cerrar');


/**
 * ejecucion de la ruta solicitada
 */
$router->dispatch();

<?php

namespace App\Core;

use App\Core\Router;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ContactoController;

$router = new Router();

$router->addRoute('GET', $_ENV["APP_DIRECTORY"].'/Home', IndexController::class, 'index');
$router->dispatch();
